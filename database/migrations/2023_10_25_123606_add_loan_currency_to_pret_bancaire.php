<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddLoanCurrencyToPretBancaire extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('pret_bancaires', function (Blueprint $table) {
            $table->string('loan_currency')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('pret_bancaires', function (Blueprint $table) {
            $table->dropColumn('loan_currency');
        });
    }
}
