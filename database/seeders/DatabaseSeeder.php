<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // User::factory(10)->create();

        DB::table('users')->updateOrInsert(
            ['email' => 'test@example.com'],
            [
                'name' => 'Test User',
                'password' => bcrypt('password'),
            ]
        );

        $this->call([
            AdminSeeder::class,
            AccountingSeeder::class,
            CustomerSeeder::class,
            CustomerLedgerSeeder::class,
            SupplierSeeder::class,
            VendorLedgerSeeder::class,
            ProductSeeder::class,
            SiteContactSeeder::class,
            TestimonialSeeder::class,
            ArticleCategorySeeder::class,
            ArticleSeeder::class,
            RegionDistrictSeeder::class,
            DistrictSeeder::class,
            CountriesSeeder::class,
            ElmsLevelsSeeder::class,
            MotherSeeder::class,
        ]);
    }
}
