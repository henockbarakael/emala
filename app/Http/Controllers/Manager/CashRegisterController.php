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
use App\Models\Cashier;
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
        
            $register = CashRegister::where('account_id', $account_id)->where('status','opened')->where('currency','CDF')->latest('updated_at')->first();
           
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
        
        $cdfId = Cashier::where('user_id', Auth::user()->id)
            ->where('currency', 'CDF')
            ->value('id');

        $usdId = Cashier::where('user_id', Auth::user()->id)
            ->where('currency', 'USD')
            ->value('id');
    

        $amountFc = CashRegister::where('cashier_id',$cdfId)->where('status','closed')->latest('opening_date')->first();
        $amountUs = CashRegister::where('cashier_id',$usdId)->where('status','closed')->latest('opening_date')->first();

        return view('cashier.branche.ouverture', [
            'user_id' => auth()->user()->id,
            'amount_cdf' => $amountFc->closing_balance,
            'amount_usd' => $amountUs->closing_balance
        ]);
    }

    public function fond_ouverture(Request $request){
        
        $request->validate([
            'new_solde_cdf'   => 'required|string|max:255',
            'new_solde_usd'   => 'required|string|max:255',
        ]);

        

        $todayDate = $this->todayDate();

        $cdfId = Cashier::where('user_id', Auth::user()->id)
            ->where('currency', 'CDF')
            ->value('id');

        $usdId = Cashier::where('user_id', Auth::user()->id)
            ->where('currency', 'USD')
            ->value('id');



        $count = CashRegister::where('cashier_id',$cdfId)->where('status','closed')->whereDate('closing_date', Carbon::today()->toDateString())->count();
        
        if ($count >= 1) {


            // Créez un enregistrement de caisse fictif
            $cash_1 = CashRegister::create([
                'cashier_id' => $cdfId,
                'agency_id' => 2,
                'opening_balance' => $request->new_solde_cdf,
                'currency' => 'CDF',
                'opening_date' => now(),
                'closing_date' => null,
                'logout_time' => now(),
                'status' => 'opened',
            ]);

            $cash_2 = CashRegister::create([
                'cashier_id' => $usdId,
                'agency_id' => 2,
                'opening_balance' => $request->new_solde_usd,
                'currency' => 'USD',
                'opening_date' => now(),
                'closing_date' => null,
                'logout_time' => now(),
                'status' => 'opened',
            ]);

            
        }
        else {
            $count = CashRegister::where('cashier_id',$cdfId)->where('status','closed')->whereDate('opening_date', Carbon::today()->toDateString())->count();
            
            if ($count >= 1) {
                $tiroir_1 = CashRegister::where('cashier_id',$cdfId)->where('status','closed')->whereDate('opening_date', Carbon::today()->toDateString())->first();
                $data_1 = [
                    'added_fund' => $tiroir_1->added_fund + $request->new_solde_cdf,
                    'status'   => "opened",
                    'opening_date'   => $todayDate,
                    'created_at'   => $todayDate,
                    'updated_at'   => $todayDate,
                ];
    
                $cash_1 = CashRegister::where('cashier_id',$cdfId)->where('currency','CDF')->whereDate('opening_date', Carbon::today()->toDateString())->update($data_1);
                $tiroir_2 = CashRegister::where('cashier_id',$usdId)->where('status','closed')->whereDate('opening_date', Carbon::today()->toDateString())->first();
            
                $data_2 = [
                    'added_fund' => $tiroir_2->added_fund + $request->new_solde_usd,
                    'status'   => "opened",
                    'opening_date'   => $todayDate,
                    'created_at'   => $todayDate,
                    'updated_at'   => $todayDate,
                ];
    
                $cash_2 = CashRegister::where('cashier_id',$usdId)->where('currency','USD')->whereDate('opening_date', Carbon::today()->toDateString())->update($data_2);
            }
            else {

                $cash_1 = CashRegister::create([
                    'cashier_id' => $cdfId,
                    'agency_id' => 2,
                    'opening_balance' => $request->new_solde_cdf,
                    'currency' => 'CDF',
                    'opening_date' => now(),
                    'closing_date' => null,
                    'logout_time' => now(),
                    'status' => 'opened',
                ]);
    
                $cash_2 = CashRegister::create([
                    'cashier_id' => $usdId,
                    'agency_id' => 2,
                    'opening_balance' => $request->new_solde_usd,
                    'currency' => 'USD',
                    'opening_date' => now(),
                    'closing_date' => null,
                    'logout_time' => now(),
                    'status' => 'opened',
                ]);

            }
            
        }


        if ($cash_1 && $cash_2) {
            $response = [
                'status' => true,
                'message' => 'Caisse ouverte avec succès!'
            ];
        } else {
            $response = [
                'status' => false,
                'message' => 'Une erreur s\'est produite lors de l\'ouverture de la caisse.'
            ];
        }
    
        return response()->json($response);
    }
    
    public function cloture(){
        $todayDate = $this->todayDate();

        $cashiers = new Cashier();

        $cdfBalance = $cashiers->getCdfBalance(Auth::user()->id);
        $usdBalance = $cashiers->getUsdBalance(Auth::user()->id);

        $cdfId = Cashier::where('user_id', Auth::user()->id)
            ->where('currency', 'CDF')
            ->value('id');

        $usdId = Cashier::where('user_id', Auth::user()->id)
            ->where('currency', 'USD')
            ->value('id');
    

        $getLastOpenedRegisterFc = CashRegister::where('cashier_id',$cdfId)->where('status','opened')->latest('opening_date')->first();
        $getLastOpenedRegisterUs = CashRegister::where('cashier_id',$usdId)->where('status','opened')->latest('opening_date')->first();


        $cashier_2 = Cashier::where('user_id', Auth::user()->id)->first();

        $lastDate = $cashier_2->transactions()
        ->where('status', 'Réussi')
        ->orderBy('created_at', 'desc')
        ->value('created_at');


        $openingDate = Carbon::parse($lastDate)->format('Y-m-d');
        $openingBalanceCDF = $getLastOpenedRegisterFc->opening_balance;
        $transactions = $cashier_2->transactions()
        ->where('status', 'Réussi')
        ->whereDate('created_at', $openingDate)
        ->get();


        $openingBalanceUSD = $getLastOpenedRegisterUs->opening_balance;

        $currentDate = Carbon::now()->toDateString();

        // $userInfo = UserInfo::where('user_id',Auth::user()->id)->first();

        $cashier = Cashier::where('user_id',Auth::user()->id)->get();

        
        $retrait_cdf = $cashier->flatMap(function ($caissier) use ($currentDate) {
            return $caissier->transactions()
                ->whereDate('created_at', $currentDate)
                ->where(['currency' => 'CDF', 'category' => 'Retrait', 'status' => 'Réussi'])
                ->pluck('amount');
        })->sum();
        
        $retrait_usd = $cashier->flatMap(function ($caissier) use ($currentDate) {
            return $caissier->transactions()
                ->whereDate('created_at', $currentDate)
                ->where(['currency' => 'USD', 'category' => 'Retrait', 'status' => 'Réussi'])
                ->pluck('amount');
        })->sum();
        
        $depot_cdf = $cashier->flatMap(function ($caissier) use ($currentDate) {
            return $caissier->transactions()
                ->whereDate('created_at', $currentDate)
                ->where(['currency' => 'CDF', 'category' => 'Dépôt', 'status' => 'Réussi'])
                ->pluck('amount');
        })->sum();
        
        $depot_usd = $cashier->flatMap(function ($caissier) use ($currentDate) {
            return $caissier->transactions()
                ->whereDate('created_at', $currentDate)
                ->where(['currency' => 'USD', 'category' => 'Dépôt', 'status' => 'Réussi'])
                ->pluck('amount');
        })->sum();
        
        $transfert_cdf = $cashier->flatMap(function ($caissier) use ($currentDate) {
            return $caissier->transactions()
                ->whereDate('created_at', $currentDate)
                ->where(['currency' => 'CDF', 'category' => 'Transfert', 'status' => 'Réussi'])
                ->pluck('amount');
        })->sum();
        
        $transfert_usd = $cashier->flatMap(function ($caissier) use ($currentDate) {
            return $caissier->transactions()
                ->whereDate('created_at', $currentDate)
                ->where(['currency' => 'USD', 'category' => 'Transfert', 'status' => 'Réussi'])
                ->pluck('amount');
        })->sum();


        $totalCreditCDF = $depot_cdf;
        $totalCreditUSD = $depot_usd;
        $totalDebitCDF = $retrait_cdf + $transfert_cdf;
        $totalDebitUSD = $retrait_usd + $transfert_usd;

        // Calculer le solde théorique
        $solde_theorique_cdf = $openingBalanceCDF + $totalCreditCDF - $totalDebitCDF;
        $solde_theorique_usd= $openingBalanceUSD + $totalCreditUSD - $totalDebitUSD;

  
        return view('cashier.branche.cloture', compact('solde_theorique_usd','solde_theorique_cdf','todayDate'));
    }
  

    public function postcloture(Request $request){
  
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
