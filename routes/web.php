<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;


// Controllers
use App\Http\Controllers\HomeController;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\Auth\VerifyNoticeController;
use App\Http\Controllers\CartController;
use App\Http\Controllers\UserProfileController;
// ────────────────────────────────────────────────────────────────
// AUTHENTICATION & PASSWORD RESET
// ────────────────────────────────────────────────────────────────
//   ||Login, Register, Password Reset, Email Verification||
Auth::routes(['verify' => true]);
//   ||Disable the built-in auth-protected route by redefining it||
Route::get('/email/verify', VerifyNoticeController::class)
     // no ->middleware('auth') here
     ->name('verification.notice');
// ────────────────────────────────────────────────────────────────
// PUBLIC ROUTES (no auth required)
// ────────────────────────────────────────────────────────────────
//   ||Post Site Suggestions||
Route::post('/suggestions', [HomeController::class, 'storeSuggestion'])
     ->name('site.suggestions');
//   ||Home page @ customerviews/home.blade.php||
Route::get('home', [HomeController::class, 'index'])->name('home');
//   ||Redirect to home||
Route::get('/', function () {
     return redirect()->route('home');
});


//   ||Add to cart @ home.blade.php, accessible by all.||
Route::post('cart/{product}',  [CartController::class, 'add'])
     ->whereNumber('product')
     ->name('cart.add');


// ────────────────────────────────────────────────────────────────
// AUTHENTICATED & VERIFIED USERS
// ────────────────────────────────────────────────────────────────
Route::middleware(['auth', 'verified'])->group(function () {


     //   ||Checkout Route, POST Request to Create Order, must be first||
     Route::post('cart/checkout', [CartController::class, 'storeOrder'])
          ->name('cart.checkout');

     //   ||Check Discount Route||
     Route::post('cart/discount', [CartController::class, 'discountIfExist'])
          ->name('cart.discount');

     //   ||Get Cart to display in cart.blade.php||
     Route::get('cart',            [CartController::class, 'index'])->name('cart.index');


     //   ||Update Cart, remove product from cart||
     Route::patch('cart/{product}',  [CartController::class, 'update'])
          ->whereNumber('product');
     Route::delete('cart/{product}',  [CartController::class, 'remove'])
          ->whereNumber('product');


     //   ||Show Profile Page @ customerviews/userprofile.blade.php||
     Route::get('/profile', [UserProfileController::class, 'index'])
          ->name('profile.index');
     Route::put('/profile', [UserProfileController::class, 'updateProfile'])
          ->name('profile.update');
     Route::put('/profile/password', [UserProfileController::class, 'updatePassword'])
          ->name('profile.password.update');
     //   ||Upload/Edit payment proof at userprofile.blade.php||
     Route::post(
          '/orders/{order}/upload-payment',
          [UserProfileController::class, 'updatePayment']
     )->name('orders.uploadPayment');
     //   ||Create a new review for completed order without review||
     Route::post(
          '/profile/store-reviews',
          [UserProfileController::class, 'storeReviews']
     )->name('profile.storeReviews');
});

// ────────────────────────────────────────────────────────────────
// ADMIN-ONLY (auth + verified + admin middleware)
// ────────────────────────────────────────────────────────────────
// Why are the Update routes deprecated? Because I reused the same modal to add/edit, so we convert the modal post/put values using JS.
Route::middleware(['auth', 'verified', 'admin'])
     ->prefix('admin')
     ->name('admin.')
     ->group(function () {

          //   ||Check System Health @ admin/index.blade.php||
          Route::get('health', [AdminController::class, 'health'])
               ->name('health');
          // ─── Admin Dashboard Sub/Main-page ─────────────────────────────     
          //   ||Get admin.index.blade.php||
          Route::get('/', [AdminController::class, 'index'])->name('index');
          //   ||Update Orders->Order-Details->Order_Progress @ index.blade.php||
          Route::patch(
               'orders/{order}/status',
               [AdminController::class, 'updateOrderStatus']
          )->name('orders.updateStatus');

          // ─── Admin Flowers Subpage ─────────────────────────────
          //   ||Get flowers data for flowers subpage @admin.index.blade.php|| DEPRECATED -> Data handling done in index function @ AdminController
          Route::get('flowers',                 [AdminController::class, 'flowers'])
               ->name('flowers.index');
          //   ||Create a new flower||
          Route::post('flowers',                 [AdminController::class, 'storeFlower'])
               ->name('flowers.store');
          //   ||Update a flower||
          Route::put('flowers/{flower}',        [AdminController::class, 'updateFlower'])
               ->name('flowers.update');
          //   ||Update a flower's stock||
          Route::post('flowers/{flower}/stock',  [AdminController::class, 'stockUpdateFlower'])
               ->name('flowers.stockupdate');
          //   ||Delete a flower||
          Route::delete('flowers/{flower}',        [AdminController::class, 'destroyFlower'])
               ->name('flowers.destroy');

          // ─── Admin Packagings Subpage ─────────────────────────────
          //   ||Get packagings data for packagings subpage @admin.index.blade.php|| DEPRECATED -> Data handling done in index function @ AdminController
          Route::get('packagings',            [AdminController::class, 'packagings'])->name('packagings.index');
          //   ||Create a new packaging||
          Route::post('packagings',            [AdminController::class, 'storePackaging'])->name('packagings.store');
          //   ||Update a packaging||
          Route::put('packagings/{packaging}', [AdminController::class, 'updatePackaging'])->name('packagings.update');
          //   ||Delete a packaging||
          Route::delete('packagings/{packaging}', [AdminController::class, 'destroyPackaging'])->name('packagings.destroy');

          // ─── Admin Discounts Subpage ───────────────────────────
          //   ||Get discounts data for discounts subpage @admin.index.blade.php|| DEPRECATED -> Data handling done in index function @ AdminController
          Route::get('discounts', [AdminController::class, 'discounts'])
               ->name('discounts.index');
          //   ||Create a discount||
          Route::post('discounts', [AdminController::class, 'storeDiscount'])
               ->name('discounts.store');
          //   ||Update a discount||
          Route::put('discounts/{discount}', [AdminController::class, 'updateDiscount'])
               ->name('discounts.update');
          //   ||Delete a discount||
          Route::delete('discounts/{discount}', [AdminController::class, 'destroyDiscount'])
               ->name('discounts.destroy');

          // ─── Admin Suggestions Subpage ───────────────────────────
          //   ||Delete a suggestion||
          Route::delete('suggestions/{suggestion}', [AdminController::class, 'destroySuggestion'])
               ->name('suggestions.destroy');

          // ─── Admin Products Subpage ───────────────────────────
          //   ||Get products data for products subpage @admin.index.blade.php|| DEPRECATED -> Data handling done in index function @ AdminController
          Route::get('products/{product}', [AdminController::class, 'showProduct'])
               ->name('products.showJson');
          //   ||Create a new product||
          Route::post('products', [AdminController::class, 'storeProduct'])
               ->name('products.store');

          //   ||Update a product||
          Route::put('products/{product}', [AdminController::class, 'updateProduct'])
               ->name('products.update');

          //   ||Delete a product||
          Route::delete('products/{product}', [AdminController::class, 'destroyProduct'])
               ->name('products.destroy');

          //   ||Update a delivery|| 
         Route::put('deliveries/{delivery}/fee', [AdminController::class, 'updateDeliveryFee'])
              ->name('deliveries.updateFee');

         //    ||Delete a delivery|| 
         Route::delete('deliveries/{delivery}', [AdminController::class, 'destroyDelivery'])
              ->name('deliveries.destroy');
     });
