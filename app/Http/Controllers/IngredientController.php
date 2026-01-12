<?php

namespace App\Http\Controllers;

use App\Models\Ingredient;
use Illuminate\Http\Request;
use App\Models\Ingredientcategory;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Exception;


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
            ->orderBy('name', 'asc')
            ->get();

        return view('control.ingredients.index', compact('ingredients', 'categories', 'selectedCategory', 'search'));
    }

    public function create()
    {
        $categories = Ingredientcategory::all();
        return view('control.ingredients.create', compact('categories'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'unit' => 'required',
            'price_per_unit' => 'required|numeric',
            'stock_quantity' => 'required|integer',
            'minimum_stock_level' => 'required|integer',
            'description' => 'required',
            'ingredient_category_id' => 'required',
            'image' => 'nullable|image|mimes:jpeg,jpg|max:2048'
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

            $result = DB::select("CALL create_ingredient_procedure(?, ?, ?, ?, ?, ?, ?, ?)", [
                $request->name,
                $request->unit,
                $request->price_per_unit,
                $request->description,
                $request->stock_quantity,
                $fileName,
                $request->minimum_stock_level,
                $request->ingredient_category_id
            ]);

            if (!empty($result) && isset($result[0]->ErrorDetail)) {
                throw new Exception($result[0]->ErrorDetail);
            }

            DB::commit();

            $file->move(public_path('ingredients'), $fileName);

            return redirect()->route('control.ingredients.index')
                ->with('success', 'Bahan baku ' . $request->name . ' berhasil disimpan!');
        } catch (Exception $e) {
            if ($fileName && file_exists(public_path('ingredients/' . $fileName))) {
                unlink(public_path('ingredients/' . $fileName));
            }
            DB::rollBack();
            report($e);
            return back()->withErrors('Gagal menyimpan data');
        }
    }

    public function edit(Ingredient $ingredient)
    {
        if (empty($ingredient)) {
            return redirect()->route('ingredients.index')->with('error', 'Data tidak ditemukan');
        }

        $categories = Ingredientcategory::all();

        return view('control.ingredients.edit', compact('ingredient', 'categories'));
    }

    public function update(Request $request, Ingredient $ingredient)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'unit' => 'required',
            'price_per_unit' => 'required|numeric',
            'stock_quantity' => 'required|integer',
            'minimum_stock_level' => 'required|integer',
            'description' => 'required',
            'ingredient_category_id' => 'required',
        ]);

        $oldImageUrl = $ingredient->image_url;
        $newImageUrl = $oldImageUrl;
        
        DB::beginTranscation();

        try {
            $words = explode(' ', $request->name);
            $twoWords = array_slice($words, 0, 2);
            $baseName = Str::slug(implode(' ', $twoWords), '_');

            if ($request->hasFile('image') && $newImageUrl !== $oldImageUrl) {
                $file = $request->file('image');
                $newImageUrl = $baseName . '_' . time() . '.jpg';

                if ($oldImageUrl && file_exists(public_path('ingredients/' . $oldImageUrl))) {
                    unlink(public_path('ingredients/' . $oldImageUrl));
                }

                $file->move(public_path('ingredients'), $newImageUrl);
            } else if ($oldImageUrl && $ingredient->name !== $request->name) {
                $newImageUrl = $baseName . '_' . time() . '.jpg';
            }

            $result = DB::select('CALL edit_ingredient_procedure(?, ?, ?, ?, ?, ?, ?, ?, ?)', [
                $ingredient->id,
                $request->name,
                $request->unit,
                $request->price_per_unit,
                $request->description,
                $request->stock_quantity,
                $newImageUrl,
                $request->minimum_stock_level,
                $request->ingredient_category_id
            ]);

            if (!empty($result) && isset($result[0]->ErrorDetail)) {
                throw new \Exception($result[0]->ErrorDetail);
            }
            
            DB::commit();

            rename(public_path('ingredients/' . $oldImageUrl), public_path('ingredients/' . $newImageUrl));

            return redirect()->route('control.ingredients.index')
                ->with('success', 'Bahan baku ' . $ingredient->name . ' berhasil diperbarui!');
        } catch (\Exception $e) {
            if ($newImageUrl !== $oldImageUrl) {
                Storage::disk('public')->delete($newImageUrl);
            }
            report($e);
            return back()->withErrors('Gagal memperbarui data');
        }
    }

    public function destroy(Ingredient $ingredient)
    {
        DB::beginTransaction();

        try {
            $result = DB::select('CALL delete_ingredient_procedure(?)', [$ingredient->id]);

            if (!empty($result) && isset($result[0]->ErrorDetail)) {
                throw new \Exception($result[0]->ErrorDetail);
            }

            DB::commit();

            if ($ingredient->image_url && file_exists(public_path('ingredients/' . $ingredient->image_url))) {
                unlink(public_path('ingredients/' . $ingredient->image_url));
            }

            return redirect()->route('control.ingredients.index')->with('success', $ingredient->name . ' Berhasil dihapus!');
        } catch (\Exception $e) {
            DB::rollBack();
            report($e);
            return back()->withErrors('Gagal menghapus');
        }
    }

    public function quickadd(Request $request)
    {
        $request->validate([
            'updates' => 'required|array',
            'updates.*.id' => 'required|exists:ingredients,id',
            'updates.*.amount' => 'required|numeric|min:0.01',
        ]);

        try {
            foreach ($request->updates as $update) {
                $ingredient = Ingredient::with('ingredients')->where('id', $update['id'])->first();

                $newTotalStock = $ingredient->stock_quantity + $update['amount'];

                $result = DB::select("CALL edit_ingredient_procedure(?, ?, ?, ?, ?, ?, ?, ?, ?)", [
                    $update['id'],
                    null,
                    null,
                    null,
                    null,
                    $newTotalStock,
                    null,
                    null,
                    null
                ]);

                if (!empty($result) && isset($result[0]->ErrorDetail)) {
                    throw new \Exception($result[0]->ErrorDetail);
                }
            }

            DB::commit();
            return redirect()->back()->with('success', 'Stok berhasil diperbarui!');
        } catch (\Exception $e) {
            DB::rollBack();
            report($e);
            return redirect()->back()->withErrors('Gagal memperbarui stok');
        }
    }
}
