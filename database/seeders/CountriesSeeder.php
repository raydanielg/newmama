<?php

namespace Database\Seeders;

use App\Models\Country;
use Illuminate\Database\Seeder;

class CountriesSeeder extends Seeder
{
    public function run(): void
    {
        $rows = [
            ['name' => 'Tanzania', 'iso2' => 'TZ', 'iso3' => 'TZA', 'phone_code' => '+255', 'sort_order' => 1],
            ['name' => 'Kenya', 'iso2' => 'KE', 'iso3' => 'KEN', 'phone_code' => '+254', 'sort_order' => 2],
            ['name' => 'Uganda', 'iso2' => 'UG', 'iso3' => 'UGA', 'phone_code' => '+256', 'sort_order' => 3],
            ['name' => 'Rwanda', 'iso2' => 'RW', 'iso3' => 'RWA', 'phone_code' => '+250', 'sort_order' => 4],
            ['name' => 'Burundi', 'iso2' => 'BI', 'iso3' => 'BDI', 'phone_code' => '+257', 'sort_order' => 5],
            ['name' => 'DR Congo', 'iso2' => 'CD', 'iso3' => 'COD', 'phone_code' => '+243', 'sort_order' => 6],
            ['name' => 'Zambia', 'iso2' => 'ZM', 'iso3' => 'ZMB', 'phone_code' => '+260', 'sort_order' => 7],
            ['name' => 'Malawi', 'iso2' => 'MW', 'iso3' => 'MWI', 'phone_code' => '+265', 'sort_order' => 8],
            ['name' => 'Mozambique', 'iso2' => 'MZ', 'iso3' => 'MOZ', 'phone_code' => '+258', 'sort_order' => 9],
        ];

        foreach ($rows as $r) {
            Country::query()->updateOrCreate(
                ['name' => $r['name']],
                [
                    'iso2' => $r['iso2'],
                    'iso3' => $r['iso3'],
                    'phone_code' => $r['phone_code'],
                    'is_active' => true,
                    'sort_order' => $r['sort_order'],
                ]
            );
        }
    }
}
