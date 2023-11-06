<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        $this->call(UserSeeder::class);
        $this->call(AgencySeeder::class);
        $this->call(WalletSeeder::class);
        $this->call(CashierSeeder::class);
        $this->call(UserInfosSeeder::class);
    }
}
