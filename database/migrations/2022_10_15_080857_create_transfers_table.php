<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTransfersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('transfers', function (Blueprint $table) {
            $table->id();
            $table->string('sender_phone')->index()->nullable();
            // $table->foreign('sender_phone')->references('phone')->on('users')->onUpdate('cascade')->onDelete('cascade');
            $table->string('receiver_phone')->index()->nullable();
            // $table->foreign('receiver_phone')->references('phone')->on('users')->onUpdate('cascade')->onDelete('cascade');
            $table->bigInteger('currency_id')->unsigned()->index()->nullable();
            $table->foreign('currency_id')->references('id')->on('currencies')->onUpdate('cascade')->onDelete('cascade');
            $table->decimal('fees', 20, 2)->nullable()->default(0.000);
            $table->decimal('amount', 20, 2)->nullable()->default(0.00);
            $table->string('reference')->nullable();
            $table->string('note')->nullable();
            $table->string('type')->nullable();
            $table->string('status')->default('Active')->comment('En attente,Envoyé,Annulé');
            $table->bigInteger('branche_id')->unsigned()->nullable();
            $table->bigInteger('user_id')->unsigned()->nullable();
            $table->foreign('user_id')->references('id')->on('users')->onUpdate('cascade')->onDelete('cascade');
            $table->foreign('branche_id')->references('id')->on('branches')->onUpdate('cascade')->onDelete('cascade');
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
        Schema::dropIfExists('transfers');
    }
}
