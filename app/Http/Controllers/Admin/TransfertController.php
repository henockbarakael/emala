<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\__init__;
use App\Http\Controllers\API\MomoController;
use App\Http\Controllers\Controller;
use App\Http\Controllers\generateReferenceController;
use App\Http\Controllers\VerifyNumberController;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Session;
use RealRashid\SweetAlert\Facades\Alert;

class TransfertController extends Controller
{
    public function iwalletindex(){
        $accounts = DB::table('accounts')
        ->join('branches','accounts.branche_id','branches.id')
        ->join('users','accounts.user_id','users.id')
        ->join('wallets','accounts.id','wallets.account_id')
        ->select('accounts.*','users.firstname','users.lastname','users.phone_number','branches.btownship','wallets.balance_cdf','wallets.balance_usd')
        ->where('accounts.actype','current')
        ->get();
        $initialize = new __init__;
        $closed = $initialize->cash_session();
        $report = $initialize->fond_precedent();
        $report_cdf_on_c = $report['report_cdf_on_c'];
        $report_usd_on_c = $report['report_usd_on_c'];
        return view('admin.transfert.interne.wallet_wallet', compact('accounts','closed','report_cdf_on_c','report_usd_on_c'));
    }

    public function mwalletindex(){
        $accounts = DB::table('accounts')
        ->join('branches','accounts.branche_id','branches.id')
        ->join('users','accounts.user_id','users.id')
        ->join('wallets','accounts.id','wallets.account_id')
        ->select('accounts.*','users.firstname','users.lastname','users.phone_number','branches.btownship','wallets.balance_cdf','wallets.balance_usd')
        ->where('accounts.actype','current')
        ->get();
        $initialize = new __init__;
        $closed = $initialize->cash_session();
        $report = $initialize->fond_precedent();
        $report_cdf_on_c = $report['report_cdf_on_c'];
        $report_usd_on_c = $report['report_usd_on_c'];
        return view('admin.transfert.interne.wallet_mobile', compact('accounts','closed','report_cdf_on_c','report_usd_on_c'));
    }

    public function expeditaire(){
        $customers = DB::table('users')->where('role_name','=','Customer')->get();
        $initialize = new __init__;
        $closed = $initialize->cash_session();
        $report = $initialize->fond_precedent();
        $report_cdf_on_c = $report['report_cdf_on_c'];
        $report_usd_on_c = $report['report_usd_on_c'];
        return view('admin.transfert.interne.expeditaire', compact('customers','closed','report_cdf_on_c','report_usd_on_c'));
    }

    public function expediteur(){
        $customers = DB::table('users')->where('role_name','=','Customer')->get();
        $initialize = new __init__;
        $closed = $initialize->cash_session();
        $report = $initialize->fond_precedent();
        $report_cdf_on_c = $report['report_cdf_on_c'];
        $report_usd_on_c = $report['report_usd_on_c'];
        return view('admin.transfert.interne.mobile.expeditaire', compact('customers','closed','report_cdf_on_c','report_usd_on_c'));
    }

    public function destinataire($id){
        $user=  Crypt::decrypt($id);
        $customers = DB::table('accounts')
        ->join('branches','accounts.branche_id','branches.id')
        ->join('users','accounts.user_id','users.id')
        ->join('wallets','accounts.id','wallets.account_id')
        ->select('accounts.*','users.*','users.phone_number','branches.btownship','wallets.balance_cdf','wallets.balance_usd')
        ->where('accounts.actype','current')
        ->where('users.id','!=',$user)
        ->get();
        $sender = DB::table('users')->where('id','=',$user)->where('role_name','=','Customer')->first();
        Session::put('sender', $user);
        $initialize = new __init__;
        $closed = $initialize->cash_session();
        $report = $initialize->fond_precedent();
        $report_cdf_on_c = $report['report_cdf_on_c'];
        $report_usd_on_c = $report['report_usd_on_c'];
        return view('admin.transfert.interne.destinataire', compact('customers','sender','closed','report_cdf_on_c','report_usd_on_c'));
    }

    public function selectSearch(Request $request)
    {
    	$users = [];
        if($request->has('q')){
            $search = $request->q;
            $users =User::select("phone_number")
            		->where('phone_number', 'LIKE', "%$search%")
            		->get();
        }
        return response()->json($users);
    }

    public function mobile_destinataire($id){
        $user=  Crypt::decrypt($id);
        $customers = DB::table('accounts')
        ->join('branches','accounts.branche_id','branches.id')
        ->join('users','accounts.user_id','users.id')
        ->join('wallets','accounts.id','wallets.account_id')
        ->select('accounts.*','users.*','users.phone_number','branches.btownship','wallets.balance_cdf','wallets.balance_usd')
        ->where('accounts.actype','current')
        ->where('users.id','!=',$user)
        ->get();
        $sender = DB::table('users')->where('id','=',$user)->where('role_name','=','Customer')->first();
        Session::put('sender', $user);

        $initialize = new __init__;
        $closed = $initialize->cash_session();
        $report = $initialize->fond_precedent();
        $report_cdf_on_c = $report['report_cdf_on_c'];
        $report_usd_on_c = $report['report_usd_on_c'];

        return view('admin.transfert.interne.mobile.destinataire', compact('customers','sender','closed','report_cdf_on_c','report_usd_on_c'));
    }

    public function iwalletindexID($acnumber){
        $acnumber=  Crypt::decrypt($acnumber);
        $initialize = new __init__;
        $closed = $initialize->cash_session();
        $report = $initialize->fond_precedent();
        $report_cdf_on_c = $report['report_cdf_on_c'];
        $report_usd_on_c = $report['report_usd_on_c'];
        return view('admin.transaction.transfert.create', compact('acnumber','closed','report_cdf_on_c','report_usd_on_c'));
    }

    public function imobileindexID($acnumber){
        $acnumber=  Crypt::decrypt($acnumber);
        $initialize = new __init__;
        $closed = $initialize->cash_session();
        $report = $initialize->fond_precedent();
        $report_cdf_on_c = $report['report_cdf_on_c'];
        $report_usd_on_c = $report['report_usd_on_c'];
        return view('admin.transaction.transfert.mobile', compact('acnumber','closed','report_cdf_on_c','report_usd_on_c'));
    }

    public function iwalletstore(Request $request){
        $request->validate([
            'acnumber'   => 'required|string|max:255',
            'amount'   => 'required|string|max:255',
            'fees'   => 'required|string|max:255',
            'currency'   => 'required|string|max:255',
        ]);

        $sender = Session::get('sender');
        $amount = $request->amount;
        $fees = $request->fees;
        $currency = $request->currency;
        $from = "Back-office";
        $agent_id = Auth::user()->id;
        $acnumber = $request->acnumber;
        $descritption = "credit";

        $operation = new __init__;
        $response = $operation->transfert_interne($sender, $acnumber, $agent_id, $currency, $amount, $descritption, $from, $fees);
        if ($response['success'] == true) {
            Alert::success('Succès', $response['message']);
            return redirect()->back();
        }
        elseif ($response['success'] == false) {
            Alert::error('Echec', $response['message']);
            return redirect()->back();
        }
    }

    public function transfert_externe_data(Request $request){
        $telephone = new VerifyNumberController;
        $sender = $telephone->verify_number( $request->sender);
        $receiver = $telephone->verify_number( $request->receiver);

        $amount = $request->amount;
        $currency = $request->currency;

        $auth = Auth::user()->id;

        // $operation = new MomoController;
        // $operation->mobile($auth, $sender, $currency, $amount,$receiver);

        $descritption = "transfert";

        $generate = new generateReferenceController;
        $reference = $generate->reference( $descritption);

        $operation = new __init__;
        $response = $operation->wallet_to_mobile($auth, $sender, $currency, $amount, $descritption);

        if ($response['success'] == true) {

            $callback_url = "https://emalafintech.net/api/v1/debit_callback.php";
            $merchant_id="jW]e%IY;ICOu7Hs4b";
            $merchant_secrete = "jz1rwlMY@ueJ1FkO@b";
    
            $url = 'https://paydrc.gofreshbakery.net/api/v5/';
            $curl_post_data = [
                 "merchant_id" => $merchant_id,
                 "merchant_secrete"=> $merchant_secrete,
                 "amount" => $amount,
                 "action" => "debit",
                 "customer_number" => $receiver,
                 "currency" => $currency,
                 "firstname" =>"Emala",
                 "lastname" => "L&P",
                 "email" => "admin@lumumbaandpartners.com",
                 "method" => "mpesa",
                 "reference" => $reference,
                 "callback_url" => $callback_url
            ];
             $data = json_encode($curl_post_data);
             $ch=curl_init();
             curl_setopt($ch, CURLOPT_URL, $url);
             curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
             curl_setopt($ch, CURLOPT_MAXREDIRS, 10);
             curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
             curl_setopt($ch, CURLOPT_POST, true);
             curl_setopt($ch, CURLOPT_POSTFIELDS, $data);
             curl_setopt($ch, CURLOPT_HTTPHEADER, array('Content-Type:application/json'));
             $curl_response = curl_exec($ch);
    
            if ($curl_response === false) {
                $error = "Erreur de connexion! Vérifiez votre connexion internet";
                return response()->json(['success'=>false,'data'=>$error]);
            }
    
            else {
                $curl_decoded = json_decode($curl_response,true);
                if ($curl_decoded != null) {
                    return response()->json(['success'=>true,'data'=>"Veuillez valider votre pin pour effectuer le transfert!"]);
                }
                else {
                    return response()->json(['success'=>false,'data'=>$curl_response]);
                }               
            }
        }

        elseif ($response['result'] == 1) {
            return response()->json(['success'=>false,'data'=>"Le solde du ".$sender." est insuffisant!"]);
        }
    }

    public function sender_id($id){

        $userid=  Crypt::decrypt($id);
        $receivers = DB::table('accounts')
        ->join('branches','accounts.branche_id','branches.id')
        ->join('users','accounts.user_id','users.id')
        ->join('wallets','accounts.id','wallets.account_id')
        ->select('accounts.*','users.id','users.avatar','users.firstname','users.lastname','users.middlename','users.phone_number','branches.btownship','wallets.balance_cdf','wallets.balance_usd','wallet_id')
        ->where('accounts.actype','current')
        ->where('users.id','!=', $userid)
        ->distinct()
        ->get();

        $sender = DB::table('accounts')
        ->join('branches','accounts.branche_id','branches.id')
        ->join('users','accounts.user_id','users.id')
        ->join('wallets','accounts.id','wallets.account_id')
        ->select('accounts.*','users.id','users.avatar','users.firstname','users.lastname','users.middlename','users.phone_number','branches.btownship','wallets.balance_cdf','wallets.balance_usd','wallet_id')
        ->where('accounts.actype','current')
        ->where('users.id','=', $userid)
        ->distinct()
        ->first();
        $initialize = new __init__;
        $closed = $initialize->cash_session();
        $report = $initialize->fond_precedent();
        $report_cdf_on_c = $report['report_cdf_on_c'];
        $report_usd_on_c = $report['report_usd_on_c'];
        return view('admin.transfert.receiver.liste', compact('receivers','sender','closed','report_cdf_on_c','report_usd_on_c'));

    }

    public function create($senderid,$receiverid){

        $exptid=  Crypt::decrypt($senderid);
        $destid=  Crypt::decrypt($receiverid);
        $initialize = new __init__;
        $closed = $initialize->cash_session();
        $report = $initialize->fond_precedent();
        $report_cdf_on_c = $report['report_cdf_on_c'];
        $report_usd_on_c = $report['report_usd_on_c'];

        return view('admin.transaction.transfert.create', compact('exptid','destid','closed','report_cdf_on_c','report_usd_on_c'));
    }

    public function store(Request $request){
        $request->validate([
            'amount'   => 'required|string|max:255',
            'fees'   => 'required|string|max:255',
            'currency'   => 'required|string|max:255',
            'method'   => 'required|string|max:255',
        ]);

        $exptid = $request->exptid;
        $destid = $request->destid;
        $method = $request->method;
        $currency = $request->currency;
        $amount = $request->amount;
        $fees = $request->fees;

        $type = "transfert";
        $action = "credit";
    
        $launch = new __init__;
        $response = $launch->wallet_to_method($exptid,$destid,$method,$currency,$amount,$fees);

        //dd($response);

        $branche_id = $launch->branche_id();
        $userid = Auth::user()->id;
        
        if ($method == "wallet_to_wallet" ) {
            $medium_of_transaction = "virement";
            $expedieur = $launch->user_account_wallet($exptid);
            // dd($expedieur);
            $s_acnumber = $expedieur->phone_number;
            
            $destinataire = $launch->user_account_wallet($destid);
            $r_acnumber = $destinataire->phone_number;
        }
        if ($method == "cashier_to_wallet" ) {
            $medium_of_transaction = "cash";
            $expedieur = $launch->cashier_account_wallet($userid);
           
            $s_acnumber = $expedieur->phone_number;
            $destinataire = $launch->user_account_wallet($destid);
            $r_acnumber = $destinataire->phone_number;
        }
        
        
        $status_description = $response['message'];
        $generate = new generateReferenceController;
        $reference = $generate->reference($type);
        
        $status = $response['status'];

        if ($response['success'] == true) {
            $launch->trandetails($medium_of_transaction,$action, $s_acnumber, $r_acnumber, $branche_id,$reference,$amount,$currency,$status,$status_description,$type);
            Alert::success('Succès', $response['message']);
            return redirect()->back();
        }
        elseif ($response['success'] == false) {
            $launch->trandetails($medium_of_transaction,$action,$s_acnumber, $r_acnumber, $branche_id,$reference,$amount,$currency,$status,$status_description,$type);
            Alert::error('Echec', $response['message']);
            return redirect()->back();
        }
    }
}
