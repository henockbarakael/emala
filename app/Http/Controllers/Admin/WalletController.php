<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\__init__;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use RealRashid\SweetAlert\Facades\Alert;

class WalletController extends Controller
{
    public function create(){
        $initialize = new __init__;
        $closed = $initialize->cash_session();
        $report = $initialize->fond_precedent();
        $report_cdf_on_c = $report['report_cdf_on_c'];
        $report_usd_on_c = $report['report_usd_on_c'];
        return view('admin.wallet.emala.add', compact('closed','report_cdf_on_c','report_usd_on_c'));
    }

    public function store(Request $request){
        $request->validate([
            'wallet_type'   => 'required|string|max:255',
            'wallet_status'   => 'required|string|max:255',
            'wallet_level'   => 'required|string|max:255',
        ]);

        $wallet_type = $request->wallet_type;
        $wallet_status = $request->wallet_status;
        $wallet_level = $request->wallet_level;

        $initialize = new __init__;
        $response = $initialize->create_wallet($wallet_status, $wallet_type, $wallet_level);
       
        if ($response['success'] == true) {
            Alert::success('Succès', $response['message']);
            return redirect()->back();
        }
        elseif ($response['success'] == false) {
            Alert::error('Echec', $response['message']);
            return redirect()->back();
        } 
    }

    public function index(){
        $initialize = new __init__;
        $closed = $initialize->cash_session();$initialize = new __init__;
        $closed = $initialize->cash_session();
        $report = $initialize->fond_precedent();
        $report_cdf_on_c = $report['report_cdf_on_c'];
        $report_usd_on_c = $report['report_usd_on_c'];
        $wallets = DB::table('wallets')->where('id','!=','1')->distinct()->get();
        return view('admin.wallet.emala.list', compact('wallets','closed','report_cdf_on_c','report_usd_on_c'));
    }

    public function main(){
        $wallets = DB::table('ewallets')->where('id','1')->distinct()->get();
        $initialize = new __init__;
        $closed = $initialize->cash_session();
        $report = $initialize->fond_precedent();
        $report_cdf_on_c = $report['report_cdf_on_c'];
        $report_usd_on_c = $report['report_usd_on_c'];
        return view('admin.wallet.emala.main', compact('wallets','closed','report_cdf_on_c','report_usd_on_c'));
    }

    public function topup(Request $request){
        $request->validate([
            'amount'   => 'required|string|max:255',
        ]);

        $wallet_id = $request->wallet_id;
        $amount = $request->amount;
        $currency = $request->currency;

        $details = new __init__;
        $response = $details->topup_wallet($amount, $wallet_id,$currency);
       
        if ($response['success'] == true) {
            Alert::success('Succès', $response['message']);
            return redirect()->back();
        }
        elseif ($response['success'] == false) {
            Alert::error('Echec', $response['message']);
            return redirect()->back();
        } 
    }

    public function update_balance(Request $request){

       // dd('ok');
        $request->validate([
            // 'id'   => 'required|string|max:255',
            'balance_cdf'   => 'required|string|max:255',
            'balance_usd'   => 'required|string|max:255',
        ]);

        $balance_cdf = $request->balance_cdf;
        $balance_usd = $request->balance_usd;
        $wid = $request->wallet_id;
  
        $details = new __init__;
        $todayDate = $details->todayDate();

        $wallet = DB::table('ewallets')->where('type', 'Emala')->first();

        $data = [
            'balance_cdf'   => $balance_cdf,
            'balance_usd'   => $balance_usd,
            'updated_at'   => $todayDate,
        ];
        

        $activityLog = [
            'fullname'   => Auth::user()->firstname." ".Auth::user()->lastname,
            'user_phone'   => Auth::user()->phone_number,
            'user_id'   => Auth::user()->id,
            'activity'   => "Vient de modifier le solde du Wallet Principal",
            'updated_at'   => $todayDate,
        ];

        $save = DB::table('ewallets')->where('id',$wid)->update($data);  
        if ($save) {
            $details->activity_log($activityLog);
            $response = [
                'success' => true,
                'resultat' => 1,
                'message' => "Agence modifiée avec succès!",
                'status' => "Successful",
            ];
            Alert::success('Succès', "Balance wallet modifié avec succès!");
            return redirect()->back();
        }   
        else{
            Alert::error('Echec', "Echec!");
            return redirect()->back();
        } 
    }
}
