<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\__init__;
use App\Http\Controllers\Controller;
use App\Http\Controllers\GenerateIdController;
use App\Models\cash_register;
use App\Models\tirroir_account;
use Brian2694\Toastr\Facades\Toastr;
use Carbon\Carbon;
use Illuminate\Http\Request;
use App\Models\bank_account;
use App\Models\branch;
use App\Models\Transaction;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class CashRegisterController extends Controller
{
    public function opened(){
        $userid = Auth::User()->id;
        $initialize = new __init__;
        $response = $initialize->cashier_account_wallet($userid);
        $bank_user_id = $response->bank_user_id;
        $bank_account = DB::table('bank_accounts')->where('bank_user_id',$bank_user_id)->first();

        $bank_account_id = $bank_account->id;

        $data = [
            'cash_register' => 0,
        ];

        $session_update = DB::table('bank_accounts')->where('id',$bank_account_id)->update($data);


        dd($bank_account_id);
        
    }


    public function opened_store(Request $request){

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
            Toastr::success('Session de caisse ouverte','Success');
            return redirect()->route('admin.dashboard');
        }

    }

    public function closed(){

        $userid = Auth::User()->id;
        $initialize = new __init__;
        $closed = $initialize->cash_session();
        $report = $initialize->fond_precedent();
        $report_cdf_on_c = $report['report_cdf_on_c'];
        $report_usd_on_c = $report['report_usd_on_c'];
        $cash_register = $initialize->cash_register_detail();

        $users = DB::table('users')->where('id',$userid)->first();
        $phone_number = $users->phone_number;
        $bank_user = DB::table('bank_users')->where('phone_number',$phone_number)->first();
        $bank_account = DB::table('bank_accounts')->where('bank_user_id',$bank_user->id)->first();

        $fund_cdf_on_o = DB::table('cash_registers')->where('bank_account_id',$bank_account->id)->whereDate('opened_on', Carbon::today()->toDateString())->sum('fund_cdf_on_o');
        $fund_usd_on_o = DB::table('cash_registers')->where('bank_account_id',$bank_account->id)->whereDate('opened_on', Carbon::today()->toDateString())->sum('fund_usd_on_o');

        $solde_ouverture_cdf = $fund_cdf_on_o;
        $solde_ouverture_usd = $fund_usd_on_o;


        $encaissement_cdf = DB::table('transactions')->where('id_agent', $userid)->where('action', 'credit')->where('currency', 'CDF')->whereDate('created_at', Carbon::today()->toDateString())->sum('amount');
        $encaissement_usd = DB::table('transactions')->where('id_agent', $userid)->where('action', 'credit')->where('currency', 'USD')->whereDate('created_at', Carbon::today()->toDateString())->sum('amount');

        $decaissement_cdf = DB::table('transactions')->where('id_agent', $userid)->where('action', 'debit')->where('currency', 'CDF')->whereDate('created_at', Carbon::today()->toDateString())->sum('amount');
        $decaissement_usd = DB::table('transactions')->where('id_agent', $userid)->where('action', 'debit')->where('currency', 'USD')->whereDate('created_at', Carbon::today()->toDateString())->sum('amount');

        $solde_theorique_cdf = ($solde_ouverture_cdf + $encaissement_cdf) - $decaissement_cdf;
        $solde_theorique_usd = ($solde_ouverture_usd + $encaissement_usd) - $decaissement_usd;

        return view('admin.cash_register.closed', compact('closed','report_cdf_on_c','report_usd_on_c',
        'solde_ouverture_cdf',
        'solde_ouverture_usd',
        'encaissement_cdf',
        'encaissement_usd',
        'decaissement_cdf',
        'decaissement_usd',
        'solde_theorique_cdf',
        'solde_theorique_usd',
    ));
    }

    public function macaisse(){
        $userid = Auth::User()->id;

        $initialize = new __init__;
        $closed = $initialize->cash_session();
        
        $report = $initialize->fond_precedent();

        $report_cdf_on_c = $report['report_cdf_on_c'];
        $report_usd_on_c = $report['report_usd_on_c'];

        Carbon::setLocale('fr');
        $todayDate = Carbon::now()->format('Y-m-d H:i:s');

        
        $response = $initialize->cashier_account_wallet($userid);
        $bank_user_id = $response->bank_user_id;

        $transactions = DB::table('transactions')
        ->where('id_agent',$userid )
        ->whereDate('created_at', Carbon::today()->toDateString())
        ->get();


        $now = Carbon::now();
        $yesterday = Carbon::yesterday()->toDateString();
        $tomorrow = Carbon::tomorrow()->toDateString();
        $brancheTotal = branch::count();
        $transactionTotal = Transaction::whereDate('created_at', Carbon::today()->toDateString())->count();
        $userTotal = User::where('role_name','!=','Customer')->count();
        $tirroir_cdfTotal = tirroir_account::sum('balance_cdf');
        $tirroir_usdTotal = tirroir_account::sum('balance_usd');
        $bank_cdfTotal = bank_account::sum('balance_cdf');
        $bank_usdTotal = bank_account::sum('balance_usd');
        $customerTotal = User::where('role_name','=','Customer')->count();

        $tirroir_account = tirroir_account::whereDate('created_at', Carbon::yesterday()->toDateString())->where('bank_user_id',$bank_user_id)->first();
        $tirroir_account_count = tirroir_account::whereDate('created_at', Carbon::yesterday()->toDateString())->where('bank_user_id',$bank_user_id)->count();

        if ($tirroir_account_count >= 1) {
            $report_tirroir_cdfTotal = tirroir_account::whereDate('created_at', Carbon::yesterday()->toDateString())->where('bank_user_id',$bank_user_id)->sum('balance_cdf');
            $report_tirroir_usdTotal = tirroir_account::whereDate('created_at', Carbon::yesterday()->toDateString())->where('bank_user_id',$bank_user_id)->sum('balance_usd');
        }

        elseif ($tirroir_account_count == 0) {
            $report_tirroir_cdfTotal = 0;
            $report_tirroir_usdTotal = 0;
        }

        return view('admin.cash_register.macaisse',compact('brancheTotal','userTotal','customerTotal','transactionTotal',
        'tirroir_cdfTotal','tirroir_usdTotal','bank_cdfTotal','bank_usdTotal','closed',
        'report_cdf_on_c', 'report_usd_on_c','transactions'
        ));
    }
}
