<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePretAmortissementsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('pret_amortissements', function (Blueprint $table) {
            $table->id();
            $table->string('control_number')->nullable();
            $table->string('date')->nullable();
            $table->decimal('payment_amount',10,2)->nullable();
            $table->decimal('interest_paid',10,2)->nullable();
            $table->decimal('principal_paid',10,2)->nullable();
            $table->decimal('remaining_balance',10,2)->nullable();
            $table->string('currency')->nullable();
            $table->bigInteger('agency_id')->unsigned()->nullable();
            $table->foreign('agency_id')->references('id')->on('agencies')->onDelete('cascade');

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
        Schema::dropIfExists('pret_amortissements');
    }
}
