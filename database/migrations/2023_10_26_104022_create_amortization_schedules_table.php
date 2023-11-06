<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateAmortizationSchedulesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('amortization_schedules', function (Blueprint $table) {
            $table->id();
            $table->integer('payment_number');
            $table->date('payment_date');
            $table->decimal('interest', 10, 2);
            $table->decimal('principal', 10, 2);
            $table->decimal('remaining_balance', 10, 2);
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
        Schema::dropIfExists('amortization_schedules');
    }
}
