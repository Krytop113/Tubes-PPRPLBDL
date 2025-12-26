<?php

namespace App\Http\Controllers;

use App\Models\CouponUser;
use Illuminate\Http\Request;
use App\Models\Coupon;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use App\Models\Order;

class CouponUserController extends Controller
{
    public function getCoupon()
    {
        $user = Auth::user();

        // if ($user->role !== 'customer') {
        //     return;
        // }

        $now = Carbon::now();

        $coupons = Coupon::where('start_date', '<=', $now)
            ->where('end_date', '>=', $now)
            ->get();

        foreach ($coupons as $coupon) {
            $exists = CouponUser::where('coupon_id', $coupon->id)
                ->where('user_id', $user->id)
                ->exists();

            if (!$exists) {
                DB::statement(
                    'CALL create_couponuser_procedure(?, ?)',
                    [
                        $coupon->id,
                        $user->id
                    ]
                );
            }
        }
    }

    public function applyCoupon(Request $request, Order $order)
    {
        $request->validate([
            'coupon_user_id' => 'required|exists:coupon_users,id',
        ]);

        $couponUser = CouponUser::with('coupon')
            ->where('id', $request->coupon_user_id)
            ->where('user_id', Auth::id())
            ->where('status', 'unused')
            ->firstOrFail();

        $discount = ($couponUser->coupon->discount / 100) * $order->total_raw;
        $order->total_raw -= $discount;
        $order->save();

        DB::statement(
            'CALL update_couponuser_procedure(?, ?)',
            [Auth::id(), $couponUser->id]
        );

        return back()->with('success', 'Kupon berhasil diterapkan');
    }
}
