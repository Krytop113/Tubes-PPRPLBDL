<?php

namespace App\Http\Controllers;

use App\Models\Ingredient;
use Illuminate\Http\Request;
use App\Models\Ingredientcategory;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;

class IngredientController extends Controller
{

    // Customer View
    public function indexcustomer(Request $request)
    {
        $user = Auth::user();
        $categories = Ingredientcategory::all();
        $selectedCategory = $request->category;

        $ingredients = Ingredient::with('ingredient_category')
            ->when($selectedCategory, function ($query) use ($selectedCategory) {
                $query->where('ingredient_category_id', $selectedCategory);
            })
            ->when($request->filled('search'), function ($query) use ($request) {
                $query->where('name', 'like', '%' . $request->search . '%');
            })
            ->paginate(8)
            ->withQueryString();

        return view('customer.ingredients.index', compact('ingredients', 'categories', 'user', 'selectedCategory'));
    }

    public function show(Ingredient $ingredient)
    {
        $user = Auth::user();

        return view('customer.ingredients.description', compact('ingredient', 'user'));
    }


    // Control Panel View
    public function indexcontrol(Request $request)
    {
        $categories = Ingredientcategory::all();

        $selectedCategory = $request->query('category');
        $search = $request->query('search');

        $ingredients = Ingredient::with('ingredient_category')
            ->when($selectedCategory, function ($query) use ($selectedCategory) {
                $query->where('ingredient_category_id', $selectedCategory);
            })
            ->when($search, function ($query) use ($search) {
                $query->where('name', 'like', '%' . $search . '%');
            })
            ->latest()
            ->get();

        return view('control.ingredients.index', compact('ingredients', 'categories', 'selectedCategory', 'search'));
    }

    public function create()
    {
        Auth::user();
        return view('control.ingredients.create');
    }

    public function store(Request $request) {}

    public function edit(Ingredient $ingredient) {}

    public function update(Request $request, Ingredient $ingredient) {}

    public function destroy(Ingredient $ingredient) {}
}
