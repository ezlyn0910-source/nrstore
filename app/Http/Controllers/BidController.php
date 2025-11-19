<?php

namespace App\Http\Controllers;

use App\Models\Bid;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;

class BidController extends Controller
{
    public function index()
    {
        // Get live auctions from database
        $liveAuctions = Bid::with(['product', 'highestBid'])
            ->active()
            ->whereHas('product', function($query) {
                $query->where('is_active', true);
            })
            ->get()
            ->map(function($bid) {
                return (object)[
                    'id' => $bid->id,
                    'product_name' => $bid->product->name,
                    'current_bid' => $bid->current_price,
                    'time_left' => $this->formatTimeRemaining($bid->time_remaining),
                    'image' => $bid->product->main_image_url,
                    'bid_count' => $bid->bid_count,
                    'condition' => $bid->product->condition ?? 'Excellent',
                    'end_time' => $bid->end_time,
                    'starting_price' => $bid->starting_price,
                    'reserve_price' => $bid->reserve_price
                ];
            });

        // Get upcoming auctions
        $upcomingAuctions = Bid::with(['product'])
            ->upcoming()
            ->whereHas('product', function($query) {
                $query->where('is_active', true);
            })
            ->get();

        return view('bid.index', compact('liveAuctions', 'upcomingAuctions'));
    }

    public function brandAuctions($brand)
    {
        $brands = ['microsoft', 'hp', 'dell', 'lenovo'];
        
        if (!in_array(strtolower($brand), $brands)) {
            abort(404);
        }

        $auctions = Bid::with(['product'])
            ->whereHas('product', function($query) use ($brand) {
                $query->where('brand', ucfirst($brand))
                      ->where('is_active', true);
            })
            ->active()
            ->get();

        return view('bid.brand', compact('auctions', 'brand'));
    }

    public function show($id)
    {
        $bid = Bid::with(['product', 'bids.user', 'highestBid.user'])
                 ->findOrFail($id);

        return view('bid.show', compact('bid'));
    }

    public function placeBid(Request $request, $id)
    {
        $request->validate([
            'amount' => 'required|numeric|min:0',
            'is_auto_bid' => 'boolean',
            'max_auto_bid' => 'nullable|numeric|min:0'
        ]);

        $bid = Bid::active()->findOrFail($id);
        $user = Auth::user();
        $amount = $request->amount;

        // Check if bid is active
        if (!$bid->is_active) {
            return back()->with('error', 'This auction has ended.');
        }

        // Check minimum bid amount
        $minBid = $bid->current_price + $bid->bid_increment;
        if ($amount < $minBid) {
            return back()->with('error', "Minimum bid amount is RM " . number_format($minBid, 2));
        }

        // Create new bid
        $bidBid = $bid->bids()->create([
            'user_id' => $user->id,
            'amount' => $amount,
            'is_auto_bid' => $request->is_auto_bid ?? false,
            'max_auto_bid' => $request->max_auto_bid,
            'ip_address' => $request->ip()
        ]);

        // Update current price
        $bid->update([
            'current_price' => $amount,
            'bid_count' => $bid->bid_count + 1
        ]);

        // Handle auto-extension if enabled
        if ($bid->auto_extend && $bid->end_time->diffInMinutes(now()) < 5) {
            $bid->update([
                'end_time' => $bid->end_time->addMinutes($bid->extension_minutes)
            ]);
        }

        return back()->with('success', 'Bid placed successfully!');
    }

    private function formatTimeRemaining($timeRemaining)
    {
        if (!$timeRemaining) return 'Ended';
        
        if ($timeRemaining->d > 0) {
            return $timeRemaining->d . 'd ' . $timeRemaining->h . 'h';
        } elseif ($timeRemaining->h > 0) {
            return $timeRemaining->h . 'h ' . $timeRemaining->i . 'm';
        } else {
            return $timeRemaining->i . 'm';
        }
    }
}