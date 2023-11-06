<?php

namespace App\Http\Controllers\Backend\Manager;

use App\Http\Controllers\Backend\DateController;
use App\Http\Controllers\Backend\GenerateIdController;
use App\Http\Controllers\Controller;
use App\Models\Customer;
use App\Models\PretAmortissement;
use App\Models\PretBancaire;
use App\Models\UserInfo;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use RealRashid\SweetAlert\Facades\Alert;

class PretBancaireController extends Controller
{
    public function branche_id()
    {
        $userInfo = UserInfo::where('user_id',Auth::user()->id)->first();
        $branche_id = $userInfo->branche_id;
        return $branche_id;
    }

    public function demande(){
        $prets = PretBancaire::join('cp1836503p07_frontend.users', 'pret_bancaires.customer_id', '=', 'users.id')
        ->where('branche_id', $this->branche_id())
        ->get();
        
        return view('backend.manager.pret.demande',compact('prets'));
    }

    public function demandePost(Request $request){

        dd('ok');
        
        $request->validate([
            'loan_amount'      => 'required|string|max:255',
            'loan_currency' => 'required|string|max:255',
            'loan_duration' => 'required|string|max:255',
            'echeance' => 'required|string|max:255',
            'principal_paid' => 'required|string|max:255',
            'paid_by_echeance' => 'required|string|max:255',
        ]);

        $phone_number = $request->receiver_phone;
        $loan_amount = $request->loan_amount;
        $loan_currency = $request->loan_currency;
        $loan_duration = $request->loan_duration;
        $echeance = $request->echeance;
        $principal_paid = $request->principal_paid;
        $paid_by_echeance = $request->paid_by_echeance;
        $objet = $request->objet;

        $customer = Customer::where('phone',$phone_number)->first();
        $customer_id = $customer->id;


        $date = new DateController;
        $today = $date->todayDate();

        $premier_echeance = Carbon::today()->addDays(7);

        $pin = new GenerateIdController;
        $control_number = $pin->reference();

        PretBancaire::create([
            'control_number'=>$control_number,
            'loan_amount'=>$loan_amount,
            'loan_currency'=>$loan_currency,
            'loan_status'=>'En attente',
            'loan_duration'=>$loan_duration,
            'principal_paid'=>$principal_paid,
            'echeance'=>$echeance,
            'premier_echeance'=>$premier_echeance,
            'amount_by_echeance'=>$paid_by_echeance,
            'objet'=>$objet,
            'customer_id'=>$customer_id,
            'branche_id'=>$this->branche_id(),
            'processed_by'=>Auth::user()->id,
            'created_at'=>$today,
            'updated_at'=>$today
        ]);

        
        Alert::success('Succès', 'Historique de prêt envoyée avec succès !');
        return redirect()->route('manager.pret.demande');
    }

    public function amortissement(){
        $prets = PretAmortissement::where('branche_id',$this->branche_id())->get();
        return view('backend.manager.pret.amortissement',compact('prets'));
    }
}
