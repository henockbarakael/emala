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

class CustomerController extends Controller
{
    public function create(){
        $initialize = new __init__;
        $closed = $initialize->cash_session();
        $report = $initialize->fond_precedent();
        $report_cdf_on_c = $report['report_cdf_on_c'];
        $report_usd_on_c = $report['report_usd_on_c'];
        return view('admin.customer.add',compact('closed','report_cdf_on_c','report_usd_on_c'));
    }

    public function index(){
        $customers = DB::table('users')->where('role_name','=','Customer')->get();
        $initialize = new __init__;
        $closed = $initialize->cash_session();
        $report = $initialize->fond_precedent();
        $report_cdf_on_c = $report['report_cdf_on_c'];
        $report_usd_on_c = $report['report_usd_on_c'];
        return view('admin.customer.list', compact('customers','closed','report_cdf_on_c','report_usd_on_c'));
    }

    public function store(Request $request){
        $request->validate([
            'firstname'   => 'required|string|max:255',
            'lastname'   => 'required|string|max:255',
            'phone_number'   => 'required|string|max:255',
            'adresse'   => 'required|string|max:255',
            'ville'   => 'required|string|max:255',
        ]);

        $firstname = $request->firstname;
        $lastname = $request->lastname;
        $telephone = $request->phone_number;
        $adresse = $request->adresse;
        $ville = $request->ville;
        $role = "Customer";
        $from = "Back-office";
        $email ="";

        $new_user = new __init__;
        $response = $new_user->create_customer($firstname,$lastname,$email,$telephone,$adresse,$ville,$role,$from);

        if ($response['success'] == true) {
            Alert::success('Succès', $response['message']);
            return redirect()-> route('admin.liste_client');
        }
        elseif ($response['success'] == false) {
            Alert::error('Echec', $response['message']);
            return redirect()->back();
        } 
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
        $response = $delete_user->delete_customer($phone_number,$user_id);

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
        $response = $new_user->update_customer($firstname,$lastname,$phone_number,$password, $user_id);

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
