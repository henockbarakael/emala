<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateRechargeRequestsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('recharge_requests', function (Blueprint $table) {
            $table->id();
            $table->string('request_id')->index()->nullable();
            $table->bigInteger('requester_id')->unsigned()->nullable();
            $table->bigInteger('assigned_id')->unsigned()->nullable();
            $table->bigInteger('branche_id')->unsigned()->nullable();
            $table->string('subject')->nullable();
            $table->double('amount',20, 2)->nullable();
            $table->string('currency')->nullable();
            $table->string('status')->default('En attente');
            $table->foreign('requester_id')->references('id')->on('users')->onDelete('cascade');
            $table->foreign('assigned_id')->references('id')->on('users')->onDelete('cascade');
            $table->foreign('branche_id')->references('id')->on('branches')->onDelete('cascade');
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
        Schema::dropIfExists('recharge_requests');
    }
}
