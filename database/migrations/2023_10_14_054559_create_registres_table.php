<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateRegistresTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('registres', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('caissier_id');
            $table->decimal('montant');
            $table->timestamp('date')->nullable();
            $table->timestamps();

            $table->foreign('caissier_id')->references('id')->on('cashiers')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('registres');
    }
}
