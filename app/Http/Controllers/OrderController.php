<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class OrderController extends Controller
{
    public function index()
    {
        /*if (Auth::check()) {*/
            $orders = collect([
                (object)[
                    'id' => 1,
                    'order_number' => 'ORD-2024-001',
                    'status' => 'shipped',
                    'total_amount' => 2499.99,
                    'created_at' => now()->subDays(2),
                    'shipping_address' => (object)[
                        'name' => 'John Doe',
                        'address_line_1' => '123 Main Street',
                        'address_line_2' => 'Apt 4B',
                        'city' => 'Kuala Lumpur',
                        'state' => 'Wilayah Persekutuan',
                        'postal_code' => '50000',
                        'country' => 'Malaysia'
                    ],
                    'items' => collect([
                        (object)[
                            'id' => 1,
                            'quantity' => 1,
                            'price' => 2499.99,
                            'product' => (object)[
                                'name' => 'Gaming Laptop Pro',
                                'processor' => 'Intel Core i7-12700H',
                                'ram' => '16GB DDR5',
                                'storage' => '1TB NVMe SSD',
                                'specifications' => null
                            ]
                        ]
                    ])
                ],
                (object)[
                    'id' => 2,
                    'order_number' => 'ORD-2024-002',
                    'status' => 'delivered',
                    'total_amount' => 899.50,
                    'created_at' => now()->subDays(7),
                    'shipping_address' => (object)[
                        'name' => 'John Doe',
                        'address_line_1' => '123 Main Street',
                        'address_line_2' => 'Apt 4B',
                        'city' => 'Kuala Lumpur',
                        'state' => 'Wilayah Persekutuan',
                        'postal_code' => '50000',
                        'country' => 'Malaysia'
                    ],
                    'items' => collect([
                        (object)[
                            'id' => 2,
                            'quantity' => 2,
                            'price' => 449.75,
                            'product' => (object)[
                                'name' => 'Wireless Mechanical Keyboard',
                                'processor' => null,
                                'ram' => null,
                                'storage' => null,
                                'specifications' => 'RGB Backlit, Brown Switches, Wireless'
                            ]
                        ]
                    ])
                ],
                (object)[
                    'id' => 3,
                    'order_number' => 'ORD-2024-003',
                    'status' => 'pending',
                    'total_amount' => 1599.00,
                    'created_at' => now()->subHours(5),
                    'shipping_address' => (object)[
                        'name' => 'John Doe',
                        'address_line_1' => '123 Main Street',
                        'address_line_2' => null,
                        'city' => 'Kuala Lumpur',
                        'state' => 'Wilayah Persekutuan',
                        'postal_code' => '50000',
                        'country' => 'Malaysia'
                    ],
                    'items' => collect([
                        (object)[
                            'id' => 3,
                            'quantity' => 1,
                            'price' => 1599.00,
                            'product' => (object)[
                                'name' => '27" Gaming Monitor',
                                'processor' => null,
                                'ram' => null,
                                'storage' => null,
                                'specifications' => '144Hz, 1ms, QHD, IPS Panel'
                            ]
                        ]
                    ])
                ],
                (object)[
                    'id' => 4,
                    'order_number' => 'ORD-2024-004',
                    'status' => 'processing',
                    'total_amount' => 3299.00,
                    'created_at' => now()->subDays(1),
                    'shipping_address' => (object)[
                        'name' => 'John Doe',
                        'address_line_1' => '123 Main Street',
                        'address_line_2' => null,
                        'city' => 'Kuala Lumpur',
                        'state' => 'Wilayah Persekutuan',
                        'postal_code' => '50000',
                        'country' => 'Malaysia'
                    ],
                    'items' => collect([
                        (object)[
                            'id' => 4,
                            'quantity' => 1,
                            'price' => 3299.00,
                            'product' => (object)[
                                'name' => 'Gaming Desktop PC',
                                'processor' => 'AMD Ryzen 7 7800X3D',
                                'ram' => '32GB DDR5',
                                'storage' => '2TB SSD + 2TB HDD',
                                'specifications' => null
                            ]
                        ]
                    ])
                ],
                (object)[
                    'id' => 5,
                    'order_number' => 'ORD-2024-005',
                    'status' => 'cancelled',
                    'total_amount' => 599.00,
                    'created_at' => now()->subDays(10),
                    'shipping_address' => (object)[
                        'name' => 'John Doe',
                        'address_line_1' => '123 Main Street',
                        'address_line_2' => 'Apt 4B',
                        'city' => 'Kuala Lumpur',
                        'state' => 'Wilayah Persekutuan',
                        'postal_code' => '50000',
                        'country' => 'Malaysia'
                    ],
                    'items' => collect([
                        (object)[
                            'id' => 5,
                            'quantity' => 1,
                            'price' => 599.00,
                            'product' => (object)[
                                'name' => 'Gaming Mouse',
                                'processor' => null,
                                'ram' => null,
                                'storage' => null,
                                'specifications' => 'RGB, 16000 DPI, Wireless'
                            ]
                        ]
                    ])
                ],
                (object)[
                    'id' => 6,
                    'order_number' => 'ORD-2024-006',
                    'status' => 'returned',
                    'total_amount' => 1299.00,
                    'created_at' => now()->subDays(15),
                    'shipping_address' => (object)[
                        'name' => 'John Doe',
                        'address_line_1' => '123 Main Street',
                        'address_line_2' => null,
                        'city' => 'Kuala Lumpur',
                        'state' => 'Wilayah Persekutuan',
                        'postal_code' => '50000',
                        'country' => 'Malaysia'
                    ],
                    'items' => collect([
                        (object)[
                            'id' => 6,
                            'quantity' => 1,
                            'price' => 1299.00,
                            'product' => (object)[
                                'name' => 'Tablet Pro',
                                'processor' => null,
                                'ram' => null,
                                'storage' => null,
                                'specifications' => '11" Display, 128GB, Stylus Included'
                            ]
                        ]
                    ])
                ]
            ]);

            return view('orders.index', compact('orders'));
        /*} else {
            // User is not logged in - return empty orders with guest flag
            $orders = collect();
            $isGuest = true;
            return view('orders.index', compact('orders', 'isGuest'));
        }*/
            return view('orders.index', compact('orders'));
    }

    public function show($orderId)
    {
        // Find the order from our dummy data
        $orders = collect([
            (object)[
                'id' => 1,
                'order_number' => 'ORD-2024-001',
                'status' => 'shipped',
                'total_amount' => 2499.99,
                'created_at' => now()->subDays(2),
                'shipping_address' => (object)[
                    'name' => 'John Doe',
                    'address_line_1' => '123 Main Street',
                    'address_line_2' => 'Apt 4B',
                    'city' => 'Kuala Lumpur',
                    'state' => 'Wilayah Persekutuan',
                    'postal_code' => '50000',
                    'country' => 'Malaysia'
                ],
                'items' => collect([
                    (object)[
                        'id' => 1,
                        'quantity' => 1,
                        'price' => 2499.99,
                        'product' => (object)[
                            'name' => 'Gaming Laptop Pro',
                            'processor' => 'Intel Core i7-12700H',
                            'ram' => '16GB DDR5',
                            'storage' => '1TB NVMe SSD',
                            'specifications' => null
                        ]
                    ]
                ])
            ]
        ]);

        $order = $orders->firstWhere('id', $orderId);

        if (!$order) {
            abort(404);
        }

        return view('orders.show', compact('order'));
    }

    public function details($orderId)
    {
        // Find the order from our dummy data for modal
        $orders = collect([
            (object)[
                'id' => 1,
                'order_number' => 'ORD-2024-001',
                'status' => 'shipped',
                'total_amount' => 2499.99,
                'created_at' => now()->subDays(2),
                'shipping_address' => (object)[
                    'name' => 'John Doe',
                    'address_line_1' => '123 Main Street',
                    'address_line_2' => 'Apt 4B',
                    'city' => 'Kuala Lumpur',
                    'state' => 'Wilayah Persekutuan',
                    'postal_code' => '50000',
                    'country' => 'Malaysia'
                ],
                'items' => collect([
                    (object)[
                        'id' => 1,
                        'quantity' => 1,
                        'price' => 2499.99,
                        'product' => (object)[
                            'name' => 'Gaming Laptop Pro',
                            'processor' => 'Intel Core i7-12700H',
                            'ram' => '16GB DDR5',
                            'storage' => '1TB NVMe SSD',
                            'specifications' => null
                        ]
                    ]
                ])
            ]
        ]);

        $order = $orders->firstWhere('id', $orderId);

        if (!$order) {
            abort(404);
        }

        return view('orders.details', compact('order'));
    }

    public function cancel(Request $request)
    {
        // Temporary: Always return success for testing
        return response()->json([
            'success' => true, 
            'message' => 'Order cancelled successfully (test mode)'
        ]);
    }
}