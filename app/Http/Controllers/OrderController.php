<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;

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
            ->orderByDesc('updated_at')
            ->get();

        return view('customer.order.index', compact('orders', 'status'));
    }

    public function orderDetail($id)
    {
        $userId = Auth::id();
        $payment = DB::table('payments')
            ->where('order_id', $id)
            ->first();
        $now = now();

        try {
            $orderDetails = DB::table('vw_order_details_with_ingredients')
                ->where('order_id', $id)
                ->where('user_id', $userId)
                ->get();

            if ($orderDetails->isEmpty()) {
                return back()->withErrors('Order tidak ditemukan atau Anda tidak memiliki akses.');
            }

            $order = $orderDetails->first();

            $couponUsers = DB::table('vw_user_coupons_detailed')
                ->where('user_id', $userId)
                ->where('usage_status', 'unused')
                ->where('start_date', '<=', $now)
                ->where('end_date', '>=', $now)
                ->get();

            return view('customer.order.show', compact('order', 'orderDetails', 'couponUsers', 'payment'));
        } catch (\Exception $e) {
            report($e);
            return back()->withErrors('Terjadi kesalahan saat memuat data.');
        }
    }

    public function cancel($id)
    {
        try {
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
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->withErrors('Terjadi kesalahan saat memuat data.');
        }
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
