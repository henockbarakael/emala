<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class InterestRateSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('interest_rates')->insert([
            'interest_rate' => 8.00,
            'created_at' => now(),
            'updated_at' => now(),
        ]);
    }
}
