<?php

namespace App\Http\Controllers;

use App\Models\account;
use App\Models\bank_account;
use App\Models\bank_user;
use App\Models\branch;
use App\Models\tirroir_account;
use App\Models\User;
use App\Models\Wallet;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class __init__ extends Controller
{

    public function bank_account($userid){
        $response = $this->cashier_account_wallet($userid);
        $bank_user_id = $response->bank_user_id;
        $bank_account = DB::table('bank_accounts')->where('bank_user_id',$bank_user_id)->first();
        return $bank_account;
    }

    public function fond_precedent(){
        $userid = Auth::User()->id;
        $response = $this->cashier_account_wallet($userid);
        $bank_user_id = $response->bank_user_id;
       
        $bank_account = DB::table('bank_accounts')->where('bank_user_id',$bank_user_id)->first();
        $bank_account_id = $bank_account->id;

        $fond_precedent = DB::table('cash_registers')->where('bank_account_id',$bank_account_id)->latest()->first();

        // if ($fond_precedent == null) {
        if ($bank_account == null) {
            $data = [
                'report_cdf_on_c'  => 0,
                'report_usd_on_c'  => 0,
            ];
        }

        else {
            // $data = [
            //     'report_cdf_on_c'  => $fond_precedent->fund_cdf_on_c,
            //     'report_usd_on_c'  => $fond_precedent->fund_usd_on_c,
            // ];

            $data = [
                'report_cdf_on_c'  => $bank_account->balance_cdf,
                'report_usd_on_c'  => $bank_account->balance_usd,
            ];
        }
         
        return $data;
    }

    public function cash_session(){
        $userid = Auth::User()->id;
        $bank_account = $this->bank_account($userid);
        $cash_register = DB::table('cash_registers')->where('bank_account_id',$bank_account->id)->latest()->first();
        if ($cash_register == null) {
            $session = null;
        }
        else {
            $session = $cash_register->closed;
        }
        
        return $session;
        
    }

    public function cash_register_detail(){

        $userid = Auth::user()->id;
        $users = DB::table('users')->where('id',$userid)->first();
        $phone_number = $users->phone_number;
        $bank_user = DB::table('bank_users')->where('phone_number',$phone_number)->first();
        $bank_account = DB::table('bank_accounts')->where('bank_user_id',$bank_user->id)->first();
        $cash_register = DB::table('cash_registers')->where('bank_account_id',$bank_account->id)->first();
        return $cash_register;
        
    }

    public function username($firstname, $lastname) {
        // original username
        $username = "{$firstname[0]}_{$lastname}";
        // if you have  a username column
        $user_count = User::where('username', $username)->count();
        // append digit if exists
        if ($user_count > 0) {
            $username .= "_$user_count";
        }
        return $username;

    }
    public function currency(){
        $currency = DB::table('currency_supporteds')->get();
        return $currency;
    }
    public function currency_id($currency_id){
        $currency = DB::table('currency_supporteds')->where('id',$currency_id)->first();
        return $currency->currency_code;
    }
    public function currency_code($currency_code){
        $currency = DB::table('currency_supporteds')->where('currency_code',$currency_code)->first();
   
        return $currency->id;
    }
    public function create_wallet($acnumber,$user_id){
        $todayDate = $this->todayDate();
        $default = new GenerateIdController;
        $wallet_id = $default->wallet_id();

        $account = DB::table('accounts')->where('acnumber',$acnumber)->first();
        $user = DB::table('users')->where('id',$user_id)->first();

        if ($user->role_name == "Root") {
            $wallet_level = "Parent";
        }

        else {
            $wallet_level = "Inner";
        }

        $activityLog = [
            'fullname'   => Auth::user()->firstname." ".Auth::user()->lastname,
            'user_phone'   => Auth::user()->phone_number,
            'user_id'   => Auth::user()->id,
            'activity'   => "Vient de créer le wallet ".$wallet_id,
            'updated_at'   => $todayDate,
        ];

        $wallet_1 = Wallet::create([
            'wallet_id'      => $wallet_id,
            'account_id'      => $account->id,
            'balance_cdf' =>0,
            'balance_usd' =>0,
            'status'  => "Active",
            'level'  => $wallet_level,
            'created_at'   => $todayDate,
            'updated_at'   => $todayDate,
        ]);



        if ($wallet_1) {
            $this->activity_log($activityLog);
            $response = [
                'success' => true,
                'message' => "Wallet a été créé avec succès!",
                'status' => "Successful",
            ];
            return $response;
        }
        else {
            $response = [
                'success' => false,
                'message' => "Une erreur est suvenue lors de la création du wallet!",
                'status' => "Failed",
            ];
            return $response;
        }
    }
    public function verify_main_wallet_balance($amount, $currency){

        $wallet = DB::table('ewallets')->where('type', 'Emala')->first();
        if ($currency == "CDF") {
            $balance = $wallet->balance_cdf;
        }
        elseif ($currency == "USD") {
            $balance = $wallet->balance_usd;
        }

        if ($amount > $balance) {
            $response = [
                'success' => false,
                'resultat' => 2,
                'message' => "Balance insuffisante",
                'status' => "Failed",
                'balance_usd' => $wallet->balance_usd,
                'balance_cdf' => $wallet->balance_cdf,
                'wallet_id' => $wallet->id,
            ];
            return $response;
        }

        else {
            $response = [
                'success' => true,
                'resultat' => 1,
                'message' => "Balance suffisante",
                'status' => "Successful",
                'balance_usd' => $wallet->balance_usd,
                'balance_cdf' => $wallet->balance_cdf,
                'wallet_id' => $wallet->id,
            ];
            return $response;
        }
    }
    public function main_wallet_balance(){

        $wallet = DB::table('ewallets')->where('type', 'Emala')->first();
       
        $balance_cdf = $wallet->balance_cdf;
       
        $balance_usd = $wallet->balance_usd;
        

        $response = [
            'balance_usd' => $balance_usd,
            'balance_cdf' => $balance_cdf,
        ];
        return $response;
    }

    public function bank_account_balance($bank_user_id){
        $bank_account = DB::table('bank_accounts')->where('bank_user_id', $bank_user_id)->first();
        $balance_cdf = $bank_account->balance_cdf;
        $balance_usd = $bank_account->balance_usd;
        $response = [
            'balance_usd' => $balance_usd,
            'balance_cdf' => $balance_cdf,
        ];
        return $response;
    }

    public function debit_main_wallet($amount, $currency){
        $todayDate = $this->todayDate();
        $wallet = DB::table('ewallets')->where('type', 'Emala')->first();
        if ($currency == "CDF") {
            $balance_cdf = $wallet->balance_cdf - $amount;
            $dataStored = [
                'balance_cdf' => $balance_cdf,
                'updated_at'   => $todayDate,
            ];
        }
        elseif ($currency == "USD") {
            $balance_usd = $wallet->balance_usd - $amount;
            $dataStored = [
                'balance_usd' => $balance_usd,
                'updated_at'   => $todayDate,
            ];
        }

        $update = DB::table('ewallets')->where('type', 'Emala')->update($dataStored);  
        if ($update) {
            $response = [
                'success' => true,
            ];
            return $response;
        }
        else {
            $response = [
                'success' => false,
            ];
            return $response;
        }
    }
    
    public function credit_bank_account($bank_user_id, $amount, $currency){
        $todayDate = $this->todayDate();
        $balance = $this->bank_account_balance($bank_user_id);

            if ($currency == "CDF") {
                $dataStored = [
                    'balance_cdf' => $balance['balance_cdf'] + $amount,
                    'updated_at'   => $todayDate,
                ];
            }
            elseif ($currency == "USD") {
                $dataStored = [
                    'balance_usd' => $balance['balance_usd'] + $amount,
                    'updated_at'   => $todayDate,
                ];
            }
            
            $update = DB::table('bank_accounts')->where('bank_user_id', $bank_user_id)->update($dataStored);  
            if ($update) {
                $response = [
                    'success' => true,
                ];
                return $response;
            }
            else {
                $response = [
                    'success' => false,
                ];
                return $response;
            }
        //}
        
    }

    public function credit_agence_account($bank_user_id, $amount, $currency){
        $todayDate = $this->todayDate();
        $balance = $this->bank_account_balance($bank_user_id);

        $debit_main_wallet = $this->debit_main_wallet($amount, $currency);
        if ($debit_main_wallet['success'] == true) {
            if ($currency == "CDF") {
                $dataStored = [
                    'balance_cdf' => $balance['balance_cdf'] + $amount,
                    'updated_at'   => $todayDate,
                ];
            }
            elseif ($currency == "USD") {
                $dataStored = [
                    'balance_usd' => $balance['balance_usd'] + $amount,
                    'updated_at'   => $todayDate,
                ];
            }
            
            $update = DB::table('bank_accounts')->where('bank_user_id', $bank_user_id)->update($dataStored);  
            if ($update) {
                $response = [
                    'success' => true,
                ];
                return $response;
            }
            else {
                $response = [
                    'success' => false,
                ];
                return $response;
            }
        }
        
    }

    public function topup_agence($bank_user_id, $amount, $currency){

        $todayDate = $this->todayDate();
        # On vérifie la balance du ewallet emala
        $verify = $this->verify_main_wallet_balance($amount, $currency);
        # Si la balance est suffisante, on débite le wallet pour créditer le compte du caissier
        if ($verify['success'] == true) {

            $this->topup_tirroir($bank_user_id, $amount, $currency);

            if ($currency == "CDF") {
                $balance = $verify['balance_cdf'];
                $data = [
                    'balance_cdf'    => $balance - $amount,
                ];
            }
            elseif ($currency == "USD") {
                $balance = $verify['balance_usd'];
                $data = [
                    'balance_usd'    => $balance - $amount,
                ];
            }

            # Ici, on débit le wallet principal
            $update = DB::table('ewallets')->where('type', 'Emala')->update($data);  
            // DB::table('etirroirs')->where('type', 'Emala')->update($data);  
            if ($update) {
                # On credit le compte du caissier
                $credit_account_cashier = $this->credit_bank_account($bank_user_id, $amount, $currency);
                if ($credit_account_cashier) {
                    $response = [
                        'success' => true,
                        'message' => "Compte rechargé avec succès!",
                        'status' => "Successful",
                    ];
                    return $response;
                }
                
            }
            else {
                $response = [
                    'success' => false,
                    'message' => "L'opération a échoué",
                    'status' => "Failed",
                ];
                return $response;
            }

        }
        else{ 
            return $verify;
        }

    }

    public function topup_wallet($amount, $wallet_id,$currency){
        $wallet = DB::table('wallets')->where('id', $wallet_id)->first();
        $todayDate = $this->todayDate();
        if ($currency == "CDF") {
            $balance = $wallet->balance_cdf;
            $data = [
                'balance_cdf'    => $balance + $amount,
            ];
        }
        elseif ($currency == "USD") {
            $balance = $wallet->balance_usd;
            $data = [
                'balance_usd'    => $balance + $amount,
            ];
        }
        
        $update = DB::table('wallets')->where('id', $wallet_id)->update($data);  
        $user_id = Auth::user()->id;

        $activityLog = [
            'fullname'   => Auth::user()->firstname." ".Auth::user()->lastname,
            'user_phone'   => Auth::user()->phone_number,
            'user_id'   => Auth::user()->id,
            'activity'   => "Vient de recharger le wallet ".$wallet->wallet_id,
            'updated_at'   => $todayDate,
        ];
        if ($update) {
            if ($currency == "CDF") {
                $data = [
                    'wallet_id' => $wallet->id,
                    'user_id' => $user_id,
                    'currency' => "CDF",
                    'amount' => $amount,
                    'created_at'   => $todayDate,
                    'updated_at'   => $todayDate,
                ];
                $save = DB::table('wallet_stories')->insert($data);  
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

            elseif ($currency == "USD") {
                $data = [
                    'wallet_id' => $wallet->id,
                    'user_id' => $user_id,
                    'currency' => "USD",
                    'amount' => $amount,
                    'created_at'   => $todayDate,
                    'updated_at'   => $todayDate,
                ];
                $save = DB::table('wallet_stories')->insert($data);  
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
        }          
    }
   
    public function create_branche($btownship,$bcity,$btype,$emaila,$user_id){
        $todayDate = $this->todayDate();
        $generate = new GenerateIdController;
        $bname = $generate->code_agence();

        $bank = DB::table('banks')->where('bank_phone','243816205345')->first();
        $bank_id = $bank->id;

        $branche = branch::create([
            'user_id'   => $user_id,
            'bname'   => $bname,
            'bemail'   => $emaila,
            'btownship'   => $btownship,
            'bcity'   => $bcity,
            'btype'   => $btype,
            'bank_id'   => $bank_id,
            'created_at'   => $todayDate,
            'updated_at'   => $todayDate,
        ]);

        $activityLog = [
            'fullname'   => Auth::user()->firstname." ".Auth::user()->lastname,
            'user_phone'   => Auth::user()->phone_number,
            'user_id'   => Auth::user()->id,
            'activity'   => "Vient de créer l'agence de ".$btownship,
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
    }

    public function update_branche($bid,$btownship,$bcity,$btype,$bemail){
        $todayDate = $this->todayDate();
        
        $branche = [
            'bemail'   => $bemail,
            'btownship'   => $btownship,
            'bcity'   => $bcity,
            'btype'   => $btype,
            'updated_at'   => $todayDate,
        ];

        

        $activityLog = [
            'fullname'   => Auth::user()->firstname." ".Auth::user()->lastname,
            'user_phone'   => Auth::user()->phone_number,
            'user_id'   => Auth::user()->id,
            'activity'   => "Vient de modifier l'agence de ".$btownship,
            'updated_at'   => $todayDate,
        ];

        $save = DB::table('branches')->where('id',$bid)->update($branche);  
        if ($save) {
            $this->activity_log($activityLog);
            $response = [
                'success' => true,
                'resultat' => 1,
                'message' => "Agence modifiée avec succès!",
                'status' => "Successful",
            ];
            return $response;
        }                
    }

    public function ewallet_stories($wallet_id, $user_id, $currency, $amount, $operation){
        $todayDate = $this->todayDate();
        $data = [
            'wallet_id' => $wallet_id,
            'user_id' => $user_id,
            'currency' => $currency,
            'amount' => $amount,
            'operation' => $operation,
            'created_at'   => $todayDate,
            'updated_at'   => $todayDate,
        ];
        $logs = DB::table('ewallet_stories')->insert($data); 

        if ($logs) {
            $response = [
                'success' => true,
                'status' => "Successful",
            ];
            return $response;
        }

        else {
            $response = [
                'success' => false,
                'status' => "Failed",
            ];
            return $response;
        }
        
    }
    public function activity_log($activityLog){
        DB::table('user_activity_logs')->insert($activityLog);
    }

    public function todayDate(){
        Carbon::setLocale('fr');
        $todayDate = Carbon::now()->format('Y-m-d H:i:s');
        return $todayDate;
    }

    public function create_customer($firstname,$lastname,$email,$telephone,$adresse,$ville,$role, $from){
        
        $todayDate = $this->todayDate();

        $verify_number = new VerifyNumberController;
        $phone_number = $verify_number->verify_number($telephone);

        $default = new GenerateIdController;
        $wallet_code = $default->AccountNumber();
        $password = $default->defaultPIN();
        $savingcode = $default->SavingAcnumber();
        
        $check_user = DB::connection('mysql2')->table('users')->where('phone',$phone_number)->first();
        $mail_verify = DB::connection('mysql2')->table('users')->where('email',$email)->count();

        if ($mail_verify  >= 1) {
            $response = [
                'success' => false,
                'message' => "Adresse e-mail déjà enregistré dans le système!",
            ];
            return $response;
        }

        else {
            if ($check_user == null) {
                $user = DB::connection('mysql2')->table('users')->insert([
                    'firstname'      => $firstname,
                    'email'      => $email,
                    'name'      => $lastname,
                    'phone'      => $phone_number,
                    'avatar'    => "user.png",
                    'city'     => $ville,
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

                    $customer_wallet_3 = DB::connection('mysql2')->table('wallets')->insert([
                        'customer_id'   => $customer_id,
                        'wallet_code'   => $savingcode,
                        'wallet_balance'   => 0,
                        'wallet_currency'   => 'CDF',
                        'wallet_type' => 'saving',
                        'created_at'   => $todayDate,
                        'updated_at'   => $todayDate,
                    ]);

                    $customer_wallet_4 = DB::connection('mysql2')->table('wallets')->insert([
                        'customer_id'   => $customer_id,
                        'wallet_code'   => $savingcode,
                        'wallet_balance'   => 0,
                        'wallet_currency'   => 'USD',
                        'wallet_type' => 'saving',
                        'created_at'   => $todayDate,
                        'updated_at'   => $todayDate,
                    ]);
                    
                    if ($customer_wallet_1 && $customer_wallet_2 && $customer_wallet_3 && $customer_wallet_4) {
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

        
    }

    public function bank_id(){
        $bank = DB::table('banks')->where('bank_phone','243816205345')->first();
        $bank_id = $bank->id;
        return $bank_id;
    }

    public function create_user($firstname,$lastname,$telephone,$adresse,$ville,$role,$btownship,$bcity,$btype,$email,$emaila){
  
        $todayDate = $this->todayDate();

        $verify_number = new VerifyNumberController;
        $phone_number = $verify_number->verify_number($telephone);

        $default = new GenerateIdController;
        $password = $default->defaultPIN();

        $check_user = DB::table('users')->where('phone_number',$phone_number)->first();

        if ($check_user == null) {
            $user = User::create([
                'created_from'      => "Back-office",
                'firstname'      => $firstname,
                'lastname'      => $lastname,
                'email'      => $email,
                'phone_number'      => $phone_number,
                'avatar'    => "user.png",
                'city'     => $ville,
                'address'     => $adresse,
                'join_date' => $todayDate,
                'role_name' =>$role,
                'user_status' => 'Hors ligne',
                'password'  => Hash::make($password),
                'salt'  => $password,
                'created_at'   => $todayDate,
                'updated_at'   => $todayDate,
            ]);

            if ($user) {

                $user = DB::table('users')->where('phone_number',$phone_number)->first();
                $password = $user->salt;
                $email = $user->email;
                $phone_number = $user->phone_number;
                $firstname = $user->firstname;
                $lastname = $user->lastname;
                $user_id = $user->id;

                $bank_id = $this->bank_id();

                if ($user->role_name == "Admin") {
                    $type = 1;
                }

                elseif ($user->role_name == "Manager") {
                    $type = 2;
                }

                else {
                    $type = 3;
                }

                $bank_user_created = bank_user::create([
                    'user_id'   => $user_id,
                    'firstname'   => $firstname,
                    'lastname'   => $lastname,
                    'phone_number'   => $phone_number,
                    'email'   => $email,
                    'password'   => $password,
                    'type'   => $type,
                    'bank_id'   => $bank_id,
                    'status'   => 1,
                    'created_at'   => $todayDate,
                    'updated_at'   => $todayDate,
                ]);

                if ($bank_user_created) {
                    $create_branche = $this->create_branche($btownship,$bcity,$btype,$emaila,$user_id);
                    if ($create_branche['success'] == true) {
                        $create_account = $this->create_user_account($user_id);
                        if ($create_account['success'] == true) {
                            $activityLog = [
                                'fullname'   => Auth::user()->firstname." ".Auth::user()->lastname,
                                'user_phone'   => Auth::user()->phone_number,
                                'activity'   => "Vient de créer l'utilisateur ".$phone_number,
                                'updated_at'   => $todayDate,
                            ];
                            $this->activity_log($activityLog);
                            $response = [
                                'success' => true,
                                'message' => "Utilisateur créer avec succès!",
                                'status' => "Successful",
                            ];
                            return $response;
                        }
                        else {
                            $response = [
                                'success' => false,
                                'message' => $create_account['message'],
                                'status' => "Failed",
                            ];
                            return $response;
                        }
                    }
                    else {
                        $response = [
                            'success' => false,
                            'message' => $create_branche['message'],
                        ];
                        return $response;
                    }
                }

                else {
                    $response = [
                        'success' => false,
                        'message' => 'Failed to create bank user',
                        'status' => "Failed",
                    ];
                    return $response;
                }

                

            }
            else {
                $response = [
                    'success' => false,
                    'message' => "Une erreur est survenue lors de la création de l'utilisateur!",
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

    public function create_cashier($firstname,$lastname,$telephone,$adresse,$ville,$role,$email){
  
        $todayDate = $this->todayDate();

        $verify_number = new VerifyNumberController;
        $phone_number = $verify_number->verify_number($telephone);

        $default = new GenerateIdController;
        $password = $default->defaultPIN();

        $check_user = DB::table('users')->where('phone_number',$phone_number)->first();

        if ($check_user == null) {
            $user = User::create([
                'created_from'      => "Back-office",
                'firstname'      => $firstname,
                'lastname'      => $lastname,
                'email'      => $email,
                'phone_number'      => $phone_number,
                'avatar'    => "user.png",
                'city'     => $ville,
                'address'     => $adresse,
                'join_date' => $todayDate,
                'role_name' =>$role,
                'user_status' => 'Hors ligne',
                'password'  => Hash::make($password),
                'salt'  => $password,
                'created_at'   => $todayDate,
                'updated_at'   => $todayDate,
            ]);

            if ($user) {

                $user = DB::table('users')->where('phone_number',$phone_number)->first();
                $password = $user->salt;
                $email = $user->email;
                $phone_number = $user->phone_number;
                $firstname = $user->firstname;
                $lastname = $user->lastname;
                $user_id = $user->id;

                $bank_id = $this->bank_id();
                $type = 3;

                $manager_id = Auth::user()->id;
                

                $bank_user_created = bank_user::create([
                    'user_id'   => $user_id,
                    'firstname'   => $firstname,
                    'lastname'   => $lastname,
                    'phone_number'   => $phone_number,
                    'email'   => $email,
                    'password'   => $password,
                    'type'   => $type,
                    'bank_id'   => $bank_id,
                    'status'   => 1,
                    'created_at'   => $todayDate,
                    'updated_at'   => $todayDate,
                ]);

                if ($bank_user_created) {
                        $create_account = $this->create_cashier_account($user_id,$manager_id);
                        if ($create_account['success'] == true) {
                            $activityLog = [
                                'fullname'   => Auth::user()->firstname." ".Auth::user()->lastname,
                                'user_phone'   => Auth::user()->phone_number,
                                'activity'   => "Vient de créer l'utilisateur ".$phone_number,
                                'updated_at'   => $todayDate,
                            ];
                            $this->activity_log($activityLog);
                            $response = [
                                'success' => true,
                                'message' => "Utilisateur créer avec succès!",
                                'status' => "Successful",
                            ];
                            return $response;
                        }
                        else {
                            $response = [
                                'success' => false,
                                'message' => $create_account['message'],
                                'status' => "Failed",
                            ];
                            return $response;
                        }
                }
                else {
                    $response = [
                        'success' => false,
                        'message' => 'Failed to create bank user',
                        'status' => "Failed",
                    ];
                    return $response;
                }

                

            }
            else {
                $response = [
                    'success' => false,
                    'message' => "Une erreur est survenue lors de la création de l'utilisateur!",
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

    public function branche_id(){
        $user_id = Auth::user()->id;
        $branche = DB::table('branches')->where('user_id',$user_id)->first();
        return $branche->id;
    }

    public function select_branche($manager_id){
        $branche = DB::table('branches')->where('user_id',$manager_id)->first();
        return $branche;
    }
    
    public function create_customer_account($user_id){

        $default = new GenerateIdController;
        $acnumber = $default->AccountNumber();
        $todayDate = $this->todayDate();
        $branche = DB::table('branches')->where('btype',"Parent")->first();


        $account = new account();
        $account->acnumber     = $acnumber;
        $account->user_id      = $user_id;
        $account->branche_id   = $branche->id ;
        $account->status       = "Active";
        $account->actype  = "current";
        $account->created_at   = $todayDate;
        $account->updated_at   = $todayDate;
        $account_created       = $account->save();   

        if ($account_created) {
            $create_wallet = $this->create_wallet($acnumber,$user_id);
            if ($create_wallet['success'] == true) {
                $response = [
                    'success' => true,
                    'message' => "Wallet créé avec succès!",
                    'status' => "Successful",
                ];
                return $response;
            }
            else {
                $response = [
                    'success' => false,
                    'message' => "Erreur lors de la création du wallet!",
                    'status' => "Failed",
                ];
                return $response;
            }
            
        }
        else {
            $response = [
                'success' => false,
                'message' => "Erreur lors de la création du compte!",
            ];
            return $response;
        }

    }

    public function create_user_account($user_id){
     
        $default = new GenerateIdController;
        $acnumber = $default->AccountNumber();
        $todayDate = $this->todayDate();
        $branche = DB::table('branches')->where('user_id',$user_id)->first();
        $acnumber = $default->bank_acount();

        $user = DB::table('users')->where('id',$user_id)->first();
        $phone_number = $user->phone_number;

        $bank_user = DB::table('bank_users')->where('phone_number',$phone_number)->first();
        $bank_user_id = $bank_user->id;

        

        $count_acount = DB::table('bank_accounts')->count();
        
        if ($count_acount >= 1) {
            $agence = DB::table('branches')->where('user_id',$user_id)->where('btype','Inner')->first();
            $agence_id = $agence->id;
            $account_created = bank_account::create([
                'acnumber'   => $acnumber,
                'bank_user_id'   => $bank_user_id,
                'branche_id'   => $agence_id,
                'status'   => 1,
                'created_at'   => $todayDate,
                'updated_at'   => $todayDate,
            ]);
            tirroir_account::create([
                'acnumber'   => $acnumber,
                'bank_user_id'   => $bank_user_id,
                'branche_id'   => $agence_id,
                'status'   => 1,
                'created_at'   => $todayDate,
                'updated_at'   => $todayDate,
            ]);
        }


        elseif ($count_acount == 0) {
            $agence = DB::table('branches')->where('btype','Parent')->first();
            $agence_id = $agence->id;
            $account_created = bank_account::create([
                'acnumber'   => $acnumber,
                'bank_user_id'   => $bank_user_id,
                'branche_id'   => $agence_id,
                'status'   => 1,
                'created_at'   => $todayDate,
                'updated_at'   => $todayDate,
            ]);
            tirroir_account::create([
                'acnumber'   => $acnumber,
                'bank_user_id'   => $bank_user_id,
                'branche_id'   => $agence_id,
                'status'   => 1,
                'created_at'   => $todayDate,
                'updated_at'   => $todayDate,
            ]);
        }

        

        if ($account_created) {
            $activityLog = [
                'fullname'   => Auth::user()->firstname." ".Auth::user()->lastname,
                'user_phone'   => Auth::user()->phone_number,
                'activity'   => "Vient de créer le compte ".$acnumber,
                'updated_at'   => $todayDate,
            ];
            $this->activity_log($activityLog);
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
            ];
            return $response;
        }

    }

    public function create_cashier_account($user_id,$manager_id){
     
        $default = new GenerateIdController;
        $acnumber = $default->AccountNumber();
        $todayDate = $this->todayDate();
        $_branche = $this->select_branche($manager_id);
        $acnumber = $default->bank_acount();

        $user = DB::table('users')->where('id',$user_id)->first();
        $phone_number = $user->phone_number;

        $bank_user = DB::table('bank_users')->where('phone_number',$phone_number)->first();
        $bank_user_id = $bank_user->id;

        $agence_id = $_branche->id;
        $account_created = bank_account::create([
            'acnumber'   => $acnumber,
            'bank_user_id'   => $bank_user_id,
            'branche_id'   => $agence_id,
            'status'   => 1,
            'created_at'   => $todayDate,
            'updated_at'   => $todayDate,
        ]);

        tirroir_account::create([
            'acnumber'   => $acnumber,
            'bank_user_id'   => $bank_user_id,
            'branche_id'   => $agence_id,
            'status'   => 1,
            'created_at'   => $todayDate,
            'updated_at'   => $todayDate,
        ]);

        if ($account_created) {
            $activityLog = [
                'fullname'   => Auth::user()->firstname." ".Auth::user()->lastname,
                'user_phone'   => Auth::user()->phone_number,
                'activity'   => "Vient de créer le compte ".$acnumber,
                'updated_at'   => $todayDate,
            ];
            $this->activity_log($activityLog);
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
            ];
            return $response;
        }

    }

    public function create_saving_account($user_id,$currency,$startDate,$endDate, $from){
        $default = new GenerateIdController;
        $acnumber = $default->AccountNumber();
        $todayDate = $this->todayDate();
        $auth_id = Auth::user()->id;

        if ($from == "Back-office") {
            $branche = DB::table('branches')->where('user_id',$auth_id)->first();
        }
        elseif ($from == "Front-office") {
            $branche = DB::table('branches')->where('btype',"Parent")->first();
        }
        
        $description = "customer";
        $account = new account();
        $account->acnumber     = $acnumber;
        $account->user_id      = $user_id;
        $account->branche_id   = $branche->id ;
        $account->status       = "Active";
        $account->actype  = "saving";
        $account->start_date   = $startDate;
        $account->end_date   = $endDate;
        $account->created_at   = $todayDate;
        $account->updated_at   = $todayDate;
        $account_created       = $account->save();   

        if ($account_created) {
            $create_wallet = $this->create_wallet($acnumber,$user_id);
            if ($create_wallet['success'] == true) {
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
                    'message' => "Erreur lors de la création du wallet!",
                    'status' => "Failed",
                ];
                return $response;
            }
            
        }
        else {
            $response = [
                'success' => false,
                'message' => "Erreur lors de la création du compte!",
            ];
            return $response;
        }
    }
    public function delete_customer($phone_number,$user_id)
    {
        $todayDate = $this->todayDate();
        $activityLog = [
            'fullname'   => Auth::user()->firstname." ".Auth::user()->lastname,
            'user_phone'   => Auth::user()->phone_number,
            'user_id'   => Auth::user()->id,
            'activity'   => "Vient de supprimer le client ".$phone_number,
            'updated_at'   => $todayDate,
        ];

        $save = DB::table('user_activity_logs')->insert($activityLog);

        if($save){
            $destroy = User::destroy($user_id);
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

    public function update_customer($firstname,$lastname,$phone_number,$password, $user_id){
        
        $data = [
            'phone_number' => $phone_number,
            'salt' => $password,
            'password'  => Hash::make($password),
            'firstname' => $firstname,
            'lastname' => $lastname,
        ];
        $todayDate = $this->todayDate();
        $activityLog = [
            'fullname'   => Auth::user()->firstname." ".Auth::user()->lastname,
            'user_phone'   => Auth::user()->phone_number,
            'user_id'   => Auth::user()->id,
            'activity'   => "Vient de modifier le client ".$phone_number,
            'updated_at'   => $todayDate,
        ];
       
        $update = DB::table('users')->where('id', $user_id)->update($data);
        if ($update) {
            $this->activity_log($activityLog);
            $response = [
                'success' => true,
                'message' => "Informations sur le client modifiées avec succès!",
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

    public function update_user($firstname,$lastname,$phone_number,$password, $user_id){
        
        $data = [
            'phone_number' => $phone_number,
            'salt' => $password,
            'password'  => Hash::make($password),
            'firstname' => $firstname,
            'lastname' => $lastname,
        ];
       
        $update = DB::table('users')->where('id', $user_id)->update($data);
        $todayDate = $this->todayDate();
        $activityLog = [
            'fullname'   => Auth::user()->firstname." ".Auth::user()->lastname,
            'user_phone'   => Auth::user()->phone_number,
            'user_id'   => Auth::user()->id,
            'activity'   => "Vient de modifier l'utilisateur ".$phone_number,
            'updated_at'   => $todayDate,
        ];

        if ($update) {
            $this->activity_log($activityLog);
            $response = [
                'success' => true,
                'message' => "Utilisateur modifiée avec succès!",
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

    public function customer_account_details($user_id){
        //dd($user_id);
        $users = DB::table('users')->where('id',$user_id)->first();
        $current = DB::table('accounts')->where('actype','current')->where('user_id',$user_id)->first();
        $saving = DB::table('accounts')->where('actype','saving')->where('user_id',$user_id)->first();

        $wallet = DB::table('wallets')->where('account_id',$current->id)->first();
        

        $amount_cdf_principal = $wallet->balance_cdf;
        $amount_usd_principal = $wallet->balance_usd;
        $acnumber_principal = $current->acnumber;
        

        if ( $saving != null) {
            $wallet_s = DB::table('wallets')->where('account_id',$saving->id)->first();
            $amount_cdf_epargne = $wallet_s->balance_cdf;
            $amount_usd_epargne = $wallet_s->balance_usd;
            $acnumber_epargne = $saving->acnumber;
        }

        if ( $saving == null) {
            $amount_cdf_epargne = "-";
            $amount_usd_epargne = "-";
            $acnumber_epargne = "-";
        }

        $response = [
            'success' => true,
            'resultat' => 1,
            'status' => "Successful",
            'data' => [$users,$amount_cdf_principal,$amount_usd_principal,$acnumber_principal,$amount_cdf_epargne,$amount_usd_epargne,$acnumber_epargne],
        ];
    
        return $response;

    }


    public function user_account_details($user_id){

        //dd($user_id);

        $users = DB::table('users')->where('id',$user_id)->first();
        $phone_number = $users->phone_number;
      
        $bank_user = DB::table('bank_users')->where('phone_number',$phone_number)->first();

        $bank_account = DB::table('bank_accounts')->where('bank_user_id',$bank_user->id)->first();
        
        $balance_cdf = $bank_account->balance_cdf;
        $balance_usd = $bank_account->balance_usd;
        $acnumber = $bank_account->acnumber;
        
        $response = [
            'success' => true,
            'resultat' => 1,
            'status' => "Successful",
            'data' => [$users,$balance_cdf,$balance_usd,$acnumber],
        ];
    
        return $response;

    }

    public function autocomplete($request)
    {
        return User::select("phone_number")
                    ->where('phone_number', 'LIKE', "%{$request->term}%")
                    ->pluck('phone_number');
    }

    public function debit_sender($sender, $amount, $currency){
        $wallet = DB::table('accounts')
        ->join('users','accounts.user_id','users.id')
        ->join('wallets','accounts.id','wallets.account_id')
        ->select('accounts.actype','accounts.description','users.id','wallets.*')
        ->where('users.id',$sender)
        ->where('accounts.actype','current')
        ->where('accounts.description','customer')
        ->where('wallets.type','Emala')
        ->distinct('accounts.acnumber')
        ->first();
        $todayDate = $this->todayDate();
        if ($currency == "CDF") {
            $data = [
                'balance_cdf'   => $wallet->balance_cdf - $amount,
                'updated_at'   => $todayDate,
            ];
        }
        elseif ($currency == "USD") {
            $data = [
                'balance_usd'   => $wallet->balance_usd - $amount,
                'updated_at'   => $todayDate,
            ];
        }

        DB::table('wallets')->where('id',$wallet->id)->update($data);

    }

    public function sender_wallet($sender, $amount, $currency){
        $wallet = DB::table('accounts')
        ->join('users','accounts.user_id','users.id')
        ->join('wallets','accounts.id','wallets.account_id')
        ->select('accounts.actype','accounts.description','wallets.*')
        ->where('users.id',$sender)
        ->where('accounts.actype','current')
        ->distinct('accounts.acnumber')
        ->first();

        if ($currency == "CDF") {
            if ($amount > $wallet->balance_cdf) {
                $response = [
                    'success' => false,
                    'message' => "Le solde du client est insuffisant!",
                    'status' => "Failed",
                ];
                return $response;
            }
            else {
                $response = [
                    'success' => true,
                    'status' => "Successful",
                    'message' => "Balance verifiée avec succès!",
                ];
                return $response;
            }
        }

        if ($currency == "USD") {
            if ($amount > $wallet->balance_usd) {
                $response = [
                    'success' => false,
                    'message' => "Le solde du client est insuffisant!",
                    'status' => "Failed",
                ];
                return $response;
            }
            else {
                $response = [
                    'success' => true,
                    'status' => "Successful",
                    'message' => "Balance verifiée avec succès!",
                ];
                return $response;
            }
        }

        
    }

    public function transfert_interne($sender, $acnumber, $agent_id, $currency, $amount, $descritption, $from, $fees){
        $total_amount = $amount + $fees;
        /* Start Vérification de la balance de l'expediteur */
        $balance = $this->sender_wallet($sender, $total_amount, $currency);
        
        /* End Vérification de la balance de l'expediteur */
        if ($balance['success']==true) {
            /* Start Débiter la balance de l'expediteur */
            $this->debit_sender($sender, $total_amount, $currency);
            /* End Débiter la balance de l'expediteur */
            $wallet = DB::table('wallets')
            ->join('accounts','wallets.account_id','accounts.id')
            ->select('wallets.*','accounts.acnumber')
            ->where('accounts.acnumber',$acnumber)
            ->where('accounts.actype','current')
            ->where('wallets.type','Emala')->first();
        
            $wallet_id = $wallet->id;
            $credit = $this->credit_receiver($wallet_id, $amount, $currency);

            if ($credit) {
                $response = [
                    'success' => true,
                    'message' => "Transfert effectué avec succès!",
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

        elseif ($balance['success']==false) {
            $response = [
                'success' => false,
                'message' => "Votre solde est insuffisant!",
            ];
            return $response;
        }
        

    }

    public function credit_receiver($wallet_id, $amount, $currency){
        $wallet = DB::table('wallets')->where('id',$wallet_id)->first();

        $todayDate = $this->todayDate();

        $logs_data = [
            'user_id' => Auth::user()->id,
            'wallet_id' => $wallet->id,
            'currency' => $currency,
            'amount' => $amount,
            'created_at'   => $todayDate,
            'updated_at'   => $todayDate,
        ];

        if ($currency == "CDF") {
            $data = [
                'balance_cdf'   => $wallet->balance_cdf + $amount,
                'updated_at'   => $todayDate,
            ];
        }
        elseif ($currency == "USD") {
            $data = [
                'balance_usd'   => $wallet->balance_usd + $amount,
                'updated_at'   => $todayDate,
            ];
            
        }

        $insert_data = DB::table('wallets')->where('id',$wallet_id)->update($data);

        if ($insert_data) {
            $this->wallet_stories($logs_data); 
            $response = [
                'success' => true,
                'message' => "Wallet chargé avec succès!",
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

        


        //$rapport = DB::table('transactions')->insert($data);
    }


    public function debit_wallet($wallet_id, $amount, $currency){
        
        $wallet = DB::table('wallets')->where('id',$wallet_id)->first();
        
        $todayDate = $this->todayDate();
        if ($currency == "CDF") {
            $data = [
                'balance_cdf'   => $wallet->balance_cdf - $amount,
                'updated_at'   => $todayDate,
            ];
            
        }
        elseif ($currency == "USD") {
            $data = [
                'balance_usd'   => $wallet->balance_usd - $amount,
                'updated_at'   => $todayDate,
            ];
            
        }

        $logs_data = [
            'user_id' => Auth::user()->id,
            'wallet_id' => $wallet->id,
            'currency' => $currency,
            'amount' => $amount,
            'created_at'   => $todayDate,
            'updated_at'   => $todayDate,
        ];

        $update_data = DB::table('wallets')->where('id',$wallet_id)->update($data);

        if ($update_data) {
            $this->wallet_stories($logs_data); 
            $response = [
                'success' => true,
                'message' => "Wallet debité avec succès!",
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

    public function retrait_current_account($wallet_id, $amount, $currency){

        $wallet = DB::table('wallets')->where('id',$wallet_id)->first();
        $todayDate = $this->todayDate();
        if ($currency == "CDF") {
            $data = [
                'balance_cdf'   => $wallet->balance_cdf - $amount,
                'updated_at'   => $todayDate,
            ];
            
        }
        elseif ($currency == "USD") {
            $data = [
                'balance_usd'   => $wallet->balance_usd - $amount,
                'updated_at'   => $todayDate,
            ];
            
        }

        $logs_data = [
            'user_id' => Auth::user()->id,
            'wallet_id' => $wallet->id,
            'currency' => $currency,
            'amount' => $amount,
            'created_at'   => $todayDate,
            'updated_at'   => $todayDate,
        ];

        $update_data = DB::table('wallets')->where('id',$wallet_id)->update($data);

        if ($update_data) {
            $this->wallet_stories($logs_data); 
            $response = [
                'success' => true,
                'message' => "Wallet debité avec succès!",
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

    public function retrait_saving_account($wallet_id, $amount, $currency){

        $wallet = DB::table('wallets')->where('id',$wallet_id)->first();
        $todayDate = $this->todayDate();
        if ($currency == "CDF") {
            $data = [
                'balance_cdf'   => $wallet->balance_cdf - $amount,
                'updated_at'   => $todayDate,
            ];
            
        }
        elseif ($currency == "USD") {
            $data = [
                'balance_usd'   => $wallet->balance_usd - $amount,
                'updated_at'   => $todayDate,
            ];
            
        }

        $logs_data = [
            'user_id' => Auth::user()->id,
            'wallet_id' => $wallet->id,
            'currency' => $currency,
            'amount' => $amount,
            'created_at'   => $todayDate,
            'updated_at'   => $todayDate,
        ];

        $update_data = DB::table('wallets')->where('id',$wallet_id)->update($data);

        if ($update_data) {
            $this->wallet_stories($logs_data); 
            $response = [
                'success' => true,
                'message' => "Wallet debité avec succès!",
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

    public function credit_wallet($wallet_id, $amount, $currency){


        $wallet = DB::table('wallets')->where('id',$wallet_id)->first();
        

        $todayDate = $this->todayDate();

        $logs_data = [
            'user_id' => Auth::user()->id,
            'wallet_id' => $wallet->id,
            'currency' => $currency,
            'amount' => $amount,
            'created_at'   => $todayDate,
            'updated_at'   => $todayDate,
        ];

        if ($currency == "CDF") {
            $data = [
                'balance_cdf'   => $wallet->balance_cdf + $amount,
                'updated_at'   => $todayDate,
            ];
        }
        elseif ($currency == "USD") {
            $data = [
                'balance_usd'   => $wallet->balance_usd + $amount,
                'updated_at'   => $todayDate,
            ];
            
        }

        $update_data = DB::table('wallets')->where('id',$wallet_id)->update($data);

        if ($update_data) {
            $this->wallet_stories($logs_data); 
            $response = [
                'success' => true,
                'message' => "Wallet credité avec succès!",
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

    public function wallet_stories($logs_data){
        DB::table('wallet_stories')->insert($logs_data); 
    }

    public function wallet_to_mobile($auth, $exptid, $currency, $amount, $method){

        if ($method == "wallet_to_momo") {
            $sender = $this->user_account_wallet($exptid);
            
            $senderid = $sender->user_id;
            $balance_sender = $this->user_balance($senderid, $amount, $currency);

            if ($balance_sender['success'] == false) {
                $response = [
                    'success' => false,
                    'result' => 1,
                    'status' => "Failed",
                    'message' => $balance_sender['message'],
                ];
              
                return $response;
            }
            else {

                $debit = $this->debit_wallet($sender->id_wallet, $amount, $currency);
    
                if ($debit['success'] == true) {
                    $response = [
                        'success' => true,
                        'status' => "Successful",
                        'message' => "Débité avec succès!",
                    ];
                   
                    return $response;
                }
                else {
                    return $debit;
                }
            }
        }

        elseif ($method == "cashier_to_momo") {
            $sender = $this->cashier_account_wallet($auth);
            $bank_user_id = $sender->bank_user_id;
            $userid = Auth::user()->id;
            $balance_cashier = $this->cashier_balance($userid, $amount, $currency);
            
            if ($balance_cashier['success'] == false) {
                $response = [
                    'success' => false,
                    'result' => 1,
                    'message' => $balance_cashier['message'],
                ];
                return $response;
            }
            else {

                $debit = $this->debit_bank_account($bank_user_id,$amount,$currency);
                
                if ($debit) {

                    $this->credit_tirroir($amount, $currency);
                    $response = [
                        'success' => true,
                        'message' => "Paiement effectué avec succès!",
                        'status' => "Successful",
                    ];
                    return $response;
                }
                else {
                    $response = [
                        'success' => false,
                        'message' => "Désolé! Une erreur est survenue pendant l'exécution de la requêtte!",
                        'status' => "Failed",
                    ];
                    return $response;
                }
            }
        }
        
    }

    public function wallet_to_method($exptid,$destid,$method,$currency,$amount,$fees){

        if ($method == "wallet_to_wallet") {
            $transfert = $this->wallet_to_wallet($exptid,$destid,$currency,$amount);
            //dd($transfert);
            if ($transfert['success'] == true) {
                $response = [
                    'success' => true,
                    'message' => "Transfert effectué avec succès!",
                    'status' => "Successful",
                ];
                return $response;
            }

            else {
                $response = [
                    'success' => false,
                    'message' => "Impossible d'effectuer ce transfert!",
                    'status' => "Failed",
                ];
                return $transfert;
            }
            
        }
        elseif ($method == "cashier_to_wallet") {
            $transfert = $this->cashier_to_wallet($destid,$currency,$amount,$fees);
      
            if ($transfert['success'] == true) {
                $response = [
                    'success' => true,
                    'message' => "Transfert effectué avec succès!",
                    'status' => "Successful",
                ];
                return $response;
            }

            else {
                $response = [
                    'success' => false,
                    'message' => $transfert['message'],
                    'status' => "Failed",
                ];
                return $response;
            }
        }
        
    }

    public function wallet_to_wallet($exptid,$destid,$currency,$amount){
        $sender = $this->user_account_wallet($exptid);
        $senderid = $sender->user_id;
       
        $balance_sender = $this->user_balance($senderid, $amount, $currency);
        
        if ($balance_sender['success'] == true) {
            $debit = $this->debit_wallet($sender->id_wallet, $amount, $currency);
            if ($debit['success'] == true) {
                $receiver = $this->user_account_wallet($destid);
                $credit = $this->credit_wallet($receiver->id_wallet, $amount, $currency);
            
                if ($credit['success'] == true) {
                    $response = [
                        'success' => true,
                        'message' => "Transfert effectué avec succès!",
                        'status' => "Successful",
                    ];
                    return $response;
                }

                else {
                    $response = [
                        'success' => false,
                        'message' => "Impossible d'effectuer ce transfert!",
                        'status' => "Failed",
                    ];
                    return $response;
                }
            }
        }

        else {
           return $balance_sender;
        }
    }

    public function verify_saving_account($compte,$wallet_id){

            $saving = DB::table('wallets')->where('id',$wallet_id)->first();
            
            $account = DB::table('accounts')->where('actype',$compte)->where('id',$saving->account_id)->count();
          
            if ($account == 0) {
                $response = [
                    'success' => false,
                    'message' => "Le client n'a pas de compte epargne",
                    'status' => "Failed",
                ];
                return $response;
            }  
            elseif ($account == 1) {
                $response = [
                    'success' => true,
                    'message' => "Compte epargne existe!",
                    'status' => "Successful",
                ];
                return $response;
            } 
            
     
    }
    public function topup_tirroir($bank_user_id, $amount, $currency){

        $tirroir = DB::table('tirroir_accounts')->where('bank_user_id','=',$bank_user_id)->first();

        //dd($tirroir);
        if ($currency == "CDF") {
            $data = [
                'balance_cdf' => $tirroir->balance_cdf + $amount,
            ];
        }
        elseif ($currency == "USD") {
            $data = [
                'balance_usd' => $tirroir->balance_usd + $amount,
            ];
        }

        $credit = DB::table('tirroir_accounts')->where('bank_user_id','=',$bank_user_id)->update($data);
        if ($credit) {
            $response = [
                'success' => true,
            ];
            return $response;
        }
        else {
            $response = [
                'success' => false,
            ];
            return $response;
        }

    }

    public function credit_tirroir($amount, $currency){

        $userid = Auth::user()->id;
        $tirroir_account = $this->cashier_account_tirroir($userid);
        $bank_user_id = $tirroir_account->bank_user_id;

        $response_balance = $this->tirroir_balance($userid);

        if ($currency == "CDF") {
            $data = [
                'balance_cdf' => $response_balance['balance_cdf'] + $amount,
            ];
        }
        elseif ($currency == "USD") {
            $data = [
                'balance_usd' => $response_balance['balance_usd'] + $amount,
            ];
        }

        $credit = DB::table('tirroir_accounts')->where('bank_user_id','=',$bank_user_id)->update($data);
        if ($credit) {
            $response = [
                'success' => true,
            ];
            return $response;
        }
        else {
            $response = [
                'success' => false,
            ];
            return $response;
        }

    }

    public function debit_tirroir($amount, $currency){

        $userid = Auth::user()->id;
        $tirroir_account = $this->cashier_account_tirroir($userid);
        $bank_user_id = $tirroir_account->bank_user_id;

        $response_balance = $this->tirroir_balance($userid);

        //dd($response_balance);

        if ($currency == "CDF") {
            if ($amount > $response_balance['balance_cdf']) {
                $response = [
                    'success' => false,
                    'message' => "Vous n'avez pas assez de fonds dans votre tirroir pour effectuer cette requêtte!",
                    'status' => "Failed",
                ];
                return $response;
            }
            else {
                $data = [
                    'balance_cdf' => $response_balance['balance_cdf'] - $amount,
                ];
            }
            
        }
        elseif ($currency == "USD") {
            if ($amount > $response_balance['balance_usd']) {
                $response = [
                    'success' => false,
                    'message' => "Vous n'avez pas assez de fonds dans votre tirroir pour effectuer cette requêtte!",
                    'status' => "Failed",
                ];
                return $response;
            }
            else {
                $data = [
                    'balance_usd' => $response_balance['balance_usd'] - $amount,
                ];
            }
        }

        DB::table('tirroir_accounts')->where('bank_user_id','=',$bank_user_id)->update($data);

    }

    public function debit_bank_account($bank_user_id,$amount,$currency){
        $todayDate = $this->todayDate();
        $balance = $this->bank_account_balance($bank_user_id);
        //dd($balance);
        //$debit_main_wallet = $this->debit_main_wallet($amount, $currency);
        if ($currency == "CDF") {
            if ($amount > $balance['balance_cdf']) {
                $response = [
                    'success' => false,
                    'message' => "Le solde virtuel de votre compte caisse est insuffisant!",
                    'status' => "Failed",
                ];
                return $response;
            }
            else {
                $dataStored = [
                    'balance_cdf' => $balance['balance_cdf'] - $amount,
                    'updated_at'   => $todayDate,
                ];
            }
            
        }
        elseif ($currency == "USD") {
            if ($amount > $balance['balance_usd']) {
                $response = [
                    'success' => false,
                    'message' => "Le solde virtuel de votre compte caisse est insuffisante!",
                    'status' => "Failed",
                ];
                return $response;
            }
            else {
                $dataStored = [
                    'balance_usd' => $balance['balance_usd'] - $amount,
                    'updated_at'   => $todayDate,
                ];
            }
        }
        
        $update = DB::table('bank_accounts')->where('bank_user_id', $bank_user_id)->update($dataStored);  
        if ($update) {
            $response = [
                'success' => true,
            ];
            return $response;
        }
        else {
            $response = [
                'success' => false,
            ];
            return $response;
        }
    }

    public function depot_agence_to_wallet($benefid,$compte,$currency,$amount, $fees){

        
        $authid = Auth::user()->id;

        if ($compte == "saving") {
            $receiver = $this->user_account_saving($benefid);
            
            $response_saving = $this->verify_saving_account($compte,$receiver->id_wallet);
           
            if ($response_saving['success'] == false) {
                $response = [
                    'success' => false,
                    'message' => "Le client n'a pas de compte epargne",
                    'status' => "Failed",
                ];
                return $response;
            } 

            else {
                $credit_tirroir = $this->credit_tirroir($amount, $currency);
                if ($credit_tirroir['success'] == true) {
                        $credit = $this->credit_wallet($receiver->id_wallet, $amount, $currency);
                        if ($credit['success'] == true) {
                            $response = [
                                'success' => true,
                                'message' => "Dépôt effectué avec succès!",
                                'status' => "Successful",
                            ];
                            return $response;
                        }

                        else {
                            $response = [
                                'success' => false,
                                'message' => "Une erreur est survenue lors du transfert de fonds!",
                                'status' => "Failed",
                            ];
                            return $response;
                        }
                }
            }
        }

        else {
            $receiver = $this->user_account_wallet($benefid);
            $credit_tirroir = $this->credit_tirroir($amount, $currency);
            if ($credit_tirroir['success'] == true) {
                /* Checking balance virtuelle*/
                // $balance = $this->compte_balance($authid);
                // if ($currency == "CDF") {
                //     if ($amount > $balance['balance_cdf']) {
                //         $response = [
                //             'success' => false,
                //             'message' => "Le solde de la caisse virtuelle est insuffisante!",
                //             'status' => "Failed",
                //         ];
                //         return $response;
                //     }

                //     else {
                //         $data = [
                //             'balance_cdf' => $balance['balance_cdf'] - $amount,
                //         ];
                //     }
                // }
                // elseif ($currency == "USD") {
                //     if ($amount > $balance['balance_usd']) {
                //         $response = [
                //             'success' => false,
                //             'message' => "Le solde de la caisse virtuelle est insuffisante!",
                //             'status' => "Failed",
                //         ];
                //         return $response;
                //     }

                //     else {
                //         $data = [
                //             'balance_usd' => $balance['balance_usd'] - $amount,
                //         ];
                //     }
                // }
                // # Récupération des informations du bank_account
                // $cashier = $this->cashier_account_wallet($authid);
                
                // $bank_user_id =   $cashier->bank_user_id;
                // # Débiter le bank_account
                // $update = DB::table('bank_accounts')->where('bank_user_id', $bank_user_id)->update($data);  
                // if ($update) {
                    $credit = $this->credit_wallet($receiver->id_wallet, $amount, $currency);
                    //dd($credit);
                    if ($credit['success'] == true) {
                        $response = [
                            'success' => true,
                            'message' => "Dépôt effectué avec succès!",
                            'status' => "Successful",
                        ];
                        return $response;
                    }

                    else {
                        $response = [
                            'success' => false,
                            'message' => "Une erreur est survenue lors du transfert de fonds!",
                            'status' => "Failed",
                        ];
                        return $response;
                    }
                    
                // }
                // else {
                    // $response = [
                    //     'success' => false,
                    //     'message' => "Une erreur est survenue lors du transfert de fonds!",
                    //     'status' => "Failed",
                    // ];
                    // return $response;
                // }
                
            }
        }

        

    }

    public function cashier_to_wallet($destid,$currency,$amount, $fees){

        $exptid = Auth::user()->id;
        $sender = $this->cashier_account_wallet($exptid);
        
        $total = $fees + $amount;
        $bank_user_id = $sender->bank_user_id;
        
        //$balance_sender = $this->cashier_account_wallet($bank_user_id);
        $credit_tirroir = $this->credit_tirroir($total, $currency);

        if ($credit_tirroir["success"] == true) {
            $debit = $this->debit_bank_account($bank_user_id,$amount,$currency);
            if ($debit['success'] == true) {
                
                $receiver = $this->user_account_wallet($destid);
                $credit = $this->credit_wallet($receiver->id_wallet, $amount, $currency);
            
                if ($credit['success'] == true) {
                    $response = [
                        'success' => true,
                        'message' => "Transfert effectué avec succès!",
                        'status' => "Successful",
                    ];
                    return $response;
                }

                else {
                    $response = [
                        'success' => false,
                        'message' => "Impossible d'effectuer ce transfert!",
                        'status' => "Successful",
                    ];
                    return $response;
                }
            }
            else {
                return $debit;
            }
        }

        else {
            return $credit_tirroir;
        }
        
    }

    public function user_balance($userid, $amount, $currency){
        $wallet = DB::table('accounts')
        ->join('users','accounts.user_id','users.id')
        ->join('wallets','accounts.id','wallets.account_id')
        ->select('accounts.actype','wallets.*')
        ->where('users.id',$userid)
        ->where('accounts.actype','current')
        ->distinct('accounts.acnumber')
        ->first();

        if ($currency == "CDF") {
            if ($amount > $wallet->balance_cdf) {
                $response = [
                    'success' => false,
                    'message' => "Le solde du client est insuffisant!",
                    'status' => "Failed",
                ];
                return $response;
            }
            else {
                $response = [
                    'success' => true,
                    'message' => "Balance verifiée avec succès!",
                    'status' => "Successful",
                ];
                return $response;
            }
        }

        if ($currency == "USD") {
            if ($amount > $wallet->balance_usd) {
                $response = [
                    'success' => false,
                    'message' => "Solde insuffisant!",
                    'status' => "Failed",
                ];
                return $response;
            }
            else {
                $response = [
                    'success' => true,
                    'status' => "Successful",
                    'message' => "Balance verifiée avec succès!",
                ];
                return $response;
            }
        }

        
    }

    public function user_balance_saving($userid, $amount, $currency){
        $wallet = DB::table('accounts')
        ->join('users','accounts.user_id','users.id')
        ->join('wallets','accounts.id','wallets.account_id')
        ->select('accounts.actype','wallets.*')
        ->where('users.id',$userid)
        ->where('accounts.actype','saving')
        ->distinct('accounts.acnumber')
        ->first();

        if ($currency == "CDF") {
            if ($amount > $wallet->balance_cdf) {
                $response = [
                    'success' => false,
                    'message' => "Le solde du client est insuffisant!",
                    'status' => "Failed",
                ];
                return $response;
            }
            else {
                $response = [
                    'success' => true,
                    'message' => "Balance verifiée avec succès!",
                    'status' => "Successful",
                ];
                return $response;
            }
        }

        if ($currency == "USD") {
            if ($amount > $wallet->balance_usd) {
                $response = [
                    'success' => false,
                    'message' => "Solde insuffisant!",
                    'status' => "Failed",
                ];
                return $response;
            }
            else {
                $response = [
                    'success' => true,
                    'status' => "Successful",
                    'message' => "Balance verifiée avec succès!",
                ];
                return $response;
            }
        }

        
    }

    public function cashier_balance($userid, $amount, $currency){

        $wallet = DB::table('bank_accounts')
        ->join('bank_users','bank_accounts.bank_user_id','bank_users.id')
        ->join('users','bank_users.user_id','users.id')
        ->join('branches','users.id','branches.user_id')
        ->select('bank_accounts.*','users.id','users.avatar','users.firstname','users.lastname','users.middlename','users.phone_number','branches.btownship')
        ->where('users.id','=',$userid)->first();

        $tirroir = DB::table('tirroir_accounts')
                ->join('bank_users','tirroir_accounts.bank_user_id','bank_users.id')
                ->join('users','bank_users.user_id','users.id')
                ->join('branches','users.id','branches.user_id')
                ->select('tirroir_accounts.*','users.id','users.avatar','users.firstname','users.lastname','users.middlename','users.phone_number','branches.btownship')
                ->where('users.id','=',$userid)->first();

        if ($currency == "CDF") {
            if ($amount > $wallet->balance_cdf && $amount > $tirroir->balance_cdf) {
                $response = [
                    'success' => false,
                    'message' => "La balance de la caisse est insuffisante!",
                    'status' => "Failed",
                    'data' => $wallet,
                ];
                return $response;
            }
            else {
                $response = [
                    'success' => true,
                    'message' => "Balance verifiée avec succès!",
                    'status' => "Successful",
                    'data' => $wallet,
                ];
                return $response;
            }
        }

        if ($currency == "USD") {
            if ($amount > $wallet->balance_usd && $amount > $tirroir->balance_usd) {
                $response = [
                    'success' => false,
                    'message' => "La balance de la caisse est insuffisante!",
                    'status' => "Failed",
                    'data' => $wallet,
                ];
                return $response;
            }
            else {
                $response = [
                    'success' => true,
                    'status' => "Successful",
                    'message' => "Balance verifiée avec succès!",
                    'data' => $wallet,
                ];
                return $response;
            }
        }

    }

    public function tirroir_balance($userid){

        $tirroir = DB::table('tirroir_accounts')
                ->join('bank_users','tirroir_accounts.bank_user_id','bank_users.id')
                ->join('users','bank_users.user_id','users.id')
                ->join('branches','users.id','branches.user_id')
                ->select('tirroir_accounts.*','users.id','users.avatar','users.firstname','users.lastname','users.middlename','users.phone_number','branches.btownship')
                ->where('users.id','=',$userid)->first();

        $balance_cdf = $tirroir->balance_cdf;
        $balance_usd = $tirroir->balance_usd;

        $response = [
            'balance_cdf' => $balance_cdf,
            'balance_usd' => $balance_usd,
        ];

        return $response;

    }

    public function compte_balance($userid){

        $bank = DB::table('bank_accounts')
                ->join('bank_users','bank_accounts.bank_user_id','bank_users.id')
                ->join('users','bank_users.user_id','users.id')
                ->join('branches','users.id','branches.user_id')
                ->select('bank_accounts.*','users.id','users.avatar','users.firstname','users.lastname','users.middlename','users.phone_number','branches.btownship')
                ->where('users.id','=',$userid)->first();

        $balance_cdf = $bank->balance_cdf;
        $balance_usd = $bank->balance_usd;

        $response = [
            'balance_cdf' => $balance_cdf,
            'balance_usd' => $balance_usd,
        ];

        return $response;

    }

    public function user_account_wallet($userid){
        $resultat = DB::table('accounts')
        ->join('branches','accounts.branche_id','branches.id')
        ->join('users','accounts.user_id','users.id')
        ->join('wallets','accounts.id','wallets.account_id')
        ->select('accounts.*','users.id','users.avatar','users.firstname','users.lastname','users.middlename','users.phone_number','branches.btownship','wallets.balance_cdf','wallets.balance_usd','wallet_id','account_id','wallets.id AS id_wallet')
        ->where('accounts.actype','current')
        ->where('users.id','=', $userid)
        ->distinct()
        ->first();
        return $resultat;
    }

    public function user_account_saving($userid){
        $resultat = DB::table('accounts')
        ->join('branches','accounts.branche_id','branches.id')
        ->join('users','accounts.user_id','users.id')
        ->join('wallets','accounts.id','wallets.account_id')
        ->select('accounts.*','users.id','users.avatar','users.firstname','users.lastname','users.middlename','users.phone_number','branches.btownship','wallets.balance_cdf','wallets.balance_usd','wallet_id','account_id','wallets.id AS id_wallet')
        ->where('accounts.actype','saving')
        ->where('users.id','=', $userid)
        ->distinct()
        ->first();
        return $resultat;
    }

    public function depot_user_account_wallet($userid, $compte){

       
        $resultat = DB::table('accounts')
        ->join('branches','accounts.branche_id','branches.id')
        ->join('users','accounts.user_id','users.id')
        ->join('wallets','accounts.id','wallets.account_id')
        ->select('accounts.*','users.id','users.avatar','users.firstname','users.lastname','users.middlename','users.phone_number','branches.btownship','wallets.balance_cdf','wallets.balance_usd','wallet_id','account_id','wallets.id AS id_wallet')
        ->where('accounts.actype',$compte)
        ->where('users.id','=', $userid)
        ->distinct()
        ->first();

        //dd($resultat);
    
        return $resultat;
    }

    public function cashier_account_wallet($userid){

        $resultat = DB::table('bank_accounts')
        ->join('bank_users','bank_accounts.bank_user_id','bank_users.id')
        ->join('users','bank_users.user_id','users.id')
        ->join('branches','users.id','branches.user_id')
        ->select('bank_accounts.*','users.id','users.avatar','users.firstname','users.lastname','users.middlename','users.phone_number','branches.btownship')
        ->where('users.id','=',$userid)->first();
        return $resultat;
    }

    public function cashier_account_tirroir($userid){

        $resultat = DB::table('tirroir_accounts')
        ->join('bank_users','tirroir_accounts.bank_user_id','bank_users.id')
        ->join('users','bank_users.user_id','users.id')
        ->join('branches','users.id','branches.user_id')
        ->select('tirroir_accounts.*','users.id','users.avatar','users.firstname','users.lastname','users.middlename','users.phone_number','branches.btownship')
        ->where('users.id','=',$userid)->first();
        return $resultat;
    }

    public function mobile_debit_wallet($auth, $wallet_id, $amount, $currency){
        
        $wallet = DB::table('wallets')->where('id',$wallet_id)->first();
        
        $todayDate = $this->todayDate();
        if ($currency == "CDF") {
            $data = [
                'balance_cdf'   => $wallet->balance_cdf - $amount,
                'updated_at'   => $todayDate,
            ];
            
        }
        elseif ($currency == "USD") {
            $data = [
                'balance_usd'   => $wallet->balance_usd - $amount,
                'updated_at'   => $todayDate,
            ];
            
        }

        $logs_data = [
            'user_id' => $auth,
            'wallet_id' => $wallet->id,
            'currency' => $currency,
            'amount' => $amount,
            'created_at'   => $todayDate,
            'updated_at'   => $todayDate,
        ];

        $insert_data = DB::table('wallets')->where('id',$wallet_id)->update($data);

        if ($insert_data) {
            $this->wallet_stories($logs_data); 
            $response = [
                'success' => true,
                'message' => "Wallet chargé avec succès!",
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

    public function verify_number_operator($number = '')
    {
        $customer_number = $number;
        $len_number = strlen($number);
    
    
        if ($len_number == 9) {
            if (substr($customer_number, 0, 2) == '81' || substr($customer_number, 0, 2) == '82') {
                return 'mpesa';
            }
    
            if (substr($customer_number, 0, 2) == '99' || substr($customer_number, 0, 2) == '97') {
                return 'airtel';
            }
    
            if (substr($customer_number, 0, 2) == '85' || substr($customer_number, 0, 2) == '84' || substr($customer_number, 0, 2) == '89' || substr($customer_number, 0, 2) == '80') {
                return 'orange';
            }
        }
    
    
        if ($len_number == 10) {
            if (substr($customer_number, 0, 1) == '0') {
                if (substr($customer_number, 1, 2) == '81' || substr($customer_number, 1, 2) == '82') {
                    return 'mpesa';
                }
            }
    
            if (substr($customer_number, 0, 1) == '0') {
                if (substr($customer_number, 1, 2) == '99' || substr($customer_number, 1, 2) == '97') {
                    return 'airtel';
                }
            }
    
            if (substr($customer_number, 0, 1) == '0') {
                if (substr($customer_number, 1, 2) == '85' || substr($customer_number, 1, 2) == '84' || substr($customer_number, 1, 2) == '89' || substr($customer_number, 1, 2) == '80') {
                    return 'orange';
                }
            }
    
        }
    
        if ($len_number == 12) {
            if (substr($customer_number, 0, 3) == '243') {
                if (substr($customer_number, 3, 2) == '81' || substr($customer_number, 3, 2) == '82') {
                    return 'mpesa';
                }
    
                if (substr($customer_number, 3, 2) == '99' || substr($customer_number, 3, 2) == '97') {
                    return 'airtel';
                }
    
                if (substr($customer_number, 3, 2) == '85' || substr($customer_number, 3, 2) == '84' || substr($customer_number, 3, 2) == '89' && substr($customer_number, 3, 2) == '80') {
                    return 'orange';
                }
            }
    
        }
    
        if ($len_number < 9 || $len_number > 12) {
            return false;
        }
    
    }

    public function trandetails($medium_of_transaction,$action, $sender, $receiver, $branche_id,$reference,$amount,$currency_id,$status,$status_description,$type){
        
       
        
        $todayDate = $this->todayDate();

        $snumber = $this->user_by_phone($sender);

        $phone_sender = $snumber->phone_number;
        
        $rnumber = $this->user_by_phone($receiver);
        
        $phone_receiver = $rnumber->phone_number;
        
        $agendid = Auth::user()->id;

        $data = [
            'id_agent' =>$agendid,
            'action' =>$action,
            'sender_phone' =>$phone_sender,
            'receiver_phone' =>$phone_receiver,
            'branche_id' =>$branche_id,
            'medium_of_transaction' =>$medium_of_transaction,
            'transaction_id' =>$reference,
            'amount' =>$amount,
            'currency' =>$currency_id,
            'status_description' => $status_description,
            'type' => $type,
            'status' => $status,
            'created_at'   => $todayDate,
            'updated_at'   => $todayDate,
        ];
        //dd($data);
        DB::table('transactions')->insert($data);  
    }

    public function user_by_account($acnumber){
    
        $user = DB::table('accounts')
        ->join('users','accounts.user_id','users.id')
        ->select('users.phone_number','accounts.acnumber')
        ->where('accounts.acnumber','=',$acnumber)
        ->first();
     
        $telephone = $user->phone_number;
        return $telephone;
    }

    public function user_by_phone($phone_number){
        $user = DB::table('users')->where('phone_number','=',$phone_number)->first();
        return $user;
    }

    public function retrait($customer_number,$amount, $currency, $compte){
        $customer = $this->user_by_phone($customer_number); 
        $customer_id = $customer->id;
        /** Vérification de la limite de retrait par jour **/
        $limit = $this->retrait_limit($customer_id); 
        
        /** Si la limite n'est pas encore atteint, on procède au retrait **/
        if ($limit['success'] == true) {
        /** Vérification de la balance du client **/
            $balance_client = $this->user_balance($customer_id, $amount, $currency);
            
            if ($balance_client['success'] == true) {
                /** Récupération de l'ID du Wallet du client **/
             
                if ($compte == "current") {

                    $compte_client = $this->user_account_wallet($customer_id);
                    $id_wallet = $compte_client->id_wallet;
                    /** Débiter compte du client **/
                    $debiter_wallet_client = $this->retrait_current_account($id_wallet, $amount, $currency);

                    if ($debiter_wallet_client['success'] == true) {

                        $this->debit_tirroir($amount, $currency);
                            $response = [
                                'success' => true,
                                'message' => "Retrait effectué avec succès!",
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

                elseif ($compte == "saving") {
                    $compte_client = $this->user_account_saving($customer_id);
                   
                    if ($compte_client == null) {
                        $response = [
                            'success' => false,
                            'status' => "Failed",
                            'message' => "Désolé, le client ne possède pas de compte epargne!",
                        ];
                        return $response;
                    }
                    $id_wallet = $compte_client->id_wallet;
                    $debiter_wallet_client = $this->retrait_saving_account($id_wallet, $amount, $currency);
                    if ($debiter_wallet_client['success'] == true) {

                        $this->debit_tirroir($amount, $currency);
                            $response = [
                                'success' => true,
                                'message' => "Retrait effectué avec succès!",
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

            else {
                return $balance_client;
            }
            
        }
        else {
            return $limit;
        }

    }

    public function retrait_limit($user_id){
        $count = DB::table('retraits')->where('user_id','=',$user_id)->count();
        if ($count > 5) {
            $response = [
                'success' => false,
                'message' => "Vous avez atteint la limite maximale des retraits par jour!",
                'status' => "Failed",
            ];
            return $response;
        }
        else {
            $response = [
                'success' => true,
            ];
            return $response;
        }

    }

}