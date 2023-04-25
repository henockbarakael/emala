<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\__init__;
use App\Http\Controllers\Controller;
use App\Http\Controllers\EmalaUserController;
use App\Http\Controllers\GenerateIdController;
use App\Http\Controllers\VerifyNumberController;
use App\Models\cash_register;
use App\Models\tirroir_account;
use App\Providers\RouteServiceProvider;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Support\Facades\Session;
use Brian2694\Toastr\Facades\Toastr;
use Illuminate\Support\Facades\Validator;

class LoginController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Login Controller
    |--------------------------------------------------------------------------
    |
    | This controller handles authenticating users for the application and
    | redirecting them to your home screen. The controller uses a trait
    | to conveniently provide its functionality to your applications.
    |
    */

    use AuthenticatesUsers;

    /**
     * Where to redirect users after login.
     *
     * @var string
     */
    protected $redirectTo = RouteServiceProvider::HOME;
    protected function redirectTo(){
        if( Auth()->user()->role_name == "Root"){
            return route('root.dashboard');
        }
        elseif( Auth()->user()->role_name == "Admin"){
            return route('admin.dashboard');
        }
        elseif( Auth()->user()->role_name == "Manager"){
            // return route('gerant.dashboard');
            return redirect()->route('manager.dashboard');
        }
        elseif( Auth()->user()->role_name == "Cashier"){
            // return route('caissier.dashboard');
            return redirect()->route('cashier.dashboard');
        }
    }

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('guest')->except([
            'logout',
            'locked',
            'unlock'
        ]);
    }

    public function login()
    {
        return view('auth.login');
    }

    public function session(){

        $last_session = Session::get('last_session');
        $userid = Auth::User()->id;
        $initialize = new __init__;
        $report = $initialize->fond_precedent();
        $report_cdf_on_c = $report['report_cdf_on_c'];
        $report_usd_on_c = $report['report_usd_on_c'];
        return view('admin.session.validate', compact('last_session','report_cdf_on_c','report_usd_on_c'));

    }

    public function start_new_session(Request $request){

        $request->validate([
            'fund_usd_on_o'   => 'required|numeric',
            'fund_cdf_on_o'   => 'required|numeric',
        ]);

        $userid = Auth::User()->id;
        $initialize = new __init__;
        
        $bank_account = $initialize->bank_account($userid);
        $bank_user = $initialize->cashier_account_wallet($userid);

        $bank_account_id = $bank_account->id;
        $bank_user_id = $bank_user->bank_user_id;
        $todayDate = Carbon::now()->format('Y-m-d H:i:s');

        $generate = new GenerateIdController;
        $acnumber = $generate->bank_acount();

        $branche = DB::table('branches')->where('btype','Parent')->first();
        $branche_id = $branche->id;

        $cash_register = cash_register::create([
            'bank_account_id'=>$bank_account_id,
            'fund_cdf_on_o'=> $request->fund_cdf_on_o,
            'fund_usd_on_o'=>$request->fund_usd_on_o,
            'closed'=>"no",
            'opened_on'=>$todayDate
        ]);

        if ($cash_register) {
            tirroir_account::create([
                'acnumber'   => $acnumber,
                'bank_user_id'   => $bank_user_id,
                'branche_id'   => $branche_id,
                'status'   => 1,
                'created_at'   => $todayDate,
                'updated_at'   => $todayDate,
                'balance_cdf'   => $request->fund_cdf_on_o,
                'balance_usd'   => $request->fund_usd_on_o,
            ]);
            Toastr::success('Connexion réussie :)','Success');
            return redirect()->route('admin.dashboard');
        }
    }

    public function session_stay_in(){
        return redirect()->route('admin.dashboard');
    }

    

    public function authenticate(Request $request)
    {
        $request->validate([
            'telephone' => 'required|string|min:9|max:12',
            'password' => 'required|string|min:4|max:8',
        ]);
        $verify_number = new VerifyNumberController;
        $telephone = $verify_number->verify_number($request->telephone);
        $pin = $request->password;

        $dt         = Carbon::now();
        $todayDate  = $dt->toDayDateTimeString();
        $user_status = [
                'user_status' => 'En ligne',
        ];
        $clientIP = request()->ip(); 

        if (Auth::attempt(['phone_number'=>$telephone,'password'=>$pin])) {
            if (Auth::user()->role_name == "Root") {
                $stmt = DB::table('users')->where('phone_number',$telephone)->first();
                $prenom = $stmt->firstname;
                $nom = $stmt->lastname;
                $email = $stmt->email;
                $telephone    = $telephone;
                $pin = $request->pin;
                $activityLog = [
                    'name'       => $prenom." ".$nom,
                    'user_id'       => $stmt->id,
                    'email'       => $email,
                    'description' => 'Connecté',
                    'date_time'   => $todayDate,
                    'ipadresse'   => $clientIP,
                ];
                DB::table('activity_logs')->insert($activityLog);
                DB::table('users')->where('phone_number',$telephone)->update($user_status);
                Toastr::success('Connexion réussie :)','Success');
                return redirect()->route('root.dashboard');
            }
            elseif (Auth::user()->role_name == "Admin") {
                $stmt = DB::table('users')->where('phone_number',$telephone)->first();
                $prenom = $stmt->firstname;
                $nom = $stmt->lastname;
                $email = $stmt->email;
                $telephone    = $telephone;
                $pin = $request->pin;
                $activityLog = [
                    'name'       => $prenom." ".$nom,
                    'user_id'       => $stmt->id,
                    'email'       => $email,
                    'description' => 'Connecté',
                    'date_time'   => $todayDate,
                    'ipadresse'   => $clientIP,
                ];
                DB::table('activity_logs')->insert($activityLog);
                DB::table('users')->where('phone_number',$telephone)->update($user_status);
                Toastr::success('Connexion réussie :)','Success');
                return redirect()->route('admin.dashboard');
                 
            }
            elseif (Auth::user()->role_name == "Manager") {
                $stmt = DB::table('users')->where('phone_number',$telephone)->first();
                $prenom = $stmt->firstname;
                $nom = $stmt->lastname;
                $email = $stmt->email;
                $telephone    = $telephone;
                $pin = $request->pin;
                $activityLog = [
                    'name'       => $prenom." ".$nom,
                    'user_id'       => $stmt->id,
                    'email'       => $email,
                    'description' => 'Connecté',
                    'date_time'   => $todayDate,
                    'ipadresse'   => $clientIP,
                ];
                DB::table('activity_logs')->insert($activityLog);
                DB::table('users')->where('phone_number',$telephone)->update($user_status);
                Toastr::success('Connexion réussie :)','Success');
                return redirect()->route('manager.dashboard');
                 
            }
            elseif (Auth::user()->role_name == "Cashier") {
                $stmt = DB::table('users')->where('phone_number',$telephone)->first();
                $prenom = $stmt->firstname;
                $nom = $stmt->lastname;
                $email = $stmt->email;
                $telephone    = $telephone;
                $pin = $request->pin;
                $activityLog = [
                    'name'       => $prenom." ".$nom,
                    'user_id'       => $stmt->id,
                    'email'       => $email,
                    'description' => 'Connecté',
                    'date_time'   => $todayDate,
                    'ipadresse'   => $clientIP,
                ];
                DB::table('activity_logs')->insert($activityLog);
                DB::table('users')->where('phone_number',$telephone)->update($user_status);
                Toastr::success('Connexion réussie :)','Success');
                return redirect()->route('cashier.dashboard');
                 
            }

        }
        else{
            Toastr::error('Erreur, Nom d\'utilisateur ou mot de passe incorrect:)','Error');
            return redirect('login');
        }

    }

    public function logout()
    {
        $user = Auth::User();
        Session::put('users', $user);
        $user=Session::get('users');

        $telephone      = $user->telephone;
        $dt         = Carbon::now();
        $todayDate  = $dt->toDayDateTimeString();

        $currentTime = Carbon::now();
        $date = $currentTime->toDateTimeString();

        $clientIP = request()->ip(); 

        $activityLog = [
            'name'       => $user->firstname." ".$user->lastname,
            'user_id'       => $user->id,
            'email'       => $user->email,
            'description' => 'Déconnecté',
            'date_time'   => $todayDate,
            'ipadresse'   => $clientIP,
        ];

        $user_status = [
            'user_status' => 'Hors ligne',
        ];

        // $moncomptes = DB::table('agence_accounts')->where('user_id',Auth::user()->id)->first();
        // $session = $moncomptes->session;
        // $caisse = $moncomptes->caisse;
        // if($caisse == "2" && $session == "1"){
        //     $data = [
        //         'session' => '0',
        //         'caisse' => '2',
        //     ];
        //     DB::table('agence_accounts')->where('user_id',Auth::user()->id)->update($data);
        // }

        // elseif($caisse == "1" && $session == "1"){
        //     $data = [
        //         'session' => '0',
        //         'caisse' => '1',
        //     ];
        //     DB::table('agence_accounts')->where('user_id',Auth::user()->id)->update($data);
        // }

        // elseif($caisse == "0" && $session == "0"){
        //     $data = [
        //         'session' => '0',
        //         'caisse' => '2',
        //     ];
        //     DB::table('agence_accounts')->where('user_id',Auth::user()->id)->update($data);
        // }

        // elseif($caisse == "0" && $session == "1"){
        //     $data = [
        //         'session' => '0',
        //         'caisse' => '0',
        //     ];
        //     DB::table('agence_accounts')->where('user_id',Auth::user()->id)->update($data);
        // }



        DB::table('activity_logs')->insert($activityLog);
        DB::table('users')->where('phone_number',$telephone)->update($user_status);

        Auth::logout();
        Toastr::success('Déconnecté avec succès :)','Success');
        return redirect('login');
    }

}
