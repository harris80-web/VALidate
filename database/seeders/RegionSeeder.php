<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class RegionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Disable foreign key checks
        DB::statement('SET FOREIGN_KEY_CHECKS=0;');

        // Truncate the table
        DB::table('regions')->truncate();

        // Re-enable foreign key checks
        DB::statement('SET FOREIGN_KEY_CHECKS=1;');

        // List of PH regions
        $regions = [
            'Region I – Ilocos Region',
            'Region II – Cagayan Valley',
            'Region III – Central Luzon',
            'Region IV‑A – CALABARZON',
            'MIMAROPA Region',
            'Region V – Bicol Regionn',
            'Region VI – Western Visayas',
            'Region VII – Central Visayas',
            'Region VIII – Eastern Visayas',
            'Region IX – Zamboanga Peninsula',
            'Region X – Northern Mindanao',
            'Region XI – Davao Region',
            'Region XII – SOCCSKSARGEN',
            'Region XIII – Caraga',
            'NCR – National Capital Region',
            'CAR – Cordillera Administrative Region',
            'BARMM – Bangsamoro Autonomous Region in Muslim Mindanao',
            'NIR – Negros Island Region'
        ];

        // Insert regions
        foreach ($regions as $region) {
            DB::table('regions')->insert([
                'name' => $region,
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }
    }
}
