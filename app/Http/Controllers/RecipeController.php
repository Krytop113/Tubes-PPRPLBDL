<?php

namespace App\Http\Controllers;

use App\Models\Recipe;
use Illuminate\Http\Request;
use App\Models\RecipeCategory;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

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
        $categories = RecipeCategory::all();
        return view('control.recipe.create', compact('categories'));
    }

    public function store(Request $request, Recipe $recipe)
    {
        //
    }

    public function edit(Recipe $recipe)
    {
        if (empty($recipe)) {
            return redirect()->route('recipes.index')->with('error', 'Data tidak ditemukan');
        }

        $categories = RecipeCategory::all();
        return view('control.recipe.edit', compact('recipe', 'categories'));
    }

    public function update(Request $request, Recipe $recipe)
    {
        //
    }

    public function destroy(Recipe $recipe)
    {
        try {
            $result = DB::select('CALL delete_ingredient_procedure(?)', [$recipe->id]);

            if (!empty($result) && isset($result[0]->ErrorDetail)) {
                throw new \Exception($result[0]->ErrorDetail);
            }

            if ($recipe->image_url && file_exists(public_path('recipes/' . $recipe->image_url))) {
                unlink(public_path('recipes/' . $recipe->image_url));
            }

            return redirect()->route('control.recipe.index')->with('success', $$recipe->name . ' Berhasil dihapus!');
        } catch (\Exception $e) {
            return back()->withErrors($e->getMessage());
        }
    }
}
