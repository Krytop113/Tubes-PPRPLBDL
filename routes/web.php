<?php

use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

// Authentication Routes
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
Route::get('/login', function () {
    return view('auth.login');
})->name('login');

Route::get('/signup', function () {
    return view('auth.signup');
})->name('register');

Route::post('/login', function (Request $request) {
    $credentials = $request->validate([
        'email'    => 'required|email',
        'password' => 'required'
    ]);

    if (Auth::attempt($credentials)) {
        $request->session()->regenerate();
        return redirect('/');
    }

    return back()->withErrors([
        'email' => 'Invalid credentials.',
    ]);
});

Route::post('/signup', function (Request $request) {
    $data = $request->validate([
        'name'     => 'required|string',
        'email'    => 'required|email|unique:users',
        'phone'    => 'required|string',
        'password' => 'required|confirmed|min:6',
    ]);

    User::create([
        'name'     => $data['name'],
        'email'    => $data['email'],
        'phone'    => $data['phone'],
        'password' => Hash::make($data['password']),
    ]);

    return redirect()->route('login');
});

// Home
Auth::routes();

Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])->name('home');
