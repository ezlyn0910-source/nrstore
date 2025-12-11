<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\TempUser;
use App\Models\User;
use Illuminate\Foundation\Auth\RegistersUsers;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Http\Request;
use Illuminate\Auth\Events\Registered;
use App\Notifications\VerifyRegistrationEmail;

class RegisterController extends Controller
{
    use RegistersUsers;

    /**
     * Where to redirect users after TEMPORARY registration.
     */
    protected $redirectTo = '/register/success';

    /**
     * Create a new controller instance.
     */
    public function __construct()
    {
        $this->middleware('guest');
    }

    /**
     * Get a validator for an incoming registration request.
     */
    protected function validator(array $data)
    {
        return Validator::make($data, [
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users,email', 'unique:temp_users,email'],
            'password' => ['required', 'string', 'min:8', 'confirmed'],
            'phone' => ['required', 'string', 'max:20'],
        ]);
    }

    /**
     * Handle a registration request for the application.
     */
    public function register(Request $request)
    {
        $this->validator($request->all())->validate();

        // Store in temp table instead of creating real user
        $tempUser = $this->createTempUser($request->all());

        // Send verification email
        $tempUser->notify(new VerifyRegistrationEmail($tempUser));

        // Store email in session for display on success page
        session(['registered_email' => $tempUser->email]);

        // Fix: Use redirect()->route() instead of redirect($this->redirectPath())
        return redirect()->route('register.success')  // ← CHANGE THIS LINE
            ->with('success', 'Registration successful! Please check your email to verify your account.');
    }

    /**
     * Show registration success page.
     */
    public function showSuccessPage()
    {
        // Check if user just registered (has email in session)
        if (!session()->has('registered_email')) {
            return redirect()->route('register');
        }
        
        return view('auth.register-success');
    }

    /**
     * Verify email and create real user.
     */
    public function verifyEmail($token)
    {
        $tempUser = TempUser::where('token', $token)->first();

        if (!$tempUser) {
            return redirect('/register')
                ->with('error', 'Invalid verification link.');
        }

        if ($tempUser->isExpired()) {
            $tempUser->delete();
            return redirect('/register')
                ->with('error', 'Verification link has expired. Please register again.');
        }

        // Create real user
        $user = User::create([
            'name' => $tempUser->name,
            'email' => $tempUser->email,
            'phone' => $tempUser->phone,
            'password' => $tempUser->password,
            'status' => 'active',
            'role' => 'customer',
            'email_verified_at' => now(), // Mark as verified
        ]);

        // Delete temp user
        $tempUser->delete();

        // Log the user in
        auth()->login($user);

        // Send welcome email if needed
        // event(new Registered($user));

        return redirect('/')
            ->with('success', 'Email verified successfully! Your account has been created.');
    }

    /**
     * Resend verification email.
     */
    public function resendVerification(Request $request)
    {
        $request->validate(['email' => 'required|email']);

        $tempUser = TempUser::where('email', $request->email)->first();

        if (!$tempUser) {
            return back()->with('error', 'Email not found in pending registrations.');
        }

        if ($tempUser->isExpired()) {
            $tempUser->delete();
            return redirect('/register')
                ->with('error', 'Registration expired. Please register again.');
        }

        // Send new verification email
        $tempUser->notify(new VerifyRegistrationEmail($tempUser));

        return back()->with('success', 'Verification email sent again!');
    }
}