<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class CustomerAPI extends Controller
{
    public function todayDate(){
        Carbon::setLocale('fr');
        $todayDate = Carbon::now()->format('Y-m-d H:i:s');
        return $todayDate;
    }

    public function getCustomerByPhone($phone_number){
        $user = DB::connection('mysql2')->table('users')->where('phone',$phone_number)->first();
        return $user;
    }
    public function getCustomerById($userId){
        $user = DB::connection('mysql2')->table('users')->where('id',$userId)->first();
        return $user;
    }
    public function getCustomerID($customer_number){
        // dd($customer_number);
        $users = DB::connection('mysql2')->table('users')->where('phone', $customer_number)->first();
        $user_id = $users->id;
        return $user_id;
    }
    public function verifyAccount($compte,$user_id,$currency){
        $account = DB::connection('mysql2')->table('wallets')->where('wallet_type', $compte)->where('wallet_currency', $currency)->where('customer_id', $user_id)->first();
        if ($compte == "saving") {
            if ($account == null) {
                $response = [
                    'success' => false,
                    'message' => "Le client n'a pas de compte epargne ".$currency,
                    'status' => "Failed",
                ];
                return $response;
            }
            else {
                $response = [
                    'success' => true,
                    'message' => "Compte epargne existe!",
                    'status' => "Successful",
                ];
                return $response;
            }
        }
        elseif ($compte == "current") {
            if ($account == null) {
                $response = [
                    'success' => false,
                    'message' => "Le client n'existe pas dans le système",
                    'status' => "Failed",
                ];
                return $response;
            }
            else {
                $response = [
                    'success' => true,
                    'message' => "Compte principal existe!",
                    'status' => "Successful",
                ];
                return $response;
            }
        }

    }
    public function getCustomerBalance($customer_number, $currency, $compte){
        $user_id = $this->getCustomerID($customer_number);
        $current_wallet = DB::connection('mysql2')->table('wallets')->where('wallet_type', $compte)->where('wallet_currency', $currency)->where('customer_id', $user_id)->first();
        $balance = $current_wallet->wallet_balance;
        return $balance;
    }
    public function getAccountNumber($customer_number, $compte){
        $user_id = $this->getCustomerID($customer_number);
        $current_wallet = DB::connection('mysql2')->table('wallets')->where('wallet_type', $compte)->where('customer_id', $user_id)->first();
        $code = $current_wallet->wallet_code;
        return $code;
    }
    public function request(Request $request){
        $phone_number = $request->receiver_phone;
        $user = DB::connection('mysql2')->table('users')->select('firstname','name')->where('phone',$phone_number)->first();
       // Store it in a array
       $firstname = $user->firstname;
       $lastname = $user->name;
       $result = array("$firstname", "$lastname");
        
        // Send in JSON encoded form
        $myJSON = json_encode($result);
        echo $myJSON;
    }
    public function autocomplete(Request $request)
    {
        return DB::connection('mysql2')->table('users')->select('firstname','name','phone')
                    ->where('phone', 'LIKE', "%{$request->term}%")
                    ->pluck('phone');
    }
    public function search_autocomplete(Request $request)
    {
        $query = $request->get('term','');
        $users= DB::connection('mysql2')->table('users');
        if($request->type=='receiver_phone'){
            $users->where('phone','LIKE','%'.$query.'%');
        }
        if($request->type=='receiver_first'){
            $users->where('firstname','LIKE','%'.$query.'%');
        }
        if($request->type=='receiver_last'){
            $users->where('name','LIKE','%'.$query.'%');
        }
           $users=$users->get();        
        $data=array();
        foreach ($users as $user) {
                $data[]=array('phone'=>$user->phone,'firstname'=>$user->firstname,'lastname'=>$user->name);
        }
        if(count($data))
             return $data;
        else
            return ['phone'=>'','firstname'=>'','lastname'=>''];
    }
    
}
