<?php

namespace App\Http\Controllers\Emala\API\Customer;

use App\Exceptions\InsufficientBalanceException;
use App\Http\Controllers\Controller;
use App\Models\Beneficiary;
use App\Models\Cashier;
use App\Models\Customer;
use App\Models\Sender;
use App\Models\Transaction;
use App\Models\Transfer;
use Illuminate\Database\QueryException;
use Illuminate\Http\Request;

class OperationController extends Controller
{
    public function deposit($amount, $currency, $customerId, $cashierId, $walletType)
    {
        $customer = Customer::findOrFail($customerId);
        $customerWallets = $customer->customer_wallet;
        $description = "Dépôt";
        $senderName = $customer->firstname.' '.$customer->name; 
        $senderPhone = $customer->phone; 
        $receiverName = $customer->firstname.' '.$customer->name; 
        $receiverPhone = $customer->phone;

        $fees = 0;
        $status = "Réussi";

        try {
            $targetWallet = $customerWallets->where('wallet_type', $walletType)
                ->where('wallet_currency', $currency)
                ->first();

            if (!$targetWallet) {
                throw new \Exception('Portefeuille introuvable');
            }

            $message = $targetWallet->creditBalance($amount, $currency);

            $cashier = Cashier::findOrFail($cashierId);
            $cashier->debit($amount, $currency);

            $this->transaction($status, $senderName, $senderPhone, $receiverName, $receiverPhone, $amount, $fees, $currency, $cashierId, $description);

            return response()->json(['message' => $message, 'status' => true]);
        } catch (ModelNotFoundException $e) {
            return response()->json(['message' => 'Ressource introuvable'], 404);
        } catch (\Exception $e) {
            $failedStatus = "Échoué";
            $this->transaction($failedStatus, $senderName, $senderPhone, $receiverName, $receiverPhone, $amount, $fees, $currency, $cashierId, $description);
            return response()->json(['message' => $e->getMessage(), 'status' => false], 400);
        }
    }

    public function withdraw($amount, $currency, $customerId, $cashierId, $walletType, $fees)
    {
        $customer = Customer::findOrFail($customerId);
        $customerWallets = $customer->customer_wallet;
        $description = "Retrait";
        $senderName = $customer->firstname.' '.$customer->name; 
        $senderPhone = $customer->phone; 
        $receiverName = $customer->firstname.' '.$customer->name; 
        $receiverPhone = $customer->phone;

        $status = "Réussi";

        try {
            $targetWallet = $customerWallets->where('wallet_type', $walletType)
                ->where('wallet_currency', $currency)
                ->first();

            if (!$targetWallet) {
                throw new \Exception('Portefeuille introuvable');
            }

            $message = $targetWallet->debitBalance($amount, $currency);

            $cashier = Cashier::findOrFail($cashierId);
            $cashier->credit($amount, $currency);

            $this->transaction($status, $senderName, $senderPhone, $receiverName, $receiverPhone, $amount, $fees, $currency, $cashierId, $description);

            return response()->json(['message' => $message, 'status' => true]);
        } catch (ModelNotFoundException $e) {
            return response()->json(['message' => 'Ressource introuvable'], 404);
        } catch (InsufficientBalanceException $e) {
            $failedStatus = "Échoué";
            $this->transaction($failedStatus, $senderName, $senderPhone, $receiverName, $receiverPhone, $amount, $fees, $currency, $cashierId, $description);
            return response()->json(['message' => $e->getMessage(), 'status' => false], 400);
        } catch (\Exception $e) {
            $failedStatus = "Échoué";
            $this->transaction($failedStatus, $senderName, $senderPhone, $receiverName, $receiverPhone, $amount, $fees, $currency, $cashierId, $description);
            return response()->json(['message' => $e->getMessage(), 'status' => false], 400);
        }
    }

    public function transfer($amount, $fees, $currency, $senderCustomerId, $receiverCustomerId, $cashierId, $walletType)
    {
        $sender = Customer::findOrFail($senderCustomerId);
        $receiver = Customer::findOrFail($receiverCustomerId);
        $description = "transfert-electronic";

        $senderName = $sender->firstname.' '.$sender->name; 
        $senderPhone = $sender->phone; 
        $receiverName = $receiver->firstname.' '.$receiver->name; 
        $receiverPhone = $receiver->phone;
    
        $status = "Réussi";


        $total = $amount + $fees;

        $senderWallet = $sender->customer_wallet->where('wallet_type', $walletType)->where('wallet_currency', $currency)->first();
        $receiverWallet = $receiver->customer_wallet->where('wallet_type', $walletType)->where('wallet_currency', $currency)->first();

        if (!$senderWallet || !$receiverWallet) {
            return response()->json(['message' => 'Sender or receiver wallet not found'], 404);
        }

        // Débit du compte client émetteur
        $senderWallet->debitBalance($total, $currency);

        // Crédit du compte client bénéficiaire
        $receiverWallet->creditBalance($amount, $currency);

        // Mettre à jour le solde du caissier associé à l'agence spécifiée
        $cashier = Cashier::findOrFail($cashierId);
        $cashier->credit($fees, $currency);

        $this->transaction($status, $senderName, $senderPhone, $receiverName, $receiverPhone, $amount, $fees, $currency, $cashierId, $description);


        // Consolider les fonds
        // $cashier->agency->consoliderFonds();

        return response()->json(['message' => 'Transfer successful']);
    }

    public function cashTransfer($senderName, $receiverName, $senderPhone, $receiverPhone, $amount, $fees, $currency, $cashierId)
    {
        $total = $amount + $fees;
        $description = "transfert-cash";
        $sender = Customer::where('phone', $senderPhone)->first();

        $status = "Réussi";

        if (!$sender) {

            $sender = new Sender();
            $sender->name = $senderName;
            $sender->phone_number = $senderPhone;
            $sender->save();
        }
    
        // Gestion des données du bénéficiaire (par exemple, recherche ou création d'un profil de bénéficiaire)
        $receiver = Customer::where('phone', $receiverPhone)->first();

        if (!$receiver) {
            // Le bénéficiaire n'existe pas, création d'un nouveau profil de bénéficiaire
            $receiver = new Beneficiary();
            $receiver->name = $receiverName;
            $receiver->phone_number = $receiverPhone;
            $receiver->save();
        }

        // Enregistrer le transfert et les détails dans la base de données
        $cashier = Cashier::findOrFail($cashierId);
        $cashier->debit($total, $currency);

        $transfer = new Transfer();
        $transfer->sender_name = $senderName;
        $transfer->receiver_name = $receiverName;
        $transfer->amount = $amount;
        $transfer->fees = $fees;
        $transfer->currency = $currency;
        $transfer->cashier_id = $cashierId;
        // Génération du code de transfert
        $transferCode = $this->generateTransferCode(); // Fonction à implémenter pour générer un code unique
        $transfer->code = $transferCode;

        $transfer->save();

        $this->transaction($status, $senderName, $senderPhone, $receiverName, $receiverPhone, $amount, $fees, $currency, $cashierId, $description);


        return response()->json(['message' => 'Transfert effectué avec succes! Le code de retrait est '.$transferCode,'status' => true]);

    }


    function generateTransferCode()
    {
        $timestamp = time(); // Obtient le timestamp actuel
        $random = mt_rand(1000, 9999); // Génère un nombre aléatoire à 4 chiffres
        $code = $timestamp . $random; // Combine le timestamp et le nombre aléatoire
        return $code;
    }


    function verifyIdentity($senderName)
    {
        // Exemple de logique de vérification d'identité
        // Cette implémentation est fictive et doit être adaptée à vos besoins réels

        // Vérifier si l'émetteur a fourni une pièce d'identité valide
        $isValidIdentity = checkIdentityDocument($senderName);

        return $isValidIdentity;
    }

    function checkIdentityDocument($senderName)
    {
        // Exemple de vérification d'une pièce d'identité
        // Cette implémentation est fictive et doit être adaptée à vos besoins réels

        // Vérifier si le nom de l'émetteur correspond à une pièce d'identité valide
        $isValidIdentity = isNameValid($senderName);

        return $isValidIdentity;
    }

    function isNameValid($senderName)
    {
        // Exemple de vérification du nom de l'émetteur
        // Cette implémentation est fictive et doit être adaptée à vos besoins réels

        // Vérifier si le nom de l'émetteur correspond à une pièce d'identité valide dans votre système
        $validNames = ['John Doe', 'Jane Smith', 'Alice Johnson'];

        $isNameValid = in_array($senderName, $validNames);

        return $isNameValid;
    }

    public function transaction($status, $senderName, $senderPhone, $receiverName, $receiverPhone, $amount, $fees, $currency, $cashierId, $description)
    {
        try {
            $cashier = Cashier::findOrFail($cashierId);
    
            $transaction = new Transaction();
            $transaction->sender_name = $senderName;
            $transaction->sender_phone = $senderPhone;
            $transaction->receiver_name = $receiverName;
            $transaction->receiver_phone = $receiverPhone;
            $transaction->amount = $amount;
            $transaction->fees = $fees;
            $transaction->currency = $currency;
            $transaction->cashier_id = $cashierId;
            $transaction->status = $status;
            $transaction->agence_id = $cashier->agency->id;
            $transaction->reference = $this->reference($description);
            $transaction->category =$description;
            $transaction->save();
    
            // Gérer d'autres opérations liées à la transaction
    
        } catch (QueryException $e) {
            return response()->json(['message' => 'Erreur lors de la sauvegarde de la transaction'], 500);
        } catch (ModelNotFoundException $e) {
            return response()->json(['message' => 'Ressource introuvable'], 404);
        } catch (\Exception $e) {
            return response()->json(['message' => $e->getMessage()], 400);
        }
    }

    public function reference($descritption) {
        $characters = '0123456789';
        $length = 8;
        $charactersLength = strlen($characters);
        $randomString = '';
        if ($descritption == "transfert-cash") {
            $randomString = 'TC';
        }
        elseif ($descritption == "transfert-electronic") {
            $randomString = 'TE';
        }

        elseif ($descritption == "dépôt") {
            $randomString = 'D';
        }
        elseif ($descritption == "retrait") {
            $randomString = 'R';
        }
        elseif ($descritption == "emprunt") {
            $randomString = 'E';
        }

        for ($i = 0; $i < $length; $i++) {
            $randomString .= $characters[rand(0, $charactersLength - 1)];
        }
        return $randomString;
    }

    
}
