<?php

namespace Database\Seeders;

use App\Models\Agency;
use App\Models\Wallet;
use Illuminate\Database\Seeder;

class WalletSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        // Récupérer toutes les agences
        $agencies = Agency::all();

        // Définir les devises disponibles
        $currencies = ['CDF', 'USD'];

        // Créer un wallet pour chaque agence et chaque devise
        foreach ($agencies as $agency) {
            foreach ($currencies as $currency) {
                Wallet::create([
                    'agency_id' => $agency->id,
                    'balance' => 0,
                    'currency' => $currency,
                ]);
            }
        }
    }
}
