<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Address;
use App\Models\Cart;
use App\Models\CartItem;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Schema;

class CheckoutController extends Controller
{
    /**
     * Display the checkout page
     */
    public function index()
    {
        $user = Auth::user();
        
        if (!$user) {
            return redirect()->route('login')->with('error', 'Please log in to checkout.');
        }

        // 1. Initialize variables
        $cartItems = collect([]);
        $subtotal = 0;
        $total = 0;
        $tax = 0;
        $discount = 0;

        // 2. Check for Buy Now Session Data first
        $buyNowOrder = session('buy_now_order');
        
        if ($buyNowOrder && isset($buyNowOrder['is_buy_now']) && $buyNowOrder['is_buy_now']) {
            // LOAD BUY NOW DATA
            $cartItems = collect($buyNowOrder['items']);
            $subtotal = $buyNowOrder['total'];
            $total = $subtotal;
        } else {
            // 3. Fallback to Database Cart
            $cart = $this->getOrCreateCart();
            
            if (!$cart || $cart->items->isEmpty()) {
                return redirect()->route('cart.index')->with('error', 'Your cart is empty.');
            }

            $cartItems = $cart->items()->with(['product.images', 'variation'])->get();
            $subtotal = $cart->total_amount;
            $total = $subtotal;
        }

        // 4. Get Addresses - FIXED QUERY
        $addresses = Address::where('user_id', $user->id)
        ->where('type', 'shipping')
        ->orderBy('created_at', 'desc')
        ->get();

        // 5. Define banks for online banking
        $banks = [
            ['img' => 'maybank.png', 'name' => 'Maybank'],
            ['img' => 'cimb.png', 'name' => 'CIMB Bank'],
            ['img' => 'public-bank.png', 'name' => 'Public Bank'],
            ['img' => 'rhb.png', 'name' => 'RHB Bank'],
            ['img' => 'hong-leong.png', 'name' => 'Hong Leong Bank'],
            ['img' => 'bank-islam.png', 'name' => 'Bank Islam'],
            ['img' => 'ambank.png', 'name' => 'AmBank'],
            ['img' => 'bank-rakyat.png', 'name' => 'Bank Rakyat'],
            ['img' => 'hsbc.png', 'name' => 'HSBC'],
            ['img' => 'ocbc.png', 'name' => 'OCBC'],
            ['img' => 'uob.png', 'name' => 'UOB'],
            ['img' => 'standard-chartered.png', 'name' => 'Standard Chartered'],
        ];

        // 6. Return View with all variables
        return view('checkout.index', compact(
            'cartItems',
            'subtotal',
            'total',
            'tax',
            'discount',
            'addresses',
            'buyNowOrder',
            'banks' // Add banks to the compact function
        ));
    }

    /**
     * Get or create cart for authenticated user
     */
    private function getOrCreateCart()
    {
        try {
            $user = Auth::user();
            
            // Simple cart retrieval without complex logic
            $cart = Cart::where('user_id', $user->id)->first();
            
            if (!$cart) {
                // Create new cart if doesn't exist
                $cart = Cart::create([
                    'user_id' => $user->id,
                    'session_id' => session()->getId(),
                    'total_amount' => 0
                ]);
            }
            
            return $cart;
        } catch (\Exception $e) {
            \Log::error('Cart creation error: ' . $e->getMessage());
            return null;
        }
    }

    /**
     * Display checkout review page
     */
    public function review()
    {
        return view('checkout.review');
    }
    
    /**
     * Validate checkout data
     */
    public function validateCheckout(Request $request)
    {
        return response()->json(['valid' => true]);
    }
    
    /**
     * Calculate shipping
     */
    public function calculateShipping(Request $request)
    {
        return response()->json(['shipping' => 5.99]);
    }
    
    /**
     * Remove promo code
     */
    public function removePromoCode(Request $request)
    {
        return response()->json(['success' => true]);
    }
    
    /**
     * Get shipping methods
     */
    public function getShippingMethods()
    {
        return response()->json([
            ['id' => 'standard', 'name' => 'Standard Shipping', 'price' => 5.99, 'days' => '3-5'],
            ['id' => 'express', 'name' => 'Express Shipping', 'price' => 12.99, 'days' => '1-2']
        ]);
    }
    
    /**
     * Get payment methods
     */
    public function getPaymentMethods()
    {
        return response()->json([
            ['id' => 'online_banking', 'name' => 'Online Banking'],
            ['id' => 'credit_card', 'name' => 'Credit Card'],
            ['id' => 'tng_ewallet', 'name' => 'Touch n Go eWallet']
        ]);
    }
    
    /**
     * Verify stock
     */
    public function verifyStock()
    {
        return response()->json(['in_stock' => true]);
    }
    
    /**
     * Update shipping address
     */
    public function updateAddress(Request $request, $id)
    {
        try {
            $user = Auth::user();

            // Make sure the address belongs to this user and is a shipping address
            $address = Address::where('id', $id)
                ->where('user_id', $user->id)
                ->where('type', 'shipping')
                ->firstOrFail();

            // Same validation rules as storeAddress
            $request->validate([
                'first_name'       => 'required|string|max:100',
                'last_name'        => 'required|string|max:100',
                'phone'           => 'required|string|max:20',
                'address_line_1'  => 'required|string|max:255',
                'address_line_2'  => 'nullable|string|max:255',
                'city'            => 'required|string|max:100',
                'state'           => 'required|string|max:100',
                'postal_code'     => 'required|string|max:10',
                'country'         => 'required|string|max:100',
                'is_default'      => 'nullable|boolean',
            ]);

            // Do we want to make THIS address the default?
            $makeDefault = $request->boolean('is_default');

            \DB::beginTransaction();

            if ($makeDefault) {
                // Clear default on all other shipping addresses for this user
                Address::where('user_id', $user->id)
                    ->where('type', 'shipping')
                    ->where('id', '!=', $address->id)
                    ->update(['is_default' => false]);
            }

            // Update basic fields
            $address->first_name      = $request->first_name;
            $address->last_name       = $request->last_name;
            $address->phone           = $request->phone;
            $address->address_line_1  = $request->address_line_1;
            $address->address_line_2  = $request->address_line_2;
            $address->city            = $request->city;
            $address->state           = $request->state;
            $address->postal_code     = $request->postal_code;
            $address->country         = $request->country ?? 'Malaysia';

            // Default logic:
            // - If user ticked the box, make this the default
            // - If they didn't tick it, keep whatever it was before (so you don't accidentally end up with no default)
            if ($makeDefault) {
                $address->is_default = true;
            }

            $address->save();

            \DB::commit();

            return response()->json([
                'success' => true,
                'message' => 'Address updated successfully!',
                'address' => $address,
            ]);

        } catch (\Illuminate\Validation\ValidationException $e) {
            // Let Laravel handle and return proper 422 JSON
            throw $e;
        } catch (\Exception $e) {
            \DB::rollBack();
            \Log::error('Address update error: ' . $e->getMessage(), [
                'address_id' => $id,
                'user_id'    => optional(Auth::user())->id,
            ]);

            return response()->json([
                'success' => false,
                'message' => 'Failed to update address.',
            ], 500);
        }
    }
    
    /**
     * Delete address
     */
    public function deleteAddress($id)
    {
        // Implementation
        return response()->json(['success' => true]);
    }

    /**
     * Store a new address
     */
    public function storeAddress(Request $request)
    {
        try {
            $user = Auth::user();
            
            \Log::info('Store Address Request:', [
                'user_id' => $user->id,
                'data' => $request->all(),
                'headers' => $request->headers->all()
            ]);
            
            $request->validate([
                'first_name' => 'required|string|max:100',
                'last_name' => 'required|string|max:100',
                'phone' => 'required|string|max:20',
                'address_line_1' => 'required|string|max:255',
                'address_line_2' => 'nullable|string|max:255',
                'city' => 'required|string|max:100',
                'state' => 'required|string|max:100',
                'postal_code' => 'required|string|max:10',
                'country' => 'required|string|max:100',
                'is_default' => 'nullable|boolean'
            ]);

            \Log::info('Validation passed');
            
            // If setting as default, update other addresses
            if ($request->boolean('is_default')) {
                Address::where('user_id', $user->id)
                    ->where('type', 'shipping')
                    ->update(['is_default' => false]);
            }
            
            // Check if this is the first address
            $isFirstAddress = !Address::where('user_id', $user->id)
                ->where('type', 'shipping')
                ->exists();
            
            $address = Address::create([
                'user_id' => $user->id,
                'type' => 'shipping',
                'first_name' => $request->first_name,
                'last_name' => $request->last_name,
                'phone' => $request->phone,
                'address_line_1' => $request->address_line_1,
                'address_line_2' => $request->address_line_2,
                'city' => $request->city,
                'state' => $request->state,
                'postal_code' => $request->postal_code,
                'country' => $request->country ?? 'Malaysia',
                'is_default' => $isFirstAddress || $request->boolean('is_default')
            ]);

            \Log::info('Address created:', ['address_id' => $address->id]);

            return response()->json([
                'success' => true,
                'message' => 'Address saved successfully!',
                'address' => $address
            ]);

        } catch (\Exception $e) {
            \Log::error('Address save error: ' . $e->getMessage());
            \Log::error('Error trace: ' . $e->getTraceAsString());
            
            return response()->json([
                'success' => false,
                'message' => 'Failed to save address: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Get user addresses
     */
    public function getAddresses()
    {
        try {
            $user = Auth::user();
            $addresses = Address::where('user_id', $user->id)
                ->where('type', 'shipping')
                ->orderBy('is_default', 'desc')
                ->orderBy('created_at', 'desc')
                ->get();
                
            return response()->json([
                'success' => true,
                'addresses' => $addresses
            ]);
        } catch (\Exception $e) {
            \Log::error('Error getting addresses: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Failed to load addresses'
            ], 500);
        }
    }

    /**
     * Save address (alias for storeAddress for backward compatibility)
     */
    public function saveAddress(Request $request)
    {
        return $this->storeAddress($request);
    }

    /**
     * Display success page
     */
    public function success(Request $request)
    {
        return view('checkout.success');
    }

    /**
     * Display failed order page
     */
    public function failed()
    {
        return view('checkout.failed');
    }

    /**
     * Place order
     */
    public function placeOrder(Request $request)
    {
        $request->validate([
            'address_id' => 'required|exists:addresses,id',
            'payment_method' => 'required|in:tng_ewallet,online_banking,credit_card',
        ]);

        try {
            $user = Auth::user();
            
            // Verify the address belongs to the user
            $address = Address::where('id', $request->address_id)
                ->where('user_id', $user->id)
                ->firstOrFail();

            return response()->json([
                'success' => true,
                'message' => 'Order placed successfully!',
                'redirect_url' => route('order.confirmation')
            ]);

        } catch (\Exception $e) {
            \Log::error('Order placement error: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Failed to place order. Please try again.'
            ], 500);
        }
    }

    /**
     * Apply promo code
     */
    public function applyPromo(Request $request)
    {
        $request->validate([
            'promo_code' => 'required|string'
        ]);

        return response()->json([
            'success' => false,
            'message' => 'Invalid promo code'
        ]);
    }
}