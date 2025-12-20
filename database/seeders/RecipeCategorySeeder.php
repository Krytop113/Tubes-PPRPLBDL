<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class RecipeCategorySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $categories = [
            [
                'id' => 1,
                'name' => 'Hidangan Utama',
                'description' => 'Lauk pauk utama pendamping nasi',
            ],
            [
                'id' => 2,
                'name' => 'Camilan',
                'description' => 'Makanan ringan dan gorengan',
            ],
        ];

        foreach ($categories as $category) {
            DB::table('recipe_categories')->updateOrInsert(
                ['id' => $category['id']],
                array_merge($category, [
                    'created_at' => now(),
                    'updated_at' => now(),
                ])
            );
        }
    }
}
