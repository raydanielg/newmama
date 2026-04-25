<?php

namespace Database\Seeders;

use App\Models\Customer;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class CustomerSeeder extends Seeder
{
    public function run(): void
    {
        $now = now();

        $customers = [
            [
                'customer_number' => 'CASH-0001',
                'customer_type' => 'cash',
                'name' => 'Walk-in Customer',
                'company' => null,
                'contact_person' => null,
                'segment' => 'retail',
                'whatsapp' => null,
                'email' => null,
                'phone' => null,
                'address' => null,
                'credit_limit' => 0,
                'credit_period' => 0,
                'payment_terms' => 'COD',
                'balance' => 0,
                'crown_points' => 120,
                'is_active' => true,
                'last_purchase_date' => $now->copy()->subDays(4)->toDateString(),
                'last_purchase_amount' => 45000,
                'notes' => null,
            ],
            [
                'customer_number' => 'CASH-0002',
                'customer_type' => 'cash',
                'name' => 'Sarah M.',
                'company' => null,
                'contact_person' => null,
                'segment' => 'retail',
                'whatsapp' => '+255700000001',
                'email' => null,
                'phone' => '+255700000001',
                'address' => 'Dar es Salaam',
                'credit_limit' => 0,
                'credit_period' => 0,
                'payment_terms' => 'COD',
                'balance' => 0,
                'crown_points' => 40,
                'is_active' => true,
                'last_purchase_date' => $now->copy()->subDays(11)->toDateString(),
                'last_purchase_amount' => 18500,
                'notes' => null,
            ],
            [
                'customer_number' => 'DEBT-0001',
                'customer_type' => 'debtor',
                'name' => 'Kilimanjaro Hospital',
                'company' => 'Kilimanjaro Hospital',
                'contact_person' => 'Procurement Office',
                'segment' => 'hospital',
                'whatsapp' => '+255700000010',
                'email' => 'procurement@kili-hospital.test',
                'phone' => '+255700000010',
                'address' => 'Moshi',
                'credit_limit' => 1500000,
                'credit_period' => 30,
                'payment_terms' => 'NET30',
                'balance' => 0,
                'crown_points' => 0,
                'is_active' => true,
                'last_purchase_date' => $now->copy()->subDays(2)->toDateString(),
                'last_purchase_amount' => 320000,
                'notes' => 'Pay by bank transfer.',
            ],
            [
                'customer_number' => 'DEBT-0002',
                'customer_type' => 'debtor',
                'name' => 'Golden Pharmacy',
                'company' => 'Golden Pharmacy',
                'contact_person' => 'Manager',
                'segment' => 'pharmacy',
                'whatsapp' => '+255700000011',
                'email' => 'accounts@golden-pharmacy.test',
                'phone' => '+255700000011',
                'address' => 'Arusha',
                'credit_limit' => 750000,
                'credit_period' => 14,
                'payment_terms' => 'NET14',
                'balance' => 0,
                'crown_points' => 0,
                'is_active' => true,
                'last_purchase_date' => $now->copy()->subDays(7)->toDateString(),
                'last_purchase_amount' => 210000,
                'notes' => null,
            ],
        ];

        foreach ($customers as $data) {
            Customer::updateOrCreate(
                ['customer_number' => $data['customer_number']],
                array_merge($data, [
                    'name' => trim((string) $data['name']),
                    'segment' => $data['segment'] ? Str::lower((string) $data['segment']) : null,
                ])
            );
        }
    }
}
