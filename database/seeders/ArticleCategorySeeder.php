<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class ArticleCategorySeeder extends Seeder
{
    public function run(): void
    {
        $categories = [
            [
                'name' => 'Maternal Health',
                'description' => 'Health guidance and self-care for mothers',
                'icon' => 'heart',
            ],
            [
                'name' => 'Newborns (0-12 months)',
                'description' => 'Care tips and health information for newborn babies',
                'icon' => 'baby',
            ],
            [
                'name' => 'Nutrition',
                'description' => 'Healthy eating recommendations for mothers and children',
                'icon' => 'apple',
            ],
            [
                'name' => 'Planning Pregnancy',
                'description' => 'Guidelines for women planning to get pregnant',
                'icon' => 'calendar',
            ],
            [
                'name' => 'Pregnancy',
                'description' => 'Helpful advice and information for pregnant women',
                'icon' => 'sun',
            ],
            [
                'name' => 'Preschoolers (3-6 years)',
                'description' => 'Health and education advice for preschool-aged children',
                'icon' => 'school',
            ],
            [
                'name' => 'Toddlers (1-3 years)',
                'description' => 'Child development and parenting tips for toddlers',
                'icon' => 'person-walking',
            ],
        ];

        foreach ($categories as $cat) {
            DB::table('article_categories')->updateOrInsert(
                ['slug' => Str::slug($cat['name'])],
                array_merge($cat, [
                    'slug' => Str::slug($cat['name']),
                    'created_at' => now(),
                    'updated_at' => now(),
                ])
            );
        }
    }
}
