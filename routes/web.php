<?php

use Illuminate\Support\Facades\Route;
// Authentication Routes
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use App\Http\Controllers\IngredientController;
use App\Http\Controllers\RecipeController;
use App\Http\Controllers\OrderController;
use App\Http\Controllers\NotificationController;
use App\Http\Controllers\CartController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Auth\SignupController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\CouponController;
use App\Http\Controllers\OngkirController;
use App\Http\Controllers\PaymentController;

Route::get('/login', function () {
    return view('auth.login');
})->name('login');

Route::get('/signup', function () {
    return view('auth.signup');
})->name('signup');

Route::post('/login', function (Request $request) {
    $credentials = $request->validate([
        'email' => 'required|email',
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
        'name' => 'required|string',
        'email' => 'required|email|unique:users',
        'phone_number' => 'required|string',
        'password' => 'required|confirmed|min:6',
    ]);

    User::create([
        'name' => $data['name'],
        'email' => $data['email'],
        'phone_number' => $data['phone_number'],
        'password' => Hash::make($data['password']),
    ]);

    return redirect()->route('login');
});

// Route::post('/email/verification-notification', function (Request $request) {
//     $request->user()->sendEmailVerificationNotification();
//     return back()->with('success', 'Email verifikasi telah dikirim.');
// })->middleware(['auth', 'throttle:6,1'])->name('verification.send');

// Home Divider
Route::get('/', [HomeController::class, 'index'])->name('home');

Auth::routes();

// Control Panel
Route::middleware(['auth', 'role:admin,employee'])
    ->group(function () {
        Route::get('control/dashboard', [DashboardController::class, 'index'])
            ->name('dashboard');

        // control Ingredients
        Route::get('/control/ingredients', [IngredientController::class, 'indexcontrol'])
            ->name('control.ingredients.index');

        Route::get('/control/ingredients/create', [IngredientController::class, 'create'])
            ->name('control.ingredients.create');
        Route::post('/control/ingredients/store', [IngredientController::class, 'store'])
            ->name('control.ingredients.store');

        Route::get('/control/ingredients/{ingredient}', [IngredientController::class, 'edit'])
            ->name('control.ingredients.edit');
        Route::put('/control/ingredients/{ingredient}', [IngredientController::class, 'update'])
            ->name('control.ingredients.update');

        Route::delete('/control/ingredients/{ingredient}', [IngredientController::class, 'destroy'])
            ->name('control.ingredients.destroy');

        Route::post('/control/ingredients/quickadd', [IngredientController::class, 'quickadd'])
            ->name('control.ingredients.quickadd');

        // control Recipes
        Route::get('/control/recipes', [RecipeController::class, 'indexcontrol'])
            ->name('control.recipes.index');
        Route::get('/control/recipes/create', [RecipeController::class, 'create'])
            ->name('control.recipes.create');
        Route::get('/control/recipes/{recipe}', [RecipeController::class, 'edit'])
            ->name('control.recipes.edit');

        Route::get('/control/orders', [OrderController::class, 'indexcontrol'])
            ->name('control.orders.index');
        Route::get('/control/orders/{id}', [OrderController::class, 'recap'])
            ->name('control.orders.recap');

        Route::get('/control/coupons', [CouponController::class, 'indexcontrol'])
            ->name('control.coupons.index');
        Route::get('/control/coupons/create', [CouponController::class, 'create'])
            ->name('control.coupons.create');
        Route::get('/control/coupons/{coupon}', [CouponController::class, 'edit'])
            ->name('control.coupons.edit');
    });

// Customer Panel
Route::middleware(['auth', 'role:customer,employee,admin'])
    ->group(function () {
        Route::get('/home', [HomeController::class, 'index']);

        Route::get('/customer/ingredients', [IngredientController::class, 'indexcustomer'])
            ->name('ingredients.index');
        Route::get('/customer/ingredients/{ingredient}', [IngredientController::class, 'show'])
            ->name('ingredients.show');

        Route::get('/customer/recipes', [RecipeController::class, 'indexcustomer'])
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
        Route::get('/orders/{order}', [OrderController::class, 'orderDetail'])
            ->name('orders.show');
        Route::put('/orders/{order}/cancel', [OrderController::class, 'cancel'])
            ->name('orders.cancel');

        Route::get('/payment', function () {
            return redirect()->route('orders.index')
                ->with('error', 'Sesi pembayaran habis, silakan proses ulang dari detail order.');
        })->name('payment');
        Route::post('/payment', [PaymentController::class, 'payment'])
            ->name('payment');
        Route::post('/payment/store', [PaymentController::class, 'store'])
            ->name('payment.store');

        Route::post('/orders/{order}/coupon', [OrderController::class, 'applyCoupon'])
            ->name('orders.applyCoupon');

        Route::get('/profile', [UserController::class, 'editProfile'])
            ->name('editProfile');
        Route::patch('/profile', [UserController::class, 'update'])
            ->name('updateProfile');

        Route::get('/coupons', [CouponController::class, 'index'])
            ->name('coupons.index');
        Route::post('/coupons/claim/{id}', [CouponController::class, 'claim'])
            ->name('coupons.claim');
    });
