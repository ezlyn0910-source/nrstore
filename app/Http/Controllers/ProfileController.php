<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use App\Models\Order;
use App\Models\Address;

class ProfileController extends Controller
{
    /**
     * My Account Shell Page
     * Banner + Left Menu (empty right panel)
     */
    public function index()
    {
        return redirect()->route('profile.personal.edit');
    }

    /**
     * PERSONAL INFORMATION
     */
    public function editPersonal()
    {
        $user = Auth::user();
        return view('profile.editpersonal', compact('user'));
    }

    public function updatePersonal(Request $request)
    {
        $user = Auth::user();

        $request->validate([
            'first_name' => 'required|string|max:150',
            'last_name'  => 'required|string|max:150',
            'email'      => 'required|email',
            'phone'      => 'required|string|max:20',
            'gender'     => 'nullable|string|max:20',
            'avatar'     => 'nullable|image|max:2048'
        ]);

        // Update basic info
        $user->first_name = $request->first_name;
        $user->last_name  = $request->last_name;
        $user->email      = $request->email;
        $user->phone      = $request->phone;
        $user->gender     = $request->gender;

        // Upload avatar if exists
        if ($request->hasFile('avatar')) {
            $path = $request->file('avatar')->store('avatars', 'public');
            $user->avatar = $path;
        }

        $user->save();

        return back()->with('success', 'Profile updated successfully!');
    }

    /**
     * MY ORDERS (ACCOUNT PAGE)
     * Show ONLY past orders: shipped, cancelled (since no delivered status)
     */
    public function orders()
    {
        $user = Auth::user();

        // Updated to match your Order model statuses
        $statuses = ['shipped', 'cancelled']; // Only shipped and cancelled for past orders

        $orders = Order::where('user_id', $user->id)
                        ->whereIn('status', $statuses)
                        ->with(['orderItems.product', 'orderItems.variation']) // Use correct relationship name
                        ->latest()
                        ->get();

        return view('profile.orders', compact('orders'));
    }

    /**
     * MANAGE ADDRESSES
     */
    public function addresses()
    {
        $addresses = Address::where('user_id', Auth::id())->get();
        return view('profile.editaddress', compact('addresses'));
    }

    public function storeAddress(Request $request)
    {
        $request->validate([
            'first_name' => 'required|string|max:150',
            'last_name'  => 'required|string|max:150',
            'address_line_1' => 'required|string',
            'address_line_2' => 'nullable|string',
            'city'       => 'required|string',
            'state'      => 'required|string',
            'postal_code' => 'required|string',
            'country'    => 'required|string',
            'phone'      => 'required|string|max:20',
            'email'      => 'required|email',
            'type'       => 'required|in:shipping,billing'
        ]);

        // If set as default, unset previous default of the same type
        if ($request->is_default) {
            Address::where('user_id', Auth::id())
                   ->where('type', $request->type)
                   ->update(['is_default' => 0]);
        }

        Address::create([
            'user_id'        => Auth::id(),
            'type'           => $request->type,
            'first_name'     => $request->first_name,
            'last_name'      => $request->last_name,
            'address_line_1' => $request->address_line_1,
            'address_line_2' => $request->address_line_2,
            'city'           => $request->city,
            'state'          => $request->state,
            'postal_code'    => $request->postal_code,
            'country'        => $request->country,
            'phone'          => $request->phone,
            'email'          => $request->email,
            'is_default'     => $request->is_default ? 1 : 0
        ]);

        return back()->with('success', 'Address added successfully!');
    }

    public function updateAddress(Request $request, Address $address)
    {
        // Ownership check
        if ($address->user_id !== Auth::id()) {
            abort(403, 'You are not allowed to update this address.');
        }

        $request->validate([
            'first_name' => 'required|string|max:150',
            'last_name'  => 'required|string|max:150',
            'address_line_1' => 'required|string',
            'address_line_2' => 'nullable|string',
            'city'       => 'required|string',
            'state'      => 'required|string',
            'postal_code' => 'required|string',
            'country'    => 'required|string',
            'phone'      => 'required|string|max:20',
            'email'      => 'required|email'
        ]);

        // Handle default address switching for the same type
        if ($request->is_default) {
            Address::where('user_id', Auth::id())
                   ->where('type', $address->type)
                   ->update(['is_default' => 0]);
            $address->is_default = 1;
        } elseif (!$request->is_default && $address->is_default) {
            // Don't allow unsetting default if it's the only one
            $count = Address::where('user_id', Auth::id())
                           ->where('type', $address->type)
                           ->count();
            if ($count > 1) {
                $address->is_default = 0;
            }
        }

        $address->update([
            'first_name'     => $request->first_name,
            'last_name'      => $request->last_name,
            'address_line_1' => $request->address_line_1,
            'address_line_2' => $request->address_line_2,
            'city'           => $request->city,
            'state'          => $request->state,
            'postal_code'    => $request->postal_code,
            'country'        => $request->country,
            'phone'          => $request->phone,
            'email'          => $request->email,
            'is_default'     => $address->is_default,
        ]);

        return back()->with('success', 'Address updated successfully!');
    }

    public function deleteAddress(Address $address)
    {
        // Ownership check
        if ($address->user_id !== Auth::id()) {
            abort(403, 'You are not allowed to delete this address.');
        }

        // If deleting a default address, set another one as default
        if ($address->is_default) {
            $newDefault = Address::where('user_id', Auth::id())
                                ->where('type', $address->type)
                                ->where('id', '!=', $address->id)
                                ->first();
            if ($newDefault) {
                $newDefault->update(['is_default' => true]);
            }
        }

        $address->delete();

        return back()->with('success', 'Address removed.');
    }

    /**
     * PAYMENT METHOD PAGE
     */
    public function paymentMethods()
    {
        // You're using Stripe – cards are stored safely with Stripe, not locally.
        return view('profile.editpayment');
    }

    /**
     * PASSWORD MANAGER
     */
    public function editPassword()
    {
        return view('profile.changepassword');
    }

    public function updatePassword(Request $request)
    {
        $request->validate([
            'current_password'      => 'required',
            'password'              => 'required|confirmed|min:6'
        ]);

        $user = Auth::user();

        if (!Hash::check($request->current_password, $user->password)) {
            return back()->withErrors(['current_password' => 'Incorrect current password.']);
        }

        $user->password = Hash::make($request->password);
        $user->save();

        return back()->with('success', 'Password updated successfully!');
    }
}