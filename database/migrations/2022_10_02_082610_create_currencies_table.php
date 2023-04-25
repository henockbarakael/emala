<?php

use App\Models\Currency;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

class CreateCurrenciesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('currencies', function (Blueprint $table) {
            $table->id();
            $table->string('name', 100)->nullable();
            $table->char('symbol', 50)->default('Fc');
            $table->decimal('rate', 20,8)->default(0.00000000);
            $table->string('logo', 100)->nullable();
            $table->string('status')->default('Active')->comment('Active, Inactive');
            $table->timestamps();
        });

        DB::table('currencies')->insert([
            ['name' => 'CDF','symbol' => 'Fc'],
            ['name' => 'USD','symbol' => '$'],
        ]);
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('currencies');
    }
}
