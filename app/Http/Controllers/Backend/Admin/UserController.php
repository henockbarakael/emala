<?php

namespace App\Http\Controllers\Backend\Admin;

use App\Http\Controllers\Backend\DateController;
use App\Http\Controllers\Controller;
use App\Models\Account;
use App\Models\Agency;
use App\Models\Branche;
use App\Models\Cashier;
use App\Models\Manager;
use App\Models\User;
use App\Models\UserInfo;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\DB;
use RealRashid\SweetAlert\Facades\Alert;

class UserController extends Controller
{

    public function branche_id()
    {
        $userInfo = UserInfo::where('user_id',Auth::user()->id)->first();
        $branche_id = $userInfo->branche_id;
        return $branche_id;
    }

    public function user_account($id)
    {
        $user_id = Crypt::decrypt($id);
        $data = $this->userDetails($user_id);
        
        return view('backend.admin.user.account', compact('data'));
    }

    public function userDetails($userID)
{
    $user = User::find($userID);
   

    if ($user) {
        if ($user->isCashier()) {
            // Utilisateur de type cashier
            $cashier = $user->cashier;
            $agency = $cashier->agency->name;
            $balanceCDF = $cashier->getCdfBalance($user->id);
            $balanceUSD = $cashier->getUsdBalance($user->id);

            return [
                'type' => 'cashier',
                'agency' => $agency,
                'user' => $user,
                'balance_cdf' => $balanceCDF,
                'balance_usd' => $balanceUSD,
            ];
        } elseif ($user->isManager()) {
            // Utilisateur de type manager
            $manager = $user->manager;
            $agency = $manager->agency;
            $agencyName = $manager->agency->name;
            $cashiersCount = $agency->cashiers()->count();
            
            $balanceCDF = $user->manager->getCdfBalance();
            $balanceUSD = $user->manager->getUsdBalance();

    

            return [
                'type' => 'manager',
                'agency' => $agencyName,
                'user' => $user,
                'cashiers_count' => $cashiersCount,
                'balance_cdf' => $balanceCDF,
                'balance_usd' => $balanceUSD,
            ];
        }
    } else {
        // Utilisateur non trouvé
    }
}
    public function index(){

        $users = User::with('cashier.agency', 'manager.agency')->where('role_name','!=','Admin')->get();
        $agences = Agency::all();
        return view('backend.admin.user.index', compact('users','agences'));
    }

    public function compte($length = 8) {
        $characters = '0123456789';
        $charactersLength = strlen($characters);
        $randomString = 'T';
        for ($i = 0; $i < $length; $i++) {
            $randomString .= $characters[rand(0, $charactersLength - 1)];
        }
        return $randomString;
    }

    public function add(Request $request){
        $request->validate([
            'firstname' => ['required', 'string', 'max:255'],
            'lastname' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users'],
            'phone_number' => ['required', 'string', 'min:9'],
            'role' => ['required', 'string', 'max:255'],
        ]);

        $date = new DateController;
        $today = $date->todayDate();

        $compte = $this->compte();

        $acnumber = new GenerateIdController;
        $password = $acnumber->password();

        $phone= new VerifyNumberController;
        $phone_number = $phone->verify_number($request->phone_number);
        
        $new_user = User::insert([
            'email' => $request['email'],
            'phone_number' => $phone_number,
            'role_name' => $request['role'],
            'password_salt' => $password,
            'created_at' => $today,
            'updated_at' => $today,
            'avatar' => "user.png",
            'password' => Hash::make($password),
        ]);

        if ($new_user) {

            $user = User::where('phone_number',$phone_number)->first();
            $user_id = $user->id;

            $userInfo = UserInfo::where('user_id',Auth::user()->id)->first();
            $branche_id = $userInfo->branche_id;

            $info = [
                'user_id' => $user_id,
                'branche_id' => $branche_id,
                'firstname' => $request->firstname,
                'lastname' => $request->lastname,
                'phone_number' => $phone_number,
                'created_at' => $today,
                'updated_at' => $today,
            ];
            $newinfo = UserInfo::insert($info);
            if ($newinfo) {
                
                $walletcode = $acnumber->walletcode();
                $data = [
                    [
                        'user_id' => $user_id,
                        'w_code' => $walletcode,
                        'acnumber' => $compte,
                        'currency' => "CDF",
                        
                        'created_at' => $today,
                        'branche_id' => $branche_id,
                        'updated_at' => $today
                    ],
                    [
                        'user_id' => $user_id,
                        'w_code' => $walletcode,
                        'acnumber' => $compte,
                        'currency' => "USD",
                        
                        'branche_id' => $branche_id,
                        'created_at' => $today,
                        'updated_at' => $today
                    ]

                ];
                Account::insert($data);
                Alert::success('Succès', 'Utilisateur créé avec succès!');
                return redirect()->route('admin.user.all');
            }
        }

    }

    public function edit(Request $request){
        $request->validate([
            'lastname'      => 'required|string|max:255',
            'firstname' => 'required|string|max:255',
            'phone_number' => 'required|string|min:9|max:12',
            'branche' => 'required|string|max:255',
        ]);

        $phone_number = $request->phone_number;
        $lastname = $request->lastname;
        $firstname = $request->firstname;

        $branche = Branche::where('bname',$request->branche)->first();
        $branche_id = $branche->id;

        $user = User::where('phone_number',$phone_number)->first();
        $user_id = $user->id;

        $date = new DateController;
        $today = $date->todayDate();
        DB::beginTransaction();
        try {
            DB::table('user_infos')->where('phone_number',$phone_number)->update([
                'firstname' => $firstname,
                'lastname' => $lastname,
                'branche_id' => $branche_id,
                'updated_at'   => $today,
            ]);

            DB::table('accounts')->where('user_id',$user_id)->update([
                'branche_id' => $branche_id,
                'updated_at'   => $today,
            ]);
        
            DB::commit();
            Alert::success('Succès', 'Infomation mise à jour avec succès !');
            return redirect()->route('admin.user.all');
        } catch (\Exception $e) {
            DB::rollback();
            Alert::success('Succès', 'Une erreur est survenue lors de la modification de l\'utilisateur!');
            return redirect()->route('admin.user.all');
        }


    }

    public function delete(Request $request){
        $employee_id = $request->id;
        $employee = User::where('id',$employee_id)->first();
        $user_id = $employee->user_id;
        DB::beginTransaction();
        try {
            DB::table('users')->delete($user_id);
            DB::table('user_infos')->delete($employee_id);
        
            DB::commit();
            Alert::success('Succès', 'Client supprimé avec succès !');
            return redirect()->route('admin.user.all');
        } catch (\Exception $e) {
            DB::rollback();
            Alert::success('Succès', 'Une erreur est survenue lors de la suppression du client!');
            return redirect()->route('admin.user.all');
        }
        
    }
}
