<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Http\Controllers\VerifyNumberController;
use Illuminate\Http\Request;
use RealRashid\SweetAlert\Facades\Alert;

class MobileMoneyController extends Controller
{
    public function credit(Request $request){

        $request->validate([
            'amount'            => 'required|string|max:255',
            'vendor'              => 'required|string|max:255',
            'currency'          => 'required|string|max:255',
            'customer_number'   => 'required|string|max:255',
            'momo_number'   => 'required|string|max:255',
        ]);

        $initialize = new Initialize;
        $action = "credit";
        $type = "retrait";
        $fees = 0.00;
        $compte = "current";
        $payment_method = "FreshPay Gateway";

        $phone= new VerifyNumberController;
        $momo_number = $phone->verify_number($request->momo_number);
      
        $customer_number = $phone->verify_number($request->customer_number);
        $operator = $phone->operator($momo_number);
        $currency = $request->currency;
        $amount = $request->amount;
        // $payment_method = $request->payment_method;
        $vendor = $request->vendor;

        function emala($length = 10) {
            $characters = '0123456789';
            $charactersLength = strlen($characters);
            $randomString = 'MO';
            for ($i = 0; $i < $length; $i++) {
                $randomString .= $characters[rand(0, $charactersLength - 1)];
            }
            return $randomString;
        }
        $authorization = $initialize->cash_register_verify();
     
        if ($authorization['success'] == false) {
            Alert::error('Caisse Fermée', 'Veuillez ouvrir votre caisse avant d\'effectuer cette opération.');
            return redirect()->back();
        }
        else {
            if ($operator != $vendor) {

                if ($operator == "mpesa") {
                    $reseau = "M-pesa";
                    $message = "Le numéro saisi n'est pas un numéro ".$reseau;
                    Alert::error('Erreur!', $message);
                    return redirect()->back();
                    
                }
                elseif ($operator == "airtel") {
                    $reseau = "Airtel money";
                    $message = "Le numéro saisi n'est pas un numéro ".$reseau;
                    Alert::error('Erreur!', $message);
                    return redirect()->back();
                    
                }
                elseif ($operator == "orange") {
                    $reseau = "Orange money";
                    $message = "Le numéro saisi n'est pas un numéro ".$reseau;
                    Alert::error('Erreur!', $message);
                    return redirect()->back();
                    
                }
    
            }
            else {
                $verifyCustomer = $initialize->verifyCustomer($customer_number);
                if ($verifyCustomer['success'] == false) {
                    Alert::error('Erreur!', 'Ce numéro n\'est pas enreistré dans le système!');
                    return redirect()->back();
                }
                else {
                    $response = $initialize->customer_withdrawal($customer_number,$amount,$fees,$currency,$compte,$payment_method);
                   
                    if ($response['success'] == true) {
                        $method = $operator;
                        $firstname = "Emala";
                        $lastname = "L&P";
                        $email = "admin@lumumbaandpartners.com";
                        $callback_url = "https://dashboard.emalafintech.net/api/v1/credit/callback";
                        $merchant_id    =   "jW]e%IY;ICOu7Hs4b";
                        $merchant_secrete = "jz1rwlMY@ueJ1FkO@b";
                        $reference  =   emala();
                        // $commission = new commissionController;
                        // $frais = $commission->commission($request->amount,$type);
                        $url = 'https://paydrc.gofreshbakery.net/api/v5/';
                        $curl_post_data = [
                            "merchant_id" => $merchant_id,
                            "merchant_secrete"=> $merchant_secrete,
                            "amount" => $amount,
                            "action" => $action,
                            "customer_number" => $momo_number,
                            "currency" => $currency,
                            "firstname" =>$firstname,
                            "lastname" => $lastname,
                            "email" => $email,
                            "method" => $operator,
                            "reference" => $reference,
                            // "callback_url" => ""
                        ];
                        $data = json_encode($curl_post_data);
                        
                        $ch=curl_init();
                        curl_setopt($ch, CURLOPT_URL, $url);
                        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
                        curl_setopt($ch, CURLOPT_MAXREDIRS, 10);
                        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
                        curl_setopt($ch, CURLOPT_POST, true);
                        curl_setopt($ch, CURLOPT_POSTFIELDS, $data);
                        curl_setopt($ch, CURLOPT_HTTPHEADER, array('Content-Type:application/json'));
                        $curl_response = curl_exec($ch);
                        
                        
                        if ($curl_response == false) {
                            $message = "Erreur de connexion! Vérifiez votre connexion internet";
                           
                            Alert::error('Erreur', $message);
                            return redirect()->back();
                        }
                        else {
                            $curl_decoded = json_decode($curl_response,true);
                            if ($curl_decoded != null) {
                                $status = $curl_decoded['Status'];
                                $comment = $curl_decoded['Comment'];
                                $transaction_id = $curl_decoded['Transaction_id'];
                                $mobilemoney = $initialize->mobilemoney($customer_number,$momo_number,$amount,$currency,$comment,$method,$action,$status,$reference,$transaction_id);
                                dd($mobilemoney);
                                if ($mobilemoney['success']==true) {
                                    Alert::success('Succès', $mobilemoney['message']);
                                    return redirect()->back();
                                }
                                else {
                                    
                                   $initialize->remise($customer_number,$amount,$payment_method,$currency);
                                    Alert::error('Erreur', "Une erreur est survenue pendant le retrait, votre argent a été rendu!");
                                    return redirect()->back();
                                    
                                }
                            }
                            else {
                                $initialize->remise($customer_number,$amount,$payment_method,$currency);
                                $status = "Failed";
                                $note = "Merchant balance insufficient!";
                                Alert::error('Erreur', $note." Argent remboursé avec succès.");
                                return redirect()->back();
                            }
                        }
                    }
                    elseif ($response['success'] == false) {
                        Alert::error('Erreur', $response['message']);
                        return redirect()->back();
                    }
                }
            }
        }
    }
}
