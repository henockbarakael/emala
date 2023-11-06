<?php

namespace App\Http\Controllers\Backend\Admin;

use App\Http\Controllers\Backend\DateController;
use App\Http\Controllers\Backend\GenerateIdController;
use App\Http\Controllers\Controller;
use App\Models\Customer;
use App\Models\Loan;
use App\Models\LoanPlan;
use App\Models\LoanType;
use App\Models\PretBancaire;
use App\Models\Transaction;
use App\Models\User;
use App\Models\UserInfo;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\DB;
use RealRashid\SweetAlert\Facades\Alert;

class AccountController extends Controller
{

    public function pret($id)
    {
        $response = new CustomerController();
        $data = $response->getCustomerData($id);

        return view('backend.admin.operation.pret', $data);
    }

    public function demandePost(Request $request){


        
        $request->validate([
            'loan_amount'      => 'required|string|max:255',
            'loan_currency' => 'required|string|max:255',
            'loan_duration' => 'required|string|max:255',
            'echeance' => 'required|string|max:255',
            'principal_paid' => 'required|string|max:255',
            'paid_by_echeance' => 'required|string|max:255',
        ]);

        $phone_number = $request->receiver_phone;
        $loan_amount = $request->loan_amount;
        $loan_currency = $request->loan_currency;
        $loan_duration = $request->loan_duration;
        $echeance = $request->echeance;
        $principal_paid = $request->principal_paid;
        $paid_by_echeance = $request->paid_by_echeance;
        $objet = $request->objet;

        $customer = Customer::where('phone',$phone_number)->first();
        $customer_id = $customer->id;


        $date = new DateController;
        $today = $date->todayDate();

        $premier_echeance = Carbon::today()->addDays(7);

        $pin = new GenerateIdController;
        $control_number = $pin->reference();

        PretBancaire::create([
            'control_number'=>$control_number,
            'loan_amount'=>$loan_amount,
            'loan_currency'=>$loan_currency,
            'loan_status'=>'En attente',
            'loan_duration'=>$loan_duration,
            'principal_paid'=>$principal_paid,
            'echeance'=>$echeance,
            'premier_echeance'=>$premier_echeance,
            'amount_by_echeance'=>$paid_by_echeance,
            'objet'=>$objet,
            'customer_id'=>$customer_id,
            'branche_id'=>$this->branche_id(),
            'processed_by'=>Auth::user()->id,
            'created_at'=>$today,
            'updated_at'=>$today
        ]);

        
        Alert::success('Succès', 'Historique de prêt envoyée avec succès !');
        return redirect()->route('admin.pret.demande');
    }


    public function client($id)
    {
        $user_id =  Crypt::decrypt($id);
        $response = new CustomerController();
        $data = $response->getCustomerData($id);
        $pret = Loan::where('customer_id',$user_id)
        ->where('status','!=','Remboursé')
        ->first();
        $data['pret'] = $pret;
        return view('backend.admin.customer.account', compact('data'));
    }

    public function clientPhone($id){
        $phone_number =  Crypt::decrypt($id);
      
        $response = new CustomerController();
        $data = $response->getCustomerDataByPhone($phone_number);
        $user = Customer::where('phone',$phone_number)->first();
        $pret = Loan::where('customer_id',$user->id)->first();
        $data['pret'] = $pret;
        return view('backend.admin.customer.account',compact('data'));

    }

    public function rechercherClient(Request $request)
    {
        $data = Customer::select("phone")

        ->where('phone', 'LIKE', '%'. $request->get('query'). '%')

        ->pluck('phone')
        ->toArray();

        return response()->json($data);
    }

    public function getDetails(Request $request)
    {
        $phone = $request->input('phone');
        $client = Customer::where('phone', $phone)->first();

        if ($client) {
            $details = [
                'surname' => $client->firstname,
                'name' => $client->name
            ];
            return response()->json($details);
        }

        // Si le client n'est pas trouvé, vous pouvez renvoyer un message d'erreur ou une réponse vide
        return response()->json(['error' => 'Client not found']);
    }

    public function client_depot($id){

        $response = new CustomerController();
        $data = $response->getCustomerData($id);
        return view('backend.admin.operation.depot',$data);
        
    }

    public function client_depot_save(Request $request){

        $request->validate([
            'amount'   => 'required|string|max:255',
            'currency'   => 'required|string|max:255',
            'compte'   => 'required|string|max:255',
            'acnumber'   => 'required|string|max:255'
        ]);

        $customer_number = $request->receiver_phone;
        $acnumber = $request->acnumber;
        $fees = $request->fees;
        $amount = $request->amount;
        $currency = $request->currency;

        $date = new DateController;
        $today = $date->todayDate();

        $code = new GenerateIdController;
        $reference = $code->reference();

        DB::beginTransaction();

        $creditBranche =  new WalletController;
        

    
       
            $wallets = DB::connection('mysql2')->table('wallets')->where(['wallet_currency'=>$currency,'wallet_code'=>$acnumber])->first();
            $previous_balance = $wallets->wallet_balance;
            try {

                $save = DB::connection('mysql2')->table('wallets')->where(['wallet_currency'=>$currency,'wallet_code'=>$acnumber])->update(['wallet_balance' => $amount + $previous_balance,'updated_at' => $today]);

                if ($save) {
                    
                    $wallets = DB::connection('mysql2')->table('wallets')->where(['wallet_currency'=>$currency,'wallet_code'=>$acnumber])->first();
                    $current_balance = $wallets->wallet_balance;
                    $creditBranche->creditWallet($amount,$currency);
                    DB::table('transactions')->insert(['user_id' => Auth::user()->id,'branche_id' => $this->branche_id(),'reference' => $reference,'currency' => $currency,'amount' => $amount,'previous_balance' => $previous_balance,'current_balance' => $current_balance,'fees' => $fees,'status' => 'Success','category' => 'depot','transaction_from' => $customer_number,'transaction_to' => $customer_number,'updated_at' => $today,'created_at' => $today]);
                    DB::commit();
                }
                
                Alert::success('Succès', 'Dépôt effectué avec succès !');
                return redirect()->route('admin.transaction.all');
            } catch (\Exception $e) {
                DB::rollback();
                Alert::error('Erreur', 'Une erreur est survenue lors du dépôt.');
                return redirect()->back();
            }

    }


    public function client_retrait($id){
        $response = new CustomerController();
        $data = $response->getCustomerData($id);
        
        return view('backend.admin.operation.retrait',$data);
        
    }

    public function client_retrait_save(Request $request){

        $request->validate([
            'amount'   => 'required|string|max:255',
            'currency'   => 'required|string|max:255',
            'compte'   => 'required|string|max:255',
            'acnumber'   => 'required|string|max:255'
        ]);

        $customer_number = $request->receiver_phone;
        $acnumber = $request->acnumber;
        $fees = $request->fees;
        $amount = $request->amount;
        $currency = $request->currency;

        $total = $amount + $fees;

        $response = $this->verifyUserBalance($total,$currency,$acnumber);

        if ($response['success'] == false) {
            Alert::error('Erreur', $response['message']);
            return redirect()->back();
        }
        else {
            $date = new DateController;
            $today = $date->todayDate();

            $code = new GenerateIdController;
            $reference = $code->reference();

            DB::beginTransaction();

            $debitBranche =  new WalletController;
            

                $wallets = DB::connection('mysql2')->table('wallets')->where(['wallet_currency'=>$currency,'wallet_code'=>$acnumber])->first();
                $previous_balance = $wallets->wallet_balance;
                try {
                    $save = DB::connection('mysql2')->table('wallets')->where(['wallet_currency'=>$currency,'wallet_code'=>$acnumber])->update(['wallet_balance' => $previous_balance - $total,'updated_at' => $today]);

                    if ($save) {
                        
                        $wallets = DB::connection('mysql2')->table('wallets')->where(['wallet_currency'=>$currency,'wallet_code'=>$acnumber])->first();
                        $current_balance = $wallets->wallet_balance;
                        $debitBranche->debitWallet($amount,$currency);
                        DB::table('transactions')->insert(['user_id' => Auth::user()->id,'branche_id' => $this->branche_id(),'reference' => $reference,'currency' => $currency,'amount' => $amount,'previous_balance' => $previous_balance,'current_balance' => $current_balance,'fees' => $fees,'status' => 'Success','category' => 'retrait','transaction_from' => $customer_number,'transaction_to' => $customer_number,'updated_at' => $today,'created_at' => $today]);
                        DB::commit();
                    }
                    
                    Alert::success('Succès', 'Retrait effectué avec succès !');
                    return redirect()->route('admin.transaction.all');
                } catch (\Exception $e) {
                    DB::rollback();
                    Alert::error('Erreur', 'Une erreur est survenue lors du retrait.');
                    return redirect()->back();
                }
 

        }

        
    }

    public function client_transfert($id){
        $response = new CustomerController();
        $data = $response->getCustomerData($id);
        $receiver = Customer::distinct('phone')
        ->select('phone','name AS lastname','firstname')
        ->get();

        $data['receiver'] = $receiver;

        return view('backend.admin.operation.transfert',$data);
        
    }

    public function creditReceiver($amount,$currency,$acnumber,$sender_number,$receiver_number,$fees){

        $date = new DateController;
        $today = $date->todayDate();

        $code = new GenerateIdController;
        $reference = $code->reference();

        DB::beginTransaction();

            $wallets = DB::connection('mysql2')->table('wallets')->where(['wallet_currency'=>$currency,'wallet_code'=>$acnumber])->first();
            $previous_balance = $wallets->wallet_balance;
            try {
                DB::connection('mysql2')->table('wallets')->where(['wallet_currency'=>$currency,'wallet_code'=>$acnumber])->update(['wallet_balance' => $amount + $previous_balance,'updated_at' => $today]);

                DB::commit();
                return ["success"=>true];

            } catch (\Exception $e) {
                DB::rollback();
                return ["success"=>false,"message"=>"Une erreur est survenue lors du transfert"];
            }

    }

    public function client_transfert_save(Request $request){

        $request->validate([
            'amount'   => 'required|string|max:255',
            'currency'   => 'required|string|max:255',
            'compte'   => 'required|string|max:255',
            'compte_receiver'   => 'required|string|max:255',
            'acnumber'   => 'required|string|max:255'
        ]);

        $sender_number = $request->customer_number;
        $receiver_number = $request->receiver_number;
        $acnumber = $request->acnumber;
        
        $fees = $request->fees;
        $amount = $request->amount;
        $currency = $request->currency;

        // $user = User::where('phone_number',$receiver_number)->first();
        $customer = Customer::where('phone',$receiver_number)->first();
        $receiverId = $customer->id;

        $r_account =  DB::connection('mysql2')
        ->table('wallets')
        ->where(['wallet_currency'=>$currency,'customer_id'=>$receiverId,'wallet_type'=>$request->compte_receiver])
        ->first();
        $racnumber = $r_account->wallet_code;
        
        $total = $amount + $fees;

        $response = $this->verifyUserBalance($total,$currency,$acnumber);

        
        if ($response['success'] == false) {
            Alert::error('Erreur', $response['message']);
            return redirect()->back();
        }
        else {
            $date = new DateController;
            $today = $date->todayDate();

            $code = new GenerateIdController;
            $reference = $code->reference();

            DB::beginTransaction();

            $wallets = DB::connection('mysql2')->table('wallets')->where(['wallet_currency'=>$currency,'wallet_code'=>$acnumber])->first();
            $previous_balance = $wallets->wallet_balance;
            try {
                $save = DB::connection('mysql2')->table('wallets')->where(['wallet_currency'=>$currency,'wallet_code'=>$acnumber])->update(['wallet_balance' => $previous_balance - $total,'updated_at' => $today]);
                if ($save) {

                    $wallets = DB::connection('mysql2')->table('wallets')->where(['wallet_currency'=>$currency,'wallet_code'=>$acnumber])->first();
                    $current_balance = $wallets->wallet_balance;
                    $this->creditReceiver($amount,$currency,$racnumber,$sender_number,$receiver_number,$fees);
                    DB::table('transactions')->insert(['user_id' => Auth::user()->id,'branche_id' => $this->branche_id(),'reference' => $reference,'currency' => $currency,'amount' => $amount,'previous_balance' => $previous_balance,'current_balance' => $current_balance,'fees' => $fees,'status' => 'Success','category' => 'transfert','transaction_from' => $sender_number,'transaction_to' => $receiver_number,'updated_at' => $today,'created_at' => $today]);
                    DB::commit();
                }

                Alert::success('Succès', 'Transfert effectué avec succès !');
                return redirect()->route('admin.transaction.all');
            } catch (\Exception $e) {
                DB::rollback();
                Alert::error('Erreur', 'Une erreur est survenue lors du transfert.');
                return redirect()->back();
            }

        }

        
    }

    public function verifyUserBalance($amount,$currency,$acnumber){
      
   
            $wallets = DB::connection('mysql2')->table('wallets')->where(['wallet_currency'=>$currency,'wallet_code'=>$acnumber])->first();

            $current_balance = $wallets->wallet_balance;
            if ($amount > $current_balance) {
                return ["success"=>false,"message"=>"Le solde de ce compte est insuffisant pour effectuer cette opération."];
            }
            else {
                return ["success"=>true];
            }


    }

    public function remboursement($id){

        $user_id =  Crypt::decrypt($id);

        $response = new CustomerController();
        $data = $response->getCustomerData($id);
        $pret = PretBancaire::where('customer_id',$user_id)->first();
        $data['pret'] = $pret;

        return view('backend.admin.operation.remboursement',$data);
        
    }

}