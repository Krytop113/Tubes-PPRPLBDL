<?php

namespace App\Http\Controllers;

use App\Models\Recipe;
use Illuminate\Http\Request;
use App\Models\RecipeCategory;
use App\Http\Controllers\Controller;

class RecipeController extends Controller
{
    // Customer View
    public function indexcustomer(Request $request)
    {
        $categories = RecipeCategory::all();
        $selectedCategory = $request->category;

        $recipes = Recipe::with('recipe_category')
            ->when($selectedCategory, function ($query) use ($selectedCategory) {
                $query->where('recipe_category_id', $selectedCategory);
            })
            ->when($request->filled('search'), function ($query) use ($request) {
                $query->where('name', 'like', '%' . $request->search . '%');
            })
            ->paginate(8)
            ->withQueryString();

        return view('customer.recipe.index', compact(
            'recipes',
            'categories',
            'selectedCategory'
        ));
    }

    public function show(Recipe $recipe)
    {
        return view('customer.recipe.description', compact('recipe'));
    }

    // Control Panel View
    public function indexcontrol(Request $request)
    {
        $categories = RecipeCategory::all();

        $selectedCategory = $request->query('category');
        $search = $request->query('search');

        $recipes = Recipe::with('recipe_category')
            ->when($selectedCategory, function ($query) use ($selectedCategory) {
                $query->whereIn('recipe_category_id', (array) $selectedCategory);
            })
            ->when($search, function ($query) use ($search) {
                $query->where('name', 'like', '%' . $search . '%');
            })
            ->latest()
            ->get();

        return view('control.recipe.index', compact('recipes', 'categories', 'selectedCategory', 'search'));
    }

    public function create()
    {
        //
    }

    public function store(Request $request)
    {
        //
    }

    public function edit(Recipe $recipe)
    {
        //
    }

    public function update(Request $request, Recipe $recipe)
    {
        //
    }

    public function destroy(Recipe $recipe)
    {
        //
    }
}
