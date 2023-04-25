<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateAccountsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('accounts', function (Blueprint $table) {
            $table->id();
            $table->string('acnumber')->index()->unique();
            $table->string('account_level')->default(0)->comment('1=> Chief Teller, 0 => Teller');
            $table->bigInteger('user_id')->unsigned()->nullable();
            $table->bigInteger('branche_id')->unsigned()->nullable();
            $table->decimal('balance',10,2)->default(0.00);
            $table->string('currency')->nullable();
            $table->tinyInteger('status')->nullable()->comment('1=> Active, 2 => Not Active, 3 => Blocked');
            $table->foreign('branche_id')->references('id')->on('branches');
            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
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
        Schema::dropIfExists('accounts');
    }
}
