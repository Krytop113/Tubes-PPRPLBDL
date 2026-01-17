<?php

namespace App\Http\Controllers;

use App\Models\Notification;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use App\Models\Order;
use Exception;

class NotificationController extends Controller
{
    private static function createNotification(
        $userId,
        $title,
        $subject,
        $message
    ): void {
        try {
            $result = DB::select("CALL create_notification_procedure(?, ?, ?, ?, ?, ?)", [
                $title,
                $subject,
                $message,
                Carbon::now(),
                'unread',
                $userId
            ]);
            if (!empty($result) && isset($result[0]->ErrorDetail)) {
                throw new Exception($result[0]->ErrorDetail);
            }
        } catch (Exception $e) {
            report($e);
        }
    }

    public static function userRegistered(User $user): void
    {
        self::createNotification(
            $user->id,
            'Selamat datang di kriuk-kriuk!',
            'Akun berhasil teregistrasi',
            'Selamat datang di kriuk kriuk, silahkan bisa mengeksplor layanan bahanbaku dan resep yang berkualitas, diambil dari berbagai sumber. Selamat berbelanja!'
        );
    }

    public static function orderCheckout(int $user, string $orderId): void
    {
        self::createNotification(
            $user,
            'Pesanan Dibuat',
            "Pesanan {$orderId} berhasil dibuat",
            "Pesanan dengan id #{$orderId} telah dibuat dan sedang menunggu konfirmasi pembayaran."
        );
    }

    public static function orderProcessing(int $user, string $orderId): void
    {
        self::createNotification(
            $user,
            'Pesanan berhasil diproses',
            "Pesanan {$orderId} sedang diproses toko",
            "Pesanan dengan id #{$orderId} telah masuk ke dalam sistem toko dan sedang diproses oleh toko."
        );
    }

    public static function orderCancel(int $userId, string $orderId): void
    {
        self::createNotification(
            $userId,
            'Pesanan Dibatalkan',
            "Order {$orderId} dibatalkan",
            "Pesanan dengan id #{$orderId} telah dibatalkan oleh pelanggan."
        );
    }

    public static function orderDone(int $userId, string $orderId): void
    {
        self::createNotification(
            $userId,
            'Pesanan Selesai',
            'Pesanan telah selesai sampai pelanggan',
            "Pesanan {$orderId} telah sampai dengan selamat ke dalam tangan pelanggan. Selamat memasak!"
        );
    }


    public function index()
    {
        $notifications = Notification::where('user_id', Auth::id())
            ->orderBy('date', 'desc')
            ->get();

        return view('customer.notification.index', compact('notifications'));
    }

    public function show($id)
    {
        $notification = Notification::where('id', $id)
            ->where('user_id', Auth::id())
            ->firstOrFail();

        if ($notification->status === 'unread') {
            $notification->update(['status' => 'read']);
        }

        return view('customer.notification.read', compact('notification'));
    }

    public function destroy($id)
    {
        try {
            $result = DB::select('CALL delete_notification_procedure(?)', [
                $id
            ]);

            if (!empty($result) && isset($result[0]->ErrorDetail)) {
                throw new \Exception($result[0]->ErrorDetail);
            }

            return redirect()
                ->route('notification.index')
                ->with('success', 'Notification deleted successfully.');
        } catch (\Exception $e) {
            DB::rollBack();
            report($e);
            return redirect()->back()->withErrors('Gagal menghapus notifikasi.');
        }
    }
}
