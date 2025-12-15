<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Address;
use App\Models\Cart;
use App\Models\CartItem;
use App\Models\Order;
use App\Models\Product;
use App\Models\Variation;
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

        $referer = request()->headers->get('referer');
        $isFromCart = $referer && str_contains($referer, route('cart.index'));

        if ($isFromCart) {
            session()->forget('buy_now_order');
        }

        // 1. Initialize variables
        $cartItems = collect([]);
        $subtotal = 0;
        $total = 0;
        $tax = 0;
        $discount = 0;
        $shippingFee = 10.99;

        // 2. Check for Buy Now Session Data first
        $buyNowOrder = session('buy_now_order');
        
        // FIX: Only use buy_now_order if it was JUST set (like from a Buy Now button)
        // Otherwise, use the user's cart
        $shouldUseBuyNow = false;
        
        if ($buyNowOrder && isset($buyNowOrder['is_buy_now']) && $buyNowOrder['is_buy_now']) {
            // Check if this is a fresh buy now (less than 5 minutes ago)
            $buyNowTime = $buyNowOrder['timestamp'] ?? 0;
            $currentTime = now()->timestamp;
            
            // Only use buy now if it's recent (5 minutes)
            if (($currentTime - $buyNowTime) < 300) { // 300 seconds = 5 minutes
                $shouldUseBuyNow = true;
            } else {
                // Clear stale buy now session
                session()->forget('buy_now_order');
                $shouldUseBuyNow = false;
            }
        }

        if ($shouldUseBuyNow) {
            $rawItems = collect($buyNowOrder['items'] ?? []);

            $cartItems = $rawItems->map(function ($item) {
                $productId   = data_get($item, 'product_id');
                $variationId = data_get($item, 'variation_id');
                $qty         = (int) (data_get($item, 'quantity', 1));

                $product = \App\Models\Product::with('images')->find($productId);
                if (!$product) return null;

                $variation = null;
                if ($variationId) {
                    $variation = \App\Models\Variation::where('id', $variationId)
                        ->where('product_id', $product->id)
                        ->first();
                }

                $unitPrice = $variation?->price ?? $product->price;

                return (object)[
                    'product_id'      => $product->id,
                    'variation_id'    => $variation?->id,
                    'product'         => $product,
                    'variation'       => $variation,
                    'name'            => $product->name,
                    'variation_name'  => $variation ? $this->getVariationName($variation) : null,
                    'price'           => $unitPrice,
                    'quantity'        => $qty,
                    'image'           => $product->image ?? null,
                    'total'           => $unitPrice * $qty,
                ];
            })->filter()->values();

            $subtotal = $cartItems->sum('total');
            $shippingFee = 10.99;
            $total = $subtotal + $shippingFee;

        } else {
            // 3. ALWAYS use Database Cart when coming from cart page
            $cart = $this->getOrCreateCart();
            
            if (!$cart || $cart->items->isEmpty()) {
                return redirect()->route('cart.index')->with('error', 'Your cart is empty.');
            }

            $cartItems = $cart->items()->with(['product.images', 'variation'])->get();
            $subtotal = $cart->total_amount;
            $shippingFee = 10.99;
            $total = $subtotal + $shippingFee;
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
            'banks',
            'shippingFee'
        ));
    }

    /**
     * Route: POST /buy-now  -> CheckoutController@buyNow
     */
    public function buyNow(Request $request)
    {
        try {
            $request->validate([
                'product_id'   => 'required|integer|exists:products,id',
                'quantity'     => 'nullable|integer|min:1',
                'variation_id' => 'nullable|integer',
            ]);

            $qty = (int) ($request->quantity ?? 1);

            $product = Product::with('images')->findOrFail((int) $request->product_id);

            $variation = null;

            // If product has variations, variation_id MUST be provided and valid
            if ((bool) $product->has_variations) {
                $variationId = (int) ($request->variation_id ?? 0);

                if ($variationId <= 0) {
                    return response()->json([
                        'success' => false,
                        'message' => 'Please select a variation.',
                    ], 422);
                }

                $variation = Variation::where('product_id', $product->id)
                    ->where('id', $variationId)
                    ->first();

                if (!$variation) {
                    return response()->json([
                        'success' => false,
                        'message' => 'Invalid variation selected.',
                    ], 422);
                }
            } 
            
            session()->forget('buy_now_order');

            $unitPrice = $variation ? (float) $variation->price : (float) $product->price;

            // Optional: for checkout display
            $variationName = $variation
                ? trim(implode(' • ', array_filter([
                    $variation->model ?? null,
                    $variation->processor ?? null,
                    $variation->ram ?? null,
                    $variation->storage ?? null,
                ])))
                : null;

            $item = [
                'product_id'     => (int) $product->id,
                'variation_id'   => $variation ? (int) $variation->id : null,
                'quantity'       => $qty,
                'price'          => $unitPrice,
                'product_name'   => (string) $product->name,
                'variation_name' => $variationName,
                'image'          => data_get($product, 'image')
                    ?? optional($product->images->first())->image_path
                    ?? null,
            ];

            session()->put('buy_now_order', [
                'is_buy_now' => true,
                'items'      => [$item],
                'total'      => $unitPrice * $qty,
                'timestamp'  => now()->timestamp, // ADD THIS
            ]);

            if ($request->expectsJson() || $request->ajax()) {
                return response()->json([
                    'success'      => true,
                    'redirect_url' => route('checkout.index'),
                ]);
            }

            return redirect()->route('checkout.index');

        } catch (\Throwable $e) {
            \Log::error('Buy Now error: ' . $e->getMessage(), [
                'payload' => $request->all(),
                'user_id' => optional(Auth::user())->id,
            ]);

            return response()->json([
                'success' => false,
                'message' => 'Error processing buy now. Please try again.',
            ], 500);
        }
    }

    public function clearBuyNow()
    {
        session()->forget('buy_now_order');
        return response()->json(['success' => true]);
    }

    private function getVariationName(Variation $variation): string
    {
        return trim(implode(' • ', array_filter([
            $variation->model ?? null,
            $variation->processor ?? null,
            $variation->ram ?? null,
            $variation->storage ?? null,
        ])));
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

    // ✅ everything below remains unchanged (your existing functions)
    public function review()
    {
        return view('checkout.review');
    }
    
    public function validateCheckout(Request $request)
    {
        return response()->json(['valid' => true]);
    }
    
    public function calculateShipping(Request $request)
    {
        return response()->json(['shipping' => 5.99]);
    }
    
    public function removePromoCode(Request $request)
    {
        return response()->json(['success' => true]);
    }
    
    public function getShippingMethods()
    {
        return response()->json([
            ['id' => 'standard', 'name' => 'Standard Shipping', 'price' => 5.99, 'days' => '3-5'],
            ['id' => 'express', 'name' => 'Express Shipping', 'price' => 12.99, 'days' => '1-2']
        ]);
    }
    
    public function getPaymentMethods()
    {
        return response()->json([
            ['id' => 'online_banking', 'name' => 'Online Banking'],
            ['id' => 'credit_card', 'name' => 'Credit Card'],
            ['id' => 'tng_ewallet', 'name' => 'Touch n Go eWallet']
        ]);
    }
    
    public function verifyStock()
    {
        return response()->json(['in_stock' => true]);
    }
    
    public function updateAddress(Request $request, $id)
    {
        try {
            $user = Auth::user();

            $address = Address::where('id', $id)
                ->where('user_id', $user->id)
                ->where('type', 'shipping')
                ->firstOrFail();

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
                'country_code' => 'nullable|string|max:5',
                'is_default'      => 'nullable|in:0,1,on,off,true,false'
            ]);

            $makeDefault = in_array($request->is_default, ['1', 'on', 'true', true], true);

            \DB::beginTransaction();

            if ($makeDefault) {
                Address::where('user_id', $user->id)
                    ->where('type', 'shipping')
                    ->where('id', '!=', $address->id)
                    ->update(['is_default' => false]);
            }

            $address->first_name      = $request->first_name;
            $address->last_name       = $request->last_name;
            $address->phone           = $request->phone;
            $address->address_line_1  = $request->address_line_1;
            $address->address_line_2  = $request->address_line_2;
            $address->city            = $request->city;
            $address->state           = $request->state;
            $address->postal_code     = $request->postal_code;
            $address->country         = $request->country ?? 'Malaysia';

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
    
    public function deleteAddress($id)
    {
        return response()->json(['success' => true]);
    }

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
                'country_code' => 'nullable|string|max:5',
                'is_default' => 'nullable|in:0,1,on,off,true,false'
            ]);

            \Log::info('Validation passed');
            
            if ($request->boolean('is_default')) {
                Address::where('user_id', $user->id)
                    ->where('type', 'shipping')
                    ->update(['is_default' => false]);
            }
            
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
                'country_code' => $request->country_code,
                'is_default' => $isFirstAddress || in_array($request->is_default, ['1', 'on', 'true', true], true)
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

    public function saveAddress(Request $request)
    {
        return $this->storeAddress($request);
    }

    public function success(Order $order)
    {
        if ($order->payment_status !== Order::PAYMENT_STATUS_PAID) {
            return redirect()
                ->route('checkout.failed')
                ->with('error', 'Payment was not completed.');
        }

        return view('checkout.success', compact('order'));
    }

    public function failed()
    {
        return view('checkout.failed');
    }

    public function placeOrder(Request $request)
    {
        $request->validate([
            'address_id' => 'required|exists:addresses,id',
            'payment_method' => 'required|in:tng_ewallet,online_banking,credit_card',
        ]);

        try {
            $user = Auth::user();
            
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
