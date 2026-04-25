<?php

namespace Database\Seeders;

use App\Models\Customer;
use App\Models\CustomerLedgerEntry;
use Illuminate\Database\Seeder;

class CustomerLedgerSeeder extends Seeder
{
    public function run(): void
    {
        $now = now();

        $kilimanjaro = Customer::where('customer_number', 'DEBT-0001')->first();
        $golden = Customer::where('customer_number', 'DEBT-0002')->first();

        if ($kilimanjaro) {
            $entries = [
                [
                    'posting_date' => $now->copy()->subDays(30)->toDateString(),
                    'document_type' => 'invoice',
                    'document_ref' => 'INV-000101',
                    'description' => 'Sales invoice',
                    'amount' => 520000,
                    'remaining_amount' => 120000,
                    'is_open' => true,
                    'due_date' => $now->copy()->subDays(30)->addDays(30)->toDateString(),
                ],
                [
                    'posting_date' => $now->copy()->subDays(20)->toDateString(),
                    'document_type' => 'payment',
                    'document_ref' => 'PAY-000051',
                    'description' => 'Payment received',
                    'amount' => -400000,
                    'remaining_amount' => 0,
                    'is_open' => false,
                    'due_date' => null,
                ],
            ];

            foreach ($entries as $e) {
                CustomerLedgerEntry::updateOrCreate(
                    ['customer_id' => $kilimanjaro->id, 'document_ref' => $e['document_ref']],
                    array_merge($e, ['customer_id' => $kilimanjaro->id])
                );
            }

            $kilimanjaro->balance = 120000;
            $kilimanjaro->save();
        }

        if ($golden) {
            $entries = [
                [
                    'posting_date' => $now->copy()->subDays(14)->toDateString(),
                    'document_type' => 'invoice',
                    'document_ref' => 'INV-000201',
                    'description' => 'Sales invoice',
                    'amount' => 310000,
                    'remaining_amount' => 310000,
                    'is_open' => true,
                    'due_date' => $now->copy()->subDays(14)->addDays(14)->toDateString(),
                ],
            ];

            foreach ($entries as $e) {
                CustomerLedgerEntry::updateOrCreate(
                    ['customer_id' => $golden->id, 'document_ref' => $e['document_ref']],
                    array_merge($e, ['customer_id' => $golden->id])
                );
            }

            $golden->balance = 310000;
            $golden->save();
        }
    }
}
