<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Bid;
use App\Models\Product;
use Illuminate\Http\Request;
use App\Models\User;

class ManageBidController extends Controller
{
    public function index()
    {
        $bids = Bid::with(['product', 'winner'])
                    ->latest()
                    ->paginate(20);
        
        return view('managebid.index', compact('bids'));
    }

    public function create()
    {
        $products = Product::active()
                          ->withoutVariations()
                          ->where('stock_quantity', '>', 0)
                          ->get();
        
        return view('managebid.create', compact('products'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'product_id' => 'required|exists:products,id',
            'starting_price' => 'required|numeric|min:0',
            'reserve_price' => 'nullable|numeric|min:0',
            'bid_increment' => 'required|numeric|min:0.01',
            'start_time' => 'required|date|after:now',
            'end_time' => 'required|date|after:start_time',
            'terms_conditions' => 'nullable|string',
            'auto_extend' => 'boolean',
            'extension_minutes' => 'required_if:auto_extend,true|integer|min:1'
        ]);

        $validated['current_price'] = $validated['starting_price'];
        $validated['status'] = 'draft';
        
        Bid::create($validated);

        return redirect()->route('admin.managebid.index')
                         ->with('success', 'Bid created successfully.');
    }

    public function show(Bid $bid)
    {
        $bid->load([
            'product.images', 
            'bids.user',
            'winner'
        ]);
        
        // Get users who participated in this bid
        $participants = User::whereHas('bidBids', function($query) use ($bid) {
            $query->where('bid_id', $bid->id);
        })->get();
        
        return view('managebid.show', compact('bid', 'participants'));
    }

    public function edit(Bid $bid)
    {
        $products = Product::active()->get();
        
        return view('managebid.edit', compact('bid', 'products'));
    }

    public function update(Request $request, Bid $bid)
    {
        $validated = $request->validate([
            'starting_price' => 'required|numeric|min:0',
            'reserve_price' => 'nullable|numeric|min:0',
            'bid_increment' => 'required|numeric|min:0.01',
            'start_time' => 'required|date',
            'end_time' => 'required|date|after:start_time',
            'status' => 'required|in:draft,active,paused,completed,cancelled',
            'terms_conditions' => 'nullable|string',
            'auto_extend' => 'boolean',
            'extension_minutes' => 'required_if:auto_extend,true|integer|min:1'
        ]);

        $bid->update($validated);

        return redirect()->route('managebid.index')
                         ->with('success', 'Bid updated successfully.');
    }

    public function destroy(Bid $bid)
    {
        $bid->delete();

        return redirect()->route('managebid.index')
                         ->with('success', 'Bid deleted successfully.');
    }

    public function startBid(Bid $bid)
    {
        $bid->update(['status' => 'active']);

        return back()->with('success', 'Bid started successfully.');
    }

    public function pauseBid(Bid $bid)
    {
        $bid->update(['status' => 'paused']);

        return back()->with('success', 'Bid paused successfully.');
    }

    public function completeBid(Bid $bid)
    {
        // Logic to determine winner and complete bid
        $highestBid = $bid->bids()->orderBy('amount', 'desc')->first();
        
        if ($highestBid) {
            $bid->update([
                'status' => 'completed',
                'winner_id' => $highestBid->user_id,
                'winning_bid_amount' => $highestBid->amount
            ]);
        } else {
            $bid->update(['status' => 'completed']);
        }

        return back()->with('success', 'Bid completed successfully.');
    }

    /**
     * Manually assign winner (admin override)
     */
    public function assignWinner(Request $request, Bid $bid)
    {
        $validated = $request->validate([
            'winner_id' => 'required|exists:users,id',
            'winning_bid_amount' => 'required|numeric|min:' . $bid->starting_price
        ]);

        $bid->update([
            'winner_id' => $validated['winner_id'],
            'winning_bid_amount' => $validated['winning_bid_amount'],
            'status' => 'completed'
        ]);

        return back()->with('success', 'Winner assigned successfully.');
    }

    /**
     * Get bid participants
     */
    public function participants(Bid $bid)
    {
        $participants = User::whereHas('bidBids', function($query) use ($bid) {
            $query->where('bid_id', $bid->id);
        })->withCount(['bidBids as bids_count' => function($query) use ($bid) {
            $query->where('bid_id', $bid->id);
        }])->get();

        return view('managebid.participants', compact('bid', 'participants'));
    }

    /**
     * View user's bid history
     */
    public function userBidHistory(User $user)
    {
        $userBids = $user->bidBids()
                        ->with(['bid.product'])
                        ->orderBy('created_at', 'desc')
                        ->paginate(20);

        return view('managebid.userhistory', compact('user', 'userBids'));
    }
}