<?php

namespace Database\Seeders;

use App\Models\Supplier;
use Illuminate\Database\Seeder;

class SupplierSeeder extends Seeder
{
    public function run(): void
    {
        $suppliers = [
            [
                'code' => 'SUP-001',
                'name' => 'Meditech Tanzania Ltd',
                'contact_person' => 'Accounts',
                'phone' => '+255700000201',
                'email' => 'accounts@meditech.test',
                'address' => 'Dar es Salaam',
                'payment_terms' => 'NET30',
                'balance_tzs' => 0,
                'balance_usd' => 0,
                'is_active' => true,
            ],
            [
                'code' => 'SUP-002',
                'name' => 'Global Imports Co.',
                'contact_person' => 'Procurement',
                'phone' => '+255700000202',
                'email' => 'procurement@global-imports.test',
                'address' => 'Dubai',
                'payment_terms' => 'NET45',
                'balance_tzs' => 0,
                'balance_usd' => 0,
                'is_active' => true,
            ],
            [
                'code' => 'SUP-003',
                'name' => 'Sunrise Packaging',
                'contact_person' => 'Sales Desk',
                'phone' => '+255700000203',
                'email' => 'hello@sunrise-packaging.test',
                'address' => 'Arusha',
                'payment_terms' => 'NET14',
                'balance_tzs' => 0,
                'balance_usd' => 0,
                'is_active' => true,
            ],
        ];

        foreach ($suppliers as $data) {
            Supplier::updateOrCreate(
                ['code' => $data['code']],
                $data
            );
        }
    }
}
