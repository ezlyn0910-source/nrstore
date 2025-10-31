<?php

use App\Http\Controllers\AdminController;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\ManageUserController;
use App\Http\Controllers\ProductController;
use Illuminate\Support\Facades\Route;

// Authentication Routes
Auth::routes();

// Admin Routes
Route::middleware(['auth'])->prefix('admin')->group(function () {
    // Dashboard
    Route::get('/dashboard', [AdminController::class, 'dashboard'])->name('admin.dashboard');
});

// Public and Home Route
Route::get('/', function () {
    return view('homepage');
});

// Product Routes
Route::get('/manageproduct', [ProductController::class, 'index'])->name('manageproduct.index');
Route::get('/manageproduct/{id}', [ProductController::class, 'show'])->name('manageproduct.show');

// Category-based filtering
Route::get('/categories/{category}', [ProductController::class, 'index'])->name('manageproduct.by_category');
Route::resource('manageproduct', ProductController::class);

// Manageuser Routes with additional custom routes
Route::resource('manageuser', ManageUserController::class);

// Additional manageuser routes
Route::middleware(['auth'])->group(function () {
    Route::prefix('manageuser')->group(function () {
        Route::post('/{manageuser}/suspend', [ManageUserController::class, 'suspend'])->name('manageuser.suspend');
        Route::post('/{manageuser}/activate', [ManageUserController::class, 'activate'])->name('manageuser.activate');
        Route::post('/bulk-action', [ManageUserController::class, 'bulkAction'])->name('manageuser.bulk-action');
    });
});

// Variation Routes
Route::middleware(['auth'])->group(function () {
    Route::prefix('manageproduct/{product}')->group(function () {
        Route::get('/variations/create', [ProductController::class, 'createVariation'])->name('manageproduct.variations.create');
        Route::post('/variations', [ProductController::class, 'storeVariation'])->name('manageproduct.variations.store');
        Route::get('/variations/{variation}/edit', [ProductController::class, 'editVariation'])->name('manageproduct.variations.edit');
        Route::put('/variations/{variation}', [ProductController::class, 'updateVariation'])->name('manageproduct.variations.update');
        Route::delete('/variations/{variation}', [ProductController::class, 'destroyVariation'])->name('manageproduct.variations.destroy');
    });
});
