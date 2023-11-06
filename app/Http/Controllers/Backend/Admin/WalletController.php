<?php

namespace App\Http\Controllers\Backend\Admin;

use App\Http\Controllers\Backend\DateController;
use App\Http\Controllers\Backend\GenerateIdController;
use App\Http\Controllers\Controller;
use App\Models\Agency;
use App\Models\Branche;
use App\Models\BrancheWallet;
use App\Models\Wallet;
use App\Models\WalletHistory;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use RealRashid\SweetAlert\Facades\Alert;

class WalletController extends Controller
{
    public function emala()
    {
        $agency = Agency::findOrFail(1);
        // Obtenez la balance en CDF de l'agence principale
        $cdfBalance = $agency->getCdfBalancePrincipale();
        $cdfBalanceAmount = null;
        $cdfBalanceWalletId = null;

        if ($cdfBalance) {
            $cdfBalanceAmount = $cdfBalance['balance'];
            $cdfBalanceWalletId = $cdfBalance['wallet_id'];
        }

        // Obtenez la balance en USD de l'agence principale
        $usdBalance = $agency->getUsdBalancePrincipale();
        $usdBalanceAmount = null;
        $usdBalanceWalletId = null;

        if ($usdBalance) {
            $usdBalanceAmount = $usdBalance['balance'];
            $usdBalanceWalletId = $usdBalance['wallet_id'];
        };
    
        return view('backend.wallet.emala', compact('agency', 'cdfBalanceAmount', 'usdBalanceAmount', 'cdfBalanceWalletId', 'usdBalanceWalletId'));
    }

    public function agence(){
        // Récupérer l'instance de l'agence
        $agency = Agency::findOrFail(2);
        
        // Obtenez la balance en CDF de l'agence principale
        $cdfBalance = $agency->getCdfBalanceFiliale();
        
        $cdfBalanceAmount = null;
        $cdfBalanceWalletId = null;

        if ($cdfBalance) {
            $cdfBalanceAmount = $cdfBalance['balance'];
            $cdfBalanceWalletId = $cdfBalance['wallet_id'];
        }

        // Obtenez la balance en USD de l'agence principale
        $usdBalance = $agency->getUsdBalanceFiliale();
        $usdBalanceAmount = null;
        $usdBalanceWalletId = null;

        if ($usdBalance) {
            $usdBalanceAmount = $usdBalance['balance'];
            $usdBalanceWalletId = $usdBalance['wallet_id'];
        };
    

        return view('backend.wallet.agence', compact('agency', 'cdfBalanceAmount', 'usdBalanceAmount', 'cdfBalanceWalletId', 'usdBalanceWalletId'));

    }

    public function walletAgenceAdd(Request $request){
        $request->validate([
            'branche_id'   => 'required|string|max:255'
        ]);

        $date = new DateController;
        $today = $date->todayDate();
        $acnumber = new GenerateIdController;
        $walletCredit = $acnumber->walletcode();
        $data = [
            [
                'w_code' => $walletCredit,
                'w_cdf' => 0,
                'w_usd' => 0,
                'branche_id' => $request->branche_id,
                'created_at' => $today,
                'updated_at' => $today
            ]
        ];
        BrancheWallet::insert($data);
        Alert::success('Succès', 'Wallet créer avec succès !');
        return redirect()->route('admin.wallet.agence');
    }

    public function creditAgencePrincipale(Request $request){
        $request->validate([
            'amount'   => 'required|string|max:255',
            // 'currency'   => 'required|string|max:255'
        ]);

        $walleId = $request->w_code;

        $date = new DateController;
        $today = $date->todayDate();
        $amount = $request->amount;

        DB::beginTransaction();

        $wallets = Wallet::where('id',$request->w_code)->first();
        $current_balance = $wallets->balance;
        $currency = $wallets->currency;
        try {
            DB::table('wallets')->where('id',$walleId)->update(['balance' => $amount + $current_balance,'updated_at' => $today]);
            DB::commit();
            $history = new WalletHistory();
            $history->wallet_id = $walleId;
            $history->action = 'credit'; // Type de transaction (déduction)
            $history->balance = $amount;
            $history->currency = $currency;
            $history->created_at = $today;
            $history->updated_at = $today;
            $history->save();
            Alert::success('Succès', 'Wallet rechargé avec succès !');
            return redirect()->route('admin.wallet.emala');
        } catch (\Exception $e) {
            Alert::error('Erreur', 'Une erreur est survenue lors de la recharge du wallet: ' . $e->getMessage());
            return redirect()->route('admin.wallet.emala');
        }

    }
    public function deduct_emala_balance(Request $request){
        $request->validate([
            'amount'   => 'required|string|max:255',
        ]);

        $walleId = $request->w_code;
        $amount = $request->amount;
        
        $date = new DateController;
        $today = $date->todayDate();

        DB::beginTransaction();

        $wallets = Wallet::where('id',$request->w_code)->first();
        $current_balance = $wallets->balance;
        $currency = $wallets->currency;

        if ($current_balance >= $amount) {
            try {
                DB::table('wallets')->where('id', $walleId)->update(['balance' => $current_balance - $amount,'updated_at' => $today]);
                DB::commit();
                $history = new WalletHistory();
                $history->wallet_id = $walleId;
                $history->action = 'debit'; // Type de transaction (déduction)
                $history->balance = $amount;
                $history->currency = $currency;
                $history->created_at = $today;
                $history->updated_at = $today;
                $history->save();
                Alert::success('Succès', 'Le montant de ' . $amount . ' ' . $currency . ' a été déduit du wallet de l\'agence.');
                return redirect()->route('admin.wallet.emala');
            } catch (\Exception $e) {
                DB::rollback();
                Alert::error('Erreur', 'Une erreur est survenue lors de la recharge du wallet: ' . $e->getMessage());
                return redirect()->route('admin.wallet.emala');
            }
        } else {
            Alert::error('Erreur', 'Impossible de reduire la balance actuelle car ele est déjà inférieur à '.$amount.' '.$currency);
            return redirect()->route('admin.wallet.agence');
        }

    }
    public function deduct_agency_balance(Request $request){
        $request->validate([
            'amount'   => 'required|string|max:255',
            'currency'   => 'required|string|max:255'
        ]);

        $date = new DateController;
        $today = $date->todayDate();

        $amount = $request->amount;
        $currency = $request->currency;
        $walleId = $request->w_code;
    

        $wallets = Wallet::where('id',$walleId)->first();
        $current_balance = $wallets->balance;

        if ($current_balance >= $amount) {
            DB::beginTransaction();
            $balance = $current_balance - $amount;
            try {
                DB::table('wallets')->where('id',$walleId)->update(['balance' => $balance,'updated_at' => $today]);
                DB::commit();
                $history = new WalletHistory();
                $history->wallet_id = $walleId;
                $history->action = 'debit'; // Type de transaction (déduction)
                $history->balance = $amount;
                $history->currency = $currency;
                $history->created_at = $today;
                $history->updated_at = $today;
                $history->save();
                Alert::success('Succès', 'Le montant de '.$amount.' '.$currency.' a été déduit du wallet de l\'agence.');
                return redirect()->route('admin.wallet.agence');
            } catch (\Exception $e) {
                DB::rollback();
                Alert::error('Erreur', 'Une erreur est survenue lors de la recharge du wallet: ' . $e->getMessage());
                return redirect()->route('admin.wallet.agence');
            }
        } else {
            Alert::error('Erreur', 'Impossible de reduire la balance actuelle car ele est déjà inférieur à '.$amount.' '.$currency);
            return redirect()->route('admin.wallet.agence');
        }
    }
    public function creditAgenceFiliale(Request $request){
        $request->validate([
            'amount'   => 'required|string|max:255',
            'currency'   => 'required|string|max:255'
        ]);

        $date = new DateController;
        $today = $date->todayDate();

        $amount = $request->amount;
        $currency = $request->currency;
        $walleId = $request->w_code;
    

        $response = $this->debitWalletEmala($amount,$currency);

        if ($response["success"]==true) {
            DB::beginTransaction();

            $wallets = Wallet::where('id',$walleId)->first();
           
            $current_balance = $wallets->balance;
            $balance = $request->amount + $current_balance;
            try {
                DB::table('wallets')->where('id',$walleId)->update(['balance' => $balance,'updated_at' => $today]);
                DB::commit();
                $history = new WalletHistory();
                $history->wallet_id = $walleId;
                $history->action = 'credit'; // Type de transaction (déduction)
                $history->balance = $amount;
                $history->currency = $currency;
                $history->created_at = $today;
                $history->updated_at = $today;
                $history->save();
                Alert::success('Succès', 'Wallet rechargé avec succès !');
                return redirect()->route('admin.wallet.agence');
            } catch (\Exception $e) {
                DB::rollback();
                Alert::error('Erreur', 'Une erreur est survenue lors de la recharge du wallet: ' . $e->getMessage());
                return redirect()->route('admin.wallet.agence');
            }

        }
        else {
            Alert::error('Erreur', $response["message"]);
            return redirect()->route('admin.wallet.agence');
        }
    }
    public function debitWalletEmala($amount,$currency){
        $date = new DateController;
        $today = $date->todayDate();
        $wallets = Wallet::where('agency_id',"1")->where('currency',$currency)->first();
        $current_balance = $wallets->balance;
        $walleId = $wallets->id;
        if ($amount > $current_balance) {
            return ["success"=>false,"message"=>"Le solde de ce compte est insuffisant pour effectuer cette opération."];
        }
        else {
            DB::beginTransaction();
            try {
                DB::table('wallets')->where('agency_id',"1")->where('currency',$currency)->update(['balance' => $current_balance - $amount,'updated_at' => $today]);
                DB::commit();
                $history = new WalletHistory();
                $history->wallet_id = $walleId;
                $history->action = 'debit'; // Type de transaction (déduction)
                $history->balance = $amount;
                $history->currency = $currency;
                $history->created_at = $today;
                $history->updated_at = $today;
                $history->save();
                return ["success"=>true,"message"=>"Excellent"];
            } catch (\Exception $e) {
                DB::rollback();
                return ["success"=>false,"message"=>'Erreur lors du debit du wallet principal: ' . $e->getMessage()];
            }
        }
       
    }
    public function creditWallet($amount,$currency){

        $date = new DateController;
        $today = $date->todayDate();

        DB::beginTransaction();

        if ($currency == "CDF") {
            $wallets = Wallet::select('w_cdf')->first();
            $current_balance = $wallets->w_cdf;
            $walleId = $wallets->id;
            try {
                DB::table('wallets')->update(['w_cdf' => $amount + $current_balance,'updated_at' => $today]);
                DB::commit();
                $history = new WalletHistory();
                $history->wallet_id = $walleId;
                $history->action = 'credit'; // Type de transaction (déduction)
                $history->balance = $amount;
                $history->currency = $currency;
                $history->created_at = $today;
                $history->updated_at = $today;
                $history->save();
                return ["success"=>true,"message"=>"Excellent"];
            } catch (\Exception $e) {
                DB::rollback();
                return ["success"=>false,"message"=>'Erreur lors du debit du wallet principal: ' . $e->getMessage()];
            }
        }
        else {
            $wallets = Wallet::select('w_usd')->first();
            $current_balance = $wallets->w_usd;
            try {
                DB::table('wallets')->update(['w_usd' => $amount + $current_balance,'updated_at' => $today]);
                DB::commit();
                return ["success"=>true,"message"=>"Excellent"];
            } catch (\Exception $e) {
                DB::rollback();
                return ["success"=>false,"message"=>'Erreur lors du debit du wallet principal: ' . $e->getMessage()];
            }
        }

        
        
    }
    public function debitWallet($amount,$currency){
        $date = new DateController;
        $today = $date->todayDate();
        if ($currency == "CDF") {
            $wallets = Wallet::select('w_cdf')->where('w_type','debit')->first();
            $current_balance = $wallets->w_cdf;
            $walleId = $wallets->id;
            if ($amount > $current_balance) {
                return ["success"=>false,"message"=>"Le solde de ce compte est insuffisant pour effectuer cette opération."];
            }
            else {
                DB::beginTransaction();
                try {
                    DB::table('wallets')->where('w_type','debit')->update(['w_cdf' => $current_balance - $amount,'updated_at' => $today]);
                    DB::commit();
                    $history = new WalletHistory();
                    $history->wallet_id = $walleId;
                    $history->action = 'debit'; // Type de transaction (déduction)
                    $history->balance = $amount;
                    $history->currency = $currency;
                    $history->created_at = $today;
                    $history->updated_at = $today;
                    $history->save();
                    return ["success"=>true,"message"=>"Excellent"];
                } catch (\Exception $e) {
                    DB::rollback();
                    return ["success"=>false,"message"=>"Erreur lors du debit du wallet principal."];
                }
            }
        }
        else {
            $wallets = Wallet::select('w_usd')->where('w_type','debit')->first();
            $current_balance = $wallets->w_usd;
            if ($amount > $current_balance) {
                return ["success"=>false,"message"=>"Le solde de ce compte est insuffisant pour effectuer cette opération."];
            }
            else {
                DB::beginTransaction();
                try {
                    DB::table('wallets')->where('w_type','debit')->update(['w_usd' => $current_balance - $amount,'updated_at' => $today]);
                    DB::commit();
                    return ["success"=>true,"message"=>"Excellent"];
                } catch (\Exception $e) {
                    DB::rollback();
                    return ["success"=>false,"message"=>"Erreur lors du debit du wallet principal."];
                }
            }
        }
    }
}
