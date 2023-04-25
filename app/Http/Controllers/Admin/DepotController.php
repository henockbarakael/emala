<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\DB;
use RealRashid\SweetAlert\Facades\Alert;
use App\Http\Controllers\__init__;
use App\Http\Controllers\generateReferenceController;
use Illuminate\Support\Facades\Auth;

class DepotController extends Controller
{
    public function create($id){
        $userid=  Crypt::decrypt($id);
       
        $receivers = DB::table('accounts')
        ->join('branches','accounts.branche_id','branches.id')
        ->join('users','accounts.user_id','users.id')
        ->join('wallets','accounts.id','wallets.account_id')
        ->select('accounts.*','users.id AS user_id','users.avatar','users.firstname','users.lastname','users.middlename','users.phone_number','branches.btownship','wallets.balance_cdf','wallets.balance_usd','wallet_id')
        ->where('accounts.actype','current')
        ->where('users.id','=', $userid)
        ->distinct()
        ->first();

        $initialize = new __init__;
        $closed = $initialize->cash_session();
        $report = $initialize->fond_precedent();
        $report_cdf_on_c = $report['report_cdf_on_c'];
        $report_usd_on_c = $report['report_usd_on_c'];

        return view('admin.transaction.depot.create', compact('receivers','userid','closed','report_cdf_on_c','report_usd_on_c'));

    }

    public function store(Request $request){
        $request->validate([
            'amount'   => 'required|string|max:255',
            // 'fees'   => 'required|string|max:255',
            'currency'   => 'required|string|max:255',
            'compte'   => 'required|string|max:255',
        ]);

        $benefid = $request->userid;
        $compte = $request->compte;
        $currency = $request->currency;
        $amount = $request->amount;
        // $fees = $request->fees;
        $fees = 0;
        $type = "depot";
        $action = "credit";
        $medium_of_transaction = "cash";

        $userid = Auth::user()->id;

        /*  Début Initialisation des fonctions */
        $launch = new __init__;
        /*  On recupère l'ID de l'agence */
        $branche_id = $launch->branche_id();
        /*  On recupère l'ID du caissier */
        $expedieur = $launch->cashier_account_wallet($userid);
        /*  Début Initialisation des fonctions */
        $sender = $expedieur->phone_number;
        $destinataire = $launch->user_account_wallet($benefid);
        $receiver= $destinataire->phone_number;

        $response = $launch->depot_agence_to_wallet($benefid,$compte,$currency,$amount, $fees);
       
        /*  Fin Initialisation des fonctions */

        $status_description = $response['message'];
        $generate = new generateReferenceController;
        $reference = $generate->reference($type);
        
        $status = $response['status'];

        if ($response['success'] == true) {
            $launch->trandetails($medium_of_transaction,$action, $sender, $receiver, $branche_id,$reference,$amount,$currency,$status,$status_description,$type);
            Alert::success('Succès', $response['message']);
            return redirect()->back(); 
        }
        elseif ($response['success'] == false) {
            $launch->trandetails($medium_of_transaction,$action,$sender, $receiver, $branche_id,$reference,$amount,$currency,$status,$status_description,$type);
            Alert::error('Echec', $response['message']);
            return redirect()->back();
        }
    }
}
