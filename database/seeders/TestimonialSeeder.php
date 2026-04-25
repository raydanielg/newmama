<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class TestimonialSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $items = [
            [
                'id' => 1,
                'name' => 'Amina S.',
                'role' => 'New Mother',
                'location' => 'Dar es Salaam',
                'quote' => 'Malkia Konnect made me feel calm and supported. I got guidance when I needed it most.',
                'sort_order' => 1,
                'is_active' => true,
            ],
            [
                'id' => 2,
                'name' => 'Neema J.',
                'role' => 'Mother of 2',
                'location' => 'Arusha',
                'quote' => 'The resources are easy to understand and the community gives real strength. I felt seen.',
                'sort_order' => 2,
                'is_active' => true,
            ],
            [
                'id' => 3,
                'name' => 'Halima K.',
                'role' => 'Expecting Mother',
                'location' => 'Mwanza',
                'quote' => 'From pregnancy tips to postpartum care, everything is well organized. Highly recommended.',
                'sort_order' => 3,
                'is_active' => true,
            ],
        ];

        foreach ($items as $item) {
            DB::table('testimonials')->updateOrInsert(
                ['id' => $item['id']],
                array_merge($item, [
                    'updated_at' => now(),
                    'created_at' => now(),
                ])
            );
        }
    }
}
