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
     * Show ONLY past orders: delivered, cancelled, completed
     */
    public function orders()
    {
        $user = Auth::user();

        // Modify status list to match your database
        $statuses = ['delivered', 'completed', 'cancelled'];

        $orders = Order::where('user_id', $user->id)
                        ->whereIn('status', $statuses)
                        ->with('items.product') // load products inside items
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
            'street'     => 'required|string',
            'city'       => 'required|string',
            'state'      => 'required|string',
            'postcode'   => 'required|string',
            'country'    => 'required|string',
            'phone'      => 'required|string|max:20',
            'email'      => 'required|email'
        ]);

        // If set as default, unset previous default
        if ($request->is_default) {
            Address::where('user_id', Auth::id())->update(['is_default' => 0]);
        }

        Address::create([
            'user_id'    => Auth::id(),
            'first_name' => $request->first_name,
            'last_name'  => $request->last_name,
            'company'    => $request->company,
            'street'     => $request->street,
            'city'       => $request->city,
            'state'      => $request->state,
            'postcode'   => $request->postcode,
            'country'    => $request->country,
            'phone'      => $request->phone,
            'email'      => $request->email,
            'is_default' => $request->is_default ? 1 : 0
        ]);

        return back()->with('success', 'Address added successfully!');
    }

    public function updateAddress(Request $request, Address $address)
    {
        // Simple ownership check instead of policy
        if ($address->user_id !== Auth::id()) {
            abort(403, 'You are not allowed to update this address.');
        }

        $request->validate([
            'first_name' => 'required|string|max:150',
            'last_name'  => 'required|string|max:150',
            'street'     => 'required|string',
            'city'       => 'required|string',
            'state'      => 'required|string',
            'postcode'   => 'required|string',
            'country'    => 'required|string',
            'phone'      => 'required|string|max:20',
            'email'      => 'required|email'
        ]);

        // Handle default address switching
        if ($request->is_default) {
            Address::where('user_id', Auth::id())->update(['is_default' => 0]);
            $address->is_default = 1;
        } else {
            $address->is_default = 0;
        }

        $address->update([
            'first_name' => $request->first_name,
            'last_name'  => $request->last_name,
            'company'    => $request->company,
            'street'     => $request->street,
            'city'       => $request->city,
            'state'      => $request->state,
            'postcode'   => $request->postcode,
            'country'    => $request->country,
            'phone'      => $request->phone,
            'email'      => $request->email,
            'is_default' => $address->is_default,
        ]);

        return back()->with('success', 'Address updated successfully!');
    }

    public function deleteAddress(Address $address)
    {
        // Ownership check instead of policy
        if ($address->user_id !== Auth::id()) {
            abort(403, 'You are not allowed to delete this address.');
        }

        $address->delete();

        return back()->with('success', 'Address removed.');
    }

    /**
     * PAYMENT METHOD PAGE
     */
    public function paymentMethods()
    {
        // You’re using Stripe – cards are stored safely with Stripe, not locally.
        return view('profile.editpayment');
    }

    public function storeCard(Request $request)
    {
        $request->validate([
            'holder_name' => 'required|string|max:150',
            'number'      => 'required|string|max:20',
            'expiry'      => 'required|string',
            'cvv'         => 'required|string|max:4'
        ]);

        PaymentCard::create([
            'user_id'     => Auth::id(),
            'holder_name' => $request->holder_name,
            'number'      => substr($request->number, -4), // store only last 4
            'brand'       => $this->detectCardBrand($request->number),
            'exp_month'   => substr($request->expiry, 0, 2),
            'exp_year'    => substr($request->expiry, 3, 2),
        ]);

        return back()->with('success', 'Card added successfully!');
    }

    public function destroyCard(PaymentCard $card)
    {
        $this->authorize('delete', $card);
        $card->delete();

        return back()->with('success', 'Card removed!');
    }

    private function detectCardBrand($number)
    {
        if (str_starts_with($number, '4')) return 'visa';
        if (preg_match('/^(5[1-5])/', $number)) return 'mastercard';
        return 'card';
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
