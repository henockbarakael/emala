<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCashRegistersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('cash_registers', function (Blueprint $table) {
            $table->id();
            $table->bigInteger('account_id')->unsigned()->nullable();
            $table->foreign('account_id')->references('id')->on('accounts')->onDelete('cascade');
            $table->bigInteger('branche_id')->unsigned()->nullable();
            $table->decimal('opening_balance',20,2)->default(0.00);
            $table->decimal('added_fund',20,2)->default(0.00);
            $table->decimal('closing_balance',20,2)->default(0.00);
            $table->decimal('gap',20,2)->default(0.00);
            $table->string('currency')->nullable();
            $table->date('opening_date')->nullable();
            $table->date('closing_date')->nullable();
            $table->string('status')->default('closed')->comment('Closed,Opened');
            $table->foreign('branche_id')->references('id')->on('branches');
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
        Schema::dropIfExists('cash_registers');
    }
}
