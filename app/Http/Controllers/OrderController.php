<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use App\Models\Order;
use App\Http\Controllers\NotificationController;
use App\Models\Payment;
use Barryvdh\DomPDF\Facade\Pdf;
use Exception;


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
            $payment = Payment::where('order_id', $order->id)
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
            dd($e);
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

    public function complete(Order $order)
    {
        $userId = Auth::id();

        if ($order->user_id !== $userId) {
            return back()->withErrors('Anda tidak memiliki akses untuk menyelesaikan pesanan ini.');
        }

        if ($order->status !== 'paid') {
            return back()->withErrors('Hanya pesanan dengan status Paid yang dapat diselesaikan.');
        }

        DB::beginTransaction();

        try {
            $result = DB::select('CALL edit_orders_procedure(?, ?)', [
                $order->id,
                'Done'
            ]);

            if (!empty($result) && isset($result[0]->ErrorDetail)) {
                throw new \Exception($result[0]->ErrorDetail);
            }

            foreach ($order->order_details as $detail) {
                $result = DB::select('CALL edit_orderdetail_procedure(?, ?)', [
                    $detail->id,
                    'Done'
                ]);

                if (!empty($result) && isset($result[0]->ErrorDetail)) {
                    throw new \Exception($result[0]->ErrorDetail);
                }
            }

            NotificationController::orderDone($userId, $order->id);

            DB::commit();

            return redirect()
                ->route('orders.index')
                ->with('success', 'Order #' . $order->id . ' berhasil diselesaikan. Terima kasih!');
        } catch (\Exception $e) {
            DB::rollBack();
            report($e);
            return back()->withErrors('Terjadi kesalahan saat menyelesaikan pesanan: ' . $e->getMessage());
        }
    }

    public function downloadReceipt(Order $order)
    {
        if ($order->user_id !== Auth::id()) {
            abort(403, 'Anda tidak memiliki akses ke resi ini.');
        }

        $payment = Payment::where('order_id', $order->id)->first();
        $orderDetails = $order->order_details()->with('ingredient')->get();
        $logo = public_path('logo.png');

        $pdf = Pdf::loadView('customer.order.receipt_pdf', [
            'order' => $order,
            'orderDetails' => $orderDetails,
            'payment' => $payment,
            'logo' => $logo,
        ]);

        return $pdf->download('Resi-Pesanan-#' . $order->id . '.pdf');
    }

    // Control Panel View
    public function indexcontrol(Request $request)
    {
        $reportType = $request->query('report_type', 'order_only');

        if ($reportType === 'top_items') {
            $query = DB::table('vw_order_item');

            if ($request->filled('start_date') && $request->filled('end_date')) {
            }

            $orders = $query->get();
            $totalRevenue = $orders->sum('total_omzet_bahan');
        } else {
            $query = DB::table('vw_order_report1');

            if ($request->filled('start_date') && $request->filled('end_date')) {
                $query->whereBetween('tanggal_order', [
                    $request->start_date . ' 00:00:00',
                    $request->end_date . ' 23:59:59'
                ]);
            }

            if ($request->filled('user_search')) {
                $query->where(function ($q) use ($request) {
                    $q->where('nama_pelanggan', 'like', '%' . $request->user_search . '%')
                        ->orWhere('order_id', 'like', '%' . $request->user_search . '%');
                });
            }

            $orders = $query->orderByDesc('tanggal_order')->get();

            if ($orders->isNotEmpty()) {
                $details = \App\Models\OrderDetail::with('ingredient')
                    ->whereIn('order_id', $orders->pluck('order_id'))
                    ->get()
                    ->groupBy('order_id');

                foreach ($orders as $order) {
                    $order->items = $details->get($order->order_id) ?? collect();
                }
            }

            $totalRevenue = $orders->sum('total_bayar');
        }

        return view('control.order.index', compact('orders', 'totalRevenue'));
    }
}
