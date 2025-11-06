<?php

use App\Http\Controllers\AdminController;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\ManageUserController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\OrderController;
use App\Http\Controllers\CheckoutController;
use App\Http\Controllers\ReviewController;
use Illuminate\Support\Facades\Route;

// Authentication Routes
Auth::routes();

// Public Routes
Route::get('/', function () {
    return view('homepage');
});

// Public Product Routes (for customers)
// Public Product Routes
Route::get('/products', [ProductController::class, 'index'])->name('productpage');
Route::get('/products/{product}', [ProductController::class, 'show'])->name('products.show');
Route::get('/products/{product}/slug', [ProductController::class, 'showBySlug'])->name('products.show.slug');

// Order Routes
//Route::middleware(['auth'])->group(function () {
    //Route::get('/orders', [OrderController::class, 'index'])->name('orders.index');
    //Route::get('/orders/{order}', [OrderController::class, 'show'])->name('orders.show');
    //Route::get('/orders/{order}/details', [OrderController::class, 'details'])->name('orders.details');
    //Route::post('/orders/cancel', [OrderController::class, 'cancel'])->name('orders.cancel');
    Route::get('/orders', [OrderController::class, 'index'])->name('orders.index');
    Route::get('/orders/{order}', [OrderController::class, 'show'])->name('orders.show');
    Route::get('/orders/{order}/details', [OrderController::class, 'details'])->name('orders.details');
    Route::post('/orders/cancel', [OrderController::class, 'cancel'])->name('orders.cancel');

    // Checkout Routes
    //Route::get('/checkout', [CheckoutController::class, 'show'])->name('checkout.show');
    //Route::post('/checkout/place-order', [CheckoutController::class, 'placeOrder'])->name('checkout.place-order');
    //Route::post('/apply-promo-code', [CheckoutController::class, 'applyPromoCode'])->name('checkout.apply-promo');
    Route::get('/checkout', [CheckoutController::class, 'show'])->name('checkout.show');
    Route::post('/checkout/place-order', [CheckoutController::class, 'placeOrder'])->name('checkout.place-order');
    Route::post('/apply-promo-code', [CheckoutController::class, 'applyPromoCode'])->name('checkout.apply-promo');

    // Review Routes
    //Route::post('/reviews', [ReviewController::class, 'store'])->name('reviews.store');
    Route::post('/reviews', [ReviewController::class, 'store'])->name('reviews.store');
//});

// Admin Routes
Route::middleware(['auth'])->prefix('admin')->group(function () {
    // Dashboard
    Route::get('/dashboard', [AdminController::class, 'dashboard'])->name('admin.dashboard');
    
    // Admin Product Management Routes
    Route::prefix('products')->name('manageproduct.')->group(function () {
        Route::get('/', [ManageProductController::class, 'index'])->name('index');
        Route::get('/create', [ManageProductController::class, 'create'])->name('create');
        Route::post('/', [ManageProductController::class, 'store'])->name('store');
        Route::get('/{product}', [ManageProductController::class, 'show'])->name('show');
        Route::get('/{product}/edit', [ManageProductController::class, 'edit'])->name('edit');
        Route::put('/{product}', [ManageProductController::class, 'update'])->name('update');
        Route::delete('/{product}', [ManageProductController::class, 'destroy'])->name('destroy');
        
        // Additional product actions
        Route::post('/{product}/toggle-featured', [ManageProductController::class, 'toggleFeatured'])->name('toggle-featured');
        Route::post('/{product}/toggle-active', [ManageProductController::class, 'toggleActive'])->name('toggle-active');
        Route::get('/search', [ManageProductController::class, 'search'])->name('search');
        
        // Bulk actions
        Route::post('/bulk-action', [ManageProductController::class, 'bulkAction'])->name('bulk-action');
        Route::put('/{product}/status', [ManageProductController::class, 'updateStatus'])->name('update-status');
        Route::delete('/product-images/{image}', [ManageProductController::class, 'deleteImage'])->name('delete-image');
    });

    // Manageuser Routes
    Route::resource('manageuser', ManageUserController::class);
    
    // Additional manageuser routes
    Route::prefix('manageuser')->group(function () {
        Route::post('/{manageuser}/suspend', [ManageUserController::class, 'suspend'])->name('manageuser.suspend');
        Route::post('/{manageuser}/activate', [ManageUserController::class, 'activate'])->name('manageuser.activate');
        Route::post('/bulk-action', [ManageUserController::class, 'bulkAction'])->name('manageuser.bulk-action');
    });
});