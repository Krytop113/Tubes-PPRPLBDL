<?php

use Illuminate\Support\Facades\Route;
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
})->name('signup');

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

Route::post('/register', function (Request $request) {
    $data = $request->validate([
        'name'     => 'required|string',
        'email'    => 'required|email|unique:users',
        'phone_number'    => 'required|string',
        'password' => 'required|confirmed|min:6',
    ]);

    User::create([
        'name'     => $data['name'],
        'email'    => $data['email'],
        'phone_number'    => $data['phone_number'],
        'password' => Hash::make($data['password']),
    ]);

    return redirect()->route('login');
});


use App\Http\Controllers\HomeController;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Auth\SignupController;
use App\Http\Controllers\UserController;

// Home Divider
Route::get('/', function () {
    return view('customer/home');
});
Route::get('/', [HomeController::class, 'index'])->name('home');

Auth::routes();

// Route::get('/home', [HomeController::class, 'index'])->name('home');

// Admin
// Route::middleware(['auth', 'role:admin'])->group(function () {
//     Route::get('/admin/dashboard', fn() => view('admin.dashboard'))
//         ->name('admin.dashboard');
// });

// Employee
// Route::middleware(['auth', 'role:employee'])->group(function () {
//     Route::get('/employee/dashboard', fn() => view('employee.dashboard'))
//         ->name('employee.dashboard');
// });

// Customer
use App\Http\Controllers\IngredientController;
use App\Http\Controllers\RecipeController;
use App\Http\Controllers\OrderController;
use App\Http\Controllers\NotificationController;
use App\Http\Controllers\CartController;

Route::middleware(['auth'])->group(function () {
    Route::get('/customer/ingredients', [IngredientController::class, 'index'])
        ->name('ingredients.index');
    Route::get('/customer/ingredients/{ingredient}', [IngredientController::class, 'show'])
        ->name('ingredients.show');

    Route::get('/customer/recipes', [RecipeController::class, 'index'])
        ->name('recipes.index');
    Route::get('/customer/recipes/{recipe}', [RecipeController::class, 'show'])
        ->name('recipes.show');
    Route::post('/cart/add-recipe/{id}', [CartController::class, 'addRecipeIngredientsToCart'])
        ->name('cart.addRecipe');

    Route::get('/customer/cart', [CartController::class, 'showCart'])
        ->name('cart.index');
    Route::get('/cart', [CartController::class, 'showCart'])
        ->name('cart.index');
    Route::post('/cart/add', [CartController::class, 'addToCart'])
        ->name('cart.add');
    Route::patch('/cart/item/{id}', [CartController::class, 'updateQty'])
        ->name('cart.update');
    Route::delete('/cart/item/{id}', [CartController::class, 'deleteItem'])
        ->name('cart.deleteItem');
    Route::post('/cart/checkout', [CartController::class, 'checkout'])
        ->name('cart.checkout');

    Route::get('/customer/notifications', [NotificationController::class, 'index'])
        ->name('notification.index');
    Route::get('/notifications/{id}', [NotificationController::class, 'show'])
        ->name('notification.show');
    Route::delete('/notifications/{id}', [NotificationController::class, 'destroy'])
        ->name('notification.delete');

    Route::get('/orders', [OrderController::class, 'orders'])
        ->name('orders.index');
    Route::get('/orders/{id}', [OrderController::class, 'orderDetail'])
        ->name('orders.show');
    Route::put('/orders/{id}/cancel', [OrderController::class, 'cancel'])
        ->name('orders.cancel');

    Route::post('/orders/{order}/coupon', [OrderController::class, 'applyCoupon'])
        ->name('orders.applyCoupon');

    Route::get('/profile', [UserController::class, 'editProfile'])
        ->name('editProfile');
    Route::patch('/profile', [UserController::class, 'update'])
        ->name('updateProfile');
});


// Route::middleware(['auth', 'role:customer'])->group(function () {
//     Route::get('/customer/home', fn() => view('customer.home'))
//         ->name('customer.home');

//     Route::middleware(['auth'])
//         ->prefix('customer')
//         ->name('customer.')
//         ->group(function () {
//         });
// });
