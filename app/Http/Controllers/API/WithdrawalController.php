<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Http\Controllers\VerifyNumberController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use RealRashid\SweetAlert\Facades\Alert;

class WithdrawalController extends Controller
{
    public function transfert_cash(){
        return view('backend.withdrawal.emala.transfert');
    }
    public function compte_cash(){
        return view('backend.withdrawal.emala.compte');
    }
    public function compte_cash_store(Request $request){

        $request->validate([
            'amount'            => 'required|string|max:255',
            'currency'          => 'required|string|max:255',
            'customer_number'   => 'required|string|max:255',
        ]);

        $phone              = new VerifyNumberController;
        $customer_number    = $phone->verify_number($request->customer_number);
        $currency           = $request->currency;
        $amount             = $request->amount;
        $fees               = $request->fees;
        $compte             = "current";
        $payment_method     = "Emala Gateway";

        $withdrawal = new WithdrawalAPI;
        $initialize = new Initialize;
        $verifyCustomer = $initialize->verifyCustomer($customer_number);
        

        if ($verifyCustomer['success'] == false) {
            Alert::error('Erreur!', 'Ce numéro n\'est pas enreistré dans le système!');
            return redirect()->back();
        }
        else {
            $response = $withdrawal->customer_withdrawal($customer_number,$amount,$fees,$currency,$compte,$payment_method);
            if ($response['success'] == true) {
                Alert::success('Succès', $response['message']);
                return redirect()->back();
            }
            elseif ($response['success'] == false) {
                Alert::error('Erreur', $response['message']);
                return redirect()->back();
            }
        }
        
        
    }
    public function retrait_compte_epargne(Request $request){

        $request->validate([
            'amount'            => 'required|string|max:255',
            'currency'          => 'required|string|max:255',
            'customer_number'   => 'required|string|max:255',
        ]);

        $phone              = new VerifyNumberController;
        $customer_number    = $phone->verify_number($request->customer_number);
        $currency           = $request->currency;
        $amount             = $request->amount;
        $fees               = $request->fees;
        $compte             = "saving";
        $payment_method     = "Emala Gateway";

        $withdrawal = new WithdrawalAPI;
        $initialize = new Initialize;
        $verifyCustomer = $initialize->verifyCustomer($customer_number);
        


        if ($verifyCustomer['success'] == false) {
            Alert::error('Erreur!', 'Ce numéro n\'est pas enreistré dans le système!');
            return redirect()->back();
        }
        else {
            $response = $withdrawal->customer_withdrawal($customer_number,$amount,$fees,$currency,$compte,$payment_method);
            if ($response['success'] == true) {
                Alert::success('Succès', $response['message']);
                return redirect()->back();
            }
            elseif ($response['success'] == false) {
                Alert::error('Erreur', $response['message']);
                return redirect()->back();
            }
        }
        
        
    }
    public function transfert_cash_store(Request $request){

        $request->validate([
            'amount'            => 'required|string|max:255',
            'fees'              => 'required|string|max:255',
            'currency'          => 'required|string|max:255',
            'reference'   => 'required|string|max:255',
        ]);
        $reference           = $request->reference;
        $checkTransaction = new CheckTransactionAPI;
        $verify = $checkTransaction->checkTransaction($reference);
        if ($verify['success']==true) {
            $compte             = "current";
            $payment_method     = "Emala Gateway";
    
            $withdrawal = new WithdrawalAPI;

            $sender_number = $verify['sender_number'];
            $senderFirstname = $verify['senderFirstname'];
            $senderLastname = $verify['senderLastname'];
            $receiver_number = $verify['receiver_number'];
            $receiverFirstname = $verify['receiverFirstname'];
            $receiverLastname = $verify['receiverLastname'];
            $reference = $verify['reference'];
            $amount = $verify['amount'];
            $currency = $verify['currency'];
            $fees = $verify['fees'];
            $remise = $verify['remise'];
            $money_received = $verify['money_received'];
            $response = $withdrawal->customer_withdrawal2($sender_number,$senderFirstname,$senderLastname,$receiver_number,$receiverFirstname,$receiverLastname,$amount,$remise,$money_received,$fees,$currency,$compte,$payment_method,$reference);
            if ($response['success'] == true) {
                Alert::success('Succès', $response['message']);
                return redirect()->back();
            }
            elseif ($response['success'] == false) {
                Alert::error('Erreur', $response['message']);
                return redirect()->back();
            }
            
        }
        else {
            Alert::error('Erreur!', $verify['message']);
            return redirect()->back();
        }
    }
    
}
