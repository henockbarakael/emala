<?php

namespace App\Http\Controllers\Backend\Cashier;

use App\Http\Controllers\Controller;
use App\Models\Cashier;
use App\Models\Transaction;
use App\Models\User;
use App\Models\UserInfo;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class TransactionController extends Controller
{

    public function all(){
        
        $user = new User();
        $cashierId = $user->getCashierId();
        $cashier = Cashier::find($cashierId);
        $transactions = $cashier->getDailyTransactions();
        return view('backend.transaction.cashier.all',compact('transactions'));
    }

    public function deposit(){
        $user = new User();
        $cashierId = $user->getCashierId();
        $cashier = Cashier::find($cashierId);
        $transactions = $cashier->getDailyDeposit();
        return view('backend.transaction.cashier.deposit',compact('transactions'));
    }

    public function transfer(){
        $user = new User();
        $cashierId = $user->getCashierId();
        $cashier = Cashier::find($cashierId);
        $transactions = $cashier->getDailyTransfer();
        return view('backend.transaction.cashier.transfer',compact('transactions'));
    }

    public function withdrawal(){
        $user = new User();
        $cashierId = $user->getCashierId();
        $cashier = Cashier::find($cashierId);
        $transactions = $cashier->getDailyWithdraw();
        return view('backend.transaction.cashier.withdrawal',compact('transactions'));
    }

    public function search(Request $request){
        $transactions = Transaction::all();
        $transaction_from = $request->transaction_from;
        $transaction_to = $request->transaction_to;
        $date = Carbon::parse($request->transaction_dat);
       
        if($request->transaction_from){
            $transactions = Transaction::where('transaction_from','LIKE','%'.$transaction_from.'%')->where('branche_id',$this->branche_id())->get();
        }

        if($request->transaction_to){
            $transactions = Transaction::where('transaction_to','LIKE','%'.$transaction_to.'%')->where('branche_id',$this->branche_id())->get();
        }

        if($request->date){
            $transactions = Transaction::where('transaction_to','LIKE','%'.$date.'%')->where('branche_id',$this->branche_id())->get();
        }
        return view('backend.transaction.cashier.all',compact('transactions'));
    }
}
