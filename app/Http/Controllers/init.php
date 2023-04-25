<?php

namespace App\Http\Controllers;

use App\Models\Account;
use App\Models\Admin;
use App\Models\Agence;
use App\Models\Agence_account;
use App\Models\User;
use App\Models\Wallet;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Session;

class init extends Controller
{
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

    public function create_superadmin_as_admin($user_id, $level){
        $dt       = Carbon::now();
        $todayDate = $dt->toDayDateTimeString();

        $check_user = DB::table('users')->where('id',$user_id)->first();

        if ($check_user != null) {
            $user = Admin::create([
                'user_id'      => $check_user->id,
                'username'      => $check_user->username,
                'password'      => $check_user->password,
                'admin_level'      => $level,
                'created_at'   => $todayDate,
                'updated_at'   => $todayDate,
            ]);

            if ($user) {
                $response = [
                    'success' => true,
                    'message' => "Admin a été créé avec succès!",
                ];
                return $response;
                        
            }
            else {
                $response = [
                    'success' => false,
                    'message' => "Erreur!",
                ];
                return $response;
            }                   

        }
    }

    public function create_client($firstname,$lastname,$telephone,$adresse,$ville,$role){
        $dt       = Carbon::now();
        $todayDate = $dt->toDayDateTimeString();

        $username = $this->username($firstname,$lastname);
        $verify_number = new VerifyNumberController;
        $phone_number = $verify_number->verify_number($telephone);

        $default = new GenerateIdController;
        $password = $default->defaultPIN();

        $check_user = DB::table('users')->where('phone_number',$phone_number)->first();

        if ($check_user == null) {
            $user = User::create([
                'username'      => $username,
                'firstname'      => $firstname,
                'lastname'      => $lastname,
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
                $select_id = DB::table('users')->where('phone_number',$phone_number)->first();
                $user_id = $select_id->id;

                $account = DB::table('accounts')->where('user_id', $user_id)->first();

                if ($account == null) {
                    $this->create_account($user_id);
                }
                else {
                    $response = [
                        'success' => false,
                        'message' => "Une erreur est survenue lors de la création du compte courant!",
                    ];
                    return $response;
                }
                $response = [
                    'success' => true,
                    'message' => "Compte client créé avec succès!",
                ];
                return $response;
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

    public function admin_id($user_id){
        //dd($user_id);
        $admin = DB::table('admins')->where('user_id', $user_id )->first();
        $admin_id = $admin->id;
        return $admin_id;
    }

    public function agence_id($currency){

        $admin_id = $this->admin_id(Auth::user()->id);
        $agence = DB::table('agences')->where('agence_currency', $currency)->where('agence_user', $admin_id)->first();
        $agence_id = $agence->id;
        //dd($agence_id);
        return $agence_id;
    }

    public function agenceid(){

        $admin_id = $this->admin_id(Auth::user()->id);
        $agence = DB::table('agences')->where('agence_user', $admin_id)->first();
        $agence_id = $agence->id;
        return $agence_id;
    }

    public function position($position){
        $level = DB::table('admin_levels')->where('position', $position)->first();
        $admin_position = $level->position ;
        return $admin_position;
    }

    public function balance_agence($admin_id, $currency, $balance_type){
        $agence = DB::table('agences')->select($balance_type)->where('currency', $currency)->where('agence_user', $admin_id)->first();
        $balance = $agence->balance_type ;
        return $balance;
    }

    public function admin_level($user_id){
        $admin = DB::table('admins')->where('user_id', $user_id)->first();
        $position = $admin->admin_level;
        $level = DB::table('admin_levels')->where('position', $position)->first();
        $admin_description = $level->description ;
        return $admin_description;
    }

    public function create_wallet_parent($user, $wallet_status, $wallet_type){

        $admin = DB::table('admins')->where('user_id', $user )->first();
        $admin_id = $admin->id;

        $dt       = Carbon::now();
        $todayDate = $dt->toDayDateTimeString();

        $default = new GenerateIdController;
        $wallet_id = $default->wallet_id();


        $wallet_1 = Wallet::create([
            'wallet_id'      => $wallet_id,
            'admin_id'      => $admin_id,
            'balance_cash' =>0,
            'balance_virtuel' =>0,
            'wallet_currency' => 'CDF',
            'wallet_status'  => $wallet_status,
            'wallet_type'  => $wallet_type,
            'created_at'   => $todayDate,
            'updated_at'   => $todayDate,
        ]);

        $wallet_2 = Wallet::create([
            'wallet_id'      => $wallet_id,
            'admin_id'      => $admin_id,
            'balance_cash' =>0,
            'balance_virtuel' =>0,
            'wallet_currency' => 'USD',
            'wallet_status'  => $wallet_status,
            'wallet_type'  => $wallet_type,
            'created_at'   => $todayDate,
            'updated_at'   => $todayDate,
        ]);


        if ($wallet_1 && $wallet_2) {
            $response = [
                'success' => true,
                'message' => "Wallet a été créé avec succès!",
            ];
            return $response;
        }
        else {
            $response = [
                'success' => false,
                'message' => "Une erreur est suvenue lors de la création du wallet!",
            ];
            return $response;
        }


    }
    

    public function create_agence_parent($wallet_id,$agence_user,$agence_phone, $agence_email, $agence_commune, $agence_ville, $agence_status, $agence_type){

        $dt       = Carbon::now();
        $todayDate = $dt->toDayDateTimeString();

        $default = new GenerateIdController;
        $agence_code = $default->code_agence();

        $agence_1 = Agence::create([
            'wallet_id'      => $wallet_id,
            'agence_user'      => $agence_user,
            'agence_code'      => $agence_code,
            'agence_phone'      => $agence_phone,
            'agence_email'     => $agence_email,
            'agence_commune' => $agence_commune,
            'agence_ville' => $agence_ville,
            'agence_status' => $agence_status,
            'balance_cash' =>0,
            'balance_virtuel' =>0,
            'mobile_cash' =>0,
            'mobile_virtuel' =>0,
            'agence_currency' => 'CDF',
            'agence_status'  => 'Actif',
            'agence_type'  => $agence_type,
            'created_at'   => $todayDate,
            'updated_at'   => $todayDate,
        ]);

        $agence_2 = Agence::create([
            'wallet_id'      => $wallet_id,
            'agence_user'      => $agence_user,
            'agence_code'      => $agence_code,
            'agence_phone'      => $agence_phone,
            'agence_email'     => $agence_email,
            'agence_commune' => $agence_commune,
            'agence_ville' => $agence_ville,
            'agence_status' => $agence_status,
            'balance_cash' =>0,
            'balance_virtuel' =>0,
            'mobile_cash' =>0,
            'mobile_virtuel' =>0,
            'agence_currency' => 'USD',
            'agence_status'  => 'Actif',
            'agence_type'  => $agence_type,
            'created_at'   => $todayDate,
            'updated_at'   => $todayDate,
        ]);


        if ($agence_1 && $agence_2) {
            $response = [
                'success' => true,
                'message' => "Agence créée avec succès!",
            ];
            return $response;
        }
        else {
            $response = [
                'success' => false,
                'message' => "Une erreur est suvenue lors de la création de l'agence!",
            ];
            return $response;
        }


    }

    public function create_agence_inner($wallet_id,$agence_user,$agence_phone, $agence_email, $agence_commune, $agence_ville, $agence_status, $agence_type){

        $dt       = Carbon::now();
        $todayDate = $dt->toDayDateTimeString();

        $default = new GenerateIdController;
        $agence_code = $default->code_agence();

        $agence_1 = Agence::create([
            'wallet_id'      => $wallet_id,
            'agence_user'      => $agence_user,
            'agence_code'      => $agence_code,
            'agence_phone'      => $agence_phone,
            'agence_email'     => $agence_email,
            'agence_commune' => $agence_commune,
            'agence_ville' => $agence_ville,
            'agence_status' => $agence_status,
            'balance_cash' =>0,
            'balance_virtuel' =>0,
            'mobile_cash' =>0,
            'mobile_virtuel' =>0,
            'agence_currency' => 'CDF',
            'agence_status'  => 'Actif',
            'agence_type'  => $agence_type,
            'created_at'   => $todayDate,
            'updated_at'   => $todayDate,
        ]);

        $agence_2 = Agence::create([
            'wallet_id'      => $wallet_id,
            'agence_user'      => $agence_user,
            'agence_code'      => $agence_code,
            'agence_phone'      => $agence_phone,
            'agence_email'     => $agence_email,
            'agence_commune' => $agence_commune,
            'agence_ville' => $agence_ville,
            'agence_status' => $agence_status,
            'balance_cash' =>0,
            'balance_virtuel' =>0,
            'mobile_cash' =>0,
            'mobile_virtuel' =>0,
            'agence_currency' => 'USD',
            'agence_status'  => 'Actif',
            'agence_type'  => $agence_type,
            'created_at'   => $todayDate,
            'updated_at'   => $todayDate,
        ]);


        if ($agence_1 && $agence_2) {
            $response = [
                'success' => true,
                'message' => "Agence créée avec succès!",
            ];
            return $response;
        }
        else {
            $response = [
                'success' => false,
                'message' => "Une erreur est suvenue lors de la création de l'agence!",
            ];
            return $response;
        }


    }

    public function create_account($user_id){

        $default = new GenerateIdController;
        $account_number = $default->AccountNumber();
        $dt       = Carbon::now();
        $todayDate = $dt->toDayDateTimeString();
        
        $account_type = "principal";
        $agence_id = $this->agenceid();

        $current_1 = new Account();
        $current_1->user_id        = $user_id;
        $current_1->account_number = $account_number;
        $current_1->account_type   = $account_type;
        $current_1->account_balance  = 0;
        $current_1->account_currency = "CDF";
        $current_1->agence_id      = $agence_id;
        $current_1->created_at   = $todayDate;
        $current_1->updated_at   = $todayDate;
        $currentAccount1         = $current_1->save();   

        $current_2 = new Account();
        $current_2->user_id        = $user_id;
        $current_2->account_number = $account_number;
        $current_2->account_type   = $account_type;
        $current_2->account_balance  = 0;
        $current_2->account_currency = "USD";
        $current_2->agence_id      = $agence_id;
        $current_2->created_at   = $todayDate;
        $current_2->updated_at   = $todayDate;
        $currentAccount2         = $current_2->save(); 

        if ($currentAccount1 && $currentAccount2) {
            $response = [
                'success' => true,
                'message' => "Compte a été créé avec succès!",
            ];
            return $response;
        }
        else {
            $response = [
                'success' => false,
                'message' => "Erreur lors de l'initialisation du compte!",
            ];
            return $response;
        }

    }

    public function create_customer($firstname,$lastname,$telephone,$email,$role,$password){
        $dt       = Carbon::now();
        $todayDate = $dt->toDayDateTimeString();

        $username = $this->username($firstname,$lastname);
        $verify_number = new VerifyNumberController;
        $phone_number = $verify_number->verify_number($telephone);

        $check_user = DB::table('users')->where('phone_number',$phone_number)->first();

        if ($check_user == null) {
            $user = User::create([
                'username'      => $username,
                'firstname'      => $firstname,
                'lastname'      => $lastname,
                'phone_number'      => $phone_number,
                'avatar'    => "user.png",
                'email'     => $email,
                'join_date' => $todayDate,
                'role_name' =>$role,
                'user_status' => 'Hors ligne',
                'password'  => Hash::make($password),
                'salt'  => $password,
                'created_at'   => $todayDate,
                'updated_at'   => $todayDate,
            ]);

            if ($user) {
                $select_id = DB::table('users')->where('phone_number',$phone_number)->first();
                $user_id = $select_id->id;

                $account = DB::table('accounts')->where('user_id', $user_id)->first();

                if ($account == null) {
                    $create_account = $this->create_account($user_id);
                    if ($create_account) {
                        $response = [
                            'success' => true,
                            'message' => "Compte a été créé avec succès!",
                        ];
                        return $response;
                    }
                    else {
                        $response = [
                            'success' => false,
                            'message' => "Erreur lors de l'initialisation du compte!",
                        ];
                        return $response;
                    }
                }

            }
        }

    }

    public function create_agence_account($user_id){

        $default = new GenerateIdController;
        $account_number = $default->AccountNumber();
        $dt       = Carbon::now();
        $todayDate = $dt->toDayDateTimeString();

        $agence_id = $this->agenceid();
        $agence_account_1 = new Agence_account();
        $agence_account_1->user_id        = $user_id;
        $agence_account_1->account_number = $account_number;
        $agence_account_1->account_type   = "emala";
        $agence_account_1->account_cash  = 0;
        $agence_account_1->account_virtuel  = 0;
        $agence_account_1->account_currency = "CDF";
        
        $agence_account_1->user_session      = 0;
        $agence_account_1->acount_session    = 0;
        $agence_account_1->agence_id      = $agence_id;
        $agence_account_1->created_at   = $todayDate;
        $agence_account_1->updated_at   = $todayDate;
        $agence_accountAccount1         = $agence_account_1->save();   

        $agence_account_2 = new Agence_account();
        $agence_account_2->user_id        = $user_id;
        $agence_account_2->account_number = $account_number;
        $agence_account_2->account_type   = "emala";
        $agence_account_2->account_cash  = 0;
        $agence_account_2->account_virtuel  = 0;
        $agence_account_2->account_currency = "USD";
        
        $agence_account_2->user_session      = 0;
        $agence_account_2->acount_session    = 0;
        $agence_account_2->agence_id      = $agence_id;
        $agence_account_2->created_at   = $todayDate;
        $agence_account_2->updated_at   = $todayDate;
        $agence_accountAccount2         = $agence_account_2->save();
        
        $agence_account_3 = new Agence_account();
        $agence_account_3->user_id        = $user_id;
        $agence_account_3->account_number = $account_number;
        $agence_account_3->account_type   = "mobile-money";
        $agence_account_3->account_cash  = 0;
        $agence_account_3->account_virtuel  = 0;
        $agence_account_3->account_currency = "CDF";
        
        $agence_account_3->user_session      = 0;
        $agence_account_3->acount_session    = 0;
        $agence_account_3->agence_id      = $agence_id;
        $agence_account_3->created_at   = $todayDate;
        $agence_account_3->updated_at   = $todayDate;
        $agence_accountAccount3         = $agence_account_3->save();   

        $agence_account_4 = new Agence_account();
        $agence_account_4->user_id        = $user_id;
        $agence_account_4->account_number = $account_number;
        $agence_account_4->account_type   = "mobile-money";
        $agence_account_4->account_cash  = 0;
        $agence_account_4->account_virtuel  = 0;
        $agence_account_4->account_currency = "USD";
        
        $agence_account_4->user_session      = 0;
        $agence_account_4->acount_session    = 0;
        $agence_account_4->agence_id      = $agence_id;
        $agence_account_4->created_at   = $todayDate;
        $agence_account_4->updated_at   = $todayDate;
        $agence_accountAccount4         = $agence_account_4->save();

        if ($agence_accountAccount1 && $agence_accountAccount2 && $agence_accountAccount3 && $agence_accountAccount4) {
            $response = [
                'success' => true,
                'message' => "Compte a été créé avec succès!",
            ];
            return $response;
        }
        else {
            $response = [
                'success' => false,
                'message' => "Erreur lors de l'initialisation du compte!",
            ];
            return $response;
        } 
                       

    }

    public function create_admin($user_id){

        $dt       = Carbon::now();
        $todayDate = $dt->toDayDateTimeString();
        $check_user = DB::table('users')->where('id', $user_id)->first();
        $agent_id = Auth::user()->id;
        $level = DB::table('admins')->where('user_id', $agent_id)->first();
        //dd($level);
        /* Auth Super Admin créer Admin Master   */
        if (Auth::user()->role_name == "Super Admin") {
            $admin = new Admin();
            $admin->user_id  = $user_id;
            $admin->username = $check_user->username;
            $admin->password   = $check_user->password;
            $admin->admin_level  = 2;
            $admin->created_at   = $todayDate;
            $admin->updated_at   = $todayDate;
            $save  = $admin->save();  
            if ($save) {
                $response = [
                    'success' => true,
                    'message' => "Le Master a été créé avec succès!",
                ];
                return $response;
            }
            else {
                $response = [
                    'success' => false,
                    'message' => "Echec de l'opération!",
                ];
                return $response;
            }
        }
        /* Auth Master créer un Admin-Gérant */
        elseif (Auth::user()->role_name == "Admin" && $level->admin_level == "2") {

            //dd("$level");
            $admin = new Admin();
            $admin->user_id  = $user_id;
            $admin->username = $check_user->username;
            $admin->password   = Hash::make($check_user->salt);
            $admin->admin_level  = 3;
            $admin->created_at   = $todayDate;
            $admin->updated_at   = $todayDate;
            $save  = $admin->save();  
            if ($save) {
                $response = [
                    'success' => true,
                    'message' => "Gérant créé avec succès!",
                ];
                return $response;
            }
            else {
                $response = [
                    'success' => false,
                    'message' => "Echec de l'opération!",
                ];
                return $response;
            }
        }

    }


    public function create_gerant($user_id){

        $dt       = Carbon::now();
        $todayDate = $dt->toDayDateTimeString();
        $check_user = DB::table('users')->where('id', $user_id)->first();
        $level = DB::table('admins')->where('user_id', $user_id)->first();

        /* Si le Auth est un admin, il crée un admin gérant. Le level est 3 */
        if ((Auth::user()->role_name == "Admin" )) {

            $admin = new Admin();
            $admin->user_id  = $user_id;
            $admin->username = $check_user->username;
            $admin->password   = Hash::make($check_user->salt);
            $admin->admin_level  = 4;
            $admin->created_at   = $todayDate;
            $admin->updated_at   = $todayDate;
            $save  = $admin->save();  
            if ($save) {
                $response = [
                    'success' => true,
                    'message' => "Gérant créé avec succès!",
                ];
                return $response;
            }
            else {
                $response = [
                    'success' => false,
                    'message' => "Echec de l'opération!",
                ];
                return $response;
            }
        }
        else {
            dd('erreur au niveau creation gerant');
        }

    }

    public function update_admin($firstname,$lastname,$phone_number,$email,$password, $user_id){

        $dt       = Carbon::now();
        $todayDate = $dt->toDayDateTimeString();
        $check_user = DB::table('users')->where('id', $phone_number)->first();

        /* Si le Auth est un super admin, le level est pris dans le formulaire de
           création d'un administrateur dans le select option. Il crée donc ici un admin gérant   */
        
            $update = [
                'phone_number' => $phone_number,
                'salt' => $password,
                'password'  => Hash::make($password),
                'firstname' => $firstname,
                'lastname' => $lastname,
                'email' => $email,
            ];
            $save_update = DB::table('users')->where('id', $user_id)->update($update);
            if ($save_update) {
                $select = DB::table('users')->where('phone_number',$phone_number)->first();

                $data = [
                    'username' => $select->username,
                    'password' => $select->password,
                ];
                $save_admin = DB::table('admins')->where('user_id',$select->id)->update($data);
                if ($save_admin) {
                    $response = [
                        'success' => true,
                        'message' => "Admin a été modifié avec succès!",
                    ];
                    return $response;
                }
                else {
                    $response = [
                        'success' => false,
                        'message' => "Echec de l'opération!",
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
           
            $update = DB::table('users')->where('id', $user_id)->update($data);
            if ($update) {
                $response = [
                    'success' => true,
                    'message' => "Informations sur le client modifiées avec succès!",
                ];
                return $response;
            }
            else {
                $response = [
                    'success' => false,
                    'message' => "Echec de l'opération!",
                ];
                return $response;
            }
    }

    public function update_gerant($firstname,$lastname,$phone_number,$password, $user_id){
        
        $data = [
            'phone_number' => $phone_number,
            'salt' => $password,
            'password'  => Hash::make($password),
            'firstname' => $firstname,
            'lastname' => $lastname,
        ];
       
        $update = DB::table('users')->where('id', $user_id)->update($data);
        if ($update) {
            $response = [
                'success' => true,
                'message' => "Informations sur le gérant modifiées avec succès!",
            ];
            return $response;
        }
        else {
            $response = [
                'success' => false,
                'message' => "Echec de l'opération!",
            ];
            return $response;
        }
    }

    public function update_caissier($firstname,$lastname,$phone_number,$password, $user_id){
        
        $data = [
            'phone_number' => $phone_number,
            'salt' => $password,
            'password'  => Hash::make($password),
            'firstname' => $firstname,
            'lastname' => $lastname,
        ];
       
        $update = DB::table('users')->where('id', $user_id)->update($data);
        if ($update) {
            $response = [
                'success' => true,
                'message' => "Informations sur le caissier modifiées avec succès!",
            ];
            return $response;
        }
        else {
            $response = [
                'success' => false,
                'message' => "Echec de l'opération!",
            ];
            return $response;
        }
    }

    public function delete_customer($fullName,$email,$phone_number,$status, $role_name, $todayDate, $user_id)
    {
        $activityLog = [
            'user_name'    => $fullName,
            'email'        => $email,
            'phone_number' => $phone_number,
            'status'       => $status,
            'role_name'    => $role_name,
            'modify_user'  => 'Utilisateur supprimé du système.',
            'date_time'    => $todayDate,
        ];

        $save = DB::table('user_activity_logs')->insert($activityLog);

        if($save){
            $destroy = User::destroy($user_id);
            if ($destroy) {
                $response = [
                    'success' => true,
                    'message' => "Client supprimé avec succès!",
                ];
                return $response;
            }
            else {
                $response = [
                    'success' => false,
                    'message' => "Echec de l'opération!",
                ];
                return $response;
            }
        }
    }

    public function delete_agence($fullName,$email,$phone_number,$status, $role_name, $todayDate, $user_id)
    {
        $activityLog = [
            'user_name'    => $fullName,
            'email'        => $email,
            'phone_number' => $phone_number,
            'status'       => $status,
            'role_name'    => $role_name,
            'modify_user'  => 'Agence supprimée du système.',
            'date_time'    => $todayDate,
        ];

        $save = DB::table('user_activity_logs')->insert($activityLog);

        if($save){
            $destroy = Agence::destroy($user_id);
            if ($destroy) {
                $response = [
                    'success' => true,
                    'message' => "Agence supprimée avec succès!",
                ];
                return $response;
            }
            else {
                $response = [
                    'success' => false,
                    'message' => "Echec de l'opération!",
                ];
                return $response;
            }
        }
    }

    public function delete_gerant($fullName,$email,$phone_number,$status, $role_name, $todayDate, $user_id)
    {
        $activityLog = [
            'user_name'    => $fullName,
            'email'        => $email,
            'phone_number' => $phone_number,
            'status'       => $status,
            'role_name'    => $role_name,
            'modify_user'  => 'Utilisateur supprimé du système.',
            'date_time'    => $todayDate,
        ];

        $save = DB::table('user_activity_logs')->insert($activityLog);

        if($save){
            $destroy = User::destroy($user_id);
            if ($destroy) {
                $response = [
                    'success' => true,
                    'message' => "Gérant supprimé avec succès!",
                ];
                return $response;
            }
            else {
                $response = [
                    'success' => false,
                    'message' => "Echec de l'opération!",
                ];
                return $response;
            }
        }
    }

    public function delete_admin($fullName,$email,$phone_number,$status, $role_name, $todayDate, $user_id)
    {
        $activityLog = [
            'user_name'    => $fullName,
            'email'        => $email,
            'phone_number' => $phone_number,
            'status'       => $status,
            'role_name'    => $role_name,
            'modify_user'  => 'Utilisateur supprimé du système.',
            'date_time'    => $todayDate,
        ];

        $save = DB::table('user_activity_logs')->insert($activityLog);

        if($save){
            $destroy = User::destroy($user_id);
            if ($destroy) {
                $response = [
                    'success' => true,
                    'message' => "Admin supprimé avec succès!",
                ];
                return $response;
            }
            else {
                $response = [
                    'success' => false,
                    'message' => "Echec de l'opération!",
                ];
                return $response;
            }
        }
    }

    public function delete_caissier($fullName,$email,$phone_number,$status, $role_name, $todayDate, $user_id)
    {
        $activityLog = [
            'user_name'    => $fullName,
            'email'        => $email,
            'phone_number' => $phone_number,
            'status'       => $status,
            'role_name'    => $role_name,
            'modify_user'  => 'Caissier supprimé du système.',
            'date_time'    => $todayDate,
        ];

        $save = DB::table('user_activity_logs')->insert($activityLog);

        if($save){
            $destroy = User::destroy($user_id);
            if ($destroy) {
                $response = [
                    'success' => true,
                    'message' => "Admin supprimé avec succès!",
                ];
                return $response;
            }
            else {
                $response = [
                    'success' => false,
                    'message' => "Echec de l'opération!",
                ];
                return $response;
            }
        }
    }

    public function create_user($firstname,$lastname,$telephone,$email,$role){
        $dt       = Carbon::now();
        $todayDate = $dt->toDayDateTimeString();

        $username = $this->username($firstname,$lastname);
        $verify_number = new VerifyNumberController;
        $phone_number = $verify_number->verify_number($telephone);

        $default = new GenerateIdController;
        $password = $default->defaultPIN();

        $check_user = DB::table('users')->where('phone_number',$phone_number)->first();

        if ($check_user == null) {
            $user = User::create([
                'username'      => $username,
                'firstname'      => $firstname,
                'lastname'      => $lastname,
                'phone_number'      => $phone_number,
                'avatar'    => "user.png",
                'email'     => $email,
                'join_date' => $todayDate,
                'role_name' =>$role,
                'user_status' => 'Hors ligne',
                'password'  => Hash::make($password),
                'salt'  => $password,
                'created_at'   => $todayDate,
                'updated_at'   => $todayDate,
            ]);

            if ($user) {
                $select_id = DB::table('users')->where('phone_number',$phone_number)->first();
                $user_id = $select_id->id;

                $account = DB::table('accounts')->where('user_id', $user_id)->first();

                if ($account == null) {
                    $create_account = $this->create_account($user_id);
      
                    if ($create_account) {
                        if ($role == "Caissier") {
                            $account = $this->create_agence_account($user_id);
                            if ($account) {
                                $response = [
                                    'success' => true,
                                    'message' => "Compte a été créé avec succès!",
                                ];
                                return $response;
                            }
                            else {
                                $response = [
                                    'success' => false,
                                    'message' => "Erreur lors de l'initialisation du compte!",
                                ];
                                return $response;
                            }
                        }

                        elseif ($role == "Admin") {
                            //dd($user_id);
                            $create_admin = $this->create_admin($user_id);
                            //dd($create_admin);
                            if ($create_admin['success'] == true) {
                                $account = $this->create_agence_account($user_id);
                                if ($account) {
                                    $response = [
                                        'success' => true,
                                        'message' => "Compte a été créé avec succès!",
                                    ];
                                    return $response;
                                }
                                else {
                                    $response = [
                                        'success' => false,
                                        'message' => "Erreur lors de l'initialisation du compte!",
                                    ];
                                    return $response;
                                }
                            }
                        }

                        elseif ($role == "Gérant") {
                            $create_gerant = $this->create_gerant($user_id);
                            if ($create_gerant['success'] == true) {
                                $account = $this->create_agence_account($user_id);
                                if ($account) {
                                    $response = [
                                        'success' => true,
                                        'message' => "Compte a été créé avec succès!",
                                    ];
                                    return $response;
                                }
                                else {
                                    $response = [
                                        'success' => false,
                                        'message' => "Erreur lors de l'initialisation du compte!",
                                    ];
                                    return $response;
                                }
                            }
                        }
                        
                    }
                    else {
                        $response = [
                            'success' => false,
                            'message' => "Erreur lors de l'initialisation du compte!",
                        ];
                        return $response;
                    }
                }

            }
        }

    }

    public function topup_agence_account($account_type, $currency, $user_id, $amount, $gateway){
        $debit_agence = $this->debit_agence($amount, $currency, $gateway);
        if ($debit_agence['resultat'] == 1) {
            $agence_id = $this->agence_id($currency);
            $account = DB::table('agence_accounts')->where('account_type', $account_type)->where('agence_id', $agence_id)->where('account_currency', $currency)->where('user_id', $user_id)->first();
            $data = [
                'account_cash'    => $account['account_cash'] + $amount,
                'account_virtuel' => $account['account_virtuel'] + $amount,
            ];
            $update = DB::table('agence_accounts')->where('agence_id', $agence_id)->where('account_currency', $currency)->where('user_id', $user_id)->update($data);
            if ($update) {
                $response = [
                    'success' => true,
                    'resultat' => 1,
                ];
                return $response;
            }        
            else {
                $response = [
                    'success' => false,
                    'resultat' => 0,
                ];
                return $response;
            }
        }
        elseif ($debit_agence['resultat'] == 0) {
            $response = [
                'success' => false,
                'resultat' => 0,
                'message' => $debit_agence['message'],
            ];
            return $response;
        }
        else {
            $response = [
                'success' => false,
                'resultat' => 2,
                'message' => "Une erreur est survenue",
            ];
            return $response;
        }
        
    }

    

    public function transfert($account_type, $agent_id, $sender_phone, $receiver_id, $currency, $amount, $descritption, $total, $fees){
        $currentTime = Carbon::now();
        $date = $currentTime->toDateTimeString();

        $reference = new generateReferenceController;
        $transaction_id = $reference->reference($descritption);
        $transaction_secret = $reference->coderetrait();

        $sender_info = DB::table('users')->where('phone_number', $sender_phone)->first();
        $receiver_info = DB::table('users')->where('id', $receiver_id)->first();

        $request = $this->credit_agence_account($account_type, $currency, $agent_id, $total);

        if ($request['success'] == true) {
            $data = [
                'user_id'=>$agent_id,
                'sphone'=>$sender_info->phone_number,
                'amount'=>$amount,
                'frais'=>$fees,
                'currency'=>$currency,
                'transaction_id'=>$transaction_id,
                'transaction_status'=>"Envoyé",
                'description'=>$descritption,
                'rphone'=>$receiver_info->phone_number,
                'agence'=>$this->agence_id($currency),
                'action'=>"credit",
                'created_at' => $date,
                'updated_at' => $date
            ];

            $transfert = [
                'user_id'=>$agent_id,
                'sname'=>$sender_info->lastname,
                // 'smiddle'=>$sender_info->middlename,
                'sfirst'=>$sender_info->firstname,
                'sphone'=>$sender_info->phone_number,
                'saddress'=>$sender_info->address,
                'scity'=>$sender_info->city,
                'amount'=>$amount,
                'currency'=>$currency,
                'transaction_id'=>$transaction_id,
                'transaction_secret'=>$transaction_secret,
                'transaction_status'=>"Envoyé",
                'description'=>$descritption,
                'rname'=>$receiver_info->lastname,
                // 'rmiddle'=>$receiver_info->middlename,
                'rfirst'=>$receiver_info->firstname,
                'rphone'=>$receiver_info->phone_number,
                'agence'=>$this->agence_id($currency),
                'rcity'=>$receiver_info->city,
                'created_at' => $date,
                'updated_at' => $date
            ];
            $rapport = DB::table('transactions')->insert($data);
            if ($rapport) {
                $save = DB::table('transferts')->insert($transfert);
                if ($save) {
                    $response = [
                        'success' => true,
                        'message' => "Transfert effectué avec succès!",
                    ];
                    return $response;
                }
                
            }
        }
        else {
            $response = [
                'success' => false,
                'resultat' => 0,
                'message' => "Erreur survenue en crédiatant l'agence",
            ];
            return $response;
        }

    }

    public function debit_agence_account($currency, $user_id, $amount){

        $admin = DB::table('admins')->where('user_id', $user_id)->first();
        $action = "debit";
        $agent_id = Auth::user()->id;
        $account = DB::table('agence_accounts')->where('account_currency', $currency)->where('user_id', $agent_id)->first();

        if ($amount > $account->account_cash && $amount > $account->account_virtuel) {
            $response = [
                'success' => false,
                'resultat' => 0,
                'message' => "Solde agence insuffisant!",
            ];
            return $response;
        }

        else {
            $data = [
                'account_cash'    => $account->account_cash - $amount,
                'account_virtuel' => $account->account_virtuel - $amount,
            ];
            $update = DB::table('agence_accounts')->where('id', $account->id)->update($data);
            if ($update) {
                $this->agence_account_stories($admin->id,$account->id,$amount,$currency, $action);
                $response = [
                    'success' => true,
                    'resultat' => 1,
                ];
                return $response;
            }        
            else {
                $response = [
                    'success' => false,
                    'resultat' => 0,
                ];
                return $response;
            }
        }
        
    }

    public function credit_agence_account($currency, $user_id, $amount){

        $admin = DB::table('admins')->where('user_id', $user_id)->first();

        $action = "credit";
        $agent_id = Auth::user()->id;

        $account = DB::table('agence_accounts')->where('account_currency', $currency)->where('user_id', $agent_id)->first();

        $data = [
            'account_cash'    => $account->account_cash + $amount,
            'account_virtuel' => $account->account_virtuel + $amount,
        ];
        $update = DB::table('agence_accounts')->where('id', $account->id)->update($data);
        if ($update) {
            $this->agence_account_stories($admin->id,$account->id,$amount,$currency, $action);
            $response = [
                'success' => true,
                'resultat' => 1,
            ];
            return $response;
        }        
        else {
            $response = [
                'success' => false,
                'resultat' => 0,
            ];
            return $response;
        }
    }

    public function agence_account_stories($user_id,$account_id,$amount,$currency, $action){
        $dt       = Carbon::now();
        $todayDate = $dt->toDateTimeString();

        $data = [
            'user_id'    => $user_id,
            'account_id' => $account_id,
            'amount' => $amount,
            'currency' => $currency,
            'created_at'   => $todayDate,
            'updated_at'   => $todayDate,
            'action'   => $action,
        ];
        $update = DB::table('agence_account_stories')->insert($data);
        if ($update) {
            $response = [
                'success' => true,
                'resultat' => 1,
            ];
            return $response;
        }
        else {
            $response = [
                'success' => false,
                'resultat' => 0,
            ];
            return $response;
        }

    }
    public function agence_stories($user_id,$agence_id,$amount,$currency){

        $dt       = Carbon::now();
        $todayDate = $dt->toDateTimeString();

        $data = [
            'user_id'    => $user_id,
            'agence_id' => $agence_id,
            'amount' => $amount,
            'currency' => $currency,
            'created_at'   => $todayDate,
            'updated_at'   => $todayDate,
        ];
        $update = DB::table('agence_stories')->insert($data);
        if ($update) {
            $response = [
                'success' => true,
                'resultat' => 1,
            ];
            return $response;
        }
        else {
            $response = [
                'success' => false,
                'resultat' => 0,
            ];
            return $response;
        }

    }

    public function topup_agence($amount, $currency, $gateway, $agence_id){
        //dd($currency);
        $response = $this->debit_wallet($amount, $currency, $gateway);
        //dd( $response['success']);
        if ($response['success'] == true) {
           
            $account = DB::table('agences')->where('id', $agence_id)->first();
           
            if ($gateway == "emala") {
                $data = [
                    'balance_cash'    => $account->balance_cash + $amount,
                    'balance_virtuel' => $account->balance_virtuel + $amount,
                ];
                $update = DB::table('agences')->where('id', $agence_id)->update($data);
                if ($update) {
                    $response = [
                        'success' => true,
                        'resultat' => 1,
                        'message' => "Agence rechargée avec succès",
                    ];
                    return $response;
                }        
                else {
                    $response = [
                        'success' => false,
                        'resultat' => 0,
                        'message' => "Echec de l'opération",

                    ];
                    return $response;
                }     
            }
            elseif ($gateway == "mobile-money") {
                $data = [
                    'balance_cash'    => $account->balance_cash + $amount,
                    'balance_virtuel' => $account->balance_virtuel + $amount,
                ];
                $update = DB::table('agences')->where('id', $agence_id)->where('agence_currency', $currency)->update($data);
                if ($update) {
                    $response = [
                        'success' => true,
                        'resultat' => 1,
                    ];
                    return $response;
                }        
                else {
                    $response = [
                        'success' => false,
                        'resultat' => 0,
                    ];
                    return $response;
                }     
            }
        }

        else{
            $response = [
                'success' => false,
                'resultat' => 0,
                'message' => "Solde insuffisant",

            ];
            return $response;
        }
    }
        
    public function debit_wallet($amount, $currency, $wallet_type){
        $agence_id = $this->agence_id($currency);
        
        $account = DB::table('agences')->where('id', $agence_id)->first();
        //dd($currency );
        $wallet_id = $account->wallet_id;
        
        $wallet = DB::table('wallets')->where('wallet_id', $wallet_id)->where('wallet_currency', $currency)->first();
       // dd($wallet);
        if ($amount > $wallet->balance_virtuel && $amount > $wallet->balance_cash) {
            $response = [
                'success' => false,
                'resultat' => 0,
                'message' => "Solde agence insuffisant!",
            ];
            return $response;
        }
        else {
            $data = [
                'balance_cash'    => $wallet->balance_cash - $amount,
                'balance_virtuel' => $wallet->balance_virtuel - $amount,
            ];
            $update = DB::table('wallets')->where('id', $wallet->id)->update($data);  
            if ($update) {
                $response = [
                    'success' => true,
                    'resultat' => 1,
                ];
                return $response;
            }
        }
                  
    }

    public function credit_wallet($amount, $currency, $wallet_type){
        $agence_id = $this->agence_id($currency);
        $account = DB::table('agences')->where('id', $agence_id)->where('agence_currency', $currency)->first();
        $wallet_id = $account->wallet_id;

        $wallet = DB::table('wallets')->where('wallet_id', $wallet_id)->where('wallet_currency', $currency)->first();
        $data = [
            'balance_cash'    => $wallet->balance_cash + $amount,
            'balance_virtuel' => $wallet->balance_virtuel + $amount,
        ];
        $update = DB::table('wallets')->where('wallet_type', $wallet_type)->where('wallet_id', $wallet_id)->where('wallet_currency', $currency)->update($data);  
        if ($update) {
            $response = [
                'success' => true,
                'resultat' => 1,
            ];
            return $response;
        }          
    }

    public function topup_wallet($amount, $wallet_id){
        $wallet = DB::table('wallets')->where('id', $wallet_id)->first();
        $dt       = Carbon::now();
        $todayDate = $dt->toDateTimeString();
        $data = [
            'balance_cash'    => $wallet->balance_cash + $amount,
            'balance_virtuel' => $wallet->balance_virtuel + $amount,
        ];
        $update = DB::table('wallets')->where('id', $wallet_id)->update($data);  
        if ($update) {
            if ($wallet->wallet_currency== "CDF") {
                $data = [
                    'admin_id' => $wallet->admin_id,
                    'wallet_id' => $wallet->wallet_id,
                    'currency' => "CDF",
                    'amount' => $amount,
                    'created_at'   => $todayDate,
                    'updated_at'   => $todayDate,
                ];
                $save = DB::table('wallet_stories')->insert($data);  
                if ($save) {
                    $response = [
                        'success' => true,
                        'resultat' => 1,
                        'message' => "Wallet rechargé avec succès",
                    ];
                    return $response;
                }
                
            }

            elseif ($wallet->wallet_currency== "USD") {
                $data = [
                    'admin_id' => $wallet->admin_id,
                    'wallet_id' => $wallet->wallet_id,
                    'currency' => "USD",
                    'amount' => $amount,
                    'created_at'   => $todayDate,
                    'updated_at'   => $todayDate,
                ];
                $save = DB::table('wallet_stories')->insert($data);  
                if ($save) {
                    $response = [
                        'success' => true,
                        'resultat' => 1,
                        'message' => "Wallet rechargé avec succès",
                    ];
                    return $response;
                }
            }
            
            
        }          
    }

    public function debit_agence($amount, $currency, $gateway){
        $agence_id = $this->agence_id($currency);

        if ($gateway == "emala") {
            $account = DB::table('agences')->select('balance_cash', 'balance_virtuel')->where('id', $agence_id)->where('agence_currency', $currency)->first();
            if ($amount >= $account->balance_cash && $amount >= $account->balance_virtuel) {
                $response = [
                    'success' => false,
                    'resultat' => 0,
                    'message' => "Solde agence insuffisant!",
                ];
                return $response;
            }
            else {
                $data = [
                    'balance_cash'    => $account->balance_cash - $amount,
                    'balance_virtuel' => $account->balance_virtuel - $amount,
                ];
                $update = DB::table('agences')->where('id', $agence_id)->where('agence_currency', $currency)->update($data);  
                if ($update) {
                    $response = [
                        'success' => true,
                        'resultat' => 1,
                    ];
                    return $response;
                }
                else {
                    $response = [
                        'success' => false,
                        'resultat' => 2,
                    ];
                    return $response;
                }
            }
        }

        elseif ($gateway == "mobile-money") {
            $account = DB::table('agences')->select('mobile_cash', 'mobile_virtuel')->where('id', $agence_id)->where('agence_currency', $currency)->first();
            if ($amount >= $account['mobile_cash'] && $amount >= $account['mobile_virtuel']) {
                $response = [
                    'success' => false,
                    'resultat' => 0,
                    'message' => "Solde agence insuffisant!",
                ];
                return $response;
            }
            else {
                $data = [
                    'mobile_cash'    => $account['mobile_cash'] - $amount,
                    'mobile_virtuel' => $account['mobile_virtuel'] - $amount,
                ];
                $update = DB::table('agences')->where('id', $agence_id)->where('agence_currency', $currency)->update($data);  
                if ($update) {
                    $response = [
                        'success' => true,
                        'resultat' => 1,
                    ];
                    return $response;
                }
                else {
                    $response = [
                        'success' => false,
                        'resultat' => 0,
                    ];
                    return $response;
                }
            }
        }
         
    }

    public function credit_agence($amount, $currency, $gateway){
        $agence_id = $this->agence_id($currency);

        if ($gateway == "emala") {
            $account = DB::table('agences')->select('balance_cash', 'balance_virtuel')->where('id', $agence_id)->where('agence_currency', $currency)->first();
            $data = [
                'balance_cash'    => $account->balance_cash + $amount,
                'balance_virtuel' => $account->balance_virtuel + $amount,
            ];
            $update = DB::table('agences')->where('id', $agence_id)->where('agence_currency', $currency)->update($data);  
            if ($update) {
                $response = [
                    'success' => true,
                    'resultat' => 1,
                ];
                return $response;
            }
            else {
                $response = [
                    'success' => false,
                    'resultat' => 2,
                ];
                return $response;
            }
        }

        elseif ($gateway == "mobile-money") {
            $account = DB::table('agences')->select('mobile_cash', 'mobile_virtuel')->where('id', $agence_id)->where('agence_currency', $currency)->first();
            $data = [
                'mobile_cash'    => $account['mobile_cash'] + $amount,
                'mobile_virtuel' => $account['mobile_virtuel'] + $amount,
            ];
            $update = DB::table('agences')->where('id', $agence_id)->where('agence_currency', $currency)->update($data);  
            if ($update) {
                $response = [
                    'success' => true,
                    'resultat' => 1,
                ];
                return $response;
            }
            else {
                $response = [
                    'success' => false,
                    'resultat' => 2,
                ];
                return $response;
            }
        }


    }

    public function user_account_details($user_id){
        $users = DB::table('users')->where('id',$user_id)->first();
        $principal_account_cdf = DB::table('accounts')->where('account_currency','CDF')->where('account_type','principal')->where('user_id',$user_id)->first();
        $principal_account_usd = DB::table('accounts')->where('account_currency','USD')->where('account_type','principal')->where('user_id',$user_id)->first();
        $epargne_account_cdf = DB::table('accounts')->where('account_currency','CDF')->where('account_type','epargne')->where('user_id',$user_id)->first();
        $epargne_account_usd = DB::table('accounts')->where('account_currency','USD')->where('account_type','epargne')->where('user_id',$user_id)->first();


        $amount_cdf_principal = $principal_account_cdf->account_balance;
        $amount_usd_principal = $principal_account_usd->account_balance;
        $acnumber_principal = $principal_account_cdf->account_number;

        if ($epargne_account_cdf != null && $epargne_account_usd != null) {
            $amount_cdf_epargne = $epargne_account_cdf->account_balance;
            $amount_usd_epargne = $epargne_account_usd->account_balance;
            $acnumber_epargne = $epargne_account_cdf->account_number;
        }
        if ($epargne_account_cdf == null && $epargne_account_usd != null) {
            $amount_cdf_epargne = "null";
            $amount_usd_epargne = $epargne_account_usd->account_balance;
            $acnumber_epargne = $epargne_account_usd->account_number;
        }
        if ($epargne_account_cdf != null && $epargne_account_usd == null) {
            $amount_cdf_epargne = $epargne_account_cdf->account_balance;
            $amount_usd_epargne = "null";
            $acnumber_epargne = $epargne_account_cdf->account_number;
        }
        if ($epargne_account_cdf == null && $epargne_account_usd == null) {
            $amount_cdf_epargne = "null";
            $amount_usd_epargne = "null";
            $acnumber_epargne = "null";
        }

        $response = [
            'success' => true,
            'resultat' => 1,
            'data' => [$users,$amount_cdf_principal,$amount_usd_principal,$acnumber_principal,$amount_cdf_epargne,$amount_usd_epargne,$acnumber_epargne],
        ];
        //dd($response);
        return $response;

    }

}
