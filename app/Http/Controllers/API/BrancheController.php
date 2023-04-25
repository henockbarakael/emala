<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use RealRashid\SweetAlert\Facades\Alert;

class BrancheController extends Controller
{
    public function all(){
        $branches = DB::table('branches')->get();
       
        $users = User::where('role_name','Admin')->orWhere('role_name','Manager')->get();
        return view('backend.branche.list', compact('branches','users'));
    }
    public function create(Request $request){
        $request->validate([
            'township'   => 'required|string|max:255',
            'city'   => 'required|string|max:255',
            'phone'   => 'required|string|max:255',
            'email'   => 'required|string|max:255',
            'bname'   => 'required|string|max:255',
        ]);

        $data = [
            'township'=>$request->township,
            'city'=>$request->city,
            'phone'=>$request->phone,
            'email'=>$request->email,
        ];

        $township = $request->township;
        $city = $request->city;
        $phone = $request->phone;
        $bname = $request->bname;
        
        $user_id = Auth::user()->id;
        $email = $request->email;

        $new_user = new Initialize;
        $response = $new_user->create_branche($township,$city,$phone,$email,$user_id,$bname);

    
        if ($response['success'] == true) {
            Alert::success('Succès', $response['message']);
            return redirect()->back();
        }
        elseif ($response['success'] == false) {
            Alert::error('Echec', $response['message']);
            return redirect()->back();
        }
    }

    public function assign(Request $request){
        $request->validate([
            'id_gerant'   => 'required|string|max:255',
        ]);

        $user_id = $request->id_gerant;
        $branche_id = $request->branche_id;

        $new_user = new Initialize;
        $response = $new_user->assign($user_id,$branche_id);
        if ($response['success'] == true) {
            Alert::success('Succès', $response['message']);
            return redirect()->back();
        }
        elseif ($response['success'] == false) {
            Alert::error('Echec', $response['message']);
            return redirect()->back();
        }
    }

    public function UpdateBranche(Request $request){
        $request->validate([
            // 'branche_id'   => 'required|string|max:255',
            'bname'   => 'required|string|max:255',
            'phone'   => 'required|string|max:255',
            'email'   => 'required|string|max:255',
            'township'   => 'required|string|max:255',
            'city'   => 'required|string|max:255',
        ]);


        $branche_id = $request->branche_id;
        $bname = $request->bname;
        $bphone = $request->phone;
        $bcity = $request->city;
        $btownship = $request->township;
        $bemail = $request->email;

        $new_user = new Initialize;
        $response = $new_user->updatebranche($branche_id,$bname,$bphone,$bcity,$btownship,$bemail);
        if ($response['success'] == true) {
            Alert::success('Succès', $response['message']);
            return redirect()->back();
        }
        elseif ($response['success'] == false) {
            Alert::error('Echec', $response['message']);
            return redirect()->back();
        }
    }
    public function AllTreller(){
        $users = DB::table('users')
        ->join('accounts','users.id','accounts.user_id')
        ->join('branches','accounts.branche_id','branches.id')
        ->select('branches.user_id','users.*')
        ->where('users.role_name', "Cashier")
        ->where('branches.user_id', Auth::user()->id)
        ->distinct()
        ->get();
        return view('backend.branche.all_treller', compact('users'));
    }

    public function topup(Request $request){
        $request->validate([
            'amount'   => 'required|string|max:255',
        ]);

        $account_id = $request->account_id;
        $account_level = $request->account_level;
        $amount = $request->amount;
        $currency = $request->currency;

        
        $details = new Initialize;
        $response = $details->topup_account($amount, $account_id,$currency,$account_level);
       
        if ($response['success'] == true) {
            Alert::success('Succès', $response['message']);
            return redirect()->back();
        }
        elseif ($response['success'] == false) {
            Alert::error('Echec', $response['message']);
            return redirect()->back();
        } 
    }

    public function AllAccount(){
        $users = DB::table('users')
        ->join('accounts','users.id','accounts.user_id')
        ->join('branches','accounts.branche_id','branches.id')
        ->select('branches.user_id','users.*','accounts.id AS idaccount','accounts.balance','accounts.currency','accounts.acnumber','accounts.account_level')
        ->where('users.role_name', "Cashier")
        ->where('users.role_name', "!=", 'Admin')
        ->where('users.role_name', "!=", 'Root')
        ->where('users.role_name', "!=", 'Manager')
        ->where('users.role_name', "!=", 'Customer')
        ->where('branches.user_id', Auth::user()->id)
        ->get();
        return view('backend.branche.all_account', compact('users'));
    }
    public function Manager(){
        $users = DB::table('users')
        ->join('accounts','users.id','accounts.user_id')
        ->join('branches','accounts.branche_id','branches.id')
        ->select('branches.user_id','users.*','accounts.id AS idaccount','accounts.balance','accounts.currency','accounts.acnumber','accounts.account_level')
        ->where('users.role_name', "Admin")
        ->where('branches.user_id', Auth::user()->id)
        ->get();
        return view('backend.branche.manager', compact('users'));
    }

    public function StoreTreller(Request $request){
        $request->validate([
            'firstname'   => 'required|string|max:255',
            'lastname'   => 'required|string|max:255',
            'phone_number'   => 'required|string|max:255',
        ]);

        $firstname = $request->firstname;
        $lastname = $request->lastname;
        $telephone = $request->phone_number;

        $new_user = new Initialize;
        $response = $new_user->create_treller($firstname,$lastname,$telephone);

        if ($response['success'] == true) {
            Alert::success('Succès', $response['message']);
            return redirect()->back();
        }
        elseif ($response['success'] == false) {
            Alert::error('Echec', $response['message']);
            return redirect()->back();
        } 
    }

    public function topup_agence(){
        $branches = DB::table('users')
        ->join('accounts','users.id','accounts.user_id')
        ->join('branches','accounts.branche_id','branches.id')
        ->select('branches.bphone','branches.bcity','branches.btownship','branches.bemail','branches.fullname','branches.bname','branches.bcode','branches.user_id','users.*','accounts.id AS idaccount','accounts.balance','accounts.currency','accounts.acnumber','accounts.account_level')
        ->where('users.role_name', "Manager")
        ->get();
        return view('backend.branche.topup_branche', compact('branches'));
    }
    public function recharge_agence(Request $request){
        $request->validate([
            'amount'   => 'required|string|max:255',
        ]);

        $account_id = $request->account_id;
        $account_level = 1;
        $amount = $request->amount;
        $currency = $request->currency;

        
        $details = new Initialize;
        $response = $details->topup_account($amount, $account_id,$currency,$account_level);
       
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
