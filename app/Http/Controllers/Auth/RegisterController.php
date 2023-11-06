<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Controllers\VerifyNumberController;
use Illuminate\Http\Request;
use App\Models\User;
use App\Models\UserInfo;
use Brian2694\Toastr\Facades\Toastr;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;
use Twilio\Rest\Client;
use Auth;

class RegisterController extends Controller
{
    public function register()
    {
        $role = DB::table('role_type_users')->get();
        return view('auth.register',compact('role'));
    }

    public function username($firstname, $lastname) {
        // original username
        $username = "{$firstname[0]}_{$lastname}";
        // if you have  a username column
        $user_count = User::where('username', $username)->count();
        // append digit if exists
        if ($user_count > 0) {
            $username .= "_$user_count";
        }
        return $username;

    }

    public function todayDate(){
        Carbon::setLocale('fr');
        $todayDate = Carbon::now()->format('Y-m-d H:i:s');
        return $todayDate;
    }

    public function storeUser(Request $request)
    {
        $data = $request->validate([
            'firstname'      => 'required|string|max:255',
            'lastname' => 'required|string|max:255',
            'telephone' => 'required|string|max:12',
            'email'     => 'required|string|email|max:255|unique:users',
            'role' => 'required|string|max:255',
            'password'  => 'required|string|min:5|confirmed',
            'password_confirmation' => 'required',
        ]);

        // $dt       = Carbon::now();
        $todayDate = $this->todayDate();

        // $username = $this->username($data['firstname'],$data['lastname']);
        $verify_number = new VerifyNumberController;
        $telephone = $verify_number->verify_number($data['telephone']);
        User::create([
            // 'username'      => $username,
            'firstname'      => $data['firstname'],
            'lastname'      => $data['lastname'],
            'phone_number'      => $telephone,
            'avatar'    => "user.png",
            'email'     => $data['email'],
            'join_date' => $todayDate,
            'role_name' =>$data['role'],
            'user_status' => 'Hors ligne',
            'password'  => Hash::make($data['password']),
            'password_salt'  => $data['password'],
            'created_at'   => $todayDate,
            'updated_at'   => $todayDate,
        ]);

        $user = User::where('phone_number',$telephone)->first();
        $user_id = $user->id;

        $userInfo = UserInfo::where('user_id',Auth::user()->id)->first();
        $branche_id = $userInfo->branche_id;

        $info = [
            'user_id' => $user_id,
            'branche_id' => $branche_id,
            'firstname' => $request->firstname,
            'lastname' => $request->lastname,
            'phone_number' => $user->phone_number,
            'created_at' => now(),
            'updated_at' => now(),
        ];
        UserInfo::insert($info);
        Toastr::success('Compte créer avec succès :)','Succès');
        return redirect('login');
    }

}
