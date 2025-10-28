<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use Illuminate\Http\Request;

class LoginController extends Controller
{
    use AuthenticatesUsers;

    /**
     * Create a new controller instance.
     */
    public function __construct()
    {
        $this->middleware('guest')->except('logout');
    }

    /**
     * Handle authenticated user redirection based on role
     */
    protected function authenticated(Request $request, $user)
    {
        // Debug info
        \Log::info('Login successful', [
            'user_id' => $user->id,
            'email' => $user->email, 
            'role' => $user->role,
            'is_admin' => $user->role === 'admin'
        ]);

        if ($user->role === 'admin') {
            return redirect()->route('admin.dashboard');
        }
        
        // Redirect customers to home page
        return redirect('/');
    }
}