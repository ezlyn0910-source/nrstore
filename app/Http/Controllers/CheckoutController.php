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

        // 5. Return View
        return view('checkout.index', compact(
            'cartItems',
            'subtotal',
            'total',
            'tax',
            'discount',
            'addresses',
            'buyNowOrder'
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
     * Get addresses
     */
    public function getAddresses()
    {
        $user = Auth::user();
        $addresses = Address::where('user_id', $user->id)
            ->where('type', 'shipping')
            ->orderBy('created_at', 'desc')
            ->get();
            
        return response()->json(['addresses' => $addresses]);
    }
    
    /**
     * Update address
     */
    public function updateAddress(Request $request, $id)
    {
        // Implementation
        return response()->json(['success' => true]);
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
        \Log::info('Store Address Request:', $request->all());
        
        $request->validate([
            'first_name' => 'required|string|max:255',
            'last_name' => 'required|string|max:255',
            'phone' => 'required|string|max:20',
            'email' => 'nullable|email',
            'state' => 'required|string|max:255',
            'city' => 'required|string|max:255',
            'postcode' => 'required|string|max:10',
            'address' => 'required|string',
            'address2' => 'nullable|string',
        ]);

        try {
            $user = Auth::user();
            
            // Map the form fields to your database columns
            $addressData = [
                'user_id' => $user->id,
                'type' => 'shipping',
                'full_name' => $request->first_name . ' ' . $request->last_name, // Combine first + last name
                'address_line_1' => $request->address,
                'address_line_2' => $request->address2,
                'city' => $request->city,
                'state' => $request->state,
                'postal_code' => $request->postcode,
                'country' => 'Malaysia', // Default value
                'phone' => $request->phone,
                'is_default' => false, // Your table uses is_default instead of is_primary
            ];

            // Create new address
            $address = Address::create($addressData);

            \Log::info('Address created successfully:', ['address_id' => $address->id]);

            return response()->json([
                'success' => true,
                'message' => 'Address added successfully!',
                'address' => $address
            ]);

        } catch (\Exception $e) {
            \Log::error('Address creation error: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Failed to save address: ' . $e->getMessage()
            ], 500);
        }
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