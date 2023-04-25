<?php

namespace App\Http\Controllers;

use App\Http\Controllers\API\Initialize;
use App\Http\Controllers\API\Statistiques;
use App\Models\bank_account;
use App\Models\branch;
use App\Models\etirroir;
use App\Models\ewallet;
use App\Models\tirroir_account;
use App\Models\Transaction;
use Illuminate\Http\Request;
use Carbon\Carbon;
use PDF;
use App\Models\User;
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
        $todayDate = $this->todayDate();
        $date = Carbon::now();
        $today = $date->format('d-m-Y');
   
        $day = Transaction::select(DB::raw('DATE_FORMAT(created_at, "%d") as date'))
                ->groupBy(DB::raw('DATE_FORMAT(created_at, "%d")'))
                ->pluck('date');
        $depotcdf = Transaction::select(DB::raw("SUM(amount) as amount"))
                ->where(['type'=>'depot','currency_id'=>1])
                ->groupBy(DB::raw("Date(created_at)"))
                ->pluck('amount');

        $retraitcdf = Transaction::select(DB::raw("SUM(amount) as amount"))
                ->where(['type'=>'retrait','currency_id'=>1])
                ->groupBy(DB::raw("Date(created_at)"))
                ->pluck('amount');

        $transfertcdf = Transaction::select(DB::raw("SUM(amount) as amount"))
                ->where(['type'=>'transfert','currency_id'=>1])
                ->groupBy(DB::raw("Date(created_at)"))
                ->pluck('amount');

        $depotusd = Transaction::select(DB::raw("SUM(amount) as amount"))
                ->where(['type'=>'depot','currency_id'=>2])
                ->groupBy(DB::raw("Date(created_at)"))
                ->pluck('amount');

        $retraitusd = Transaction::select(DB::raw("SUM(amount) as amount"))
                ->where(['type'=>'retrait','currency_id'=>2])
                ->groupBy(DB::raw("Date(created_at)"))
                ->pluck('amount');

        $transfertusd = Transaction::select(DB::raw("SUM(amount) as amount"))
                ->where(['type'=>'transfert','currency_id'=>2])
                ->groupBy(DB::raw("Date(created_at)"))
                ->pluck('amount');

        $depot_count = Transaction::where(['type'=>'depot'])->whereDate('created_at', Carbon::today()->toDateString())->count();
        $retrait_count = Transaction::where(['type'=>'retrait'])->whereDate('created_at', Carbon::today()->toDateString())->count();
        $transfert_count = Transaction::where(['type'=>'transfert'])->whereDate('created_at', Carbon::today()->toDateString())->count();
        $transaction_count = Transaction::whereDate('created_at', Carbon::today()->toDateString())->count();

        $total_count = $depot_count + $retrait_count + $transfert_count;

        if ($total_count == 0) {
            $percent_depot_count = number_format(0,2);
            $percent_retrait_count = number_format(0,2);
            $percent_transfert_count = number_format(0,2);
        }
        else {
            $percent_depot_count = number_format(($depot_count * 100) / ($total_count),2);
            $percent_retrait_count = number_format(($retrait_count * 100) / ($total_count),2);
            $percent_transfert_count = number_format(($transfert_count * 100) / ($total_count),2);
        }
        
        $initialize = new Statistiques;
        $total_agence = $initialize->total_agence();
        $total_user = $initialize->total_user();
        $total_client = $initialize->total_client();
        $total_transaction = $initialize->total_transaction();
        $total_depot = $initialize->total_depot();
        $depot_cdf = $total_depot['depot_cdf'];
        $depot_usd = $total_depot['depot_usd'];
        $total_retrait = $initialize->total_retrait();
        $retrait_cdf = $total_retrait['retrait_cdf'];
        $retrait_usd = $total_retrait['retrait_usd'];
        $total_transfert = $initialize->total_transfert();
        $transfert_cdf = $total_transfert['transfert_cdf'];
        $transfert_usd = $total_transfert['transfert_usd'];
        $total_revenu = $initialize->revenu_global();
        $revenu_cdf = $total_revenu['revenu_cdf'];
        $revenu_usd = $total_revenu['revenu_usd'];
        $balance_agence = $initialize->balance_agence();
        $agence_cdf = $balance_agence['solde_cdf'];
        $agence_usd = $balance_agence['solde_usd'];
        $balance_wallet = $initialize->balance_wallet();
        $wallet_cdf = $balance_wallet['wallet_cdf'];
        $wallet_usd = $balance_wallet['wallet_usd'];
        return view('admin.index', compact('total_agence','total_user',
        'total_client','total_transaction','depot_cdf','depot_usd',
        'retrait_cdf','retrait_usd','revenu_cdf','revenu_usd',
        'agence_cdf','agence_usd','wallet_cdf','wallet_usd','day','depotcdf','retraitcdf','transfertcdf','depotusd','retraitusd','transfertusd',
        'percent_depot_count','percent_retrait_count','percent_transfert_count', 'today'
    ));
    }
    public function manager(){
        /*
        $hour = Transaction::select(DB::raw("Hour(created_at) as hour"))
                ->whereDate('created_at', Carbon::yesterday()->toDateString())
                ->groupBy(DB::raw("Hour(created_at)"))
                ->pluck('hour');
        $depot = Transaction::select(DB::raw("SUM(amount) as count"))
                ->whereDate('created_at', Carbon::yesterday()->toDateString())
                ->where('type', 'depot')
                ->groupBy(DB::raw("Hour(created_at)"))
                ->pluck('count');

        $retrait = Transaction::select(DB::raw("SUM(amount) as count"))
                ->whereDate('created_at', Carbon::yesterday()->toDateString())
                ->where('type', 'retrait')
                ->groupBy(DB::raw("Hour(created_at)"))
                ->pluck('count');
        */
        $todayDate = $this->todayDate();
        $date = Carbon::now();
        $today = $date->format('d-m-Y');

        $initialize = new Initialize;
        $branche_id = $initialize->branche_id(Auth::user()->id);
   
        $day = Transaction::select(DB::raw('DATE_FORMAT(created_at, "%d") as date'),'branche_id')
        ->where(['branche_id'=>$branche_id])
                ->groupBy(DB::raw('DATE_FORMAT(created_at, "%d")'),'branche_id')
                ->pluck('date');
        $depotcdf = Transaction::select(DB::raw("SUM(amount) as amount"))
                ->where(['type'=>'depot','currency_id'=>1,'branche_id'=>$branche_id])
                ->groupBy(DB::raw("Date(created_at)"))
                ->pluck('amount');

        $retraitcdf = Transaction::select(DB::raw("SUM(amount) as amount"))
                ->where(['type'=>'retrait','currency_id'=>1,'branche_id'=>$branche_id])
                ->groupBy(DB::raw("Date(created_at)"))
                ->pluck('amount');

        $transfertcdf = Transaction::select(DB::raw("SUM(amount) as amount"))
                ->where(['type'=>'transfert','currency_id'=>1,'branche_id'=>$branche_id])
                ->groupBy(DB::raw("Date(created_at)"))
                ->pluck('amount');

        $depotusd = Transaction::select(DB::raw("SUM(amount) as amount"))
                ->where(['type'=>'depot','currency_id'=>2,'branche_id'=>$branche_id])
                ->groupBy(DB::raw("Date(created_at)"))
                ->pluck('amount');

        $retraitusd = Transaction::select(DB::raw("SUM(amount) as amount"))
                ->where(['type'=>'retrait','currency_id'=>2,'branche_id'=>$branche_id])
                ->groupBy(DB::raw("Date(created_at)"))
                ->pluck('amount');

        $transfertusd = Transaction::select(DB::raw("SUM(amount) as amount"))
                ->where(['type'=>'transfert','currency_id'=>2,'branche_id'=>$branche_id])
                ->groupBy(DB::raw("Date(created_at)"))
                ->pluck('amount');

        $depot_count = Transaction::where(['type'=>'depot','branche_id'=>$branche_id])->whereDate('created_at', Carbon::today()->toDateString())->count();
        $retrait_count = Transaction::where(['type'=>'retrait','branche_id'=>$branche_id])->whereDate('created_at', Carbon::today()->toDateString())->count();
        $transfert_count = Transaction::where(['type'=>'transfert','branche_id'=>$branche_id])->whereDate('created_at', Carbon::today()->toDateString())->count();
        $transaction_count = Transaction::whereDate('created_at', Carbon::today()->toDateString())->where(['branche_id'=>$branche_id])->count();

        $total_count = $depot_count + $retrait_count + $transfert_count;
        if ($total_count == 0) {
            $percent_depot_count = number_format(0,2);
            $percent_retrait_count = number_format(0,2);
            $percent_transfert_count = number_format(0,2);
        }
        else {
            $percent_depot_count = number_format(($depot_count * 100) / ($total_count),2);
            $percent_retrait_count = number_format(($retrait_count * 100) / ($total_count),2);
            $percent_transfert_count = number_format(($transfert_count * 100) / ($total_count),2);
        }

        $initialize = new Statistiques;
        $total_agence = $initialize->total_agence();
        $total_user = $initialize->total_user();
        $total_client = $initialize->total_client();
        $total_transaction = $initialize->total_transaction();
        $total_depot = $initialize->total_depot();
        $depot_cdf = $total_depot['depot_cdf'];
        $depot_usd = $total_depot['depot_usd'];
        $total_retrait = $initialize->total_retrait();
        $retrait_cdf = $total_retrait['retrait_cdf'];
        $retrait_usd = $total_retrait['retrait_usd'];
        $total_transfert = $initialize->total_transfert();
        $transfert_cdf = $total_transfert['transfert_cdf'];
        $transfert_usd = $total_transfert['transfert_usd'];
        $total_revenu = $initialize->total_revenu();
        $revenu_cdf = $total_revenu['revenu_cdf'];
        $revenu_usd = $total_revenu['revenu_usd'];
        $balance_agence = $initialize->balance_agence();
        $agence_cdf = $balance_agence['solde_cdf'];
        $agence_usd = $balance_agence['solde_usd'];
        $balance_wallet = $initialize->balance_wallet();
        $wallet_cdf = $balance_wallet['wallet_cdf'];
        $wallet_usd = $balance_wallet['wallet_usd'];
        return view('manager.index', compact('total_agence','total_user',
        'total_client','total_transaction','depot_cdf','depot_usd','transaction_count',
        'retrait_cdf','retrait_usd','revenu_cdf','revenu_usd',
        'agence_cdf','agence_usd','wallet_cdf','wallet_usd',
        'day','depotcdf','retraitcdf','transfertcdf','depotusd','retraitusd','transfertusd',
        'percent_depot_count','percent_retrait_count','percent_transfert_count', 'today'
    ));
    }

    public function cashier(){
        $todayDate = $this->todayDate();
        $date = Carbon::now();
        $today = $date->format('d-m-Y');
   
        $day = Transaction::select(DB::raw('DATE_FORMAT(created_at, "%d-%m") as date'),'user_id')
                ->where(['user_id'=>Auth::user()->id])
                ->groupBy(DB::raw('DATE_FORMAT(created_at, "%d-%m")'),'user_id')
                ->pluck('date');
        $depotcdf = Transaction::select(DB::raw("SUM(amount) as amount"))
                ->where(['type'=>'depot','currency_id'=>1,'user_id'=>Auth::user()->id])
                ->groupBy(DB::raw("Date(created_at)"))
                ->pluck('amount');

        $retraitcdf = Transaction::select(DB::raw("SUM(amount) as amount"))
                ->where(['type'=>'retrait','currency_id'=>1,'user_id'=>Auth::user()->id])
                ->groupBy(DB::raw("Date(created_at)"))
                ->pluck('amount');

        $transfertcdf = Transaction::select(DB::raw("SUM(amount) as amount"))
                ->where(['type'=>'transfert','currency_id'=>1,'user_id'=>Auth::user()->id])
                ->groupBy(DB::raw("Date(created_at)"))
                ->pluck('amount');

        $depotusd = Transaction::select(DB::raw("SUM(amount) as amount"))
                ->where(['type'=>'depot','currency_id'=>2,'user_id'=>Auth::user()->id])
                ->groupBy(DB::raw("Date(created_at)"))
                ->pluck('amount');

        $retraitusd = Transaction::select(DB::raw("SUM(amount) as amount"))
                ->where(['type'=>'retrait','currency_id'=>2,'user_id'=>Auth::user()->id])
                ->groupBy(DB::raw("Date(created_at)"))
                ->pluck('amount');

        $transfertusd = Transaction::select(DB::raw("SUM(amount) as amount"))
                ->where(['type'=>'transfert','currency_id'=>2,'user_id'=>Auth::user()->id])
                ->groupBy(DB::raw("Date(created_at)"))
                ->pluck('amount');

        $depot_count = Transaction::where(['type'=>'depot','user_id'=>Auth::user()->id])->whereDate('created_at', Carbon::today()->toDateString())->count();
        $retrait_count = Transaction::where(['type'=>'retrait','user_id'=>Auth::user()->id])->whereDate('created_at', Carbon::today()->toDateString())->count();
        $transfert_count = Transaction::where(['type'=>'transfert','user_id'=>Auth::user()->id])->whereDate('created_at', Carbon::today()->toDateString())->count();
        $transaction_count = Transaction::whereDate('created_at', Carbon::today()->toDateString())->where(['user_id'=>Auth::user()->id])->count();

        $total_count = $depot_count + $retrait_count + $transfert_count;
        if ($total_count == 0) {
            $percent_depot_count = number_format(0,2);
            $percent_retrait_count = number_format(0,2);
            $percent_transfert_count = number_format(0,2);
        }
        else {
            $percent_depot_count = number_format(($depot_count * 100) / ($total_count),2);
            $percent_retrait_count = number_format(($retrait_count * 100) / ($total_count),2);
            $percent_transfert_count = number_format(($transfert_count * 100) / ($total_count),2);
        }
        $initialize = new Statistiques;
        $total_agence = $initialize->total_agence();
        $total_user = $initialize->total_user();
        $total_client = $initialize->total_client();
        $total_transaction = $initialize->total_transaction();
        $total_depot = $initialize->total_depot();
        $depot_cdf = $total_depot['depot_cdf'];
        $depot_usd = $total_depot['depot_usd'];
        $total_retrait = $initialize->total_retrait();
        $retrait_cdf = $total_retrait['retrait_cdf'];
        $retrait_usd = $total_retrait['retrait_usd'];
        $total_transfert = $initialize->total_transfert();
        $transfert_cdf = $total_transfert['transfert_cdf'];
        $transfert_usd = $total_transfert['transfert_usd'];
        $total_revenu = $initialize->total_revenu();
        $revenu_cdf = $total_revenu['revenu_cdf'];
        $revenu_usd = $total_revenu['revenu_usd'];
        $balance_agence = $initialize->getCashierBalance();
        $agence_cdf = $balance_agence['solde_cdf'];
        $agence_usd = $balance_agence['solde_usd'];
        $balance_wallet = $initialize->balance_wallet();
        $wallet_cdf = $balance_wallet['wallet_cdf'];
        $wallet_usd = $balance_wallet['wallet_usd'];
        return view('cashier.index', compact('total_agence','total_user',
        'total_client','total_transaction','depot_cdf','depot_usd','transaction_count',
        'retrait_cdf','retrait_usd','revenu_cdf','revenu_usd','transfert_cdf','transfert_usd',
        'agence_cdf','agence_usd','wallet_cdf','wallet_usd','day','depotcdf','retraitcdf','transfertcdf','depotusd','retraitusd','transfertusd',
        'percent_depot_count','percent_retrait_count','percent_transfert_count', 'today'
    ));
    }
}
