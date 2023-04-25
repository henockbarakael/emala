<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\DB;
use RealRashid\SweetAlert\Facades\Alert;
use App\Http\Controllers\generateReferenceController;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\__init__;
use App\Http\Controllers\VerifyNumberController;

class MobileMoneyController extends Controller
{
    public function create($id){
        $userid=  Crypt::decrypt($id);
       
        $customer = DB::table('accounts')
        ->join('branches','accounts.branche_id','branches.id')
        ->join('users','accounts.user_id','users.id')
        ->join('wallets','accounts.id','wallets.account_id')
        ->select('accounts.*','users.id AS user_id','users.avatar','users.firstname','users.lastname','users.middlename','users.phone_number','branches.btownship','wallets.balance_cdf','wallets.balance_usd','wallet_id')
        ->where('accounts.actype','current')
        ->where('users.id','=', $userid)
        ->distinct()
        ->first();

        $acnumber = $customer->acnumber;

        $initialize = new __init__;
        $closed = $initialize->cash_session();
        $report = $initialize->fond_precedent();
        $report_cdf_on_c = $report['report_cdf_on_c'];
        $report_usd_on_c = $report['report_usd_on_c'];

        return view('admin.transaction.transfert.mobile', compact('customer','closed','report_cdf_on_c','report_usd_on_c'));

    }

    public function store(Request $request){
        $request->validate([
            'amount'   => 'required|string|max:255',
            'currency'   => 'required|string|max:255',
            'method'   => 'required|string|max:255',
            'customer_number'   => 'required|string|max:255',
            'telco'   => 'required|string|max:255',
        ]);

        $exptid = $request->exptid;
        $method = $request->method;
        $currency = $request->currency;
        $amount = $request->amount;
        $mobile = $request->customer_number;
        $telco = $request->telco;

        $verify_number = new VerifyNumberController;
        $customer_number = $verify_number->verify_number($mobile);

        $cust = DB::table('users')->where('phone_number','=',$customer_number)->first();


        if ($cust == null) {
            return response()->json(['success'=>false,'data'=>"Le numéro ".$mobile." n'est pas enregistré dans la système!"]);
        }
        

       // dd($request->exptid);

        $launch = new __init__;

        $operator = $launch->verify_number_operator($customer_number);

        if ($telco != $operator) {
            Alert::error('Echec', "Vous avez choisi ".$telco." comme moyen de paiement. Cependant, le numéro entré est un numéro ".$operator.".");
            return redirect()->back();
        }
        $type = "transfert";
        $action = "credit";

        $auth = Auth::user()->id;

        $descritption = "transfert";

        $generate = new generateReferenceController;
        $reference = $generate->reference( $descritption);

        $userid = Auth::user()->id;

        $response = $launch->wallet_to_mobile($auth, $exptid, $currency, $amount, $method);
       //dd($response);
        $user = DB::table('users')->where('id','=',$exptid)->first();
        // $cust = DB::table('users')->where('phone_number','=',$customer_number)->first();
        $destid = $cust->id;
       // dd($destid);

        if ($method == "wallet_to_momo" ) {
            $medium_of_transaction = "virement";
            $expedieur = $launch->user_account_wallet($exptid);
            
            $s_acnumber = $expedieur->phone_number;
            if ($cust->role_name != "Customer") {
                $destinataire = $launch->cashier_account_wallet($destid);
            }
            else {
                $destinataire = $launch->user_account_wallet($destid);
            }

            $r_acnumber = $destinataire->phone_number;
        }
        elseif ($method == "cashier_to_momo" ) {
            $medium_of_transaction = "cash";
            $expedieur = $launch->cashier_account_wallet($userid);
           
            $s_acnumber = $expedieur->phone_number;
            if ($cust->role_name != "Customer") {
                $destinataire = $launch->cashier_account_wallet($destid);
            }
            else {
                $destinataire = $launch->user_account_wallet($destid);
            }
            $r_acnumber = $destinataire->phone_number;
        }

        $status_description = $response['message'];
        $generate = new generateReferenceController;
        $reference = $generate->reference($type);
        $branche_id = $launch->branche_id();

        $medium_of_transaction = "mobile money";
        //dd($response);
        $status = $response['status'];
        
        if ($response['success'] == true) {

            $callback_url = "https://emalafintech.net/api/v1/credit_callback.php";
            $merchant_id="jW]e%IY;ICOu7Hs4b";
            $merchant_secrete = "jz1rwlMY@ueJ1FkO@b";
    
            $url = 'https://paydrc.gofreshbakery.net/api/v5/';
            $curl_post_data = [
                 "merchant_id" => $merchant_id,
                 "merchant_secrete"=> $merchant_secrete,
                 "amount" => $amount,
                 "action" => "credit",
                 "customer_number" => $customer_number,
                 "currency" => $currency,
                 "firstname" =>"Emala",
                 "lastname" => "L&P",
                 "email" => "admin@lumumbaandpartners.com",
                 "method" => $telco,
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
                return response()->json(['status' => "Failed",'success'=>false,'data'=>$error,'message'=>$error]);
            }
    
            else {
                $curl_decoded = json_decode($curl_response,true);
                
                if ($curl_decoded != null) {
                    $launch->trandetails($medium_of_transaction,$action, $s_acnumber, $r_acnumber, $branche_id,$reference,$amount,$currency,$status,$status_description,$type);
                    return response()->json(['status' => "Successful",'success'=>true,'data'=>"Transfert effectué avec succès!"]);
                }
                else {
                    $launch->trandetails($medium_of_transaction,$action,$s_acnumber, $r_acnumber, $branche_id,$reference,$amount,$currency,$status,$status_description,$type);
                    return response()->json(['status' => "Successful",'success'=>false,'data'=>$curl_response]);
                }               
            }
        }

        elseif ($response['result'] == 1) {
            if ($method == "wallet_to_momo") {
                return response()->json(['status' => "Failed",'success'=>false,'data'=>"Le solde du ".$user->phone_number." est insuffisant!"]);
            }
            elseif ($method == "cashier_to_momo") {
                return response()->json(['status' => "Failed",'success'=>false,'data'=>"Le solde du ".Auth::user()->phone_number." est insuffisant!"]);
            }
        }
    }
}
