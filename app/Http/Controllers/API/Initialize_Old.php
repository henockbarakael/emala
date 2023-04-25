<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Http\Controllers\GenerateIdController;
use App\Http\Controllers\generateReferenceController;
use App\Http\Controllers\VerifyNumberController;
use App\Models\Account;
use App\Models\Branche;
use App\Models\EmalaTransfert;
use App\Models\TiroirCaisse;
use App\Models\User;
use App\Models\Wallet;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use SebastianBergmann\Type\TrueType;

class Initialize extends Controller
{
    public function create_wallet($institution){
        $todayDate = $this->todayDate();
        $data_1 = [
            'bank_id'   => $institution,
            'balance'   => 0.00,
            'currency'   => "CDF",
            'created_at'   => $todayDate,
            'updated_at'   => $todayDate,
        ];
        $data_2 = [
            'bank_id'   => $institution,
            'balance'   => 0.00,
            'currency'   => "USD",
            'created_at'   => $todayDate,
            'updated_at'   => $todayDate,
        ];
        $activityLog = [
            'fullname'   => Auth::user()->firstname." ".Auth::user()->lastname,
            'user_phone'   => Auth::user()->phone_number,
            'user_id'   => Auth::user()->id,
            'activity'   => "Vient de créer le wallet ".$institution,
            'updated_at'   => $todayDate,
        ];

        $save_1 = DB::table('wallets')->insert($data_1);
        $save_2 = DB::table('wallets')->insert($data_2);

        if ($save_1 && $save_2) {
            $this->activity_log($activityLog);
            $response = [
                'success' => true,
                'message' => "Wallet créer avec succès",
                'status' => "Successful",
            ];
            return $response;
        }
        else {
            $activityFailed= [
                'fullname'   => Auth::user()->firstname." ".Auth::user()->lastname,
                'user_phone'   => Auth::user()->phone_number,
                'activity'   => "a tenté de créer le wallet ".$institution,
                'updated_at'   => $todayDate,
            ];
            $this->activity_log($activityFailed);
            $response = [
                'success' => false,
                'message' => "Une erreur est survenue lors de la création du wallet",
                'status' => "Successful",
            ];
            return $response;
        }
    }
    public function topup_wallet($amount, $wallet_id,$currency){
        $wallet = DB::table('wallets')->where('id', $wallet_id)->first();
        $todayDate = $this->todayDate();

        $balance = $wallet->balance;
        $data = [
            'balance'    => $balance + $amount,
        ];
        $update = DB::table('wallets')->where('id', $wallet_id)->where('currency', $currency)->update($data);
        $user_id = Auth::user()->id;

        $activityLog = [
            'fullname'   => Auth::user()->firstname." ".Auth::user()->lastname,
            'user_phone'   => Auth::user()->phone_number,
            'user_id'   => Auth::user()->id,
            'activity'   => "Vient de recharger le wallet ".$wallet->id,
            'updated_at'   => $todayDate,
        ];
        if ($update) {
            $data = [
                'wallet_id' => $wallet->id,
                'user_id' => $user_id,
                'currency' => $currency,
                'amount' => $amount,
                'created_at'   => $todayDate,
                'updated_at'   => $todayDate,
            ];
            $save = DB::table('wallet_stories')->where('currency', $currency)->insert($data);
            $this->activity_log($activityLog);
            if ($save) {
                $response = [
                    'success' => true,
                    'resultat' => 1,
                    'message' => "Wallet rechargé avec succès",
                    'status' => "Successful",
                ];
                return $response;
            }
        }
        else {
            $activityFailed= [
                'fullname'   => Auth::user()->firstname." ".Auth::user()->lastname,
                'user_phone'   => Auth::user()->phone_number,
                'activity'   => "a tenté de recharger le wallet ".$wallet->id,
                'updated_at'   => $todayDate,
            ];
            $this->activity_log($activityFailed);
            $response = [
                'success' => false,
                'message' => "Une erreur est survenue lors de la création du wallet",
                'status' => "Successful",
            ];
            return $response;
        }
    }


    public function debitWallet($currency, $amount){
        $wallet = DB::table('wallets')->where('bank_id', 1)->where('currency', $currency)->first();
        $todayDate = $this->todayDate();
        $balance = $wallet->balance;

        if ($amount > $balance) {
            $response = [
                'success' => false,
                'message' => "Balance insuffisante!",
                'status' => "Failed",
            ];
            return $response;
        }
        else {
            $data = [
                'balance'    => $balance - $amount,
            ];
            $update = DB::table('wallets')->where('bank_id', 1)->where('currency', $currency)->update($data);
            $user_id = Auth::user()->id;

            $activityLog = [
                'fullname'   => Auth::user()->firstname." ".Auth::user()->lastname,
                'user_phone'   => Auth::user()->phone_number,
                'activity'   => "Vient de débiter le wallet Emala",
                'updated_at'   => $todayDate,
            ];
            if ($update) {
                $data = [
                    'wallet_id' => $wallet->id,
                    'user_id' => $user_id,
                    'currency' => $currency,
                    'amount' => $amount,
                    'created_at'   => $todayDate,
                    'updated_at'   => $todayDate,
                ];
                $save = DB::table('wallet_stories')->where('currency', $currency)->insert($data);
                $this->activity_log($activityLog);
                if ($save) {
                    $response = [
                        'success' => true,
                        'resultat' => 1,
                        'message' => "Wallet rechargé avec succès",
                        'status' => "Successful",
                    ];
                    return $response;
                }
            }
            else {
                $activityFailed= [
                    'fullname'   => Auth::user()->firstname." ".Auth::user()->lastname,
                    'user_phone'   => Auth::user()->phone_number,
                    'activity'   => "a tenté de recharger le wallet ".$wallet->id,
                    'updated_at'   => $todayDate,
                ];
                $this->activity_log($activityFailed);
                $response = [
                    'success' => false,
                    'message' => "Une erreur est survenue lors de la création du wallet",
                    'status' => "Successful",
                ];
                return $response;
            }
        }



    }

    public function debitGerantAccount($currency, $amount){
        $user_id = Auth::user()->id;
        $account = DB::table('accounts')->where('user_id', $user_id)->where('currency', $currency)->first();
        $todayDate = $this->todayDate();

        $balance = $account->balance;
        if ($amount > $balance) {
            $response = [
                'success' => false,
                'message' => "Balance insuffisante!",
                'status' => "Failed",
            ];
            return $response;
        }
        else {
            $data = [
                'balance'    => $balance - $amount,
            ];
            $update = DB::table('accounts')->where('user_id', $user_id)->where('currency', $currency)->update($data);
            $user_id = Auth::user()->id;

            $activityLog = [
                'fullname'   => Auth::user()->firstname." ".Auth::user()->lastname,
                'user_phone'   => Auth::user()->phone_number,
                'activity'   => "Vient d'approvisionner le compte ".$account->id,
                'updated_at'   => $todayDate,
            ];
            if ($update) {
                $data = [
                    'account_id' => $account->id,
                    'user_id' => $user_id,
                    'currency' => $currency,
                    'amount' => $amount,
                    'created_at'   => $todayDate,
                    'updated_at'   => $todayDate,
                ];
                $save = DB::table('account_stories')->where('currency', $currency)->insert($data);
                $this->activity_log($activityLog);
                if ($save) {
                    $response = [
                        'success' => true,
                        'resultat' => 1,
                        'message' => "Compte debité avec succès",
                        'status' => "Successful",
                    ];
                    return $response;
                }
            }
            else {
                $activityFailed= [
                    'fullname'   => Auth::user()->firstname." ".Auth::user()->lastname,
                    'user_phone'   => Auth::user()->phone_number,
                    'activity'   => "a tenté de débiter le compte ".$account->id,
                    'updated_at'   => $todayDate,
                ];
                $this->activity_log($activityFailed);
                $response = [
                    'success' => false,
                    'message' => "Une erreur est survenue lors de l'approvisionnement du compte",
                    'status' => "Failed",
                ];
                return $response;
            }
        }

    }

    public function creditGerantAccount($currency, $amount){
        $user_id = Auth::user()->id;
        $account = DB::table('accounts')->where('user_id', $user_id)->where('currency', $currency)->first();
        $todayDate = $this->todayDate();

        $balance = $account->balance;
        $data = [
            'balance'    => $balance + $amount,
        ];
        $update = DB::table('accounts')->where('user_id', $user_id)->where('currency', $currency)->update($data);
        $user_id = Auth::user()->id;

        $activityLog = [
            'fullname'   => Auth::user()->firstname." ".Auth::user()->lastname,
            'user_phone'   => Auth::user()->phone_number,
            'user_id'   => Auth::user()->id,
            'activity'   => "Vient de créditer le compte ".$account->id,
            'updated_at'   => $todayDate,
        ];
        if ($update) {
            $data = [
                'account_id' => $account->id,
                'user_id' => $user_id,
                'currency' => $currency,
                'amount' => $amount,
                'created_at'   => $todayDate,
                'updated_at'   => $todayDate,
            ];
            $save = DB::table('account_stories')->where('currency', $currency)->insert($data);
            $this->activity_log($activityLog);
            if ($save) {
                $response = [
                    'success' => true,
                    'resultat' => 1,
                    'message' => "Compte crédité avec succès",
                    'status' => "Successful",
                ];
                return $response;
            }
        }
        else {
            $activityFailed= [
                'fullname'   => Auth::user()->firstname." ".Auth::user()->lastname,
                'user_phone'   => Auth::user()->phone_number,
                'activity'   => "a tenté de créditer le compte ".$account->id,
                'updated_at'   => $todayDate,
            ];
            $this->activity_log($activityFailed);
            $response = [
                'success' => false,
                'message' => "Une erreur est survenue lors du credit du compte",
                'status' => "Failed",
            ];
            return $response;
        }
    }

    public function creditGerantTiroir($currency, $amount){
        $user_id = Auth::user()->id;
        $account = DB::table('tiroir_caisses')->where('user_id', $user_id)->where('currency', $currency)->first();
        $todayDate = $this->todayDate();

        $balance = $account->balance;
        $data = [
            'balance'    => $balance + $amount,
        ];
        $update = DB::table('tiroir_caisses')->where('user_id', $user_id)->where('currency', $currency)->update($data);
        $user_id = Auth::user()->id;

        $activityLog = [
            'fullname'   => Auth::user()->firstname." ".Auth::user()->lastname,
            'user_phone'   => Auth::user()->phone_number,
            'user_id'   => Auth::user()->id,
            'activity'   => "Vient de créditer le compte ".$account->id,
            'updated_at'   => $todayDate,
        ];
        if ($update) {
            $data = [
                'account_id' => $account->id,
                'user_id' => $user_id,
                'currency' => $currency,
                'amount' => $amount,
                'created_at'   => $todayDate,
                'updated_at'   => $todayDate,
            ];
            $save = DB::table('account_stories')->where('currency', $currency)->insert($data);
            $this->activity_log($activityLog);
            if ($save) {
                $response = [
                    'success' => true,
                    'resultat' => 1,
                    'message' => "Compte crédité avec succès",
                    'status' => "Successful",
                ];
                return $response;
            }
        }
        else {
            $activityFailed= [
                'fullname'   => Auth::user()->firstname." ".Auth::user()->lastname,
                'user_phone'   => Auth::user()->phone_number,
                'activity'   => "a tenté de créditer le compte ".$account->id,
                'updated_at'   => $todayDate,
            ];
            $this->activity_log($activityFailed);
            $response = [
                'success' => false,
                'message' => "Une erreur est survenue lors du credit du compte",
                'status' => "Failed",
            ];
            return $response;
        }
    }

    public function credit_account($currency, $amount, $account_id){
        $account = DB::table('accounts')->where('id', $account_id)->first();
        $todayDate = $this->todayDate();

        $balance = $account->balance;
        $data = [
            'balance'    => $balance + $amount,
        ];
        $update = DB::table('accounts')->where('id', $account_id)->where('currency', $currency)->update($data);
        $user_id = Auth::user()->id;

        $activityLog = [
            'fullname'   => Auth::user()->firstname." ".Auth::user()->lastname,
            'user_phone'   => Auth::user()->phone_number,
            'user_id'   => Auth::user()->id,
            'activity'   => "Vient de recharger le compte ".$account->id,
            'updated_at'   => $todayDate,
        ];
        if ($update) {
            $data = [
                'account_id' => $account->id,
                'user_id' => $user_id,
                'currency' => $currency,
                'amount' => $amount,
                'created_at'   => $todayDate,
                'updated_at'   => $todayDate,
            ];
            $save = DB::table('account_stories')->where('currency', $currency)->insert($data);
            $this->activity_log($activityLog);
            if ($save) {
                $response = [
                    'success' => true,
                    'resultat' => 1,
                    'message' => "Wallet rechargé avec succès",
                    'status' => "Successful",
                ];
                return $response;
            }
        }
        else {
            $activityFailed= [
                'fullname'   => Auth::user()->firstname." ".Auth::user()->lastname,
                'user_phone'   => Auth::user()->phone_number,
                'activity'   => "a tenté de recharger le compte ".$account->id,
                'updated_at'   => $todayDate,
            ];
            $this->activity_log($activityFailed);
            $response = [
                'success' => false,
                'message' => "Une erreur est survenue lors de la création du compte",
                'status' => "Failed",
            ];
            return $response;
        }
    }

    public function credit_tiroir($currency, $amount, $account_id){
        $account = DB::table('tiroir_caisses')->where('id', $account_id)->first();
        $todayDate = $this->todayDate();

        $balance = $account->balance;
        $data = [
            'balance'    => $balance + $amount,
        ];
        $update = DB::table('tiroir_caisses')->where('id', $account_id)->where('currency', $currency)->update($data);
        $user_id = Auth::user()->id;

        $activityLog = [
            'fullname'   => Auth::user()->firstname." ".Auth::user()->lastname,
            'user_phone'   => Auth::user()->phone_number,
            'user_id'   => Auth::user()->id,
            'activity'   => "Vient de recharger le compte ".$account->id,
            'updated_at'   => $todayDate,
        ];
        if ($update) {
            $data = [
                'account_id' => $account->id,
                'user_id' => $user_id,
                'currency' => $currency,
                'amount' => $amount,
                'created_at'   => $todayDate,
                'updated_at'   => $todayDate,
            ];
            $save = DB::table('tiroir_caisse_stories')->where('currency', $currency)->insert($data);
            $this->activity_log($activityLog);
            if ($save) {
                $response = [
                    'success' => true,
                    'resultat' => 1,
                    'message' => "Wallet rechargé avec succès",
                    'status' => "Successful",
                ];
                return $response;
            }
        }
        else {
            $activityFailed= [
                'fullname'   => Auth::user()->firstname." ".Auth::user()->lastname,
                'user_phone'   => Auth::user()->phone_number,
                'activity'   => "a tenté de recharger le compte ".$account->id,
                'updated_at'   => $todayDate,
            ];
            $this->activity_log($activityFailed);
            $response = [
                'success' => false,
                'message' => "Une erreur est survenue lors de la création du compte",
                'status' => "Failed",
            ];
            return $response;
        }
    }

    public function topup_account($amount, $account_id,$currency,$account_level){

        if ($account_level == 1) {
            $response = $this->debitWallet($currency, $amount);

            if ($response['success'] == true) {
                $credit_account = $this->credit_account($currency, $amount, $account_id);
                if ($credit_account['success']==true) {
                    $response = [
                        'success' => true,
                        'resultat' => 1,
                        'message' => "Compte rechargé avec succès",
                        'status' => "Successful",
                    ];
                    return $response;
                }
                else {
                    return $response;
                }
            }
            else {
                return $response;
            }
        }

        else {
            $response = $this->debitGerantAccount($currency, $amount);

            if ($response['success'] == true) {
                $credit_account = $this->credit_account($currency, $amount, $account_id);
                if ($credit_account['success']==true) {
                    $response = [
                        'success' => true,
                        'resultat' => 1,
                        'message' => "Compte rechargé avec succès",
                        'status' => "Successful",
                    ];
                    return $response;
                }
                else {
                    return $response;
                }
            }
            else {
                return $response;
            }
        }
    }

    public function delete_wallet($wallet_id)
    {
        $todayDate = $this->todayDate();
        $activityLog = [
            'fullname'   => Auth::user()->firstname." ".Auth::user()->lastname,
            'user_phone'   => Auth::user()->phone_number,
            'user_id'   => Auth::user()->id,
            'activity'   => "Vient de supprimer le wallet ".$wallet_id,
            'updated_at'   => $todayDate,
        ];

        $save = DB::table('user_activity_logs')->insert($activityLog);

        if($save){
            $destroy = Wallet::destroy($wallet_id);
            if ($destroy) {
                $response = [
                    'success' => true,
                    'message' => "Wallet supprimé avec succès!",
                    'status' => "Successful",
                ];
                return $response;
            }
            else {
                $response = [
                    'success' => false,
                    'message' => "Echec de l'opération!",
                    'status' => "Failed",
                ];
                return $response;
            }
        }
    }
    public function create_branche($township,$city,$phone,$email,$user_id){
        $todayDate = $this->todayDate();
        $generate = new GenerateIdController;
        $bname = $generate->code_agence();

        $bank = DB::table('banks')->where('bank_name',"Lumumba & Partners")->first();
        $bank_id = $bank->id;

        $branche = Branche::create([
            'created_by'   => $user_id,
            'bname'   => $bname,
            'bemail'   => $email,
            'btownship'   => $township,
            'bcity'   => $city,
            'bphone'   => $phone,
            'bank_id'   => $bank_id,
            'created_at'   => $todayDate,
            'updated_at'   => $todayDate,
        ]);

        $activityLog = [
            'fullname'   => Auth::user()->firstname." ".Auth::user()->lastname,
            'user_phone'   => Auth::user()->phone_number,
            'user_id'   => Auth::user()->id,
            'activity'   => "Vient de créer l'agence de ".$township,
            'updated_at'   => $todayDate,
        ];

        if ($branche) {
            $this->activity_log($activityLog);
            $response = [
                'success' => true,
                'message' => "Agence créée avec succès!",
                'status' => "Successful",
            ];
            return $response;
        }
        else {
            $activityFailed= [
                'fullname'   => Auth::user()->firstname." ".Auth::user()->lastname,
                'user_phone'   => Auth::user()->phone_number,
                'activity'   => "a tenté de créer l'agence ".$township,
                'updated_at'   => $todayDate,
            ];
            $this->activity_log($activityFailed);
            $response = [
                'success' => false,
                'message' => "Une erreur est survenue lors de la création de l'agence",
                'status' => "Successful",
            ];
            return $response;
        }
    }
    public function assign($user_id,$branche_id){
        $todayDate = $this->todayDate();
        $user = DB::table('users')->where('id',$user_id)->first();
        $fullname = $user->firstname." ".$user->lastname;

        $data = [
            'fullname'=>$fullname,
            'user_id'=>$user_id,
            'status'=>"Active"
        ];

        $branche = Branche::where('id', $branche_id)->update($data);
        $activityLog = [
            'fullname'   => Auth::user()->firstname." ".Auth::user()->lastname,
            'user_phone'   => Auth::user()->phone_number,
            'user_id'   => Auth::user()->id,
            'activity'   => "Vient d'assigner un gérant.",
            'updated_at'   => $todayDate,
        ];

        if ($branche) {
            $default = new GenerateIdController;
            Account::create([
                'user_id'   => $user_id,
                'acnumber'   => $default->AccountNumber(),
                'branche_id'   => $branche_id,
                'balance'   => 0.00,
                'currency'   => "CDF",
                'status'   => 1,
                'account_level'   => 1,
                'created_at'   => $todayDate,
                'updated_at'   => $todayDate,
            ]);

            Account::create([
                'user_id'   => $user_id,
                'acnumber'   => $default->AccountNumber(),
                'branche_id'   => $branche_id,
                'balance'   => 0.00,
                'currency'   => "USD",
                'status'   => 1,
                'account_level'   => 1,
                'created_at'   => $todayDate,
                'updated_at'   => $todayDate,
            ]);

            TiroirCaisse::create([
                'user_id'   => $user_id,
                'acnumber'   => $default->AccountNumber(),
                'branche_id'   => $branche_id,
                'balance'   => 0.00,
                'currency'   => "CDF",
                'status'   => 1,
                'account_level'   => 1,
                'created_at'   => $todayDate,
                'updated_at'   => $todayDate,
            ]);

            TiroirCaisse::create([
                'user_id'   => $user_id,
                'acnumber'   => $default->AccountNumber(),
                'branche_id'   => $branche_id,
                'balance'   => 0.00,
                'currency'   => "USD",
                'status'   => 1,
                'account_level'   => 1,
                'created_at'   => $todayDate,
                'updated_at'   => $todayDate,
            ]);

            $this->activity_log($activityLog);

            $response = [
                'success' => true,
                'message' => "Agence créée avec succès!",
                'status' => "Successful",
            ];
            return $response;
        }
        else {
            $activityLog = [
                'fullname'   => Auth::user()->firstname." ".Auth::user()->lastname,
                'user_phone'   => Auth::user()->phone_number,
                'activity'   => "A tenter d'assigner un gérant.",
                'updated_at'   => $todayDate,
            ];
            $this->activity_log($activityLog);

            $response = [
                'success' => false,
                'message' => "Une erreu est survenue lors de l'assignation du gérant!",
                'status' => "Failed",
            ];
            return $response;
        }
    }
    public function create_treller($firstname,$lastname,$telephone){

        $limite = $this->branche_user_limit();
        if ($limite['success'] == false) {
            return $limite;
        }
        else {
            $todayDate = $this->todayDate();
            $verify_number = new VerifyNumberController;
            $phone_number = $verify_number->verify_number($telephone);

            $default = new GenerateIdController;
            $password = $default->defaultPIN();
            $acnumber = $default->AccountNumber();

            $check_user = DB::table('users')->where('phone_number',$phone_number)->first();

            if ($check_user == null) {
                $user = User::create([
                    'firstname'      => $firstname,
                    'lastname'      => $lastname,
                    'phone_number'      => $phone_number,
                    'avatar'    => "user.png",
                    'join_date' => $todayDate,
                    'role_name' =>"Cashier",
                    'user_status' => 'Hors ligne',
                    'password'  => Hash::make($password),
                    'password_salt'  => $password,
                    'created_at'   => $todayDate,
                    'updated_at'   => $todayDate,
                ]);

                if ($user) {

                    $user = DB::table('users')->where('phone_number',$phone_number)->first();
                    $user_id = $user->id;

                    $branche_id = $this->branche_id(Auth::user()->id);

                    $account_1 = Account::create([
                        'user_id'   => $user_id,
                        'acnumber'   => $default->AccountNumber(),
                        'branche_id'   => $branche_id,
                        'balance'   => 0.00,
                        'currency'   => "CDF",
                        'status'   => 1,
                        'created_at'   => $todayDate,
                        'updated_at'   => $todayDate,
                    ]);

                    $account_2 = Account::create([
                        'user_id'   => $user_id,
                        'acnumber'   => $default->AccountNumber(),
                        'branche_id'   => $branche_id,
                        'balance'   => 0.00,
                        'currency'   => "USD",
                        'status'   => 1,
                        'created_at'   => $todayDate,
                        'updated_at'   => $todayDate,
                    ]);
                    if ($account_1 && $account_2) {
                        $activityLog = [
                            'fullname'   => Auth::user()->firstname." ".Auth::user()->lastname,
                            'user_phone'   => Auth::user()->phone_number,
                            'activity'   => "Vient de créer un caissier",
                            'updated_at'   => $todayDate,
                        ];
                        $this->activity_log($activityLog);
                        $activityLog = [
                            'fullname'   => Auth::user()->firstname." ".Auth::user()->lastname,
                            'user_phone'   => Auth::user()->phone_number,
                            'activity'   => "A tenter d'assigner un gérant.",
                            'updated_at'   => $todayDate,
                        ];
                        $response = [
                            'success' => true,
                            'message' => "Caissier créer avec succès!",
                            'status' => "Successful",
                        ];
                        return $response;
                    }

                }
                else {
                    $response = [
                        'success' => false,
                        'message' => "Une erreur est survenue lors de la création de l'utilisateur!",
                    ];
                    $activityLog = [
                        'fullname'   => Auth::user()->firstname." ".Auth::user()->lastname,
                        'user_phone'   => Auth::user()->phone_number,
                        'activity'   => "A tenter de créer un caissier",
                        'updated_at'   => $todayDate,
                    ];
                    $this->activity_log($activityLog);
                    return $response;
                }
            }
            else {
                $response = [
                    'success' => false,
                    'message' => "Numéro déjà enregistré dans le système, veuillez changer de numéro svp!",
                ];
                return $response;
            }
        }

    }
    public function customer_withdrawal($customer_number,$amount,$fees,$currency){
        $compte = "current";
        $sender_phone = Auth::user()->phone_number;
        $type = "retrait";
        $action = "debit";
        $generate = new generateReferenceController;
        $reference = $generate->reference($type);
        $debit = $this->debit_customer($currency, $amount, $fees, $compte, $customer_number);
        if ($debit['success'] == true) {
            $status = "Succès";
            $withdrawal_status = "En attente";
            $status_description = "Transfert effectué avec succès!";
            $this->transaction($sender_phone,$customer_number,$amount,$fees,$currency,$status,$reference,$action,$type,$status_description);
            $this->table_withdrawal($customer_number,$amount,$fees,$currency,$withdrawal_status,$reference);
            $response = [
                'success' => true,
                'message' => "Retrait effectué avec succès",
                'status' => "Successfull",
            ];
            return $response;
        }
        else {
            return $debit;
        }

    }

    public function remise($customer_number,$amount,$fees,$currency){
        
        $compte = "current";
        $sender_phone = Auth::user()->phone_number;
        $type = "remise";
        $action = "credit";
        $generate = new generateReferenceController;
        $reference = $generate->reference($type);
        $credit = $this->credit_customer($currency, $amount, $compte, $customer_number);
        if ($credit['success'] == true) {
            $status = "Succès";
            $withdrawal_status = "En attente";
            $status_description = "Remise effectuée avec succès!";
            $this->transaction($sender_phone,$customer_number,$amount,$fees,$currency,$status,$reference,$action,$type,$status_description);
            // $this->table_withdrawal($customer_number,$amount,$fees,$currency,$withdrawal_status,$reference);
            $response = [
                'success' => true,
                'message' => "Remise effectuée avec succès",
                'status' => "Successfull",
            ];
            return $response;
        }
        else {
            return $credit;
        }

    }

    public function mobilemoney($sender_number,$customer_number,$amount,$currency,$comment,$method,$action,$status,$reference,$transaction_id){

        $todayDate = $this->todayDate();

        $dataAll = [
            'sender_number' => $sender_number,
            'customer_number' => $customer_number,
            'amount' => $amount,
            'currency' => $currency,
            'comment' => $comment,
            'action' => $action,
            'method' => $method,
            'status' => $status,
            'reference' => $reference,
            'transaction_id' => $transaction_id,
            'created_at' => $todayDate,
            'updated_at' => $todayDate
        ];

        $success = DB::table('mobile_money')->insert($dataAll);

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

    public function branche_user_limit(){
        $branche_id = $this->branche_id(Auth::user()->id);
        $agentNumber = Account::where('branche_id',$branche_id)->distinct('user_id')->count();
        if ($agentNumber == 3) {
            $response = [
                'success' => false,
                'message' => "Nombre total (3) des agents dans une agence atteind!",
                'status' => "Failed",
            ];
            return $response;
        }
        else {
            $response = [
                'success' => true,
                'message' => "Fine!",
                'status' => "Successful",
            ];
            return $response;
        }
    }
    public function todayDate(){
        Carbon::setLocale('fr');
        $todayDate = Carbon::now()->format('Y-m-d H:i:s');
        return $todayDate;
    }
    public function activity_log($activityLog){
        DB::table('user_activity_logs')->insert($activityLog);
    }
    public function branche_id($user_id){
        $branche = Branche::where('user_id', $user_id)->first();
        $branche_id = $branche->id;
        return $branche_id;
    }
    public function create_customer($firstname,$lastname,$phone,$adresse,$ville,$role,$pays){

        $todayDate = $this->todayDate();

        $verify_number = new verifyNumberController;
        $phone_number = $verify_number->verify_number($phone);

        $default = new GenerateIdController;
        $password = $default->defaultPIN();
        $wallet_code = $default->AccountNumber();


        $check_user = DB::connection('mysql2')->table('users')->where('phone',$phone_number)->first();

        if ($check_user == null) {
            $user = DB::connection('mysql2')->table('users')->insert([
                'firstname'      => $firstname,
                'name'      => $lastname,
                'phone'      => $phone_number,
                'avatar'    => "user.png",
                'city'     => $ville,
                'country'     => $pays,
                'address'     => $adresse,
                'role_name' =>$role,
                'password'  => Hash::make($password),
                'password_salt'  => $password,
                'created_at'   => $todayDate,
                'updated_at'   => $todayDate,
            ]);

            if ($user) {
                $customer = DB::connection('mysql2')->table('users')->where('phone',$phone_number)->first();
                $customer_id = $customer->id;

                $customer_wallet_1 = DB::connection('mysql2')->table('wallets')->insert([
                    'customer_id'   => $customer_id,
                    'wallet_code'   => $wallet_code,
                    'wallet_balance'   => 0,
                    'wallet_currency'   => 'CDF',
                    'created_at'   => $todayDate,
                    'updated_at'   => $todayDate,
                ]);

                $customer_wallet_2 = DB::connection('mysql2')->table('wallets')->insert([
                    'customer_id'   => $customer_id,
                    'wallet_code'   => $wallet_code,
                    'wallet_balance'   => 0,
                    'wallet_currency'   => 'USD',
                    'created_at'   => $todayDate,
                    'updated_at'   => $todayDate,
                ]);

                if ($customer_wallet_1 && $customer_wallet_2) {
                        $response = [
                            'success' => true,
                            'message' => "L'utilisateur a été créé avec succès !",
                            'status' => "Successful",
                        ];
                        return $response;

                }
                else {
                    $response = [
                        'success' => false,
                        'message' => "Une erreur est survenue lors de la création du compte principal du client!",
                        'status' => "Failed",
                    ];
                    return $response;
                }
            }
            else {
                $response = [
                    'success' => false,
                    'message' => "Une erreur est survenue lors de la création du client!",
                ];
                return $response;
            }
        }
        else {
            $response = [
                'success' => false,
                'message' => "Numéro déjà enregistré dans le système, veuillez changer de numéro svp!",
            ];
            return $response;
        }
    }

    public function update_customer($firstname,$lastname,$phone,$adresse,$ville,$pays, $user_id){

        $todayDate = $this->todayDate();
        $update = DB::connection('mysql2')->table('users')->where('id',$user_id)->update([
            'firstname'      => $firstname,
            'name'      => $lastname,
            'phone'      => $phone,
            'city'     => $ville,
            'country'     => $pays,
            'address'     => $adresse,
            'updated_at'   => $todayDate,
        ]);

        if ($update) {
            $response = [
                'success' => true,
                'message' => "L'utilisateur a été modifié avec succès !",
                'status' => "Successful",
            ];
            return $response; $response;
        }

        else {
            $response = [
                'success' => false,
                'message' => "Une erreur est survenue lors de la modification du client!",
            ];
            return $response;
        }

    }

    public function compte_client($user_id){

        $users = DB::connection('mysql2')->table('users')->where('id', $user_id)->first();
        $current_wallet_cdf = DB::connection('mysql2')->table('wallets')->where('wallet_type', 'current')->where('wallet_currency', 'CDF')->where('customer_id', $user_id)->first();
        $current_wallet_usd = DB::connection('mysql2')->table('wallets')->where('wallet_type', 'current')->where('wallet_currency', 'USD')->where('customer_id', $user_id)->first();
        $saving_wallet_cdf = DB::connection('mysql2')->table('wallets')->where('wallet_type', 'saving')->where('wallet_currency', 'CDF')->where('customer_id', $user_id)->first();
        $saving_wallet_usd = DB::connection('mysql2')->table('wallets')->where('wallet_type', 'saving')->where('wallet_currency', 'USD')->where('customer_id', $user_id)->first();

        $data = [$users,$current_wallet_cdf,$current_wallet_usd,$saving_wallet_cdf,$saving_wallet_usd];

        $response = [
            'success' => true,
            'resultat' => 1,
            'status' => "Successful",
            'data' => [$data],
        ];

        return $response;

    }

    public function credit_customer($currency, $amount,  $compte, $customer_number){
        $user_id = $this->getCustomerID($customer_number);
        $todayDate = $this->todayDate();
        $account = DB::connection('mysql2')->table('wallets')->where('wallet_type', $compte)->where('wallet_currency', $currency)->where('customer_id', $user_id)->first();
        if ($account != null) {
            $balance = $account->wallet_balance;
            $wallet_id = $account->id;
            $data = [
                'wallet_balance'    => $balance + $amount,
            ];
            $update = DB::connection('mysql2')->table('wallets')->where('id',$wallet_id)->update($data);
            $user_id = Auth::user()->id;
            $activityLog = [
                'fullname'   => Auth::user()->firstname." ".Auth::user()->lastname,
                'user_phone'   => Auth::user()->phone_number,
                'activity'   => "Vient de créditer le client ".$customer_number,
                'updated_at'   => $todayDate,
            ];
            if ($update) {
                // $data = [
                //     'account_id' => $account->id,
                //     'user_id' => $user_id,
                //     'currency' => $currency,
                //     'amount' => $amount,
                //     'created_at'   => $todayDate,
                //     'updated_at'   => $todayDate,
                // ];
                // $save = DB::table('account_stories')->where('currency', $currency)->insert($data);
                $this->activity_log($activityLog);

                    $response = [
                        'success' => true,
                        'resultat' => 1,
                        'message' => "Wallet credité avec succès",
                        'status' => "Successful",
                    ];
                    return $response;

            }
            else {
                $activityFailed= [
                    'fullname'   => Auth::user()->firstname." ".Auth::user()->lastname,
                    'user_phone'   => Auth::user()->phone_number,
                    'activity'   => "a tenté de recharger le compte ".$account->id,
                    'updated_at'   => $todayDate,
                ];
                $this->activity_log($activityFailed);
                $response = [
                    'success' => false,
                    'message' => "Une erreur est survenue lors de la création du compte",
                    'status' => "Failed",
                ];
                return $response;
            }

        }

    }

    public function debit_customer($currency, $amount, $fees, $compte, $customer_number){
        $balance_data = $this->customer_current_balance($customer_number);
        $total = $amount + $fees;
        if ($currency == "CDF") {
            $balance = $balance_data[0];
            if ($total > $balance) {
                $response = [
                    'success' => false,
                    'message' => "Balance insuffisante!",
                    'status' => "Failed",
                ];
                return $response;
            }
        }
        elseif ($currency == "USD") {
            $balance = $balance_data[1];
            if ($total > $balance) {
                $response = [
                    'success' => false,
                    'message' => "Balance insuffisante!",
                    'status' => "Failed",
                ];
                return $response;
            }
        }
        $user_id = $this->getCustomerID($customer_number);
        $todayDate = $this->todayDate();
        $account = DB::connection('mysql2')->table('wallets')->where('wallet_type', $compte)->where('wallet_currency', $currency)->where('customer_id', $user_id)->first();
        if ($account != null) {
            $balance = $account->wallet_balance;
            $wallet_id = $account->id;
            $data = [
                'wallet_balance'    => $balance - $total,
            ];
            $update = DB::connection('mysql2')->table('wallets')->where('id',$wallet_id)->update($data);
            $user_id = Auth::user()->id;
            $activityLog = [
                'fullname'   => Auth::user()->firstname." ".Auth::user()->lastname,
                'user_phone'   => Auth::user()->phone_number,
                'activity'   => "Vient de debiter le client ".$customer_number,
                'updated_at'   => $todayDate,
            ];
            if ($update) {

                $this->activity_log($activityLog);

                    $response = [
                        'success' => true,
                        'resultat' => 1,
                        'message' => "Wallet debité avec succès",
                        'status' => "Successful",
                    ];
                    return $response;

            }
            else {
                $activityFailed= [
                    'fullname'   => Auth::user()->firstname." ".Auth::user()->lastname,
                    'user_phone'   => Auth::user()->phone_number,
                    'activity'   => "a tenté de débiter le compte ".$account->id,
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

    }

    public function customer_deposit($customer_number,$compte,$amount,$fees,$currency){
        $wallet = $this->customer_current_wallet($customer_number);
        $total_amount = $amount + $fees;
        $action = "credit";
        $type = "depot";
        $generate = new generateReferenceController;
        $reference = $generate->reference($type);
        if ($compte == "current") {

            if (Auth::user()->role_name == "Admin" || Auth::user()->role_name == "Manager") {
                $this->creditGerantTiroir($currency, $total_amount);
                $this->debitGerantAccount($currency, $amount);
                $credit_customer = $this->credit_customer($currency, $amount, $compte, $customer_number);

                if ($credit_customer['success'] == true) {
                    $status = "Succès";
                    $status_description = "Transfert effectué avec succès!";
                    $this->transaction(Auth::user()->phone_number,$customer_number,$amount,$fees,$currency,$status,$reference,$action,$type,$status_description);
                    $response = [
                        'success' => true,
                        'resultat' => 1,
                        'message' => "Dépôt effectué avec succès",
                        'status' => "Successful",
                    ];
                    return $response;
                }
                else {
                    $status = "Echouée";
                    $status_description = $credit_customer['message'];
                    $this->transaction(Auth::user()->phone_number,$customer_number,$amount,$fees,$currency,$status,$reference,$action,$type,$status_description);
                    $response = [
                        'success' => false,
                        'resultat' => 0,
                        'message' => "Dépôt echoué",
                        'status' => "Failed",
                    ];
                    return $response;
                }
            }

        }

        if ($compte == "saving") {
            $verify = $this->verify_saving_account($compte,$currency,$customer_number);
            if ($verify['success'] == true) {
                if (Auth::user()->role_name == "Admin" || Auth::user()->role_name == "Manager") {
                    $this->creditGerantTiroir($currency, $total_amount);
                    $this->debitGerantAccount($currency, $amount);
                    $credit_customer = $this->credit_customer($currency, $amount, $compte, $customer_number);

                    if ($credit_customer['success'] == true) {
                        $response = [
                            'success' => true,
                            'resultat' => 1,
                            'message' => "Dépôt effectué avec succès",
                            'status' => "Successful",
                        ];
                        return $response;
                    }
                    else {
                        $response = [
                            'success' => false,
                            'resultat' => 0,
                            'message' => "Dépôt echoué",
                            'status' => "Failed",
                        ];
                        return $response;
                    }
                }
            }
            else {
                return $verify;
            }
        }

    }

    public function customer_transfer($sender_phone,$sender_first,$sender_last,$receiver_phone,$receiver_first,$receiver_last,$amount,$fees,$currency){
        $total_amount = $amount + $fees;
        $branche_id = $this->branche_id(Auth::user()->id);
        $user_id = Auth::user()->id;
        $type = "transfert";
        $action = "credit";
        $todayDate = $this->todayDate();
        $generate = new generateReferenceController;
        $reference = $generate->reference($type);
        if (Auth::user()->role_name == "Admin" || Auth::user()->role_name == "Manager") {
            $this->creditGerantTiroir($currency, $total_amount);
            $credit = $this->creditGerantAccount($currency, $total_amount);

            if ($credit['success'] == true) {
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
                    'status' => "Envoyé",
                    'created_at'   => $todayDate,
                    'updated_at'   => $todayDate,
                ];
                $done = DB::table('emala_transferts')->insert($data);
                if ($done) {
                    $status = "Succès";
                    $status_description = "Transfert effectué avec succès!";
                    $this->transaction($sender_phone,$receiver_phone,$amount,$fees,$currency,$status,$reference,$action,$type,$status_description);
                    $response = [
                        'success' => true,
                        'resultat' => 1,
                        'message' => "Transfert effectué avec succès",
                        'status' => "Successful",
                    ];
                    return $response;
                }
            }
            else {
                $status = "Echouée";
                $status_description = $credit['message'];
                $this->transaction($sender_phone,$receiver_phone,$amount,$fees,$currency,$status,$reference,$action,$type,$status_description);
                return $credit;
            }
        }

    }
    public function table_withdrawal($customer_number,$amount,$fees,$currency,$status,$transaction_id){
        $branche_id = $this->branche_id(Auth::user()->id);
        $user_id = Auth::user()->id;
        $receiver_id = $this->getCustomerID($customer_number);
        $todayDate = $this->todayDate();
        if ($currency == "CDF") {
            $devise = 1;
        }
        elseif ($currency == "USD") {
            $devise = 2;
        }
        $data = [
            'receiver_id' => $receiver_id,
            'amount' => $amount,
            'fees' => $fees,
            'transaction_id' => $transaction_id,
            'currency' => $devise,
            // 'withdrawal_type' => $withdrawal_type,
            'status' => $status,
            'payment_method' => 1,
            'branche_id' => $branche_id,
            'user_id' => $user_id,
            'created_at'   => $todayDate,
            'updated_at'   => $todayDate,
        ];
        DB::table('withdrawals')->insert($data);
    }
    public function table_transfer($sender_phone,$sender_first,$sender_last,$amount,$fees,$currency,$reference,$receiver_phone,$receiver_first,$receiver_last){
        $branche_id = $this->branche_id(Auth::user()->id);
        $user_id = Auth::user()->id;
        $type = "transfert";
        $action = "credit";
        $todayDate = $this->todayDate();
        // $generate = new generateReferenceController;
        // $reference = $generate->reference($type);
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
            'status' => "Envoyé",
            'created_at'   => $todayDate,
            'updated_at'   => $todayDate,
        ];
        DB::table('emala_transferts')->insert($data);
    }
    public function transaction($sender_phone,$receiver_phone,$amount,$fees,$currency,$status,$transaction_id,$action,$type,$status_description){
        $branche_id = $this->branche_id(Auth::user()->id);
        $user_id = Auth::user()->id;
        $todayDate = $this->todayDate();
        $data = [
            'user_id' => $user_id,
            'branche_id' => $branche_id,
            'payment_method' => 1,
            'sender_phone' => $sender_phone,
            'amount' => $amount,
            'fees' => $fees,
            'currency' => $currency,
            'receiver_phone' => $receiver_phone,
            'status' => $status,
            'transaction_id' => $transaction_id,
            'type' => $type,
            'action' => $action,
            'date'   => $todayDate,
            'status_description' => $status_description,
            'created_at'   => $todayDate,
            'updated_at'   => $todayDate,
        ];
        DB::table('transactions')->insert($data);
    }
    public function verify_saving_account($compte,$currency,$customer_number){
        $user_id = $this->getCustomerID($customer_number);
        $account = DB::connection('mysql2')->table('wallets')->where('wallet_type', $compte)->where('wallet_currency', $currency)->where('customer_id', $user_id)->first();


        if ($account == null) {
            $response = [
                'success' => false,
                'message' => "Le client n'a pas de compte epargne",
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

    public function customer_current_wallet($customer_number){
        $user_id = $this->getCustomerID($customer_number);
        $current_wallet_cdf = DB::connection('mysql2')->table('wallets')->where('wallet_type', 'current')->where('wallet_currency', 'CDF')->where('customer_id', $user_id)->first();
        $current_wallet_usd = DB::connection('mysql2')->table('wallets')->where('wallet_type', 'current')->where('wallet_currency', 'USD')->where('customer_id', $user_id)->first();
        $data = [$current_wallet_cdf,$current_wallet_usd];
        return $data;
    }

    public function customer_current_balance($customer_number){
        $user_id = $this->getCustomerID($customer_number);
        $current_wallet_cdf = DB::connection('mysql2')->table('wallets')->where('wallet_type', 'current')->where('wallet_currency', 'CDF')->where('customer_id', $user_id)->first();
        $current_wallet_usd = DB::connection('mysql2')->table('wallets')->where('wallet_type', 'current')->where('wallet_currency', 'USD')->where('customer_id', $user_id)->first();
        $balance_cdf = $current_wallet_cdf->wallet_balance;
        $balance_usd = $current_wallet_usd->wallet_balance;
        $data = [$balance_cdf,$balance_usd];
        return $data;
    }

    public function getCustomerID($customer_number){
        $users = DB::connection('mysql2')->table('users')->where('phone', $customer_number)->first();
        $user_id = $users->id;
        return $user_id;
    }

    public function verifyCustomer($customer_number){
        $users = DB::connection('mysql2')->table('users')->where('phone', $customer_number)->count();
        if ($users >= 1) {
            $response = ['success' => true ];
            return $response;
        }
        else {
            $response = ['success' => false ];
            return $response;
        }
    }

    public function delete_client($user_id)
    {
        $todayDate = $this->todayDate();
        $user = DB::connection('mysql2')->table('users')->where('id',$user_id)->first();
        $activityLog = [
            'fullname'   => Auth::user()->firstname." ".Auth::user()->lastname,
            'user_phone'   => Auth::user()->phone_number,
            'user_id'   => Auth::user()->id,
            'activity'   => "Vient de supprimer le client ".$user->phone,
            'updated_at'   => $todayDate,
        ];

        $save = DB::table('user_activity_logs')->insert($activityLog);

        if($save){
            $destroy = DB::connection('mysql2')->table('users')->delete($user_id);
            if ($destroy) {
                $response = [
                    'success' => true,
                    'message' => "Client supprimé avec succès!",
                    'status' => "Successful",
                ];
                return $response;
            }
            else {
                $response = [
                    'success' => false,
                    'message' => "Echec de l'opération!",
                    'status' => "Failed",
                ];
                return $response;
            }
        }
    }


}
