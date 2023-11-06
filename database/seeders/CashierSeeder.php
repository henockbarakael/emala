<?php

namespace Database\Seeders;

use App\Models\Agency;
use App\Models\Cashier;
use App\Models\User;
use Illuminate\Database\Seeder;

class CashierSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        // Récupérer tous les utilisateurs et toutes les agences
        $users = User::where('role_name','Cashier')->get();
        $agencies = Agency::where('id',2)->get();

        $currencies = ['CDF', 'USD'];

        // Créer un cashier pour chaque combinaison utilisateur-agence
        foreach ($users as $user) {
            foreach ($agencies as $agency) {
                foreach ($currencies as $currency) {
                    Cashier::create([
                        'user_id' => $user->id,
                        'agency_id' => $agency->id,
                        'balance' => 0,
                        'currency' => $currency, // Remplacez par la devise souhaitée
                    ]);
                }
            }
        }
    }
}
