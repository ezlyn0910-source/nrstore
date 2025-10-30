<?php

use App\Http\Controllers\AdminController;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\ProductController;
use Illuminate\Support\Facades\Route;

// Authentication Routes
Auth::routes();

// Admin Routes
Route::middleware(['auth'])->group(function () {
    Route::get('/admin/dashboard', [AdminController::class, 'dashboard'])->name('admin.dashboard');
});

// Home Route  
Route::get('/', [HomeController::class, 'index'])->name('home');

// Public Route
Route::get('/', function () {
    return view('homepage');
});

// Product Routes
Route::get('/products', [ProductController::class, 'index'])->name('products.index');
Route::get('/products/{id}', [ProductController::class, 'show'])->name('products.show');

// Category-based filtering
Route::get('/categories/{category}', [ProductController::class, 'index'])->name('products.by_category');