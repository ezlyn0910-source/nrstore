<?php

namespace App\Http\Controllers;

use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class HomeController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * Allow guests to access the homepage (index),
     * but keep auth middleware for all other methods.
     */
    public function __construct()
    {
        $this->middleware('auth')->except(['index']);
    }

    /**
     * Show the application dashboard / homepage.
     */
    public function index()
    {
        $user = Auth::user(); // Will be null for guests

        // If user is logged in and is admin, redirect to admin dashboard
        if ($user && $user->role === 'admin') {
            return redirect()->route('admin.dashboard');
        }

        // Get hot selling products (using "featured" scope as hot selling)
        $hotProducts = Product::with(['images', 'variations'])
            ->active()
            ->featured()
            ->orderBy('created_at', 'desc')
            ->limit(3)
            ->get();

        // Get new arrivals
        $newArrivals = Product::with(['images', 'variations'])
            ->active()
            ->orderBy('created_at', 'desc')
            ->limit(4)
            ->get();

        // For guests, customers, and other roles, show the normal home page
        return view('homepage', compact('hotProducts', 'newArrivals'));
    }
}
