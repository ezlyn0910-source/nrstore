<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Cart;
use App\Models\CartItem;
use App\Models\Product;
use Illuminate\Support\Facades\Auth;

class CartController extends Controller
{
    /**
     * Display the cart page
     */
    public function index()
    {
        $cart = $this->getOrCreateCart();
        
        if (!$cart) {
            $cartItems = collect();
            $subtotal = 0;
            $total = 0;
        } else {
            $cartItems = $cart->items()->with(['product.images', 'variation'])->get();
            $cart->calculateTotals();
            $subtotal = $cart->total_amount;
            $total = $subtotal;
        }

        return view('cart.index', compact('cartItems', 'subtotal', 'total', 'cart'));
    }

    /**
     * Add item to cart
     */
    public function add(Request $request)
    {
        \Log::info('Cart Add Request:', $request->all());
        
        try {
            $request->validate([
                'product_id' => 'required|exists:products,id',
                'quantity' => 'required|integer|min:1',
            ]);

            $cart = $this->getOrCreateCart();
            
            if (!$cart) {
                \Log::error('Cart creation failed');
                return response()->json([
                    'success' => false,
                    'message' => 'Unable to create cart. Please try again.'
                ], 500);
            }

            $product = Product::with('images')->findOrFail($request->product_id);

            // Check if item already exists in cart
            $existingItem = $cart->items()
                ->where('product_id', $request->product_id)
                ->first();

            if ($existingItem) {
                $existingItem->update([
                    'quantity' => $existingItem->quantity + $request->quantity
                ]);
                \Log::info('Updated existing cart item', ['item_id' => $existingItem->id]);
            } else {
                $cartItem = CartItem::create([
                    'cart_id' => $cart->id,
                    'product_id' => $request->product_id,
                    'quantity' => $request->quantity,
                    'price' => $product->price,
                ]);
                \Log::info('Created new cart item', ['item_id' => $cartItem->id]);
            }

            // Recalculate cart totals
            $cart->calculateTotals();
            $cart->refresh();

            \Log::info('Cart after add:', [
                'cart_id' => $cart->id,
                'item_count' => $cart->item_count,
                'total_amount' => $cart->total_amount
            ]);

            return response()->json([
                'success' => true,
                'message' => 'Product added to cart successfully',
                'cart_count' => $cart->item_count,
                'cart_total' => 'RM ' . number_format($cart->total_amount, 2)
            ]);

        } catch (\Exception $e) {
            \Log::error('Cart Add Error: ' . $e->getMessage());
            \Log::error('Error Stack: ' . $e->getTraceAsString());
            
            return response()->json([
                'success' => false,
                'message' => 'Error adding product to cart: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Update cart item quantity
     */
    public function update(Request $request, $id)
    {
        $request->validate([
            'quantity' => 'required|integer|min:1'
        ]);

        $cart = $this->getCart();
        
        if (!$cart) {
            return response()->json([
                'success' => false,
                'message' => 'Cart not found'
            ], 404);
        }

        $item = $cart->items()->where('id', $id)->firstOrFail();
        
        $item->update(['quantity' => $request->quantity]);
        $cart->calculateTotals();
        $cart->refresh();

        return response()->json([
            'success' => true,
            'message' => 'Cart updated successfully',
            'item_total' => 'RM ' . number_format($item->subtotal, 2),
            'cart_total' => 'RM ' . number_format($cart->total_amount, 2),
            'cart_count' => $cart->item_count
        ]);
    }

    /**
     * Remove item from cart
     */
    public function remove($id)
    {
        $cart = $this->getCart();
        
        if (!$cart) {
            return response()->json([
                'success' => false,
                'message' => 'Cart not found'
            ], 404);
        }

        $item = $cart->items()->where('id', $id)->firstOrFail();
        $item->delete();
        $cart->calculateTotals();
        $cart->refresh();

        return response()->json([
            'success' => true,
            'message' => 'Item removed from cart successfully',
            'cart_count' => $cart->item_count,
            'cart_total' => 'RM ' . number_format($cart->total_amount, 2)
        ]);
    }

    /**
     * Clear entire cart
     */
    public function clear()
    {
        $cart = $this->getCart();
        
        if (!$cart) {
            return response()->json([
                'success' => false,
                'message' => 'Cart not found'
            ], 404);
        }

        $cart->items()->delete();
        $cart->calculateTotals();

        return response()->json([
            'success' => true,
            'message' => 'Cart cleared successfully',
            'cart_count' => 0,
            'cart_total' => 'RM 0.00'
        ]);
    }

    /**
     * Get cart count for header
     */
    public function getCount()
    {
        $cart = $this->getCart();
        $count = $cart ? $cart->item_count : 0;

        return response()->json(['count' => $count]);
    }

    /**
     * Increase item quantity
     */
    public function increase($id)
    {
        $cart = $this->getCart();
        
        if (!$cart) {
            return response()->json([
                'success' => false,
                'message' => 'Cart not found'
            ], 404);
        }

        $item = $cart->items()->where('id', $id)->firstOrFail();
        $item->increaseQuantity(1);
        $cart->refresh();

        return response()->json([
            'success' => true,
            'quantity' => $item->quantity,
            'item_total' => 'RM ' . number_format($item->subtotal, 2),
            'cart_total' => 'RM ' . number_format($cart->total_amount, 2),
            'cart_count' => $cart->item_count
        ]);
    }

    /**
     * Decrease item quantity
     */
    public function decrease($id)
    {
        $cart = $this->getCart();
        
        if (!$cart) {
            return response()->json([
                'success' => false,
                'message' => 'Cart not found'
            ], 404);
        }

        $item = $cart->items()->where('id', $id)->firstOrFail();
        $item->decreaseQuantity(1);
        $cart->refresh();

        $response = [
            'success' => true,
            'cart_total' => 'RM ' . number_format($cart->total_amount, 2),
            'cart_count' => $cart->item_count
        ];

        if ($item->exists) {
            $response['quantity'] = $item->quantity;
            $response['item_total'] = 'RM ' . number_format($item->subtotal, 2);
        }

        return response()->json($response);
    }

    /**
     * Get or create cart for authenticated user or session
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
     * Get existing cart
     */
    private function getCart()
    {
        try {
            $user = Auth::user();
            $sessionId = session()->getId();
            
            if ($user) {
                return Cart::where('user_id', $user->id)->first();
            }
            
            return Cart::where('session_id', $sessionId)->first();
        } catch (\Exception $e) {
            \Log::error('Cart retrieval error: ' . $e->getMessage());
            return null;
        }
    }
}