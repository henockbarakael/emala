<?php

namespace Database\Seeders;

use App\Models\UserInfo;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class UserInfosSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
{
    $users = DB::table('users')->get();

    foreach ($users as $user) {
        UserInfo::create([
            'user_id' => $user->id,
            'firstname' => $user->firstname,
            'lastname' => $user->lastname,
            'middlename' => $user->middlename,
            'phone_number' => $user->phone_number,
            'address' => $user->address,
            'city' => $user->city,
            'country' => $user->country,
            'created_by' => 1, // Remplacez par l'ID de l'utilisateur créateur si nécessaire
            'agency_id' => 1, // Remplacez par l'ID de la branche si nécessaire
        ]);
    }
}
}
