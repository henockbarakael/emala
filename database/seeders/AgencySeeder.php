<?php

namespace Database\Seeders;

use App\Models\Agency;
use Illuminate\Database\Seeder;

class AgencySeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        // Création de l'agence principale "Kinshasa"
        Agency::create([
            'name' => 'Kinshasa',
            'agence_principale_id' => null,
        ]);

        // Création de l'agence filiale "Tshumbe"
        $kinshasa = Agency::where('name', 'Kinshasa')->first();

        Agency::create([
            'name' => 'Tshumbe',
            'agence_principale_id' => $kinshasa->id,
        ]);
    }
}
