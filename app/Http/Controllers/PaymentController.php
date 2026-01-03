<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Models\Order;
use Exception;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\Controller;
use App\Http\Controllers\NotificationController;

class PaymentController extends Controller
{
    public function payment(Request $request)
    {
        $order = Order::findOrFail($request->order_id);

        $shippingCost = (float) $request->shipping_cost;
        $couponUserId = $request->coupon_user_id;

        $couponAmount = 0;
        if ($couponUserId) {
            $couponUser = DB::table('coupon_users')
                ->join('coupons', 'coupon_users.coupon_id', '=', 'coupons.id')
                ->where('coupon_users.id', $couponUserId)
                ->first();

            if ($couponUser) {
                $couponAmount = ($couponUser->discount_percentage / 100) * $order->total_raw;
            }
        }

        $totalAmount = ($order->total_raw - $couponAmount) + $shippingCost;

        return view('customer.payment.payment', compact(
            'order',
            'shippingCost',
            'couponAmount',
            'totalAmount',
            'couponUserId'
        ));
    }

    public function store(Request $request)
    {
        $request->validate([
            'order_id' => 'required|exists:orders,id',
            'method'   => 'required|string',
        ]);

        DB::beginTransaction();
        try {
            $order = Order::findOrFail($request->order_id);

            DB::statement("CALL create_payment_procedure(?, ?, ?, ?, ?, ?, ?)", [
                (float) $request->coupon_amount,
                (float) $request->shipping_cost,
                (float) $request->total_amount,
                $request->method,
                now(),
                (int) $request->order_id,
                $request->coupon_user_id ? (int) $request->coupon_user_id : null
            ]);
            
            $this->afterPayment($order);

            DB::commit();

            if (!empty($result) && isset($result[0]->ErrorDetail)) {
                throw new \Exception($result[0]->ErrorDetail);
            }

            NotificationController::orderProcessing(Auth::id(), $request->order_id);

            return redirect()->route('orders.index')->with('success', 'Pembayaran berhasil dikonfirmasi!');
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->withErrors('Gagal melakukan pembayaran: ' . $e->getMessage());
        }
    }

    public function afterPayment(Order $order): void
    {
        if (empty($order->order_details)){
            throw new Exception("Detail pesanan tidak ditemukan."); 
        }

        foreach ($order->order_details as $detail) {
            $ingredient = DB::table('ingredients')->where('id', $detail->ingredient_id)->first();

            if (!$ingredient){
                throw new Exception("Bahan tidak ditemukan.");
            }

            $newTotalStock = $ingredient->stock_quantity - $detail->quantity;

            DB::statement("CALL edit_ingredient_procedure(?, ?, ?, ?, ?, ?, ?, ?, ?)", [
                $detail->ingredient_id,
                null,
                null,
                null,
                null,
                $newTotalStock,
                null,
                null,
                null
            ]);
        }
    }
}
