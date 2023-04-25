<?php

namespace App\Http\Controllers\Manager;

use App\Http\Controllers\API\TransfertAPI;
use App\Http\Controllers\Controller;
use App\Http\Controllers\VerifyNumberController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use RealRashid\SweetAlert\Facades\Alert;

class TransferController extends Controller
{
    public function create_emala(){
        $user = DB::connection('mysql2')->table('users')->get();
        if (Auth::user()->role_name == "Manager") {
            return view('manager.transfer.emala.create', compact('user'));
        }
        elseif (Auth::user()->role_name == "Cashier") {
            return view('cashier.transfer.emala.create', compact('user'));
        }
    }
    public function EmalaStore(Request $request){
        $request->validate([
            'amount'   => 'required|string|max:255',
            'fees'   => 'required|string|max:255',
            'currency'   => 'required|string|max:255',
            'sender_phone'   => 'required|string|max:255',
            'sender_first'   => 'required|string|max:255',
            'sender_last'   => 'required|string|max:255',
            'receiver_phone'   => 'required|string|max:255',
            'receiver_first'   => 'required|string|max:255',
            'receiver_last'   => 'required|string|max:255',
            'remise'   => 'required|string|max:255',
            'money'   => 'required|string|max:255',
        ]);
        

        $phone= new VerifyNumberController;
        $snumber = $phone->verify_number($request->sender_phone);
        $rnumber = $phone->verify_number($request->receiver_phone);
        $money_received = $request->money;
        $remise = $request->remise;

        $sender_first = $request->sender_first;
        $sender_last = $request->sender_last;
        $receiver_first = $request->receiver_first;
        $receiver_last = $request->receiver_last;
        $currency = $request->currency;
        $amount = $request->amount;
        $fees = $request->fees;
        $payment_method = "Emala Gateway";
        $initialize = new TransfertAPI;
        $authorization = $initialize->cash_register_verify();
        // $authorization['success'] = true;
     
        if ($authorization['success'] == false) {
            Alert::error('Caisse Fermée', 'Veuillez ouvrir votre caisse avant d\'effectuer cette opération.');
            return redirect()->back();
        }
        else {
            if ($snumber == $rnumber) {
                Alert::error('Erreur', 'L\'expéditeur ne peut pas être le bénéficiaire!');
                return redirect()->back();
            }
            else {
                
                $response = $initialize->customer_extenal_transfer($snumber,$sender_first,$sender_last,$rnumber,$receiver_first,$receiver_last,$amount,$remise,$money_received,$fees,$currency,$payment_method);
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

    }
    public function create_emala_interne(){
        $user = DB::connection('mysql2')->table('users')->get();
        if (Auth::user()->role_name == "Manager") {
            return view('manager.transfer.emala.interne', compact('user'));
        }
        elseif (Auth::user()->role_name == "Cashier") {
            return view('cashier.transfer.emala.interne', compact('user'));
        }
    }
    public function create_emala_virement(){
        $user = DB::connection('mysql2')->table('users')->get();
        if (Auth::user()->role_name == "Manager") {
            return view('manager.transfer.emala.virement', compact('user'));
        }
        elseif (Auth::user()->role_name == "Cashier") {
            return view('cashier.transfer.emala.virement', compact('user'));
        }
    }
    public function EmalaStoreInterne(Request $request){

        $request->validate([
            'amount'   => 'required|string|max:255',
            'fees'   => 'required|string|max:255',
            'currency'   => 'required|string|max:255',
            'receiver_phone'   => 'required|string|max:255',
            'receiver_first'   => 'required|string|max:255',
            'receiver_last'   => 'required|string|max:255',
            'compte'   => 'required|string|max:255',
            'sender_phone'   => 'required|string|max:255',
            'sender_first'   => 'required|string|max:255',
            'sender_last'   => 'required|string|max:255',
        ]);

        $sender_phone = $request->sender_phone;
        $sender_first = $request->sender_first;
        $sender_last = $request->sender_last;
        $receiver_phone = $request->receiver_phone;
        $receiver_first = $request->receiver_first;
        $receiver_last = $request->receiver_last;
        $compte = $request->compte;
        $currency = $request->currency;
        $amount = $request->amount;
        $fees = $request->fees;
        $payment_method     = "Emala Gateway";

        $initialize = new TransfertAPI;
        $authorization = $initialize->cash_register_verify();

     
        if ($authorization['success'] == false) {
            Alert::error('Caisse Fermée', 'Veuillez ouvrir votre caisse avant d\'effectuer cette opération.');
            return redirect()->back();
        }
        else {
            $response = $initialize->customer_internal_transfer($sender_phone,$sender_first,$sender_last,$receiver_phone,$receiver_first,$receiver_last,$compte,$amount,$fees,$currency,$payment_method);
           // dd($response);
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

    public function Current_Saving(Request $request){

        $request->validate([
            'amount'   => 'required|string|max:255',
            'currency'   => 'required|string|max:255',
        ]);

        $sender_phone = $request->sender_phone;
        $sender_first = $request->sender_first;
        $sender_last = $request->sender_last;
        $acnumber = $request->acnumber;
        $currency = $request->currency;
        $amount = $request->amount;
        $payment_method     = "Emala Gateway";

        $initialize = new TransfertAPI;
        $authorization = $initialize->cash_register_verify();

     
        if ($authorization['success'] == false) {
            Alert::error('Caisse Fermée', 'Veuillez ouvrir votre caisse avant d\'effectuer cette opération.');
            return redirect()->back();
        }
        else {
            $response = $initialize->customer_current_to_saving($sender_phone,$sender_first,$sender_last,$acnumber,$amount,$currency,$payment_method);
           // dd($response);
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

    public function EmalaStoreVirement(Request $request){
        $request->validate([
            'amount'   => 'required|string|max:255',
            'fees'   => 'required|string|max:255',
            'currency'   => 'required|string|max:255',
            'sender_number'   => 'required|string|max:255',
            'customer_number'   => 'required|string|max:255',
            'remise'   => 'required|string|max:255',
            'money'   => 'required|string|max:255',
        ]);

        $phone= new VerifyNumberController;
        $sender_number = $phone->verify_number($request->sender_number);
        $receiver_number = $phone->verify_number($request->customer_number);
        $currency = $request->currency;
        $amount = $request->amount;
        $fees = $request->fees;
        $compte = "current";
        $payment_method = "Emala Gateway";
        $money_received = $request->money;
        $remise = $request->remise;

        $initialize = new TransfertAPI;
        $authorization = $initialize->cash_register_verify();
        // $authorization['success'] = true;
     
        if ($authorization['success'] == false) {
            Alert::error('Caisse Fermée', 'Veuillez ouvrir votre caisse avant d\'effectuer cette opération.');
            return redirect()->back();
        }
        else {
            if ($sender_number == $receiver_number) {
                Alert::error('Erreur', 'L\'expéditeur ne peut pas être le bénéficiaire!');
                return redirect()->back();
            }
            else {
                
                $response = $initialize->customer_virement($sender_number,$receiver_number,$compte,$amount,$remise,$money_received,$fees,$currency,$payment_method);
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
 
    }
    public function create_mobile(){
        if (Auth::user()->role_name == "Manager") {
            return view('manager.transfer.mobile.create');
        }
        elseif (Auth::user()->role_name == "Cashier") {
            return view('cashier.transfer.mobile.create');
        }
    }
}
