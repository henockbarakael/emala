<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateExternalTransfersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('external_transfers', function (Blueprint $table) {
            $table->id();
            $table->bigInteger('user_id')->unsigned()->nullable();
            $table->bigInteger('branche_id')->unsigned()->nullable();
            $table->string('sender_phone')->nullable();
            $table->string('sender_first')->nullable();
            $table->string('sender_last')->nullable();
            $table->decimal('amount',10,2)->nullable();
            $table->decimal('money_received', 20, 2)->nullable()->default(0.00);
            $table->decimal('remise', 20, 2)->nullable()->default(0.00);
            $table->decimal('fees',10,2)->nullable();
            $table->string('currency')->nullable();
            $table->string('receiver_phone')->nullable();
            $table->string('receiver_first')->nullable();
            $table->string('receiver_last')->nullable();
            $table->string('reference')->nullable();
            $table->bigInteger('payment_method')->unsigned()->nullable();
            $table->string('status')->nullable()->comment('0:pending,1:successful,2:rejected');
            $table->string('status_description')->nullable();
            $table->foreign('user_id')->references('id')->on('users')->onUpdate('cascade')->onDelete('cascade');
            $table->foreign('branche_id')->references('id')->on('branches')->onUpdate('cascade')->onDelete('cascade');
            $table->foreign('payment_method')->references('id')->on('payment_methods')->onUpdate('cascade')->onDelete('cascade');
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
        Schema::dropIfExists('external_transfers');
    }
}
