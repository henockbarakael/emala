<?php

namespace Database\Seeders;

use App\Models\Agency;
use App\Models\Cashier;
use App\Models\CashRegister;
use Illuminate\Database\Seeder;

class CashRegisterSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        // Obtenez l'ID d'un caissier et d'une agence existants
        $cashierId = Cashier::pluck('id')->first();

        // Créez un enregistrement de caisse fictif
        CashRegister::create([
            'cashier_id' => $cashierId,
            'agency_id' => 2,
            'opening_balance' => 15000.00,
            'added_fund' => 0.00,
            'closing_balance' => 0.00,
            'gap' => 0.00,
            'currency' => 'CDF',
            'opening_date' => now(),
            'closing_date' => null,
            'logout_time' => now(),
            'status' => 'opened',
        ]);

        CashRegister::create([
            'cashier_id' => $cashierId,
            'agency_id' => 2,
            'opening_balance' => 10.00,
            'added_fund' => 0.00,
            'closing_balance' => 0.00,
            'gap' => 0.00,
            'currency' => 'USD',
            'opening_date' => now(),
            'closing_date' => null,
            'logout_time' => now(),
            'status' => 'opened',
        ]);
    }
}
