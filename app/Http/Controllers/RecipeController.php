<?php

namespace App\Http\Controllers;

use App\Models\Recipe;
use Illuminate\Http\Request;
use App\Models\RecipeCategory;
use App\Http\Controllers\Controller;

class RecipeController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $categories = RecipeCategory::all();

        $selectedCategories = $request->categories ?? [];

        if ($request->filled('add_category')) {
            $selectedCategories[] = (int) $request->add_category;
        }
        if ($request->filled('remove_category')) {
            $selectedCategories = array_diff(
                $selectedCategories,
                [(int) $request->remove_category]
            );
        }
        $recipes = Recipe::with('recipe_category')
            ->when(!empty($selectedCategories), function ($query) use ($selectedCategories) {
                $query->whereIn('recipe_category_id', $selectedCategories);
            })
            ->when($request->filled('search'), function ($query) use ($request) {
                $query->where('name', 'like', '%' . $request->search . '%');
            })
            ->get();

        return view('customer.recipe.index', compact(
            'recipes',
            'categories'
        ))->with('selectedCategories', $selectedCategories);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(Recipe $recipe)
    {
        return view('customer.recipe.description', compact('recipe'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Recipe $recipe)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Recipe $recipe)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Recipe $recipe)
    {
        //
    }
}
