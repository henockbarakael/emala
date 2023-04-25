<?php

namespace App\Http\Controllers\Manager;

use App\Http\Controllers\Controller;
use App\Models\Account;
use App\Models\Branche;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class LogController extends Controller
{
    public function branche_id($user_id){
        $branche = Branche::where('user_id', $user_id)->first();
        if ($branche == null) {
            $branche_id = null;
            return $branche_id;
        }
        else {
            $branche_id = $branche->id;
            return $branche_id;
        }
        
    }
    public function cashierBrancheId($user_id){
        $branche = Account::where('user_id',$user_id)->first();
            
        if ($branche == null) {
            $branche_id = null;
            return $branche_id;
        }
        else {
            $branche_id = $branche->branche_id;
            return $branche_id;
        }
        
    }
    public function user_activity_log()
    {
        $branche_id = $this->branche_id(Auth::user()->id);
        $activityLog = DB::table('user_activity_logs')
        ->join('users','user_activity_logs.user_phone','users.phone_number')
        ->join('branches','user_activity_logs.user_id','branches.user_id')
        ->select('user_activity_logs.*','users.phone_number','branches.id')
        ->where('branches.id',$branche_id)
        ->get();
        if (Auth::user()->role_name == "Manager") {
            return view('manager.logs.user_activity_log',compact('activityLog'));
        }
        elseif (Auth::user()->role_name == "Cashier") {
            return view('cashier.logs.user_activity_log',compact('activityLog'));
        }
        
    }
    public function activity_log()
    {
        $branche_id = $this->branche_id(Auth::user()->id);
        $activityLog = DB::table('activity_logs')
        ->join('users','activity_logs.user_id','users.id')
        ->join('branches','activity_logs.user_id','branches.user_id')
        ->select('activity_logs.*','users.phone_number')
        ->where('branches.id',$branche_id)
        ->get();
        if (Auth::user()->role_name == "Manager") {
            return view('manager.logs.activity_log',compact('activityLog'));
        }
        elseif (Auth::user()->role_name == "Cashier") {
            return view('cashier.logs.activity_log',compact('activityLog'));
        }
        
    }
}
