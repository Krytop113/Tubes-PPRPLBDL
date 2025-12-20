<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        $this->call(RoleSeeder::class);
        $this->call(IngredientCategorySeeder::class);
        $this->call(RecipeCategorySeeder::class);
        $this->call(CouponSeeder::class);
        $this->call(IngredientSeeder::class);
        $this->call(RecipeSeeder::class);
        $this->call(UserSeeder::class);
        $this->call(RecipeIngredientSeeder::class);
    }
}
