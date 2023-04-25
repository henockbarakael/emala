<?php

namespace App\Http\Controllers\Manager;

use App\Http\Controllers\API\Initialize;
use App\Http\Controllers\Controller;
use App\Models\Account;
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
        if (Auth::user()->role_name == "Manager") {
            return view('manager.branche.list', compact('branches','users'));
        }
        elseif (Auth::user()->role_name == "Cashier") {
            return view('cahsier.branche.list', compact('branches','users'));
        }
        
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
        ->select('users.*','branches.user_id','branches.bname')
        ->where('users.role_name', "!=", 'Admin')
        ->where('users.role_name', "!=", 'Manager')
        ->where('users.role_name', "!=", 'Root')
        ->where('branches.user_id', Auth::user()->id)
        ->distinct()
        ->get();

        $count_users = DB::table('users')
        ->join('accounts','users.id','accounts.user_id')
        ->join('branches','accounts.branche_id','branches.id')
        ->select('users.*','branches.user_id','branches.bname')
        ->where('users.role_name', "!=", 'Admin')
        ->where('users.role_name', "!=", 'Manager')
        ->where('users.role_name', "!=", 'Root')
        ->where('branches.user_id', Auth::user()->id)
        ->distinct('users.id')
        ->count();


        if (Auth::user()->role_name == "Manager") {
            return view('manager.branche.all_treller', compact('users','count_users'));
        }
        elseif (Auth::user()->role_name == "Cashier") {
            return view('cashier.branche.all_treller', compact('users','count_users'));
        }
        
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
        if (Auth::user()->role_name == "Manager") {
            return view('manager.branche.all_account', compact('users'));
        }
        elseif (Auth::user()->role_name == "Cashier") {
            return view('cashier.branche.all_account', compact('users'));
        }
        
    }
    public function Manager(){
        
        $admins = DB::table('users')->where('role_name', 'Admin')->get();
        if (Auth::user()->role_name == "Manager") {
            $users = DB::table('users')
        ->join('accounts','users.id','accounts.user_id')
        ->join('branches','accounts.branche_id','branches.id')
        ->select('branches.user_id','users.*','accounts.id AS idaccount','accounts.balance','accounts.currency','accounts.acnumber','accounts.account_level','branches.bname')
        ->where('users.role_name', "Manager")
        ->where('branches.user_id', Auth::user()->id)
        ->get();
            return view('manager.branche.manager', compact('users','admins'));
        }
        elseif (Auth::user()->role_name == "Cashier") {
            $agence = Account::where('user_id',Auth::user()->id)->first();
            $brancheId = $agence->branche_id;

            $account = Account::where('branche_id',$brancheId)->where('account_level',2)->first();
            $userId = $account->user_id;

            $admins = DB::table('users')->where('id', $userId)->first();
            $users = DB::table('users')
        ->join('accounts','users.id','accounts.user_id')
        ->join('branches','accounts.branche_id','branches.id')
        ->select('branches.user_id','users.*','accounts.id AS idaccount','accounts.balance','accounts.currency','accounts.acnumber','accounts.account_level','branches.bname')
        ->where('users.role_name', "Cashier")
        ->where('accounts.user_id', Auth::user()->id)
        ->get();
            return view('cashier.branche.manager', compact('users','admins'));
        }
        
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
}
