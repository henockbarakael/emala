<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateExternalWithdrawalsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('external_withdrawals', function (Blueprint $table) {
            $table->id();
            $table->string('sender_number')->nullable();
            $table->string('sender_firstname')->nullable();
            $table->string('sender_lastname')->nullable();
            $table->decimal('fees', 20, 2)->nullable()->default(0.00);
            $table->decimal('amount', 20, 2)->nullable()->default(0.00);
            $table->decimal('money_received', 20, 2)->nullable()->default(0.00);
            $table->decimal('remise', 20, 2)->nullable()->default(0.00);
            $table->bigInteger('currency_id')->unsigned()->index()->nullable();
            $table->string('transaction_id')->nullable();
            $table->foreign('currency_id')->references('id')->on('currencies')->onUpdate('cascade')->onDelete('cascade');
            $table->string('status')->default('En attente')->comment('Pending,Success,Refund,Blocked');
            $table->bigInteger('payment_method')->unsigned()->nullable();
            $table->foreign('payment_method')->references('id')->on('payment_methods')->onUpdate('cascade')->onDelete('cascade');
            $table->bigInteger('branche_id')->unsigned()->nullable();
            $table->bigInteger('user_id')->unsigned()->nullable();
            $table->foreign('user_id')->references('id')->on('users')->onUpdate('cascade')->onDelete('cascade');
            $table->foreign('branche_id')->references('id')->on('branches')->onUpdate('cascade')->onDelete('cascade');
            $table->string('type')->comment('Emala, Momo');
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
        Schema::dropIfExists('external_withdrawals');
    }
}
