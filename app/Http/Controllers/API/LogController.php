<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class LogController extends Controller
{
    public function user_activity_log()
    {
        $activityLog = DB::table('user_activity_logs')->get();
        return view('backend.logs.user_activity_log',compact('activityLog'));
    }
    public function activity_log()
    {
        $activityLog = DB::table('activity_logs')
        ->join('users','activity_logs.user_id','users.id')
        ->select('activity_logs.*','users.phone_number')
        ->get();
        return view('backend.logs.activity_log',compact('activityLog'));
    }
}
