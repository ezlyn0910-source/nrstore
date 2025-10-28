<?php

use App\Http\Controllers\AdminController;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\HomeController;

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