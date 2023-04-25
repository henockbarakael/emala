<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\__init__;
use App\Http\Controllers\Controller;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Session;
use RealRashid\SweetAlert\Facades\Alert;

class CashierController extends Controller
{
    public function create(){
        $role_name   = DB::table('role_type_users')->where('role_type','!=','Cashier')->where('role_type','!=','Customer')->where('role_type','!=','Root')->get();
        $initialize = new __init__;
        $closed = $initialize->cash_session();
        $report = $initialize->fond_precedent();
        $report_cdf_on_c = $report['report_cdf_on_c'];
        $report_usd_on_c = $report['report_usd_on_c'];
        return view('admin.cashier.create', compact('role_name','closed','report_cdf_on_c','report_usd_on_c'));
    }
    public function store(Request $request){
        $request->validate([
            'firstname'   => 'required|string|max:255',
            'lastname'   => 'required|string|max:255',
            'phone_number'   => 'required|string|max:255',
            'email'   => 'required|string|max:255',
            'adresse'   => 'required|string|max:255',
            'ville'   => 'required|string|max:255',
        ]);

        $firstname = $request->firstname;
        $lastname = $request->lastname;
        $telephone = $request->phone_number;
        $adresse = $request->adresse;
        $ville = $request->ville;
        $email = $request->email;
        $role = "Cashier";
        $from = "Back-office";

        //dd($btype);

        $new_user = new __init__;
        $response = $new_user->create_cashier($firstname,$lastname,$telephone,$adresse,$ville,$role,$email);

        if ($response['success'] == true) {
            Alert::success('Succès', $response['message']);
            return redirect()->back();
        }
        elseif ($response['success'] == false) {
            Alert::error('Echec', $response['message']);
            return redirect()->back();
        } 
    }

    public function index(){
        $cashiers = DB::table('users')->where('role_name','=','Cashier')->get();
        $initialize = new __init__;
        $closed = $initialize->cash_session();
        $report = $initialize->fond_precedent();
        $report_cdf_on_c = $report['report_cdf_on_c'];
        $report_usd_on_c = $report['report_usd_on_c'];
        return view('admin.cashier.all', compact('cashiers','closed','report_cdf_on_c','report_usd_on_c'));
    }

    public function delete(Request $request)
    {
        $user = Auth::User();
        Session::put('user', $user);
        $user = Session::get('user');
        $phone_number = $user->phone_number;
        $dt       = Carbon::now();
        $user_id = $request->id;

        $delete_user = new __init__;
        $response = $delete_user->delete_user($phone_number,$user_id);

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
            'firstname'   => 'required|string|max:255',
            'lastname'   => 'required|string|max:255',
            'phone_number'   => 'required|string|max:255',
            'password'  => 'required|string|min:5|confirmed',
            'password_confirmation' => 'required',
        ]);

        $firstname = $request->firstname;
        $lastname = $request->lastname;
        $phone_number = $request->phone_number;
        $password = $request->password;
        $user_id = $request->user_id;

        $new_user = new __init__;
        $response = $new_user->update_user($firstname,$lastname,$phone_number,$password, $user_id);

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
