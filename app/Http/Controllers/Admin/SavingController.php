<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\__init__;
use App\Http\Controllers\API\CustomerAccountAPI;
use App\Http\Controllers\Controller;
use App\Http\Controllers\VerifyNumberController;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Session;
use RealRashid\SweetAlert\Facades\Alert;

class SavingController extends Controller
{
    public function index(){
        $accounts = DB::table('accounts')
        ->join('branches','accounts.branche_id','branches.id')
        ->join('users','accounts.user_id','users.id')
        ->join('wallets','accounts.id','wallets.account_id')
        ->select('accounts.*','users.firstname','users.lastname','users.phone_number','branches.btownship','wallets.balance_cdf','wallets.balance_usd')
        ->where('accounts.actype','saving')
        ->distinct()
        ->get();

        $initialize = new __init__;
        $closed = $initialize->cash_session();
        $report = $initialize->fond_precedent();
        $report_cdf_on_c = $report['report_cdf_on_c'];
        $report_usd_on_c = $report['report_usd_on_c'];

        //dd($accounts);
        return view('admin.account.saving.list', compact('accounts','closed','report_cdf_on_c','report_usd_on_c'));
    }

    public function autocomplete(Request $request)
    {
        return User::select("phone_number")
                    ->where('phone_number', 'LIKE', "%{$request->term}%")
                    ->pluck('phone_number');
    }

    public function request(Request $request){
        $phone_number = $request->phone_number;
        $user = DB::table('users')->select('firstname','lastname')->where('phone_number',$phone_number)->first();
        // Store it in a array
        $firstname = $user->firstname;
        $lastname = $user->lastname;
        $result = array("$firstname", "$lastname");
        
        // Send in JSON encoded form
        $myJSON = json_encode($result);
        echo $myJSON;
    }

    public function verify(){
        $initialize = new __init__;
        $closed = $initialize->cash_session();
        $report = $initialize->fond_precedent();
        $report_cdf_on_c = $report['report_cdf_on_c'];
        $report_usd_on_c = $report['report_usd_on_c'];
        return view('admin.account.saving.verify', compact('closed','report_cdf_on_c','report_usd_on_c'));
    }

    public function verify_post(Request $request){

        $verify_number = new VerifyNumberController;
        $mobile = $verify_number->verify_number($request->phone_number);
        
        $user = DB::table('users')->select('id','firstname','lastname','phone_number')->where('phone_number',$mobile)->first();
        
        if ($user != null) {
            $userid = $user->id;
            Session::put('user_id', $userid);
            $firstname = $user->firstname;
            $lastname = $user->lastname;
            $customer_number = $user->phone_number;
            return response()->json(['success'=>true,'firstname'=>$firstname,'lastname'=>$lastname,'customer_number'=>$customer_number]); 
        }

        else {
            return response()->json(['success'=>false]); 
        }
        
        
    }

    public function create(){
        return view('admin.account.saving.add');
    }

    public function store(Request $request){
        $request->validate([
            'currency'   => 'required|string|max:255',
            'startDate'   => 'required|string|max:255',
            'endDate'   => 'required|string|max:255',
        ]);

        $sender_phone = $request->sender_phone;
        $currency = $request->currency;
        $startDate = $request->startDate;
        $endDate = $request->endDate;
        $from = "Back-office";
        $getCustomerAccount = new CustomerAccountAPI;
        $response = $getCustomerAccount->create_saving_account($sender_phone,$currency,$startDate,$endDate, $from);

        if ($response['success'] == true) {
            Alert::success('Succès', $response['message']);
            return redirect()->back();
        }
        elseif ($response['success'] == false) {
            Alert::error('Echec', $response['message']);
            return redirect()->back();
        } 
    }
}
