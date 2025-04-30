<?php

use App\Http\Controllers\DiscountController;
use App\Http\Controllers\SuggestionController;
use App\Http\Controllers\OrderController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\OrderProductController;
use App\Http\Controllers\Auth\AdminLoginController;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});
Auth::routes();

// Primary CRUD resources

Route::resource('suggestions', SuggestionController::class);
Route::resource('discounts', DiscountController::class);
Route::resource('products', ProductController::class);

// Nested “order item” routes (no standalone resource for OrderProduct)
Route::prefix('orders/{order}')->group(function () {
    // POST   /orders/{order}/products
    Route::post('products', [OrderProductController::class, 'store'])
         ->name('orders.products.store');

    // PATCH  /orders/{order}/products/{orderProduct}
    Route::patch('products/{orderProduct}', [OrderProductController::class, 'update'])
         ->name('orders.products.update');

    // DELETE /orders/{order}/products/{orderProduct}
    Route::delete('products/{orderProduct}', [OrderProductController::class, 'destroy'])
         ->name('orders.products.destroy');
});

// // ── Public (no auth) ────────────────────────────────────────────────────────

// // Show the admin login form
// Route::get('/login', [AdminLoginController::class, 'showLoginForm'])
//      ->name('admin.login');

// // Handle the login POST
// Route::post('/login', [AdminLoginController::class, 'login'])
//      ->name('admin.login.submit');

// // Handle logout (must be POST for safety)
// Route::post('/logout', [AdminLoginController::class, 'logout'])
//      ->name('admin.logout');


// ── Protected (requires auth) ──────────────────────────────────────────────

Route::middleware('auth')->group(function () {

     // Dashboard
     Route::resource('orders', OrderController::class);
});