<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\__init__;
use App\Http\Controllers\API\CustomerAccountAPI;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use RealRashid\SweetAlert\Facades\Alert;

class CurrentController extends Controller
{
    public function index(){
        $accounts = DB::table('accounts')
        ->join('branches','accounts.branche_id','branches.id')
        ->join('users','accounts.user_id','users.id')
        ->join('wallets','accounts.id','wallets.account_id')
        ->select('accounts.*','users.firstname','users.lastname','users.phone_number','branches.btownship','wallets.balance_cdf','wallets.balance_usd')
        ->where('accounts.actype','current')
        ->distinct('accounts.acnumber')
        ->get();
        $initialize = new __init__;
        $closed = $initialize->cash_session();
        $report = $initialize->fond_precedent();
        $report_cdf_on_c = $report['report_cdf_on_c'];
        $report_usd_on_c = $report['report_usd_on_c'];
        return view('admin.account.current.list', compact('accounts','closed','report_cdf_on_c','report_usd_on_c'));
    }
    public function create(){
        return view('admin.account.current.add');
    }

    public function store(Request $request){
        $request->validate([
            'firstname'   => 'required|string|max:255',
            'lastname'   => 'required|string|max:255',
            'phone_number'   => 'required|string|max:255',
        ]);

        $firstname = $request->firstname;
        $lastname = $request->lastname;
        $phone_number = $request->phone_number;
        $email = $request->email;
        $ville = $request->ville;
        $adresse = $request->adresse;
        $from = "Back-office";
        $role = "Customer";

        $new_user = new __init__;
        $response = $new_user->create_customer($firstname,$lastname,$email,$phone_number,$adresse,$ville,$role,$from);

        if ($response['success'] == true) {
            Alert::success('Succès', $response['message']);
            return redirect()-> route('admin.liste_client');
        }
        elseif ($response['success'] == false) {
            Alert::error('Echec', $response['message']);
            return redirect()->back();
        } 
    }
}
