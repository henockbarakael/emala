<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\__init__;
use App\Http\Controllers\Controller;
use App\Http\Controllers\VerifyNumberController;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Session;
use RealRashid\SweetAlert\Facades\Alert;

class BrancheController extends Controller
{
    public function create(){
        $initialize = new __init__;
        $closed = $initialize->cash_session();
        $report = $initialize->fond_precedent();
        $report_cdf_on_c = $report['report_cdf_on_c'];
        $report_usd_on_c = $report['report_usd_on_c'];
        return view('admin.branche.emala.add', compact('closed','report_cdf_on_c','report_usd_on_c'));
    }

    public function index(){
        $branches = DB::table('branches')
        ->join('users','branches.user_id','users.id')
        ->join('bank_users','users.id','bank_users.user_id')
        ->join('bank_accounts','bank_users.id','bank_accounts.bank_user_id')
        ->select('users.firstname','branches.*','users.lastname','users.phone_number','bank_accounts.balance_cdf','bank_accounts.balance_usd','bank_accounts.bank_user_id')
        ->where('btype','=','Inner')->get();
        $initialize = new __init__;
        $closed = $initialize->cash_session();
        $report = $initialize->fond_precedent();
        $report_cdf_on_c = $report['report_cdf_on_c'];
        $report_usd_on_c = $report['report_usd_on_c'];
        return view('admin.branche.emala._list', compact('branches','closed','report_cdf_on_c','report_usd_on_c'));
    }

    public function master(){
        $branches = DB::table('branches')
        ->join('users','branches.user_id','users.id')
        ->join('bank_users','users.id','bank_users.user_id')
        ->join('bank_accounts','bank_users.id','bank_accounts.bank_user_id')
        ->select('users.firstname','branches.*','users.lastname','users.phone_number','bank_accounts.balance_cdf','bank_accounts.balance_usd','bank_accounts.bank_user_id')
        ->where('branches.btype','=','Parent')->get();
        $initialize = new __init__;
        $closed = $initialize->cash_session();
        $report = $initialize->fond_precedent();
        $report_cdf_on_c = $report['report_cdf_on_c'];
        $report_usd_on_c = $report['report_usd_on_c'];
        return view('admin.branche.emala.list', compact('branches','closed','report_cdf_on_c','report_usd_on_c'));
    }

    public function store(Request $request){

        $request->validate([
            'btownship'   => 'required|string|max:255',
            'bcity'   => 'required|string|max:255',
            'btype'   => 'required|string|max:255',
            'bemail'   => 'required|string|max:255',
        ]);

        $btownship = $request->btownship;
        $bcity = $request->bcity;
        $btype = $request->btype;
        $bid = $request->id;
        $bemail = $request->bemail;

        $new_user = new __init__;
        $response = $new_user->create_branche($bid,$btownship,$bcity,$btype,$bemail);
    
        if ($response['success'] == true) {
            Alert::success('Succès', $response['message']);
            return redirect()->back();
        }
        elseif ($response['success'] == false) {
            Alert::error('Echec', $response['message']);
            return redirect()->back();
        } 
    }

    public function topup(Request $request){
        $request->validate([
            'amount'   => 'required|string|max:255',
            'currency'   => 'required|string|max:255',
            'bank_user_id'   => 'required|string|max:255',
        ]);

        $bank_user_id = $request->bank_user_id;
        $amount = $request->amount;
        $currency = $request->currency;

        $details = new __init__;
        $response = $details->topup_agence($bank_user_id, $amount, $currency);

        if ($response['success'] == true) {
            Alert::success('Succès', $response['message']);
            return redirect()->back();
        }
        elseif ($response['success'] == false) {
            Alert::error('Echec', $response['message']);
            return redirect()->back();
        } 
    }

    // public function topup(Request $request){
    //     $request->validate([
    //         'amount'   => 'required|string|max:255',
    //         'gateway'   => 'required|string|max:255',
    //     ]);

    //     $gateway = $request->gateway;
    //     $currency = $request->currency;
    //     $amount = $request->amount;
    //     $agence_id = $request->agence_id;
     
    //     $details = new __init__;
    //     $response = $details->topup_agence($amount, $currency, $gateway, $agence_id);
    
    //     if ($response['success'] == true) {
    //         Alert::success('Succès', $response['message']);
    //         return redirect()->back();
    //     }
    //     elseif ($response['success'] == false) {
    //         Alert::error('Echec', $response['message']);
    //         return redirect()->back();
    //     } 
    // }

    public function delete(Request $request)
    {
        $user = Auth::User();
        Session::put('user', $user);
        $user = Session::get('user');
        $fullName     = $user->name;
        $email        = $user->email;
        $phone_number = $user->phone_number;
        $status       = $user->status;
        $role_name    = $user->role_name;
        $dt       = Carbon::now();
        $todayDate = $dt->toDayDateTimeString();
        $user_id = $request->id;

        $delete_user = new __init__;
        $response = $delete_user->delete_agence($fullName,$email,$phone_number,$status, $role_name, $todayDate, $user_id);

        if ($response['success'] == true) {
            Alert::success('Succès', $response['message']);
            return redirect()->back();
        }
        elseif ($response['success'] == false) {
            Alert::error('Echec', $response['message']);
            return redirect()->back();
        } 

    }

    public function update(Request $request){
        $request->validate([
            'btownship'   => 'required|string|max:255',
            'bcity'   => 'required|string|max:255',
            'btype'   => 'required|string|max:255',
            'bemail'   => 'required|string|max:255',
        ]);

        $btownship = $request->btownship;
        $bcity = $request->bcity;
        $btype = $request->btype;
        $bid = $request->id;
        $bemail = $request->bemail;

        $new_user = new __init__;
        $response = $new_user->update_branche($bid,$btownship,$bcity,$btype,$bemail);
       
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
