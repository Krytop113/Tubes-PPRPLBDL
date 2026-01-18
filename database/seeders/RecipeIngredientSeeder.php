<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class RecipeIngredientSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $recipeIngredients = [
            [
                'id' => 1,
                'recipe_id' => 1,
                'ingredient_id' => 5,
                'quantity_required' => 5,
                'unit' => 'packet',
            ],
            [
                'id' => 2,
                'recipe_id' => 1,
                'ingredient_id' => 1,
                'quantity_required' => 1,
                'unit' => 'sachet',
            ],
            [
                'id' => 3,
                'recipe_id' => 1,
                'ingredient_id' => 7,
                'quantity_required' => 5,
                'unit' => 'bungkus',
            ],
            [
                'id' => 4,
                'recipe_id' => 2,
                'ingredient_id' => 6,
                'quantity_required' => 5,
                'unit' => 'packet',
            ],
            [
                'id' => 5,
                'recipe_id' => 2,
                'ingredient_id' => 2,
                'quantity_required' => 1,
                'unit' => 'sachet',
            ],
            [
                'id' => 6,
                'recipe_id' => 3,
                'ingredient_id' => 10,
                'quantity_required' => 3,
                'unit' => 'packet',
            ],
            [
                'id' => 7,
                'recipe_id' => 3,
                'ingredient_id' => 8,
                'quantity_required' => 1,
                'unit' => 'sachet',
            ],
            [
                'id' => 8,
                'recipe_id' => 3,
                'ingredient_id' => 11,
                'quantity_required' => 3,
                'unit' => 'butir',
            ],
            [
                'id' => 9,
                'recipe_id' => 3,
                'ingredient_id' => 9,
                'quantity_required' => 2,
                'unit' => 'bungkus',
            ],
            [
                'id' => 10,
                'recipe_id' => 4,
                'ingredient_id' => 13,
                'quantity_required' => 1,
                'unit' => 'packet',
            ],
            [
                'id' => 11,
                'recipe_id' => 4,
                'ingredient_id' => 12,
                'quantity_required' => 1,
                'unit' => 'sachet',
            ],
            [
                'id' => 12,
                'recipe_id' => 5,
                'ingredient_id' => 14,
                'quantity_required' => 1,
                'unit' => 'ekor',
            ],
            [
                'id' => 13,
                'recipe_id' => 5,
                'ingredient_id' => 2,
                'quantity_required' => 1,
                'unit' => 'sachet',
            ],
        ];

        foreach ($recipeIngredients as $recipeIngredient) {
            DB::table('recipe_ingredients')->updateOrInsert(
                ['id' => $recipeIngredient['id']],
                array_merge($recipeIngredient, [
                    'created_at' => now(),
                    'updated_at' => now(),
                ])
            );
        }
    }
}
