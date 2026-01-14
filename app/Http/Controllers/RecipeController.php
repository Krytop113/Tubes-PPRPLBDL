<?php

namespace App\Http\Controllers;

use App\Models\Recipe;
use Illuminate\Http\Request;
use App\Models\RecipeCategory;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use App\Models\Ingredient;
use Illuminate\Support\Facades\DB;
use Exception;
use Illuminate\Support\Str;

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
            ->orderBy('name', 'asc')
            ->get();

        return view('control.recipe.index', compact('recipes', 'categories', 'selectedCategory', 'search'));
    }

    public function create()
    {
        $categories = RecipeCategory::all();
        $allIngredients = Ingredient::orderBy('name', 'asc')->get();
        return view('control.recipe.create', compact('categories','allIngredients'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'required|string',
            'steps' => 'required|string',
            'cook_time' => 'required|integer|min:1',
            'serving' => 'required|integer|min:1',
            'recipe_category_id' => 'required|exists:recipe_categories,id',
            'image' => 'nullable|image|mimes:jpeg,png,jpg|max:2048',
            'ingredients' => 'required|array|min:1',
            'ingredients.*.id' => 'required|exists:ingredients,id',
            'ingredients.*.quantity' => 'required|integer|min:1'
        ]);

        DB::beginTransaction();

        try {
            $fileName = null;
            if ($request->hasFile('image')) {
                $file = $request->file('image');

                $words = explode(' ', $request->name);
                $twoWords = array_slice($words, 0, 2);
                $baseName = Str::slug(implode('_', $twoWords), '_');
                $fileName = $baseName . '_' . time() . '.jpg';
            }

            $result = DB::select("CALL create_recipe_procedure(?, ?, ?, ?, ?, ?, ?)", [
                $request->name,
                $request->description,
                $request->steps,
                $request->cook_time,
                $request->serving,
                $request->recipe_category_id,
                $fileName
            ]);

            if (!empty($result) && isset($result[0]->ErrorDetail)) {
                throw new \Exception($result[0]->ErrorDetail);
            }

            $newRecipe = Recipe::where('name', $request->name)->orderBy('created_at', 'desc')->first();

            foreach ($request->ingredients as $item) {
                $ingResult = DB::select("CALL create_recipe_ingredient_procedure(?, ?, ?, ?)", [
                    $item['quantity'],
                    $item['unit'] ?? 'pcs',
                    $newRecipe->id,
                    $item['id']
                ]);

                if (!empty($ingResult) && isset($ingResult[0]->ErrorDetail)) {
                    throw new \Exception($ingResult[0]->ErrorDetail);
                }
            }
            DB::commit();

            $file->move(public_path('recipes'), $fileName);

            return redirect()->route('control.recipes.index')
                ->with('success', 'Resep ' . $request->name . ' berhasil disimpan!');

        } catch (\Exception $e) {
            if ($fileName && file_exists(public_path('recipes/' . $fileName))) {
                unlink(public_path('recipes/' . $fileName));
            }
            DB::rollBack();
            report($e);
            return back()->withErrors('Gagal menyimpan: ' . $e->getMessage())->withInput();
        }
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
        $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'required|string',
            'steps' => 'required|string',
            'cook_time' => 'required|integer|min:1',
            'serving' => 'required|integer|min:1',
            'recipe_category_id' => 'required|exists:recipe_categories,id',
            'image' => 'nullable|image|mimes:jpeg,png,jpg|max:2048'
        ]);

        $oldImageUrl = $recipe->image_url;
        $newImageUrl = $oldImageUrl;

        DB::beginTransaction();

        try {
            $words = explode(' ', $request->name);
            $twoWords = array_slice($words, 0, 2);
            $baseName = Str::slug(implode('_', $twoWords), '_');

            if ($request->hasFile('image')) {
                $file = $request->file('image');
                $newImageUrl = $baseName . '_' . time() . '.jpg';

                if ($oldImageUrl && file_exists(public_path('recipes/' . $oldImageUrl))) {
                    unlink(public_path('recipes/' . $oldImageUrl));
                }

                $file->move(public_path('recipes'), $newImageUrl);
            } else if ($oldImageUrl && $recipe->name !== $request->name) {
                $newImageUrl = $baseName . '_' . time() . '.jpg';
            }

            $result = DB::select('CALL edit_recipe_procedure(?, ?, ?, ?, ?, ?, ?, ?)', [
                $recipe->id,
                $request->name,
                $request->description,
                $request->steps,
                $request->cook_time,
                $request->serving,
                $request->recipe_category_id,
                $newImageUrl
            ]);

            if (!empty($result) && isset($result[0]->ErrorDetail)) {
                throw new \Exception($result[0]->ErrorDetail);
            }

            DB::commit();

            rename(public_path('recipes/' . $oldImageUrl), public_path('recipes/' . $newImageUrl));

            return redirect()->route('control.recipes.index')
                ->with('success', 'Resep ' . $recipe->name . ' berhasil diperbarui!');
        } catch (\Exception $e) {
            if ($newImageUrl !== $oldImageUrl && file_exists(public_path('recipes/' . $newImageUrl))) {
                unlink(public_path('recipes/' . $newImageUrl));
            }
            DB::rollBack();

            dd($e);
            report($e);
            return back()->withErrors('Gagal memperbarui: ' . $e->getMessage())->withInput();
        }
    }

    public function destroy(Recipe $recipe)
    {
        DB::beginTransaction();

        try {
            $result = DB::select('CALL delete_recipe_procedure(?)', [$recipe->id]);

            if (!empty($result) && isset($result[0]->ErrorDetail)) {
                throw new \Exception($result[0]->ErrorDetail);
            }

            DB::commit();

            if ($recipe->image_url && file_exists(public_path('recipes/' . $recipe->image_url))) {
                unlink(public_path('recipes/' . $recipe->image_url));
            }

            return redirect()->route('control.recipes.index')->with('success', $recipe->name . ' Berhasil dihapus!');
        } catch (\Exception $e) {
            DB::rollBack();
            report($e);
            return back()->withErrors($e->getMessage());
        }
    }
}
