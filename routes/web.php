<?php

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
use App\Http\Controllers\BidController;
use App\Http\Controllers\FavoriteController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\WishlistController;
use App\Http\Controllers\ContactController;
use App\Http\Controllers\BrandController;
use App\Http\Controllers\ManageProductController;
use App\Http\Controllers\PaymentController;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/


// ==================== AUTHENTICATION ROUTES ====================
// Disable Laravel's default email verification since we have custom 2-step verification
Auth::routes(['register' => true, 'verify' => false]);


// ==================== CUSTOM EMAIL VERIFICATION ROUTES ====================
// Custom 2-step registration verification routes
Route::controller(\App\Http\Controllers\Auth\RegisterController::class)->group(function () {
    Route::get('/register/verify/{token}', 'verifyEmail')->name('register.verify');
    Route::post('/register/resend-verification', 'resendVerification')->name('register.resend-verification');
    Route::get('/register/success', 'showSuccessPage')->name('register.success');
});



// ==================== PUBLIC ROUTES (No login required) ====================

// Home Page
Route::get('/', [HomeController::class, 'index'])->name('home');

// Product Routes (Public)
Route::controller(ProductController::class)->group(function () {
    Route::get('/products', 'index')->name('products.index');
    Route::get('/products/{productId}/variations', 'getVariations')
        ->whereNumber('productId')
        ->name('products.variations');
    Route::get('/products/featured', 'featured')->name('products.featured');
    Route::get('/products/recommended', 'recommended')->name('products.recommended');
    Route::get('/products/category/{slug}', 'category')->name('products.category');
    Route::get('/products/{slug}', 'show')->name('products.show');
    Route::get('/product/search', 'quickSearch')->name('products.quick-search');
});

// Cart Routes (Public)
Route::controller(CartController::class)
    ->prefix('cart')
    ->name('cart.')
    ->group(function () {
        Route::get('/', 'index')->name('index');
        Route::post('/add', 'add')->name('add');
        Route::post('/update/{id}', 'update')->name('update');
        Route::post('/increase/{id}', 'increase')->name('increase');
        Route::post('/decrease/{id}', 'decrease')->name('decrease');
        Route::delete('/remove/{id}', 'remove')->name('remove');
        Route::post('/clear', 'clear')->name('clear');
        Route::get('/count', 'getCount')->name('count');
        Route::get('/validate-stock', 'validateStock')->name('validate.stock');
        Route::get('/validate-quantities', 'validateQuantities')->name('validate.quantities');
    });

// Checkout result pages
Route::get('/checkout/success/{order}', [CheckoutController::class, 'success'])->name('checkout.success');
Route::get('/checkout/failed', [CheckoutController::class, 'failed'])->name('checkout.failed');

// Bid Routes (Combined - Public and Authenticated)
Route::controller(BidController::class)
    ->prefix('bid')
    ->name('bid.')
    ->group(function () {
        // Public routes
        Route::get('/', 'index')->name('index');
        Route::get('/{id}', 'show')->name('show');
        
        // Authenticated routes only
        Route::middleware(['auth'])->group(function () {
            Route::post('/{id}/place', 'placeBid')->name('place');
            Route::get('/{id}/checkout', 'processBidCheckout')->name('checkout');
        });
    });

// Brand auction pages (Public)
Route::get('/brand/{brand}/auctions', [BidController::class, 'brandAuctions'])
    ->name('brand.auctions');

// Static Pages (Public)
Route::get('/about', function () {
    return view('aboutus.index');
})->name('about');

Route::get('/contact', function () {
    return view('pages.contact');
})->name('contact');

Route::post('/contact', function () {
    return redirect()
        ->back()
        ->with('success', 'Thank you for your message. We will get back to you soon!');
})->name('contact.store');

Route::get('/faq', function () {
    return view('pages.faq');
})->name('faq');

Route::get('/shipping-info', function () {
    return view('policy.shippinginfo');
})->name('shipping.info');

Route::get('/return-policy', function () {
    return view('policy.returnsrefunds');
})->name('return.policy');

Route::get('/privacy-policy', function () {
    return view('policy.privacypolicy');
})->name('privacy.policy');

Route::get('/terms-conditions', function () {
    return view('policy.termsconditions');
})->name('terms.conditions');

// API Routes for AJAX calls (Public)
Route::prefix('api')->name('api.')->group(function () {

    Route::get('/products/{productId}/variations', [ProductController::class, 'getVariations'])
        ->whereNumber('productId')
        ->name('products.variations');

    Route::get('/check-auth', function () {
        return response()->json([
            'authenticated' => auth()->check(),
            'user' => auth()->check() ? [
                'id'    => auth()->user()->id,
                'name'  => auth()->user()->name,
                'email' => auth()->user()->email,
                'verified' => auth()->user()->hasVerifiedEmail(),
            ] : null,
        ]);
    })->name('check.auth');

    Route::get('/states/{country}', [CheckoutController::class, 'getStates'])->name('states');
    Route::get('/cities/{state}', [CheckoutController::class, 'getCities'])->name('cities');
});


// ==================== AUTHENTICATED ROUTES (Require login) ====================
Route::middleware(['auth'])->group(function () {

    Route::post('/buy-now', [ProductController::class, 'buyNow'])->name('buy-now');

    /*Checkout Routes (Authentication Required)*/
    Route::prefix('checkout')->name('checkout.')->group(function () {
        Route::get('/', [CheckoutController::class, 'index'])->name('index');
        Route::get('/review', [CheckoutController::class, 'review'])->name('review');

        // Checkout actions
        Route::post('/place-order', [CheckoutController::class, 'placeOrder'])->name('place-order');
        Route::post('/validate', [CheckoutController::class, 'validateCheckout'])->name('validate');
        Route::post('/calculate-shipping', [CheckoutController::class, 'calculateShipping'])->name('calculate-shipping');
        Route::post('/apply-promo', [CheckoutController::class, 'applyPromo'])->name('apply-promo');
        Route::post('/remove-promo', [CheckoutController::class, 'removePromoCode'])->name('remove-promo');

        // Address routes (checkout-specific)
        Route::post('/address/store', [CheckoutController::class, 'storeAddress'])->name('address.store');
        Route::get('/addresses', [CheckoutController::class, 'getAddresses'])->name('addresses.index');
        Route::put('/address/{address}', [CheckoutController::class, 'updateAddress'])->name('address.update');
        Route::delete('/address/{address}', [CheckoutController::class, 'deleteAddress'])->name('address.delete');

        // Checkout data
        Route::get('/shipping-methods', [CheckoutController::class, 'getShippingMethods'])->name('shipping-methods');
        Route::get('/payment-methods', [CheckoutController::class, 'getPaymentMethods'])->name('payment-methods');
        Route::get('/verify-stock', [CheckoutController::class, 'verifyStock'])->name('verify-stock');

        // Clear buy now session route
        Route::post('/clear-buy-now', [CheckoutController::class, 'clearBuyNow'])->name('clear-buy-now');
    });

    /*Order Routes (Customer side)*/
    Route::prefix('orders')->name('orders.')->controller(OrderController::class)->group(function () {
        Route::get('/', 'index')->name('index');
        Route::get('/{order}', 'show')->name('show');
        Route::get('/{order}/details', 'details')->name('details');
        Route::post('/{order}/cancel', 'cancel')->name('cancel');

        // If not used anywhere, you can remove this later
        Route::post('/process-checkout', 'processCheckout')->name('process-checkout');
    });

    /*Profile Routes (My Account area)*/
    Route::prefix('profile')
        ->name('profile.')
        ->controller(ProfileController::class)
        ->group(function () {
            Route::get('/', 'index')->name('index');

            Route::get('/orders', 'orders')->name('orders.index');

            // Personal Information (editpersonal.blade.php)
            Route::get('/personal', 'editPersonal')->name('personal.edit');
            Route::put('/personal', 'updatePersonal')->name('personal.update');

            // Manage Address (editaddress.blade.php)
            Route::get('/addresses', 'addresses')->name('addresses.index');
            Route::post('/addresses', 'storeAddress')->name('addresses.store');
            Route::put('/addresses/{address}', 'updateAddress')->name('addresses.update');
            Route::delete('/addresses/{address}', 'deleteAddress')->name('addresses.delete');

            // Payment Method (editpayment.blade.php)
            Route::get('/payment-methods', 'paymentMethods')->name('payment.index');
            Route::post('/payment-methods/cards', 'storeCard')->name('payment.cards.store');
            Route::delete('/payment-methods/cards/{card}', 'destroyCard')->name('payment.cards.destroy');
            Route::post('/payment-methods/wallets/{wallet}/toggle', 'toggleWallet')
                ->name('payment.wallets.toggle');

            // Password Manager (changepassword.blade.php)
            Route::get('/password', 'editPassword')->name('password.edit');
            Route::put('/password', 'updatePassword')->name('password.update');
        });

    /*Favorite Routes*/
    Route::prefix('favorites')->name('favorites.')->controller(FavoriteController::class)->group(function () {
        Route::get('/', 'index')->name('index');
        Route::post('/add', 'add')->name('add');
        Route::post('/remove', 'remove')->name('remove');
    });
    
    /*Review Routes*/
    Route::prefix('reviews')->name('reviews.')->controller(ReviewController::class)->group(function () {
        Route::get('/{product}/create', 'create')->name('create');
        Route::get('/{review}/edit', 'edit')->name('edit');

        Route::post('/', 'store')->name('store');
        Route::post('/{product}', 'storeProductReview')->name('store.product');
        Route::put('/{review}', 'update')->name('update');
        Route::delete('/{review}', 'destroy')->name('destroy');
    });

    /*PAYMENT PROCESSING ROUTES (Require login only)*/
    Route::prefix('payment')
        ->name('payment.')
        ->controller(PaymentController::class)
        ->group(function () {
            Route::post('/process', 'process')->name('process');
        });

});



// ==================== ADMIN ROUTES ====================
Route::middleware(['auth'])
    ->prefix('admin')
    ->name('admin.')
    ->group(function () {
        // Admin Dashboard
        Route::get('/dashboard', [AdminController::class, 'dashboard'])->name('dashboard');
        
        // Admin Product Management Routes
        Route::prefix('products')
            ->name('manageproduct.')
            ->controller(ManageProductController::class)
            ->group(function () {
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
        Route::prefix('products/{product}/variations')
            ->name('variations.')
            ->controller(ManageProductVariationController::class)
            ->group(function () {
                Route::get('/create', 'create')->name('create');
                Route::post('/', 'store')->name('store');
                Route::get('/{variation}/edit', 'edit')->name('edit');
                Route::put('/{variation}', 'update')->name('update');
                Route::delete('/{variation}', 'destroy')->name('destroy');
                Route::post('/{variation}/toggle-active', 'toggleActive')->name('toggle-active');
            });
        
        // Bid Management Routes
        Route::prefix('bids')
            ->name('managebid.')
            ->controller(ManageBidController::class)
            ->group(function () {
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
        Route::prefix('users')
            ->name('manageuser.')
            ->controller(ManageUserController::class)
            ->group(function () {
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
        Route::prefix('orders')
            ->name('manageorder.')
            ->controller(ManageOrderController::class)
            ->group(function () {
                Route::get('/', 'index')->name('index');
                Route::get('/{order}', 'show')->name('show');
                Route::get('/{order}/edit', 'edit')->name('edit');
                Route::put('/{order}/status', 'updateStatus')->name('update-status');
                Route::put('/{order}/update', 'update')->name('update');
                Route::delete('/{order}', 'destroy')->name('destroy');
                Route::post('/bulk-action', 'bulkAction')->name('bulk-action');
            });
    });


// ==================== PAYMENT CALLBACK ROUTES (NO AUTH) ====================
    Route::prefix('payment')
        ->name('payment.')
        ->controller(PaymentController::class)
        ->group(function () {

            // Stripe
            Route::get('stripe/success/{order}/{session_id}', 'stripeSuccess')
                ->name('stripe.success.path');

            Route::get('stripe/cancel/{order}', 'stripeCancel')
                ->name('stripe.cancel');

            Route::post('stripe/webhook', 'stripeWebhook')
                ->name('stripe.webhook');

            // Toyyibpay
            Route::post('/toyyibpay/callback', 'toyyibpayCallback')->name('toyyibpay.callback');
            Route::get('/toyyibpay/return/{order}', 'toyyibpayReturn')->name('toyyibpay.return');
            
        });


// ==================== FALLBACK ROUTE ====================
Route::fallback(function () {
    return view('errors.404');
});

// Debug route for testing authentication
Route::get('/debug-auth', function() {
    $data = [
        'is_logged_in' => auth()->check(),
        'user' => auth()->check() ? [
            'id' => auth()->id(),
            'email' => auth()->user()->email,
            'verified' => auth()->user()->hasVerifiedEmail(),
        ] : null,
        'session_data' => session()->all(),
    ];
    
    return response()->json($data);
});