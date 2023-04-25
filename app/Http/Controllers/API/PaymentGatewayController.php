<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class PaymentGatewayController extends Controller
{
    public function all(){
        $gateway = DB::table('payment_methods')->get();
        return view('backend.gateway.all', compact('gateway'));
    }
}
