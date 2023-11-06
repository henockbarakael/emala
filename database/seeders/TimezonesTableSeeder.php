<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class TimezonesTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $cities = [
            'Kinshasa' => 'Africa/Kinshasa',
            'Lubumbashi' => 'Africa/Lubumbashi',
            'Mbuji-Mayi' => 'Africa/Lubumbashi',
            'Kisangani' => 'Africa/Lubumbashi',
            'Bukavu' => 'Africa/Lubumbashi',
            'Kananga' => 'Africa/Lubumbashi',
            'Likasi' => 'Africa/Lubumbashi',
            'Kolwezi' => 'Africa/Lubumbashi',
            'Tshikapa' => 'Africa/Lubumbashi',
            'Uvira' => 'Africa/Lubumbashi',
            'Goma' => 'Africa/Lubumbashi',
            'Boma' => 'Africa/Kinshasa',
            'Mbandaka' => 'Africa/Kinshasa',
            'Matadi' => 'Africa/Kinshasa',
            'Butembo' => 'Africa/Kinshasa',
            'Mwene-Ditu' => 'Africa/Lubumbashi',
            'Isiro' => 'Africa/Lubumbashi',
            'Kikwit' => 'Africa/Kinshasa',
            'Bandundu' => 'Africa/Kinshasa',
            'Kalemie' => 'Africa/Lubumbashi',
            'Kabinda' => 'Africa/Lubumbashi',
            'Kamina' => 'Africa/Lubumbashi',
            'Mushie' => 'Africa/Kinshasa',
            'Lisala' => 'Africa/Kinshasa',
            'Kenge' => 'Africa/Kinshasa',
            'Bumba' => 'Africa/Kinshasa',
            'Kasongo' => 'Africa/Lubumbashi',
            'Kongolo' => 'Africa/Lubumbashi',
            'Mbanza-Ngungu' => 'Africa/Kinshasa',
            'Beni' => 'Africa/Kinshasa',
            'Lubao' => 'Africa/Kinshasa',
            'Bunia' => 'Africa/Kinshasa',
            'Kipushi' => 'Africa/Lubumbashi',
            'Kisantu' => 'Africa/Kinshasa',
            'Kasangulu' => 'Africa/Kinshasa',
            'Mangai' => 'Africa/Kinshasa',
            'Gemena' => 'Africa/Kinshasa',
            'Kampene' => 'Africa/Lubumbashi',
            'Kindu' => 'Africa/Lubumbashi',
            'Kisangani' => 'Africa/Lubumbashi',
            'Gandajika' => 'Africa/Lubumbashi',
            'Bukama' => 'Africa/Lubumbashi',
            'Likasi' => 'Africa/Lubumbashi',
            'Inongo' => 'Africa/Kinshasa',
            'Buta' => 'Africa/Kinshasa',
            'Bulungu' => 'Africa/Kinshasa',
            'Kambove' => 'Africa/Lubumbashi',
            'Bolobo' => 'Africa/Kinshasa',
            'Kimpese' => 'Africa/Kinshasa',
            'Mweka' => 'Africa/Lubumbashi',
            'Kabare' => 'Africa/Kinshasa',
            'Kasongo-Lunda' => 'Africa/Kinshasa',
            'Luebo' => 'Africa/Lubumbashi',
            'Lodja' => 'Africa/Lubumbashi',
            'Sankuru' => 'Africa/Lubumbashi',
            'Tshumbe' => 'Africa/Lubumbashi',
        ];

        $timezones = [];

        foreach ($cities as $city => $timezone) {
            $timezones[] = [
                'timezone' => $timezone,
                'city' => $city,
            ];
        }

        DB::table('timezones')->insert($timezones);
    }
}
