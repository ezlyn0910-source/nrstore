<?php
// routes/web.php

use App\Http\Controllers\ManageProductVariationController;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\CartController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\ManageUserController;
use App\Http\Controllers\ManageOrderController;
use App\Http\Controllers\ManageBidController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\OrderController;
use App\Http\Controllers\CheckoutController;
use App\Http\Controllers\ReviewController;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;

// Authentication Routes
Auth::routes();

// User Routes
// Starter/Landing Page
Route::get('/', function () {
    return view('homepage');
})->name('home');

// Homepage
Route::get('/homepage', function () {
    return view('homepage');
})->name('homepage');

// Authentication Routes
Auth::routes(['register' => true]); // Enable registration if needed

// Public Product Routes (for customers)
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
Route::controller(ProductController::class)->group(function () {
    Route::get('/products', 'index')->name('products.index');
    Route::get('/products/featured', 'featured')->name('products.featured');
    Route::get('/products/recommended', 'recommended')->name('products.recommended');
    Route::get('/products/category/{slug}', 'category')->name('products.category');
    Route::get('/products/{slug}', 'show')->name('products.show');
    Route::get('/product/search', 'quickSearch')->name('products.quick-search');
});

// Cart Routes (for customers)
Route::controller(CartController::class)->group(function () {
    Route::get('/cart', 'index')->name('cart.index');
    Route::post('/cart/add', 'add')->name('cart.add');
    Route::put('/cart/update/{cartItem}', 'update')->name('cart.update');
    Route::delete('/cart/remove/{cartItem}', 'remove')->name('cart.remove');
    Route::post('/cart/clear', 'clear')->name('cart.clear');
    Route::get('/cart/count', 'getCartCount')->name('cart.count');
});

// Checkout & Order Routes (for customers)
Route::middleware(['auth'])->group(function () {
    Route::controller(OrderController::class)->group(function () {
        Route::get('/checkout', 'checkout')->name('checkout');
        Route::post('/checkout/process', 'processCheckout')->name('checkout.process');
        Route::get('/orders', 'index')->name('orders.index');
        Route::get('/orders/{order}', 'show')->name('orders.show');
        Route::post('/orders/{order}/cancel', 'cancel')->name('orders.cancel');
    });
});

// Admin Routes
Route::middleware(['auth'])->prefix('admin')->name('admin.')->group(function () {
    // Admin Dashboard
    Route::get('/dashboard', [AdminController::class, 'dashboard'])->name('dashboard');
    
    // Admin Product Management Routes
    Route::prefix('products')->name('manageproduct.')->controller(\App\Http\Controllers\ManageProductController::class)->group(function () {
        Route::get('/', 'index')->name('index');
        Route::get('/create', 'create')->name('create');
        Route::post('/', 'store')->name('store');
        Route::get('/{product}', 'show')->name('show');
        Route::get('/{product}/edit', 'edit')->name('edit');
        Route::put('/{product}', 'update')->name('update');
        Route::delete('/{product}', 'destroy')->name('destroy');
        
        // Additional product actions
        Route::post('/{product}/toggle-featured', 'toggleFeatured')->name('toggle-featured');
        Route::post('/{product}/toggle-active', 'toggleActive')->name('toggle-active');
        Route::get('/search', 'search')->name('search');
        
        // Bulk actions
        Route::post('/bulkAction', 'bulkAction')->name('bulkAction');
        Route::put('/{product}/status', 'updateStatus')->name('update-status');
        Route::delete('/product-images/{image}', 'deleteImage')->name('delete-image');
    });

    // Product Variations Management
    Route::prefix('products/{product}/variations')->name('variations.')->controller(\App\Http\Controllers\ManageProductVariationController::class)->group(function () {
        Route::get('/create', 'create')->name('create');
        Route::post('/', 'store')->name('store');
        Route::get('/{variation}/edit', 'edit')->name('edit');
        Route::put('/{variation}', 'update')->name('update');
        Route::delete('/{variation}', 'destroy')->name('destroy');
        Route::post('/{variation}/toggle-active', 'toggleActive')->name('toggle-active');
    });

    // User Management Routes
    Route::prefix('users')->name('manageuser.')->controller(\App\Http\Controllers\ManageUserController::class)->group(function () {
        Route::get('/', 'index')->name('index');
        Route::get('/create', 'create')->name('create');
        Route::post('/', 'store')->name('store');
        Route::get('/{user}', 'show')->name('show');
        Route::get('/{user}/edit', 'edit')->name('edit');
        Route::put('/{user}', 'update')->name('update');
        Route::delete('/{user}', 'destroy')->name('destroy');
        
        // Additional user actions
        Route::post('/{user}/suspend', 'suspend')->name('suspend');
        Route::post('/{user}/activate', 'activate')->name('activate');
        Route::post('/bulk-action', 'bulkAction')->name('bulk-action');
    });

    // Order Management Routes (Admin)
    Route::prefix('orders')->name('manageorder.')->controller(\App\Http\Controllers\ManageOrderController::class)->group(function () {
        Route::get('/', 'index')->name('index');
        Route::get('/{order}', 'show')->name('show');
        Route::put('/{order}/status', 'updateStatus')->name('update-status');
        Route::put('/{order}/update', 'update')->name('update');
        Route::delete('/{order}', 'destroy')->name('destroy');
        Route::post('/bulk-action', 'bulkAction')->name('bulk-action');
    });

    // routes bids
    Route::prefix('bids')->name('managebid.')->controller(\App\Http\Controllers\ManageBidController::class)->group(function () {
        Route::get('/', 'index')->name('index');
        Route::get('/create', 'create')->name('create');
        Route::get('/{bid}', 'show')->name('show');
        Route::get('/{bid}/edit', 'edit')->name('edit');
        Route::put('/{bid}', 'update')->name('update');
        Route::delete('/{bid}', 'destroy')->name('destroy');
        Route::post('/{bid}/start', 'startBid')->name('start');
        Route::post('/{bid}/pause', 'pauseBid')->name('pause');
        Route::post('/{bid}/complete', 'completeBid')->name('complete');
    });

    // Reports & Analytics
    Route::prefix('reports')->name('reports.')->controller(\App\Http\Controllers\ReportController::class)->group(function () {
        Route::get('/sales', 'sales')->name('sales');
        Route::get('/products', 'products')->name('products');
        Route::get('/users', 'users')->name('users');
        Route::get('/inventory', 'inventory')->name('inventory');
    });
});

// User Profile Routes (for authenticated users)
Route::middleware(['auth'])->group(function () {
    Route::prefix('profile')->name('profile.')->controller(\App\Http\Controllers\ProfileController::class)->group(function () {
        Route::get('/', 'edit')->name('edit');
        Route::put('/', 'update')->name('update');
        Route::put('/password', 'updatePassword')->name('password.update');
        Route::get('/orders', 'orders')->name('orders');
        Route::get('/addresses', 'addresses')->name('addresses');
        Route::post('/addresses', 'storeAddress')->name('addresses.store');
        Route::put('/addresses/{address}', 'updateAddress')->name('addresses.update');
        Route::delete('/addresses/{address}', 'deleteAddress')->name('addresses.delete');
    });
});

// Wishlist Routes (for authenticated users)
Route::middleware(['auth'])->controller(\App\Http\Controllers\WishlistController::class)->group(function () {
    Route::get('/wishlist', 'index')->name('wishlist.index');
    Route::post('/wishlist/add/{product}', 'add')->name('wishlist.add');
    Route::delete('/wishlist/remove/{wishlistItem}', 'remove')->name('wishlist.remove');
    Route::delete('/wishlist/clear', 'clear')->name('wishlist.clear');
});

// Review Routes (for authenticated users who purchased)
Route::middleware(['auth'])->controller(\App\Http\Controllers\ReviewController::class)->group(function () {
    Route::post('/reviews/{product}', 'store')->name('reviews.store');
    Route::put('/reviews/{review}', 'update')->name('reviews.update');
    Route::delete('/reviews/{review}', 'destroy')->name('reviews.destroy');
    Route::get('/reviews/{product}/create', 'create')->name('reviews.create');
    Route::get('/reviews/{review}/edit', 'edit')->name('reviews.edit');
});

// Static Pages
Route::get('/about', function () {
    return view('pages.about');
})->name('about');

Route::get('/contact', function () {
    return view('pages.contact');
})->name('contact');

Route::post('/contact', [\App\Http\Controllers\ContactController::class, 'store'])->name('contact.store');

Route::get('/faq', function () {
    return view('pages.faq');
})->name('faq');

Route::get('/shipping-info', function () {
    return view('pages.shipping');
})->name('shipping.info');

Route::get('/return-policy', function () {
    return view('pages.returns');
})->name('return.policy');

Route::get('/privacy-policy', function () {
    return view('pages.privacy');
})->name('privacy.policy');

Route::get('/terms-conditions', function () {
    return view('pages.terms');
})->name('terms.conditions');

// Fallback Route (404 Page)
Route::fallback(function () {
    return view('errors.404');
});