<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Queue;
use Illuminate\Support\Facades\Mail;

// Controllers
use App\Http\Controllers\HomeController;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\FlowerController;
use App\Http\Controllers\CartController;
use App\Http\Controllers\OrderController;
use App\Http\Controllers\OrderProductController;
use App\Http\Controllers\SuggestionController;
use App\Http\Controllers\DiscountController;
use App\Http\Controllers\UserProfileController;
use App\Http\Controllers\PackagingController;
// ────────────────────────────────────────────────────────────────
// AUTHENTICATION & PASSWORD RESET
// ────────────────────────────────────────────────────────────────
Auth::routes(['verify' => true]);

// ────────────────────────────────────────────────────────────────
// PUBLIC ROUTES (no auth required)
// ────────────────────────────────────────────────────────────────
//HOME PAGE
Route::get('home', [HomeController::class, 'index'])->name('home');

Route::get('/', fn() => view('welcome'));
Route::get('homepage', fn() => view('customerviews.homepage'))
     ->name('homepage');

Route::get('catalogue', [ProductController::class, 'customerCatalogue'])
     ->name('products.catalogue');

// Public flowers & products
Route::resource('flowers', FlowerController::class)
     ->only(['index', 'show']);
Route::resource('products', ProductController::class)
     ->only(['index', 'show']);

// Orders & Cart (public)
Route::resource('orders', OrderController::class)
     ->only(['index', 'create', 'show', 'store', 'edit', 'destroy']);

Route::post('/cart/discount', [CartController::class, 'discountIfExist']);
Route::get('cart',             [CartController::class, 'index'])->name('cart.index');
Route::post('cart/{product}',  [CartController::class, 'add'])->name('cart.add');
Route::patch('cart/{product}',  [CartController::class, 'update'])->name('cart.update');
Route::delete('cart/{product}',  [CartController::class, 'remove'])->name('cart.remove');

// ────────────────────────────────────────────────────────────────
// AUTHENTICATED & VERIFIED USERS
// ────────────────────────────────────────────────────────────────

Route::middleware(['auth', 'verified'])->group(function () {

     // Nested Order → OrderProduct
     Route::prefix('orders/{order}')
          ->name('orders.')
          ->group(function () {
               Route::post('products',                 [OrderProductController::class, 'store'])->name('products.store');
               Route::patch('products/{orderProduct}', [OrderProductController::class, 'update'])->name('products.update');
               Route::delete('products/{orderProduct}', [OrderProductController::class, 'destroy'])->name('products.destroy');
          });

     Route::get('/profile', [UserProfileController::class, 'index'])
          ->name('profile.index');
     Route::put('/profile', [UserProfileController::class, 'updateProfile'])
          ->name('profile.update');
     Route::put('/profile/password', [UserProfileController::class, 'updatePassword'])
          ->name('profile.password.update');
     // Upload payment screenshot
     Route::post(
          '/orders/{order}/upload-payment',
          [UserProfileController::class, 'updatePayment']
     )->name('orders.uploadPayment');

     // User transactions, suggestions, discounts, product management…
     Route::get('orders/usertransactions', [OrderController::class, 'getTransactions'])
          ->name('orders.getTransactions');
     Route::resource('suggestions', SuggestionController::class)
          ->only(['index', 'create', 'store', 'destroy']);
     Route::prefix('discounts')->name('discounts.')->group(fn() => [
          Route::get('create', [DiscountController::class, 'create'])->name('create'),
          Route::get('index', [DiscountController::class, 'index'])->name('index'),
          // …store, show, edit, update, destroy…

     ]);
     Route::prefix('products')->name('products.')->group(fn() => [
          Route::get('create', [ProductController::class, 'create'])->name('create'),
          // …store, edit, update, destroy…
     ]);

     // Flower stock (non-admin: just the “stock” form view + action)
     Route::get('flowers/stock',           [FlowerController::class, 'stock'])->name('flowers.stock');
     Route::post('flowers/{flower}/stock',  [FlowerController::class, 'stockupdate'])->name('flowers.stockupdate');
});

// ────────────────────────────────────────────────────────────────
// ADMIN-ONLY (auth + verified + admin middleware)
// ────────────────────────────────────────────────────────────────

Route::middleware(['auth', 'verified', 'admin'])
     ->prefix('admin')
     ->name('admin.')
     ->group(function () {

          Route::get('health', function () {
               try {
                    $dbOk    = DB::connection()->getPdo() !== null;
                    $queueOk = Queue::size('default') !== null;
                    $mailOk  = true; // or ping your mailer here

                    $healthy = $dbOk && $queueOk && $mailOk;
               } catch (\Exception $e) {
                    $healthy = false;
                    $dbOk    = $dbOk ?? false;
                    $queueOk = $queueOk ?? false;
                    $mailOk  = $mailOk ?? false;
               }

               return response()->json([
                    'healthy'   => $healthy,
                    'checks'    => [
                         'database' => $dbOk,
                         'queue'    => $queueOk,
                         'mailer'   => $mailOk,
                    ],
                    'timestamp' => now()->toIso8601String(),
               ]);
          })->name('health');
          // Dashboard
          Route::get('/', [AdminController::class, 'index'])->name('index');
          Route::patch(
               'orders/{order}/status',
               [AdminController::class, 'updateOrderStatus']
          )->name('orders.updateStatus');

          // ─── Admin Flowers Subpage ─────────────────────────────
          // List & show the Flowers subpage inside the dashboard
          Route::get('flowers',                 [AdminController::class, 'flowers'])
               ->name('flowers.index');
          // Create a new flower
          Route::post('flowers',                 [AdminController::class, 'storeFlower'])
               ->name('flowers.store');
          // Update flower details
          Route::put('flowers/{flower}',        [AdminController::class, 'updateFlower'])
               ->name('flowers.update');
          // Add stock to an existing flower
          Route::post('flowers/{flower}/stock',  [AdminController::class, 'stockUpdateFlower'])
               ->name('flowers.stockupdate');
          // Delete a flower
          Route::delete('flowers/{flower}',        [AdminController::class, 'destroyFlower'])
               ->name('flowers.destroy');


          // ─── Admin Packagings Subpage ─────────────────────────────
          Route::get('packagings',            [AdminController::class, 'packagings'])->name('packagings.index');
          Route::post('packagings',            [AdminController::class, 'storePackaging'])->name('packagings.store');
          Route::put('packagings/{packaging}', [AdminController::class, 'updatePackaging'])->name('packagings.update');
          Route::delete('packagings/{packaging}', [AdminController::class, 'destroyPackaging'])->name('packagings.destroy');

          // ─── Admin Discounts Subpage ───────────────────────────
          // List & show the Discounts subpage inside the dashboard
          Route::get('discounts', [AdminController::class, 'discounts'])
               ->name('discounts.index');
          // Create a new discount
          Route::post('discounts', [AdminController::class, 'storeDiscount'])
               ->name('discounts.store');
          // Update discount details
          Route::put('discounts/{discount}', [AdminController::class, 'updateDiscount'])
               ->name('discounts.update');
          // Delete a discount
          Route::delete('discounts/{discount}', [AdminController::class, 'destroyDiscount'])
               ->name('discounts.destroy');

          // ─── Admin Suggestions Subpage ───────────────────────────
          Route::delete('suggestions/{suggestion}', [AdminController::class, 'destroySuggestion'])
               ->name('suggestions.destroy');

          // ─── Admin Products Subpage ───────────────────────────
          Route::get('products/{product}', [AdminController::class, 'showProduct'])
               ->name('products.showJson');
          // Create new product
          Route::post('products', [AdminController::class, 'storeProduct'])
               ->name('products.store');

          // Update existing product
          Route::put('products/{product}', [AdminController::class, 'updateProduct'])
               ->name('products.update');

          //Delete product
          Route::delete('products/{product}', [AdminController::class, 'destroyProduct'])
               ->name('products.destroy');
     });
