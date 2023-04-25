<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Http\Controllers\generateReferenceController;
use App\Models\CashRegister;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class TransfertAPI extends Controller
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

    public function customer_extenal_transfer($snumber,$sender_first,$sender_last,$rnumber,$receiver_first,$receiver_last,$amount,$remise,$money_received,$fees,$currency,$payment_method){
        $type ="transfert-externe";
        $total_amount = $amount + $fees;
        $default = new generateReferenceController;
        $reference = $default->reference($type);
        $initialize = new Initialize;
        $gateway = $initialize->getPaymentMethodID($payment_method);
        $action = "credit";
        $currency_id = $this->getCurrencyID($currency);
        $total = ($fees + $amount) - $remise;
        $getUserAccount = new UserAccountAPI;
        
                $done = $this->table_external_transferts($snumber,$sender_first,$sender_last,$amount,$fees,$currency,$reference,$rnumber,$receiver_first,$receiver_last);
                if ($done) {
                    $status = "En attente";
                    $note = "Transfert effectué avec succès!";
                    $impact = "caisse";
                    $getUserAccount->credit_user($currency, $total);
                    $this->table_transfers($snumber,$rnumber,$amount,$currency_id,$reference,$fees,$gateway,$status, $note);
                    $this->table_transactions($reference,$amount,$currency,$sender_first,$sender_last,$snumber,$receiver_first,$receiver_last,$rnumber,$type,$note,$action,$status,$gateway,$money_received,$remise,$fees,$impact);
                    $response = [
                        'success' => true,
                        'resultat' => 1,
                        'message' => "Transfert effectué avec succès",
                        'status' => "Successful",
                    ];
                    return $response;
                }

    }
    public function customer_internal_transfer($sender_phone,$sender_first,$sender_last,$receiver_phone,$receiver_first,$receiver_last,$compte,$amount,$fees,$currency,$payment_method){
        $type ="transfert";
        $total_amount = $amount + $fees;
        $default = new generateReferenceController;
        $reference = $default->reference($type);
        $initialize = new Initialize;
        $gateway = $initialize->getPaymentMethodID($payment_method);
        $action = "credit";
        $currency_id = $this->getCurrencyID($currency);
        $getCustomer = new CustomerAPI;
        $senderId = $getCustomer->getCustomerID($sender_phone);
        $account = $getCustomer->verifyAccount($compte,$senderId,$currency);

       

        $getCustomer = new CustomerAPI;
        $senderFirstname = $sender_first;
        $senderLastname = $sender_last;
        $receiverFirstname = $receiver_first;
        $receiverLastname = $receiver_last;

        $money_received = $amount;
        $remise = 0;

        $total = ($fees + $amount);
        $getUserAccount = new UserAccountAPI;
        
        $getCustomerAccount = new CustomerAccountAPI;
        $compte2 = "current";

        if ($account['success'] == true ) {
            $debit = $getCustomerAccount->debit_customer($currency, $amount, $fees, $compte, $sender_phone);
            // dd($debit);
            if ($debit['success'] == true) {
                $credit = $getCustomerAccount->credit_customer($currency, $amount, $compte2, $receiver_phone);
                
                if ($credit['success'] == true) {
                    $status = "Succès";
                    $note = "Transfert effectué avec succès!";
                    $impact = "système";

                    // $getUserAccount->credit_user($currency, $total);

                    $this->table_transfers($sender_phone,$receiver_phone,$amount,$currency_id,$reference,$fees,$gateway,$status, $note);
                    $this->table_transactions($reference,$amount,$currency,$senderFirstname,$senderLastname,$sender_phone,$receiverFirstname,$receiverLastname,$receiver_phone,$type,$note,$action,$status,$gateway,$money_received,$remise,$fees,$impact);
                    $response = [
                        'success' => true,
                        'resultat' => 1,
                        'message' => "Transfert effectué avec succès",
                        'status' => "Successful",
                    ];
                    return $response;
                }
                else {
                    return $credit;
                }
            }
            else {
                return $debit;
            }

        }
        elseif ($account['success'] == false) {
            return $account;
        }

    }
    public function customer_current_to_saving($sender_phone,$sender_first,$sender_last,$acnumber,$amount,$currency,$payment_method){
        $type ="transfert";
        $default = new generateReferenceController;
        $reference = $default->reference($type);
        $initialize = new Initialize;
        $gateway = $initialize->getPaymentMethodID($payment_method);
        $action = "credit";
        $currency_id = $this->getCurrencyID($currency);
        $getCustomer = new CustomerAPI;
        $senderId = $getCustomer->getCustomerID($sender_phone);

       

        $getCustomer = new CustomerAPI;
        $senderFirstname = $sender_first;
        $senderLastname = $sender_last;
        $receiverFirstname = $sender_first;
        $receiverLastname = $sender_last;
        $fees = 0;
        $money_received = $amount;
        $remise = 0;

        $getUserAccount = new UserAccountAPI;
        
        $getCustomerAccount = new CustomerAccountAPI;
        $current = "current";
        $saving = "saving";

        $debit = $getCustomerAccount->debit_customer($currency, $amount, $fees, $current, $sender_phone);

        if ($debit['success'] == true) {
            $credit = $getCustomerAccount->credit_customer($currency, $amount, $saving, $sender_phone);
            
            if ($credit['success'] == true) {
                $status = "Succès";
                $note = "Transfert effectué avec succès!";
                $impact = "système";

                $this->table_transfers($sender_phone,$sender_phone,$amount,$currency_id,$reference,$fees,$gateway,$status, $note);
                $this->table_transactions($reference,$amount,$currency,$senderFirstname,$senderLastname,$sender_phone,$receiverFirstname,$receiverLastname,$sender_phone,$type,$note,$action,$status,$gateway,$money_received,$remise,$fees,$impact);
                $response = [
                    'success' => true,
                    'resultat' => 1,
                    'message' => "Transfert effectué avec succès",
                    'status' => "Successful",
                ];
                return $response;
            }
            else {
                return $credit;
            }
        }
        else {
            return $debit;
        }
    }
    public function customer_virement($sender_number,$receiver_number,$compte,$amount,$remise,$money_received,$fees,$currency,$payment_method){
        $type ="virement";
        $total_amount = $amount + $fees;
        $default = new generateReferenceController;
        $reference = $default->reference($type);
        $initialize = new Initialize;
        $gateway = $initialize->getPaymentMethodID($payment_method);
        $action = "credit";
        $currency_id = $this->getCurrencyID($currency);
        $getCustomerAccount = new CustomerAccountAPI;

        $getCustomer = new CustomerAPI;

        $senderDetails = $getCustomer->getCustomerByPhone($sender_number);
        $senderFirstname = $senderDetails->firstname;
        $senderLastname = $senderDetails->name;

        $receiverDetails = $getCustomer->getCustomerByPhone($receiver_number);
        $receiverFirstname = $receiverDetails->firstname;
        $receiverLastname = $receiverDetails->name;

        $total = $fees;
        $getUserAccount = new UserAccountAPI;
       
        $debit = $getCustomerAccount->debit_customer($currency, $amount, $fees, $compte, $sender_number);
        if ($debit['success'] == true) {
            $credit = $getCustomerAccount->credit_customer($currency, $amount, $compte, $receiver_number);
            if ($credit['success'] == true) {
                $getUserAccount->credit_user($currency, $total);
                $impact = "caisse";
                $status = "Succès";
                $note = "Virement effectué avec succès!";
                $this->table_transfers($sender_number,$receiver_number,$amount,$currency_id,$reference,$fees,$gateway,$status, $note);
                $this->table_transactions($reference,$amount,$currency,$senderFirstname,$senderLastname,$sender_number,$receiverFirstname,$receiverLastname,$receiver_number,$type,$note,$action,$status,$gateway,$money_received,$remise,$fees,$impact);
                $response = [
                    'success' => true,
                    'resultat' => 1,
                    'message' => "Virement effectué avec succès",
                    'status' => "Successful",
                ];
                return $response;
            }
            else {
                return $credit;
            }
            
        }
        else {
            $response = [
                'success' => false,
                'message' => "Pour une raison inconnue, le virement n'a pas abouti !",
                'status' => "Failed",
            ];
        }
    }
    public function table_transactions($reference,$amount,$currency,$senderFirstname,$senderLastname,$sender_phone,$receiverFirstname,$receiverLastname,$receiver_phone,$type,$note,$action,$status,$gateway,$money_received,$remise,$fees,$impact){
        $todayDate = $this->todayDate();
        $currency_id = $this->getCurrencyID($currency);
        $user_id = Auth::user()->id;
        $initialize = new Initialize;
        $branche_id = $initialize->branche_id(Auth::user()->id);
        if (Auth::user()->role_name == "Cashier") {
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
    public function table_transfers($sender_phone,$receiver_phone,$amount,$currency_id,$reference,$fees,$gateway,$status, $note){
        $todayDate = $this->todayDate();
        $user_id = Auth::user()->id;
        $initialize = new Initialize;
        $branche_id = $initialize->branche_id(Auth::user()->id);
        if (Auth::user()->role_name == "Cashier") {
            $branche_id = $initialize->cashierBrancheId($user_id);
        }
        $dataToInsert = [
            'sender_phone' => $sender_phone,
            'receiver_phone' => $receiver_phone,
            'amount' => $amount,
            'currency_id' => $currency_id,
            'fees' => $fees,
            'branche_id' => $branche_id,
            'user_id' => $user_id,
            'reference' => $reference,
            'type' => $gateway,
            'note' => $note,
            'status' => $status,
            'created_at' => $todayDate,
            'updated_at' => $todayDate
        ];
        $success = DB::table('transfers')->insert($dataToInsert);
        if ($success) {
            $response = [
                'success' => true,
                'message' => "Transfert effectué avec succès! Réf. : ".$reference,
                'status' => "Successful",
            ];
            return $response;
        }
        else {
            $response = [
                'success' => false,
                'message' => "Votre demande de transfert a echoué !",
                'status' => "Failed",
            ];
            return $response;
        }
    }

    public function table_external_transferts($sender_phone,$sender_first,$sender_last,$amount,$fees,$currency,$reference,$receiver_phone,$receiver_first,$receiver_last){
        $todayDate = $this->todayDate();
        $user_id = Auth::user()->id;
        $initialize = new Initialize;
        $branche_id = $initialize->branche_id(Auth::user()->id);
        $data = [
            'user_id' => $user_id,
            'branche_id' => $branche_id,
            'payment_method' => 1,
            'sender_phone' => $sender_phone,
            'sender_first' => $sender_first,
            'sender_last' => $sender_last,
            'amount' => $amount,
            'fees' => $fees,
            'currency' => $currency,
            'reference' => $reference,
            'receiver_phone' => $receiver_phone,
            'receiver_first' => $receiver_first,
            'receiver_last' => $receiver_last,
            'status' => "En attente",
            'created_at'   => $todayDate,
            'updated_at'   => $todayDate,
        ];
        $success = DB::table('external_transfers')->insert($data);
        if ($success) {
            $response = [
                'success' => true,
                'message' => "Transfert effectué avec succès! Réf. : ".$reference,
                'status' => "Successful",
            ];
            return $response;
        }
        else {
            $response = [
                'success' => false,
                'message' => "Votre demande de transfert a echoué !",
                'status' => "Failed",
            ];
            return $response;
        }
    }
}
