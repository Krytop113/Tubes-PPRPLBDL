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
        DB::select("CALL create_notification_procedure(?, ?, ?, ?, ?, ?)", [
            $title,
            $subject,
            $message,
            Carbon::now(),
            'unread',
            $userId
        ]);
    }

    public static function userRegistered(User $user): void
    {
        self::createNotification(
            $user->id,
            'Welcome!',
            'Account Registered',
            'Your account has been successfully registered.'
        );
    }

    public static function orderCheckout(int $user, int $orderId): void
    {
        self::createNotification(
            $user,
            'Order Checkout',
            'Order Successfully Checked Out',
            "Your order #{$orderId} has been successfully checked out and waiting for processing."
        );
    }

    public static function orderProcessing(int $user, int $orderId): void
    {
        self::createNotification(
            $user,
            'Order Processing',
            'Order Now Being Processed',
            "Your order #{$orderId} is now being processed."
        );
    }

    public static function orderCancel(int $userId, int $orderId): void
    {
        self::createNotification(
            $userId,
            'Order Cancelled',
            'Order Cancelled',
            "Your order #{$orderId} has been cancelled."
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
            return redirect()->back()->withErrors(['error' => 'Gagal menghapus notifikasi: ' . $e->getMessage()]);
        }
    }
}
