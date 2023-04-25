<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateBranchesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('branches', function (Blueprint $table) {
            $table->id();
            $table->bigInteger('created_by')->unsigned()->nullable();
            $table->bigInteger('user_id')->unsigned()->nullable();
            $table->bigInteger('bank_id')->unsigned()->nullable();
            $table->string('fullname')->nullable()->comment('nom complet du gérant');
            $table->string('bname')->nullable();
            $table->string('bcode')->nullable();
            $table->string('bphone')->nullable();
            $table->string('bemail')->nullable();
            $table->string('btownship')->nullable();
            $table->string('bcity')->nullable();
            $table->string('btype')->nullable();
            $table->string('status')->default('Inactive');
            $table->integer('bmember')->default(3);
            $table->foreign('user_id')->references('id')->on('users')->onDelete('set null');
            $table->foreign('created_by')->references('id')->on('users');
            $table->foreign('bank_id')->references('id')->on('banks');
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
        Schema::dropIfExists('branches');
    }
}
