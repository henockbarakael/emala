<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Http\Controllers\VerifyNumberController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use RealRashid\SweetAlert\Facades\Alert;

class DepositController extends Controller
{
    public function internal(){
        $user = DB::connection('mysql2')->table('users')->get();
        return view('backend.deposit.internal', compact('user'));
    }

    public function external(){
        $user = DB::connection('mysql2')->table('users')->get();
        return view('backend.deposit.external', compact('user'));
    }

    public function internal_store(Request $request){
        $request->validate([
            'amount'   => 'required|string|max:255',
            'currency'   => 'required|string|max:255',
            'compte'   => 'required|string|max:255',
            'money'   => 'required|string|max:255',
        ]);

        
        $verify_number = new VerifyNumberController;
        $receiver_phone = $verify_number->verify_number($request->receiver_phone);

        $receiver_first = $request->receiver_first;
        $receiver_last = $request->receiver_last;
        $reference = $request->reference;

        $compte = $request->compte;
        $currency = $request->currency;
        $amount = $request->amount;
        $fees = $request->fees;
        $money_received = $request->money;
        $remise = $request->remise;
        $payment_method     = "Emala Gateway";

        $initialize = new DepositAPI;

        $response = $initialize->internal_deposit($reference,$receiver_phone,$receiver_first,$receiver_last,$compte,$amount,$money_received,$fees,$remise,$currency,$payment_method);
        if ($response['success'] == true) {
            Alert::success('Succès', $response['message']);
            return redirect()->back();
        }
        elseif ($response['success'] == false) {
            Alert::error('Echec', $response['message']);
            return redirect()->back();
        }
        
         
    }

    public function external_store(Request $request){
        $request->validate([
            'amount'   => 'required|string|max:255',
            'fees'   => 'required|string|max:255',
            'currency'   => 'required|string|max:255',
            'compte'   => 'required|string|max:255',
            'sender_number'   => 'required|string|max:255',
            'senderFirstname'   => 'required|string|max:255',
            'senderLastname'   => 'required|string|max:255',
            'receiver_number'   => 'required|string|max:255',
            'remise'   => 'required|string|max:255',
            'money'   => 'required|string|max:255',
        ]);

        $phone= new VerifyNumberController;
        $sender_number = $phone->verify_number($request->sender_number);
        $receiver_number = $request->receiver_number;
        $compte = $request->compte;
        $currency = $request->currency;
        $amount = $request->amount;
        $fees = $request->fees;
        $money_received = $request->money;
        $remise = $request->remise;
        $payment_method     = "Emala Gateway";
        $senderFirstname = $request->senderFirstname;
        $senderLastname = $request->senderLastname;

        
        $initialize = new DepositAPI;

        $response = $initialize->external_deposit($senderFirstname,$senderLastname,$sender_number,$receiver_number,$compte,$amount,$money_received,$fees,$remise,$currency,$payment_method);
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
