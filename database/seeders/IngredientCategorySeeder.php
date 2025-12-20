<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class IngredientCategorySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $categories = [
            [
                'id' => 1,
                'name' => 'Bumbu Instan & Rempah',
                'description' => 'Berbagai macam bumbu siap pakai dan rempah alami',
            ],
            [
                'id' => 2,
                'name' => 'Daging & Protein',
                'description' => 'Daging sapi, ayam, dan sumber protein lainnya',
            ],
            [
                'id' => 3,
                'name' => 'Tepung & Bahan Kering',
                'description' => 'Aneka tepung untuk gorengan dan kue',
            ],
            [
                'id' => 4,
                'name' => 'Saus & Kecap',
                'description' => 'Pelengkap rasa masakan cair',
            ],
            [
                'id' => 5,
                'name' => 'Sayuran & Nabati',
                'description' => 'Santan, sayur, dan bahan nabati lainnya',
            ],
        ];

        foreach ($categories as $category) {
            DB::table('ingredient_categories')->updateOrInsert(
                ['id' => $category['id']],
                array_merge($category, [
                    'created_at' => now(),
                    'updated_at' => now(),
                ])
            );
        }
    }
}
