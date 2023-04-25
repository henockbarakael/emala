<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Query\Expression;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

class CreateWithdrawalsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('withdrawals', function (Blueprint $table) {
            $db = DB::connection('mysql2')->getDatabaseName();
            $table->id();
            $table->bigInteger('customer_id')->unsigned()->index()->nullable();
            $table->foreign('customer_id')->references('id')->on(new Expression($db . '.users'))->onUpdate('cascade')->onDelete('cascade');
            // $table->bigInteger('wallet_id')->unsigned()->index()->nullable();
            // $table->foreign('wallet_id')->references('id')->on('wallets')->onUpdate('cascade')->onDelete('cascade');
            $table->decimal('fees', 20, 2)->nullable()->default(0.00);
            $table->decimal('amount', 20, 2)->nullable()->default(0.00);
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
        Schema::dropIfExists('withdrawals');
    }
}
