<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use App\Models\CouponUser;
use App\Models\OrderDetail;

class OrderController extends Controller
{
    // Customer View
    public function orders(Request $request)
    {
        $status = $request->query('status');

        $query = DB::table('orders')
            ->where('user_id', Auth::id());

        if ($status) {
            $query->where('status', $status);
        }

        $orders = $query
            ->orderByDesc('created_at')
            ->get();

        return view('customer.order.index', compact('orders', 'status'));
    }

    public function orderDetail($id)
    {
        $userId = Auth::id();
        $order = DB::table('orders')
            ->where('id', $id)
            ->where('user_id', $userId)
            ->first();

        if (!$order) {
            return back()->withErrors('Order tidak ditemukan.');
        }

        $orderDetails = OrderDetail::with('ingredient')
            ->where('order_id', $id)
            ->get();

        $couponUsers = CouponUser::with('coupon')
            ->where('user_id', $userId)
            ->where('status', 'unused')
            ->whereHas('coupon', function ($q) {
                $now = now();
                $q->where('start_date', '<=', $now)
                    ->where('end_date', '>=', $now);
            })
            ->get();

        return view('customer.order.show', compact('order', 'orderDetails', 'couponUsers'));
    }

    public function cancel($id)
    {
        DB::statement(
            'CALL edit_orders_procedure(?, ?)',
            [
                $id,
                'cancel'
            ]
        );

        $details = DB::table('order_details')
            ->where('order_id', $id)
            ->get();

        foreach ($details as $detail) {
            DB::statement(
                'CALL edit_orderdetail_procedure(?, ?)',
                [
                    $detail->id,
                    'cancel'
                ]
            );
        }

        NotificationController::orderCancel(Auth::id(), $id);

        return redirect()
            ->route('orders.index')
            ->with('success', 'Order berhasil dibatalkan');
    }

    // Control Panel View
    public function indexcontrol(Request $request)
    {
        $query = DB::table('orders')
            ->join('users', 'orders.user_id', '=', 'users.id')
            ->select('orders.*', 'users.name as customer_name')
            ->whereIn('orders.status', ['done', 'paid']);

        if ($request->has('search')) {
            $search = $request->query('search');
            $query->where(function ($q) use ($search) {
                $q->where('users.name', 'like', "%{$search}%")
                    ->orWhere('orders.id', 'like', "%{$search}%");
            });
        }
        $orders = $query->orderByDesc('orders.created_at')->get();

        return view('control.order.index', compact('orders'));
    }
}
