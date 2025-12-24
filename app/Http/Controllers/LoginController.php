<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use Illuminate\Support\Facades\Auth;
use App\Models\User;

class LoginController extends Controller
{
    // use AuthenticatesUsers;
    // protected function redirectTo()
    // {
    //     $role = Auth::user()->role->name;

    //     return match ($role) {
    //         'admin'   => '/admin/dashboard',
    //         'employee'  => '/employee/dashboard',
    //         'customer' => '/customer/home',
    //         default    => '/login',
    //     };
    // }
}
