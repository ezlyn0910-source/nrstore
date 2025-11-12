<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class BidController extends Controller
{
    public function index()
    {
        // Auction Categories Data
        $auctionCategories = collect([
            (object)[
                'id' => 1,
                'name' => 'Gaming Laptops',
                'logo' => 'storage/images/categories/gaming.png',
                'count' => 24
            ],
            (object)[
                'id' => 2,
                'name' => 'Business Laptops',
                'logo' => 'storage/images/categories/business.png',
                'count' => 18
            ],
            (object)[
                'id' => 3,
                'name' => 'Ultrabooks',
                'logo' => 'storage/images/categories/ultrabook.png',
                'count' => 15
            ],
            (object)[
                'id' => 4,
                'name' => 'Workstations',
                'logo' => 'storage/images/categories/workstation.png',
                'count' => 12
            ],
            (object)[
                'id' => 5,
                'name' => '2-in-1 Laptops',
                'logo' => 'storage/images/categories/2in1.png',
                'count' => 20
            ],
            (object)[
                'id' => 6,
                'name' => 'Student Laptops',
                'logo' => 'storage/images/categories/student.png',
                'count' => 30
            ],
            (object)[
                'id' => 7,
                'name' => 'Apple MacBooks',
                'logo' => 'storage/images/categories/apple.png',
                'count' => 16
            ],
            (object)[
                'id' => 8,
                'name' => 'Gaming Desktops',
                'logo' => 'storage/images/categories/desktop.png',
                'count' => 14
            ]
        ]);

        // Live Auctions Data for Slider
        $liveAuctions = collect([
            (object)[
                'id' => 1,
                'product_name' => 'MacBook Pro 16" M3 Max',
                'current_bid' => 3200.00,
                'time_left' => '2h 15m',
                'image' => 'storage/products/bid-macbook.jpg',
                'bid_count' => 12,
                'condition' => 'Like New',
                'end_time' => now()->addHours(2)->addMinutes(15)
            ],
            (object)[
                'id' => 2,
                'product_name' => 'Dell XPS 15 OLED',
                'current_bid' => 1800.00,
                'time_left' => '1d 4h',
                'image' => 'storage/products/bid-dell.jpg',
                'bid_count' => 8,
                'condition' => 'Excellent',
                'end_time' => now()->addDays(1)->addHours(4)
            ],
            (object)[
                'id' => 3,
                'product_name' => 'ASUS ROG Zephyrus',
                'current_bid' => 2200.00,
                'time_left' => '6h 30m',
                'image' => 'storage/products/bid-asus.jpg',
                'bid_count' => 15,
                'condition' => 'Refurbished',
                'end_time' => now()->addHours(6)->addMinutes(30)
            ],
            (object)[
                'id' => 4,
                'product_name' => 'Lenovo ThinkPad X1',
                'current_bid' => 950.00,
                'time_left' => '15m',
                'image' => 'storage/products/bid-lenovo.jpg',
                'bid_count' => 6,
                'condition' => 'Good',
                'end_time' => now()->addMinutes(15)
            ],
            (object)[
                'id' => 5,
                'product_name' => 'HP Spectre x360',
                'current_bid' => 1400.00,
                'time_left' => '3h 45m',
                'image' => 'storage/products/bid-hp.jpg',
                'bid_count' => 9,
                'condition' => 'Excellent',
                'end_time' => now()->addHours(3)->addMinutes(45)
            ]
        ]);

        return view('bid.index', compact('auctionCategories', 'liveAuctions'));
    }

    // ... keep your existing show() and placeBid() methods
}