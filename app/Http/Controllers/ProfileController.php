<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use App\Models\Order;
use App\Models\Address;

class ProfileController extends Controller
{
    public function index()
    {
        return redirect()->route('profile.personal.edit');
    }

    /** PERSONAL INFORMATION **/
    public function editPersonal()
    {
        $user = Auth::user();

        $first = trim((string) $user->first_name);
        $last  = trim((string) $user->last_name);

        if ($first === '' && $last === '') {
            $full = trim((string) $user->name);

            if ($full !== '') {
                $parts = preg_split('/\s+/', $full, -1, PREG_SPLIT_NO_EMPTY);
                $user->first_name = $parts[0] ?? '';
                $user->last_name  = count($parts) > 1 ? implode(' ', array_slice($parts, 1)) : '';
            }
        }

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
            'birthday'   => 'nullable|date',
            'avatar'     => 'nullable|image|max:2048'
        ]);

        $user->first_name = $request->first_name;
        $user->last_name  = $request->last_name;
        $user->name       = trim($request->first_name . ' ' . $request->last_name);
        $user->email      = $request->email;
        $user->phone      = $request->phone;
        $user->birthday   = $request->birthday;

        if ($request->hasFile('avatar')) {
            $path = $request->file('avatar')->store('avatars', 'public');
            $user->avatar = $path;
        }

        $user->save();

        return back()->with('success', 'Profile updated successfully!');
    }

    /** MANAGE ADDRESSES **/
    public function addresses()
    {
        $user = Auth::user();

        $dialMap = [
            'Malaysia' => '+60',
            'Singapore' => '+65',
            'Indonesia' => '+62',
            'Thailand' => '+66',
            'Philippines' => '+63',
            'Vietnam' => '+84',
            'China' => '+86',
            'India' => '+91',
            'Pakistan' => '+92',
            'Bangladesh' => '+880',
            'United Arab Emirates' => '+971',
            'United States' => '+1',
            'Canada' => '+1',
            'United Kingdom' => '+44',
            'Australia' => '+61',
        ];

        $addresses = Address::where('user_id', Auth::id())->get();

        foreach ($addresses as $addr) {
            $addr->display_email = $addr->email ?: ($user->email ?: '');

            $dial = $addr->country_code ?: ($dialMap[$addr->country] ?? '');

            $rawPhone = (string) ($addr->phone ?? '');
            $rawPhone = preg_replace('/\s+/', '', $rawPhone);

            if (str_starts_with($rawPhone, '+')) {
                $digits = preg_replace('/\D+/', '', $rawPhone);

                $allDials = array_values($dialMap);
                usort($allDials, fn($a, $b) => strlen($b) <=> strlen($a));

                foreach ($allDials as $d) {
                    $dDigits = ltrim($d, '+');
                    if (str_starts_with($digits, $dDigits)) {
                        $dial = $d;
                        $rawPhone = substr($digits, strlen($dDigits));
                        break;
                    }
                }
            }

            if (($addr->country === 'Malaysia') && preg_match('/^0\d+$/', $rawPhone)) {
                $rawPhone = ltrim($rawPhone, '0');
            }

            if ($dial && preg_match('/^0\d+$/', $rawPhone)) {
                $rawPhone = preg_replace('/^0/', '', $rawPhone, 1);
            }

            $addr->display_country_code = $dial;
            $addr->display_phone = $rawPhone;
        }

        return view('profile.editaddress', compact('addresses', 'user'));
    }

    public function storeAddress(Request $request)
    {
        $request->validate([
            'first_name'     => 'required|string|max:150',
            'last_name'      => 'required|string|max:150',
            'address_line_1' => 'required|string',
            'address_line_2' => 'nullable|string',
            'city'           => 'required|string',
            'state'          => 'required|string',
            'postal_code'    => 'required|string',
            'country'        => 'required|string',
            'country_code'   => 'required|string|max:10',
            'phone' => ['required', 'string', 'max:20', 'regex:/^[0-9\s\-()]+$/'],
            'email'          => 'required|email',
            'type'           => 'required|in:shipping,billing'
        ], [
            'phone.regex' => 'Phone number is invalid.',
        ]);

        if ($request->is_default) {
            Address::where('user_id', Auth::id())
                   ->where('type', $request->type)
                   ->update(['is_default' => 0]);
        }

        $dial = $request->country_code ?: null;
        $phone = preg_replace('/\s+/', '', (string) $request->phone);

        if (str_starts_with($phone, '+')) {
            $digits = preg_replace('/\D+/', '', $phone);

            if (!$dial) {
                $known = ['+880','+971','+92','+91','+86','+84','+66','+65','+63','+62','+61','+60','+44','+1'];
                foreach ($known as $k) {
                    $kDigits = ltrim($k, '+');
                    if (str_starts_with($digits, $kDigits)) {
                        $dial = $k;
                        $digits = substr($digits, strlen($kDigits));
                        break;
                    }
                }
            }
            $phone = $digits;
        }

        if ($dial && preg_match('/^0\d+$/', $phone)) {
            $phone = preg_replace('/^0/', '', $phone, 1);
        }

        if ($request->country === 'Malaysia' && preg_match('/^0\d+$/', $phone)) {
            $phone = ltrim($phone, '0');
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
            'country_code'   => $dial,
            'phone'          => $phone,
            'email'          => $request->email,
            'is_default'     => $request->is_default ? 1 : 0
        ]);

        return back()->with('success', 'Address added successfully!');
    }

    public function updateAddress(Request $request, Address $address)
    {
        if ((int) $address->user_id !== (int) Auth::id()) {
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
            'country_code'   => 'required|string|max:10',
            'phone' => ['required', 'string', 'max:20', 'regex:/^[0-9\s\-()]+$/'],
            'email'      => 'required|email'
        ], [
            'phone.regex' => 'Phone number is invalid.',
        ]);

        if ($request->is_default) {
            Address::where('user_id', Auth::id())
                   ->where('type', $address->type)
                   ->update(['is_default' => 0]);
            $address->is_default = 1;
        } elseif (!$request->is_default && $address->is_default) {
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
            'country_code'   => $request->country_code,
            'phone'          => $request->phone,
            'email'          => $request->email,
            'is_default'     => $address->is_default,
        ]);

        return back()->with('success', 'Address updated successfully!');
    }

    public function deleteAddress(Address $address)
    {
        if ((int) $address->user_id !== (int) Auth::id()) {
            abort(403, 'You are not allowed to delete this address.');
        }

        $total = Address::where('user_id', Auth::id())->count();
        if ($total <= 1) {
            return back()->with('address_delete_error', "Address can't be empty. Try again");
        }

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

    /** PAYMENT METHOD PAGE **/
    public function paymentMethods()
    {
        return view('profile.editpayment');
    }

    /** PASSWORD MANAGER **/
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