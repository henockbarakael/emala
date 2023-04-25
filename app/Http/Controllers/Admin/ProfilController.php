<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\__init__;
use App\Http\Controllers\Controller;
use App\Models\Account;
use App\Models\ProfileInformation;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Session;

class ProfilController extends Controller
{
    public function profil(){
        $user = Auth::User();
        Session::put('users', $user);
        $user=Session::get('users');
        $profile = $user->rec_id;
        $user = DB::table('users')->orderBy('id','desc')->get();
        $employees = DB::table('profile_information')->where('rec_id',$profile)->first();
        $initialize = new __init__;
        $closed = $initialize->cash_session();
        $report = $initialize->fond_precedent();
        $report_cdf_on_c = $report['report_cdf_on_c'];
        $report_usd_on_c = $report['report_usd_on_c'];
        if(empty($employees))
        {
            $users = DB::table('users')->where('id',Auth::User()->id)->first();
            $information = DB::table('profile_information')->where('rec_id',$profile)->first();
            $personnal_information = DB::table('personnal_informations')->where('rec_id',$profile)->first();
            $bank_information = DB::table('bank_informations')->where('rec_id',$profile)->first();
            $family_information = DB::table('family_informations')->where('rec_id',$profile)->get();

            if (Auth::user()->role_name == "Gérant") {
                return view('gerant.profile',compact('closed','report_cdf_on_c','report_usd_on_c','family_information','bank_information','information','users','personnal_information'));
            }
            elseif (Auth::user()->role_name == "Caissier") {
                return view('caissier.profile',compact('closed','report_cdf_on_c','report_usd_on_c','family_information','bank_information','information','users','personnal_information'));
            }

            elseif (Auth::user()->role_name == "Gérant") {
                return view('admin.profile',compact('closed','report_cdf_on_c','report_usd_on_c','family_information','bank_information','information','users','personnal_information'));
            }


        }else{

            $rec_id = $employees->rec_id;
            if($rec_id == $profile)
            {
                $users = DB::table('users')->orderBy('id','desc')->get();
                $information = DB::table('profile_information')->where('rec_id',$profile)->first();
                if (Auth::user()->role_name == "Gérant") {
                    return view('gerant.profile',compact('information','users'));
                }
                elseif (Auth::user()->role_name == "Caissier") {
                    return view('caissier.profile',compact('information','users'));
                }
                elseif (Auth::user()->role_name == "Admin") {
                    return view('admin.profile',compact('information','users','closed','report_cdf_on_c','report_usd_on_c'));
                }

            }else{
                $users = DB::table('users')->orderBy('id','desc')->get();
                $information = ProfileInformation::all();
                if (Auth::user()->role_name == "Gérant") {
                    return view('gerant.profile',compact('information','users','closed','report_cdf_on_c','report_usd_on_c'));
                }
                elseif (Auth::user()->role_name == "Caissier") {
                    return view('caissier.profile',compact('information','users','closed','report_cdf_on_c','report_usd_on_c'));
                }
                elseif (Auth::user()->role_name == "Admin") {
                    return view('admin.profile',compact('information','users','closed','report_cdf_on_c','report_usd_on_c'));
                }
            }
        }
    }

    public function profilClient(){
        $user = Auth::User();
        Session::put('users', $user);
        $user=Session::get('users');
        $profile = $user->rec_id;
        $user = DB::table('users')->orderBy('id','desc')->get();
        $employees = DB::table('profile_information')->where('rec_id',$profile)->first();

        $initialize = new __init__;
        $closed = $initialize->cash_session();
        $report = $initialize->fond_precedent();
        $report_cdf_on_c = $report['report_cdf_on_c'];
        $report_usd_on_c = $report['report_usd_on_c'];
        if(empty($employees))
        {
            $users = DB::table('users')->where('id',Auth::User()->id)->first();
            $information = DB::table('profile_information')->where('rec_id',$profile)->first();
            $personnal_information = DB::table('personnal_informations')->where('rec_id',$profile)->first();
            $bank_information = DB::table('bank_informations')->where('rec_id',$profile)->first();
            $family_information = DB::table('family_informations')->where('rec_id',$profile)->get();

            if (Auth::user()->role_name == "Gérant") {
                return view('gerant.profile',compact('closed','report_cdf_on_c','report_usd_on_c','family_information','bank_information','information','users','personnal_information'));
            }
            elseif (Auth::user()->role_name == "Caissier") {
                return view('caissier.profile',compact('closed','report_cdf_on_c','report_usd_on_c','family_information','bank_information','information','users','personnal_information'));
            }

            elseif (Auth::user()->role_name == "Gérant") {
                return view('admin.profile',compact('closed','report_cdf_on_c','report_usd_on_c','family_information','bank_information','information','users','personnal_information'));
            }


        }else{

            $rec_id = $employees->rec_id;
            if($rec_id == $profile)
            {
                $users = DB::table('users')->orderBy('id','desc')->get();
                $information = DB::table('profile_information')->where('rec_id',$profile)->first();
                if (Auth::user()->role_name == "Gérant") {
                    return view('gerant.profile',compact('closed','report_cdf_on_c','report_usd_on_c','information','users'));
                }
                elseif (Auth::user()->role_name == "Caissier") {
                    return view('caissier.profile',compact('closed','report_cdf_on_c','report_usd_on_c','information','users'));
                }
                elseif (Auth::user()->role_name == "Admin") {
                    return view('admin.profile',compact('closed','report_cdf_on_c','report_usd_on_c','information','users'));
                }

            }else{
                $users = DB::table('users')->orderBy('id','desc')->get();
                $information = ProfileInformation::all();
                if (Auth::user()->role_name == "Gérant") {
                    return view('gerant.profile',compact('closed','report_cdf_on_c','report_usd_on_c','information','users'));
                }
                elseif (Auth::user()->role_name == "Caissier") {
                    return view('caissier.profile',compact('closed','report_cdf_on_c','report_usd_on_c','information','users'));
                }
                elseif (Auth::user()->role_name == "Admin") {
                    return view('admin.profile',compact('closed','report_cdf_on_c','report_usd_on_c','information','users'));
                }
            }
        }
    }


    public function profilID($id){
        
        $userid=  Crypt::decrypt($id);
        $initialize = new __init__;
        $response = $initialize->user_account_details($userid);
        $closed = $initialize->cash_session();

        $report = $initialize->fond_precedent();
        $report_cdf_on_c = $report['report_cdf_on_c'];
        $report_usd_on_c = $report['report_usd_on_c'];

        if ($response['success'] == true) {
            $user_id = $response['data']['0']->id;
            $firstname = $response['data']['0']->firstname;
            $lastname = $response['data']['0']->lastname;
            $email = $response['data']['0']->email;
            $role_name = $response['data']['0']->role_name;
            $avatar = $response['data']['0']->avatar;
            $join_date = $response['data']['0']->join_date;
            $phone_number = $response['data']['0']->phone_number;
            $address = $response['data']['0']->address;
            $city = $response['data']['0']->city;
            $balance_cdf = $response['data']['1'];
            $balance_usd = $response['data']['2'];
            $acnumber = $response['data']['3'];
            return view('admin.profile-user',compact('closed','report_cdf_on_c','report_usd_on_c','user_id','avatar','role_name','city','address','phone_number','join_date','email','lastname','firstname','balance_cdf','balance_usd','acnumber'));
        }
    }

    public function profilIDClient($id){
        $userid=  Crypt::decrypt($id);
       
        $initialize = new __init__;
        $response = $initialize->customer_account_details($userid);
        $closed = $initialize->cash_session();


        $report = $initialize->fond_precedent();
        $report_cdf_on_c = $report['report_cdf_on_c'];
        $report_usd_on_c = $report['report_usd_on_c'];

        if ($response['success'] == true) {
            $user_id = $response['data']['0']->id;

            // $username = $response['data']['0']->username;
            $firstname = $response['data']['0']->firstname;
            $lastname = $response['data']['0']->lastname;
            $email = $response['data']['0']->email;
            $role_name = $response['data']['0']->role_name;
            $avatar = $response['data']['0']->avatar;
            $join_date = $response['data']['0']->join_date;
            $phone_number = $response['data']['0']->phone_number;
            $address = $response['data']['0']->address;
            $city = $response['data']['0']->city;
            $amount_cdf_principal = $response['data']['1'];
            $amount_usd_principal = $response['data']['2'];
            $acnumber_principal = $response['data']['3'];
            $amount_cdf_epargne = $response['data']['4'];
            $amount_usd_epargne = $response['data']['5'];
            $acnumber_epargne = $response['data']['6'];
            return view('admin.profile-client',compact('closed','report_cdf_on_c','report_usd_on_c','user_id','avatar','role_name','city','address','phone_number','join_date','email','lastname','firstname','amount_cdf_principal','amount_usd_principal','acnumber_principal','amount_cdf_epargne','amount_usd_epargne','acnumber_epargne'));
        }
    }

    public function historique_profilIDClient($telephone){
        $userphone=  Crypt::decrypt($telephone);

        $user = DB::table('users')->where('phone_number',$userphone)->first();
        $userid = $user->id;
        $role_name = $user->role_name;
        $initialize = new __init__;
        $report = $initialize->fond_precedent();
        $report_cdf_on_c = $report['report_cdf_on_c'];
        $report_usd_on_c = $report['report_usd_on_c'];
       
        $request = new __init__;
        $closed = $request->cash_register();
        if ($role_name == "Customer") {
            $response = $request->customer_account_details($userid);
            if ($response['success'] == true) {
                $user_id = $response['data']['0']->id;
                $firstname = $response['data']['0']->firstname;
                $lastname = $response['data']['0']->lastname;
                $email = $response['data']['0']->email;
                $role_name = $response['data']['0']->role_name;
                $avatar = $response['data']['0']->avatar;
                $join_date = $response['data']['0']->join_date;
                $phone_number = $response['data']['0']->phone_number;
                $address = $response['data']['0']->address;
                $city = $response['data']['0']->city;
                $amount_cdf_principal = $response['data']['1'];
                $amount_usd_principal = $response['data']['2'];
                $acnumber_principal = $response['data']['3'];
                $amount_cdf_epargne = $response['data']['4'];
                $amount_usd_epargne = $response['data']['5'];
                $acnumber_epargne = $response['data']['6'];
                return view('admin.profile-client',compact('closed','report_cdf_on_c','report_usd_on_c','user_id','avatar','role_name','city','address','phone_number','join_date','email','lastname','firstname','amount_cdf_principal','amount_usd_principal','acnumber_principal','amount_cdf_epargne','amount_usd_epargne','acnumber_epargne'));
            }
        }
        else {
            $response = $request->user_account_details($userid);
            if ($response['success'] == true) {
                $user_id = $response['data']['0']->id;
                $firstname = $response['data']['0']->firstname;
                $lastname = $response['data']['0']->lastname;
                $email = $response['data']['0']->email;
                $role_name = $response['data']['0']->role_name;
                $avatar = $response['data']['0']->avatar;
                $join_date = $response['data']['0']->join_date;
                $phone_number = $response['data']['0']->phone_number;
                $address = $response['data']['0']->address;
                $city = $response['data']['0']->city;
                $balance_cdf = $response['data']['1'];
                $balance_usd = $response['data']['2'];
                $acnumber = $response['data']['3'];
                return view('admin.profile-user',compact('closed','report_cdf_on_c','report_usd_on_c','user_id','avatar','role_name','city','address','phone_number','join_date','email','lastname','firstname','balance_cdf','balance_usd','acnumber'));
            }
        }
        

        
    }

    public function senderID($sphone){
        $user_id=  Crypt::decrypt($sphone);
        $initialize = new __init__;
        $closed = $initialize->cash_session();
        $report = $initialize->fond_precedent();
        $report_cdf_on_c = $report['report_cdf_on_c'];
        $report_usd_on_c = $report['report_usd_on_c'];
        $users = DB::table('users')->where('telephone',$user_id)->first();

        $compte_courant = DB::table('comptes')->where('actype',"courant")->orWhere('actype',"agence")->where('user_id',$user_id)->first();
        if ($compte_courant == null) {
            $compte_c_cdf = "null";
            $compte_c_usd = "null";
        }
        else {
            $first_c = $compte_courant->id;
            $compte_c_cdf = DB::table('comptes')->where('id',$first_c)->first();
            $previous_c = $compte_c_cdf->acnumber;
            // get next user id
            $second = Account::where('id', '>', $compte_courant->id)->min('id');
            $compte_c_usd = DB::table('comptes')->where('id',$second)->first();
            $next = $compte_c_usd->acnumber;
        }


        $compte_epargne = DB::table('comptes')->where('actype',"epargne")->where('user_id',$users->id)->first();
        if ($compte_epargne == null) {
            $compte_e_cdf = "null";
            $compte_e_usd = "null";
        }
        else {
            $first_e = $compte_epargne->id;
            $compte_e_cdf = DB::table('comptes')->where('id',$first_e)->first();
            $previous_e = $compte_e_cdf->acnumber;
            // get next user id
            $second_e = Account::where('id', '>', $compte_epargne->id)->min('id');
            $compte_e_usd = DB::table('comptes')->where('id',$second_e)->first();
            $next = $compte_e_usd->acnumber;
        }


        $transactions = DB::table('transactions')->where('sphone',$user_id)->get();

        if (Auth::user()->role_name == "Gérant") {
            return view('gerant.profile-user',compact('closed','report_cdf_on_c','report_usd_on_c','compte_epargne','compte_courant','users','compte_c_cdf','compte_c_usd','transactions','compte_e_cdf','compte_e_usd'));
        }
        elseif (Auth::user()->role_name == "Caissier") {
            return view('caissier.profile-user',compact('closed','report_cdf_on_c','report_usd_on_c','compte_epargne','compte_courant','users','compte_c_cdf','compte_c_usd','transactions','compte_e_cdf','compte_e_usd'));
        }
        elseif (Auth::user()->role_name == "Admin") {
            return view('admin.profile-user',compact('closed','report_cdf_on_c','report_usd_on_c','compte_epargne','compte_courant','users','compte_c_cdf','compte_c_usd','transactions','compte_e_cdf','compte_e_usd'));
        }


    }

    public function receiverID($rphone){
        $user_id=  Crypt::decrypt($rphone);
        $initialize = new __init__;
        $closed = $initialize->cash_session();
        $report = $initialize->fond_precedent();
        $report_cdf_on_c = $report['report_cdf_on_c'];
        $report_usd_on_c = $report['report_usd_on_c'];
        $users = DB::table('users')->where('telephone',$user_id)->first();

        $compte_courant = DB::table('comptes')->where('actype',"courant")->orWhere('actype',"agence")->where('user_id',$user_id)->first();
        if ($compte_courant == null) {
            $compte_c_cdf = "null";
            $compte_c_usd = "null";
        }
        else {
            $first_c = $compte_courant->id;
            $compte_c_cdf = DB::table('comptes')->where('id',$first_c)->first();
            $previous_c = $compte_c_cdf->acnumber;
            // get next user id
            $second = Account::where('id', '>', $compte_courant->id)->min('id');
            $compte_c_usd = DB::table('comptes')->where('id',$second)->first();
            $next = $compte_c_usd->acnumber;
        }


        $compte_epargne = DB::table('comptes')->where('actype',"epargne")->where('user_id',$users->id)->first();
        if ($compte_epargne == null) {
            $compte_e_cdf = "null";
            $compte_e_usd = "null";
        }
        else {
            $first_e = $compte_epargne->id;
            $compte_e_cdf = DB::table('comptes')->where('id',$first_e)->first();
            $previous_e = $compte_e_cdf->acnumber;
            // get next user id
            $second_e = Account::where('id', '>', $compte_epargne->id)->min('id');
            $compte_e_usd = DB::table('comptes')->where('id',$second_e)->first();
            $next = $compte_e_usd->acnumber;
        }


        $transactions = DB::table('transactions')->where('sphone',$user_id)->get();

        if (Auth::user()->role_name == "Gérant") {
            return view('gerant.profile-user',compact('closed','report_cdf_on_c','report_usd_on_c','compte_epargne','compte_courant','users','compte_c_cdf','compte_c_usd','transactions','compte_e_cdf','compte_e_usd'));
        }
        elseif (Auth::user()->role_name == "Caissier") {
            return view('caissier.profile-user',compact('closed','report_cdf_on_c','report_usd_on_c','compte_epargne','compte_courant','users','compte_c_cdf','compte_c_usd','transactions','compte_e_cdf','compte_e_usd'));
        }
        elseif (Auth::user()->role_name == "Admin") {
            return view('admin.profile-user',compact('closed','report_cdf_on_c','report_usd_on_c','compte_epargne','compte_courant','users','compte_c_cdf','compte_c_usd','transactions','compte_e_cdf','compte_e_usd'));
        }


    }
}
