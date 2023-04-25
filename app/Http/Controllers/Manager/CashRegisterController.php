<?php

namespace App\Http\Controllers\Manager;

use App\Http\Controllers\__init__;
use App\Http\Controllers\API\Initialize;
use App\Http\Controllers\API\Statistiques;
use App\Http\Controllers\CashRegisterAPI;
use App\Http\Controllers\Controller;
use App\Http\Controllers\GenerateIdController;
use App\Models\Account;
use App\Models\bank_account;
use App\Models\branch;
use App\Models\cash_register;
use App\Models\CashRegister;
use App\Models\tirroir_account;
use App\Models\Transaction;
use App\Models\User;
use Brian2694\Toastr\Facades\Toastr;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use RealRashid\SweetAlert\Facades\Alert;

class CashRegisterController extends Controller
{
    public function todayDate(){
        Carbon::setLocale('fr');
        $todayDate = Carbon::now()->format('Y-m-d H:i:s');
        return $todayDate;
    }
    public function cash_register_verify(){
        $user_id = Auth::user()->id;
        $account = DB::table('accounts')->where('user_id',$user_id)->where('currency','CDF')->first();
        if ($account == null) {
            # caisse fermée
            $response = [
                'success' => false,
            ];
            return $response;
        }
        else {
            $account_id = $account->id;
            // dd($account_id);
            $register = CashRegister::where('account_id', $account_id)->where('status','opened')->where('currency','CDF')->latest('updated_at')->first();
            // dd($register->status);
            if ($register == null) {
                # caisse fermée
                $response = [
                    'success' => false,
                ];
                return $response;
            }
            else {
                $account_status = $register->status;
                if ($account_status == "closed") {
                    # caisse fermée
                    $response = [
                        'success' => false,
                    ];
                    return $response;
                }
                elseif ($account_status == "opened") {
                    # caisse ouverte
                    $response = [
                        'success' => true,
                    ];
                    return $response;
                }
            }
            
        }
        
    }
    public function ouverture(){
        // dd(Auth::check());
        // $year = Carbon::now()->format('Y');
        // $month = Carbon::now()->format('m');
        // $day = Carbon::now()->format('d');
        // $dateClosed = Carbon::create($year, $month, $day, 16,35,59);
        // dd($dateClosed);
        // if (Carbon::now() == $dateClosed) {
        //     # code...
        // }
        // $formmatted =  Carbon::createFromFormat('Y-m-d', $now)->format('F j, Y');

        $todayDate = $this->todayDate();
        $transactions = DB::table('transactions')
        ->join('branches','transactions.branche_id','branches.id')
        ->select('transactions.*','branches.btownship','branches.user_id')
        ->whereDate('transactions.created_at', Carbon::today()->toDateString())
        ->get();
        $authorization = $this->cash_register_verify();
        $initialize = new Initialize;
        

        $balanceAccount = new Statistiques;
        if (Auth::user()->role_name == "Manager") {
            $balance_agence = $balanceAccount->balance_agence();
            $branche_id = $initialize->branche_id(Auth::user()->id);
        }
        elseif (Auth::user()->role_name == "Cashier") {
            $balance_agence = $balanceAccount->getCashierBalance();
            $agence = Account::where('user_id',Auth::user()->id)->first();
            $branche_id = $agence->branche_id;
        }
        $agence_cdf = $balance_agence['solde_cdf'];
        $agence_usd = $balance_agence['solde_usd'];

        if (Auth::user()->role_name == "Manager" && $branche_id == null) {
            Alert::error('Erreur!', 'Veuillez créer une agence!');
            return redirect()->back();
        }
        else {
            $account_cdf = Account::where('branche_id',$branche_id)->where('user_id',Auth::user()->id)->where('currency','CDF')->first();
            $account_usd = Account::where('branche_id',$branche_id)->where('user_id',Auth::user()->id)->where('currency','USD')->first();
            # On recherche s'il existe une session qui a été ouverte aujourd'hui.
            $getLastOpenedRegisterFc = CashRegister::where('account_id',$account_cdf->id)->where('currency','CDF')->where('branche_id',$branche_id)->where('status','opened')->whereDate('created_at', Carbon::today()->toDateString())->latest('opening_date')->first();
            $getLastOpenedRegisterUs = CashRegister::where('account_id',$account_usd->id)->where('currency','USD')->where('branche_id',$branche_id)->where('status','opened')->whereDate('created_at', Carbon::today()->toDateString())->latest('opening_date')->first();
            # Si non, on recherche la dernière session
            if ($getLastOpenedRegisterFc == null && $getLastOpenedRegisterUs == null) {
                // dd('ok');
                $LastRecordFc = CashRegister::where('account_id',$account_cdf->id)->where('currency','CDF')->where('branche_id',$branche_id)->latest('updated_at')->first();
                $LastRecordUs = CashRegister::where('account_id',$account_usd->id)->where('currency','USD')->where('branche_id',$branche_id)->latest('updated_at')->first();

                if ($LastRecordFc->status == "closed" && $LastRecordUs->status == "closed") {
                    $dernierSoldeFc = $LastRecordFc->closing_balance;
                    $dernierSoldeUs = $LastRecordUs->closing_balance;
                    $dateCloture    = $LastRecordFc->closing_date;
                    $dateOuverture  = $LastRecordFc->closing_date;
                    // dd($dernierSoldeUs);
                }
                elseif ($LastRecordFc->status != "closed") {
                    // dd('nozzz');
                    return response()->json(['success'=>false,'message' => "La dernière session n'a pas été clôturée, voudriez-vous la clôturer ou continuer?"]);
                }
            }
            else {
                // dd('no');
                # On recherche si il existe une session au cours de la journée qui a été clôturée.
                $getLastClosedRegisterFc = CashRegister::where('account_id',$account_cdf->id)->where('currency','CDF')->where('branche_id',$branche_id)->where('status','opened')->whereDate('created_at', Carbon::today()->toDateString())->latest('opening_date')->count();
                $getLastClosedRegisterUs = CashRegister::where('account_id',$account_usd->id)->where('currency','USD')->where('branche_id',$branche_id)->where('status','opened')->whereDate('created_at', Carbon::today()->toDateString())->latest('opening_date')->count();
                # Si aucune session n'a été clôturée, on pose la question si on doit continuer avec la session ou la clôturée.
                if ($getLastClosedRegisterFc > 1 && $getLastClosedRegisterUs > 1) {
                    $response = [
                        'success' => false,
                        'message' => "La dernière session n'a pas été clôturée, voudriez-vous continuer ou ouvrir une nouvelle session?",
                    ];
                    return $response;
                }
                # Si la dernière session a été clôturée,
                else {
                    $getLastClosedRegisterFc = CashRegister::where('account_id',$account_cdf->id)->where('currency','CDF')->where('branche_id',$branche_id)->where('status','opened')->whereDate('created_at', Carbon::today()->toDateString())->latest('opening_date')->first();
                    $getLastClosedRegisterUs = CashRegister::where('account_id',$account_usd->id)->where('currency','USD')->where('branche_id',$branche_id)->where('status','opened')->whereDate('created_at', Carbon::today()->toDateString())->latest('opening_date')->first();
                    $dernierSoldeFc = $getLastClosedRegisterFc->opening_balance;
                    $dernierSoldeUs = $getLastClosedRegisterUs->opening_balance;
                    $dateCloture    = $getLastClosedRegisterFc->opening_date;
                    $dateOuverture  = $getLastClosedRegisterFc->opening_date;
                }
            }
            
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
                'authorization','credit_cdf','debit_cdf','credit_usd','debit_usd','total_trx'
                ,'total_credit','total_debit','fees_cdf','fees_usd','agence_cdf','agence_usd',
                'dateCloture','dateOuverture','dernierSoldeFc','dernierSoldeUs'
                ));
            }
            elseif (Auth::user()->role_name == "Cashier") {
                return view('cashier.branche.ouverture', 
                compact('transactions','todayDate','account_cdf','account_usd',
                'authorization','credit_cdf','debit_cdf','credit_usd','debit_usd','total_trx'
                ,'total_credit','total_debit','fees_cdf','fees_usd','agence_cdf','agence_usd',
                'dateCloture','dateOuverture','dernierSoldeFc','dernierSoldeUs'
                ));
            }

            
        }
        
    }
    public function fond_ouverture(Request $request){
        $request->validate([
            'new_solde_cdf'   => 'required|string|max:255',
            'new_solde_usd'   => 'required|string|max:255',
        ]);
        $data = [
            'opening_balance'
        ];

        $initialize = new Initialize;
        $branche_id = $initialize->branche_id(Auth::user()->id);
        if (Auth::user()->role_name == "Cashier") {
            $agence = Account::where('user_id',Auth::user()->id)->first();
            $branche_id = $agence->branche_id;
        }
        $account_cdf = Account::where('branche_id',$branche_id)->where('user_id',Auth::user()->id)->where('currency','CDF')->first();
        $account_usd = Account::where('branche_id',$branche_id)->where('user_id',Auth::user()->id)->where('currency','USD')->first();
        $todayDate = $this->todayDate();


        // CashRegister::where('account_id',$account_cdf->id)->whereDate('opening_date', Carbon::today()->toDateString())->first();
        $count = CashRegister::where('account_id',$account_cdf->id)->where('status','closed')->whereDate('closing_date', Carbon::today()->toDateString())->count();
        
        if ($count >= 1) {
            $cash_1 = CashRegister::create([
                'account_id'   => $account_cdf->id,
                'branche_id'   => $branche_id,
                'opening_balance' => $request->new_solde_cdf,
                'currency'   => "CDF",
                'status'   => "opened",
                'opening_date'   => $todayDate,
                'created_at'   => $todayDate,
                'updated_at'   => $todayDate,
            ]);
    
            $cash_2 = CashRegister::create([
                'account_id'   => $account_usd->id,
                'branche_id'   => $branche_id,
                'opening_balance' => $request->new_solde_usd,
                'currency'   => "USD",
                'status'   => "opened",
                'opening_date'   => $todayDate,
                'created_at'   => $todayDate,
                'updated_at'   => $todayDate,
            ]);
            
        }
        else {
            $count = CashRegister::where('account_id',$account_cdf->id)->where('status','closed')->whereDate('opening_date', Carbon::today()->toDateString())->count();
            
            if ($count >= 1) {
                $tiroir_1 = CashRegister::where('account_id',$account_cdf->id)->where('status','closed')->whereDate('opening_date', Carbon::today()->toDateString())->first();
                $data_1 = [
                    'added_fund' => $tiroir_1->added_fund + $request->new_solde_cdf,
                    'status'   => "opened",
                    'opening_date'   => $todayDate,
                    'created_at'   => $todayDate,
                    'updated_at'   => $todayDate,
                ];
    
                $cash_1 = CashRegister::where('account_id',$account_cdf->id)->where('currency','CDF')->whereDate('opening_date', Carbon::today()->toDateString())->update($data_1);
                $tiroir_2 = CashRegister::where('account_id',$account_usd->id)->where('status','closed')->whereDate('opening_date', Carbon::today()->toDateString())->first();
            
                $data_2 = [
                    'added_fund' => $tiroir_2->added_fund + $request->new_solde_usd,
                    'status'   => "opened",
                    'opening_date'   => $todayDate,
                    'created_at'   => $todayDate,
                    'updated_at'   => $todayDate,
                ];
    
                $cash_2 = CashRegister::where('account_id',$account_usd->id)->where('currency','USD')->whereDate('opening_date', Carbon::today()->toDateString())->update($data_2);
            }
            else {
                $cash_1 = CashRegister::create([
                    'account_id'   => $account_cdf->id,
                    'opening_balance' => $request->new_solde_cdf,
                    'currency'   => "CDF",
                    'status'   => "opened",
                    'branche_id'   => $branche_id,
                    'opening_date'   => $todayDate,
                    'created_at'   => $todayDate,
                    'updated_at'   => $todayDate,
                ]);
        
                $cash_2 = CashRegister::create([
                    'account_id'   => $account_usd->id,
                    'opening_balance' => $request->new_solde_usd,
                    'currency'   => "USD",
                    'status'   => "opened",
                    'branche_id'   => $branche_id,
                    'opening_date'   => $todayDate,
                    'created_at'   => $todayDate,
                    'updated_at'   => $todayDate,
                ]);
            }
            
        }

        if ($cash_1 && $cash_2) {
            Alert::success('Succès', 'Caisse ouverte avec succè!');
            if (Auth::user()->role_name == "Cashier") {
                return redirect()->route('cashier.ouverture_caisse');
            }
            else {
                return redirect()->back();
            }
            
        }
    }
    public function cloture(){
        $todayDate = $this->todayDate();
        $transactions = DB::table('transactions')
        ->join('branches','transactions.branche_id','branches.id')
        ->select('transactions.*','branches.btownship','branches.user_id')
        ->whereDate('transactions.created_at', Carbon::today()->toDateString())
        // ->where('transactions.branche_id','branches.btownship','branches.user_id')
        ->get();
        $authorization = $this->cash_register_verify();
        // dd($authorization);
        $initialize = new Initialize;
        

        $balanceAccount = new Statistiques;
        if (Auth::user()->role_name == "Manager") {
            $balance_agence = $balanceAccount->balance_agence();
            $branche_id = $initialize->branche_id(Auth::user()->id);
        }
        elseif (Auth::user()->role_name == "Cashier") {
            $balance_agence = $balanceAccount->getCashierBalance();
            $agence = Account::where('user_id',Auth::user()->id)->first();
            $branche_id = $agence->branche_id;
        }
        
        
        $agence_cdf = $balance_agence['solde_cdf'];
        $agence_usd = $balance_agence['solde_usd'];

        if (Auth::user()->role_name == "Manager" && $branche_id == null) {
            Alert::error('Erreur!', 'Veuillez créer une agence!');
            return redirect()->back();
        }
        else {
            $account_cdf = Account::where('branche_id',$branche_id)->where('user_id',Auth::user()->id)->where('currency','CDF')->first();
            $account_usd = Account::where('branche_id',$branche_id)->where('user_id',Auth::user()->id)->where('currency','USD')->first();
            
            $countFc = CashRegister::where('account_id',$account_cdf->id)->where('currency','CDF')->where('branche_id',$branche_id)->where('status','opened')->latest('closing_date')->count();
            $countUs = CashRegister::where('account_id',$account_usd->id)->where('currency','USD')->where('branche_id',$branche_id)->where('status','opened')->latest('closing_date')->count();

            if ($countFc <= 1 && $countUs <= 1) {
                $getLastOpenedRegisterFc = CashRegister::where('account_id',$account_cdf->id)->where('currency','CDF')->where('branche_id',$branche_id)->where('status','opened')->latest('opening_date')->first();
                $getLastOpenedRegisterUs = CashRegister::where('account_id',$account_usd->id)->where('currency','USD')->where('branche_id',$branche_id)->where('status','opened')->latest('opening_date')->first();

                $dernierSoldeFc = $getLastOpenedRegisterFc->closing_balance;
                $dernierSoldeUs = $getLastOpenedRegisterUs->closing_balance;
                $dateCloture    = $getLastOpenedRegisterFc->closing_date;

                $openingSoldeFc = $getLastOpenedRegisterFc->opening_balance;
                $openingSoldeUs = $getLastOpenedRegisterUs->opening_balance;
                $dateOuverture  = $getLastOpenedRegisterFc->opening_date;

                $IDdernierSoldeFc = $getLastOpenedRegisterFc->id;
                $IDdernierSoldeUs = $getLastOpenedRegisterUs->id;
            }
            else {
                $getLastOpenedRegisterFc = CashRegister::where('account_id',$account_cdf->id)->where('currency','CDF')->where('branche_id',$branche_id)->where('status','closed')->latest('closing_date')->first();
                $getLastOpenedRegisterUs = CashRegister::where('account_id',$account_usd->id)->where('currency','USD')->where('branche_id',$branche_id)->where('status','closed')->latest('closing_date')->first();

                $dernierSoldeFc = $getLastOpenedRegisterFc->closing_balance;
                $dernierSoldeUs = $getLastOpenedRegisterUs->closing_balance;
                $dateCloture    = $getLastOpenedRegisterFc->closing_date;

                $openingSoldeFc = $getLastOpenedRegisterFc->opening_balance;
                $openingSoldeUs = $getLastOpenedRegisterUs->opening_balance;
                $dateOuverture  = $getLastOpenedRegisterFc->opening_date;

                $IDdernierSoldeFc = $getLastOpenedRegisterFc->id;
                $IDdernierSoldeUs = $getLastOpenedRegisterUs->id;
            }
            

            $credit_cdf = Transaction::where('branche_id',$branche_id)->whereDate('created_at', Carbon::yesterday()->toDateString())->where('user_id',Auth::user()->id)->where('currency_id','1')->where('action','credit')->where('impact','caisse')->sum('amount');
            $debit_cdf = Transaction::where('branche_id',$branche_id)->whereDate('created_at', Carbon::yesterday()->toDateString())->where('user_id',Auth::user()->id)->where('currency_id','1')->where('action','debit')->where('impact','caisse')->sum('amount');
            $credit_usd = Transaction::where('branche_id',$branche_id)->whereDate('created_at', Carbon::yesterday()->toDateString())->where('user_id',Auth::user()->id)->where('currency_id','2')->where('action','credit')->where('impact','caisse')->sum('amount');
            $debit_usd = Transaction::where('branche_id',$branche_id)->whereDate('created_at', Carbon::yesterday()->toDateString())->where('user_id',Auth::user()->id)->where('currency_id','2')->where('action','debit')->where('impact','caisse')->sum('amount');

        }
            
            
            $total_trx = Transaction::where('branche_id',$branche_id)->whereDate('created_at', Carbon::today()->toDateString())->where('user_id',Auth::user()->id)->where('impact','caisse')->count();
            $total_credit = Transaction::where('branche_id',$branche_id)->whereDate('created_at', Carbon::today()->toDateString())->where('user_id',Auth::user()->id)->where('action','credit')->where('impact','caisse')->count();
            $total_debit = Transaction::where('branche_id',$branche_id)->whereDate('created_at', Carbon::today()->toDateString())->where('user_id',Auth::user()->id)->where('action','debit')->where('impact','caisse')->count();
            $fees_usd = Transaction::where('branche_id',$branche_id)->whereDate('created_at', Carbon::today()->toDateString())->where('user_id',Auth::user()->id)->where('currency_id','2')->where('impact','caisse')->sum('fees');
            $fees_cdf = Transaction::where('branche_id',$branche_id)->whereDate('created_at', Carbon::today()->toDateString())->where('user_id',Auth::user()->id)->where('currency_id','1')->where('impact','caisse')->sum('fees');

            $solde_theorique_cdf = ($openingSoldeFc + $credit_cdf) - $debit_cdf;
            $solde_theorique_usd = ($openingSoldeUs + $credit_usd) - $debit_usd;
            if (Auth::user()->role_name == "Manager") {
                return view('manager.branche.cloture', 
                compact('transactions','todayDate','account_cdf','account_usd',
                'authorization','credit_cdf','debit_cdf','credit_usd','debit_usd','total_trx'
                ,'total_credit','total_debit','fees_cdf','fees_usd','agence_cdf','agence_usd',
                'dateCloture','dateOuverture','dernierSoldeFc','dernierSoldeUs','solde_theorique_usd',
                'solde_theorique_cdf','IDdernierSoldeFc','IDdernierSoldeUs','openingSoldeFc','openingSoldeUs'
                ));
            }
            elseif (Auth::user()->role_name == "Cashier") {
                return view('cashier.branche.cloture', 
                compact('transactions','todayDate','account_cdf','account_usd',
                'authorization','credit_cdf','debit_cdf','credit_usd','debit_usd','total_trx'
                ,'total_credit','total_debit','fees_cdf','fees_usd','agence_cdf','agence_usd',
                'dateCloture','dateOuverture','dernierSoldeFc','dernierSoldeUs','solde_theorique_usd',
                'solde_theorique_cdf','IDdernierSoldeFc','IDdernierSoldeUs','openingSoldeFc','openingSoldeUs'
                ));
            }
    }
  

    public function postcloture(Request $request){
        // dd($request->IDdernierSoldeFc);
        $soldeFC = $request->soldeFC;
        $ecartFC = $request->ecartFC;
        $banqueFC = $request->banqueFC;
        $reportFC = $request->reportFC;
        $soldeUSD = $request->soldeUSD;
        $ecartUSD = $request->ecartUSD;
        $banqueUSD = $request->banqueUSD;
        $reportUSD = $request->reportUSD;
        $IDdernierSoldeFc = $request->IDdernierSoldeFc;
        $IDdernierSoldeUs = $request->IDdernierSoldeUs;
        $data = [
            'soldeFC' => $request->soldeFC,
            'ecartFC' => $request->ecartFC,
            'banqueFC' => $request->banqueFC,
            'reportFC' => $request->reportFC,
            'soldeUSD' => $request->soldeUSD,
            'ecartUSD' => $request->ecartUSD,
            'banqueUSD' => $request->banqueUSD,
            'reportUSD' => $request->reportUSD,
        ];
        // dd($data);
        $initialize = new CashRegisterAPI;
        $authorization = $initialize->cloture($IDdernierSoldeFc,$IDdernierSoldeUs,$ecartFC,$banqueFC,$reportFC,$soldeUSD,$ecartUSD,$banqueUSD,$reportUSD);
        if ($authorization['success'] == true) {
            return response()->json(['message'=>$authorization['message'],'status'=>true]);
        }
        else{
            return response()->json(['message'=>$authorization['message'],'status'=>false]);
        }
    }
}
