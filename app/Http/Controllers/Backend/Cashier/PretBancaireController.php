<?php

namespace App\Http\Controllers\Backend\Cashier;

use App\Http\Controllers\Backend\DateController;
use App\Http\Controllers\Backend\GenerateIdController;
use App\Http\Controllers\Controller;
use App\Models\Cashier;
use App\Models\Customer;
use App\Models\Loan;
use App\Models\PretAmortissement;
use App\Models\PretBancaire;
use App\Models\UserInfo;
use Carbon\Carbon;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Crypt;
use RealRashid\SweetAlert\Facades\Alert;

class PretBancaireController extends Controller
{
    public function branche_id()
    {
        $userInfo = UserInfo::where('user_id',Auth::user()->id)->first();
        $branche_id = $userInfo->branche_id;
        return $branche_id;
    }

    public function show($id)
    {
        $pret = PretBancaire::findOrFail($id);
        $customer = Customer::where('id',$pret->customer_id)->first();
        return view('backend.cashier.pret.show', compact('pret','customer'));
    }

    public function pret($id)
    {
        $response = new CustomerController();
        $data = $response->getCustomerData($id);

        return view('backend.cashier.operation.pret', $data);
    }

    public function index(){
        
        $prets = PretBancaire::all();
        
        return view('backend.cashier.pret.index',compact('prets'));
    }

    public function create(){
        
        $prets = PretBancaire::all();
        
        return view('backend.cashier.pret.create',compact('prets'));
    }

    // public function demandePost(Request $request){

    //     $request->validate([
    //         'loan_amount'      => 'required|string|max:255',
    //         'phone'      => 'required|string|max:255',
    //         'surname'      => 'required|string|max:255',
    //         'name' => 'required|string|max:255',
    //         'loan_duration' => 'required|string|max:255',
    //         'payment_frequency' => 'required|in:monthly,weekly,daily',
    //         'principal_paid' => 'required|string|max:255',
    //         'paid_by_echeance' => 'required|string|max:255',
    //     ]);

    //     $phone_number = $request->phone;
    //     $loan_amount = $request->loan_amount;
    //     $loan_currency = $request->loan_currency;
    //     $loan_duration = $request->loan_duration;
    //     $payment_frequency = $request->payment_frequency;
    //     $principal_paid = $request->principal_paid;
    //     $paid_by_echeance = $request->paid_by_echeance;
    //     $objet = $request->objet;

    //     $customer = Customer::where('phone',$phone_number)->first();
    //     $customer_id = $customer->id;

    //     $premier_echeance = Carbon::today()->addDays(7);

    //     $pin = new GenerateIdController;
    //     $control_number = $pin->reference();

    //     $pretBancaire = PretBancaire::create([
    //         'control_number'=>$control_number,
    //         'loan_amount'=>$loan_amount,
    //         'loan_currency'=>$loan_currency,
    //         'loan_status'=>'En attente',
    //         'loan_duration'=>$loan_duration,
    //         'principal_paid'=>$principal_paid,
    //         'payment_frequency'=>$payment_frequency,
    //         'first_payment'=>$premier_echeance,
    //         'amount_by_echeance'=>$paid_by_echeance,
    //         'objet'=>$objet,
    //         'customer_id'=>$customer_id
    //     ]);

    //     $cashier = Cashier::findOrFail(Auth::user()->id);
    //     $agencyId = $cashier->agency->id;
    //     $pretBancaire->agency_id = $agencyId;
    //     $pretBancaire->processed_by = Auth::user()->id;
    //     $pretBancaire->save();

        
    //     Alert::success('Succès', 'Historique de prêt envoyée avec succès !');
    //     return redirect()->route('cashier.pret.index');
    // }

    public function demandePost(Request $request)
    {
        $request->validate([
            'loan_amount' => 'required|string|max:255',
            'phone' => 'required|string|max:255',
            'surname' => 'required|string|max:255',
            'name' => 'required|string|max:255',
            'loan_duration' => 'required|string|max:255',
            'echeance' => 'required|in:monthly,weekly,daily',
            'principal_paid' => 'required|string|max:255',
            'paid_by_echeance' => 'required|string|max:255',
        ]);

        try {
            $phone_number = $request->phone;
            $loan_amount = $request->loan_amount;
            $loan_currency = $request->loan_currency;
            $loan_duration = $request->loan_duration;
            $payment_frequency = $request->echeance;
            $principal_paid = $request->principal_paid;
            $paid_by_echeance = $request->paid_by_echeance;
            $objet = $request->objet;

            $customer = Customer::where('phone', $phone_number)->first();
            
            if (!$customer) {
                throw new \Exception('Customer not found'); // Gérer le cas où le client n'est pas trouvé
            }
            
            $customer_id = $customer->id;

            $premier_echeance = Carbon::today()->addDays(7);

            $pin = new GenerateIdController;
            $control_number = $pin->reference();

            $pretBancaire = PretBancaire::create([
                'control_number' => $control_number,
                'loan_amount' => $loan_amount,
                'loan_currency' => $loan_currency,
                'loan_status' => 'En attente',
                'loan_duration' => $loan_duration,
                'principal_paid' => $principal_paid,
                'payment_frequency' => $payment_frequency,
                'first_payment' => $premier_echeance,
                'amount_by_echeance' => $paid_by_echeance,
                'objet' => $objet,
                'customer_id' => $customer_id
            ]);

            $cashier = Cashier::findOrFail(Auth::user()->id);
            $agencyId = $cashier->agency->id;
            $pretBancaire->agency_id = $agencyId;
            $pretBancaire->processed_by = Auth::user()->id;
            $pretBancaire->save();

            Alert::success('Succès', 'Historique de prêt envoyée avec succès !');
            return redirect()->route('cashier.pret.index');
        } catch (\Exception $e) {
            // Gérer l'erreur et afficher un message approprié
            Alert::error('Erreur', 'Une erreur s\'est produite lors de la demande de prêt.');
            return redirect()->back()->withInput();
        }
    }
    public function validerDemandePret(Request $request, $id)
    {
        // Récupérer la demande de prêt à partir de l'ID
        $pret = Loan::findOrFail($id);

        // Effectuer vos vérifications et opérations de validation ici

        // Vérifier si la demande de prêt est déjà validée
        if ($pret->status === 'Approuvé') {
            return response()->json(['message' => 'La demande de prêt est déjà validée.','error' => true]);
        }

        // Mettre à jour le statut de la demande de prêt
        $pret->status = 'Approuvé';
        $pret->save();

        // Transférer le montant du prêt dans le portefeuille principal du client
        $customer = Customer::findOrFail($pret->customer_id);
        $principalAccount = $customer->current_wallets()
            ->where('wallet_currency', 'CDF')
            // ->where('wallet_currency', $pret->loan_currency)
            ->first();
        
        if ($principalAccount) {
            $principalAccount->creditBalance($pret->loan_amount);
            return response()->json(['message' => 'La demande de prêt a été validée avec succès.', 'error' => false]);
        } else {
            throw new Exception("Le compte principal du client n'a pas été trouvé pour la devise spécifiée.");
        }
    }

    public function annulerDemandePret(Request $request, $id)
    {
        // Récupérer la demande de prêt à partir de l'ID
        $pret = Loan::findOrFail($id);

        // Effectuer vos vérifications et opérations d'annulation ici

        // Vérifier si la demande de prêt est déjà annulée
        if ($pret->status === 'Rejeté') {
            return response()->json(['message' => 'La demande de prêt est déjà rejetée.','error' => true]);
        }

        // Mettre à jour le statut de la demande de prêt
        $pret->status = 'Rejeté';
        $pret->save();

        // Effectuer d'autres opérations d'annulation si nécessaire

        return response()->json(['message' => 'La demande de prêt a été rejetée avec succès.','error' => false]);
    }

    public function isEligibleForValidation()
    {
        // Vérifier les critères d'éligibilité pour la demande de prêt

        // Par exemple, vérifier si le montant du prêt est supérieur à zéro
        if ($this->loan_amount <= 0) {
            return false;
        }

        // Vérifier d'autres critères d'éligibilité, tels que les revenus du client, le score de crédit, etc.

        // Retourner true si la demande de prêt est éligible pour validation, sinon false
        return true;
    }

    public function amortissement($id){
        $loanId = Crypt::decrypt($id);
        
        $pretBancaire = PretBancaire::findOrFail($loanId);
      
        $amortissements = $pretBancaire->amortissements; 
     
        return view('backend.cashier.pret.amortissement',compact('amortissements','pretBancaire'));
    }

}
