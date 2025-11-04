<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class CheckoutController extends Controller
{
    public function show()
    {
        // Temporary: Sample cart data for testing without database
        $cartItems = collect([
            (object)[
                'product' => (object)[
                    'id' => 1,
                    'name' => 'Dell XPS 13 Laptop',
                    'specifications' => 'Intel Core i7-1165G7 • 16GB RAM • 512GB SSD',
                    'images' => [
                        (object)['path' => 'images/product1.jpg']
                    ]
                ],
                'quantity' => 1,
                'price' => 3499.00
            ],
            (object)[
                'product' => (object)[
                    'id' => 2,
                    'name' => 'Wireless Mouse',
                    'specifications' => 'Bluetooth 5.0 • 2400 DPI • Ergonomic Design',
                    'images' => [
                        (object)['path' => 'images/product2.jpg']
                    ]
                ],
                'quantity' => 2,
                'price' => 89.00
            ]
        ]);

        // Calculate totals
        $subtotal = $cartItems->sum(function ($item) {
            return $item->quantity * $item->price;
        });

        $shipping = 10.00; // Default shipping
        $tax = $subtotal * 0.06; // 6% tax
        $discount = 0; // Will be calculated based on promo codes
        $total = $subtotal + $shipping + $tax - $discount;

        return view('checkout', compact('cartItems', 'subtotal', 'shipping', 'tax', 'discount', 'total'));
    }

    public function placeOrder(Request $request)
    {
        // Temporary: Always return success for testing without database
        return response()->json([
            'success' => true,
            'order_id' => 1,
            'redirect_url' => route('orders.show', 1)
        ]);
    }

    private function getShippingCost($method)
    {
        return match($method) {
            'express' => 20.00,
            'next_day' => 35.00,
            default => 10.00, // standard
        };
    }

    public function applyPromoCode(Request $request)
    {
        // Temporary: Simple promo code validation for testing
        $promoCode = $request->promo_code;
        $discount = 0;

        // Example promo codes
        if ($promoCode === 'WELCOME10') {
            $discount = 10.00;
        } elseif ($promoCode === 'SAVE20') {
            $discount = 20.00;
        } elseif ($promoCode === 'TEST50') {
            $discount = 50.00;
        } else {
            return response()->json(['success' => false, 'message' => 'Invalid promo code']);
        }

        return response()->json(['success' => true, 'discount' => $discount]);
    }
}