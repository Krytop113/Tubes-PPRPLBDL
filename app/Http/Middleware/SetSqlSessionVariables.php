<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;

class SetSqlSessionVariables
{
    public function handle($request, Closure $next)
    {
        if (Auth::check()) {
            $userId = Auth::id();
            $userName = Auth::user()->name;

            DB::statement("SET @current_user_id = '{$userId}', @current_user_name = '{$userName}'");
        }

        return $next($request);
    }
}
