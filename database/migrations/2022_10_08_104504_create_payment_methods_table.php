<?php

use App\Models\PaymentMethod;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

class CreatePaymentMethodsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('payment_methods', function (Blueprint $table) {
            $table->id();
            $table->string('method', 50)->nullable();
            $table->string('api_key', 50)->nullable();
            $table->string('api_secrete', 50)->nullable();
            $table->string('callback_url_debit', 100)->nullable();
            $table->string('callback_url_credit', 100)->nullable();
            $table->string('logo')->nullable();
            $table->string('status')->default('Active')->comment('Active, Inactive');
            $table->timestamps();
        });

        DB::table('payment_methods')->insert([
            ['method'=>'Emala Gateway','api_key'=>'','api_secrete'=>'','callback_url_debit'=>'','callback_url_credit'=>'','logo'=>'logo_emala.png','status'=>'Active'],
            ['method'=>'FreshPay Gateway','api_key'=>'jW]e%IY;ICOu7Hs4b','api_secrete'=>'jz1rwlMY@ueJ1FkO@b','callback_url_debit'=>'https://dashboard.emalafintech.net/api/v1/debit/callback','callback_url_credit'=>'https://dashboard.emalafintech.net/api/v1/credit/callback','logo'=>'logo_freshpay.png','status'=>'Active'],
            ['method'=>'Mpesa Gateway','api_key'=>'','api_secrete'=>'','callback_url_debit'=>'','callback_url_credit'=>'','logo'=>'logo_mpesa.png','status'=>'Inactive'],
            ['method'=>'Orange Money Gateway','api_key'=>'','api_secrete'=>'','callback_url_debit'=>'','callback_url_credit'=>'','logo'=>'logo_orange.png','status'=>'Inactive'],
            ['method'=>'Airtel Money Gateway','api_key'=>'','api_secrete'=>'','callback_url_debit'=>'','callback_url_credit'=>'','logo'=>'logo_airtel.png','status'=>'Inactive'],
            ['method'=>'AfriMoney Gateway','api_key'=>'','api_secrete'=>'','callback_url_debit'=>'','callback_url_credit'=>'','logo'=>'logo_africell.png','status'=>'Inactive'],
        ]);
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('payment_methods');
    }
}
