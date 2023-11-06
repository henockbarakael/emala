<?php

namespace App\Http\Controllers\Backend\Admin;

use App\Http\Controllers\Backend\DateController;
use App\Http\Controllers\Backend\GenerateIdController;
use App\Http\Controllers\Controller;
use App\Models\Agency;
use App\Models\Branche;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use RealRashid\SweetAlert\Facades\Alert;

class BranchController extends Controller
{
    public function index(){
        $branches = Agency::all();
        return view('backend.branche.all', compact('branches'));
    }

    public function add(Request $request){

        $request->validate([
            'bname'   => 'required|string|max:255',
        ]);

        $bname = $request->bname;
       
        $kinshasa = Agency::where('name', 'Kinshasa')->first();

        Agency::create([
            'name' => $bname,
            'agence_principale_id' => $kinshasa->id,
        ]);

        Alert::success('Succès', 'Agence créée avec succès!');
        return redirect()->route('admin.branch.all');
    }

    public function edit(Request $request){
        $request->validate([
            'bname'   => 'required|string|max:255',
        ]);

        $bname = $request->bname;
        $branche_id = $request->id;

        $date = new DateController;
        $today = $date->todayDate();

        DB::beginTransaction();
        try {
            DB::table('agencies')->where('id',$branche_id)->update([
                'name' => $bname,
                'updated_at'   => $today,
            ]);
            DB::commit();
            Alert::success('Succès', 'Agence supprimée avec succès !');
            return redirect()->route('admin.branch.all');
        } catch (\Exception $e) {
            DB::rollback();
            Alert::error('Erreur', 'Une erreur est survenue lors de la suppression de l\'agence!');
            return redirect()->back();
        }

    }

    public function delete(Request $request){
        $branche_id = $request->id;
        DB::beginTransaction();
        try {
            DB::table('agencies')->delete($branche_id);
            DB::commit();
            Alert::success('Succès', 'Agence supprimée avec succès !');
            return redirect()->route('admin.branch.all');
        } catch (\Exception $e) {
            DB::rollback();
            Alert::error('Erreur', 'Une erreur est survenue lors de la suppression de l\'agence!');
            return redirect()->back();
        }
        
    }

    public function recharge(){
        $branches = Branche::all();
        return view('backend.branche.all', compact('branches'));
    }
}