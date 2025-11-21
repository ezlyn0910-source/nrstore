<?php

namespace App\Http\Controllers;

use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class FavoriteController extends Controller
{
    public function index()
    {
        $favorites = Auth::user()->favorites()->with('brand')->get();
        
        return view('favorites.index', compact('favorites'));
    }
    
    public function add(Request $request)
    {
        $request->validate([
            'product_id' => 'required|exists:products,id'
        ]);
        
        Auth::user()->favorites()->attach($request->product_id);
        
        return response()->json(['success' => true, 'message' => 'Product added to favorites']);
    }
    
    public function remove(Request $request)
    {
        $request->validate([
            'product_id' => 'required|exists:products,id'
        ]);
        
        Auth::user()->favorites()->detach($request->product_id);
        
        return response()->json(['success' => true, 'message' => 'Product removed from favorites']);
    }
}