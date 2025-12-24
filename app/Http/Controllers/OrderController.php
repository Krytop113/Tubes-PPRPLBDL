<?php

namespace App\Http\Controllers;

use App\Models\Order;
use Illuminate\Http\Request;
use App\Models\OrderDetail;
use App\Models\Ingredient;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use App\Models\User;

class OrderController extends Controller
{
    /**
     * Add ingredient to cart
     */
    public function addToCart(Request $request, $ingredientId)
    {
        $user = Auth::user();

        $ingredient = Ingredient::findOrFail($ingredientId);
        // Get or create cart order
        $order = Order::firstOrCreate(
            [
                'user_id' => $user->id,
                'status' => 'cart'
            ],
            [
                'total_price' => 0
            ]
        );

        // Check if item already exists in cart
        $item = OrderDetail::where('order_id', $order->id)
            ->where('ingredient_id', $ingredient->id)
            ->where('status', 'cart')
            ->first();

        if ($item) {
            $item->quantity += 1;
            $item->save();
        } else {
            OrderDetail::create([
                'order_id' => $order->id,
                'ingredient_id' => $ingredient->id,
                'quantity' => 1,
                'price' => $ingredient->price,
                'status' => 'cart'
            ]);
        }

        // Update total
        $this->updateTotal($order);

        return redirect()->back()->with('success', 'Item added to cart');
    }

    /**
     * View cart
     */
    public function cart()
    {
        $order = Order::where('user_id', auth()->id())
            ->where('status', 'cart')
            ->with('items.ingredient')
            ->first();

        return view('cart.index', compact('order'));
    }

    /**
     * Checkout
     */
    // public function checkout()
    // {
    //     $order = Order::where('user_id', auth()->id())
    //         ->where('status', 'cart')
    //         ->with('items')
    //         ->firstOrFail();

    //     // Update order status
    //     $order->update(['status' => 'order']);

    //     // Update items status
    //     $order->items()->update(['status' => 'order']);

    //     return redirect()->route('orders.success')
    //         ->with('success', 'Order placed successfully');
    // }

    private function updateTotal(Order $order)
    {
        $total = $order->items()
            ->where('status', 'cart')
            ->sum(DB::raw('price * quantity'));

        $order->update(['total_price' => $total]);
    }
}
