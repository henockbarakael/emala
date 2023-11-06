<?php

namespace App\Http\Controllers\Backend\Manager;

use App\Http\Controllers\Controller;
use App\Models\Transaction;
use App\Models\UserInfo;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class TransactionController extends Controller
{

    public function branche_id()
    {
        $userInfo = UserInfo::where('user_id',Auth::user()->id)->first();
        $branche_id = $userInfo->branche_id;
        return $branche_id;
    }

    public function all(){
        
        $transactions = Transaction::where('branche_id',$this->branche_id())->get();
        // dd($transactions);
        return view('backend.transaction.manager.all',compact('transactions'));
    }

    public function deposit(){
        $transactions = Transaction::where(['branche_id'=>$this->branche_id(),'category'=>'depot'])->get();
        return view('backend.transaction.manager.deposit',compact('transactions'));
    }

    public function transfer(){
        $transactions = Transaction::where(['branche_id'=>$this->branche_id(),'category'=>'transfert'])->get();
        return view('backend.transaction.manager.transfer',compact('transactions'));
    }

    public function withdrawal(){
        $transactions = Transaction::where(['branche_id'=>$this->branche_id(),'category'=>'retrait'])->get();
        return view('backend.transaction.manager.withdrawal',compact('transactions'));
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
        return view('backend.transaction.manager.all',compact('transactions'));
    }
}
