<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateMobileMoneyTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('mobile_money', function (Blueprint $table) {
            $table->id();
            $table->string('sender_number')->nullable();
            $table->string('customer_number')->nullable();
            $table->decimal('amount', 20, 2)->nullable()->default(0.00);
            $table->string('currency')->nullable();
            $table->string('comment')->nullable();
            $table->string('action')->nullable();
            $table->string('method')->nullable();
            $table->string('status')->nullable();
            $table->string('reference')->nullable();
            $table->string('telco_reference')->nullable();
            $table->string('final_status')->nullable();
            $table->string('transaction_id')->nullable();
            $table->bigInteger('branche_id')->unsigned()->nullable();
            $table->bigInteger('user_id')->unsigned()->nullable();
            $table->foreign('user_id')->references('id')->on('users')->onUpdate('cascade')->onDelete('cascade');
            $table->foreign('branche_id')->references('id')->on('branches')->onUpdate('cascade')->onDelete('cascade');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('mobile_money');
    }
}
