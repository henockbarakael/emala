<?php

namespace App\Http\Controllers\Backend\Cashier;

use App\Http\Controllers\Backend\DateController;
use App\Http\Controllers\Controller;
use App\Models\Branche;
use App\Models\BrancheWallet;
use App\Models\RechargeRequest;
use App\Models\UserInfo;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use RealRashid\SweetAlert\Facades\Alert;

class WalletController extends Controller
{
    public function branche_id()
    {
        $userInfo = UserInfo::where('user_id',Auth::user()->id)->first();
        $branche_id = $userInfo->branche_id;
        return $branche_id;
    }
    public function agence(){
        $wallets = BrancheWallet::join('branches','branche_wallets.branche_id','branches.id')
        ->select('branches.bname','branche_wallets.*')
        ->where(['branches.id'=>$this->branche_id()])
        ->get();
        $branches = Branche::where(['id'=>$this->branche_id()])->first();
        return view('backend.manager.wallet.agence',compact('wallets','branches'));
    }

    public function recharge(Request $request){
        $request->validate([
            'amount'   => 'required|string|max:255',
            'currency'   => 'required|string|max:255'
        ]);
        $w_code = $request->w_code;
        $amount = $request->amount;
        $currency = $request->currency;
        $userId = Auth::user()->id;
        $branche_id = $this->branche_id();
        $date = new DateController;
        $today = $date->todayDate();
        $data = ['amount'=>$amount,'currency'=>$currency,'wallet_code'=>$w_code,'user_id'=>$userId,'branche_id'=>$branche_id,'created_at'=>$today,'updated_at'=>$today];
        RechargeRequest::insert($data);
        Alert::success('Succès', 'Demande envoyée avec succès!');
        return redirect()->route('manager.wallet.agence');
    }

    public function historique(){
        $requests = RechargeRequest::where(['user_id'=>Auth::user()->id])->get();
        return view('backend.manager.wallet.story',compact('requests'));
    }

    public function creditWallet($amount,$currency){
        $date = new DateController;
        $today = $date->todayDate();

        DB::beginTransaction();

        if ($currency == "CDF") {
            $wallets = BrancheWallet::select('w_cdf')->where('w_type','credit')->first();
            $current_balance = $wallets->w_cdf;
            try {
                DB::table('branche_wallets')->where('w_type','credit')->update(['w_cdf' => $amount + $current_balance,'updated_at' => $today]);
                DB::commit();
                
                return ['success' => true, 'message' => 'Wallet rechargé avec succès !'];
            } catch (\Exception $e) {
                DB::rollback();
                
                return ['success' => false, 'message' => 'Une erreur est survenue lors de la recharge du wallet'];
            }
        }
        else {
            $wallets = BrancheWallet::select('w_usd')->where('w_type','credit')->first();
            $current_balance = $wallets->w_usd;
            try {
                DB::table('branche_wallets')->where('w_type','credit')->update(['w_usd' => $amount + $current_balance,'updated_at' => $today]);
                DB::commit();
                
                return ['success' => true, 'message' => 'Wallet rechargé avec succès !'];
            } catch (\Exception $e) {
                DB::rollback();
                
                return ['success' => false, 'message' => 'Une erreur est survenue lors de la recharge du wallet'];

            }
        }
    }

    public function debitWallet($amount,$currency){
        $date = new DateController;
        $today = $date->todayDate();

        DB::beginTransaction();

        if ($currency == "CDF") {
            $wallets = BrancheWallet::select('w_cdf')->where('w_type','credit')->first();
            $current_balance = $wallets->w_cdf;
            try {
                DB::table('branche_wallets')->where('w_type','credit')->update(['w_cdf' => $amount - $current_balance,'updated_at' => $today]);
                DB::commit();
                
                return ['success' => true, 'message' => 'Wallet rechargé avec succès !'];
            } catch (\Exception $e) {
                DB::rollback();
                
                return ['success' => false, 'message' => 'Une erreur est survenue lors de la recharge du wallet'];
            }
        }
        else {
            $wallets = BrancheWallet::select('w_usd')->where('w_type','credit')->first();
            $current_balance = $wallets->w_usd;
            try {
                DB::table('branche_wallets')->where('w_type','credit')->update(['w_usd' => $amount - $current_balance,'updated_at' => $today]);
                DB::commit();
                
                return ['success' => true, 'message' => 'Wallet rechargé avec succès !'];
            } catch (\Exception $e) {
                DB::rollback();
                
                return ['success' => false, 'message' => 'Une erreur est survenue lors de la recharge du wallet'];

            }
        }
    }

}
