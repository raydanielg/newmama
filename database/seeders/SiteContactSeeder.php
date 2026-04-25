<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class SiteContactSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('site_contacts')->updateOrInsert(
            ['id' => 1],
            [
                'phone' => '+255 700 000 000',
                'email' => 'support@malkiakonnect.co.tz',
                'instagram_url' => 'https://instagram.com/malkiakonnect',
                'updated_at' => now(),
                'created_at' => now(),
            ]
        );
    }
}
