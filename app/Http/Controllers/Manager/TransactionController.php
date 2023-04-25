<?php

namespace App\Http\Controllers\Manager;

use App\Http\Controllers\API\Initialize;
use App\Http\Controllers\Controller;
use App\Http\Controllers\VerifyNumberController;
use App\Models\Branche;
use App\Models\Customer;
use App\Models\Deposit;
use App\Models\TransactionLimit;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Date;
use Illuminate\Support\Facades\DB;
use RealRashid\SweetAlert\Facades\Alert;

class TransactionController extends Controller
{
    public function all(){
        $transactions = DB::table('transactions')
        ->join('branches','transactions.branche_id','branches.id')
        ->select('transactions.*','branches.btownship','branches.user_id')
        ->get();
        if (Auth::user()->role_name == "Manager") {
            return view('manager.transaction.all', compact('transactions'));
        }
        elseif (Auth::user()->role_name == "Cashier") {
            return view('cashier.transaction.all', compact('transactions'));
        }
    }
    public function recherche(Request $request){
        $transactions = DB::table('transactions')
        ->join('branches','transactions.branche_id','branches.id')
        ->select('transactions.*','branches.btownship','branches.user_id')
        ->get();

        $verify_number = new VerifyNumberController;
        $sender = $request->sender_number;
        $sender_phone = $verify_number->verify_number($sender);
        $receiver = $request->receiver_number;
        $receiver_phone = $verify_number->verify_number($receiver);
        
        $daterange = strtotime($request->date);
        $date = date('Y-m-d', $daterange);
        

        if($request->sender_number){
            $transactions = DB::table('transactions')
            ->join('branches','transactions.branche_id','branches.id')
            ->select('transactions.*','branches.btownship','branches.user_id')
            ->where('sender_phone','LIKE','%'.$sender_phone.'%')->get();
        }

        if($request->receiver_number){
            $transactions = DB::table('transactions')
            ->join('branches','transactions.branche_id','branches.id')
            ->select('transactions.*','branches.btownship','branches.user_id')
            ->where('receiver_phone','LIKE','%'.$receiver_phone.'%')->get();
        }

        if($request->date){
            $transactions = DB::table('transactions')
            ->join('branches','transactions.branche_id','branches.id')
            ->select('transactions.*','branches.btownship','branches.user_id')
            ->whereDate('transactions.created_at','LIKE','%'.$date.'%')->get();
        }

        if (Auth::user()->role_name == "Manager") {
            return view('manager.transaction.all', compact('transactions'));
        }
        elseif (Auth::user()->role_name == "Cashier") {
            return view('cashier.transaction.all', compact('transactions'));
        }
    }
    public function deposit(){
        $db = DB::connection('mysql2')->getDatabaseName();
        $transactions = Deposit::join($db . '.users AS db2','deposits.customer_id','=','db2.id')
        ->join('branches','deposits.branche_id','branches.id')
        ->select(['db2.phone','deposits.*','branches.btownship'])
        ->get();
        if (Auth::user()->role_name == "Manager") {
            return view('manager.transaction.deposit', compact('transactions'));
        }
        elseif (Auth::user()->role_name == "Cashier") {
            return view('cashier.transaction.deposit', compact('transactions'));
        }
    }
    public function transfer(){
        $transactions = DB::table('transfers')
        ->join('branches','transfers.branche_id','branches.id')
        ->select('transfers.*','branches.btownship','branches.user_id')
        ->get();
        if (Auth::user()->role_name == "Manager") {
            return view('manager.transaction.transfer', compact('transactions'));
        }
        elseif (Auth::user()->role_name == "Cashier") {
            return view('cashier.transaction.transfer', compact('transactions'));
        }
    }
    public function withdrawal(){
        $db = DB::connection('mysql2')->getDatabaseName();
        $transactions_1 = DB::table('withdrawals')
        ->join('branches','withdrawals.branche_id','branches.id')
        ->join($db . '.users AS db2','withdrawals.customer_id','=','db2.id')
        ->select('db2.phone','withdrawals.customer_id','withdrawals.currency_id','withdrawals.fees','withdrawals.amount','withdrawals.transaction_id','withdrawals.status AS status','withdrawals.created_at','withdrawals.id')
        ->get();

        // dd($transactions_1);

        $transactions_2 = DB::connection('mysql2')->table('withdrawals')
        ->join($db . '.users AS db2','withdrawals.customer_id','=','db2.id')
        ->select('db2.phone','customer_id','withdrawals.currency_id','withdrawals.fees','withdrawals.amount','withdrawals.transaction_id','withdrawals.status','withdrawals.created_at','withdrawals.id')
        ->get();

        // dd($transactions_2);

        $transactions = $transactions_1->union($transactions_2);
        // dd($transactions);
        if (Auth::user()->role_name == "Manager") {
            return view('manager.transaction.withdrawal', compact('transactions'));
        }
        elseif (Auth::user()->role_name == "Cashier") {
            return view('cashier.transaction.withdrawal', compact('transactions'));
        }
    }
    public function limit(){
        $limits = TransactionLimit::all();
        if (Auth::user()->role_name == "Manager") {
            return view('manager.transaction.limit', compact('limits'));
        }
        elseif (Auth::user()->role_name == "Cashier") {
            return view('cashier.transaction.limit', compact('limits'));
        }
    }
    public function limitPOST(Request $request){
        $request->validate([
            'transaction_type'   => 'required|string|max:255',
            'min_amount'   => 'required|string|max:255',
            'max_amount'   => 'required|string|max:255',
            'currency'   => 'required|string|max:255',
            'limit_by_day'   => 'required|string|max:255',
        ]);
        $transaction_type = $request->transaction_type;
        $min_amount = $request->min_amount;
        $max_amount = $request->max_amount;
        $currency = $request->currency;
        $limit_by_day = $request->limit_by_day;

        $initialize = new Initialize;
        $response = $initialize->transaction_limit($transaction_type,$min_amount,$max_amount,$currency,$limit_by_day);
        if ($response['success'] == true) {
            Alert::success('Succès', $response['message']);
            return redirect()->back();
        }
        elseif ($response['success'] == false) {
            Alert::error('Erreur', $response['message']);
            return redirect()->back();
        }
    }
}
