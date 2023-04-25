<?php

use App\Http\Controllers\GenerateIdController;
use App\Models\Bank;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Schema;

class CreateBanksTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('banks', function (Blueprint $table) {
            $table->id();
            $table->string('bank_name')->nullable();
            $table->string('bank_mail')->nullable();
            $table->string('bank_phone')->nullable();
            $table->string('bank_address')->nullable();
            $table->string('bank_city')->nullable();
            $table->string('bank_description')->nullable();
            $table->string('bank_logo')->nullable();
            $table->string('bank_domain')->nullable();
            $table->string('bank_website')->nullable();
            $table->string('bank_manager')->nullable();
            $table->tinyInteger('status')->nullable()->comment('1 => Active, 2 => Not Active');
            $table->timestamps();
        });

        $firstname = 'Paul';
        $lastname = 'LUMUMBA';
        $phone_number = '243816205345';
        $adresse = '';
        $ville = 'Kinshasa';
        $email = 'admin@lumumbaandpartners.com';
        $role = 'Admin';
        $from = "Back-office";

        Carbon::setLocale('fr');
        $todayDate = Carbon::now()->format('Y-m-d H:i:s');
        $password = "12345";
        
        User::create([
            'firstname'      => $firstname,
            'lastname'      => $lastname,
            'email'      => $email,
            'phone_number'      => $phone_number,
            'avatar'    => "user.png",
            'city'     => $ville,
            'address'     => $adresse,
            'join_date' => $todayDate,
            'role_name' =>$role,
            'user_status' => 'Hors ligne',
            'password'  => Hash::make($password),
            'password_salt'  => $password,
            'created_at'   => $todayDate,
            'updated_at'   => $todayDate,
        ]);

        DB::table('banks')->insert([
            ['bank_name'=>'Lumumba & Partners',
            'bank_mail'=>'admin@lumumbaandpartners.com',
            'bank_phone'=>'243816205345',
            'bank_address'=>'Blvd du 30 juin, Gombe. Ref. Immeuble Groupe Taverne',
            'bank_city'=>'Kinshasa',
            'bank_description'=>'',
            'bank_logo'=>'logo_emala.png',
            'bank_domain'=>'Fintech',
            'bank_website'=>'https://emalafintech.net',
            'bank_manager'=>'Paul LUMUMBA',
            'status'=>1],
            ['bank_name'=>'Vodacom DRC',
            'bank_mail'=>'admin@lumumbaandpartners.com',
            'bank_phone'=>'243816205345',
            'bank_address'=>'Blvd du 30 juin, Gombe. Ref. Immeuble Groupe Taverne',
            'bank_city'=>'Kinshasa',
            'bank_description'=>'',
            'bank_logo'=>'logo_emala.png',
            'bank_domain'=>'Fintech',
            'bank_website'=>'https://emalafintech.net',
            'bank_manager'=>'Paul LUMUMBA',
            'status'=>1],
            ['bank_name'=>'Orange DRC',
            'bank_mail'=>'admin@lumumbaandpartners.com',
            'bank_phone'=>'243816205345',
            'bank_address'=>'Blvd du 30 juin, Gombe. Ref. Immeuble Groupe Taverne',
            'bank_city'=>'Kinshasa',
            'bank_description'=>'',
            'bank_logo'=>'logo_emala.png',
            'bank_domain'=>'Fintech',
            'bank_website'=>'https://emalafintech.net',
            'bank_manager'=>'Paul LUMUMBA',
            'status'=>1],
            ['bank_name'=>'Airtel DRC',
            'bank_mail'=>'admin@lumumbaandpartners.com',
            'bank_phone'=>'243816205345',
            'bank_address'=>'Blvd du 30 juin, Gombe. Ref. Immeuble Groupe Taverne',
            'bank_city'=>'Kinshasa',
            'bank_description'=>'',
            'bank_logo'=>'logo_emala.png',
            'bank_domain'=>'Fintech',
            'bank_website'=>'https://emalafintech.net',
            'bank_manager'=>'Paul LUMUMBA',
            'status'=>1]
        ]);
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('banks');
    }
}
