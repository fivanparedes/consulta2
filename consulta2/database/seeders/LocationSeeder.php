<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use App\Models\Coverage;

class LocationSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('countries')->insert([
            'name' => 'Argentina',
            'code' => '54'
        ]);

        DB::table('provinces')->insert([
            'name' => 'Misiones',
            'country_id' => 1
        ]);

        DB::table('cities')->insert([
            'name' => 'Posadas',
            'zipcode' => 3300,
            'province_id' => 1
        ]);

        DB::table('cities')->insert([
            'name' => 'Apóstoles',
            'zipcode' => 3350,
            'province_id' => 1
        ]);

    }
}