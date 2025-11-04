<?php

use App\Http\Controllers\AdminController;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\ManageUserController;
use App\Http\Controllers\ManageManageProductController;
use Illuminate\Support\Facades\Route;

// Authentication Routes
Auth::routes();

// Public Routes
Route::get('/', function () {
    return view('homepage');
});

// Public Product Routes (for customers)
Route::get('/products', [ManageProductController::class, 'publicIndex'])->name('products.index');
Route::get('/products/{slug}', [ManageProductController::class, 'publicShow'])->name('products.show');
Route::get('/product/{product}/slug', [ManageProductController::class, 'showBySlug'])->name('products.show.slug');

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