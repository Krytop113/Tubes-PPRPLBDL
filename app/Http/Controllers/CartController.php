<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;

class CartController extends Controller
{
    public function showCart()
    {
        $cart = session()->get('cart', []);
        $items = collect($cart)->map(function ($item) {
            return (object) $item;
        });

        return view('customer.cart.index', compact('items'));
    }

    private function calculateCartTotal(array &$cart): float
    {
        $total = 0;

        foreach ($cart as $key => $item) {
            $cart[$key]['subtotal'] = $item['price'] * $item['quantity'];
            $total += $cart[$key]['subtotal'];
        }

        return $total;
    }

    public function addRecipeIngredientsToCart(Request $request, $id)
    {
        $request->validate([
            'serving_order' => 'required|integer|min:1'
        ]);

        $recipe = DB::table('recipes')->where('id', $id)->first();
        $recipeingredients = DB::table('recipe_ingredients')->where('recipe_id', $id)->get();

        if (!$recipe || $recipeingredients->isEmpty()) {
            return back()->withErrors('Resep atau daftar bahan tidak ditemukan.');
        }

        $multiplier = $request->serving_order;
        $cart = session()->get('cart', []);
        $errors = [];
        $addedItemIds = [];

        foreach ($recipeingredients as $ri) {
            $ingredient = DB::table('ingredients')->where('id', $ri->ingredient_id)->first();

            if (!$ingredient)
                continue;

            $neededQty = $ri->quantity_required * $multiplier;
            $prodId = $ingredient->id;

            $currentInCart = isset($cart[$prodId]) ? $cart[$prodId]['quantity'] : 0;
            if (($currentInCart + $neededQty) > $ingredient->stock_quantity) {
                $errors[] = "Stok {$ingredient->name} tidak mencukupi untuk porsi tersebut.";
                continue;
            }

            if (isset($cart[$prodId])) {
                $cart[$prodId]['quantity'] += $neededQty;
            } else {
                $cart[$prodId] = [
                    "product_id" => $prodId,
                    "name" => $ingredient->name,
                    "quantity" => $neededQty,
                    "price" => $ingredient->price_per_unit,
                    "image" => $ingredient->image_url,
                    "unit" => $ingredient->unit
                ];
            }
            $addedItemIds[] = $prodId;
        }

        $cartTotal = $this->calculateCartTotal($cart);
        session()->put('cart', $cart);
        session()->put('cart_total', $cartTotal);

        if (count($errors) > 0) {
            return redirect()->route('cart.index')->withErrors($errors);
        }

        return redirect()->route('cart.index')
            ->with('success', 'Bahan-bahan resep berhasil ditambahkan ke keranjang!')
            ->with('highlight_items', $addedItemIds);
    }

    public function addToCart(Request $request)
    {
        $request->validate([
            'product_id' => 'required',
            'quantity' => 'required|integer|min:1'
        ]);

        $ingredient = DB::table('ingredients')->where('id', $request->product_id)->first();

        if (!$ingredient) {
            return back()->withErrors('Produk tidak ditemukan.');
        }

        $cart = session()->get('cart', []);
        $id = $request->product_id;

        $currentCartQty = isset($cart[$id]) ? $cart[$id]['quantity'] : 0;
        $totalRequestedQty = $currentCartQty + $request->quantity;

        if ($totalRequestedQty > $ingredient->stock_quantity) {
            return back()->withErrors([
                'quantity' => "Stok tidak mencukupi. Stok tersedia: {$ingredient->stock_quantity}, "
            ]);
        }

        if (isset($cart[$id])) {
            $cart[$id]['quantity'] = $totalRequestedQty;
        } else {
            $cart[$id] = [
                "product_id" => $id,
                "name" => $ingredient->name,
                "quantity" => $request->quantity,
                "price" => $ingredient->price_per_unit,
                "image" => $ingredient->image_url,
                "unit" => $ingredient->unit
            ];
        }

        $cartTotal = $this->calculateCartTotal($cart);
        session()->put('cart', $cart);
        session()->put('cart_total', $cartTotal);

        return back()->with('success', 'Produk ditambahkan ke keranjang!');
    }

    public function updateQty(Request $request, $id)
    {
        $cart = session()->get('cart');

        if (isset($cart[$id])) {
            $ingredient = DB::table('ingredients')->where('id', $id)->first();

            if ($request->action == 'increase') {
                if ($cart[$id]['quantity'] + 1 > $ingredient->stock_quantity) {
                    return back()->withErrors('Gagal tambah: Stok sudah mencapai batas maksimal.');
                }
                $cart[$id]['quantity']++;
            } else {
                $cart[$id]['quantity']--;
            }

            if ($cart[$id]['quantity'] < 1) {
                unset($cart[$id]);
            }

            $cartTotal = $this->calculateCartTotal($cart);
            session()->put('cart', $cart);
            session()->put('cart_total', $cartTotal);

            return back()->with('success', 'Jumlah diperbarui.');
        }

        return back()->withErrors('Item tidak ditemukan.');
    }

    public function deleteItem($id)
    {
        $cart = session()->get('cart');

        if (isset($cart[$id])) {
            unset($cart[$id]);

            $cartTotal = $this->calculateCartTotal($cart);
            session()->put('cart', $cart);
            session()->put('cart_total', $cartTotal);

            return back()->with('success', 'Item dihapus');
        }

        return back()->withErrors('Gagal menghapus item');
    }

    public function checkout(Request $request)
    {
        $cart = session()->get('cart', []);
        $selectedIds = $request->input('selected_items', []);

        if (empty($selectedIds)) {
            return back()->withErrors('Pilih barang terlebih dahulu.');
        }

        $itemsToCheckout = array_intersect_key($cart, array_flip($selectedIds));

        $totalPrice = 0;
        foreach ($itemsToCheckout as $item) {
            $totalPrice += $item['price'] * $item['quantity'];
        }

        DB::beginTransaction();
        try {
            $orderResult = DB::select('CALL create_orders_procedure(?, ?, ?)', ['pending', $totalPrice, Auth::id()]);

            if (!empty($orderResult) && isset($orderResult[0]->ErrorDetail)) {
                throw new \Exception($orderResult[0]->ErrorDetail);
            }

            if (empty($orderResult) || !isset($orderResult[0]->generated_id)) {
                throw new \Exception("Gagal membuat order: Respon database tidak valid.");
            }

            $orderId = $orderResult[0]->generated_id;

            foreach ($itemsToCheckout as $id => $item) {
                $detailResult = DB::select('CALL create_orderdetail_procedure(?, ?, ?, ?)', [
                    $orderId,
                    $item['product_id'],
                    $item['quantity'],
                    $item['price']
                ]);

                if (!empty($detailResult) && isset($detailResult[0]->ErrorDetail)) {
                    throw new \Exception($detailResult[0]->ErrorDetail);
                }

                unset($cart[$id]);
            }

            DB::commit();

            session()->put('cart', $cart);

            NotificationController::orderCheckout(Auth::id(), $orderId);

            return redirect()->route('orders.show', $orderId)->with('success', 'Checkout berhasil!');
        } catch (\Exception $e) {
            DB::rollBack();
            report($e);
            return back()->withErrors('Terjadi sebuah kesalahan saat melakukan checkout: ' . $e->getMessage());
        }
    }
}
