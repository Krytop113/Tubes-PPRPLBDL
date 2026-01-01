<?php

namespace App\Http\Controllers;

use App\Models\Notification;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Exception;

class NotificationController extends Controller
{
    private static function createNotification(
        int $userId,
        string $title,
        string $subject,
        string $message
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

    public static function orderCheckout(int $userId, int $orderId): void
    {
        self::createNotification(
            $userId,
            'Order Checkout',
            'Order Successfully Checked Out',
            "Your order #{$orderId} has been successfully checked out and waiting for processing."
        );
    }

    public static function orderProcessing(int $userId, int $orderId): void
    {
        self::createNotification(
            $userId,
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
            $results = DB::select('CALL delete_notification_procedure(?, ?)', [
                $id,
                Auth::id()
            ]);

            $message = $results[0]->ResultMessage ?? $results[0]->ErrorDetail ?? 'Notification deleted successfully.';

            return redirect()
                ->route('notification.index')
                ->with('success', $message);
        } catch (Exception $e) {
            return redirect()
                ->back()
                ->with('error', 'Gagal menghapus notifikasi: ' . $e->getMessage());
        }
    }
}
