<?php

namespace App\Http\Controllers;

use App\Models\Admin;
use App\Models\BrancheWallet;
use App\Models\Cashier;
use App\Models\Customer;
use App\Models\User;
use Illuminate\Http\Request;
use Carbon\Carbon;
use App\Models\UserInfo;
use App\Models\Wallet;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class HomeController extends Controller
{
    public function todayDate(){
        Carbon::setLocale('fr');
        $todayDate = Carbon::now()->format('Y-m-d H:i:s');
        return $todayDate;
    }
    
    public function admin(){

        $currentDate = Carbon::now()->toDateString();

        $user = new User();
        $adminId = $user->getAdminId();
        // Obtenez l'objet Admin correspondant à l'ID de l'admin
        $admin = Admin::find($adminId);

        // Obtenez les soldes des caissiers des agences filiales pour CDF et USD
        $cdfBranchesBalances = $admin->getCdfBranchesBalances();
        $usdBranchesBalances = $admin->getUsdBranchesBalances();

        // Obtenez les soldes des agences filiales pour CDF et USD
        $sommeSoldesCDF = Wallet::whereIn('agency_id', $admin->agencesFiliales()->pluck('id'))
        ->where('currency', 'CDF')
        ->sum('balance');
    
        $sommeSoldesUSD = Wallet::whereIn('agency_id', $admin->agencesFiliales()->pluck('id'))
        ->where('currency', 'USD')
        ->sum('balance');

        $soldeGlobalCDF = $cdfBranchesBalances + $sommeSoldesCDF;
        $soldeGlobalUSD = $usdBranchesBalances + $sommeSoldesUSD;

        $userInfo = UserInfo::where('user_id',Auth::user()->id)->first();
        
        $balances = Wallet::select(
            DB::raw("SUM(CASE WHEN currency = 'CDF' THEN balance END) AS balance_cdf"),
            DB::raw("SUM(CASE WHEN currency = 'USD' THEN balance END) AS balance_usd")
        )
        ->where('agency_id','1')
        ->get();

        $balanceCDF = $balances->first()->balance_cdf;
        $balanceUSD = $balances->first()->balance_usd;
   
        $transactions = DB::table('transactions')
        
        ->whereDate('created_at', $currentDate)
            ->selectRaw('count(*) as total')
            ->selectRaw("count(case when category = 'Retrait' then 1 end) as retrait")
            ->selectRaw("count(case when category = 'Dépôt' then 1 end) as depot")
            ->selectRaw("count(case when category = 'Transfert' then 1 end) as transfert")
            ->first();

           

        $retrait_cdf= DB::table("transactions")->whereDate('created_at', $currentDate)->where(['currency'=>'CDF','category'=>'Retrait'])->sum('amount');
        $retrait_usd= DB::table("transactions")->whereDate('created_at', $currentDate)->where(['currency'=>'USD','category'=>'Retrait'])->sum('amount');
        $depot_cdf= DB::table("transactions")->whereDate('created_at', $currentDate)->where(['currency'=>'CDF','category'=>'Dépôt'])->sum('amount');
        $depot_usd= DB::table("transactions")->whereDate('created_at', $currentDate)->where(['currency'=>'USD','category'=>'Dépôt'])->sum('amount');
        $transfert_cdf= DB::table("transactions")->whereDate('created_at', $currentDate)->where(['currency'=>'CDF','category'=>'Transfert'])->sum('amount');
        $transfert_usd= DB::table("transactions")->whereDate('created_at', $currentDate)->where(['currency'=>'USD','category'=>'Transfert'])->sum('amount');
      
        $customers = Customer::count();

        return view('backend.dashboard.admin',
        compact(
            'transactions','customers','retrait_cdf','retrait_usd',
            'depot_cdf','depot_usd','cdfBranchesBalances','usdBranchesBalances',
            'transfert_cdf','transfert_usd','balanceCDF','balanceUSD','soldeGlobalCDF','soldeGlobalUSD'));
    }

    public function manager(){
        $currentDate = Carbon::now()->toDateString();

        $userInfo = UserInfo::where('user_id',Auth::user()->id)->first();
        
        $balances = Wallet::select(
            DB::raw("SUM(CASE WHEN currency = 'CDF' THEN balance END) AS balance_cdf"),
            DB::raw("SUM(CASE WHEN currency = 'USD' THEN balance END) AS balance_usd")
        )->where('agency_id',$userInfo->agency_id)
        ->get();

        $cashierBalances = Cashier::select(
            DB::raw("SUM(CASE WHEN currency = 'CDF' THEN balance END) AS balance_cdf"),
            DB::raw("SUM(CASE WHEN currency = 'USD' THEN balance END) AS balance_usd")
        )->where('agency_id',$userInfo->agency_id)
        ->get();

        $balanceCDF = $balances->first()->balance_cdf;
        $balanceUSD = $balances->first()->balance_usd;

        $cashierCDF = $cashierBalances->first()->balance_cdf;
        $cashierUSD = $cashierBalances->first()->balance_usd;
   
        $transactions = DB::table('transactions')
        ->whereDate('created_at', $currentDate)
        ->where('agence_id',$userInfo->agency_id)
            ->selectRaw('count(*) as total')
            ->selectRaw("count(case when category = 'Retrait' then 1 end) as retrait")
            ->selectRaw("count(case when category = 'Dépôt' then 1 end) as depot")
            ->selectRaw("count(case when category = 'Transfert' then 1 end) as transfert")
            ->first();

        $retrait_cdf= DB::table("transactions")->where('agence_id',$userInfo->agency_id)->whereDate('created_at', $currentDate)->where(['currency'=>'CDF','category'=>'Retrait'])->sum('amount');
        $retrait_usd= DB::table("transactions")->where('agence_id',$userInfo->agency_id)->whereDate('created_at', $currentDate)->where(['currency'=>'USD','category'=>'Retrait'])->sum('amount');
        $depot_cdf= DB::table("transactions")->where('agence_id',$userInfo->agency_id)->whereDate('created_at', $currentDate)->where(['currency'=>'CDF','category'=>'Dépôt'])->sum('amount');
        $depot_usd= DB::table("transactions")->where('agence_id',$userInfo->agency_id)->whereDate('created_at', $currentDate)->where(['currency'=>'USD','category'=>'Dépôt'])->sum('amount');
        $transfert_cdf= DB::table("transactions")->where('agence_id',$userInfo->agency_id)->whereDate('created_at', $currentDate)->where(['currency'=>'CDF','category'=>'Transfert'])->sum('amount');
        $transfert_usd= DB::table("transactions")->where('agence_id',$userInfo->agency_id)->whereDate('created_at', $currentDate)->where(['currency'=>'USD','category'=>'Transfert'])->sum('amount');
      
        $customers = Customer::count();

        return view('backend.dashboard.manager',compact('transactions','customers','retrait_cdf','retrait_usd','depot_cdf','depot_usd','transfert_cdf','transfert_usd','balanceCDF','balanceUSD'));

    }

    public function cashier(){
        $currentDate = Carbon::now()->toDateString();

        $userInfo = UserInfo::where('user_id',Auth::user()->id)->first();

        $cashier = Cashier::where('user_id',Auth::user()->id)->get();

        
        $cashier_2 = Cashier::where('user_id', Auth::user()->id)->first();

        $transactions = $cashier_2->transactions()
            ->whereDate('created_at', $currentDate)
            ->selectRaw('count(*) as total')
            ->selectRaw("coalesce(sum(case when category = 'Retrait' then 1 end), 0) as retrait")
            ->selectRaw("coalesce(sum(case when category = 'Dépôt' then 1 end), 0) as depot")
            ->selectRaw("coalesce(sum(case when category = 'Transfert' then 1 end), 0) as transfert")
            ->first();



        $cashierBalances = Cashier::select(
            DB::raw("SUM(CASE WHEN currency = 'CDF' THEN balance END) AS balance_cdf"),
            DB::raw("SUM(CASE WHEN currency = 'USD' THEN balance END) AS balance_usd")
            )->where('user_id',Auth::user()->id)
            ->get();

        $cashierCDF = $cashierBalances->first()->balance_cdf;
        $cashierUSD = $cashierBalances->first()->balance_usd;
   
    

            $retrait_cdf = $cashier->flatMap(function ($caissier) use ($currentDate) {
                return $caissier->transactions()
                    ->whereDate('created_at', $currentDate)
                    ->where(['currency' => 'CDF', 'category' => 'Retrait'])
                    ->pluck('amount');
            })->sum();
            
            $retrait_usd = $cashier->flatMap(function ($caissier) use ($currentDate) {
                return $caissier->transactions()
                    ->whereDate('created_at', $currentDate)
                    ->where(['currency' => 'USD', 'category' => 'Retrait'])
                    ->pluck('amount');
            })->sum();
            
            $depot_cdf = $cashier->flatMap(function ($caissier) use ($currentDate) {
                return $caissier->transactions()
                    ->whereDate('created_at', $currentDate)
                    ->where(['currency' => 'CDF', 'category' => 'Dépôt'])
                    ->pluck('amount');
            })->sum();
            
            $depot_usd = $cashier->flatMap(function ($caissier) use ($currentDate) {
                return $caissier->transactions()
                    ->whereDate('created_at', $currentDate)
                    ->where(['currency' => 'USD', 'category' => 'Dépôt'])
                    ->pluck('amount');
            })->sum();
            
            $transfert_cdf = $cashier->flatMap(function ($caissier) use ($currentDate) {
                return $caissier->transactions()
                    ->whereDate('created_at', $currentDate)
                    ->where(['currency' => 'CDF', 'category' => 'Transfert'])
                    ->pluck('amount');
            })->sum();
            
            $transfert_usd = $cashier->flatMap(function ($caissier) use ($currentDate) {
                return $caissier->transactions()
                    ->whereDate('created_at', $currentDate)
                    ->where(['currency' => 'USD', 'category' => 'Transfert'])
                    ->pluck('amount');
            })->sum();
     

        return view('backend.dashboard.cashier',compact('transactions','retrait_cdf','retrait_usd','depot_cdf','depot_usd','transfert_cdf','transfert_usd','cashierCDF','cashierUSD'));

    }
}
