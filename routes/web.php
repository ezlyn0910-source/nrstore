<?php

use App\Http\Controllers\AdminController;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\ProductController;

// Authentication Routes
Auth::routes();

// Admin Routes
Route::middleware(['auth'])->group(function () {
    Route::get('/admin/dashboard', [AdminController::class, 'dashboard'])->name('admin.dashboard');
});

// Public and Home Route
Route::get('/', function () {
    return view('homepage');
});

// Product Routes
Route::resource('products', ProductController::class);

// Variation Routes - Make sure these are inside the resource or properly grouped
Route::middleware(['auth'])->group(function () {
    Route::prefix('products/{product}')->group(function () {
        Route::get('/variations/create', [ProductController::class, 'createVariation'])->name('products.variations.create');
        Route::post('/variations', [ProductController::class, 'storeVariation'])->name('products.variations.store');
        Route::get('/variations/{variation}/edit', [ProductController::class, 'editVariation'])->name('products.variations.edit');
        Route::put('/variations/{variation}', [ProductController::class, 'updateVariation'])->name('products.variations.update');
        Route::delete('/variations/{variation}', [ProductController::class, 'destroyVariation'])->name('products.variations.destroy');
    });
});