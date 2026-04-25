<?php

namespace Database\Seeders;

use App\Models\ElmsLevel;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class ElmsLevelsSeeder extends Seeder
{
    public function run(): void
    {
        $rows = [
            ['name' => 'Beginner', 'sort_order' => 1],
            ['name' => 'Intermediate', 'sort_order' => 2],
            ['name' => 'Advanced', 'sort_order' => 3],
            ['name' => 'Expert', 'sort_order' => 4],
        ];

        foreach ($rows as $r) {
            $slug = Str::slug($r['name']);
            ElmsLevel::query()->updateOrCreate(
                ['slug' => $slug],
                [
                    'name' => $r['name'],
                    'is_active' => true,
                    'sort_order' => $r['sort_order'],
                ]
            );
        }
    }
}
