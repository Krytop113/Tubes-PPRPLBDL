<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class RecipeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $recipes = [
            [
                'id' => 1,
                'name' => 'Rendang Daging Sapi Empuk',
                'description' => 'Resep rendang sapi yang empuk dengan bumbu meresap sempurna.',
                'steps' => '1. Potong daging sapi kotak-kotak. 2. Tumis bumbu rendang instan hingga harum. 3. Masukkan daging, aduk hingga berubah warna. 4. Tuang santan, masak dengan api kecil hingga kuah mengering dan berminyak.',
                'cook_time' => 120,
                'serving' => 4,
                'image_url' => 'rendang_recipe.jpg',
                'recipe_category_id' => 1,
            ],
            [
                'id' => 2,
                'name' => 'Ayam Goreng Krispi Ala Resto',
                'description' => 'Ayam goreng dengan kulit keriting dan sangat renyah tahan lama.',
                'steps' => '1. Cuci bersih ayam. 2. Siapkan adonan basah dan kering menggunakan Tepung Bumbu Krispi. 3. Celupkan ayam ke adonan basah, lalu gulingkan ke adonan kering sambil diremas-remas. 4. Goreng dalam minyak panas hingga kuning keemasan.',
                'cook_time' => 45,
                'serving' => 2,
                'image_url' => 'ayam_krispi_recipe.jpg',
                'recipe_category_id' => 1,
            ],
            [
                'id' => 3,
                'name' => 'Nasi Goreng Kampung Spesial',
                'description' => 'Nasi goreng sederhana dengan rasa otentik yang pas untuk sarapan.',
                'steps' => '1. Panaskan sedikit minyak. 2. Masukkan telur, orak-arik hingga matang. 3. Masukkan nasi putih dan Bumbu Nasi Goreng Spesial. 4. Tambahkan Kecap Manis, aduk rata dengan api besar hingga tercium aroma wangi.',
                'cook_time' => 15,
                'serving' => 2,
                'image_url' => 'nasgor_kampung.jpg',
                'recipe_category_id' => 1,
            ],
            [
                'id' => 4,
                'name' => 'Sayur Asem Segar Jakarta',
                'description' => 'Sayur berkuah bening dengan rasa asam yang menyegarkan.',
                'steps' => '1. Didihkan air. 2. Masukkan bahan keras. 3. Masukkan bumbu. 4. Masukkan sayur hijau terakhir.',
                'cook_time' => 30,
                'serving' => 4,
                'image_url' => 'sayur_asem.jpg',
                'recipe_category_id' => 1,
            ],
            [
                'id' => 5,
                'name' => 'Gurame Goreng Terbang',
                'description' => 'Ikan gurame goreng kering dengan bentuk menarik.',
                'steps' => '1. Bersihkan ikan. 2. Lumuri bumbu. 3. Goreng deep fry hingga keemasan.',
                'cook_time' => 40,
                'serving' => 2,
                'image_url' => 'gurame_terbang.jpg',
                'recipe_category_id' => 1,
            ],
        ];

        foreach ($recipes as $recipe) {
            DB::table('recipes')->updateOrInsert(
                ['id' => $recipe['id']],
                array_merge($recipe, [
                    'created_at' => now(),
                    'updated_at' => now(),
                ])
            );
        }
    }
}
