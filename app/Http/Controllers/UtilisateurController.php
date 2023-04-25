<?php

namespace App\Http\Controllers;

use App\Http\Controllers\API\Initialize;
use App\Models\Customer;
use App\Models\User;
use Illuminate\Http\Request;
use RealRashid\SweetAlert\Facades\Alert;
use RealRashid\SweetAlert\Storage\AlertSessionStore;

class UtilisateurController extends Controller
{
    public function liste_client(){
        $customers = Customer::all();
        return view('backend.utilisateur.client.liste', compact('customers'));
    }
    public function creation_client(Request $request){
        $request->validate([
            'lastname'      => 'required|string|max:255',
            'firstname' => 'required|string|max:255',
            'phone_number' => 'required|string|min:9|max:12|unique:users',
            'adresse' => 'required|string|max:255',
            'ville' => 'required|string|max:255',
            'pays' => 'required|string|max:255',
        ]);

        $phone= new VerifyNumberController;
        $phone_number = $phone->verify_number($request->phone_number);
        $lastname = $request->lastname;
        $firstname = $request->firstname;
        $adresse = $request->adresse;
        $ville = $request->ville;
        $pays = $request->pays;
        $role = "Customer";

        $initialize = new Initialize;
        $response = $initialize->create_customer($firstname,$lastname,$phone_number,$adresse,$ville,$role,$pays);

        if ($response['success'] == true) {
            Alert::success('Succès','Client créer avec succès!');
            return redirect()->back();
        }
        else {
            Alert::error('Échec!','Erreur lors de la création du compte.');
            return redirect()->back();
        }
    }
    public function modification_client(Request $request){
        $request->validate([
            'lastname'      => 'required|string|max:255',
            'firstname' => 'required|string|max:255',
            'phone_number' => 'required|string|min:9|max:12|unique:users',
            'adresse' => 'required|string|max:255',
            'ville' => 'required|string|max:255',
            'pays' => 'required|string|max:255',
        ]);

        $phone= new VerifyNumberController;
        $phone_number = $phone->verify_number($request->phone_number);
        $lastname = $request->lastname;
        $firstname = $request->firstname;
        $adresse = $request->adresse;
        $ville = $request->ville;
        $pays = $request->pays;
        $user_id = $request->user_id;

        $initialize = new Initialize;
        $response = $initialize->update_customer($firstname,$lastname,$phone_number,$adresse,$ville,$pays, $user_id);

        if ($response['success'] == true) {
            Alert::success('Succès','Client modifié avec succès!');
            return redirect()->back();
        }
        else {
            Alert::error('Échec!','Erreur lors de la modification du compte.');
            return redirect()->back();
        }
    }
    public function supprimer_client(Request $request){
        $user_id = $request->id;
        $initialize = new Initialize;
        $response = $initialize->delete_client($user_id);

        if ($response['success'] == true) {
            Alert::success('Succès', $response['message']);
            return redirect()->back();
        }
        elseif ($response['success'] == false) {
            Alert::error('Echec', $response['message']);
            return redirect()->back();
        } 
    }
    public function liste_caissier(){
        $users = User::join('accounts','users.id','accounts.user_id')
        ->join('branches','accounts.branche_id','branches.id')
        ->select('users.*','accounts.user_id','branches.bname','branches.btownship')
        ->where('users.role_name', "Cashier")->distinct('accounts.user_id')->get();
        return view('backend.utilisateur.caissier.liste', compact('users'));
    }
    public function liste_gerant(){
        $users = User::join('accounts','users.id','accounts.user_id')
        ->join('branches','accounts.branche_id','branches.id')
        ->select('users.*','accounts.user_id','branches.bname','branches.btownship')
        ->where('users.role_name',"!=", "Cashier")->distinct('accounts.user_id')->get();
        return view('backend.utilisateur.gerant.liste', compact('users'));
    }
    public function liste_admin(){
        $users = User::join('accounts','users.id','accounts.user_id')
        ->join('branches','accounts.branche_id','branches.id')
        ->select('users.*','accounts.user_id','branches.bname','branches.btownship')
        ->where('users.role_name',"!=", "Cashier")->distinct('accounts.user_id')->get();
        return view('backend.utilisateur.admin.liste', compact('users'));
    }
    public function creation_admin(Request $request){
        $request->validate([
            'lastname'      => 'required|string|max:255',
            'firstname' => 'required|string|max:255',
            'phone_number' => 'required|string|min:9|max:12|unique:users',
            'adresse' => 'required|string|max:255',
            'ville' => 'required|string|max:255',
            'pays' => 'required|string|max:255',
        ]);

        $phone= new VerifyNumberController;
        $phone_number = $phone->verify_number($request->phone_number);
        $lastname = $request->lastname;
        $firstname = $request->firstname;
        $adresse = $request->adresse;
        $ville = $request->ville;
        $pays = $request->pays;
        $role = "Admin";

        $initialize = new Initialize;
        $response = $initialize->create_admin($firstname,$lastname,$phone_number,$adresse,$ville,$role,$pays);

        if ($response['success'] == true) {
            Alert::success('Succès',$response['message']);
            return redirect()->route('admin.branche.all');
        }
        else {
            Alert::error('Échec!','Erreur lors de la création du compte.');
            return redirect()->back();
        }
    }
    public function modification_admin(Request $request){
        $request->validate([
            'lastname'      => 'required|string|max:255',
            'firstname' => 'required|string|max:255',
            // 'phone_number' => 'required|string|min:9|max:12',
            'adresse' => 'required|string|max:255',
            'ville' => 'required|string|max:255',
            'pays' => 'required|string|max:255',
        ]);

        $phone= new VerifyNumberController;
        $phone_number = $phone->verify_number($request->phone_number);
        $lastname = $request->lastname;
        $firstname = $request->firstname;
        $adresse = $request->adresse;
        $ville = $request->ville;
        $pays = $request->pays;
        $user_id = $request->user_id;
        $password = $request->password;

        $initialize = new Initialize;
        $response = $initialize->update_admin($firstname,$lastname,$phone_number,$adresse,$ville,$pays, $user_id,$password);

        if ($response['success'] == true) {
            Alert::success('Succès','Admin modifié avec succès!');
            return redirect()->back();
        }
        else {
            Alert::error('Échec!','Erreur lors de la modification du compte.');
            return redirect()->back();
        }
    }
    public function supprimer_admin(Request $request){
        $user_id = $request->id;
        $initialize = new Initialize;
        $response = $initialize->delete_admin($user_id);

        if ($response['success'] == true) {
            Alert::success('Succès', $response['message']);
            return redirect()->back();
        }
        elseif ($response['success'] == false) {
            Alert::error('Echec', $response['message']);
            return redirect()->back();
        } 
    }
    public function creation_gerant(Request $request){
        $request->validate([
            'lastname'      => 'required|string|max:255',
            'firstname' => 'required|string|max:255',
            'phone_number' => 'required|string|min:9|max:12|unique:users',
            'adresse' => 'required|string|max:255',
            'ville' => 'required|string|max:255',
            'pays' => 'required|string|max:255',
        ]);

        $phone= new VerifyNumberController;
        $phone_number = $phone->verify_number($request->phone_number);
        $lastname = $request->lastname;
        $firstname = $request->firstname;
        $adresse = $request->adresse;
        $ville = $request->ville;
        $pays = $request->pays;
        $role = "Manager";

        $initialize = new Initialize;
        $response = $initialize->create_gerant($firstname,$lastname,$phone_number,$adresse,$ville,$role,$pays);

        if ($response['success'] == true) {
            Alert::success('Succès',$response['message']);
            return redirect()->route('admin.branche.all');
        }
        else {
            Alert::error('Échec!','Erreur lors de la création du compte.');
            return redirect()->back();
        }
    }
    public function modification_gerant(Request $request){
        $request->validate([
            'lastname'      => 'required|string|max:255',
            'firstname' => 'required|string|max:255',
            // 'phone_number' => 'required|string|min:9|max:12',
            'adresse' => 'required|string|max:255',
            'ville' => 'required|string|max:255',
            'pays' => 'required|string|max:255',
        ]);

        $phone= new VerifyNumberController;
        $phone_number = $phone->verify_number($request->phone_number);
        $lastname = $request->lastname;
        $firstname = $request->firstname;
        $adresse = $request->adresse;
        $ville = $request->ville;
        $pays = $request->pays;
        $user_id = $request->user_id;
        $password = $request->password;

        $initialize = new Initialize;
        $response = $initialize->update_manager($firstname,$lastname,$phone_number,$adresse,$ville,$pays, $user_id,$password);

        if ($response['success'] == true) {
            Alert::success('Succès','Gérant modifié avec succès!');
            return redirect()->back();
        }
        else {
            Alert::error('Échec!','Erreur lors de la modification du compte.');
            return redirect()->back();
        }
    }
    public function supprimer_gerant(Request $request){
        $user_id = $request->id;
        $initialize = new Initialize;
        $response = $initialize->delete_gerant($user_id);

        if ($response['success'] == true) {
            Alert::success('Succès', $response['message']);
            return redirect()->back();
        }
        elseif ($response['success'] == false) {
            Alert::error('Echec', $response['message']);
            return redirect()->back();
        } 
    }
    public function liste_marchand(){
        $users = User::where('role_name','=','Merchant')->get();
        return view('backend.utilisateur.marchand.liste', compact('users'));
    }
}
