<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Http\Controllers\generateReferenceController;
use App\Models\CashRegister;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class DepositAPI extends Controller
{
    public function todayDate(){
        Carbon::setLocale('fr');
        $todayDate = Carbon::now()->format('Y-m-d H:i:s');
        return $todayDate;
    }
    public function activity_log($activityLog){
        DB::table('user_activity_logs')->insert($activityLog);
    }
    public function cash_register_verify(){
        $user_id = Auth::user()->id;
        $account = DB::table('accounts')->where('user_id',$user_id)->where('currency','CDF')->first();
        if ($account == null) {
            # caisse fermée
            $response = [
                'success' => false,
            ];
            return $response;
        }
        else {
            $account_id = $account->id;
            // dd($account_id);
            $register = CashRegister::where('account_id', $account_id)->where('status','opened')->where('currency','CDF')->latest('updated_at')->first();
            // dd($register->status);
            if ($register == null) {
                # caisse fermée
                $response = [
                    'success' => false,
                ];
                return $response;
            }
            else {
                $account_status = $register->status;
                if ($account_status == "closed") {
                    # caisse fermée
                    $response = [
                        'success' => false,
                    ];
                    return $response;
                }
                elseif ($account_status == "opened") {
                    # caisse ouverte
                    $response = [
                        'success' => true,
                    ];
                    return $response;
                }
            }
            
        }
        
    }
    public function getCurrencyID($currency){
        $currency = DB::table('currencies')->where('name',$currency)->first();
        $response = $currency->id;
        return $response;
    }
    public function table_transactions($reference,$amount,$currency,$senderFirstname,$senderLastname,$sender_phone,$receiverFirstname,$receiverLastname,$receiver_phone,$type,$note,$action,$status,$gateway,$money_received,$remise,$fees,$impact){
        $todayDate = $this->todayDate();
        $currency_id = $this->getCurrencyID($currency);
        $user_id = Auth::user()->id;
        $initialize = new Initialize;
        $branche_id = $initialize->branche_id(Auth::user()->id);
        if (Auth::user()->role_name == "Cashier") {
            $branche_id = $initialize->cashierBrancheId($user_id);
        } {
            $branche_id = $initialize->cashierBrancheId($user_id);
        }
        $datafortrx = [
            'sender_firstname'=>$senderFirstname,
            'sender_lastname'=>$senderLastname,
            'sender_phone'=>$sender_phone,
            'amount'=>$amount,
            'money_received'=>$money_received,
            'remise'=>$remise,
            'fees'=>$fees,
            'currency_id'=>$currency_id,
            'reference'=>$reference,
            'receiver_firstname'=>$receiverFirstname,
            'receiver_lastname'=>$receiverLastname,
            'receiver_phone'=>$receiver_phone,
            'transaction_date'=>$todayDate,
            'status'=>$status,
            'type'=>$type,
            'impact'=>$impact,
            'action'=>$action,
            'branche_id' => $branche_id,
            'user_id' => $user_id,
            'payment_method' => $gateway,
            'note'=>$note,
            'created_at' => $todayDate,
            'updated_at' => $todayDate
        ];

        DB::table('transactions')->insert($datafortrx);
    }
    public function table_deposits($customer_id,$amount,$currency_id,$reference,$fees,$gateway,$status, $wallet_type){
        $todayDate = $this->todayDate();
        $user_id = Auth::user()->id;
        $initialize = new Initialize;
        $branche_id = $initialize->branche_id(Auth::user()->id);
        if (Auth::user()->role_name == "Cashier") {
            $branche_id = $initialize->cashierBrancheId($user_id);
        }
        $getCustomerAccount = new CustomerAccountAPI;
        $wallet_id = $getCustomerAccount->getWalletID($customer_id, $currency_id, $wallet_type);
        $dataToInsert = [
            'wallet_id' => $wallet_id,
            'customer_id' => $customer_id,
            'amount' => $amount,
            'currency_id' => $currency_id,
            'transaction_id' => $reference,
            'fees' => $fees,
            'branche_id' => $branche_id,
            'user_id' => $user_id,
            'type' => $gateway,
            'status' => $status,
            'created_at' => $todayDate,
            'updated_at' => $todayDate
        ];
        $success = DB::table('deposits')->insert($dataToInsert);
        if ($success) {
            $response = [
                'success' => true,
                'message' => "Dépôt effectué avec succès! Réf. : ".$reference,
                'status' => "Successful",
            ];
            return $response;
        }
        else {
            $response = [
                'success' => false,
                'message' => "Votre demande de dépôt a echoué !",
                'status' => "Failed",
            ];
            return $response;
        }
    }
    public function internal_deposit($motif,$receiver_phone,$receiver_first,$receiver_last,$compte,$amount,$money_received,$fees,$remise,$currency,$payment_method){
        
        $getCustomer = new CustomerAPI;

        // $senderDetails = $getCustomer->getCustomerByPhone($sender_phone);
        $senderFirstname = null;
        $senderLastname = null;
        $sender_phone = null;

        // $receiverDetails = $getCustomer->getCustomerByPhone($receiver_phone);
        $receiverFirstname = $receiver_first;
        $receiverLastname = $receiver_last;

        $initialize = new Initialize;
        $gateway = $initialize->getPaymentMethodID($payment_method);

        $descritption ="depot";
        $default = new generateReferenceController;
        $reference = $default->reference($descritption);
    
        $receiver_id = $getCustomer->getCustomerID($receiver_phone);
        $account = $getCustomer->verifyAccount($compte,$receiver_id,$currency);

        $getCustomerAccount = new CustomerAccountAPI;
        $getUserAccount = new UserAccountAPI;

        $total = ($fees + $amount) - $remise;

        $impact = "caisse";
        
        if ($account['success'] == true) {
            $credit = $getCustomerAccount->credit_customer($currency, $amount, $compte, $receiver_phone);
            
            if ($credit['success'] == true) {
              
                $status = "Succès";
                $currency_id = $this->getCurrencyID($currency);
                $deposit = $this->table_deposits($receiver_id,$amount,$currency_id,$reference,$fees,$gateway,$status, $compte);
               
                if ($deposit['success'] == true) {
                    $getUserAccount->credit_user($currency, $total);

                    $status = "Succès";
                    $note = "Dépôt effectué avec succès!";
                    $action = "credit";
                    
                    $this->table_transactions($reference,$amount,$currency,$senderFirstname,$senderLastname,$sender_phone,$receiverFirstname,$receiverLastname,$receiver_phone,$descritption,$motif,$action,$status,$gateway,$money_received,$remise,$fees,$impact,$motif);
                    return $deposit;
                }
                else {
                    $status = "Echoué";
                    $note = $deposit['message'];
                    $action = "credit";
                    $this->table_transactions($reference,$amount,$currency,$senderFirstname,$senderLastname,$sender_phone,$receiverFirstname,$receiverLastname,$receiver_phone,$descritption,$motif,$action,$status,$gateway,$money_received,$remise,$fees,$impact,$motif);
                    return $deposit;
                }
                
            }
            else {
                $response = [
                    'success' => false,
                    'message' => "Pour une raison inconnue, le dépôt n'a pas abouti !",
                    'status' => "Failed",
                ];
                return $response;
            }
        }
        else {
            return $account;
        }
    
    }
    public function external_deposit($senderFirstname,$senderLastname,$sender_number,$receiver_number,$compte,$amount,$money_received,$fees,$remise,$currency,$payment_method){
        
        $getCustomer = new CustomerAPI;

        $receiverDetails = $getCustomer->getCustomerByPhone($receiver_number);
        $receiverFirstname = $receiverDetails->firstname;
        $receiverLastname = $receiverDetails->name;

        $initialize = new Initialize;
        $gateway = $initialize->getPaymentMethodID($payment_method);

        $descritption ="depot";
        $default = new generateReferenceController;
        $reference = $default->reference($descritption);
    
        $receiver_id = $getCustomer->getCustomerID($receiver_number);
        $account = $getCustomer->verifyAccount($compte,$receiver_id,$currency);

        $getCustomerAccount = new CustomerAccountAPI;
        $getUserAccount = new UserAccountAPI;

        $impact = "caisse";

        $total = ($fees + $amount) - $remise;
        
        if ($account['success'] == true) {
            $credit = $getCustomerAccount->credit_customer($currency, $amount, $compte, $receiver_number);
            
            if ($credit['success'] == true) {
              
                $status = "Succès";
                $currency_id = $this->getCurrencyID($currency);
                $deposit = $this->table_deposits($receiver_id,$amount,$currency_id,$reference,$fees,$gateway,$status, $compte);
               
                if ($deposit['success'] == true) {
                    $getUserAccount->credit_user($currency, $total);

                    $status = "Succès";
                    $note = "Dépôt effectué avec succès!";
                    $action = "credit";
                    $this->table_transactions($reference,$amount,$currency,$senderFirstname,$senderLastname,$sender_number,$receiverFirstname,$receiverLastname,$receiver_number,$descritption,$note,$action,$status,$gateway,$money_received,$remise,$fees,$impact);
                    return $deposit;
                }
                else {
                    $status = "Echoué";
                    $note = $deposit['message'];
                    $action = "credit";
                    $this->table_transactions($reference,$amount,$currency,$senderFirstname,$senderLastname,$sender_number,$receiverFirstname,$receiverLastname,$receiver_number,$descritption,$note,$action,$status,$gateway,$money_received,$remise,$fees,$impact);
                    return $deposit;
                }
                
            }
            else {
                $response = [
                    'success' => false,
                    'message' => "Pour une raison inconnue, le dépôt n'a pas abouti !",
                    'status' => "Failed",
                ];
                return $response;
            }
        }
        else {
            return $account;
        }
    
    }
}
