<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Http\Controllers\GenerateIdController;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class CustomerAccountAPI extends Controller
{
    public function todayDate(){
        Carbon::setLocale('fr');
        $todayDate = Carbon::now()->format('Y-m-d H:i:s');
        return $todayDate;
    }
    public function activity_log($activityLog){
        DB::table('user_activity_logs')->insert($activityLog);
    }
    public function getWalletID($customer_id, $currency, $wallet_type){
        $devise = $this->getCurrencyByID($currency);
        $wallet = DB::connection('mysql2')->table('wallets')->where('wallet_type', $wallet_type)->where('wallet_currency', $devise)->where('customer_id', $customer_id)->first();
        $response = $wallet->id;
        return $response;
    }
    public function getCurrencyByID($currency_id){
        $currency = DB::table('currencies')->where('id',$currency_id)->first();
        $response = $currency->name;
        return $response;
    }
    public function getCustomerBalance($customer_number, $currency){
        $getCustomer = new CustomerAPI;
        $user_id = $getCustomer->getCustomerID($customer_number);
        $current_wallet = DB::connection('mysql2')->table('wallets')->where('wallet_type', 'current')->where('wallet_currency', $currency)->where('customer_id', $user_id)->first();
        $balance = $current_wallet->wallet_balance;
        return $balance;
    }
    public function credit_customer($currency, $amount, $compte, $customer_number){
        $getCustomer = new CustomerAPI;
        $balance = $getCustomer->getCustomerBalance($customer_number, $currency, $compte);
        $customer_id = $getCustomer->getCustomerID($customer_number);
        $todayDate = $this->todayDate();
        $account = $getCustomer->verifyAccount($compte,$customer_id,$currency);
        if ($account['success'] == true) {
            $data = ['wallet_balance' => $balance + $amount];
            $update = DB::connection('mysql2')->table('wallets')->where('wallet_type', $compte)->where('wallet_currency', $currency)->where('customer_id', $customer_id)->update($data);
            $activityLog = [
                'fullname'   => Auth::user()->firstname." ".Auth::user()->lastname,
                'user_phone'   => Auth::user()->phone_number,
                'activity'   => "vient de créditer le client ".$customer_number,
                'updated_at'   => $todayDate,
            ];
            if ($update) {
                $this->activity_log($activityLog);
                $response = [
                    'success' => true,
                    'message' => "Wallet créditer avec succès!",
                    'status' => "Successful",
                ];
                return $response;
            }
            else {
                $activityFailed= [
                    'fullname'   => Auth::user()->firstname." ".Auth::user()->lastname,
                    'user_phone'   => Auth::user()->phone_number,
                    'activity'   => "a tenté de créditer le client ".$customer_number,
                    'updated_at'   => $todayDate,
                ];
                $this->activity_log($activityFailed);
                $response = [
                    'success' => false,
                    'message' => "Une erreur est survenue lors du recharge du compte du client",
                    'status' => "Failed",
                ];
                return $response;
            }

        }
        else {
            return $account;
        }
    }
    public function debit_customer($currency, $amount, $fees, $compte, $customer_number){
        
        $getCustomer = new CustomerAPI;
        #On récupère la balance du client par devise
        $balance = $this->getCustomerBalance($customer_number, $currency);
        
        #On récupère l'ID du client
        $customer_id = $getCustomer->getCustomerID($customer_number);
        
        $todayDate = $this->todayDate();
        $total = $amount + $fees;
        #On vérifie si l'opération peut-être effectuée
        if ($amount > $balance ) {
            $response = [
                'success' => false,
                'message' => "Solde insuffisant!",
                'status' => "Failed",
            ];
            return $response;
        }
        else {
            #On vérifie l'existence du compte client
            $account = $getCustomer->verifyAccount($compte,$customer_id,$currency);
           
            if ($account['success'] == true) {
                $data = ['wallet_balance' => $balance - $total];
                // dd($data);
                
                $update = DB::connection('mysql2')->table('wallets')->where('wallet_type', $compte)->where('wallet_currency', $currency)->where('customer_id', $customer_id)->update($data);
                
                $activityLog = [
                    'fullname'   => Auth::user()->firstname." ".Auth::user()->lastname,
                    'user_phone'   => Auth::user()->phone_number,
                    'activity'   => "vient de debiter le client ".$customer_number,
                    'updated_at'   => $todayDate,
                ];
                if ($update) {
                    // dd('ok');
                    $this->activity_log($activityLog);
                    $response = [
                        'success' => true,
                        'message' => "Wallet debité avec succès!",
                        'status' => "Successful",
                    ];
                    
                    return $response;
                }
                else {
                    // dd('no');
                    $activityFailed= [
                        'fullname'   => Auth::user()->firstname." ".Auth::user()->lastname,
                        'user_phone'   => Auth::user()->phone_number,
                        'activity'   => "a tenté de débiter le client ".$customer_number,
                        'updated_at'   => $todayDate,
                    ];
                    $this->activity_log($activityFailed);
                    $response = [
                        'success' => false,
                        'message' => "Une erreur est survenue lors du debit du compte",
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

    public function create_saving_account($sender_phone,$currency,$startDate,$endDate, $from){

        $default = new GenerateIdController;
        $acnumber = $default->AccountNumber();
        $todayDate = $this->todayDate();
        $wallet_code = $default->AccountNumber();
        
        $getCustomer = new CustomerAPI;
        $senderDetails = $getCustomer->getCustomerByPhone($sender_phone);
        $customer_id = $senderDetails->id;

        $check_user = DB::connection('mysql2')->table('users')->where('phone',$sender_phone)->first();

        if ($check_user == null ) {
            $response = [
                'success' => false,
                'message' => "Impossible de créer un compte epargne si vous n'êtes pas un client emala! Veuillez premièrement ouvrir un compte!",
            ];
            return $response;
        }
        else {
            if ($currency == "CDF") {
                $verify_saving = DB::connection('mysql2')->table('wallets')->where('customer_id',$check_user->id)->where('wallet_currency', 'CDF')->where('wallet_type','saving')->count();
                if ($verify_saving >= 1) {
                    $response = [
                        'success' => false,
                        'message' => "Cet utilisateur a déjà un compte epargne en franc congolais!",
                    ];
                    return $response;
                }
            }
            elseif ($currency == "USD") {
                $verify_saving = DB::connection('mysql2')->table('wallets')->where('customer_id',$check_user->id)->where('wallet_currency', 'USD')->where('wallet_type','saving')->count();
                if ($verify_saving >= 1) {
                    $response = [
                        'success' => false,
                        'message' => "Cet utilisateur a déjà un compte epargne en dollars!",
                    ];
                    return $response;
                }

            }
            elseif ($currency == "CDF-USD") {
                $verify_saving1 = DB::connection('mysql2')->table('wallets')->where('customer_id',$check_user->id)->where('wallet_currency', 'USD')->where('wallet_type','saving')->count();
                $verify_saving2 = DB::connection('mysql2')->table('wallets')->where('customer_id',$check_user->id)->where('wallet_currency', 'CDF')->where('wallet_type','saving')->count();
                if ($verify_saving1 >= 1 && $verify_saving2 >= 1) {
                    $response = [
                        'success' => false,
                        'message' => "Cet utilisateur a déjà un compte epargne en dollars et franc congolais",
                    ];
                    return $response;
                }
                elseif ($verify_saving1 >= 1 && $verify_saving2 == 0) {
                    $response = [
                        'success' => false,
                        'message' => "Cet utilisateur a déjà un compte epargne en dollars",
                    ];
                    return $response;
                }
                elseif ($verify_saving1 >= 0 && $verify_saving2 == 1) {
                    $response = [
                        'success' => false,
                        'message' => "Cet utilisateur a déjà un compte epargne en franc congolais",
                    ];
                    return $response;
                }

            }
            else {
                if ($currency == "CDF") {
                    $customer_wallet_1 = DB::connection('mysql2')->table('wallets')->insert([
                        'customer_id'   => $customer_id,
                        'wallet_code'   => $wallet_code,
                        'wallet_balance'   => 0,
                        'wallet_currency'   => 'CDF',
                        'wallet_type' => 'saving',
                        'created_at'   => $todayDate,
                        'updated_at'   => $todayDate,
                    ]);
                    if ($customer_wallet_1) {
                        $wallet = DB::connection('mysql2')->table('wallets')->where(['wallet_code'=>$wallet_code,'wallet_currency'=>'CDF','wallet_currency'=>$currency,'customer_id'=>$customer_id])->first();
                        $wallet_id = $wallet->id;
                        DB::connection('mysql2')->table('saving_accounts')->insert([
                            'wallet_id'   => $wallet_id,
                            'date_start'   => $startDate,
                            'date_end'   => $endDate,
                        ]);
                        $response = [
                            'success' => true,
                            'message' => "Compte créer avec succès!",
                            'status' => "Successful",
                        ];
                        return $response;
                    }
                    else {
                        $response = [
                            'success' => false,
                            'message' => "Erreur lors de la création du compte!",
                            'status' => "Failed",
                        ];
                        return $response;
                    }
                }
                elseif ($currency == "USD") {
                    $customer_wallet_2 = DB::connection('mysql2')->table('wallets')->insert([
                        'customer_id'   => $customer_id,
                        'wallet_code'   => $wallet_code,
                        'wallet_balance'   => 0,
                        'wallet_currency'   => 'USD',
                        'wallet_type' => 'saving',
                        'created_at'   => $todayDate,
                        'updated_at'   => $todayDate,
                    ]);
                    if ($customer_wallet_2) {
                        $wallet_usd = DB::connection('mysql2')->table('wallets')->where(['wallet_code'=>$wallet_code,'wallet_currency'=>'USD','wallet_currency'=>$currency,'customer_id'=>$customer_id])->first();
                        $wallet_id_2 = $wallet_usd->id;
                        DB::connection('mysql2')->table('saving_accounts')->insert([
                            'wallet_id'   => $wallet_id_2,
                            'date_start'   => $startDate,
                            'date_end'   => $endDate,
                        ]);
                        $response = [
                            'success' => true,
                            'message' => "Compte créer avec succès!",
                            'status' => "Successful",
                        ];
                        return $response;
                    }
                    else {
                        $response = [
                            'success' => false,
                            'message' => "Erreur lors de la création du compte!",
                            'status' => "Failed",
                        ];
                        return $response;
                    }
                }
                elseif ($currency == "CDF-USD") {
                    $customer_wallet_1 = DB::connection('mysql2')->table('wallets')->insert([
                        'customer_id'   => $customer_id,
                        'wallet_code'   => $wallet_code,
                        'wallet_balance'   => 0,
                        'wallet_currency'   => 'CDF',
                        'wallet_type' => 'saving',
                        'created_at'   => $todayDate,
                        'updated_at'   => $todayDate,
                    ]);
        
                    $customer_wallet_2 = DB::connection('mysql2')->table('wallets')->insert([
                        'customer_id'   => $customer_id,
                        'wallet_code'   => $wallet_code,
                        'wallet_balance'   => 0,
                        'wallet_currency'   => 'USD',
                        'wallet_type' => 'saving',
                        'created_at'   => $todayDate,
                        'updated_at'   => $todayDate,
                    ]);
        
                    if ($customer_wallet_1 && $customer_wallet_2) {
                        $wallet_cdf = DB::connection('mysql2')->table('wallets')->where(['wallet_code'=>$wallet_code,'wallet_currency'=>'CDF','customer_id'=>$customer_id])->first();
                        
                        $wallet_id_1 = $wallet_cdf->id;
                        $done = DB::connection('mysql2')->table('saving_accounts')->insert([
                            'wallet_id'   => $wallet_id_1,
                            'date_start'   => $startDate,
                            'date_end'   => $endDate,
                        ]);
                        if ($done) {
                            $wallet_usd = DB::connection('mysql2')->table('wallets')->where(['wallet_code'=>$wallet_code,'wallet_currency'=>'USD','customer_id'=>$customer_id])->first();
                            $wallet_id_2 = $wallet_usd->id;
                            DB::connection('mysql2')->table('saving_accounts')->insert([
                                'wallet_id'   => $wallet_id_2,
                                'date_start'   => $startDate,
                                'date_end'   => $endDate,
                            ]);
                            $response = [
                                'success' => true,
                                'message' => "Compte créer avec succès!",
                                'status' => "Successful",
                            ];
                            return $response;
                        }
                        
                    }
                    else {
                        $response = [
                            'success' => false,
                            'message' => "Erreur lors de la création du compte!",
                            'status' => "Failed",
                        ];
                        return $response;
                    }
                } 
            }
            
        }


         

    }

}
