<?php

use App\Http\Controllers\DiscountController;
use App\Http\Controllers\SuggestionController;
use App\Http\Controllers\OrderController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\OrderProductController;
use App\Http\Controllers\CartController;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});
Route::get('homepage', function () {
    return view('customerviews.homepage'); // Correct view path without the dot (`.`)
})->name('homepage'); // Assign route name if needed for generating links

Route::get('catalogue', [ProductController::class, 'customerCatalogue'])->name('products.catalogue');

Route::get('/cart',               [CartController::class, 'index'])->name('cart.index');
Route::post('/cart/{product}',    [CartController::class, 'add'])->name('cart.add');      // you already have this
Route::patch('/cart/{product}',   [CartController::class, 'update'])->name('cart.update');
Route::delete('/cart/{product}',  [CartController::class, 'remove'])->name('cart.remove');

Auth::routes();

// Primary CRUD resources

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



//Login Routes
// ── Protected (requires auth) ──────────────────────────────────────────────
Route::middleware('auth')->group(function () {
    // ── Order Routes ────────────────────────────────────────────────────────────
    Route::prefix('orders')->name('orders.')->group(function () {
        Route::get('{order}/edit', [OrderController::class, 'edit'])->name('edit');
        Route::match(['put', 'patch'], '{order}', [OrderController::class, 'update'])->name('update');
        Route::delete('{order}', [OrderController::class, 'destroy'])->name('destroy');
    });


    // ── Suggestion Routes ────────────────────────────────────────────────────────
    Route::delete('suggestions/{suggestion}', [SuggestionController::class, 'destroy'])->name('suggestions.destroy');


    // ── Discount Routes ────────────────────────────────────────────────────────
    Route::prefix('discounts')->name('discounts.')->group(function () {
        // 1. Show the “create” form
        Route::get('create', [DiscountController::class, 'create'])->name('create');
        // 2. Save a new discount
        Route::post('/', [DiscountController::class, 'store'])->name('store');
        // 3. Show a single discount’s details
        Route::get('{discount}', [DiscountController::class, 'show'])->name('show');
        // 4. Show the “edit” form for an existing discount
        Route::get('{discount}/edit', [DiscountController::class, 'edit'])->name('edit');
        // 5. Update an existing discount
        Route::match(['put', 'patch'], '{discount}', [DiscountController::class, 'update'])->name('update');
        // 6. Delete a discount
        Route::delete('{discount}', [DiscountController::class, 'destroy'])->name('destroy');
    });


    // ── Product Routes ────────────────────────────────────────────────────────
    Route::prefix('products')->name('products.')->group(function () {
        // 2. Show the “create” form
        Route::get('create', [ProductController::class, 'create'])->name('create');
        // 3. Save a new product
        Route::post('/', [ProductController::class, 'store'])->name('store');
        // 5. Show the “edit” form
        Route::get('{product}/edit', [ProductController::class, 'edit'])->name('edit');
        // 6. Update an existing product
        Route::match(['put', 'patch'], '{product}', [ProductController::class, 'update'])->name('update');
        // 7. Delete a product
        Route::delete('{product}', [ProductController::class, 'destroy'])->name('destroy');
    });
});

// ── Public (no auth) ────────────────────────────────────────────────────────
// ── Order Routes ────────────────────────────────────────────────────────────
Route::prefix('orders')->name('orders.')->group(function () {
    // 1. List all orders
    Route::get('/',          [OrderController::class, 'index'])->name('index');
    // 2. Show "Create" form
    Route::get('create',     [OrderController::class, 'create'])->name('create');
    // 3. Save a new order
    Route::post('/',         [OrderController::class, 'store'])->name('store');
    // 4. Display a single order
    Route::get('{order}',    [OrderController::class, 'show'])->name('show');
});


// ── Suggestion Routes ────────────────────────────────────────────────────────
Route::prefix('suggestions')->name('suggestions.')->group(function () {
    // 1. List all suggestions
    Route::get('/', [SuggestionController::class, 'index'])->name('index');
    // 2. Show the “create” form
    Route::get('create', [SuggestionController::class, 'create'])->name('create');
    // 3. Save a new suggestion
    Route::post('/', [SuggestionController::class, 'store'])->name('store');
});


//// ── Discount Routes ────────────────────────────────────────────────────────
// 1. List all discounts
Route::get('/discounts', [DiscountController::class, 'index'])->name('discounts.index');


//// ── Product Routes ────────────────────────────────────────────────────────
// 1. List all products
Route::get('/products', [ProductController::class, 'index'])->name('products.index');
// 2. Display a single product
Route::get('/products/{product}', [ProductController::class, 'show'])->name('products.show');