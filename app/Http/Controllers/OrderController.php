<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use App\Models\Order;
use App\Http\Controllers\NotificationController;
use App\Models\Payment;

class OrderController extends Controller
{
    // Customer View
    public function orders(Request $request)
    {
        $status = $request->status;


        $query = Order::with('user')
            ->where('user_id', Auth::id());

        if ($status) {
            $query->where('status', $status);
        }

        $orders = $query
            ->orderByDesc('updated_at')
            ->get();

        return view('customer.order.index', compact('orders', 'status'));
    }

    public function orderDetail(Order $order)
    {
        $userId = Auth::id();
        $now = now();

        if ($order->user_id !== $userId) {
            return back()->withErrors('Anda tidak memiliki akses ke order ini.');
        }

        try {
            $payment = Payment::with('payments')
                ->where('order_id', $order->id)
                ->first();

            $orderDetails = DB::table('vw_order_details_with_ingredients')
                ->where('order_id', $order->id)
                ->get();

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

    public function cancel(Order $order)
    {
        $userId = Auth::id();

        if ($order->user_id !== $userId) {
            return back()->withErrors('Anda tidak memiliki akses untuk membatalkan pesanan ini.');
        }

        if ($order->status === 'cancel') {
            return back()->withErrors('Pesanan ini sudah dibatalkan sebelumnya.');
        }

        DB::beginTransaction();

        try {
            $result = DB::select('CALL edit_orders_procedure(?, ?)', [
                $order->id,
                'cancel'
            ]);

            if (!empty($result) && isset($result[0]->ErrorDetail)) {
                throw new \Exception($result[0]->ErrorDetail);
            }

            foreach ($order->order_details as $detail) {
                $result = DB::statement('CALL edit_orderdetail_procedure(?, ?)', [
                    $detail->id,
                    'cancel'
                ]);

                if (!empty($result) && isset($result[0]->ErrorDetail)) {
                    throw new \Exception($result[0]->ErrorDetail);
                }
            }

            NotificationController::orderCancel($userId, $order->id);

            DB::commit();

            return redirect()
                ->route('orders.index')
                ->with('success', 'Order #' . $order->id . ' berhasil dibatalkan.');
        } catch (\Exception $e) {
            DB::rollBack();
            report($e);
            return back()->withErrors('Terjadi kesalahan saat memproses pembatalan.');
        }
    }

    // Control Panel View
    public function indexcontrol(Request $request)
    {   
        $query = DB::table('orders')
            ->join('users', 'orders.user_id', '=', 'users.id')
            ->select('orders.*', 'users.name as customer_name')
            ->whereIn('orders.status', ['done', 'paid']);

        if ($request->filled('start_date') && $request->filled('end_date')) {
            $start = $request->query('start_date');
            $end = $request->query('end_date');
            $query->whereBetween('orders.created_at', [$start . ' 00:00:00', $end . ' 23:59:59']);
        }

        $orders = $query->orderByDesc('orders.created_at')->get();

        $totalRevenue = $orders->sum('total_raw');

        return view('control.order.index', compact('orders', 'totalRevenue'));
    }
}
