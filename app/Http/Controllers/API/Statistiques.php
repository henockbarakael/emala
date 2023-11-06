<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\Account;
use App\Models\Branche;
use App\Models\Customer;
use App\Models\Deposit;
use App\Models\Transaction;
use App\Models\Transfer;
use App\Models\User;
use App\Models\Wallet;
use App\Models\Withdrawal;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class Statistiques extends Controller
{
    public function total_agence(){
        $total_branche = Branche::count();
        return $total_branche;
    }
    public function total_user(){
        $total_user = User::count();
        return $total_user;
    }
    public function total_client(){
        $total_customer = Customer::count();
        return $total_customer;
    }
    public function total_transaction(){
        $total_transaction = Transaction::whereDate('created_at', Carbon::today()->toDateString())->count();
        return $total_transaction;
    }
    public function total_depot(){
        $depot_cdf_1 = Deposit::whereDate('created_at', Carbon::today()->toDateString())->where('currency_id', 1)->sum('amount');
        $depot_cdf_2 = DB::connection('mysql2')->table('deposits')->whereDate('created_at', Carbon::today()->toDateString())->where('currency_id', 1)->sum('amount');
        $depot_cdf = $depot_cdf_1 + $depot_cdf_2;
        $depot_usd_1 = Deposit::whereDate('created_at', Carbon::today()->toDateString())->where('currency_id', 2)->sum('amount');
        $depot_usd_2 = DB::connection('mysql2')->table('deposits')->whereDate('created_at', Carbon::today()->toDateString())->where('currency_id', 2)->sum('amount');
        $depot_usd = $depot_usd_1 + $depot_usd_2;
        $data = [
            'depot_cdf' => $depot_cdf,
            'depot_usd' => $depot_usd,
        ];
        return $data;
    }
    public function total_retrait(){
        $retrait_cdf_1 = Withdrawal::whereDate('created_at', Carbon::today()->toDateString())->where('currency_id', 1)->sum('amount');
        $retrait_cdf_2 = DB::connection('mysql2')->table('withdrawals')->whereDate('created_at', Carbon::today()->toDateString())->where('currency_id', 1)->sum('amount');
        $retrait_cdf = $retrait_cdf_1 + $retrait_cdf_2;
        $retrait_usd_1 = Withdrawal::whereDate('created_at', Carbon::today()->toDateString())->where('currency_id', 2)->sum('amount');
        $retrait_usd_2 = DB::connection('mysql2')->table('withdrawals')->whereDate('created_at', Carbon::today()->toDateString())->where('currency_id', 2)->sum('amount');
        $retrait_usd = $retrait_usd_1 + $retrait_usd_2;
        $data = [
            'retrait_cdf' => $retrait_cdf,
            'retrait_usd' => $retrait_usd,
        ];
        return $data;
    }
    public function total_transfert(){
        $transfert_cdf_1 = Transfer::whereDate('created_at', Carbon::today()->toDateString())->where('currency_id', 1)->sum('amount');
        $transfert_cdf_2 = DB::connection('mysql2')->table('transfers')->whereDate('created_at', Carbon::today()->toDateString())->where('currency_id', 1)->sum('amount');
        $transfert_cdf = $transfert_cdf_1 + $transfert_cdf_2;
        $transfert_usd_1 = Transfer::whereDate('created_at', Carbon::today()->toDateString())->where('currency_id', 2)->sum('amount');
        $transfert_usd_2 = DB::connection('mysql2')->table('transfers')->whereDate('created_at', Carbon::today()->toDateString())->where('currency_id', 2)->sum('amount');
        $transfert_usd = $transfert_usd_1 + $transfert_usd_2;
        $data = [
            'transfert_cdf' => $transfert_cdf,
            'transfert_usd' => $transfert_usd,
        ];
        return $data;
    }
    public function total_revenu(){
        $depot_cdf_1 = Deposit::whereDate('created_at', Carbon::today()->toDateString())->where('currency_id', 1)->sum('fees');
        $depot_cdf_2 = DB::connection('mysql2')->table('deposits')->whereDate('created_at', Carbon::today()->toDateString())->where('currency_id', 1)->sum('fees');
        $depot_cdf = $depot_cdf_1 + $depot_cdf_2;
        $depot_usd_1 = Deposit::whereDate('created_at', Carbon::today()->toDateString())->where('currency_id', 2)->sum('fees');
        $depot_usd_2 = DB::connection('mysql2')->table('deposits')->whereDate('created_at', Carbon::today()->toDateString())->where('currency_id', 2)->sum('fees');
        $depot_usd = $depot_usd_1 + $depot_usd_2;

        $retrait_cdf_1 = Withdrawal::whereDate('created_at', Carbon::today()->toDateString())->where('currency_id', 1)->sum('fees');
        $retrait_cdf_2 = DB::connection('mysql2')->table('withdrawals')->whereDate('created_at', Carbon::today()->toDateString())->where('currency_id', 1)->sum('fees');
        $retrait_cdf = $retrait_cdf_1 + $retrait_cdf_2;
        $retrait_usd_1 = Withdrawal::whereDate('created_at', Carbon::today()->toDateString())->where('currency_id', 2)->sum('fees');
        $retrait_usd_2 = DB::connection('mysql2')->table('withdrawals')->whereDate('created_at', Carbon::today()->toDateString())->where('currency_id', 2)->sum('fees');
        $retrait_usd = $retrait_usd_1 + $retrait_usd_2;

        $transfert_cdf_1 = Transfer::whereDate('created_at', Carbon::today()->toDateString())->where('currency_id', 1)->sum('fees');
        $transfert_cdf_2 = DB::connection('mysql2')->table('transfers')->whereDate('created_at', Carbon::today()->toDateString())->where('currency_id', 1)->sum('fees');
        $transfert_cdf = $transfert_cdf_1 + $transfert_cdf_2;
        $transfert_usd_1 = Transfer::whereDate('created_at', Carbon::today()->toDateString())->where('currency_id', 2)->sum('fees');
        $transfert_usd_2 = DB::connection('mysql2')->table('transfers')->whereDate('created_at', Carbon::today()->toDateString())->where('currency_id', 2)->sum('fees');
        $transfert_usd = $transfert_usd_1 + $transfert_usd_2;

        $revenu_cdf = $depot_cdf + $retrait_cdf + $transfert_cdf;
        $revenu_usd = $depot_usd + $retrait_usd + $transfert_usd;

        $data = [
            'revenu_cdf' => $revenu_cdf,
            'revenu_usd' => $revenu_usd,
        ];
        return $data;
    }

    public function revenu_global(){
        $depot_cdf_1 = Deposit::where('currency_id', 1)->sum('fees');
        $depot_cdf_2 = DB::connection('mysql2')->table('deposits')->where('currency_id', 1)->sum('fees');
        $depot_cdf = $depot_cdf_1 + $depot_cdf_2;
        $depot_usd_1 = Deposit::where('currency_id', 2)->sum('fees');
        $depot_usd_2 = DB::connection('mysql2')->table('deposits')->where('currency_id', 2)->sum('fees');
        $depot_usd = $depot_usd_1 + $depot_usd_2;

        $retrait_cdf_1 = Withdrawal::where('currency_id', 1)->sum('fees');
        $retrait_cdf_2 = DB::connection('mysql2')->table('withdrawals')->where('currency_id', 1)->sum('fees');
        $retrait_cdf = $retrait_cdf_1 + $retrait_cdf_2;
        $retrait_usd_1 = Withdrawal::where('currency_id', 2)->sum('fees');
        $retrait_usd_2 = DB::connection('mysql2')->table('withdrawals')->where('currency_id', 2)->sum('fees');
        $retrait_usd = $retrait_usd_1 + $retrait_usd_2;

        $transfert_cdf_1 = Transfer::where('currency_id', 1)->sum('fees');
        $transfert_cdf_2 = DB::connection('mysql2')->table('transfers')->where('currency_id', 1)->sum('fees');
        $transfert_cdf = $transfert_cdf_1 + $transfert_cdf_2;
        $transfert_usd_1 = Transfer::where('currency_id', 2)->sum('fees');
        $transfert_usd_2 = DB::connection('mysql2')->table('transfers')->where('currency_id', 2)->sum('fees');
        $transfert_usd = $transfert_usd_1 + $transfert_usd_2;

        $revenu_cdf = $depot_cdf + $retrait_cdf + $transfert_cdf;
        $revenu_usd = $depot_usd + $retrait_usd + $transfert_usd;

        $data = [
            'revenu_cdf' => $revenu_cdf,
            'revenu_usd' => $revenu_usd,
        ];
        return $data;
    }
    public function balance_agence(){
        $solde_cdf = Account::join('branches','accounts.branche_id','branches.id')->
        select('accounts.balance AS balance')
        ->where('branches.user_id', Auth::user()->id)
        ->where('currency', 'CDF')->first();
        // dd($solde_cdf);
        $solde_usd = Account::join('branches','accounts.branche_id','branches.id')->
        select('accounts.balance AS balance')->where('branches.user_id', Auth::user()->id)->where('currency', 'USD')->first();
        if ($solde_cdf != null && $solde_usd != null) {
            $data = [
                'solde_cdf' => $solde_cdf->balance,
                'solde_usd' => $solde_usd->balance,
            ];
        }
        else {
            $data = [
                'solde_cdf' => 0,
                'solde_usd' => 0,
            ];
        }
        
        return $data;
    }
    public function getCashierBalance(){
        $user_id = Auth::user()->id;
        $balance_cdf = DB::table('accounts')->where('currency', 'CDF')->where('user_id', $user_id)->first();
        $balance_usd = DB::table('accounts')->where('currency', 'USD')->where('user_id', $user_id)->first();
        $data = [
            'solde_cdf' => $balance_cdf->amount,
            'solde_usd' => $balance_usd->amount,
        ];
        return $data;
    }
    public function balance_wallet(){
        $wallet_1 = Wallet::where('currency', 'CDF')->sum('balance');
        $wallet_2 = Wallet::where('currency', 'USD')->sum('balance');
        $data = [
            'wallet_cdf' => $wallet_1,
            'wallet_usd' => $wallet_2,
        ];
        return $data;
    }
}
