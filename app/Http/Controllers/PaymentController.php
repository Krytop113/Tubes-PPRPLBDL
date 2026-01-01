<?php

namespace App\Http\Controllers;

use App\Models\Payment;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Models\Order;
use App\Models\CouponUser;
use Exception;

class PaymentController extends Controller
{
    public function payment(Request $request)
    {
        $order = Order::findOrFail($request->order_id);

        $shippingCost = (float) $request->input('shipping_cost', 0);

        $couponUserId = $request->input('coupon_user_id');

        $couponAmount = 0;
        if ($couponUserId) {
            $cu = CouponUser::with('coupon')->find($couponUserId);
            if ($cu && $cu->status === 'unused') {
                $couponAmount = ($cu->coupon->discount_percentage / 100) * $order->total_raw;
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
            'order_id' => 'required',
            'method' => 'required',
        ]);

        try {
            DB::statement("CALL create_payment_procedure(?, ?, ?, ?, ?, ?, ?)", [
                $request->coupon_amount,
                $request->shipping_cost,
                $request->total_amount,
                $request->method,
                now(),
                $request->order_id,
                $request->coupon_user_id ?: null
            ]);

            return redirect()->route('orders.index')->with('success', 'Pembayaran berhasil dikonfirmasi!');
        } catch (Exception $e) {
            return back()->withErrors('Gagal memproses pembayaran: ' . $e->getMessage())->withInput();
        }
    }
}
