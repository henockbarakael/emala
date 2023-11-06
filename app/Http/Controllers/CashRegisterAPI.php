<?php

namespace App\Http\Controllers;

use App\Http\Controllers\API\Initialize;
use App\Http\Controllers\API\Statistiques;
use App\Http\Controllers\API\UserAccountAPI;
use App\Models\Account;
use App\Models\Cashier;
use App\Models\CashRegister;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class CashRegisterAPI extends Controller
{
    public function cloture($IDdernierSoldeFc,$IDdernierSoldeUs,$ecartFC,$banqueFC,$reportFC,$soldeUSD,$ecartUSD,$banqueUSD,$reportUSD){
       

        $cdfId = Cashier::where('user_id', Auth::user()->id)
            ->where('currency', 'CDF')
            ->value('id');

        $usdId = Cashier::where('user_id', Auth::user()->id)
            ->where('currency', 'USD')
            ->value('id');

        $todayDate = $this->todayDate();
        $data_1 = [
            'closing_balance' => $reportFC,
            'status'   => "closed",
            'closing_date'   => $todayDate,
            'gap'   => $ecartFC,
            'updated_at'   => $todayDate,
        ];
        $data_2 = [
            'closing_balance' => $reportUSD,
            'status'   => "closed",
            'closing_date'   => $todayDate,
            'gap'   => $ecartUSD,
            'updated_at'   => $todayDate,
        ];

        $cash_1 = CashRegister::where('cashier_id',$cdfId)->where('status','opened')->latest('opening_date')->update($data_1);
        $cash_2 = CashRegister::where('cashier_id',$usdId)->where('status','opened')->latest('opening_date')->update($data_2);
        if ($cash_1 && $cash_2) {
            // $this->reserve($banqueFC,$banqueUSD,$cdfId,$usdId);
            $user = new User();
            $cashierId = $user->getCashierId();
            $cashier = Cashier::findOrFail($cashierId);
            $cashier->agency->consoliderFonds($reportFC,$reportUSD);

            $response = [
                'success' => true,
                'message' => "Caisse clôturée avec succès!",
            ];
            return $response;
        }
        else {
            $response = [
                'success' => false,
                'message' => "Cette session de caisse a déjà été clôturéé! Veuillez ouvrir une nouvelle session!",
            ];
            return $response;
        }

    }
    public function todayDate(){
        Carbon::setLocale('fr');
        $todayDate = Carbon::now()->format('Y-m-d H:i:s');
        return $todayDate;
    }

    public function user_account_wallet($userid){
        
        $resultat = DB::table('accounts')
        ->join('branches','accounts.branche_id','branches.id')
        ->join('users','accounts.user_id','users.id')
        ->join('wallets','accounts.id','wallets.account_id')
        ->select('accounts.*','users.id','users.avatar','users.firstname','users.lastname','users.middlename','users.phone_number','branches.btownship','wallets.balance_cdf','wallets.balance_usd','wallet_id','account_id','wallets.id AS id_wallet')
        ->where('accounts.actype','current')
        ->where('users.id','=', $userid)
        ->distinct()
        ->first();
        return $resultat;
    }

    public function credit_wallet($wallet_id, $amount, $currency){


        $wallet = DB::table('wallets')->where('id',$wallet_id)->first();
        

        $todayDate = $this->todayDate();

        $logs_data = [
            'user_id' => Auth::user()->id,
            'wallet_id' => $wallet->id,
            'currency' => $currency,
            'amount' => $amount,
            'created_at'   => $todayDate,
            'updated_at'   => $todayDate,
        ];

        if ($currency == "CDF") {
            $data = [
                'balance_cdf'   => $wallet->balance_cdf + $amount,
                'updated_at'   => $todayDate,
            ];
        }
        elseif ($currency == "USD") {
            $data = [
                'balance_usd'   => $wallet->balance_usd + $amount,
                'updated_at'   => $todayDate,
            ];
            
        }

        $update_data = DB::table('wallets')->where('id',$wallet_id)->update($data);

        if ($update_data) {
            $this->wallet_stories($logs_data); 
            $response = [
                'success' => true,
                'message' => "Wallet credité avec succès!",
                'status' => "Successful",
            ];
            return $response;
        }

        else {
            $response = [
                'success' => false,
                'message' => "Echec de l'opération!",
                'status' => "Failed",
            ];
            return $response;
        }
    }

    public function reserve($banqueFC,$banqueUSD,$account_1,$account_2){
        $initialize = new Initialize;
        $branche_id = $initialize->branche_id(Auth::user()->id);
        $userId = Auth::user()->id;
        $todayDate = $this->todayDate();
        $data_1 = [
            'account_id' => $account_1,
            'amount'   => $banqueFC,
            'currency'   => "CDF",
            'branche_id'   => $branche_id,
            'user_id'   => $userId,
            'created_at'   => $todayDate,
            'updated_at'   => $todayDate,
        ];
        $data_2 = [
            'account_id' => $account_2,
            'amount'   => $banqueUSD,
            'currency'   => "USD",
            'branche_id'   => $branche_id,
            'user_id'   => $userId,
            'created_at'   => $todayDate,
            'updated_at'   => $todayDate,
        ];
        if ($banqueFC != 0) {
            DB::table('reserves')->insert($data_1); 
        }
        elseif ($banqueUSD != 0 ) {
            DB::table('reserves')->insert($data_2); 
        }
        else {
            return 1;
        }
        
        
    }
}
