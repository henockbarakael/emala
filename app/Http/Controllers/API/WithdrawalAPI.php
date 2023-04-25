<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Http\Controllers\generateReferenceController;
use App\Models\CashRegister;
use App\Models\ExternalTransfer;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class WithdrawalAPI extends Controller
{
    public function getCurrencyID($currency){
        $currency = DB::table('currencies')->where('name',$currency)->first();
        $response = $currency->id;
        return $response;
    }
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
    public function customer_withdrawal($customer_number,$amount,$fees,$currency,$compte,$payment_method){
        $getCustomer = new CustomerAPI;
        $getUserAccount = new UserAccountAPI;
        $descritption ="retrait";
        $default = new generateReferenceController;
        $reference = $default->reference($descritption);

        $senderDetails = $getCustomer->getCustomerByPhone($customer_number);
        $senderFirstname = $senderDetails->firstname;
        $senderLastname = $senderDetails->name;

        $receiverDetails = $getCustomer->getCustomerByPhone($customer_number);
        $receiverFirstname = $receiverDetails->firstname;
        $receiverLastname = $receiverDetails->name;
        $getCustomerAccount = new CustomerAccountAPI;

        $remise = 0;

        $total = ($fees + $amount);

        $debitCaissier = $getUserAccount->debit_user($currency, $amount);
        $impact = "caisse";
        if ($debitCaissier['success'] == true) {
            $debit = $getCustomerAccount->debit_customer($currency, $amount, $fees, $compte, $customer_number);
            $gateway = $this->getPaymentMethodID($payment_method);
            if ($debit['success'] == true) {
                $customer_id = $getCustomer->getCustomerID($customer_number);
                $status = "Succès";
                $currency_id = $this->getCurrencyID($currency);
                $retrait = $this->table_withdrawals($customer_id,$amount,$currency_id,$reference,$fees,$gateway,$status);
                if ($retrait['success'] == true) {
                    $status = "Succès";
                    $note = "Retrait effectué avec succès!";
                    $action = "debit";
                    
                    $this->table_transactions($reference,$amount,$currency,$senderFirstname,$senderLastname,$customer_number,$receiverFirstname,$receiverLastname,$customer_number,$descritption,$note,$action,$status,$gateway,$amount,$remise,$fees,$impact);
                    return $retrait;
                }
                else {
                    $status = "Echoué";
                    $note = $retrait['message'];
                    $action = "debit";
                    $this->table_transactions($reference,$amount,$currency,$senderFirstname,$senderLastname,$customer_number,$receiverFirstname,$receiverLastname,$customer_number,$descritption,$note,$action,$status,$gateway,$amount,$remise,$fees,$impact);
                    return $retrait;
                }
                
            }
            else {
                $response = [
                    'success' => false,
                    'message' => "Pour une raison inconnue, l'argent n'a pas pu être retiré de votre wallet !",
                    'status' => "Failed",
                ];
                return $response;
            }
        }
        else {
            $getUserAccount->credit_user($currency, $total);
            return $debitCaissier;
        }
        
        
        
    }
    public function customer_withdrawal2($sender_number,$senderFirstname,$senderLastname,$receiver_number,$receiverFirstname,$receiverLastname,$amount,$remise,$money_received,$fees,$currency,$compte,$payment_method,$transaction_id){
        $getCustomer = new CustomerAPI;
        $getUserAccount = new UserAccountAPI;
        $descritption ="retrait";
        $default = new generateReferenceController;
        $reference = $default->reference($descritption);
        $getCustomerAccount = new CustomerAccountAPI;

        $total = ($amount);

        $debitCaissier = $getUserAccount->debit_user($currency, $total);
        $impact = "caisse";
        if ($debitCaissier['success'] == true) {
            $transaction = ExternalTransfer::where('reference',$transaction_id)->where('status','En attente')->first();
            $id = $transaction->id;
            $data = ['status'=>"Retiré",'status_description'=>"Argent retiré avec succès!"];
            DB::table('external_transfers')->where('id',$id)->update($data);
            // $debit = $getCustomerAccount->debit_customer($currency, $amount, $fees, $compte, $customer_number);
            $gateway = $this->getPaymentMethodID($payment_method);
            // if ($debit['success'] == true) {
                // $customer_id = $getCustomer->getCustomerID($customer_number);
                $status = "Succès";
                $currency_id = $this->getCurrencyID($currency);
                $retrait = $this->table_external_withdrawals($money_received,$remise,$sender_number,$senderFirstname,$senderLastname,$amount,$currency_id,$reference,$fees,$gateway,$status);
                $retrait_fees = 0;
                if ($retrait['success'] == true) {
                    $status = "Succès";
                    $note = "Retrait effectué avec succès!";
                    $action = "debit";
                    
                    $this->table_transactions($reference,$amount,$currency,$senderFirstname,$senderLastname,$sender_number,$receiverFirstname,$receiverLastname,$receiver_number,$descritption,$note,$action,$status,$gateway,$money_received,$remise,$retrait_fees,$impact);
                    return $retrait;
                }
                else {
                    $status = "Echoué";
                    $note = $retrait['message'];
                    $action = "debit";
                    $this->table_transactions($reference,$amount,$currency,$senderFirstname,$senderLastname,$sender_number,$receiverFirstname,$receiverLastname,$receiver_number,$descritption,$note,$action,$status,$gateway,$money_received,$remise,$retrait_fees,$impact);
                    return $retrait;
                }
        }
        else {
            $getUserAccount->credit_user($currency, $total);
            return $debitCaissier;
        }
        
        
        
    }
    public function getPaymentMethodID($payment_method){
        
        $method = DB::table('payment_methods')->where('method',$payment_method)->first();
        $response = $method->id;
        return $response;
    }

    public function table_transactions($reference,$amount,$currency,$senderFirstname,$senderLastname,$sender_phone,$receiverFirstname,$receiverLastname,$receiver_phone,$type,$note,$action,$status,$gateway,$money_received,$remise,$fees, $impact){
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
    public function table_withdrawals($customer_id,$amount,$currency_id,$reference,$fees,$gateway,$status){
        $todayDate = $this->todayDate();
        $user_id = Auth::user()->id;
        $initialize = new Initialize;
        $branche_id = $initialize->branche_id(Auth::user()->id);
        if (Auth::user()->role_name == "Cashier") {
            $branche_id = $initialize->cashierBrancheId($user_id);
        }
        $dataToInsert = [
            'customer_id' => $customer_id,
            'amount' => $amount,
            'currency_id' => $currency_id,
            'transaction_id' => $reference,
            'fees' => $fees,
            'type' => $gateway,
            'branche_id' => $branche_id,
            'user_id' => $user_id,
            'status' => $status,
            'payment_method' => $gateway,
            'created_at' => $todayDate,
            'updated_at' => $todayDate
        ];
        $success = DB::table('withdrawals')->insert($dataToInsert);
        if ($success) {
            $response = [
                'success' => true,
                'message' => "Demande de retrait effectuée avec succès! Réf. : ".$reference,
                'status' => "Successful",
            ];
            return $response;
        }
        else {
            $response = [
                'success' => false,
                'message' => "Votre demande de retrait a echoué !",
                'status' => "Failed",
            ];
            return $response;
        }
    }
    public function table_external_withdrawals($money_received,$remise,$sender_number,$senderFirstname,$senderLastname,$amount,$currency_id,$reference,$fees,$gateway,$status){
        $todayDate = $this->todayDate();
        $user_id = Auth::user()->id;
        $initialize = new Initialize;
        $branche_id = $initialize->branche_id(Auth::user()->id);
        $dataToInsert = [
            'sender_number' => $sender_number,
            'sender_firstname' => $senderFirstname,
            'sender_lastname' => $senderLastname,
            'amount' => $amount,
            'money_received' => $money_received,
            'remise' => $remise,
            'currency_id' => $currency_id,
            'transaction_id' => $reference,
            'fees' => $fees,
            'type' => $gateway,
            'branche_id' => $branche_id,
            'user_id' => $user_id,
            'status' => $status,
            'payment_method' => $gateway,
            'created_at' => $todayDate,
            'updated_at' => $todayDate
        ];
        $success = DB::table('external_withdrawals')->insert($dataToInsert);
        if ($success) {
            $response = [
                'success' => true,
                'message' => "Demande de retrait effectuée avec succès! Réf. : ".$reference,
                'status' => "Successful",
            ];
            return $response;
        }
        else {
            $response = [
                'success' => false,
                'message' => "Votre demande de retrait a echoué !",
                'status' => "Failed",
            ];
            return $response;
        }
    }
}
