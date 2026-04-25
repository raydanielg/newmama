<?php

namespace Database\Seeders;

use App\Models\Supplier;
use App\Models\VendorLedgerEntry;
use Illuminate\Database\Seeder;

class VendorLedgerSeeder extends Seeder
{
    public function run(): void
    {
        $now = now();

        $meditech = Supplier::where('code', 'SUP-001')->first();
        $global = Supplier::where('code', 'SUP-002')->first();

        if ($meditech) {
            $entries = [
                [
                    'posting_date' => $now->copy()->subDays(40)->toDateString(),
                    'document_type' => 'invoice',
                    'document_ref' => 'PINV-000101',
                    'description' => 'Purchase Invoice — Meditech Tanzania Ltd',
                    'amount_tzs' => 680000,
                    'amount' => 0,
                    'remaining_amount' => 180000,
                    'is_open' => true,
                    'due_date' => $now->copy()->subDays(40)->addDays(30)->toDateString(),
                    'journal_id' => null,
                    'import_order_ref' => 'IMP-0007',
                ],
                [
                    'posting_date' => $now->copy()->subDays(25)->toDateString(),
                    'document_type' => 'payment',
                    'document_ref' => 'CPAY-000051',
                    'description' => 'Cash Payment — Meditech Tanzania Ltd',
                    'amount_tzs' => -500000,
                    'amount' => 0,
                    'remaining_amount' => 0,
                    'is_open' => false,
                    'due_date' => null,
                    'journal_id' => null,
                    'import_order_ref' => 'IMP-0007',
                ],
            ];

            foreach ($entries as $e) {
                VendorLedgerEntry::updateOrCreate(
                    ['supplier_id' => $meditech->id, 'document_ref' => $e['document_ref']],
                    array_merge($e, ['supplier_id' => $meditech->id])
                );
            }

            $meditech->balance_tzs = 180000;
            $meditech->save();
        }

        if ($global) {
            $entries = [
                [
                    'posting_date' => $now->copy()->subDays(18)->toDateString(),
                    'document_type' => 'invoice',
                    'document_ref' => 'PINV-000201',
                    'description' => 'Purchase Invoice — Global Imports Co.',
                    'amount_tzs' => 1250000,
                    'amount' => 0,
                    'remaining_amount' => 1250000,
                    'is_open' => true,
                    'due_date' => $now->copy()->subDays(18)->addDays(45)->toDateString(),
                    'journal_id' => null,
                    'import_order_ref' => 'IMP-0008',
                ],
            ];

            foreach ($entries as $e) {
                VendorLedgerEntry::updateOrCreate(
                    ['supplier_id' => $global->id, 'document_ref' => $e['document_ref']],
                    array_merge($e, ['supplier_id' => $global->id])
                );
            }

            $global->balance_tzs = 1250000;
            $global->save();
        }
    }
}
