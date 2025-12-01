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
            ->orderBy('created_at', 'desc') // Removed is_primary ordering
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
            $sessionId = session()->getId();
            
            // Implement your cart retrieval logic here
            $cart = Cart::where('user_id', $user->id)
                ->orWhere('session_id', $sessionId)
                ->first();
                
            return $cart;
        } catch (\Exception $e) {
            \Log::error('Cart creation error: ' . $e->getMessage());
            return null;
        }
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
            'is_primary' => 'sometimes|boolean',
        ]);

        try {
            $user = Auth::user();
            
            // Check if is_primary column exists before using it
            $tableColumns = Schema::getColumnListing('addresses');
            $hasIsPrimaryColumn = in_array('is_primary', $tableColumns);
            
            // If setting as primary and column exists, update existing primary addresses
            if ($request->boolean('is_primary') && $hasIsPrimaryColumn) {
                Address::where('user_id', $user->id)
                    ->where('type', 'shipping')
                    ->where('is_primary', true)
                    ->update(['is_primary' => false]);
            }

            // Create address data array
            $addressData = [
                'user_id' => $user->id,
                'type' => 'shipping',
                'first_name' => $request->first_name,
                'last_name' => $request->last_name,
                'phone' => $request->phone,
                'email' => $request->email,
                'state' => $request->state,
                'city' => $request->city,
                'postcode' => $request->postcode,
                'address' => $request->address,
                'address2' => $request->address2,
            ];

            // Only add is_primary if the column exists
            if ($hasIsPrimaryColumn) {
                $addressData['is_primary'] = $request->boolean('is_primary', false);
            }

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