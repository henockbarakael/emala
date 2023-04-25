<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class UserAccountAPI extends Controller
{
    public function todayDate(){
        Carbon::setLocale('fr');
        $todayDate = Carbon::now()->format('Y-m-d H:i:s');
        return $todayDate;
    }
    public function activity_log($activityLog){
        DB::table('user_activity_logs')->insert($activityLog);
    }
    public function account_id($currency){
        $user_id = Auth::user()->id;
        $account = DB::table('accounts')->where('user_id',$user_id)->where('currency',$currency)->first();
        $account_id = $account->id;
        return $account_id;
    }
    public function credit_user($currency, $amount){
        $account_id = $this->account_id($currency);
        $account = DB::table('accounts')->where('id', $account_id)->first();
        $todayDate = $this->todayDate();

        $balance = $account->balance;
        $data = [
            'balance'    => $balance + $amount,
        ];
        $update = DB::table('accounts')->where('id', $account_id)->where('currency', $currency)->update($data);
        $user_id = Auth::user()->id;

        $activityLog = [
            'fullname'   => Auth::user()->firstname." ".Auth::user()->lastname,
            'user_phone'   => Auth::user()->phone_number,
            'user_id'   => Auth::user()->id,
            'activity'   => "Vient de recharger le compte ".$account_id,
            'updated_at'   => $todayDate,
        ];
        if ($update) {
            $data = [
                'account_id' => $account_id,
                'user_id' => $user_id,
                'currency' => $currency,
                'amount' => $amount,
                'created_at'   => $todayDate,
                'updated_at'   => $todayDate,
            ];
            $save = DB::table('account_stories')->where('currency', $currency)->insert($data);
            $this->activity_log($activityLog);
            if ($save) {
                $response = [
                    'success' => true,
                    'resultat' => 1,
                    'message' => "Wallet rechargé avec succès",
                    'status' => "Successful",
                ];
                return $response;
            }
        }
        else {
            $activityFailed= [
                'fullname'   => Auth::user()->firstname." ".Auth::user()->lastname,
                'user_phone'   => Auth::user()->phone_number,
                'activity'   => "a tenté de recharger le compte ".$account_id,
                'updated_at'   => $todayDate,
            ];
            $this->activity_log($activityFailed);
            $response = [
                'success' => false,
                'message' => "Une erreur est survenue lors de la création du compte",
                'status' => "Failed",
            ];
            return $response;
        }
    }
    public function debit_user($currency, $amount){
        $account_id = $this->account_id($currency);
        $account = DB::table('accounts')->where('id', $account_id)->where('currency', $currency)->first();
        $todayDate = $this->todayDate();

        $balance = $account->balance;
        if ($amount > $balance) {
            $response = [
                'success' => false,
                'message' => "Balance insuffisante!",
                'status' => "Failed",
            ];
            return $response;
        }
        else {
            $data = [
                'balance'    => $balance - $amount,
            ];
            $update = DB::table('accounts')->where('id', $account_id)->where('currency', $currency)->update($data);
            $user_id = Auth::user()->id;

            $activityLog = [
                'fullname'   => Auth::user()->firstname." ".Auth::user()->lastname,
                'user_phone'   => Auth::user()->phone_number,
                'activity'   => "Vient d'approvisionner le compte ".$account->id,
                'updated_at'   => $todayDate,
            ];
            if ($update) {
                $data = [
                    'account_id' => $account->id,
                    'user_id' => $user_id,
                    'currency' => $currency,
                    'amount' => $amount,
                    'created_at'   => $todayDate,
                    'updated_at'   => $todayDate,
                ];
                $save = DB::table('account_stories')->where('currency', $currency)->insert($data);
                $this->activity_log($activityLog);
                if ($save) {
                    $response = [
                        'success' => true,
                        'resultat' => 1,
                        'message' => "Compte debité avec succès",
                        'status' => "Successful",
                    ];
                    return $response;
                }
            }
            else {
                $activityFailed= [
                    'fullname'   => Auth::user()->firstname." ".Auth::user()->lastname,
                    'user_phone'   => Auth::user()->phone_number,
                    'activity'   => "a tenté de débiter le compte ".$account->id,
                    'updated_at'   => $todayDate,
                ];
                $this->activity_log($activityFailed);
                $response = [
                    'success' => false,
                    'message' => "Une erreur est survenue lors de l'approvisionnement du compte",
                    'status' => "Failed",
                ];
                return $response;
            }
        }

    }
}
