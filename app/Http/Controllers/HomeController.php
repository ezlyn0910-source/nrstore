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
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Show the application dashboard.
     */
    public function index()
    {
        $user = Auth::user();

        // Check if user is admin and redirect to admin dashboard
        if ($user->role === 'admin') {
            return redirect()->route('admin.dashboard');
        }

        // Get hot selling products (you can define your own logic for "hot selling")
        $hotProducts = Product::with(['images', 'variations'])
            ->active()
            ->featured() // Using featured as hot selling for now
            ->orderBy('created_at', 'desc')
            ->limit(3)
            ->get();

        // Get new arrivals
        $newArrivals = Product::with(['images', 'variations'])
            ->active()
            ->orderBy('created_at', 'desc')
            ->limit(4)
            ->get();
        
        // For customers and other roles, show the normal home page
        return view('homepage', compact('hotProducts', 'newArrivals'));
    }
}
