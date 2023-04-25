<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTransactionsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('transactions', function (Blueprint $table) {
            $table->id();
            $table->string('sender_firstname')->index()->nullable();
            $table->string('sender_lastname')->index()->nullable();
            $table->string('sender_phone')->index()->nullable();
            $table->decimal('amount', 20, 2)->nullable()->default(0.00);
            $table->decimal('money_received', 20, 2)->nullable()->default(0.00);
            $table->decimal('remise', 20, 2)->nullable()->default(0.00);
            $table->decimal('fees', 20, 2)->nullable()->default(0.00);
            $table->bigInteger('currency_id')->unsigned()->index()->nullable();
            $table->foreign('currency_id')->references('id')->on('currencies')->onUpdate('cascade')->onDelete('cascade');
            $table->string('reference')->nullable();
            $table->string('receiver_firstname')->index()->nullable();
            $table->string('receiver_lastname')->index()->nullable();
            $table->string('receiver_phone')->index()->nullable();
            $table->string('transaction_date')->nullable();
            $table->string('status')->comment('Pending,Success,Refund,Blocked');
            $table->string('type')->comment('Withdrawal,Transferred,Deposit,Payment');
            $table->string('action');
            $table->bigInteger('payment_method')->unsigned()->index()->nullable();
            $table->foreign('payment_method')->references('id')->on('payment_methods')->onUpdate('cascade')->onDelete('cascade');
            $table->string('note')->nullable();
            $table->string('impact')->nullable();
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
        Schema::dropIfExists('transactions');
    }
}
