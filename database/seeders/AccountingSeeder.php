<?php

namespace Database\Seeders;

use App\Models\Account;
use Illuminate\Database\Seeder;

class AccountingSeeder extends Seeder
{
    public function run(): void
    {
        $accounts = [
            [
                'code' => '1000',
                'name' => 'Cash on Hand',
                'type' => 'asset',
                'category' => 'Cash & Bank',
                'balance' => 0,
                'is_active' => true,
            ],
            [
                'code' => '1010',
                'name' => 'Bank Account',
                'type' => 'asset',
                'category' => 'Cash & Bank',
                'balance' => 0,
                'is_active' => true,
            ],
            [
                'code' => '1110',
                'name' => 'Inventory Control',
                'type' => 'asset',
                'category' => 'Inventory',
                'balance' => 0,
                'is_active' => true,
            ],
            [
                'code' => '1121',
                'name' => 'GRN Interim',
                'type' => 'asset',
                'category' => 'Inventory',
                'balance' => 0,
                'is_active' => true,
            ],
            [
                'code' => '1050',
                'name' => 'Accounts Receivable (Trade Debtors)',
                'type' => 'asset',
                'category' => 'AR',
                'balance' => 0,
                'is_active' => true,
            ],
            [
                'code' => '1200',
                'name' => 'Accounts Receivable Control',
                'type' => 'asset',
                'category' => 'AR',
                'balance' => 0,
                'is_active' => true,
            ],
            [
                'code' => '2010',
                'name' => 'Accounts Payable Control',
                'type' => 'liability',
                'category' => 'AP',
                'balance' => 0,
                'is_active' => true,
            ],
            [
                'code' => '4010',
                'name' => 'Sales Revenue',
                'type' => 'revenue',
                'category' => 'Revenue',
                'balance' => 0,
                'is_active' => true,
            ],
            [
                'code' => '4011',
                'name' => 'Sales Revenue (Net)',
                'type' => 'revenue',
                'category' => 'Revenue',
                'balance' => 0,
                'is_active' => true,
            ],
            [
                'code' => '2020',
                'name' => 'VAT Output Payable',
                'type' => 'liability',
                'category' => 'Tax',
                'balance' => 0,
                'is_active' => true,
            ],
            [
                'code' => '5010',
                'name' => 'Cost of Goods Sold',
                'type' => 'expense',
                'category' => 'COGS',
                'balance' => 0,
                'is_active' => true,
            ],
            [
                'code' => '5000',
                'name' => 'General Expenses',
                'type' => 'expense',
                'category' => 'Expenses',
                'balance' => 0,
                'is_active' => true,
            ],
        ];

        foreach ($accounts as $a) {
            Account::updateOrCreate(
                ['code' => $a['code']],
                $a
            );
        }
    }
}
