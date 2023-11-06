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
            $table->bigInteger('cashier_id')->unsigned()->nullable();
            $table->foreign('cashier_id')->references('id')->on('cashiers')->onDelete('cascade');
            $table->unsignedBigInteger('agency_id');
            $table->foreign('agency_id')->references('id')->on('agencies')->onDelete('cascade');
            $table->decimal('opening_balance',20,2)->default(0.00);
            $table->decimal('added_fund',20,2)->default(0.00);
            $table->decimal('closing_balance',20,2)->default(0.00);
            $table->decimal('gap',20,2)->default(0.00);
            $table->string('currency')->nullable();
            $table->dateTime('opening_date')->nullable();
            $table->dateTime('closing_date')->nullable();
            $table->timestamp('logout_time')->nullable();
            $table->string('status')->default('closed')->comment('Closed,Opened');
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
