<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class PaymentRequestController extends Controller
{
    public function PaymentRequest(){
        $transactions_1 = DB::table('withdrawals')
        ->join('branches','withdrawals.branche_id','branches.id')
        ->select('withdrawals.*','branches.btownship','branches.user_id')
        ->select('withdrawals.customer_id','withdrawals.currency_id','withdrawals.fees','withdrawals.amount','withdrawals.transaction_id','withdrawals.status AS status','withdrawals.created_at','withdrawals.id')
        ->get();

        $transactions_2 = DB::connection('mysql2')->table('withdrawals')
        ->select('customer_id','currency_id','fees','amount','transaction_id','status','created_at','id')
        ->get();


        $transactions = $transactions_1->union($transactions_2)->where('status','En attente');
        return view('backend.payment.index', compact('transactions'));
    }
}
