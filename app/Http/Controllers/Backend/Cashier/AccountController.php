<?php

namespace App\Http\Controllers\Backend\Cashier;

use App\Http\Controllers\Backend\DateController;
use App\Http\Controllers\Backend\GenerateIdController;
use App\Http\Controllers\Controller;
use App\Http\Controllers\Emala\API\Customer\OperationController;
use App\Models\Cashier;
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
use Illuminate\Validation\ValidationException;
use RealRashid\SweetAlert\Facades\Alert;

class AccountController extends Controller
{

    public function pret($id)
    {
        $response = new CustomerController();
        $data = $response->getCustomerData($id);

        return view('backend.cashier.operation.pret', $data);
    }

  
    public function demandePost(Request $request)
    {
       
        $request->validate([
            'loan_amount' => 'required|string|max:255',
            'phone' => 'required|string|max:255',
            'surname' => 'required|string|max:255',
            'name' => 'required|string|max:255',
            'loan_duration' => 'required|string|max:255',
            'loan_currency' => 'required|string|max:255',
            'echeance' => 'required|in:monthly,weekly,daily',
            'principal_paid' => 'required|string|max:255',
            'interest_rate' => 'required|string|max:255',
        ]);

        try {
            $phone_number = $request->phone;
            $loan_amount = $request->loan_amount;
            $loan_currency = $request->loan_currency;
            $loan_duration = $request->loan_duration;
            $payment_frequency = $request->echeance;
            $principal_paid = $request->principal_paid;
            $interest_rate = $request->interest_rate;
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
                'loan_status' => 'En attente',
                'loan_duration' => $loan_duration,
                'loan_currency' => $loan_currency,
                'principal_paid' => $principal_paid,
                'payment_frequency' => $payment_frequency,
                'first_payment' => $premier_echeance,
                'interest_rate' => $interest_rate,
                'customer_id' => $customer_id
            ]);

            $cashier = Cashier::where('user_id',Auth::user()->id)->first();
      
            $agencyId = $cashier->agency->id;

            $pretBancaire->agency_id = $agencyId;
            $pretBancaire->processed_by = Auth::user()->id;
            $pretBancaire->save();

            Alert::success('Succès', 'Demande de prêt enregistrée avec succès !');
            return redirect()->route('cashier.pret.index');
        } catch (\Exception $e) {
            Alert::error('Erreur', $e->getMessage());
            return redirect()->back();
        }
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
      
      
        return view('backend.cashier.customer.account', compact('data'));
    }

    public function clientPhone($id){
        $phone_number =  Crypt::decrypt($id);
      
        $response = new CustomerController();
        $data = $response->getCustomerDataByPhone($phone_number);
        $user = Customer::where('phone',$phone_number)->first();
        $pret = Loan::where('customer_id',$user->id)->first();
        $data['pret'] = $pret;
        return view('backend.cashier.customer.account',compact('data'));

    }

    public function client_depot($id){

        $response = new CustomerController();
        $data = $response->getCustomerData($id);
        return view('backend.cashier.operation.depot',$data);
        
    }

    public function client_depot_save(Request $request){

        $request->validate([
            'amount'   => 'required|string|max:255',
            'currency'   => 'required|string|max:255',
            'compte'   => 'required|string|max:255',
            'acnumber'   => 'required|string|max:255'
        ]);

        $request->validate([
            'amount'   => 'required|string|max:255',
            'phone'    => 'required|numeric',
            'compte'   => 'required|string|max:255',
            'currency' => 'required|string|max:255',
        ]);

        $customer_number = $request->receiver_phone;
        $acnumber = $request->acnumber;
        $amount = $request->amount;
        $currency = $request->currency;

        // Récupération des données du formulaire
        $customer_number = $request->phone;
        $walletType = $request->compte;
        $amount = $request->amount;
        $currency = $request->currency;

        // Recherche du client
        $customer = Customer::where('phone', $customer_number)->first();
        $customerId = $customer->id;

        $user = new User();
        $cashierId = $user->getCashierId();

        $data = new OperationController;
        $response = $data->deposit($amount, $currency, $customerId, $cashierId, $walletType);
        $message = $response->getData()->message;
        $status = $response->getData()->status;

        if ($status == true) {
            Alert::success('Succès', $message);
            return redirect()->route('cashier.transaction.all');
        } else {
            Alert::error('Erreur', $message);
            return redirect()->back();
        }
    }


    public function client_retrait($id){
        $response = new CustomerController();
        $data = $response->getCustomerData($id);
        
        return view('backend.cashier.operation.retrait',$data);
        
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
                    return redirect()->route('cashier.transaction.all');
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

        return view('backend.cashier.operation.transfert',$data);
        
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

    public function deposit(){

        return view('backend.cashier.operation.deposit');
        
    }

    public function processDeposit(Request $request)
    {
       
            // Validation des données du formulaire
            $request->validate([
                'amount'   => 'required|string|max:255',
                'phone'    => 'required|numeric',
                'compte'   => 'required|string|max:255',
                'currency' => 'required|string|max:255',
            ]);

            // Récupération des données du formulaire
            $customer_number = $request->phone;
            $walletType = $request->compte;
            $amount = $request->amount;
            $currency = $request->currency;

            // Recherche du client
            $customer = Customer::where('phone', $customer_number)->first();
            $customerId = $customer->id;

            $user = new User();
            $cashierId = $user->getCashierId();

            $data = new OperationController;
            $response = $data->deposit($amount, $currency, $customerId, $cashierId, $walletType);
          
            $message = $response->getData()->message;
            $status = $response->getData()->status;

            if ($status == true) {
                Alert::success('Succès', $message);
                return redirect()->route('cashier.transaction.all');
            } else {
                Alert::error('Erreur', $message);
                return redirect()->back();
            }


    }

    public function transfer(){

        return view('backend.cashier.operation.transfer');
        
    }

    public function processTransfer(Request $request)
    {
       
        // Validation des données du formulaire
        $request->validate([
            'amount'   => 'required|string|max:255',
            // 'phone'    => 'required|numeric',
            // 'transfert'   => 'required|string|max:255',
            'currency' => 'required|string|max:255',
            'sender_phone' => 'required|string|max:255',
            'sender_first' => 'required|string|max:255',
            'sender_last' => 'required|string|max:255',
            'receiver_phone' => 'required|string|max:255',
            'receiver_first' => 'required|string|max:255',
            'receiver_last' => 'required|string|max:255',
            'fees' => 'required|string|max:255',

        ]);

        // Récupération des données du formulaire
        $sender_phone = $request->sender_phone;
        $sender_first = $request->sender_first;
        $sender_last = $request->sender_last;
        $receiver_phone = $request->receiver_phone;
        $receiver_first = $request->receiver_first;
        $receiver_last = $request->receiver_last;
        // $transferType = $request->transfert;
        $amount = $request->amount;
        $currency = $request->currency;
        $fees = $request->fees;

        $user = new User();
        $cashierId = $user->getCashierId();

        $senderName = $sender_first.' '.$sender_last;
        $receiverName = $receiver_first.' '.$receiver_last;

        $data = new OperationController;
        $response = $data->cashTransfer($senderName, $receiverName, $sender_phone, $receiver_phone, $amount, $fees, $currency, $cashierId);
        // dd($response);
        $message = $response->getData()->message;
        $status = $response->getData()->status;

        if ($status == true) {
            Alert::success('Succès', $message);
            return redirect()->route('cashier.transaction.all');
        } else {
            Alert::error('Erreur', $message);
            return redirect()->back();
        }
            
    }

    public function withdraw(){

        return view('backend.cashier.operation.withdraw');
        
    }

    public function processWithdraw(Request $request)
    {
       
            // Validation des données du formulaire
            $request->validate([
                'amount'   => 'required|string|max:255',
                'phone'    => 'required|numeric',
                'compte'   => 'required|string|max:255',
                'currency' => 'required|string|max:255',
                'fees' => 'required|string|max:255',
            ]);

            // Récupération des données du formulaire
            $customer_number = $request->phone;
            $walletType = $request->compte;
            $amount = $request->amount;
            $currency = $request->currency;
            $fees = $request->fees;

            // Recherche du client
            $customer = Customer::where('phone', $customer_number)->first();
            $customerId = $customer->id;

            $user = new User();
            $cashierId = $user->getCashierId();

            $data = new OperationController;
            $response = $data->withdraw($amount, $currency, $customerId, $cashierId, $walletType, $fees);
         
            $message = $response->getData()->message;
            $status = $response->getData()->status;

            if ($status == true) {
                Alert::success('Succès', $message);
                return redirect()->route('cashier.transaction.all');
            } else {
                Alert::error('Erreur', $message);
                return redirect()->back();
            }
            


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
                return redirect()->route('cashier.transaction.all');
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

        return view('backend.cashier.operation.remboursement',$data);
        
    }

}
