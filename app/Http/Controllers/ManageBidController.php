<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Bid;
use App\Models\Product;
use App\Models\Variation;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ManageBidController extends Controller
{
    /**
     * Display a listing of the bids with filters.
     */
    public function index(Request $request)
    {
        // Start building the query with necessary relationships
        $query = Bid::with(['product', 'winner', 'variation']);

        // 1. Search Filter (Product Name or Winner Name)
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->whereHas('product', function($pq) use ($search) {
                    $pq->where('name', 'like', "%{$search}%");
                })
                ->orWhereHas('winner', function($wq) use ($search) {
                    $wq->where('name', 'like', "%{$search}%");
                });
            });
        }

        // 2. Status Filter
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        // 3. Time Filter
        if ($request->filled('time_filter')) {
            $now = now();
            switch ($request->time_filter) {
                case 'today':
                    $query->whereDate('start_time', $now->today());
                    break;
                case 'tomorrow':
                    $query->whereDate('start_time', $now->copy()->addDay());
                    break;
                case 'week':
                    $query->whereBetween('start_time', [
                        $now->copy()->startOfWeek(),
                        $now->copy()->endOfWeek()
                    ]);
                    break;
                case 'month':
                    $query->whereMonth('start_time', $now->month)
                          ->whereYear('start_time', $now->year);
                    break;
                case 'past':
                    $query->where('end_time', '<', $now);
                    break;
            }
        }

        // Get paginated results ordered by latest
        $bids = $query->latest()->paginate(20);

        // Calculate global statistics (independent of current filters for the stats cards)
        $allBids = Bid::all();
        $stats = [
            'active' => $allBids->where('status', 'active')
                                ->where('start_time', '<=', now())
                                ->where('end_time', '>', now())
                                ->count(),
            'upcoming' => $allBids->where('status', 'active')
                                  ->where('start_time', '>', now())
                                  ->count(),
            'completed' => $allBids->where('status', 'completed')->count(),
            'draft' => $allBids->where('status', 'draft')->count(),
            'total' => $allBids->count()
        ];

        // Calculate page-specific statistics (for the quick stats bar)
        $pageStats = [
            'participants' => $bids->sum('bid_count'), // Total bids placed on current page
            'total_value' => $bids->sum('current_price'), // Total current bid value on page
            'winners' => $bids->where('winner_id', '!=', null)->count() // Winners on current page
        ];

        return view('managebid.index', compact('bids', 'stats', 'pageStats'));
    }

    /**
     * Show the form for creating a new bid.
     */
    public function create()
    {
        // Fetch products that are active
        $products = Product::active()
            ->with(['variations' => function($q) {
                $q->active()->where('stock', '>', 0);
            }])
            ->where(function($query) {
                $query->where('stock_quantity', '>', 0)
                      ->orWhereHas('variations', function($q) {
                          $q->active()->where('stock', '>', 0);
                      });
            })
            ->get();
        
        return view('managebid.create', compact('products'));
    }

    /**
     * Store a newly created bid in storage.
     */
    public function store(Request $request)
    {
        // 1. Custom validation for the composite selection field
        $request->validate([
            'item_selection' => 'required|string', 
        ]);

        // 2. Parse the selection (Format: "product_1" or "variation_5")
        $selectionParts = explode('_', $request->input('item_selection'));
        $type = $selectionParts[0] ?? null;
        $id = $selectionParts[1] ?? null;

        $productId = null;
        $variationId = null;

        if ($type === 'product' && $id) {
            $productId = $id;
        } elseif ($type === 'variation' && $id) {
            $variation = Variation::findOrFail($id);
            $productId = $variation->product_id;
            $variationId = $id;
        } else {
             return back()->withInput()->with('error', 'Invalid product/variation selection.');
        }

        // Merge back for standard validation
        $request->merge([
            'product_id' => $productId,
            'variation_id' => $variationId
        ]);

        // 3. Standard Validation
        $validated = $request->validate([
            'product_id' => 'required|exists:products,id',
            'variation_id' => 'nullable|exists:variations,id',
            'starting_price' => 'required|numeric|min:0.01',
            'reserve_price' => 'nullable|numeric|min:0|gt:starting_price',
            'bid_increment' => 'required|numeric|min:0.01',
            'start_time' => 'required|date|after:now',
            'end_time' => 'required|date|after:start_time',
            'terms_conditions' => 'nullable|string|max:1000',
            'auto_extend' => 'boolean',
            'extension_minutes' => 'required_if:auto_extend,true|integer|min:1|max:30'
        ], [
            'reserve_price.gt' => 'The reserve price must be greater than the starting price.',
            'end_time.after' => 'The end time must be after the start time.',
            'start_time.after' => 'The start time must be in the future.',
            'product_id.required' => 'Please select a product.',
        ]);

        try {
            $bidData = [
                'product_id' => $validated['product_id'],
                'variation_id' => $validated['variation_id'],
                'starting_price' => $validated['starting_price'],
                'current_price' => $validated['starting_price'],
                'reserve_price' => $validated['reserve_price'] ?? null,
                'bid_increment' => $validated['bid_increment'],
                'start_time' => $validated['start_time'],
                'end_time' => $validated['end_time'],
                'terms_conditions' => $validated['terms_conditions'] ?? null,
                'auto_extend' => $validated['auto_extend'] ?? false,
                'extension_minutes' => $validated['auto_extend'] ? $validated['extension_minutes'] : 5,
                'status' => 'draft',
                'bid_count' => 0,
            ];

            Bid::create($bidData);

            return redirect()->route('admin.managebid.index')
                            ->with('success', 'Bid created successfully!');

        } catch (\Exception $e) {
            return redirect()->back()
                            ->withInput()
                            ->with('error', 'Failed to create bid: ' . $e->getMessage());
        }
    }

    /**
     * Display the specified bid.
     */
    public function show(Bid $bid)
    {
        $bid->load([
            'product.images', 
            'variation',
            'bids.user',
            'winner'
        ]);
        
        $participants = User::whereHas('bidBids', function($query) use ($bid) {
            $query->where('bid_id', $bid->id);
        })->get();
        
        return view('managebid.show', compact('bid', 'participants'));
    }

    /**
     * Show the form for editing the specified bid.
     */
    public function edit(Bid $bid)
    {
        $products = Product::active()
            ->with(['variations' => function($q) {
                $q->active()->where('stock', '>', 0);
            }])
            ->get();
        
        return view('managebid.edit', compact('bid', 'products'));
    }

    /**
     * Update the specified bid in storage.
     */
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

        return redirect()->route('admin.managebid.index')
                         ->with('success', 'Bid updated successfully.');
    }

    /**
     * Remove the specified bid from storage.
     */
    public function destroy(Bid $bid)
    {
        try {
            $bid->delete();
            return redirect()->route('admin.managebid.index')
                             ->with('success', 'Bid deleted successfully.');
        } catch (\Exception $e) {
             return redirect()->back()->with('error', 'Error deleting bid.');
        }
    }

    // --- Action Methods ---

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

    // --- Bulk Action Method (Linked to Index Page) ---

    public function bulkAction(Request $request)
    {
        $request->validate([
            'ids' => 'required|json',
            'action' => 'required|string|in:start,pause,complete,delete'
        ]);

        $ids = json_decode($request->ids, true);
        if (empty($ids)) {
             return back()->with('error', 'No bids selected.');
        }

        $bids = Bid::whereIn('id', $ids)->get();
        $count = 0;

        DB::beginTransaction();
        try {
            foreach($bids as $bid) {
                switch ($request->action) {
                    case 'start':
                        if ($bid->status === 'draft' || $bid->status === 'paused') {
                            $bid->update(['status' => 'active']);
                            $count++;
                        }
                        break;
                    case 'pause':
                        if ($bid->status === 'active') {
                            $bid->update(['status' => 'paused']);
                            $count++;
                        }
                        break;
                    case 'complete':
                        if ($bid->status !== 'completed' && $bid->status !== 'cancelled') {
                            // Determine winner logic manually to avoid redirect loop
                            $highestBid = $bid->bids()->orderBy('amount', 'desc')->first();
                            $updateData = ['status' => 'completed'];
                            
                            if ($highestBid) {
                                $updateData['winner_id'] = $highestBid->user_id;
                                $updateData['winning_bid_amount'] = $highestBid->amount;
                            }
                            
                            $bid->update($updateData);
                            $count++;
                        }
                        break;
                    case 'delete':
                        $bid->delete();
                        $count++;
                        break;
                }
            }
            DB::commit();
            return back()->with('success', "Bulk action '{$request->action}' performed on {$count} bid(s).");

        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', 'Bulk action failed: ' . $e->getMessage());
        }
    }

    // --- Helper Methods ---

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