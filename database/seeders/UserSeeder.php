<?php

namespace Database\Seeders;

use App\Http\Controllers\Backend\GenerateIdController;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
{
    $acnumber = new GenerateIdController;
    $password = $acnumber->password();

    User::create([
        'email' => 'admin@emalafintech.net',
        'phone_number' => '243818993005',
        'firstname' => 'Paul',
        'lastname' => 'LUMUMBA',
        'role_name' => 'Admin',
        'password_salt' => $password,
        'avatar' => "user.png",
        'join_date' => now(),
        'password' => Hash::make($password),
    ]);

    User::create([
        'email' => 'barakael@emalafintech.net',
        'phone_number' => '243828584688',
        'role_name' => 'Manager',
        'firstname' => 'Henock',
        'lastname' => 'BARAKAEL',
        'password_salt' => $password,
        'avatar' => "user.png",
        'join_date' => now(),
        'password' => Hash::make($password),
    ]);

    User::create([
        'email' => 'louise@emalafintech.net',
        'phone_number' => '243826732282',
        'role_name' => 'Cashier',
        'firstname' => 'Louise',
        'lastname' => 'MBUTSHU',
        'password_salt' => $password,
        'avatar' => "user.png",
        'join_date' => now(),
        'password' => Hash::make($password),
    ]);
}
}
