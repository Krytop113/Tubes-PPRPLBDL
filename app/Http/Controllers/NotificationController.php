<?php

namespace App\Http\Controllers;

use App\Models\Notification;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

class NotificationController extends Controller
{
    private static function createNotification(
        int $userId,
        string $title,
        string $subject,
        string $message
    ): void {
        Notification::create([
            'user_id' => $userId,
            'title'   => $title,
            'subject' => $subject,
            'message' => $message,
            'date'    => Carbon::now(),
            'status'  => 'unread',
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
            "Your order #{$orderId} has been successfully checked out."
        );
    }

    public static function orderPending(int $userId, int $orderId): void
    {
        self::createNotification(
            $userId,
            'Order Pending',
            'Order Pending Confirmation',
            "Your order #{$orderId} is currently pending confirmation."
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
        DB::statement(
            'CALL delete_notification(?, ?)',
            [$id, Auth::id()]
        );

        return redirect()
            ->route('customer.notifications')
            ->with('success', 'Notification deleted successfully.');
    }
}
