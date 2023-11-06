<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\__init__;
use App\Http\Controllers\API\Statistiques;
use App\Http\Controllers\Controller;
use App\Http\Controllers\EmalaUserController;
use App\Http\Controllers\GenerateIdController;
use App\Http\Controllers\VerifyNumberController;
use App\Models\Account;
use App\Models\cash_register;
use App\Models\Cashier;
use App\Models\CashRegister;
use App\Models\tirroir_account;
use App\Models\Transaction;
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
        $stmt = DB::table('users')->where('phone_number',$telephone)->first();
        $prenom = $stmt->firstname;
        $nom = $stmt->lastname;
        $email = $stmt->email;
        $telephone    = $telephone;

        $activityLog = [
            'name'       => $prenom." ".$nom,
            'user_id'       => $stmt->id,
            'email'       => $email,
            'description' => 'Connecté',
            'date_time'   => $todayDate,
            'ipadresse'   => $clientIP,
        ];


        if (Auth::attempt(['phone_number'=>$telephone,'password'=>$pin])) {
          
            $user = Auth::user();
            $cashiers = new Cashier();

            $account_cdf = $cashiers->getCdfBalance(Auth::user()->id);
            $account_usd = $cashiers->getUsdBalance(Auth::user()->id);

            if ($user->role_name == "Root") {
                
                DB::table('activity_logs')->insert($activityLog);
                DB::table('users')->where('phone_number',$telephone)->update($user_status);
                Toastr::success('Connexion réussie :)','Success');
                return redirect()->route('root.dashboard');
            }
            elseif ($user->role_name == "Admin") {
                
                DB::table('activity_logs')->insert($activityLog);
                DB::table('users')->where('phone_number',$telephone)->update($user_status);
                Toastr::success('Connexion réussie :)','Success');
                return redirect()->route('admin.dashboard');
                 
            }
            elseif ($user->role_name == "Manager") {
                $this->checkCashRegisterSession($user);
                
                DB::table('activity_logs')->insert($activityLog);
                DB::table('users')->where('phone_number',$telephone)->update($user_status);
                Toastr::success('Connexion réussie :)','Success');
                return redirect()->route('manager.dashboard');
                 
            }
            
            elseif ($user->role_name == "Cashier") {
                // Vérifier le statut du compte dans la table cash_registers
                $cdfId = Cashier::where('user_id', Auth::user()->id)
                    ->where('currency', 'CDF')
                    ->value('id');

                $usdId = Cashier::where('user_id', Auth::user()->id)
                    ->where('currency', 'USD')
                    ->value('id');

           
                $lastCashRegister = CashRegister::where('cashier_id',$cdfId)
                    ->orderBy('id', 'desc')
                    ->first();
            
                if (!$lastCashRegister || $lastCashRegister->status === 'closed') {
                    // Pas d'enregistrement de cash register trouvé ou dernier enregistrement est "closed"
                    DB::table('activity_logs')->insert($activityLog);
                    DB::table('users')->where('phone_number', $telephone)->update($user_status);
                    $user->last_session_date = date('Y-m-d');
                    $user->save();
                    Toastr::success('Connexion réussie :)', 'Success');
                    return redirect()->route('cashier.ouverture_caisse', [
                        'user_id' => auth()->user()->id,
                        'amount_cdf' => $account_cdf,
                        'amount_usd' => $account_usd
                    ]);
                }
            
                if ($lastCashRegister->status === 'opened') {
                    // Vérifier la date du dernier enregistrement de cash register
                    $today = Carbon::now()->startOfDay();
                    $openingDate = Carbon::parse($lastCashRegister->opening_date)->startOfDay();
            
                    if ($openingDate->equalTo($today)) {
                        // La date de clôture du dernier enregistrement de cash register est aujourd'hui
                        return redirect()->route('cashier.dashboard');
                    } else {
                        return redirect()->route('cashier.cloture_caisse');
                    }
                }
            }

        }
        else{
            Toastr::error('Erreur, Nom d\'utilisateur ou mot de passe incorrect:)','Error');
            return redirect()->route('login');
        }

    }

    public function checkCashRegisterSession($user)
    {
        // Vérifier le rôle de l'utilisateur et récupérer l'account et le branche_id


        $cdfId = Cashier::where('user_id', Auth::user()->id)
            ->where('currency', 'CDF')
            ->value('id');

        $usdId = Cashier::where('user_id', Auth::user()->id)
            ->where('currency', 'USD')
            ->value('id');

        // Déterminer la date de session à utiliser
        $userSessionDate = $user->last_session_date ?? CashRegister::where('cashier_id',$cdfId)
            ->orderBy('closing_date', 'desc')
            ->value('closing_date');
          
        if ($userSessionDate == null) {
            return 'openNewSession';
        }
        // Vérifier si la session de caisse de la journée précédente a été clôturée
        $cashRegisterLastSessionDate = CashRegister::where('cashier_id',$cdfId)
            ->where('status', 'closed')
            ->orderBy('closing_date', 'desc')
            ->value('closing_date');

        if ($userSessionDate > $cashRegisterLastSessionDate) {
            $SessionDate = $cashRegisterLastSessionDate;
            // Mettre à jour $user->last_session_date avec $cashRegisterLastSessionDate
            User::where('id', $user->id)->update(['last_session_date' => $cashRegisterLastSessionDate]);
        }
        else {
            $SessionDate = $userSessionDate;

        }

        $lastClosedRegister = CashRegister::where('cashier_id',$cdfId)
            ->where('status', 'closed')
            ->whereDate('closing_date', $SessionDate)
            ->latest('opening_date')
            ->latest('id')
            ->first();

        // Vérifier si l'utilisateur s'est déconnecté pendant la journée d'aujourd'hui
        $todayRegister = CashRegister::where('cashier_id',$cdfId)
            ->where('status', 'opened')
            ->whereDate('opening_date', Carbon::today()->toDateString())
            ->whereNotNull('logout_time')
            ->latest('opening_date')
            ->latest('id')
            ->first();

        // Seuil de temps en minutes
        $thresholdMinutes = 60;

        // Seuil de temps d'inactivité en minutes
        $inactivityThresholdMinutes = 30;

        if ($lastClosedRegister != null) {
            // La session de caisse de la journée précédente a été clôturée, ouvrir une nouvelle session pour la journée d'aujourd'hui
            // Vérifier si la dernière session clôturée dépasse le seuil de temps
            $lastClosedDate = Carbon::parse($lastClosedRegister->closing_date);
            $currentTime = Carbon::now();
            $timeDifference = $currentTime->diffInMinutes($lastClosedDate);

            if ($timeDifference > $thresholdMinutes) {
                // La dernière session clôturée dépasse le seuil de temps, ouvrir une nouvelle session
                return 'openNewSession';
            } else {
                // La dernière session clôturée est récente, garder la session existante
                return 'keepSessionOpen';
            }

        } elseif ($todayRegister != null) {
            // Vérifier la durée d'inactivité pour la session ouverte aujourd'hui
            $openingDate = Carbon::parse($todayRegister->opening_date);
            $logoutTime = Carbon::parse($todayRegister->logout_time);
            $inactivityDuration = $openingDate->diffInMinutes($logoutTime);

            if ($inactivityDuration > $inactivityThresholdMinutes) {
                // La durée d'inactivité dépasse le seuil, fermer la session et ouvrir une nouvelle session
                return 'openNewSession';
            } else {
                // La durée d'inactivité est inférieure au seuil, garder la session ouverte
                return 'keepSessionOpen';
            }

        } else {
            // La session de caisse de la journée précédente n'a pas été clôturée, rediriger vers une page d'erreur ou effectuer une autre action appropriée
            return false;
        }
    }

    public function todayDate(){
        Carbon::setLocale('fr');
        $todayDate = Carbon::now()->format('Y-m-d H:i:s');
        return $todayDate;
    }

    private function openCashRegisterSession($account, $branche_id,$dernierSoldeFc,$dernierSoldeUs,$dateCloture,$dateOuverture)
    {
     
        $todayDate = $this->todayDate();
        $transactions = DB::table('transactions')
        ->join('branches','transactions.branche_id','branches.id')
        ->select('transactions.*','branches.btownship','branches.user_id')
        ->whereDate('transactions.created_at', Carbon::today()->toDateString())
        ->get();

        $balanceAccount = new Statistiques;
        if (Auth::user()->role_name == "Manager") {
            $balance_agence = $balanceAccount->balance_agence();
        }
        elseif (Auth::user()->role_name == "Cashier") {
            $balance_agence = $balanceAccount->getCashierBalance();
        }

        $account_cdf = Account::where('branche_id',$branche_id)->where('user_id',Auth::user()->id)->where('currency','CDF')->first();
        $account_usd = Account::where('branche_id',$branche_id)->where('user_id',Auth::user()->id)->where('currency','USD')->first();

        $agence_cdf = $balance_agence['solde_cdf'];
        $agence_usd = $balance_agence['solde_usd'];

        $credit_cdf = Transaction::where('branche_id',$branche_id)->whereDate('created_at', Carbon::today()->toDateString())->where('user_id',Auth::user()->id)->where('currency_id','1')->where('action','credit')->where('impact','caisse')->sum('amount');
        $debit_cdf = Transaction::where('branche_id',$branche_id)->whereDate('created_at', Carbon::today()->toDateString())->where('user_id',Auth::user()->id)->where('currency_id','1')->where('action','debit')->where('impact','caisse')->sum('amount');
        $credit_usd = Transaction::where('branche_id',$branche_id)->whereDate('created_at', Carbon::today()->toDateString())->where('user_id',Auth::user()->id)->where('currency_id','2')->where('action','credit')->where('impact','caisse')->sum('amount');
        $debit_usd = Transaction::where('branche_id',$branche_id)->whereDate('created_at', Carbon::today()->toDateString())->where('user_id',Auth::user()->id)->where('currency_id','2')->where('action','debit')->where('impact','caisse')->sum('amount');

        $total_trx = Transaction::where('branche_id',$branche_id)->whereDate('created_at', Carbon::today()->toDateString())->where('user_id',Auth::user()->id)->where('impact','caisse')->count();
        $total_credit = Transaction::where('branche_id',$branche_id)->whereDate('created_at', Carbon::today()->toDateString())->where('user_id',Auth::user()->id)->where('action','credit')->where('impact','caisse')->count();
        $total_debit = Transaction::where('branche_id',$branche_id)->whereDate('created_at', Carbon::today()->toDateString())->where('user_id',Auth::user()->id)->where('action','debit')->where('impact','caisse')->count();
        $fees_usd = Transaction::where('branche_id',$branche_id)->whereDate('created_at', Carbon::today()->toDateString())->where('user_id',Auth::user()->id)->where('currency_id','2')->where('impact','caisse')->sum('fees');
        $fees_cdf = Transaction::where('branche_id',$branche_id)->whereDate('created_at', Carbon::today()->toDateString())->where('user_id',Auth::user()->id)->where('currency_id','1')->where('impact','caisse')->sum('fees');
        if (Auth::user()->role_name == "Manager") {
            return view('manager.branche.ouverture', 
            compact('transactions','todayDate','account_cdf','account_usd',
            'credit_cdf','debit_cdf','credit_usd','debit_usd','total_trx'
            ,'total_credit','total_debit','fees_cdf','fees_usd','agence_cdf','agence_usd',
            'dateCloture','dateOuverture','dernierSoldeFc','dernierSoldeUs'
            ));
        }
        elseif (Auth::user()->role_name == "Cashier") {
            return view('cashier.branche.ouverture', 
            compact('transactions','todayDate','account_cdf','account_usd',
            'credit_cdf','debit_cdf','credit_usd','debit_usd','total_trx'
            ,'total_credit','total_debit','fees_cdf','fees_usd','agence_cdf','agence_usd',
            'dateCloture','dateOuverture','dernierSoldeFc','dernierSoldeUs'
            ));
        }
    }

    public function logout(Request $request)
    {
        $user = Auth::user();
        $telephone = $user->phone_number;
    
        $dt = Carbon::now();
        $todayDate = $dt->toDayDateTimeString();
    
        $clientIP = request()->ip(); 
    
        $activityLog = [
            'name' => $user->firstname." ".$user->lastname,
            'user_id' => $user->id,
            'email' => $user->email,
            'description' => 'Déconnecté',
            'date_time' => $todayDate,
            'ipadresse' => $clientIP,
        ];
    
        $user_status = [
            'user_status' => 'Hors ligne',
        ];
    
        DB::table('activity_logs')->insert($activityLog);
        DB::table('users')->where('phone_number', $telephone)->update($user_status);
    
        
        // Récupérer l'enregistrement de la session de caisse en cours


        $cdfId = Cashier::where('user_id', Auth::user()->id)
            ->where('currency', 'CDF')
            ->value('id');

        $usdId = Cashier::where('user_id', Auth::user()->id)
            ->where('currency', 'USD')
            ->value('id');

        $sessionCDF = CashRegister::where('cashier_id',$cdfId)
        ->whereDate('opening_date', $user->last_session_date)
        ->latest('opening_date')
        ->latest('id')
        ->first();
    
        
        if ($sessionCDF) {
            $sessionCDF->logout_time = Carbon::now();
            $sessionCDF->save();
        }

        $sessionUSD = CashRegister::where('cashier_id',$usdId)
        ->whereDate('opening_date', $user->last_session_date)
        ->latest('opening_date')
        ->latest('id')
        ->first();

        if ($sessionUSD) {
            $sessionUSD->logout_time = Carbon::now();
            $sessionUSD->save();
        }
     
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        $user->last_session_date = Carbon::now(); // Utilisez la date actuelle ou une autre date pertinente
        $user->save();
        Auth::logout();
        Toastr::success('Déconnecté avec succès :)','Success');
        return redirect()->route('login');
    }

}
