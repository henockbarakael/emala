<?php

namespace App\Http\Controllers\Manager;

use App\Http\Controllers\API\CustomerAPI;
use App\Http\Controllers\API\Initialize;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\DB;

class AccountController extends Controller
{
    public function client($id){
        $user_id =  Crypt::decrypt($id);
        //    dd($user_id);
        $initialize = new Initialize;
        $response = $initialize->compte_client($user_id);
     
        if ($response['success'] == true) {
            $id_user = $response['data']['0']['0']->id;
            $firstname = $response['data']['0']['0']->firstname;
            $lastname = $response['data']['0']['0']->name;
            $email = $response['data']['0']['0']->email;
            $role_name = $response['data']['0']['0']->role_name;
            $avatar = $response['data']['0']['0']->avatar;
            $join_date = $response['data']['0']['0']->created_at;
            $phone_number = $response['data']['0']['0']->phone;
            $address = $response['data']['0']['0']->address;
            $city = $response['data']['0']['0']->city;
            $phone = $response['data']['0']['0']->phone;

            $current_wallet_code    = $response['data']['0']['1']->wallet_code;
            $current_balance_cdf    = $response['data']['0']['1']->wallet_balance;
            $current_balance_usd    = $response['data']['0']['2']->wallet_balance;
            $saving_wallet_code     = $response['data']['0']['3']->wallet_code;
            $saving_balance_cdf     = $response['data']['0']['3']->wallet_balance;
            $saving_balance_usd     = $response['data']['0']['4']->wallet_balance;


            $transaction1 = DB::table('transactions')->where('sender_phone',$phone_number)->where('type','!=','depot')->limit(5)->orderBy('transaction_date','desc')->get();
            $transaction2 = DB::connection('mysql2')->table('transactions')->where('sender_phone',$phone_number)->where('type','!=','depot')->limit(5)->orderBy('transaction_date','desc')->get();
            $transaction = $transaction1->union($transaction2)->sortByDesc('transaction_date');
            
            $depot1 = DB::table('transactions')->where('receiver_phone',$phone)->where('type','depot')->limit(5)->orderBy('transaction_date','desc')->get();
            $depot2 = DB::connection('mysql2')->table('transactions')->where('receiver_phone',$phone)->where('type','depot')->limit(5)->orderBy('transaction_date','desc')->get();
            $depot = $transaction1->union($transaction2)->sortByDesc('transaction_date');


            if (Auth::user()->role_name == "Manager") {
                return view('manager.account.client',compact('transaction','id_user','avatar','role_name','city','address','phone_number','join_date','email','lastname','firstname','current_balance_cdf','current_balance_usd','saving_balance_cdf','saving_balance_usd','saving_wallet_code','current_wallet_code','depot'));
            }
            elseif (Auth::user()->role_name == "Cashier") {
                return view('cashier.account.client',compact('transaction','id_user','avatar','role_name','city','address','phone_number','join_date','email','lastname','firstname','current_balance_cdf','current_balance_usd','saving_balance_cdf','saving_balance_usd','saving_wallet_code','current_wallet_code','depot'));
            }
            
            
        }
    }
    public function profilUser(){
        $user_id =  Auth::user()->id;
        $initialize = new Initialize;
        $response = $initialize->compte_utilisateur($user_id);
     
        if ($response['success'] == true) {
            $id_user = $response['data']['0']['0']->id;
            $firstname = $response['data']['0']['0']->firstname;
            $lastname = $response['data']['0']['0']->lastname;
            $email = $response['data']['0']['0']->email;
            $role_name = $response['data']['0']['0']->role_name;
            $avatar = $response['data']['0']['0']->avatar;
            $join_date = $response['data']['0']['0']->created_at;
            $phone_number = $response['data']['0']['0']->phone_number;
            $address = $response['data']['0']['0']->address;
            $city = $response['data']['0']['0']->city;

            $acnumber_1 = $response['data']['0']['1']->acnumber;
            $account_1 = $response['data']['0']['1']->balance;
            $acnumber_2 = $response['data']['0']['2']->acnumber;
            $account_2 = $response['data']['0']['2']->balance;


            $transaction = DB::table('transactions')->where('user_id',$id_user)->limit(5)->get();

            if (Auth::user()->role_name == "Manager") {
                return view('manager.account.profil_user',compact('transaction','id_user','avatar','role_name','city','address','phone_number','join_date','email','lastname','firstname','acnumber_1','acnumber_2','account_1','account_2'));
            }
            elseif (Auth::user()->role_name == "Cashier") {
                return view('cashier.account.profil_user',compact('transaction','id_user','avatar','role_name','city','address','phone_number','join_date','email','lastname','firstname','acnumber_1','acnumber_2','account_1','account_2'));
            }
            
            
        }
    }
    public function customerByPhone($phone){
        $phone_number =  Crypt::decrypt($phone);
        $this_user = DB::connection('mysql2')->table('users')->where('phone', $phone_number)->first();
        $user_id = $this_user->id;
        $initialize = new Initialize;
        $response = $initialize->compte_client($user_id);
     
        if ($response['success'] == true) {
            $id_user = $response['data']['0']['0']->id;
            $firstname = $response['data']['0']['0']->firstname;
            $lastname = $response['data']['0']['0']->name;
            $email = $response['data']['0']['0']->email;
            $role_name = $response['data']['0']['0']->role_name;
            $avatar = $response['data']['0']['0']->avatar;
            $join_date = $response['data']['0']['0']->created_at;
            $phone_number = $response['data']['0']['0']->phone;
            $address = $response['data']['0']['0']->address;
            $city = $response['data']['0']['0']->city;
            $phone = $response['data']['0']['0']->phone;

            $current_wallet_code = $response['data']['0']['1']->wallet_code;
            $current_balance_cdf = $response['data']['0']['1']->wallet_balance;
            $current_balance_usd = $response['data']['0']['2']->wallet_balance;

            
            if ($response['data']['0']['3'] != null) {
                $saving_wallet_code = $response['data']['0']['3']->wallet_code;
                $saving_balance_cdf = $response['data']['0']['3']->wallet_balance;
            }
            else {
                $saving_wallet_code = "-";
                $saving_balance_cdf = "-";
            }

            if ($response['data']['0']['4'] != null) {
                $saving_wallet_code = $response['data']['0']['4']->wallet_code;
                $saving_balance_usd = $response['data']['0']['4']->wallet_balance;
            }
            else {
                $saving_wallet_code = "-";
                $saving_balance_usd = "-";
            }
            // dd($response['data']['0']['3'] );

            $transaction1 = DB::table('transactions')->where('sender_phone',$phone_number)->where('type','!=','depot')->limit(5)->orderBy('transaction_date','desc')->get();
            $transaction2 = DB::connection('mysql2')->table('transactions')->where('sender_phone',$phone_number)->where('type','!=','depot')->limit(5)->orderBy('transaction_date','desc')->get();
            $transaction = $transaction1->union($transaction2)->sortByDesc('transaction_date');

            $depot1 = DB::table('transactions')->where('receiver_phone',$phone)->where('type','depot')->limit(5)->orderBy('transaction_date','desc')->get();
            $depot2 = DB::connection('mysql2')->table('transactions')->where('receiver_phone',$phone)->where('type','depot')->limit(5)->orderBy('transaction_date','desc')->get();
            $depot = $transaction1->union($transaction2)->sortByDesc('transaction_date');

            if (Auth::user()->role_name == "Manager") {
                return view('manager.account.client',compact('transaction','id_user','avatar','role_name','city','address','phone_number','join_date','email','lastname','firstname','current_balance_cdf','current_balance_usd','saving_balance_cdf','saving_balance_usd','saving_wallet_code','current_wallet_code','depot'));
            }
            elseif (Auth::user()->role_name == "Cashier") {
                return view('cashier.account.client',compact('transaction','id_user','avatar','role_name','city','address','phone_number','join_date','email','lastname','firstname','current_balance_cdf','current_balance_usd','saving_balance_cdf','saving_balance_usd','saving_wallet_code','current_wallet_code','depot'));
            }

            
        }
    }
    public function client_depot($id){
        $user_id =  Crypt::decrypt($id);
        $user = DB::connection('mysql2')->table('users')->where('id',$user_id)->first();
        $users = DB::connection('mysql2')->table('users')->get();
        $initialize = new CustomerAPI;
        $compte = "current";
        $customer_number = $user->phone;
        $balance_cdf = $initialize->getCustomerBalance($customer_number, 'CDF', $compte);
        $balance_usd = $initialize->getCustomerBalance($customer_number, 'USD', $compte);
        $acnumber = $initialize->getAccountNumber($customer_number, $compte);
        $customer_id = $initialize->getCustomerID($customer_number);

        $transaction1 = DB::table('transactions')->where('receiver_phone',$customer_number)->where('type','=','depot')->limit(5)->orderBy('transaction_date','desc')->get();
        $transaction2 = DB::connection('mysql2')->table('transactions')->where('receiver_phone',$customer_number)->where('type','=','depot')->limit(5)->orderBy('transaction_date','desc')->get();
        $transaction = $transaction1->union($transaction2)->sortByDesc('transaction_date');


        $account_cdf = DB::connection('mysql2')->table('wallets')->where('wallet_currency', 'CDF')->where('wallet_type', 'saving')->where('customer_id', $customer_id)->first();
        $account_usd = DB::connection('mysql2')->table('wallets')->where('wallet_currency', 'USD')->where('wallet_type', 'saving')->where('customer_id', $customer_id)->first();
        if ($account_cdf == null) {
            $solde_cdf = 0;
        }
        else {
            $solde_cdf = $account_cdf->wallet_balance;
        }

        if ($account_usd == null) {
            $solde_usd = 0;
        }
        else {
            $solde_usd = $account_usd->wallet_balance;
        }

        if (Auth::user()->role_name == "Manager") {
            return view('manager.deposit.create_id', compact('user','users','balance_cdf','balance_usd','acnumber','solde_cdf','solde_usd','transaction'));
        }
        elseif (Auth::user()->role_name == "Cashier") {
            return view('cashier.deposit.create_id', compact('user','users','balance_cdf','balance_usd','acnumber','solde_cdf','solde_usd','transaction'));
        }
        
    }
    public function client_retrait($id){
        $user_id =  Crypt::decrypt($id);
        $user = DB::connection('mysql2')->table('users')->where('id',$user_id)->first();
        $initialize = new CustomerAPI;
        $compte = "current";
        $customer_number = $user->phone;
        $balance_cdf = $initialize->getCustomerBalance($customer_number, 'CDF', $compte);
        $balance_usd = $initialize->getCustomerBalance($customer_number, 'USD', $compte);
        $acnumber = $initialize->getAccountNumber($customer_number, $compte);
        if (Auth::user()->role_name == "Manager") {
            return view('manager.withdrawal.emala.create_id', compact('user','balance_cdf','balance_usd','acnumber'));
        }
        elseif (Auth::user()->role_name == "Cashier") {
            return view('cashier.withdrawal.emala.create_id', compact('user','balance_cdf','balance_usd','acnumber'));
        }
        
    }
    public function client_transfert($id){
        $user_id =  Crypt::decrypt($id);
        $user = DB::connection('mysql2')->table('users')->where('id',$user_id)->first();
        $users = DB::connection('mysql2')->table('users')->where('id',$user_id)->get();
        if (Auth::user()->role_name == "Manager") {
            return view('manager.transfer.emala.create_id', compact('users','user'));
        }
        elseif (Auth::user()->role_name == "Cashier") {
            return view('cashier.transfer.emala.create_id', compact('users','user'));
        }
        
    }
    public function transfert_compte_compte($id){
        $user_id =  Crypt::decrypt($id);
        $user = DB::connection('mysql2')->table('users')->where('id',$user_id)->first();
        $users = DB::connection('mysql2')->table('users')->where('id',$user_id)->get();
        $initialize = new CustomerAPI;
        $compte = "current";
        $customer_number = $user->phone;
        $balance_cdf = $initialize->getCustomerBalance($customer_number, 'CDF', $compte);
        $balance_usd = $initialize->getCustomerBalance($customer_number, 'USD', $compte);
        $acnumber = $initialize->getAccountNumber($customer_number, $compte);
        if (Auth::user()->role_name == "Manager") {
            return view('manager.transfer.emala.compte_compte', compact('users','user','balance_cdf','balance_usd','acnumber'));
        }
        elseif (Auth::user()->role_name == "Cashier") {
            return view('cashier.transfer.emala.compte_compte', compact('users','user','balance_cdf','balance_usd','acnumber'));
        }
        
    }
    public function retrait_compte_epargne($id){
        $user_id =  Crypt::decrypt($id);
        $user = DB::connection('mysql2')->table('users')->where('id',$user_id)->first();
        $initialize = new CustomerAPI;
        $compte = "saving";
        $customer_number = $user->phone;
        $balance_cdf = $initialize->getCustomerBalance($customer_number, 'CDF', $compte);
        $balance_usd = $initialize->getCustomerBalance($customer_number, 'USD', $compte);
        $acnumber = $initialize->getAccountNumber($customer_number, $compte);
        if (Auth::user()->role_name == "Manager") {
            return view('manager.withdrawal.emala.saving_id', compact('user','balance_cdf','balance_usd','acnumber'));
        }
        elseif (Auth::user()->role_name == "Cashier") {
            return view('cashier.withdrawal.emala.saving_id', compact('user','balance_cdf','balance_usd','acnumber'));
        }
        
    }
    public function client_mobile($id){
        $user_id =  Crypt::decrypt($id);
        $user = DB::connection('mysql2')->table('users')->where('id',$user_id)->first();
        if (Auth::user()->role_name == "Manager") {
            return view('manager.withdrawal.mobile.create_id', compact('user'));
        }
        elseif (Auth::user()->role_name == "Cashier") {
            return view('cashier.withdrawal.mobile.create_id', compact('user'));
        }
        
    }
}
