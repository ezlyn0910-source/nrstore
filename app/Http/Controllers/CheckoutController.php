<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Address;
use App\Models\Cart;
use App\Models\CartItem;
use Illuminate\Support\Facades\Auth;

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

        // 4. Get Addresses
        $addresses = Address::getShippingAddresses($user->id);

        // 5. Return View (ONLY ONCE)
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
            
            return Cart::getCart($user, $sessionId);
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
            'is_primary' => 'boolean',
        ]);

        try {
            $user = Auth::user();
            
            // Create shipping address using the model method
            $address = Address::createShippingAddress([
                'user_id' => $user->id,
                'first_name' => $request->first_name,
                'last_name' => $request->last_name,
                'phone' => $request->phone,
                'email' => $request->email,
                'state' => $request->state,
                'city' => $request->city,
                'postcode' => $request->postcode,
                'address' => $request->address,
                'address2' => $request->address2,
                'is_primary' => $request->boolean('is_primary'),
            ]);

            return response()->json([
                'success' => true,
                'message' => 'Address added successfully!',
                'address' => $address
            ]);

        } catch (\Exception $e) {
            \Log::error('Address creation error: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Failed to save address. Please try again.'
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
            'shipping_method' => 'required|in:standard,express,next_day',
            'payment_method' => 'required|in:credit_card,paypal,bank_transfer',
            'same_billing' => 'boolean',
        ]);

        try {
            $user = Auth::user();
            
            // Get the selected address
            $address = Address::findOrFail($request->address_id);
            
            // Get the cart
            $cart = $this->getOrCreateCart();
            
            if (!$cart) {
                return response()->json([
                    'success' => false,
                    'message' => 'Cart not found.'
                ], 404);
            }

            // Here you would typically:
            // 1. Create an order record
            // 2. Process payment
            // 3. Clear the cart
            // 4. Send confirmation email
            
            // For now, we'll just return a success response
            // You'll need to implement your actual order creation logic here

            return response()->json([
                'success' => true,
                'message' => 'Order placed successfully!',
                'redirect_url' => route('order.confirmation') // You'll need to create this route
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

        // Implement your promo code logic here
        // For now, returning a dummy response
        return response()->json([
            'success' => false,
            'message' => 'Invalid promo code'
        ]);
    }
}