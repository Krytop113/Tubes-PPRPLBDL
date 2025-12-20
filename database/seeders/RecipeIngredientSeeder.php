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
                'quantity_required' => 500,
                'unit' => 'gram',
            ],
            [
                'id' => 2,
                'recipe_id' => 1,
                'ingredient_id' => 1,
                'quantity_required' => 1,
                'unit' => 'pcs',
            ],
            [
                'id' => 3,
                'recipe_id' => 1,
                'ingredient_id' => 7,
                'quantity_required' => 500,
                'unit' => 'ml',
            ],
            [
                'id' => 4,
                'recipe_id' => 2,
                'ingredient_id' => 6,
                'quantity_required' => 500,
                'unit' => 'gram',
            ],
            [
                'id' => 5,
                'recipe_id' => 2,
                'ingredient_id' => 2,
                'quantity_required' => 1,
                'unit' => 'pcs',
            ],
            [
                'id' => 6,
                'recipe_id' => 3,
                'ingredient_id' => 10,
                'quantity_required' => 1,
                'unit' => 'kg',
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
                'quantity_required' => 250,
                'unit' => 'gram',
            ],
            [
                'id' => 9,
                'recipe_id' => 3,
                'ingredient_id' => 9,
                'quantity_required' => 20,
                'unit' => 'ml',
            ],
            [
                'id' => 10,
                'recipe_id' => 4,
                'ingredient_id' => 13,
                'quantity_required' => 1,
                'unit' => 'bungkus',
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
                'unit' => 'kg',
            ],
            [
                'id' => 13,
                'recipe_id' => 5,
                'ingredient_id' => 2,
                'quantity_required' => 50,
                'unit' => 'gram',
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
