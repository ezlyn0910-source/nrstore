<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules;

class ProfileController extends Controller
{
    public function edit()
    {
        $user = Auth::user();
        return view('profile.edit', compact('user'));
    }

    public function update(Request $request)
    {
        $user = Auth::user();
        
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users,email,' . $user->id],
            'phone' => ['nullable', 'string', 'max:20'],
        ]);

        $user->update($validated);

        return redirect()->route('profile.edit')->with('success', 'Profile updated successfully.');
    }

    public function updatePassword(Request $request)
    {
        $request->validate([
            'current_password' => ['required', 'current_password'],
            'password' => ['required', 'confirmed', Rules\Password::defaults()],
        ]);

        $user = Auth::user();
        $user->update([
            'password' => Hash::make($request->password),
        ]);

        return redirect()->route('profile.edit')->with('success', 'Password updated successfully.');
    }

    public function orders()
    {
        $orders = Auth::user()->orders()->latest()->paginate(10);
        return view('profile.orders', compact('orders'));
    }

    public function addresses()
    {
        $addresses = Auth::user()->addresses;
        return view('profile.addresses', compact('addresses'));
    }

    public function storeAddress(Request $request)
    {
        $validated = $request->validate([
            'street' => ['required', 'string', 'max:255'],
            'city' => ['required', 'string', 'max:255'],
            'state' => ['required', 'string', 'max:255'],
            'postal_code' => ['required', 'string', 'max:20'],
            'country' => ['required', 'string', 'max:255'],
            'type' => ['required', 'in:shipping,billing'],
            'is_default' => ['boolean'],
        ]);

        Auth::user()->addresses()->create($validated);

        return redirect()->route('profile.addresses')->with('success', 'Address added successfully.');
    }

    public function updateAddress(Request $request, $id)
    {
        $address = Auth::user()->addresses()->findOrFail($id);
        
        $validated = $request->validate([
            'street' => ['required', 'string', 'max:255'],
            'city' => ['required', 'string', 'max:255'],
            'state' => ['required', 'string', 'max:255'],
            'postal_code' => ['required', 'string', 'max:20'],
            'country' => ['required', 'string', 'max:255'],
            'type' => ['required', 'in:shipping,billing'],
            'is_default' => ['boolean'],
        ]);

        $address->update($validated);

        return redirect()->route('profile.addresses')->with('success', 'Address updated successfully.');
    }

    public function deleteAddress($id)
    {
        $address = Auth::user()->addresses()->findOrFail($id);
        $address->delete();

        return redirect()->route('profile.addresses')->with('success', 'Address deleted successfully.');
    }
}