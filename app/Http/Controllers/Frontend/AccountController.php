<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Backend\DateController;
use App\Http\Controllers\Backend\GenerateIdController;
use App\Http\Controllers\Controller;
use App\Models\Account;
use App\Models\UserInfo;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AccountController extends Controller
{
    public function compte($customer_id){

        $user_role = Auth::user()->role_name;
        $user_id = Auth::user()->id;
        $date = new DateController;
        $today = $date->todayDate();

        $code = new GenerateIdController;
        $acnumber = $code->acnumber();
        $saving = $code->saving();

       $manager = UserInfo::where('user_id',$user_id)->first();
        $branche_id = $manager->branche_id;
        

        $record = [
            [
            'acnumber' => $acnumber,
            'customer_id' => $customer_id,
            'branche_id' => $branche_id,
            'actype' => 'current',
            'created_at' => $today,
            'updated_at' => $today,
            ],
            [
                'acnumber' => $saving,
                'customer_id' => $customer_id,
                'branche_id' => $branche_id,
                'actype' => 'saving',
                'created_at' => $today,
                'updated_at' => $today,
            ]
        ];

        Account::insert($record);
    }
}
