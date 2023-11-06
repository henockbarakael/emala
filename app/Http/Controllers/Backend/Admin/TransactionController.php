<?php

namespace App\Http\Controllers\Backend\Admin;

use App\Http\Controllers\Controller;
use App\Models\Admin;
use App\Models\Transaction;
use App\Models\User;
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
        $user = new User();
        $adminId = $user->getAdminId();
        $admin = Admin::find($adminId);
        $transactions = $admin->getDailyTransactions();
        return view('backend.transaction.admin.all',compact('transactions'));
    }

    public function deposit(){
        $category = 'Dépôt';
        $user = new User();
        $adminId = $user->getAdminId();
        $admin = Admin::find($adminId);
        $transactions = $admin->getCategoryTransactions($category);
        return view('backend.transaction.admin.deposit',compact('transactions'));
    }

    public function transfer(){
        $category = 'Transfert';
        $user = new User();
        $adminId = $user->getAdminId();
        $admin = Admin::find($adminId);
        $transactions = $admin->getCategoryTransactions($category);
        return view('backend.transaction.admin.transfer',compact('transactions'));
    }

    public function withdrawal(){
        $category = 'Retrait';
        $user = new User();
        $adminId = $user->getAdminId();
        $admin = Admin::find($adminId);
        $transactions = $admin->getCategoryTransactions($category);
        return view('backend.transaction.admin.withdrawal',compact('transactions'));
    }

    public function search(Request $request){
        $transactions = Transaction::all();
        $transaction_from = $request->transaction_from;
        $transaction_to = $request->transaction_to;
        $date = Carbon::parse($request->transaction_dat);
       
        if($request->transaction_from){
            $transactions = Transaction::where('sender_phone','LIKE','%'.$transaction_from.'%')->get();
        }

        if($request->transaction_to){
            $transactions = Transaction::where('receiver_phone','LIKE','%'.$transaction_to.'%')->get();
        }

        if($request->date){
            $transactions = Transaction::where('receiver_phone','LIKE','%'.$date.'%')->get();
        }
        return view('backend.transaction.admin.all',compact('transactions'));
    }
}
