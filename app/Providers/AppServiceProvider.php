<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Pagination\Paginator;
use Illuminate\Support\Facades\Auth;
use App\Models\Notification;


class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        Paginator::useBootstrapFive();

        view()->composer('layouts.app', function ($view) {
            if (Auth::check()) {
                $unreadCount = Notification::where('status', 'unread')->where('user_id', Auth::id())->count();
                $view->with('unreadNotificationsCount', $unreadCount);
            }
        });
    }
}
