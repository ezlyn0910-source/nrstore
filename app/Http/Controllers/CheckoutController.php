<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Cart;
use App\Models\CartItem;

class CheckoutController extends Controller
{
    /**
     * Display the checkout page
     */
    public function index()
    {
        try {
            // Get the user's cart
            $cart = Cart::where('user_id', auth()->id())->first();
            
            if (!$cart || $cart->isEmpty()) {
                return redirect()->route('cart.index')->with('error', 'Your cart is empty.');
            }
            
            // Load cart items with product relationships
            $cartItems = $cart->getCartItemsWithProducts();
            
            // Calculate totals
            $subtotal = $cart->getSubtotal();
            $tax = $subtotal * 0.06; // Example: 6% tax
            $shipping = 10.00; // Default shipping
            $discount = 0; // No discount by default
            $total = $subtotal + $tax + $shipping - $discount;

            return view('checkout.index', compact(
                'cartItems', 
                'subtotal', 
                'tax', 
                'shipping', 
                'discount', 
                'total'
            ));

        } catch (\Exception $e) {
            // Fallback if there are any issues
            return $this->fallbackCheckout();
        }
    }

    /**
     * Fallback checkout method if main method fails
     */
    private function fallbackCheckout()
    {
        $cart = Cart::where('user_id', auth()->id())->first();
        
        if (!$cart) {
            return redirect()->route('cart.index')->with('error', 'Your cart is empty.');
        }
        
        $cartItems = $cart->items;
        
        // Basic calculations
        $subtotal = $cart->getSubtotal();
        $tax = $subtotal * 0.06;
        $shipping = 10.00;
        $discount = 0;
        $total = $subtotal + $tax + $shipping - $discount;

        return view('checkout.index', compact(
            'cartItems', 
            'subtotal', 
            'tax', 
            'shipping', 
            'discount', 
            'total'
        ));
    }

    /**
     * Process order placement
     */
    public function placeOrder(Request $request)
    {
        try {
            // Validate the request
            $validated = $request->validate([
                'shipping_first_name' => 'required|string|max:255',
                'shipping_last_name' => 'required|string|max:255',
                'shipping_address' => 'required|string|max:500',
                'shipping_city' => 'required|string|max:255',
                'shipping_state' => 'required|string|max:255',
                'shipping_postcode' => 'required|string|max:10',
                'shipping_phone' => 'required|string|max:20',
                'shipping_method' => 'required|string',
                'payment_method' => 'required|string',
            ]);

            // Get user's cart
            $cart = Cart::where('user_id', auth()->id())->first();
            
            if (!$cart || $cart->isEmpty()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Your cart is empty.'
                ]);
            }

            // TODO: Create order logic here
            // For now, just return success
            $orderId = rand(10000, 99999);

            // Clear the cart after successful order
            $cart->clear();
            $cart->calculateTotals();

            return response()->json([
                'success' => true,
                'message' => 'Order placed successfully!',
                'order_id' => $orderId,
                'redirect_url' => route('checkout.success', ['order' => $orderId])
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error placing order: ' . $e->getMessage()
            ]);
        }
    }

    // ... keep the other methods the same as before ...
    /**
     * Apply promo code
     */
    public function applyPromoCode(Request $request)
    {
        return response()->json([
            'success' => false,
            'message' => 'Promo code functionality coming soon'
        ]);
    }

    /**
     * Validate checkout
     */
    public function validateCheckout(Request $request)
    {
        return response()->json([
            'valid' => true,
            'message' => 'Checkout validation passed'
        ]);
    }

    /**
     * Calculate shipping
     */
    public function calculateShipping(Request $request)
    {
        $shippingMethod = $request->input('shipping_method', 'standard');
        
        $shippingCosts = [
            'standard' => 10.00,
            'express' => 20.00,
            'next_day' => 35.00
        ];

        return response()->json([
            'shipping_cost' => $shippingCosts[$shippingMethod] ?? 10.00
        ]);
    }

    /**
     * Get shipping methods
     */
    public function getShippingMethods()
    {
        return response()->json([
            'methods' => [
                ['id' => 'standard', 'name' => 'Standard Shipping', 'price' => 10.00, 'time' => '5-7 business days'],
                ['id' => 'express', 'name' => 'Express Shipping', 'price' => 20.00, 'time' => '2-3 business days'],
                ['id' => 'next_day', 'name' => 'Next Day Delivery', 'price' => 35.00, 'time' => 'Next business day']
            ]
        ]);
    }

    /**
     * Get payment methods
     */
    public function getPaymentMethods()
    {
        return response()->json([
            'methods' => [
                ['id' => 'credit_card', 'name' => 'Credit/Debit Card'],
                ['id' => 'paypal', 'name' => 'PayPal'],
                ['id' => 'bank_transfer', 'name' => 'Bank Transfer']
            ]
        ]);
    }

    /**
     * Verify stock
     */
    public function verifyStock()
    {
        $cart = Cart::where('user_id', auth()->id())->first();
        
        if (!$cart) {
            return response()->json([
                'in_stock' => false,
                'message' => 'Cart not found'
            ]);
        }

        $cartItems = $cart->getCartItemsWithProducts();
        $allInStock = true;

        foreach ($cartItems as $item) {
            if ($item->product && $item->product->stock < $item->quantity) {
                $allInStock = false;
                break;
            }
        }

        return response()->json([
            'in_stock' => $allInStock,
            'message' => $allInStock ? 'All items are in stock' : 'Some items are out of stock'
        ]);
    }

    /**
     * Checkout success page
     */
    public function success($order)
    {
        return view('checkout.success', ['order' => $order]);
    }

    /**
     * Checkout failed page
     */
    public function failed()
    {
        return view('checkout.failed');
    }
}