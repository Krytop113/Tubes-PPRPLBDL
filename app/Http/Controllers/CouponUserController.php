<?php

namespace App\Http\Controllers;

use App\Models\CouponUser;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use App\Models\Order;
use App\Http\Controllers\Controller;
use Exception;

class CouponUserController extends Controller
{
    public function applyCoupon(Request $request, Order $order)
    {
        $request->validate([
            'coupon_user_id' => 'required|exists:coupon_users,id',
        ]);

        DB::beginTransaction();

        try {
            $couponUser = CouponUser::with('coupon')
                ->where('id', $request->coupon_user_id)
                ->where('user_id', Auth::id())
                ->where('status', 'unused')
                ->first();

            if (!$couponUser) {
                return back()->with('error', 'Kupon tidak tersedia atau sudah digunakan.');
            }

            $discount = ($couponUser->coupon->discount / 100) * $order->total_raw;
            $order->total_raw -= $discount;
            $order->save();

            $result = DB::select(
                'CALL update_couponuser_procedure(?, ?)',
                [Auth::id(), $couponUser->id]
            );

            if (!empty($result) && isset($result[0]->ErrorDetail)) {
                throw new Exception($result[0]->ErrorDetail);
            }

            DB::commit();

            return back()->with('success', 'Kupon berhasil diterapkan. Total tagihan Anda telah diperbarui.');
        } catch (Exception $e) {
            DB::rollBack();
            report($e);
            return back()->withErrors("Gagal menerapkan kupon ke dalam pembelian");
        }
    }
}
