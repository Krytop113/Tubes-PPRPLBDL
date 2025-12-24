<?php

namespace App\Http\Controllers;

use App\Models\Ingredient;
use Illuminate\Http\Request;
use App\Models\Ingredientcategory;
use App\Http\Controllers\Controller;

class IngredientController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $categories = Ingredientcategory::all();

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
        $ingredients = Ingredient::with('ingredient_category')
            ->when(!empty($selectedCategories), function ($query) use ($selectedCategories) {
                $query->whereIn('ingredient_category_id', $selectedCategories);
            })
            ->when($request->filled('search'), function ($query) use ($request) {
                $query->where('name', 'like', '%' . $request->search . '%');
            })
            ->get();

        return view('customer.ingredients.index', compact(
            'ingredients',
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
    public function show(Ingredient $ingredient)
    {
        return view('customer.ingredients.description', compact('ingredient'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Ingredient $ingredient)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Ingredient $ingredient)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Ingredient $ingredient)
    {
        //
    }
}
