<?php

use App\Http\Controllers\ManageProductVariationController;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\CartController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\ManageUserController;
use App\Http\Controllers\ManageOrderController;
use App\Http\Controllers\ManageBidController;
use App\Http\Controllers\ManageReportController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\OrderController;
use App\Http\Controllers\CheckoutController;
use App\Http\Controllers\ReviewController;
use App\Http\Controllers\BidController;
use App\Http\Controllers\FavoriteController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\WishlistController;
use App\Http\Controllers\ContactController;
use App\Http\Controllers\BrandController;
use App\Http\Controllers\ManageProductController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\PaymentController;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;

// Authentication Routes
Auth::routes(['register' => true]);

// Starter/Landing Page
Route::get('/', [HomeController::class, 'index'])->name('home');

// Public Product Routes
Route::controller(ProductController::class)->group(function () {
    Route::get('/products', 'index')->name('products.index');
    Route::get('/products/featured', 'featured')->name('products.featured');
    Route::get('/products/recommended', 'recommended')->name('products.recommended');
    Route::get('/products/category/{slug}', 'category')->name('products.category');
    Route::get('/products/{slug}', 'show')->name('products.show');
    Route::get('/product/search', 'quickSearch')->name('products.quick-search');
});

// Public Cart Routes
Route::controller(CartController::class)->prefix('cart')->name('cart.')->group(function () {
    Route::get('/', 'index')->name('index');
    Route::post('/add', 'add')->name('add');
    Route::post('/update/{id}', 'update')->name('update');
    Route::post('/increase/{id}', 'increase')->name('increase');
    Route::post('/decrease/{id}', 'decrease')->name('decrease');
    Route::delete('/remove/{id}', 'remove')->name('remove');
    Route::post('/clear', 'clear')->name('clear');
    Route::get('/count', 'getCount')->name('count');
    
    // Cart validation routes - PUBLIC (no auth required)
    Route::get('/validate-stock', 'validateStock')->name('validate.stock');
    Route::get('/validate-quantities', 'validateQuantities')->name('validate.quantities');
});

// API Routes for AJAX calls - PUBLIC (moved outside auth group)
Route::prefix('api')->name('api.')->group(function () {
    Route::get('/check-auth', function () {
        return response()->json([
            'authenticated' => auth()->check(),
            'user' => auth()->check() ? [
                'id' => auth()->user()->id,
                'name' => auth()->user()->name,
                'email' => auth()->user()->email
            ] : null
        ]);
    })->name('check.auth');
    
    Route::get('/states/{country}', [CheckoutController::class, 'getStates'])->name('states');
    Route::get('/cities/{state}', [CheckoutController::class, 'getCities'])->name('cities');
});

// Bid Routes (Public viewing, authenticated for placing bids)
Route::controller(BidController::class)->prefix('bid')->name('bid.')->group(function () {
    Route::get('/', 'index')->name('index');
    Route::get('/{id}', 'show')->name('show');
    Route::middleware(['auth'])->post('/{id}/place', 'placeBid')->name('place');
});

// Brand auction pages
Route::get('/brand/{brand}/auctions', [BrandController::class, 'show'])->name('brand.auctions');

// Static Pages
Route::get('/about', function () {
    return view('pages.about');
})->name('about');

Route::get('/contact', function () {
    return view('pages.contact');
})->name('contact');

Route::post('/contact', [ContactController::class, 'store'])->name('contact.store');

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

// Authenticated User Routes
Route::middleware(['auth'])->group(function () {
    
    // Checkout Routes (Authentication Required)
    Route::prefix('checkout')->name('checkout.')->controller(CheckoutController::class)->group(function () {
        // Checkout pages
        Route::get('/', 'index')->name('index');
        Route::get('/review', 'review')->name('review');
        
        // Checkout actions
        Route::post('/place-order', 'placeOrder')->name('place-order');
        Route::post('/validate', 'validateCheckout')->name('validate');
        Route::post('/calculate-shipping', 'calculateShipping')->name('calculate-shipping');
        Route::post('/apply-promo', 'applyPromoCode')->name('apply-promo');
        Route::post('/remove-promo', 'removePromoCode')->name('remove-promo');
        Route::post('/save-address', 'saveAddress')->name('save-address');
        
        // Checkout data
        Route::get('/shipping-methods', 'getShippingMethods')->name('shipping-methods');
        Route::get('/payment-methods', 'getPaymentMethods')->name('payment-methods');
        Route::get('/verify-stock', 'verifyStock')->name('verify-stock');
        
        // Checkout results
        Route::get('/success/{order}', 'success')->name('success');
        Route::get('/failed', 'failed')->name('failed');
    });

    // Payment Processing Routes
    Route::prefix('payment')->name('payment.')->controller(PaymentController::class)->group(function () {
        Route::post('/process', 'process')->name('process');
        Route::get('/success', 'success')->name('success');
        Route::get('/cancel', 'cancel')->name('cancel');
        Route::post('/webhook/{gateway}', 'webhook')->name('webhook');
    });

    // Order Routes
    Route::controller(OrderController::class)->prefix('orders')->name('orders.')->group(function () {
        Route::get('/', 'index')->name('index');
        Route::get('/{order}', 'show')->name('show');
        Route::get('/{order}/details', 'details')->name('details');
        Route::post('/{order}/cancel', 'cancel')->name('cancel');
        Route::post('/process-checkout', 'processCheckout')->name('process-checkout');
    });

    // Profile Routes
    Route::prefix('profile')->name('profile.')->controller(ProfileController::class)->group(function () {
        Route::get('/', 'edit')->name('edit');
        Route::put('/', 'update')->name('update');
        Route::put('/password', 'updatePassword')->name('password.update');
        Route::get('/orders', 'orders')->name('orders');
        Route::get('/addresses', 'addresses')->name('addresses');
        Route::post('/addresses', 'storeAddress')->name('addresses.store');
        Route::put('/addresses/{address}', 'updateAddress')->name('addresses.update');
        Route::delete('/addresses/{address}', 'deleteAddress')->name('addresses.delete');
    });

    // Favorite Routes
    Route::prefix('favorites')->name('favorites.')->controller(FavoriteController::class)->group(function () {
        Route::get('/', 'index')->name('index');
        Route::post('/add', 'add')->name('add');
        Route::post('/remove', 'remove')->name('remove');
    });

    // Wishlist Routes
    Route::prefix('wishlist')->name('wishlist.')->controller(WishlistController::class)->group(function () {
        Route::get('/', 'index')->name('index');
        Route::post('/add/{product}', 'add')->name('add');
        Route::delete('/remove/{wishlistItem}', 'remove')->name('remove');
        Route::delete('/clear', 'clear')->name('clear');
    });

    // Review Routes
    Route::prefix('reviews')->name('reviews.')->controller(ReviewController::class)->group(function () {
        Route::post('/', 'store')->name('store');
        Route::post('/{product}', 'storeProductReview')->name('store.product');
        Route::put('/{review}', 'update')->name('update');
        Route::delete('/{review}', 'destroy')->name('destroy');
        Route::get('/{product}/create', 'create')->name('create');
        Route::get('/{review}/edit', 'edit')->name('edit');
    });
});

// Admin Routes
Route::middleware(['auth'])->prefix('admin')->name('admin.')->group(function () {
    // Admin Dashboard
    Route::get('/dashboard', [AdminController::class, 'dashboard'])->name('dashboard');
    
    // Admin Product Management Routes
    Route::prefix('products')->name('manageproduct.')->controller(ManageProductController::class)->group(function () {
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
    Route::prefix('products/{product}/variations')->name('variations.')->controller(ManageProductVariationController::class)->group(function () {
        Route::get('/create', 'create')->name('create');
        Route::post('/', 'store')->name('store');
        Route::get('/{variation}/edit', 'edit')->name('edit');
        Route::put('/{variation}', 'update')->name('update');
        Route::delete('/{variation}', 'destroy')->name('destroy');
        Route::post('/{variation}/toggle-active', 'toggleActive')->name('toggle-active');
    });

    // Bid Management Routes 
    Route::prefix('bids')->name('managebid.')->controller(ManageBidController::class)->group(function () {
        Route::get('/', 'index')->name('index');
        Route::get('/create', 'create')->name('create');
        Route::post('/bulk-action', 'bulkAction')->name('bulk_action');
        Route::post('/', 'store')->name('store');
        Route::get('/{bid}', 'show')->name('show');
        Route::get('/{bid}/edit', 'edit')->name('edit');
        Route::put('/{bid}', 'update')->name('update');
        Route::delete('/{bid}', 'destroy')->name('destroy');
        Route::post('/{bid}/start', 'startBid')->name('start');
        Route::post('/{bid}/pause', 'pauseBid')->name('pause');
        Route::post('/{bid}/complete', 'completeBid')->name('complete');
        
        // Additional bid management routes
        Route::post('/{bid}/assign-winner', 'assignWinner')->name('assign-winner');
        Route::get('/{bid}/participants', 'participants')->name('participants');
        Route::get('/users/{user}/bid-history', 'userBidHistory')->name('user-history');
    });

    // User Management Routes
    Route::prefix('users')->name('manageuser.')->controller(ManageUserController::class)->group(function () {
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
    Route::prefix('orders')->name('manageorder.')->controller(ManageOrderController::class)->group(function () {
        Route::get('/', 'index')->name('index');
        Route::get('/{order}', 'show')->name('show');
        Route::put('/{order}/status', 'updateStatus')->name('update-status');
        Route::put('/{order}/update', 'update')->name('update');
        Route::delete('/{order}', 'destroy')->name('destroy');
        Route::post('/bulk-action', 'bulkAction')->name('bulk-action');
    });

    // Reports & Analytics
    Route::prefix('reports')->name('managereport.')->controller(ManageReportController::class)->group(function () {
        Route::get('/', 'index')->name('index');
        Route::get('/export/{type}', 'export')->name('export');
    });
});

// Fallback Route (404 Page)
Route::fallback(function () {
    return view('errors.404');
});