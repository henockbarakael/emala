<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Http\Controllers\GenerateIdController;
use App\Http\Controllers\generateReferenceController;
use App\Http\Controllers\VerifyNumberController;
use App\Models\Account;
use App\Models\Branche;
use App\Models\CashRegister;
use App\Models\EmalaTransfert;
use App\Models\RechargeRequest;
use App\Models\Ticket;
use App\Models\TiroirCaisse;
use App\Models\TransactionLimit;
use App\Models\User;
use App\Models\Wallet;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use SebastianBergmann\Type\TrueType;

class Initialize extends Controller
{
    public function todayDate(){
        Carbon::setLocale('fr');
        $todayDate = Carbon::now()->format('Y-m-d H:i:s');
        return $todayDate;
    }
    public function activity_log($activityLog){
        DB::table('user_activity_logs')->insert($activityLog);
    }
    public function customer_deposit($customer_number,$compte,$amount,$fees,$currency,$payment_method){
        $descritption ="depot";
        $default = new generateReferenceController;
        $reference = $default->reference($descritption);
        $sender_phone = Auth::user()->phone_number;
        
        $gateway = $this->getPaymentMethodID($payment_method);
        if ($sender_phone == $customer_number) {
            $sender_number = $customer_number;
        }
        else {
            $sender_number = Auth::user()->phone_number;
        }
        $customer_id = $this->getCustomerID($customer_number);
        $account = $this->verifyAccount($compte,$customer_id,$currency);
        
        if ($account['success'] == true) {
            $credit = $this->credit_customer($currency, $amount, $compte, $customer_number);
            
            if ($credit['success'] == true) {
              
                $status = "Succès";
                $currency_id = $this->getCurrencyID($currency);
                $deposit = $this->table_deposits($customer_id,$amount,$currency_id,$reference,$fees,$gateway,$status, $compte);
               
                if ($deposit['success'] == true) {
                    $status = "Succès";
                    $note = "Dépôt effectué avec succès!";
                    $action = "credit";
                    $this->table_transactions($reference,$amount,$currency,$sender_number,$customer_number,$descritption,$note,$action,$status,$gateway);
                    return $deposit;
                }
                else {
                    $status = "Echoué";
                    $note = $deposit['message'];
                    $action = "credit";
                    $this->table_transactions($reference,$amount,$currency,$sender_number,$customer_number,$descritption,$note,$action,$status,$gateway);
                    return $deposit;
                }
                
            }
            else {
                $response = [
                    'success' => false,
                    'message' => "Pour une raison inconnue, le dépôt n'a pas abouti !",
                    'status' => "Failed",
                ];
                return $response;
            }
        }
        else {
            return $account;
        }
        

    }
    public function customer_withdrawal($customer_number,$amount,$fees,$currency,$compte,$payment_method){

        $descritption ="retrait";
        $default = new generateReferenceController;
        $reference = $default->reference($descritption);
        $sender_phone = Auth::user()->phone_number;

        if ($sender_phone == $customer_number) {
            $sender_number = $customer_number;
        }
        else {
            $sender_number = Auth::user()->phone_number;
        }
        
        $debit = $this->debit_customer($currency, $amount, $fees, $compte, $customer_number);
        $gateway = $this->getPaymentMethodID($payment_method);
        if ($debit['success'] == true) {
            $customer_id = $this->getCustomerID($customer_number);
            $status = "Succès";
            $currency_id = $this->getCurrencyID($currency);
            $retrait = $this->table_withdrawals($customer_id,$amount,$currency_id,$reference,$fees,$gateway,$status);
            if ($retrait['success'] == true) {
                $status = "Succès";
                $note = "Retrait effectué avec succès!";
                $action = "debit";
                $this->table_transactions($reference,$amount,$currency,$sender_number,$customer_number,$descritption,$note,$action,$status,$gateway);
                return $retrait;
            }
            else {
                $status = "Echoué";
                $note = $retrait['message'];
                $action = "debit";
                $this->table_transactions($reference,$amount,$currency,$sender_number,$customer_number,$descritption,$note,$action,$status,$gateway);
                return $retrait;
            }
            
        }
        else {
            $response = [
                'success' => false,
                'message' => "Pour une raison inconnue, l'argent n'a pas pu être retiré de votre wallet !",
                'status' => "Failed",
            ];
            return $response;
        }
        
    }
    public function customer_extenal_transfer($snumber,$sender_first,$sender_last,$rnumber,$receiver_first,$receiver_last,$amount,$fees,$currency,$payment_method){
        $type ="transfert-externe";
        $total_amount = $amount + $fees;
        $default = new generateReferenceController;
        $reference = $default->reference($type);
        $gateway = $this->getPaymentMethodID($payment_method);
        $action = "credit";
        $currency_id = $this->getCurrencyID($currency);
        
        if (Auth::user()->role_name == "Admin" || Auth::user()->role_name == "Manager") {
            $this->creditGerantTiroir($currency, $total_amount);
            $credit = $this->creditGerantAccount($currency, $total_amount);

            if ($credit['success'] == true) {
                $done = $this->table_external_transferts($snumber,$sender_first,$sender_last,$amount,$fees,$currency,$reference,$rnumber,$receiver_first,$receiver_last);
                if ($done) {
                    $status = "Succès";
                    $note = "Transfert effectué avec succès!";
                    $this->table_transfers($snumber,$rnumber,$amount,$currency_id,$reference,$fees,$gateway,$status, $note);
                    $this->table_transactions($reference,$amount,$currency,$snumber,$rnumber,$type,$note,$action,$status,$gateway);
                    $response = [
                        'success' => true,
                        'resultat' => 1,
                        'message' => "Transfert effectué avec succès",
                        'status' => "Successful",
                    ];
                    return $response;
                }
            }
            else {
                $status = "Echouée";
                $note = $credit['message'];
                $this->table_transactions($reference,$amount,$currency,$snumber,$rnumber,$type,$note,$action,$status,$gateway);
                return $credit;
            }
        }
    }

    public function customer_internal_transfer($customer_number,$compte_1,$compte_2,$amount,$fees,$currency,$payment_method){
        $type ="transfert";
        $total_amount = $amount + $fees;
        $default = new generateReferenceController;
        $reference = $default->reference($type);
        $gateway = $this->getPaymentMethodID($payment_method);
        $action = "credit";
        $currency_id = $this->getCurrencyID($currency);
        $customer_id = $this->getCustomerID($customer_number);
        $account_1 = $this->verifyAccount($compte_1,$customer_id,$currency);
        $account_2 = $this->verifyAccount($compte_2,$customer_id,$currency);

        if ($account_1['success'] == true && $account_2['success'] == true) {
            $debit = $this->debit_customer($currency, $amount, $fees, $compte_1, $customer_number);
            if ($debit['success'] == true) {
                $credit = $this->credit_customer($currency, $amount, $compte_2, $customer_number);
                if ($credit['success'] == true) {
                    $status = "Succès";
                    $note = "Transfert effectué avec succès!";
                    $this->table_transfers($customer_number,$customer_number,$amount,$currency_id,$reference,$fees,$gateway,$status, $note);
                    $this->table_transactions($reference,$amount,$currency,$customer_number,$customer_number,$type,$note,$action,$status,$gateway);
                    $response = [
                        'success' => true,
                        'resultat' => 1,
                        'message' => "Transfert effectué avec succès",
                        'status' => "Successful",
                    ];
                    return $response;
                }
                else {
                    return $credit;
                }
                
            }
            else {
                $response = [
                    'success' => false,
                    'message' => "Pour une raison inconnue, le transfert n'a pas abouti !",
                    'status' => "Failed",
                ];
                return $response;
            }
        }
        elseif ($account_1['success'] == false) {
            return $account_1;
        }
        elseif ($account_2['success'] == false) {
            return $account_2;
        }
    }

    public function transaction_limit($transaction_type,$min_amount,$max_amount,$currency,$limit_by_day){
        $todayDate = $this->todayDate();
        $data = [
            'type_transaction' =>$transaction_type,
            'min_amount' => $min_amount,
            'max_amount' => $max_amount,
            'currency' => $currency,
            'limit_by_day' => $limit_by_day,
            'created_at' => $todayDate,
            'updated_at' => $todayDate
        ];
        $done = DB::table('transaction_limits')->insert($data);
        if ($done) {
            $response = [
                'success' => true,
                'message' => "Limite ajoutée avec succès!",
                'status' => "Successful",
            ];
            return $response;
        }
        else {
            $response = [
                'success' => false,
                'message' => "Opération echouée!
                ",
                'status' => "Failed",
            ];
            return $response;
        }
    }

    public function customer_virement($sender_number,$customer_number,$compte,$amount,$fees,$currency,$payment_method){
        $type ="virement";
        $total_amount = $amount + $fees;
        $default = new generateReferenceController;
        $reference = $default->reference($type);
        $gateway = $this->getPaymentMethodID($payment_method);
        $action = "credit";
        $currency_id = $this->getCurrencyID($currency);
       
        $debit = $this->debit_customer($currency, $amount, $fees, $compte, $sender_number);
        if ($debit['success'] == true) {
            $credit = $this->credit_customer($currency, $amount, $compte, $customer_number);
            if ($credit['success'] == true) {
                $status = "Succès";
                $note = "Virement effectué avec succès!";
                $this->table_transfers($sender_number,$customer_number,$amount,$currency_id,$reference,$fees,$gateway,$status, $note);
                $this->table_transactions($reference,$amount,$currency,$sender_number,$customer_number,$type,$note,$action,$status,$gateway);
                $response = [
                    'success' => true,
                    'resultat' => 1,
                    'message' => "Virement effectué avec succès",
                    'status' => "Successful",
                ];
                return $response;
            }
            else {
                return $credit;
            }
            
        }
        else {
            $response = [
                'success' => false,
                'message' => "Pour une raison inconnue, le virement n'a pas abouti !",
                'status' => "Failed",
            ];
        }
    }

    public function debit_customer($currency, $amount, $fees, $compte, $customer_number){
        #On récupère la balance du client par devise
        $balance = $this->getCustomerBalance($customer_number, $currency);
        #On récupère l'ID du client
        $customer_id = $this->getCustomerID($customer_number);
        $todayDate = $this->todayDate();
        $total = $amount + $fees;
        #On vérifie si l'opération peut-être effectuée
        if ($amount > $balance ) {
            $response = [
                'success' => false,
                'message' => "Solde insuffisant!",
                'status' => "Failed",
            ];
            return $response;
        }
        else {
            #On vérifie l'existence du compte client
            $account = $this->verifyAccount($compte,$customer_id,$currency);
            if ($account['success'] == true) {
                $data = ['wallet_balance' => $balance - $total];
                $update = DB::connection('mysql2')->table('wallets')->where('wallet_type', $compte)->where('wallet_currency', $currency)->where('customer_id', $customer_id)->update($data);
                $activityLog = [
                    'fullname'   => Auth::user()->firstname." ".Auth::user()->lastname,
                    'user_phone'   => Auth::user()->phone_number,
                    'activity'   => "vient de debiter le client ".$customer_number,
                    'updated_at'   => $todayDate,
                ];
                if ($update) {
                    $this->activity_log($activityLog);
                    $response = [
                        'success' => true,
                        'message' => "Wallet debité avec succès!",
                        'status' => "Successful",
                    ];
                    return $response;
                }
                else {
                    $activityFailed= [
                        'fullname'   => Auth::user()->firstname." ".Auth::user()->lastname,
                        'user_phone'   => Auth::user()->phone_number,
                        'activity'   => "a tenté de débiter le client ".$customer_number,
                        'updated_at'   => $todayDate,
                    ];
                    $this->activity_log($activityFailed);
                    $response = [
                        'success' => false,
                        'message' => "Une erreur est survenue lors du debit du compte",
                        'status' => "Failed",
                    ];
                    return $response;
                }

            }
            else {
                return $account;
            }
        }
    }

    public function credit_customer($currency, $amount, $compte, $customer_number){
        #On récupère la balance du client par devise
        $balance = $this->getCustomerBalance($customer_number, $currency);
        #On récupère l'ID du client
        $customer_id = $this->getCustomerID($customer_number);
        $todayDate = $this->todayDate();
        #On vérifie l'existence du compte client
        $account = $this->verifyAccount($compte,$customer_id,$currency);
        if ($account['success'] == true) {
            $data = ['wallet_balance' => $balance + $amount];
            $update = DB::connection('mysql2')->table('wallets')->where('wallet_type', $compte)->where('wallet_currency', $currency)->where('customer_id', $customer_id)->update($data);
            $activityLog = [
                'fullname'   => Auth::user()->firstname." ".Auth::user()->lastname,
                'user_phone'   => Auth::user()->phone_number,
                'activity'   => "vient de créditer le client ".$customer_number,
                'updated_at'   => $todayDate,
            ];
            if ($update) {
                $this->activity_log($activityLog);
                $response = [
                    'success' => true,
                    'message' => "Wallet créditer avec succès!",
                    'status' => "Successful",
                ];
                return $response;
            }
            else {
                $activityFailed= [
                    'fullname'   => Auth::user()->firstname." ".Auth::user()->lastname,
                    'user_phone'   => Auth::user()->phone_number,
                    'activity'   => "a tenté de créditer le client ".$customer_number,
                    'updated_at'   => $todayDate,
                ];
                $this->activity_log($activityFailed);
                $response = [
                    'success' => false,
                    'message' => "Une erreur est survenue lors du recharge du compte du client",
                    'status' => "Failed",
                ];
                return $response;
            }

        }
        else {
            return $account;
        }
    }

    public function verifyCustomer($customer_number){
        $users = DB::connection('mysql2')->table('users')->where('phone', $customer_number)->count();
        if ($users >= 1) {
            $response = ['success' => true ];
            return $response;
        }
        else {
            $response = ['success' => false ];
            return $response;
        }
    }

    public function verify_saving_account($compte,$currency,$customer_number){
        $user_id = $this->getCustomerID($customer_number);
        $account = DB::connection('mysql2')->table('wallets')->where('wallet_type', $compte)->where('wallet_currency', $currency)->where('customer_id', $user_id)->first();
        if ($account == null) {
            $response = [
                'success' => false,
                'message' => "Le client n'a pas de compte epargne",
                'status' => "Failed",
            ];
            return $response;
        }
        else {
            $response = [
                'success' => true,
                'message' => "Compte epargne existe!",
                'status' => "Successful",
            ];
            return $response;
        }

    }

    public function verifyAccount($compte,$user_id,$currency){
        $account = DB::connection('mysql2')->table('wallets')->where('wallet_type', $compte)->where('wallet_currency', $currency)->where('customer_id', $user_id)->first();
        if ($compte == "saving") {
            if ($account == null) {
                $response = [
                    'success' => false,
                    'message' => "Le client n'a pas de compte epargne",
                    'status' => "Failed",
                ];
                return $response;
            }
            else {
                $response = [
                    'success' => true,
                    'message' => "Compte epargne existe!",
                    'status' => "Successful",
                ];
                return $response;
            }
        }
        elseif ($compte == "current") {
            if ($account == null) {
                $response = [
                    'success' => false,
                    'message' => "Le client n'existe pas dans le système",
                    'status' => "Failed",
                ];
                return $response;
            }
            else {
                $response = [
                    'success' => true,
                    'message' => "Compte principal existe!",
                    'status' => "Successful",
                ];
                return $response;
            }
        }

    }

    public function user_activity_log($UseractivityLog){
        DB::table('user_activity_logs')->insert($UseractivityLog);
    }

    public function getCustomerID($customer_number){
        $users = DB::connection('mysql2')->table('users')->where('phone', $customer_number)->first();
        $user_id = $users->id;
        return $user_id;
    }

    public function getCurrencyByID($currency_id){
        $currency = DB::table('currencies')->where('id',$currency_id)->first();
        $response = $currency->name;
        return $response;
    }

    public function getCurrencyID($currency){
        $currency = DB::table('currencies')->where('name',$currency)->first();
        $response = $currency->id;
        return $response;
    }
    public function getPaymentMethodID($payment_method){
        
        $method = DB::table('payment_methods')->where('method',$payment_method)->first();
        $response = $method->id;
        return $response;
    }

    public function getCustomerBalance($customer_number, $currency){
        $user_id = $this->getCustomerID($customer_number);
        $current_wallet = DB::connection('mysql2')->table('wallets')->where('wallet_type', 'current')->where('wallet_currency', $currency)->where('customer_id', $user_id)->first();
        $balance = $current_wallet->wallet_balance;
        return $balance;
    }

    public function getWalletID($customer_id, $currency, $wallet_type){

        $devise = $this->getCurrencyByID($currency);

        $wallet = DB::connection('mysql2')->table('wallets')->where('wallet_type', $wallet_type)->where('wallet_currency', $devise)->where('customer_id', $customer_id)->first();
    
        $response = $wallet->id;
        return $response;
    }

    public function table_transactions($reference,$amount,$currency,$sender_phone,$receiver_phone,$type,$note,$action,$status,$gateway){
        $todayDate = $this->todayDate();
        $currency_id = $this->getCurrencyID($currency);
        $user_id = Auth::user()->id;
        $branche_id = $this->branche_id(Auth::user()->id);
        if (Auth::user()->role_name == "Cashier") {
            $branche_id = $this->cashierBrancheId($user_id);
        } 
        $datafortrx = [
            'sender_phone'=>$sender_phone,
            'amount'=>$amount,
            'currency_id'=>$currency_id,
            'reference'=>$reference,
            'receiver_phone'=>$receiver_phone,
            'transaction_date'=>$todayDate,
            'status'=>$status,
            'type'=>$type,
            'action'=>$action,
            'branche_id' => $branche_id,
            'user_id' => $user_id,
            'payment_method' => $gateway,
            'note'=>$note,
            'created_at' => $todayDate,
            'updated_at' => $todayDate
        ];

        DB::table('transactions')->insert($datafortrx);
    }

    public function table_withdrawals($customer_id,$amount,$currency_id,$reference,$fees,$gateway,$status){
        $todayDate = $this->todayDate();
        $user_id = Auth::user()->id;
        $branche_id = $this->branche_id(Auth::user()->id);
        if (Auth::user()->role_name == "Cashier") {
            $branche_id = $this->cashierBrancheId($user_id);
        }
        $dataToInsert = [
            'customer_id' => $customer_id,
            'amount' => $amount,
            'currency_id' => $currency_id,
            'transaction_id' => $reference,
            'fees' => $fees,
            'type' => $gateway,
            'branche_id' => $branche_id,
            'user_id' => $user_id,
            'status' => $status,
            'payment_method' => $gateway,
            'created_at' => $todayDate,
            'updated_at' => $todayDate
        ];
        $success = DB::table('withdrawals')->insert($dataToInsert);
        if ($success) {
            $response = [
                'success' => true,
                'message' => "Demande de retrait effectuée avec succès! Réf. : ".$reference,
                'status' => "Successful",
            ];
            return $response;
        }
        else {
            $response = [
                'success' => false,
                'message' => "Votre demande de retrait a echoué !",
                'status' => "Failed",
            ];
            return $response;
        }
    }
    
    public function table_deposits($customer_id,$amount,$currency_id,$reference,$fees,$gateway,$status, $wallet_type){
        $todayDate = $this->todayDate();
        $user_id = Auth::user()->id;
        $branche_id = $this->branche_id(Auth::user()->id);
        if (Auth::user()->role_name == "Cashier") {
            $branche_id = $this->cashierBrancheId($user_id);
        }
    
        $wallet_id = $this->getWalletID($customer_id, $currency_id, $wallet_type);
       
        $dataToInsert = [
            'wallet_id' => $wallet_id,
            'customer_id' => $customer_id,
            'amount' => $amount,
            'currency_id' => $currency_id,
            'transaction_id' => $reference,
            'fees' => $fees,
            'branche_id' => $branche_id,
            'user_id' => $user_id,
            'type' => $gateway,
            'status' => $status,
            'created_at' => $todayDate,
            'updated_at' => $todayDate
        ];
        $success = DB::table('deposits')->insert($dataToInsert);
        if ($success) {
            $response = [
                'success' => true,
                'message' => "Dépôt effectué avec succès! Réf. : ".$reference,
                'status' => "Successful",
            ];
            return $response;
        }
        else {
            $response = [
                'success' => false,
                'message' => "Votre demande de dépôt a echoué !",
                'status' => "Failed",
            ];
            return $response;
        }
    }

    public function table_transfers($sender_phone,$receiver_phone,$amount,$currency_id,$reference,$fees,$gateway,$status, $note){
        $todayDate = $this->todayDate();
        $user_id = Auth::user()->id;
        $branche_id = $this->branche_id(Auth::user()->id);
        if (Auth::user()->role_name == "Cashier") {
            $branche_id = $this->cashierBrancheId($user_id);
        }
        $dataToInsert = [
            'sender_phone' => $sender_phone,
            'receiver_phone' => $receiver_phone,
            'amount' => $amount,
            'currency_id' => $currency_id,
            'fees' => $fees,
            'branche_id' => $branche_id,
            'user_id' => $user_id,
            'reference' => $reference,
            'type' => $gateway,
            'note' => $note,
            'status' => $status,
            'created_at' => $todayDate,
            'updated_at' => $todayDate
        ];
        $success = DB::table('transfers')->insert($dataToInsert);
        if ($success) {
            $response = [
                'success' => true,
                'message' => "Transfert effectué avec succès! Réf. : ".$reference,
                'status' => "Successful",
            ];
            return $response;
        }
        else {
            $response = [
                'success' => false,
                'message' => "Votre demande de transfert a echoué !",
                'status' => "Failed",
            ];
            return $response;
        }
    }
    public function table_external_transferts($sender_phone,$sender_first,$sender_last,$amount,$fees,$currency,$reference,$receiver_phone,$receiver_first,$receiver_last){
        $todayDate = $this->todayDate();
        $user_id = Auth::user()->id;
        $branche_id = $this->branche_id(Auth::user()->id);
        $data = [
            'user_id' => $user_id,
            'branche_id' => $branche_id,
            'payment_method' => 1,
            'sender_phone' => $sender_phone,
            'sender_first' => $sender_first,
            'sender_last' => $sender_last,
            'amount' => $amount,
            'fees' => $fees,
            'currency' => $currency,
            'reference' => $reference,
            'receiver_phone' => $receiver_phone,
            'receiver_first' => $receiver_first,
            'receiver_last' => $receiver_last,
            'status' => "Envoyé",
            'created_at'   => $todayDate,
            'updated_at'   => $todayDate,
        ];
        $success = DB::table('external_transfers')->insert($data);
        if ($success) {
            $response = [
                'success' => true,
                'message' => "Transfert effectué avec succès! Réf. : ".$reference,
                'status' => "Successful",
            ];
            return $response;
        }
        else {
            $response = [
                'success' => false,
                'message' => "Votre demande de transfert a echoué !",
                'status' => "Failed",
            ];
            return $response;
        }
    }

    public function create_wallet($institution){
        $todayDate = $this->todayDate();
        $data_1 = [
            'bank_id'   => $institution,
            'balance'   => 0.00,
            'currency'   => "CDF",
            'created_at'   => $todayDate,
            'updated_at'   => $todayDate,
        ];
        $data_2 = [
            'bank_id'   => $institution,
            'balance'   => 0.00,
            'currency'   => "USD",
            'created_at'   => $todayDate,
            'updated_at'   => $todayDate,
        ];
        $activityLog = [
            'fullname'   => Auth::user()->firstname." ".Auth::user()->lastname,
            'user_phone'   => Auth::user()->phone_number,
            'user_id'   => Auth::user()->id,
            'activity'   => "Vient de créer le wallet ".$institution,
            'updated_at'   => $todayDate,
        ];

        $save_1 = DB::table('wallets')->insert($data_1);
        $save_2 = DB::table('wallets')->insert($data_2);

        if ($save_1 && $save_2) {
            $this->activity_log($activityLog);
            $response = [
                'success' => true,
                'message' => "Wallet créer avec succès",
                'status' => "Successful",
            ];
            return $response;
        }
        else {
            $activityFailed= [
                'fullname'   => Auth::user()->firstname." ".Auth::user()->lastname,
                'user_phone'   => Auth::user()->phone_number,
                'activity'   => "a tenté de créer le wallet ".$institution,
                'updated_at'   => $todayDate,
            ];
            $this->activity_log($activityFailed);
            $response = [
                'success' => false,
                'message' => "Une erreur est survenue lors de la création du wallet",
                'status' => "Successful",
            ];
            return $response;
        }
    }
    public function topup_wallet($amount, $wallet_id,$currency){
        $wallet = DB::table('wallets')->where('id', $wallet_id)->first();
        $todayDate = $this->todayDate();

        $balance = $wallet->balance;
        $data = [
            'balance'    => $balance + $amount,
        ];
        $update = DB::table('wallets')->where('id', $wallet_id)->where('currency', $currency)->update($data);
        $user_id = Auth::user()->id;

        $activityLog = [
            'fullname'   => Auth::user()->firstname." ".Auth::user()->lastname,
            'user_phone'   => Auth::user()->phone_number,
            'user_id'   => Auth::user()->id,
            'activity'   => "Vient de recharger le wallet ".$wallet->id,
            'updated_at'   => $todayDate,
        ];
        if ($update) {
            $data = [
                'wallet_id' => $wallet->id,
                'user_id' => $user_id,
                'currency' => $currency,
                'amount' => $amount,
                'created_at'   => $todayDate,
                'updated_at'   => $todayDate,
            ];
            $save = DB::table('wallet_stories')->where('currency', $currency)->insert($data);
            $this->activity_log($activityLog);
            if ($save) {
                $response = [
                    'success' => true,
                    'resultat' => 1,
                    'message' => "Wallet rechargé avec succès",
                    'status' => "Successful",
                ];
                return $response;
            }
        }
        else {
            $activityFailed= [
                'fullname'   => Auth::user()->firstname." ".Auth::user()->lastname,
                'user_phone'   => Auth::user()->phone_number,
                'activity'   => "a tenté de recharger le wallet ".$wallet->id,
                'updated_at'   => $todayDate,
            ];
            $this->activity_log($activityFailed);
            $response = [
                'success' => false,
                'message' => "Une erreur est survenue lors de la création du wallet",
                'status' => "Successful",
            ];
            return $response;
        }
    }

    public function debitWallet($currency, $amount){
        $wallet = DB::table('wallets')->where('bank_id', 1)->where('currency', $currency)->first();
        $todayDate = $this->todayDate();
        $balance = $wallet->balance;

        if ($amount > $balance) {
            $response = [
                'success' => false,
                'message' => "Balance insuffisante!",
                'status' => "Failed",
            ];
            return $response;
        }
        else {
            $data = [
                'balance'    => $balance - $amount,
            ];
            $update = DB::table('wallets')->where('bank_id', 1)->where('currency', $currency)->update($data);
            $user_id = Auth::user()->id;

            $activityLog = [
                'fullname'   => Auth::user()->firstname." ".Auth::user()->lastname,
                'user_phone'   => Auth::user()->phone_number,
                'activity'   => "Vient de débiter le wallet Emala",
                'updated_at'   => $todayDate,
            ];
            if ($update) {
                $data = [
                    'wallet_id' => $wallet->id,
                    'user_id' => $user_id,
                    'currency' => $currency,
                    'amount' => $amount,
                    'created_at'   => $todayDate,
                    'updated_at'   => $todayDate,
                ];
                $save = DB::table('wallet_stories')->where('currency', $currency)->insert($data);
                $this->activity_log($activityLog);
                if ($save) {
                    $response = [
                        'success' => true,
                        'resultat' => 1,
                        'message' => "Wallet debité avec succès",
                        'status' => "Successful",
                    ];
                    return $response;
                }
            }
            else {
                $activityFailed= [
                    'fullname'   => Auth::user()->firstname." ".Auth::user()->lastname,
                    'user_phone'   => Auth::user()->phone_number,
                    'activity'   => "a tenté de debiter le wallet ".$wallet->id,
                    'updated_at'   => $todayDate,
                ];
                $this->activity_log($activityFailed);
                $response = [
                    'success' => false,
                    'message' => "Une erreur est survenue lors de la création du wallet",
                    'status' => "Successful",
                ];
                return $response;
            }
        }



    }

    public function debitGerantAccount($currency, $amount){
        $user_id = Auth::user()->id;
        $account = DB::table('accounts')->where('user_id', $user_id)->where('currency', $currency)->first();
        $todayDate = $this->todayDate();

        $balance = $account->balance;
        if ($amount > $balance) {
            $response = [
                'success' => false,
                'message' => "Balance insuffisante!",
                'status' => "Failed",
            ];
            return $response;
        }
        else {
            $data = [
                'balance'    => $balance - $amount,
            ];
            $update = DB::table('accounts')->where('user_id', $user_id)->where('currency', $currency)->update($data);
            $user_id = Auth::user()->id;

            $activityLog = [
                'fullname'   => Auth::user()->firstname." ".Auth::user()->lastname,
                'user_phone'   => Auth::user()->phone_number,
                'activity'   => "Vient d'approvisionner le compte ".$account->id,
                'updated_at'   => $todayDate,
            ];
            if ($update) {
                $data = [
                    'account_id' => $account->id,
                    'user_id' => $user_id,
                    'currency' => $currency,
                    'amount' => $amount,
                    'created_at'   => $todayDate,
                    'updated_at'   => $todayDate,
                ];
                $save = DB::table('account_stories')->where('currency', $currency)->insert($data);
                $this->activity_log($activityLog);
                if ($save) {
                    $response = [
                        'success' => true,
                        'resultat' => 1,
                        'message' => "Compte debité avec succès",
                        'status' => "Successful",
                    ];
                    return $response;
                }
            }
            else {
                $activityFailed= [
                    'fullname'   => Auth::user()->firstname." ".Auth::user()->lastname,
                    'user_phone'   => Auth::user()->phone_number,
                    'activity'   => "a tenté de débiter le compte ".$account->id,
                    'updated_at'   => $todayDate,
                ];
                $this->activity_log($activityFailed);
                $response = [
                    'success' => false,
                    'message' => "Une erreur est survenue lors de l'approvisionnement du compte",
                    'status' => "Failed",
                ];
                return $response;
            }
        }

    }

    public function creditGerantAccount($currency, $amount){
        $user_id = Auth::user()->id;
        $account = DB::table('accounts')->where('user_id', $user_id)->where('currency', $currency)->first();
        $todayDate = $this->todayDate();

        $balance = $account->balance;
        $data = [
            'balance'    => $balance + $amount,
        ];
        $update = DB::table('accounts')->where('user_id', $user_id)->where('currency', $currency)->update($data);
        $user_id = Auth::user()->id;

        $activityLog = [
            'fullname'   => Auth::user()->firstname." ".Auth::user()->lastname,
            'user_phone'   => Auth::user()->phone_number,
            'user_id'   => Auth::user()->id,
            'activity'   => "Vient de créditer le compte ".$account->id,
            'updated_at'   => $todayDate,
        ];
        if ($update) {
            $data = [
                'account_id' => $account->id,
                'user_id' => $user_id,
                'currency' => $currency,
                'amount' => $amount,
                'created_at'   => $todayDate,
                'updated_at'   => $todayDate,
            ];
            $save = DB::table('account_stories')->where('currency', $currency)->insert($data);
            $this->activity_log($activityLog);
            if ($save) {
                $response = [
                    'success' => true,
                    'resultat' => 1,
                    'message' => "Compte crédité avec succès",
                    'status' => "Successful",
                ];
                return $response;
            }
        }
        else {
            $activityFailed= [
                'fullname'   => Auth::user()->firstname." ".Auth::user()->lastname,
                'user_phone'   => Auth::user()->phone_number,
                'activity'   => "a tenté de créditer le compte ".$account->id,
                'updated_at'   => $todayDate,
            ];
            $this->activity_log($activityFailed);
            $response = [
                'success' => false,
                'message' => "Une erreur est survenue lors du credit du compte",
                'status' => "Failed",
            ];
            return $response;
        }
    }

    public function creditGerantTiroir($currency, $amount){
        $user_id = Auth::user()->id;
        $account = DB::table('tiroir_caisses')->where('user_id', $user_id)->where('currency', $currency)->first();
        $todayDate = $this->todayDate();

        $balance = $account->balance;
        $data = [
            'balance'    => $balance + $amount,
        ];
        $update = DB::table('tiroir_caisses')->where('user_id', $user_id)->where('currency', $currency)->update($data);
        $user_id = Auth::user()->id;

        $activityLog = [
            'fullname'   => Auth::user()->firstname." ".Auth::user()->lastname,
            'user_phone'   => Auth::user()->phone_number,
            'user_id'   => Auth::user()->id,
            'activity'   => "Vient de créditer le compte ".$account->id,
            'updated_at'   => $todayDate,
        ];
        if ($update) {
            $data = [
                'account_id' => $account->id,
                'user_id' => $user_id,
                'currency' => $currency,
                'amount' => $amount,
                'created_at'   => $todayDate,
                'updated_at'   => $todayDate,
            ];
            $save = DB::table('account_stories')->where('currency', $currency)->insert($data);
            $this->activity_log($activityLog);
            if ($save) {
                $response = [
                    'success' => true,
                    'resultat' => 1,
                    'message' => "Compte crédité avec succès",
                    'status' => "Successful",
                ];
                return $response;
            }
        }
        else {
            $activityFailed= [
                'fullname'   => Auth::user()->firstname." ".Auth::user()->lastname,
                'user_phone'   => Auth::user()->phone_number,
                'activity'   => "a tenté de créditer le compte ".$account->id,
                'updated_at'   => $todayDate,
            ];
            $this->activity_log($activityFailed);
            $response = [
                'success' => false,
                'message' => "Une erreur est survenue lors du credit du compte",
                'status' => "Failed",
            ];
            return $response;
        }
    }

    public function credit_account($currency, $amount, $account_id){
        // dd($account_id);
        $account = DB::table('accounts')->where('id', $account_id)->first();
        // dd($account);
        $todayDate = $this->todayDate();

        $balance = $account->balance;
        $data = [
            'balance'    => $balance + $amount,
        ];
        $update = DB::table('accounts')->where('id', $account_id)->where('currency', $currency)->update($data);
        $user_id = Auth::user()->id;

        $activityLog = [
            'fullname'   => Auth::user()->firstname." ".Auth::user()->lastname,
            'user_phone'   => Auth::user()->phone_number,
            'user_id'   => Auth::user()->id,
            'activity'   => "Vient de recharger le compte ".$account->id,
            'updated_at'   => $todayDate,
        ];
        if ($update) {
            $data = [
                'account_id' => $account->id,
                'user_id' => $user_id,
                'currency' => $currency,
                'amount' => $amount,
                'created_at'   => $todayDate,
                'updated_at'   => $todayDate,
            ];
            $save = DB::table('account_stories')->where('currency', $currency)->insert($data);
            $this->activity_log($activityLog);
            if ($save) {
                $response = [
                    'success' => true,
                    'resultat' => 1,
                    'message' => "Wallet rechargé avec succès",
                    'status' => "Successful",
                ];
                return $response;
            }
        }
        else {
            $activityFailed= [
                'fullname'   => Auth::user()->firstname." ".Auth::user()->lastname,
                'user_phone'   => Auth::user()->phone_number,
                'activity'   => "a tenté de recharger le compte ".$account->id,
                'updated_at'   => $todayDate,
            ];
            $this->activity_log($activityFailed);
            $response = [
                'success' => false,
                'message' => "Une erreur est survenue lors de la création du compte",
                'status' => "Failed",
            ];
            return $response;
        }
    }

    public function credit_tiroir($currency, $amount, $account_id){
        $account = DB::table('tiroir_caisses')->where('id', $account_id)->first();
        $todayDate = $this->todayDate();

        $balance = $account->balance;
        $data = [
            'balance'    => $balance + $amount,
        ];
        $update = DB::table('tiroir_caisses')->where('id', $account_id)->where('currency', $currency)->update($data);
        $user_id = Auth::user()->id;

        $activityLog = [
            'fullname'   => Auth::user()->firstname." ".Auth::user()->lastname,
            'user_phone'   => Auth::user()->phone_number,
            'user_id'   => Auth::user()->id,
            'activity'   => "Vient de recharger le compte ".$account->id,
            'updated_at'   => $todayDate,
        ];
        if ($update) {
            $data = [
                'account_id' => $account->id,
                'user_id' => $user_id,
                'currency' => $currency,
                'amount' => $amount,
                'created_at'   => $todayDate,
                'updated_at'   => $todayDate,
            ];
            $save = DB::table('tiroir_caisse_stories')->where('currency', $currency)->insert($data);
            $this->activity_log($activityLog);
            if ($save) {
                $response = [
                    'success' => true,
                    'resultat' => 1,
                    'message' => "Wallet rechargé avec succès",
                    'status' => "Successful",
                ];
                return $response;
            }
        }
        else {
            $activityFailed= [
                'fullname'   => Auth::user()->firstname." ".Auth::user()->lastname,
                'user_phone'   => Auth::user()->phone_number,
                'activity'   => "a tenté de recharger le compte ".$account->id,
                'updated_at'   => $todayDate,
            ];
            $this->activity_log($activityFailed);
            $response = [
                'success' => false,
                'message' => "Une erreur est survenue lors de la création du compte",
                'status' => "Failed",
            ];
            return $response;
        }
    }

    public function recharge_request($subject,$amount,$currency,$assigned_id){
        $branche_id = $this->branche_id(Auth::user()->id);
        $default = new GenerateIdController;
        $requestId = $default->requestID();

        if (Auth::user()->role_name == "Cashier") {
            
            $agence = Account::where('user_id',Auth::user()->id)->first();
            $branche_id = $agence->branche_id;
        }
        $recharge = RechargeRequest::create([
            'subject' => $subject,
            'amount' => $amount,
            'currency' => $currency,
            'assigned_id' => $assigned_id,
            'branche_id' => $branche_id,
            'requester_id' => Auth::user()->id,
            'request_id' => $requestId,
        ]);
        if ($recharge) {
            $response = [
                'success' => true,
                'resultat' => 1,
                'message' => "Demande de recharge envoyée avec succès!",
                'status' => "Successful",
            ];
            return $response;
        }
        else {
            $response = [
                'success' => false,
                'resultat' => 1,
                'message' => "Echec! La requêtte n'a pas pu être envoyée.",
                'status' => "Failed",
            ];
            return $response;
        }
    }

    

    public function ticket_request($subject,$message,$file_name,$assigned_id){
        $branche_id = $this->branche_id(Auth::user()->id);
        $default = new GenerateIdController;
        $requestId = $default->requestID();
        if (Auth::user()->role_name == "Cashier") {
            $agence = Account::where('user_id',Auth::user()->id)->first();
            $branche_id = $agence->branche_id;
        }
        $recharge =Ticket::create([
            'subject' => $subject,
            'message' => $message,
            'file' => $file_name,
            'receiver_id' => $assigned_id,
            'branche_id' => $branche_id,
            'sender_id' => Auth::user()->id,
            'ticket_id' => $requestId,
            'status' => "Envoyé",
        ]);
        if ($recharge) {
            $response = [
                'success' => true,
                'resultat' => 1,
                'message' => "Ticket envoyé avec succès!",
                'status' => "Successful",
            ];
            return $response;
        }
        else {
            $response = [
                'success' => false,
                'resultat' => 1,
                'message' => "Echec! La requêtte n'a pas pu être envoyée.",
                'status' => "Failed",
            ];
            return $response;
        }
    }

    public function topup_account($amount, $account_id,$currency,$account_level){
// dd($account_level);
        if ($account_level == 1) {
            
            $response = $this->debitWallet($currency, $amount);
            
            if ($response['success'] == true) {
                $credit_account = $this->credit_account($currency, $amount, $account_id);
                // dd($credit_account);
                if ($credit_account['success']==true) {
                    $response = [
                        'success' => true,
                        'resultat' => 1,
                        'message' => "Compte rechargé avec succès",
                        'status' => "Successful",
                    ];
                    return $response;
                }
                else {
                    return $response;
                }
            }
            else {
                return $response;
            }
        }

        else {
            $response = $this->debitGerantAccount($currency, $amount);

            if ($response['success'] == true) {
                $credit_account = $this->credit_account($currency, $amount, $account_id);
                // dd($credit_account);
                if ($credit_account['success']==true) {
                    $response = [
                        'success' => true,
                        'resultat' => 1,
                        'message' => "Compte rechargé avec succès",
                        'status' => "Successful",
                    ];
                    return $response;
                }
                else {
                    return $response;
                }
            }
            else {
                return $response;
            }
        }
    }

    public function delete_wallet($wallet_id)
    {
        $todayDate = $this->todayDate();
        $activityLog = [
            'fullname'   => Auth::user()->firstname." ".Auth::user()->lastname,
            'user_phone'   => Auth::user()->phone_number,
            'user_id'   => Auth::user()->id,
            'activity'   => "Vient de supprimer le wallet ".$wallet_id,
            'updated_at'   => $todayDate,
        ];

        $save = DB::table('user_activity_logs')->insert($activityLog);

        if($save){
            $destroy = Wallet::destroy($wallet_id);
            if ($destroy) {
                $response = [
                    'success' => true,
                    'message' => "Wallet supprimé avec succès!",
                    'status' => "Successful",
                ];
                return $response;
            }
            else {
                $response = [
                    'success' => false,
                    'message' => "Echec de l'opération!",
                    'status' => "Failed",
                ];
                return $response;
            }
        }
    }
    public function create_branche($township,$city,$phone,$email,$user_id,$bname){
        $todayDate = $this->todayDate();
        $generate = new GenerateIdController;
        $bcode = $generate->code_agence();

        $bank = DB::table('banks')->where('bank_name',"Lumumba & Partners")->first();
        $bank_id = $bank->id;

        $branche = Branche::create([
            'created_by'   => $user_id,
            'bname'   => $bname,
            'bcode'   => $bcode,
            'bemail'   => $email,
            'btownship'   => $township,
            'bcity'   => $city,
            'bphone'   => $phone,
            'bank_id'   => $bank_id,
            'created_at'   => $todayDate,
            'updated_at'   => $todayDate,
        ]);

        $activityLog = [
            'fullname'   => Auth::user()->firstname." ".Auth::user()->lastname,
            'user_phone'   => Auth::user()->phone_number,
            'user_id'   => Auth::user()->id,
            'activity'   => "Vient de créer l'agence de ".$township,
            'updated_at'   => $todayDate,
        ];

        if ($branche) {
            $this->activity_log($activityLog);
            $response = [
                'success' => true,
                'message' => "Agence créée avec succès!",
                'status' => "Successful",
            ];
            return $response;
        }
        else {
            $activityFailed= [
                'fullname'   => Auth::user()->firstname." ".Auth::user()->lastname,
                'user_phone'   => Auth::user()->phone_number,
                'activity'   => "a tenté de créer l'agence ".$township,
                'updated_at'   => $todayDate,
            ];
            $this->activity_log($activityFailed);
            $response = [
                'success' => false,
                'message' => "Une erreur est survenue lors de la création de l'agence",
                'status' => "Successful",
            ];
            return $response;
        }
    }

    public function updatebranche($branche_id,$bname,$bphone,$bcity,$btownship,$bemail){
        $todayDate = $this->todayDate();

        $data = [
            'bname'   => $bname,
            'bemail'   => $bemail,
            'btownship'   => $btownship,
            'bcity'   => $bcity,
            'bphone'   => $bphone,
            'updated_at'   => $todayDate,
        ];

        $activityLog = [
            'fullname'   => Auth::user()->firstname." ".Auth::user()->lastname,
            'user_phone'   => Auth::user()->phone_number,
            'user_id'   => Auth::user()->id,
            'activity'   => "Vient de créer l'agence de ".$btownship,
            'updated_at'   => $todayDate,
        ];

        $update = DB::table('branches')->where('id',$branche_id)->update($data);

        if ($update) {
            $this->activity_log($activityLog);
            $response = [
                'success' => true,
                'message' => "Agence modifiée avec succès!",
                'status' => "Successful",
            ];
            return $response;
        }
        else {
            $activityFailed= [
                'fullname'   => Auth::user()->firstname." ".Auth::user()->lastname,
                'user_phone'   => Auth::user()->phone_number,
                'activity'   => "vient de modifier l'agence ".$btownship,
                'updated_at'   => $todayDate,
            ];
            $this->activity_log($activityFailed);
            $response = [
                'success' => false,
                'message' => "Une erreur est survenue lors de la modification de l'agence",
                'status' => "Failed",
            ];
            return $response;
        }
    }

    public function assign($user_id,$branche_id){
        $todayDate = $this->todayDate();
        $user = DB::table('users')->where('id',$user_id)->first();
        $fullname = $user->firstname." ".$user->lastname;

        $data = [
            'fullname'=>$fullname,
            'user_id'=>$user_id,
            'status'=>"Active"
        ];

        $branche = Branche::where('id', $branche_id)->update($data);
        $activityLog = [
            'fullname'   => Auth::user()->firstname." ".Auth::user()->lastname,
            'user_phone'   => Auth::user()->phone_number,
            'user_id'   => Auth::user()->id,
            'activity'   => "Vient d'assigner un gérant.",
            'updated_at'   => $todayDate,
        ];

        if ($branche) {
            $default = new GenerateIdController;
            Account::create([
                'user_id'   => $user_id,
                'acnumber'   => $default->AccountNumber(),
                'branche_id'   => $branche_id,
                'account_level'   => 2,
                'balance'   => 0.00,
                'currency'   => "CDF",
                'status'   => 1,
                'account_level'   => 1,
                'created_at'   => $todayDate,
                'updated_at'   => $todayDate,
            ]);

            Account::create([
                'user_id'   => $user_id,
                'acnumber'   => $default->AccountNumber(),
                'branche_id'   => $branche_id,
                'account_level'   => 2,
                'balance'   => 0.00,
                'currency'   => "USD",
                'status'   => 1,
                'account_level'   => 1,
                'created_at'   => $todayDate,
                'updated_at'   => $todayDate,
            ]);

            TiroirCaisse::create([
                'user_id'   => $user_id,
                'acnumber'   => $default->AccountNumber(),
                'branche_id'   => $branche_id,
                'balance'   => 0.00,
                'currency'   => "CDF",
                'status'   => 1,
                'account_level'   => 1,
                'created_at'   => $todayDate,
                'updated_at'   => $todayDate,
            ]);

            TiroirCaisse::create([
                'user_id'   => $user_id,
                'acnumber'   => $default->AccountNumber(),
                'branche_id'   => $branche_id,
                'balance'   => 0.00,
                'currency'   => "USD",
                'status'   => 1,
                'account_level'   => 1,
                'created_at'   => $todayDate,
                'updated_at'   => $todayDate,
            ]);

            $this->activity_log($activityLog);
            $this->cash_register($user_id);

            $response = [
                'success' => true,
                'message' => "Agence créée avec succès!",
                'status' => "Successful",
            ];
            return $response;
        }
        else {
            $activityLog = [
                'fullname'   => Auth::user()->firstname." ".Auth::user()->lastname,
                'user_phone'   => Auth::user()->phone_number,
                'activity'   => "A tenter d'assigner un gérant.",
                'updated_at'   => $todayDate,
            ];
            $this->activity_log($activityLog);

            $response = [
                'success' => false,
                'message' => "Une erreu est survenue lors de l'assignation du gérant!",
                'status' => "Failed",
            ];
            return $response;
        }
    }

    public function cash_register_verify(){
        $user_id = Auth::user()->id;
        $account = DB::table('accounts')->where('user_id',$user_id)->where('currency','CDF')->first();
        if ($account == null) {
            # caisse fermée
            $response = [
                'success' => false,
            ];
            return $response;
        }
        else {
            $account_id = $account->id;
            // dd($account_id);
            $register = CashRegister::where('account_id', $account_id)->where('status','opened')->where('currency','CDF')->latest('updated_at')->first();
            // dd($register->status);
            if ($register == null) {
                # caisse fermée
                $response = [
                    'success' => false,
                ];
                return $response;
            }
            else {
                $account_status = $register->status;
                if ($account_status == "closed") {
                    # caisse fermée
                    $response = [
                        'success' => false,
                    ];
                    return $response;
                }
                elseif ($account_status == "opened") {
                    # caisse ouverte
                    $response = [
                        'success' => true,
                    ];
                    return $response;
                }
            }
            
        }
        
    }

    public function cash_register($user_id){

        $todayDate = $this->todayDate();

        $account_cdf = DB::table('accounts')->where('user_id',$user_id)->where('currency','CDF')->first();
        $account_id_cdf = $account_cdf->id;
        $opening_balance_cdf = $account_cdf->balance;

        $account_usd = DB::table('accounts')->where('user_id',$user_id)->where('currency','USD')->first();
        $account_id_usd = $account_usd->id;
        $opening_balance_usd = $account_usd->balance;

        CashRegister::create([
            'account_id'   => $account_id_usd,
            'opening_balance'   => $opening_balance_usd,
            'currency'   => "USD",
            'opening_date'   => $todayDate,
            'created_at'   => $todayDate,
            'updated_at'   => $todayDate,
        ]);

        CashRegister::create([
            'account_id'   => $account_id_cdf,
            'opening_balance'   => $opening_balance_cdf,
            'currency'   => "CDF",
            'opening_date'   => $todayDate,
            'created_at'   => $todayDate,
            'updated_at'   => $todayDate,
        ]);
    }

    public function create_treller($firstname,$lastname,$telephone){

        $limite = $this->branche_user_limit();
        if ($limite['success'] == false) {
            return $limite;
        }
        else {
            $todayDate = $this->todayDate();
            $verify_number = new VerifyNumberController;
            $phone_number = $verify_number->verify_number($telephone);

            $default = new GenerateIdController;
            $password = $default->defaultPIN();
            $acnumber = $default->AccountNumber();

            $check_user = DB::table('users')->where('phone_number',$phone_number)->first();

            if ($check_user == null) {
                $user = User::create([
                    'firstname'      => $firstname,
                    'lastname'      => $lastname,
                    'phone_number'      => $phone_number,
                    'avatar'    => "user.png",
                    'join_date' => $todayDate,
                    'role_name' =>"Cashier",
                    'user_status' => 'Hors ligne',
                    'password'  => Hash::make($password),
                    'password_salt'  => $password,
                    'created_at'   => $todayDate,
                    'updated_at'   => $todayDate,
                ]);

                if ($user) {

                    $user = DB::table('users')->where('phone_number',$phone_number)->first();
                    $user_id = $user->id;
                    $role_name = $user->role_name;

                    if ($role_name == "Manager") {
                        $level = 2;
                    }
                    elseif ($role_name == "Cashier") {
                        $level = 3;
                    }
                    elseif ($role_name == "Admin") {
                        $level = 1;
                    }

                    $branche_id = $this->branche_id(Auth::user()->id);

                    $account_1 = Account::create([
                        'user_id'   => $user_id,
                        'acnumber'   => $default->AccountNumber(),
                        'branche_id'   => $branche_id,
                        'account_level'   => $level,
                        'balance'   => 0.00,
                        'currency'   => "CDF",
                        'status'   => 1,
                        'created_at'   => $todayDate,
                        'updated_at'   => $todayDate,
                    ]);

                    $account_2 = Account::create([
                        'user_id'   => $user_id,
                        'acnumber'   => $default->AccountNumber(),
                        'branche_id'   => $branche_id,
                        'account_level'   => $level,
                        'balance'   => 0.00,
                        'currency'   => "USD",
                        'status'   => 1,
                        'created_at'   => $todayDate,
                        'updated_at'   => $todayDate,
                    ]);
                    $this->cash_register($user_id);
                    if ($account_1 && $account_2) {
                        $activityLog = [
                            'fullname'   => Auth::user()->firstname." ".Auth::user()->lastname,
                            'user_phone'   => Auth::user()->phone_number,
                            'activity'   => "Vient de créer un caissier",
                            'updated_at'   => $todayDate,
                        ];
                        $this->activity_log($activityLog);
                        $activityLog = [
                            'fullname'   => Auth::user()->firstname." ".Auth::user()->lastname,
                            'user_phone'   => Auth::user()->phone_number,
                            'activity'   => "A tenter d'assigner un gérant.",
                            'updated_at'   => $todayDate,
                        ];
                        $response = [
                            'success' => true,
                            'message' => "Caissier créer avec succès!",
                            'status' => "Successful",
                        ];
                        return $response;
                    }

                }
                else {
                    $response = [
                        'success' => false,
                        'message' => "Une erreur est survenue lors de la création de l'utilisateur!",
                    ];
                    $activityLog = [
                        'fullname'   => Auth::user()->firstname." ".Auth::user()->lastname,
                        'user_phone'   => Auth::user()->phone_number,
                        'activity'   => "A tenter de créer un caissier",
                        'updated_at'   => $todayDate,
                    ];
                    $this->activity_log($activityLog);
                    return $response;
                }
            }
            else {
                $response = [
                    'success' => false,
                    'message' => "Numéro déjà enregistré dans le système, veuillez changer de numéro svp!",
                ];
                return $response;
            }
        }

    }

    public function remise($customer_number,$amount,$payment_method,$currency){
        
        $compte = "current";
        $sender_phone = Auth::user()->phone_number;
        $type = "remise";
        $action = "credit";
        $gateway = $this->getPaymentMethodID($payment_method);
       
        $generate = new generateReferenceController;
        $reference = $generate->reference($type);
        $credit = $this->credit_customer($currency, $amount, $compte, $customer_number);
        if ($credit['success'] == true) {
            $status = "Succès";
            $withdrawal_status = "En attente";
            $status_description = "Remise effectuée avec succès!";
            $status = "Succès";
            $note = "Transfert effectué avec succès!";
            $this->table_transactions($reference,$amount,$currency,$sender_phone,$customer_number,$type,$note,$action,$status,$gateway);
            $response = [
                'success' => true,
                'message' => "Remise effectuée avec succès",
                'status' => "Successfull",
            ];
            return $response;
        }
        else {
            return $credit;
        }

    }

    public function mobilemoney($sender_number,$customer_number,$amount,$currency,$comment,$method,$action,$status,$reference,$transaction_id){

        $todayDate = $this->todayDate();

        $dataAll = [
            'sender_number' => $sender_number,
            'customer_number' => $customer_number,
            'amount' => $amount,
            'currency' => $currency,
            'comment' => $comment,
            'action' => $action,
            'method' => $method,
            'status' => $status,
            'reference' => $reference,
            'transaction_id' => $transaction_id,
            'created_at' => $todayDate,
            'updated_at' => $todayDate
        ];

        $success = DB::table('mobile_money')->insert($dataAll);

        if ($success) {
            $response = [
                'success' => true,
                'message' => "Demande de retrait effectuée avec succès! Réf. : ".$reference,
                'status' => "Successful",
            ];
            return $response;
        }
        else {
            $response = [
                'success' => false,
                'message' => "Votre demande de retrait a echoué !",
                'status' => "Failed",
            ];
            return $response;
        }
    }

    public function branche_user_limit(){
        $branche_id = $this->branche_id(Auth::user()->id);
        $agentNumber = Account::where('branche_id',$branche_id)->distinct('user_id')->count();
        if ($agentNumber == 4) {
            $response = [
                'success' => false,
                'message' => "Nombre total (3) des agents dans une agence atteind!",
                'status' => "Failed",
            ];
            return $response;
        }
        else {
            $response = [
                'success' => true,
                'message' => "Fine!",
                'status' => "Successful",
            ];
            return $response;
        }
    }
    public function branche_id($user_id){
        $branche = Branche::where('user_id', $user_id)->first();
        if ($branche == null) {
            $branche_id = null;
            return $branche_id;
        }
        else {
            $branche_id = $branche->id;
            return $branche_id;
        }
        
    }
    public function cashierBrancheId($user_id){
        $branche = Account::where('user_id',$user_id)->first();
            
        if ($branche == null) {
            $branche_id = null;
            return $branche_id;
        }
        else {
            $branche_id = $branche->branche_id;
            return $branche_id;
        }
        
    }

    
    public function create_customer($firstname,$lastname,$phone,$adresse,$ville,$role,$pays){

        $todayDate = $this->todayDate();

        $verify_number = new verifyNumberController;
        $phone_number = $verify_number->verify_number($phone);

        $default = new GenerateIdController;
        $password = $default->defaultPIN();
        $wallet_code = $default->AccountNumber();
        $savingcode = $default->SavingAcnumber();


        $check_user = DB::connection('mysql2')->table('users')->where('phone',$phone_number)->first();

        if ($check_user == null) {
            $user = DB::connection('mysql2')->table('users')->insert([
                'firstname'      => $firstname,
                'name'      => $lastname,
                'phone'      => $phone_number,
                'avatar'    => "user.png",
                'city'     => $ville,
                'country'     => $pays,
                'address'     => $adresse,
                'role_name' =>$role,
                'password'  => Hash::make($password),
                'password_salt'  => $password,
                'created_at'   => $todayDate,
                'updated_at'   => $todayDate,
            ]);

            if ($user) {
                $customer = DB::connection('mysql2')->table('users')->where('phone',$phone_number)->first();
                $customer_id = $customer->id;

                $customer_wallet_1 = DB::connection('mysql2')->table('wallets')->insert([
                    'customer_id'   => $customer_id,
                    'wallet_code'   => $wallet_code,
                    'wallet_balance'   => 0,
                    'wallet_currency'   => 'CDF',
                    'created_at'   => $todayDate,
                    'updated_at'   => $todayDate,
                ]);

                $customer_wallet_2 = DB::connection('mysql2')->table('wallets')->insert([
                    'customer_id'   => $customer_id,
                    'wallet_code'   => $wallet_code,
                    'wallet_balance'   => 0,
                    'wallet_currency'   => 'USD',
                    'created_at'   => $todayDate,
                    'updated_at'   => $todayDate,
                ]);

                $customer_wallet_3 = DB::connection('mysql2')->table('wallets')->insert([
                    'customer_id'   => $customer_id,
                    'wallet_code'   => $savingcode,
                    'wallet_balance'   => 0,
                    'wallet_currency'   => 'CDF',
                    'wallet_type' => 'saving',
                    'created_at'   => $todayDate,
                    'updated_at'   => $todayDate,
                ]);

                $customer_wallet_4 = DB::connection('mysql2')->table('wallets')->insert([
                    'customer_id'   => $customer_id,
                    'wallet_code'   => $savingcode,
                    'wallet_balance'   => 0,
                    'wallet_currency'   => 'USD',
                    'wallet_type' => 'saving',
                    'created_at'   => $todayDate,
                    'updated_at'   => $todayDate,
                ]);
                
                if ($customer_wallet_1 && $customer_wallet_2 && $customer_wallet_3 && $customer_wallet_4) {
                        $response = [
                            'success' => true,
                            'message' => "L'utilisateur a été créé avec succès !",
                            'status' => "Successful",
                        ];
                        return $response;

                }
                else {
                    $response = [
                        'success' => false,
                        'message' => "Une erreur est survenue lors de la création du compte principal du client!",
                        'status' => "Failed",
                    ];
                    return $response;
                }
            }
            else {
                $response = [
                    'success' => false,
                    'message' => "Une erreur est survenue lors de la création du client!",
                ];
                return $response;
            }
        }
        else {
            $response = [
                'success' => false,
                'message' => "Numéro déjà enregistré dans le système, veuillez changer de numéro svp!",
            ];
            return $response;
        }
    }

    public function create_gerant($firstname,$lastname,$phone,$adresse,$ville,$role,$pays){

        $todayDate = $this->todayDate();

        $verify_number = new verifyNumberController;
        $phone_number = $verify_number->verify_number($phone);

        $default = new GenerateIdController;
        $password = $default->defaultPIN();
        $wallet_code = $default->AccountNumber();


        $check_user = DB::table('users')->where('phone_number',$phone_number)->first();

        if ($check_user == null) {
            $user = DB::table('users')->insert([
                'firstname'      => $firstname,
                'lastname'      => $lastname,
                'phone_number'      => $phone_number,
                'avatar'    => "user.png",
                'city'     => $ville,
                'country'     => $pays,
                'address'     => $adresse,
                'role_name' =>$role,
                'password'  => Hash::make($password),
                'password_salt'  => $password,
                'join_date'   => $todayDate,
                'created_at'   => $todayDate,
                'updated_at'   => $todayDate,
            ]);

            if ($user) {
                $activityLog = [
                    'fullname'   => Auth::user()->firstname." ".Auth::user()->lastname,
                    'user_phone'   => Auth::user()->phone_number,
                    'activity'   => "Vient de créer un gérant",
                    'updated_at'   => $todayDate,
                ];
                $this->activity_log($activityLog);
                $response = [
                    'success' => true,
                    'message' => "Gérant créer avec succès!",
                    'status' => "Successful",
                ];
                return $response;

            }
            else {
                $response = [
                    'success' => false,
                    'message' => "Une erreur est survenue lors de la création de l'utilisateur!",
                ];
                $activityLog = [
                    'fullname'   => Auth::user()->firstname." ".Auth::user()->lastname,
                    'user_phone'   => Auth::user()->phone_number,
                    'activity'   => "A tenter de créer un gérant",
                    'updated_at'   => $todayDate,
                ];
                $this->activity_log($activityLog);
                return $response;
            }
        }
        else {
            $response = [
                'success' => false,
                'message' => "Numéro déjà enregistré dans le système, veuillez changer de numéro svp!",
            ];
            return $response;
        }
    }
    public function create_admin($firstname,$lastname,$phone,$adresse,$ville,$role,$pays){

        $todayDate = $this->todayDate();

        $verify_number = new verifyNumberController;
        $phone_number = $verify_number->verify_number($phone);

        $default = new GenerateIdController;
        $password = $default->defaultPIN();
        $wallet_code = $default->AccountNumber();


        $check_user = DB::table('users')->where('phone_number',$phone_number)->first();

        if ($check_user == null) {
            $user = DB::table('users')->insert([
                'firstname'      => $firstname,
                'lastname'      => $lastname,
                'phone_number'      => $phone_number,
                'avatar'    => "user.png",
                'city'     => $ville,
                'country'     => $pays,
                'address'     => $adresse,
                'role_name' =>$role,
                'password'  => Hash::make($password),
                'password_salt'  => $password,
                'join_date'   => $todayDate,
                'created_at'   => $todayDate,
                'updated_at'   => $todayDate,
            ]);

            

            if ($user) {

                $user = DB::table('users')->where('phone_number',$phone_number)->first();
                $user_id = $user->id;
                $role_name = $user->role_name;
                $branche_id = $this->branche_id(Auth::user()->id);

                Account::create([
                    'user_id'   => $user_id,
                    'acnumber'   => $default->AccountNumber(),
                    'branche_id'   => $branche_id,
                    'account_level'   => 1,
                    'balance'   => 0.00,
                    'currency'   => "CDF",
                    'status'   => 1,
                    'created_at'   => $todayDate,
                    'updated_at'   => $todayDate,
                ]);

                Account::create([
                    'user_id'   => $user_id,
                    'acnumber'   => $default->AccountNumber(),
                    'branche_id'   => $branche_id,
                    'account_level'   => 1,
                    'balance'   => 0.00,
                    'currency'   => "USD",
                    'status'   => 1,
                    'created_at'   => $todayDate,
                    'updated_at'   => $todayDate,
                ]);
                $this->cash_register($user_id);
                $activityLog = [
                    'fullname'   => Auth::user()->firstname." ".Auth::user()->lastname,
                    'user_phone'   => Auth::user()->phone_number,
                    'activity'   => "Vient de créer un admin",
                    'updated_at'   => $todayDate,
                ];
                $this->activity_log($activityLog);
                $response = [
                    'success' => true,
                    'message' => "Admin créer avec succès!",
                    'status' => "Successful",
                ];
                return $response;

            }
            else {
                $response = [
                    'success' => false,
                    'message' => "Une erreur est survenue lors de la création de l'utilisateur!",
                ];
                $activityLog = [
                    'fullname'   => Auth::user()->firstname." ".Auth::user()->lastname,
                    'user_phone'   => Auth::user()->phone_number,
                    'activity'   => "A tenter de créer un admin",
                    'updated_at'   => $todayDate,
                ];
                $this->activity_log($activityLog);
                return $response;
            }
        }
        else {
            $response = [
                'success' => false,
                'message' => "Numéro déjà enregistré dans le système, veuillez changer de numéro svp!",
            ];
            return $response;
        }
    }
    public function update_admin($firstname,$lastname,$phone,$adresse,$ville,$pays, $user_id,$password){

        $todayDate = $this->todayDate();
        $pin = Hash::make($password);
        $update = DB::table('users')->where('id',$user_id)->update([
            'firstname'      => $firstname,
            'lastname'      => $lastname,
            'phone_number'      => $phone,
            'city'     => $ville,
            'country'     => $pays,
            'password_salt'     => $password,
            'password'     => $pin,
            'address'     => $adresse,
            'updated_at'   => $todayDate,
        ]);

        if ($update) {
            $response = [
                'success' => true,
                'message' => "L'utilisateur a été modifié avec succès !",
                'status' => "Successful",
            ];
            return $response; $response;
        }

        else {
            $response = [
                'success' => false,
                'message' => "Une erreur est survenue lors de l'exécution de la requêtte!",
            ];
            return $response;
        }

    }
    public function delete_admin($user_id){
        $todayDate = $this->todayDate();
        $user = DB::table('users')->where('id',$user_id)->first();
        $activityLog = [
            'fullname'   => Auth::user()->firstname." ".Auth::user()->lastname,
            'user_phone'   => Auth::user()->phone_number,
            'user_id'   => Auth::user()->id,
            'activity'   => "Vient de supprimer l'administrateur ".$user->phone_number,
            'updated_at'   => $todayDate,
        ];

        $save = DB::table('user_activity_logs')->insert($activityLog);

        if($save){
            $destroy = DB::table('users')->delete($user_id);
            if ($destroy) {
                $response = [
                    'success' => true,
                    'message' => "Admin supprimé avec succès!",
                    'status' => "Successful",
                ];
                return $response;
            }
            else {
                $response = [
                    'success' => false,
                    'message' => "Echec de l'opération!",
                    'status' => "Failed",
                ];
                return $response;
            }
        }
    }

    public function update_customer($firstname,$lastname,$phone,$adresse,$ville,$pays, $user_id){

        $todayDate = $this->todayDate();
        $update = DB::connection('mysql2')->table('users')->where('id',$user_id)->update([
            'firstname'      => $firstname,
            'name'      => $lastname,
            'phone'      => $phone,
            'city'     => $ville,
            'country'     => $pays,
            'address'     => $adresse,
            'updated_at'   => $todayDate,
        ]);

        if ($update) {
            $response = [
                'success' => true,
                'message' => "L'utilisateur a été modifié avec succès !",
                'status' => "Successful",
            ];
            return $response; $response;
        }

        else {
            $response = [
                'success' => false,
                'message' => "Une erreur est survenue lors de la modification du client!",
            ];
            return $response;
        }

    }

    public function update_manager($firstname,$lastname,$phone,$adresse,$ville,$pays, $user_id,$password){

        $todayDate = $this->todayDate();
        $pin = Hash::make($password);
        $update = DB::table('users')->where('id',$user_id)->update([
            'firstname'      => $firstname,
            'lastname'      => $lastname,
            'phone_number'      => $phone,
            'city'     => $ville,
            'country'     => $pays,
            'password_salt'     => $password,
            'password'     => $pin,
            'address'     => $adresse,
            'updated_at'   => $todayDate,
        ]);

        if ($update) {
            $response = [
                'success' => true,
                'message' => "L'utilisateur a été modifié avec succès !",
                'status' => "Successful",
            ];
            return $response; $response;
        }

        else {
            $response = [
                'success' => false,
                'message' => "Une erreur est survenue lors de la modification du gérant!",
            ];
            return $response;
        }

    }


    public function compte_client($user_id){

        $users = DB::connection('mysql2')->table('users')->where('id', $user_id)->first();
        $current_wallet_cdf = DB::connection('mysql2')->table('wallets')->where('wallet_type', 'current')->where('wallet_currency', 'CDF')->where('customer_id', $user_id)->first();
        $current_wallet_usd = DB::connection('mysql2')->table('wallets')->where('wallet_type', 'current')->where('wallet_currency', 'USD')->where('customer_id', $user_id)->first();
        $saving_wallet_cdf = DB::connection('mysql2')->table('wallets')->where('wallet_type', 'saving')->where('wallet_currency', 'CDF')->where('customer_id', $user_id)->first();
        $saving_wallet_usd = DB::connection('mysql2')->table('wallets')->where('wallet_type', 'saving')->where('wallet_currency', 'USD')->where('customer_id', $user_id)->first();

        $data = [$users,$current_wallet_cdf,$current_wallet_usd,$saving_wallet_cdf,$saving_wallet_usd];

        $response = [
            'success' => true,
            'resultat' => 1,
            'status' => "Successful",
            'data' => [$data],
        ];

        return $response;

    }
    public function compte_utilisateur($user_id){

        $users = DB::table('users')->where('id', $user_id)->first();
        $current_wallet_cdf = Account::where('user_id',$user_id)->where('currency', 'CDF')->first();
        $current_wallet_usd = Account::where('user_id',$user_id)->where('currency', 'USD')->first();

        $data = [$users,$current_wallet_cdf,$current_wallet_usd];

        $response = [
            'success' => true,
            'resultat' => 1,
            'status' => "Successful",
            'data' => [$data],
        ];

        return $response;

    }

    public function delete_client($user_id)
    {
        $todayDate = $this->todayDate();
        $user = DB::connection('mysql2')->table('users')->where('id',$user_id)->first();
        $activityLog = [
            'fullname'   => Auth::user()->firstname." ".Auth::user()->lastname,
            'user_phone'   => Auth::user()->phone_number,
            'user_id'   => Auth::user()->id,
            'activity'   => "Vient de supprimer le client ".$user->phone,
            'updated_at'   => $todayDate,
        ];

        $save = DB::table('user_activity_logs')->insert($activityLog);

        if($save){
            $destroy = DB::connection('mysql2')->table('users')->delete($user_id);
            if ($destroy) {
                $response = [
                    'success' => true,
                    'message' => "Client supprimé avec succès!",
                    'status' => "Successful",
                ];
                return $response;
            }
            else {
                $response = [
                    'success' => false,
                    'message' => "Echec de l'opération!",
                    'status' => "Failed",
                ];
                return $response;
            }
        }
    }
    public function delete_gerant($user_id)
    {
        $todayDate = $this->todayDate();
        $user = DB::table('users')->where('id',$user_id)->first();
        $activityLog = [
            'fullname'   => Auth::user()->firstname." ".Auth::user()->lastname,
            'user_phone'   => Auth::user()->phone_number,
            'user_id'   => Auth::user()->id,
            'activity'   => "Vient de supprimer le gérant ".$user->phone_number,
            'updated_at'   => $todayDate,
        ];

        $save = DB::table('user_activity_logs')->insert($activityLog);

        if($save){
            $destroy = DB::table('users')->delete($user_id);
            if ($destroy) {
                $response = [
                    'success' => true,
                    'message' => "Gérant supprimé avec succès!",
                    'status' => "Successful",
                ];
                return $response;
            }
            else {
                $response = [
                    'success' => false,
                    'message' => "Echec de l'opération!",
                    'status' => "Failed",
                ];
                return $response;
            }
        }
    }
    public function delete_caissier($user_id)
    {
        $todayDate = $this->todayDate();
        $user = DB::table('users')->where('id',$user_id)->first();
        $activityLog = [
            'fullname'   => Auth::user()->firstname." ".Auth::user()->lastname,
            'user_phone'   => Auth::user()->phone_number,
            'user_id'   => Auth::user()->id,
            'activity'   => "Vient de supprimer le caissier ".$user->phone_number,
            'updated_at'   => $todayDate,
        ];

        $save = DB::table('user_activity_logs')->insert($activityLog);

        if($save){
            $destroy = DB::table('users')->delete($user_id);
            if ($destroy) {
                $response = [
                    'success' => true,
                    'message' => "Caissier supprimé avec succès!",
                    'status' => "Successful",
                ];
                return $response;
            }
            else {
                $response = [
                    'success' => false,
                    'message' => "Echec de l'opération!",
                    'status' => "Failed",
                ];
                return $response;
            }
        }
    }
}
