<?php

namespace App\Http\Controllers\Backend\Admin;

use App\Http\Controllers\Controller;
use App\Http\Controllers\DateController;
use App\Models\LoanPlan;
use App\Models\LoanType;
use App\Models\PretAmortissement;
use App\Models\PretBancaire;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use RealRashid\SweetAlert\Facades\Alert;

class PretBancaireController extends Controller
{
    public function demande(){
        $prets = PretBancaire::join('customers','pret_bancaires.customer_id','customers.id')->get();
        return view('backend.pret.demande',compact('prets'));
    }

    public function amortissement(){
        $prets = PretAmortissement::all();
        return view('backend.pret.amortissement',compact('prets'));
    }

    public function type(){
        $prets = LoanType::all();
        return view('backend.pret.type',compact('prets'));
    }

    public function typePost(Request $request){
        $request->validate([
            'description'   => 'required|string|max:255',
            'type'   => 'required|string|max:255'
        ]);
        $description = $request->description;
        $type = $request->type;

        LoanType::create(['description'=>$description,'type_name'=>$type]);
        Alert::success('Succès', 'Type de prêt ajouté avec succès !');
        return redirect()->route('admin.pret.type');
    }

    public function typeEdit(Request $request){
        $request->validate([
            'description'   => 'required|string|max:255',
            'type'   => 'required|string|max:255'
        ]);
        $description = $request->description;
        $type = $request->type;
        $id = $request->id;

        DB::beginTransaction();
        try {
            DB::table('loan_types')->where('id',$id)->update(['description'=>$description,'type_name'=>$type]);
            DB::commit();
            Alert::success('Succès', 'Type de prêt modifié avec succès !');
            return redirect()->route('admin.pret.type');
        } catch (\Exception $e) {
            DB::rollback();
            Alert::error('Erreur', 'Une erreur est survenue!');
            return redirect()->back();
        }
    }

    public function typeDelete(Request $request){
        $id = $request->id;
        DB::beginTransaction();
        try {
            DB::table('loan_types')->delete($id);
            DB::commit();
            Alert::success('Succès', 'Type supprimé avec succès !');
            return redirect()->route('admin.pret.type');
        } catch (\Exception $e) {
            DB::rollback();
            Alert::error('Erreur', 'Une erreur est survenue.');
            return redirect()->back();
        }
        
    }

    public function plan(){
        $prets = LoanPlan::all();
        return view('backend.pret.plan',compact('prets'));
    }

    public function planPost(Request $request){
        $request->validate([
            'interest_percentage'   => 'required|string|max:255',
            'months'   => 'required|string|max:255',
            'rate'   => 'required|string|max:255'
        ]);
        $interest_percentage = $request->interest_percentage;
        $months = $request->months;
        $rate = $request->rate;
        $id = $request->id;

        LoanPlan::create(['months'=>$months,'interest_percentage'=>$interest_percentage,'rate'=>$rate]);
        Alert::success('Succès', 'Plan de prêt ajouté avec succès !');
        return redirect()->route('admin.pret.plan');
    }

    public function planEdit(Request $request){
        $request->validate([
            // 'interest_percentage'   => 'required|string|max:255',
            // 'months'   => 'required|string|max:255',
            'rate'   => 'required|string|max:255'
        ]);
        // $interest_percentage = $request->interest_percentage;
        // $months = $request->months;
        $rate = $request->rate;
        $id = $request->id;

        DB::beginTransaction();
        try {

            // DB::table('loan_plans')->where('id',$id)->update(['months'=>$months,'interest_percentage'=>$interest_percentage,'rate'=>$rate]);
            DB::table('loan_plans')->where('id',$id)->update(['rate'=>$rate]);
            DB::commit();
            Alert::success('Succès', 'Taux annuel modifié avec succès !');
            return redirect()->route('admin.pret.plan');
        } catch (\Exception $e) {
            DB::rollback();
            Alert::error('Erreur', 'Une erreur est survenue!');
            return redirect()->back();
        }
    }

    public function planDelete(Request $request){
        $id = $request->id;
        DB::beginTransaction();
        try {
            DB::table('loan_plans')->delete($id);
            DB::commit();
            Alert::success('Succès', 'Plan supprimé avec succès !');
            return redirect()->route('admin.pret.plan');
        } catch (\Exception $e) {
            DB::rollback();
            Alert::error('Erreur', 'Une erreur est survenue.');
            return redirect()->back();
        }
        
    }
    public function desApprouvé(Request $request){
        $control_number = $request->control_number;
        $date = new DateController;
        $today = $date->todayDate();
        DB::beginTransaction();
        try {
            DB::table('pret_bancaires')->where('control_number',$control_number)->update([
                'loan_status'=>'DésApprouvé',
                'updated_at'=>$today   
            ]);
            DB::commit();
            Alert::success('Succès', 'Demande désapprouvée!');
            return redirect()->route('admin.pret.demande');
        } catch (\Exception $e) {
            DB::rollback();
            Alert::error('Erreur', 'Une erreur est survenue.');
            return redirect()->back();
        }
        
    }

    public function Approuvé(Request $request){
        $control_number = $request->control_number;
        $date = new DateController;
        $today = $date->todayDate();
        DB::beginTransaction();
        try {
            DB::table('pret_bancaires')->where('control_number',$control_number)->update([
                'loan_status'=>'Approuvé',
                'updated_at'=>$today   
            ]);
            DB::commit();
            Alert::success('Succès', 'Demande approuvée avec succès !');
            return redirect()->route('admin.pret.demande');
        } catch (\Exception $e) {
            DB::rollback();
            Alert::error('Erreur', 'Une erreur est survenue.');
            return redirect()->back();
        }
        
    }
}