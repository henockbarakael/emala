<?php

namespace App\Http\Controllers\Backend\Admin;

use App\Http\Controllers\Controller;
use App\Http\Controllers\DateController;
use App\Models\BrancheWallet;
use App\Models\RechargeRequest;
use App\Models\Wallet;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use RealRashid\SweetAlert\Facades\Alert;

class RechargeRequestController extends Controller
{
    public function index(){
        $requests = RechargeRequest::join('branches','recharge_requests.branche_id','branches.id')
        ->select('branches.bname','recharge_requests.*')
        ->where('recharge_requests.status','En attente')
        ->get();
        return view('backend.branche.recharge',compact('requests'));
    }

    public function respondRequest(Request $request){
        $request->validate([
            'amount'   => 'required|string|max:255',
            'currency'   => 'required|string|max:255'
        ]);

        $date = new DateController;
        $today = $date->todayDate();

        $w_code = $request->w_code;

        $amount = $request->amount;
        $currency = $request->currency;

   
        $debit = new WalletController;
        $response = $debit->debitWalletEmala($amount,$currency);

        if ($response["success"]==true) {
            DB::beginTransaction();

            if ($request->currency == "CDF") {
                $wallets = BrancheWallet::select('w_cdf')->where('w_code',$w_code)->first();
                $current_balance = $wallets->w_cdf;
                try {
                    DB::table('branche_wallets')->where('w_code',$w_code)->update(['w_cdf' => $request->amount + $current_balance,'updated_at' => $today]);
                    DB::table('recharge_requests')->where('id',$request->w_id)->update(['status' => 'Approuvée','updated_at' => $today]);
                    DB::commit();
                    Alert::success('Succès', 'Wallet rechargé avec succès !');
                    return redirect()->route('admin.recharge.request');
                } catch (\Exception $e) {
                    DB::rollback();
                    Alert::error('Erreur', 'Une erreur est survenue lors de la recharge du wallet');
                    return redirect()->route('admin.recharge.request');
                }
            }
            else {
                $wallets = BrancheWallet::select('w_usd')->where('w_code',$w_code)->first();
                $current_balance = $wallets->w_usd;
                try {
                    DB::table('branche_wallets')->where('w_code',$w_code)->update(['w_usd' => $request->amount + $current_balance,'updated_at' => $today]);
                    DB::table('recharge_requests')->where('id',$request->w_id)->update(['status' => 'Approuvée','updated_at' => $today]);
                    DB::commit();
                    Alert::success('Succès', 'Wallet rechargé avec succès !');
                    return redirect()->route('admin.recharge.request');
                } catch (\Exception $e) {
                    DB::rollback();
                    Alert::error('Erreur', 'Une erreur est survenue lors de la recharge du wallet');
                    return redirect()->route('admin.recharge.request');
                }
            }
        }
        else {
            Alert::error('Erreur', $response["message"]);
            return redirect()->route('admin.recharge.request');
        }
        
        
    }
}

