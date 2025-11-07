<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class ReviewController extends Controller
{
    public function store(Request $request)
    {
        // Temporary: Always return success for testing without database
        return response()->json([
            'success' => true, 
            'message' => 'Review submitted successfully (test mode)'
        ]);
    }
}