<?php

namespace Database\Seeders;

use App\Models\Product;
use Illuminate\Database\Seeder;

class ProductSeeder extends Seeder
{
    public function run(): void
    {
        $products = [
            [
                'sku' => 'PKG-001',
                'name' => 'Basic Mother Care Package',
                'category' => 'Packages',
                'selling_price' => 150000,
                'description' => 'Essential care package for new mothers including basic supplies.',
                'is_active' => true,
            ],
            [
                'sku' => 'PKG-002',
                'name' => 'Premium Mother Care Package',
                'category' => 'Packages',
                'selling_price' => 350000,
                'description' => 'Comprehensive care package with premium items and nutrition guides.',
                'is_active' => true,
            ],
            [
                'sku' => 'PRD-001',
                'name' => 'Organic Baby Lotion',
                'category' => 'Baby Care',
                'selling_price' => 25000,
                'description' => 'Gentle organic lotion for sensitive baby skin.',
                'is_active' => true,
            ],
            [
                'sku' => 'PRD-002',
                'name' => 'Breastfeeding Support Pillow',
                'category' => 'Maternity',
                'selling_price' => 45000,
                'description' => 'Comfortable support pillow for breastfeeding mothers.',
                'is_active' => true,
            ],
            [
                'sku' => 'PRD-003',
                'name' => 'Maternity Vitamin Set',
                'category' => 'Health',
                'selling_price' => 60000,
                'description' => 'Essential vitamins for prenatal and postnatal health.',
                'is_active' => true,
            ],
            ['sku' => 'MWG-0001', 'name' => 'Malkia Wellness Supplement', 'category' => 'supplements', 'cost_price' => 8500, 'selling_price' => 15000, 'qty_on_hand' => 60, 'is_active' => true],
            ['sku' => 'MWG-0002', 'name' => 'Postnatal Care Kit', 'category' => 'kits', 'cost_price' => 24000, 'selling_price' => 38000, 'qty_on_hand' => 25, 'is_active' => true],
            ['sku' => 'MWG-0003', 'name' => 'Prenatal Vitamins', 'category' => 'supplements', 'cost_price' => 12000, 'selling_price' => 20000, 'qty_on_hand' => 40, 'is_active' => true],
        ];

        foreach ($products as $product) {
            Product::updateOrCreate(['sku' => $product['sku']], $product);
        }
    }
}
