<?php

use App\Http\Controllers\AdminController;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\ManageUserController;
use App\Http\Controllers\ProductController;
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

// Admin Routes
Route::middleware(['auth'])->prefix('admin')->group(function () {
    // Dashboard
    Route::get('/dashboard', [AdminController::class, 'dashboard'])->name('admin.dashboard');
});

// Admin Product Routes
Route::prefix('admin')->name('admin.')->group(function () {
    Route::get('/products', [ProductController::class, 'index'])->name('manageproduct.index');
    Route::get('/products/create', [ProductController::class, 'create'])->name('manageproduct.create');
    Route::post('/products', [ProductController::class, 'store'])->name('manageproduct.store');
    Route::get('/products/{product}', [ProductController::class, 'show'])->name('manageproduct.show');
    Route::get('/products/{product}/edit', [ProductController::class, 'edit'])->name('manageproduct.edit');
    Route::put('/products/{product}', [ProductController::class, 'update'])->name('manageproduct.update');
    Route::delete('/products/{product}', [ProductController::class, 'destroy'])->name('manageproduct.destroy');

    // Bulk actions
    Route::post('/products/bulk-action', [ProductController::class, 'bulkAction'])->name('products.bulk-action');
    Route::put('/products/{product}/status', [ProductController::class, 'updateStatus'])->name('products.update-status');
    Route::delete('/product-images/{image}', [ProductController::class, 'deleteImage'])->name('products.delete-image');
});



// Manageuser Routes
Route::resource('manageuser', ManageUserController::class);

// Additional manageuser routes
Route::middleware(['auth'])->group(function () {
    Route::prefix('manageuser')->group(function () {
        Route::post('/{manageuser}/suspend', [ManageUserController::class, 'suspend'])->name('manageuser.suspend');
        Route::post('/{manageuser}/activate', [ManageUserController::class, 'activate'])->name('manageuser.activate');
        Route::post('/bulk-action', [ManageUserController::class, 'bulkAction'])->name('manageuser.bulk-action');
    });
});