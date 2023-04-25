<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\Bank;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use RealRashid\SweetAlert\Facades\Alert;

class WalletController extends Controller
{
    public function all(){
        $wallets = DB::table('wallets')
        ->join('banks','wallets.bank_id','banks.id')
        ->select('wallets.*','banks.bank_name')
        ->get();

        $banks = Bank::all();
        return view('backend.wallet.list', compact('wallets','banks'));
    }

    public function create(Request $request){
        $request->validate([
            'institution'   => 'required|string|max:255',
        ]);
        $institution = $request->institution;
        $initialize = new Initialize;
        $response = $initialize->create_wallet($institution);
        if ($response['success'] == true) {
            Alert::success('Succès', $response['message']);
            return redirect()->back();
        }
        else {
            Alert::error('Erreur', $response['message']);
            return redirect()->back();
        }
    }

    public function topup(Request $request){
        $request->validate([
            'amount'   => 'required|string|max:255',
        ]);

        $wallet_id = $request->wallet_id;
        $amount = $request->amount;
        $currency = $request->currency;

        $details = new Initialize;
        $response = $details->topup_wallet($amount, $wallet_id,$currency);
       
        if ($response['success'] == true) {
            Alert::success('Succès', $response['message']);
            return redirect()->back();
        }
        elseif ($response['success'] == false) {
            Alert::error('Echec', $response['message']);
            return redirect()->back();
        } 
    }

    public function delete(Request $request)
    {
        $wallet_id = $request->id;
        $delete_user = new Initialize;
        $response = $delete_user->delete_wallet($wallet_id);

        if ($response['success'] == true) {
            Alert::success('Succès', $response['message']);
            return redirect()->back();
        }
        elseif ($response['success'] == false) {
            Alert::error('Echec', $response['message']);
            return redirect()->back();
        } 

    }
}
