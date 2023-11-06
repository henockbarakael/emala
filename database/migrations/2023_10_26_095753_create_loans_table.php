<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Query\Expression;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

class CreateLoansTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('loans', function (Blueprint $table) {
            $db = DB::connection('mysql2')->getDatabaseName();
            $table->id();
            $table->string('control_number')->nullable();
            $table->bigInteger('customer_id')->unsigned()->index()->nullable();
            $table->decimal('amount', 10, 2);
            $table->string('currency')->nullable();
            $table->bigInteger('interest_rate_id')->unsigned()->nullable();
            $table->integer('duration');
            $table->enum('payment_frequency', ['monthly', 'weekly', 'daily'])->default('monthly');
            $table->decimal('remaining_balance', 10, 2)->nullable();
            $table->string('status')->nullable();
            $table->bigInteger('agency_id')->unsigned()->nullable();
            $table->bigInteger('processed_by')->unsigned()->nullable();
            $table->foreign('customer_id')->references('id')->on(new Expression($db . '.users'))->onUpdate('cascade')->onDelete('cascade');
            $table->foreign('agency_id')->references('id')->on('agencies')->onDelete('cascade');
            $table->foreign('processed_by')->references('id')->on('users');
            $table->foreign('interest_rate_id')->references('id')->on('interest_rates');
            $table->timestamp('first_payment_date')->nullable();
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
        Schema::dropIfExists('loans');
    }
}
