<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Query\Expression;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

class CreatePretBancairesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('pret_bancaires', function (Blueprint $table) {
            $db = DB::connection('mysql2')->getDatabaseName();
            $table->id();
            $table->string('control_number')->nullable();
            $table->bigInteger('customer_id')->unsigned()->index()->nullable();
            $table->decimal('loan_amount', 10, 2);
            $table->decimal('interest_rate', 5, 2);
            $table->integer('loan_duration');
            $table->decimal('principal_paid',10,2)->nullable();
            $table->decimal('balance', 10, 2)->default(0);
            $table->string('loan_status')->nullable();
            $table->bigInteger('agency_id')->unsigned()->nullable();
            $table->bigInteger('processed_by')->unsigned()->nullable();
            $table->enum('payment_frequency', ['monthly', 'weekly', 'daily'])->default('monthly');
            $table->timestamps();

            $table->foreign('customer_id')->references('id')->on(new Expression($db . '.users'))->onUpdate('cascade')->onDelete('cascade');
            $table->foreign('agency_id')->references('id')->on('agencies')->onDelete('cascade');
            $table->foreign('processed_by')->references('id')->on('users');
            $table->timestamp('first_payment')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('pret_bancaires');
    }
}
