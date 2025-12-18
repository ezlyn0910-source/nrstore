<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Cart;
use App\Models\CartItem;
use App\Models\Product;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Illuminate\Validation\ValidationException;

class CartController extends Controller
{
    /**
     * Display the cart page
     */
    public function index()
    {
        try {

            session()->forget('buy_now_order');

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
            
        } catch (\Exception $e) {
            Log::error('Cart index error: ' . $e->getMessage());
            return redirect()->back()->with('error', 'Unable to load cart. Please try again.');
        }
    }

    /**
     * Add item to cart
     */
    public function add(Request $request)
    {
        Log::info('Cart Add Request:', $request->all());
        
        try {

            session()->forget('buy_now_order');

            $request->validate([
                'product_id' => 'required|exists:products,id',
                'quantity' => 'required|integer|min:1|max:99',
                'variation_id' => 'nullable|exists:variations,id'
            ]);

            $cart = $this->getOrCreateCart();
            
            if (!$cart) {
                Log::error('Cart creation failed');
                return response()->json([
                    'success' => false,
                    'message' => 'Unable to create cart. Please try again.'
                ], 500);
            }

            $product = Product::with(['images', 'variations'])->findOrFail($request->product_id);

            // Check stock based on variation or main product
            $availableStock = 0;
            if ($request->has('variation_id') && $request->variation_id) {
                $variation = $product->variations->where('id', $request->variation_id)->first();
                if ($variation) {
                    $availableStock = $variation->stock ?? 0;
                }
            } else {
                $availableStock = $product->stock_quantity ?? 0;
            }

            // Check product stock
            if ($availableStock < $request->quantity) {
                return response()->json([
                    'success' => false,
                    'message' => 'Insufficient stock. Only ' . $availableStock . ' items available.'
                ], 422);
            }

            // Check if item already exists in cart
            $existingItemQuery = $cart->items()->where('product_id', $request->product_id);
            
            if ($request->has('variation_id') && $request->variation_id) {
                $existingItemQuery->where('variation_id', $request->variation_id);
            } else {
                $existingItemQuery->whereNull('variation_id');
            }

            $existingItem = $existingItemQuery->first();

            $newQuantity = $existingItem ? 
                $existingItem->quantity + $request->quantity : 
                $request->quantity;

            // Check if total quantity exceeds stock
            if ($newQuantity > $availableStock) {
                $available = $availableStock - ($existingItem ? $existingItem->quantity : 0);
                return response()->json([
                    'success' => false,
                    'message' => 'Cannot add more than available stock. You can add up to ' . $available . ' more items.'
                ], 422);
            }

            if ($existingItem) {
                $existingItem->update([
                    'quantity' => $newQuantity
                ]);
                Log::info('Updated existing cart item', ['item_id' => $existingItem->id]);
            } else {
                $cartItemData = [
                    'cart_id' => $cart->id,
                    'product_id' => $request->product_id,
                    'quantity' => $request->quantity,
                    'price' => $product->price,
                ];

                // Add variation_id if provided
                if ($request->has('variation_id') && $request->variation_id) {
                    $variation = $product->variations->where('id', $request->variation_id)->first();
                    if ($variation) {
                        $cartItemData['variation_id'] = $request->variation_id;
                        $cartItemData['price'] = $variation->price ?? $product->price;
                    }
                }

                $cartItem = CartItem::create($cartItemData);
                Log::info('Created new cart item', ['item_id' => $cartItem->id]);
            }

            // Recalculate cart totals
            $cart->calculateTotals();
            $cart->refresh();

            Log::info('Cart after add:', [
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

        } catch (ValidationException $e) {
            Log::error('Cart Add Validation Error: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Invalid input: ' . $e->getMessage()
            ], 422);
            
        } catch (\Exception $e) {
            Log::error('Cart Add Error: ' . $e->getMessage());
            Log::error('Error Stack: ' . $e->getTraceAsString());
            
            return response()->json([
                'success' => false,
                'message' => 'Error adding product to cart. Please try again.'
            ], 500);
        }
    }

    /**
     * Update cart item quantity
     */
    public function update(Request $request, $id)
    {
        try {

            session()->forget('buy_now_order');

            // If quantity is 0, remove item instead of validating
            if ((int) $request->quantity === 0) {
                $cart = $this->getCart();
                if (!$cart) {
                    return response()->json([
                        'success' => false,
                        'message' => 'Cart not found'
                    ], 404);
                }

                $item = $cart->items()->where('id', $id)->first();

                if ($item) {
                    $item->delete();
                }

                // Recalculate totals after removal
                $cart->calculateTotals();
                $cart->refresh();

                return response()->json([
                    'success'        => true,
                    'removed'        => true,
                    'cart_total_raw' => (float) $cart->total_amount,
                    'cart_total_html'=> 'RM ' . number_format($cart->total_amount, 2),
                    'cart_count'     => (int) $cart->item_count,
                ]);
            }

            // Normal validation for qty 1–99
            $request->validate([
                'quantity' => 'required|integer|min:1|max:99'
            ]);

            $cart = $this->getCart();
            
            if (!$cart) {
                return response()->json([
                    'success' => false,
                    'message' => 'Cart not found'
                ], 404);
            }

            $item = $cart->items()->with('product')->where('id', $id)->firstOrFail();

            $availableStock = $item->product->stock_quantity ?? $item->product->stock ?? 0;

            if ($request->quantity > $availableStock) {
                return response()->json([
                    'success' => false,
                    'message' => 'Only ' . $availableStock . ' items available in stock.'
                ], 422);
            }

            // Update quantity
            $item->update(['quantity' => $request->quantity]);
            $item->refresh();

            // Recalculate cart totals
            $cart->calculateTotals();
            $cart->refresh();

            // Item total
            $itemTotal = $item->subtotal ?? ($item->price * $item->quantity);

            return response()->json([
                'success'          => true,
                'removed'          => false,
                'item_total_raw'   => (float) $itemTotal,
                'cart_total_raw'   => (float) $cart->total_amount,
                'item_total_html'  => 'RM ' . number_format($itemTotal, 2),
                'cart_total_html'  => 'RM ' . number_format($cart->total_amount, 2),
                'cart_count'       => (int) $cart->item_count,
            ]);

        } catch (ValidationException $e) {
            return response()->json([
                'success' => false,
                'message' => 'Invalid quantity: ' . $e->getMessage()
            ], 422);

        } catch (\Exception $e) {
            \Log::error('Cart Update Error: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Error updating cart item. Please try again.'
            ], 500);
        }
    }

    /**
     * Remove item from cart
     */
    public function remove($id)
    {
        try {

            session()->forget('buy_now_order');
            
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

        } catch (\Exception $e) {
            Log::error('Cart Remove Error: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Error removing item from cart. Please try again.'
            ], 500);
        }
    }

    /**
     * Clear entire cart
     */
    public function clear()
    {
        try {
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

        } catch (\Exception $e) {
            Log::error('Cart Clear Error: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Error clearing cart. Please try again.'
            ], 500);
        }
    }

    /**
     * Get cart count for header
     */
    public function getCount(Request $request)
    {
        try {
            $cart = $this->getOrCreateCart(); // ✅ same logic as index()

            $count = $cart ? (int) $cart->items()->sum('quantity') : 0;

            return response()->json(['count' => $count])
                ->header('Cache-Control', 'no-store, no-cache, must-revalidate, max-age=0')
                ->header('Pragma', 'no-cache')
                ->header('Expires', '0');

        } catch (\Exception $e) {
            \Log::error('Cart count error: ' . $e->getMessage());

            return response()->json(['count' => 0])
                ->header('Cache-Control', 'no-store, no-cache, must-revalidate, max-age=0')
                ->header('Pragma', 'no-cache')
                ->header('Expires', '0');
        }
    }

    /**
     * Increase item quantity
     */
    public function increase($id)
    {
        try {
            $cart = $this->getCart();
            
            if (!$cart) {
                return response()->json([
                    'success' => false,
                    'message' => 'Cart not found'
                ], 404);
            }

            $item = $cart->items()->with('product')->where('id', $id)->firstOrFail();

            $availableStock = $item->product->stock_quantity 
                ?? $item->product->stock 
                ?? null;

            if (!is_null($availableStock) && $item->quantity >= $availableStock) {
                return response()->json([
                    'success' => false,
                    'message' => 'Cannot add more than available stock. Maximum ' . $availableStock . ' items available.'
                ], 422);
            }

            $item->increaseQuantity(1);

            $cart->calculateTotals();
            $cart->refresh();

            return response()->json([
                'success'    => true,
                'quantity'   => $item->quantity,
                'item_total' => 'RM ' . number_format($item->subtotal, 2),
                'cart_total' => 'RM ' . number_format($cart->total_amount, 2),
                'cart_count' => $cart->item_count
            ]);

        } catch (\Exception $e) {
            Log::error('Cart Increase Error: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Error increasing quantity. Please try again.'
            ], 500);
        }
    }

    /**
     * Decrease item quantity
     */
    public function decrease($id)
    {
        try {
            $cart = $this->getCart();
            
            if (!$cart) {
                return response()->json([
                    'success' => false,
                    'message' => 'Cart not found'
                ], 404);
            }

            $item = $cart->items()->where('id', $id)->firstOrFail();
            $item->decreaseQuantity(1);

            $cart->calculateTotals();
            $cart->refresh();

            $response = [
                'success'    => true,
                'cart_total' => 'RM ' . number_format($cart->total_amount, 2),
                'cart_count' => $cart->item_count
            ];

            if ($item->exists) {
                $response['quantity']   = $item->quantity;
                $response['item_total'] = 'RM ' . number_format($item->subtotal, 2);
            }

            return response()->json($response);

        } catch (\Exception $e) {
            Log::error('Cart Decrease Error: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Error decreasing quantity. Please try again.'
            ], 500);
        }
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
            Log::error('Cart creation error: ' . $e->getMessage());
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
            Log::error('Cart retrieval error: ' . $e->getMessage());
            return null;
        }
    }

    /**
     * Validate stock for all cart items
     */
    public function validateStock()
    {
        try {
            $cart = $this->getCart();

            if (!$cart) {
                return response()->json([
                    'valid'           => false,
                    'outOfStockItems' => 0,
                    'details'         => [],
                    'message'         => 'Cart not found',
                ]);
            }

            // Use the scopes/accessors defined in CartItem
            $cartItems = $cart->items()
                ->withStockInfo()
                ->get();

            $outOfStockItems   = 0;
            $outOfStockDetails = [];

            foreach ($cartItems as $item) {
                $availableStock = $item->available_stock ?? 0;
                $requested      = (int) $item->quantity;

                $productName = $item->product->name ?? 'Unknown Product';

                if ($availableStock < $requested) {
                    $outOfStockItems++;

                    $outOfStockDetails[] = [
                        'product_name' => $productName,
                        'requested'    => $requested,
                        'available'    => $availableStock,
                        'item_id'      => $item->id,
                    ];
                }
            }

            return response()->json([
                'valid'           => $outOfStockItems === 0,
                'outOfStockItems' => $outOfStockItems,
                'details'         => $outOfStockDetails,
                'message'         => $outOfStockItems === 0
                    ? 'All items are in stock'
                    : 'Some items are out of stock',
            ]);

        } catch (\Throwable $e) {
            \Log::error('Stock validation error: '.$e->getMessage(), [
                'trace' => $e->getTraceAsString(),
            ]);

            return response()->json([
                'valid'           => false,
                'outOfStockItems' => 0,
                'details'         => [],
                'message'         => 'Error checking stock: '.$e->getMessage(),
            ], 500);
        }
    }

    /**
     * Validate quantities
     */
    public function validateQuantities()
    {
        try {
            $cart = $this->getCart();
            
            if (!$cart) {
                return response()->json([
                    'valid' => false,
                    'message' => 'Cart not found'
                ]);
            }

            $cartItems = $cart->items;
            $invalidItems = 0;
            $invalidDetails = [];

            foreach ($cartItems as $item) {
                if ($item->quantity < 1 || $item->quantity > 99) {
                    $invalidItems++;
                    $invalidDetails[] = [
                        'product_name' => $item->product->name ?? 'Unknown Product',
                        'quantity' => $item->quantity,
                        'item_id' => $item->id
                    ];
                }
            }

            return response()->json([
                'valid' => $invalidItems === 0,
                'invalidItems' => $invalidItems,
                'details' => $invalidDetails,
                'message' => $invalidItems === 0 ? 'All quantities are valid' : 'Some items have invalid quantities'
            ]);

        } catch (\Exception $e) {
            Log::error('Quantity validation error: ' . $e->getMessage());
            return response()->json([
                'valid' => false,
                'message' => 'Error validating quantities'
            ]);
        }
    }

    /**
     * Get cart summary for checkout
     */
    public function getSummary()
    {
        try {
            $cart = $this->getCart();
            
            if (!$cart) {
                return response()->json([
                    'success' => false,
                    'message' => 'Cart not found'
                ], 404);
            }

            $cart->calculateTotals();
            $cartItems = $cart->items()->with(['product.images'])->get();

            return response()->json([
                'success' => true,
                'cart' => [
                    'item_count'      => $cart->item_count,
                    'total_amount'    => $cart->total_amount,
                    'formatted_total' => 'RM ' . number_format($cart->total_amount, 2),
                    'items'           => $cartItems
                ]
            ]);

        } catch (\Exception $e) {
            Log::error('Cart Summary Error: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Error retrieving cart summary'
            ], 500);
        }
    }
}