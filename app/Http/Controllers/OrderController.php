<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class OrderController extends Controller
{
    public function index()
    {
        // Temporary: Return empty collection for testing without database
        $orders = collect([
            // Sample order data for design testing
            (object)[
                'id' => 1,
                'order_number' => 'ORD-001234',
                'status' => 'pending',
                'total_amount' => 3499.00,
                'created_at' => now()->subDays(2),
                'items' => [
                    (object)[
                        'product' => (object)[
                            'id' => 1,
                            'name' => 'Dell XPS 13 Laptop',
                            'specifications' => 'Intel Core i7-1165G7 • 16GB RAM • 512GB SSD',
                            'processor' => 'Intel Core i7-1165G7', // Add this
                            'ram' => '16GB RAM', // Add this
                            'storage' => '512GB SSD', // Add this
                            'images' => [
                                (object)['path' => 'images/product1.jpg']
                            ]
                        ],
                        'quantity' => 1,
                        'price' => 3499.00
                    ]
                ],
                'statusHistory' => [
                    (object)[
                        'status' => 'pending',
                        'created_at' => now()->subDays(2),
                        'notes' => 'Order placed successfully'
                    ]
                ]
            ],
            (object)[
                'id' => 2,
                'order_number' => 'ORD-001233',
                'status' => 'delivered',
                'total_amount' => 2299.00,
                'created_at' => now()->subDays(7),
                'items' => [
                    (object)[
                        'product' => (object)[
                            'id' => 2,
                            'name' => 'HP Pavilion 15',
                            'specifications' => 'AMD Ryzen 5 5500U • 8GB RAM • 256GB SSD',
                            'processor' => 'AMD Ryzen 5 5500U', // Add this
                            'ram' => '8GB RAM', // Add this
                            'storage' => '256GB SSD', // Add this
                            'images' => [
                                (object)['path' => 'images/product2.jpg']
                            ]
                        ],
                        'quantity' => 1,
                        'price' => 2299.00
                    ]
                ],
                'statusHistory' => [
                    (object)[
                        'status' => 'delivered',
                        'created_at' => now()->subDays(1),
                        'notes' => 'Order delivered successfully'
                    ],
                    (object)[
                        'status' => 'shipped',
                        'created_at' => now()->subDays(3),
                        'notes' => 'Order shipped via express delivery'
                    ],
                    (object)[
                        'status' => 'processing',
                        'created_at' => now()->subDays(5),
                        'notes' => 'Order is being processed'
                    ],
                    (object)[
                        'status' => 'pending',
                        'created_at' => now()->subDays(7),
                        'notes' => 'Order placed successfully'
                    ]
                ]
            ],
            (object)[
                'id' => 3,
                'order_number' => 'ORD-001232',
                'status' => 'cancelled',
                'total_amount' => 1899.00,
                'created_at' => now()->subDays(10),
                'items' => [
                    (object)[
                        'product' => (object)[
                            'id' => 3,
                            'name' => 'Lenovo IdeaPad 3',
                            'specifications' => 'Intel Core i5-1135G7 • 8GB RAM • 512GB SSD',
                            'processor' => 'Intel Core i5-1135G7', // Add this
                            'ram' => '8GB RAM', // Add this
                            'storage' => '512GB SSD', // Add this
                            'images' => [
                                (object)['path' => 'images/product3.jpg']
                            ]
                        ],
                        'quantity' => 1,
                        'price' => 1899.00
                    ]
                ],
                'statusHistory' => [
                    (object)[
                        'status' => 'cancelled',
                        'created_at' => now()->subDays(8),
                        'notes' => 'Order cancelled by customer'
                    ],
                    (object)[
                        'status' => 'pending',
                        'created_at' => now()->subDays(10),
                        'notes' => 'Order placed successfully'
                    ]
                ]
            ]
        ]);

        return view('orders', compact('orders'));
    }

    public function show($orderId)
    {
        // Temporary: Return sample order data for testing
        $order = (object)[
            'id' => $orderId,
            'order_number' => 'ORD-001234',
            'status' => 'pending',
            'subtotal' => 3499.00,
            'shipping_cost' => 10.00,
            'tax_amount' => 210.00,
            'discount_amount' => 0.00,
            'total_amount' => 3719.00,
            'payment_method' => 'credit_card',
            'payment_status' => 'paid',
            'shipping_method' => 'express',
            'tracking_number' => 'TRK123456789',
            'tracking_url' => '#',
            'estimated_delivery' => now()->addDays(3),
            'created_at' => now()->subDays(2),
            'items' => [
                (object)[
                    'product' => (object)[
                        'id' => 1,
                        'name' => 'Dell XPS 13 Laptop',
                        'specifications' => 'Intel Core i7-1165G7 • 16GB RAM • 512GB SSD',
                        'images' => collect([
                            (object)['path' => 'images/product1.jpg']
                        ])
                    ],
                    'quantity' => 1,
                    'price' => 3499.00
                ]
            ],
            'statusHistory' => [
                (object)[
                    'status' => 'pending',
                    'created_at' => now()->subDays(2),
                    'notes' => 'Order placed successfully'
                ]
            ],
            'shippingAddress' => (object)[
                'full_name' => 'John Doe',
                'address_line_1' => '123 Main Street',
                'address_line_2' => 'Apt 4B',
                'city' => 'Kuala Lumpur',
                'state' => 'Wilayah Persekutuan',
                'postal_code' => '50000',
                'country' => 'Malaysia',
                'phone' => '+60 12-345 6789'
            ],
            'billingAddress' => (object)[
                'full_name' => 'John Doe',
                'address_line_1' => '123 Main Street',
                'address_line_2' => 'Apt 4B',
                'city' => 'Kuala Lumpur',
                'state' => 'Wilayah Persekutuan',
                'postal_code' => '50000',
                'country' => 'Malaysia'
            ]
        ];

        return view('order-details', compact('order'));
    }

    public function details($orderId)
    {
        // Temporary: Return sample order details for modal
        $order = (object)[
            'id' => $orderId,
            'order_number' => 'ORD-001234',
            'status' => 'pending',
            'subtotal' => 3499.00,
            'shipping_cost' => 10.00,
            'tax_amount' => 210.00,
            'total_amount' => 3719.00,
            'payment_method' => 'credit_card',
            'payment_status' => 'paid',
            'shipping_method' => 'express',
            'tracking_number' => 'TRK123456789',
            'estimated_delivery' => now()->addDays(3),
            'created_at' => now()->subDays(2),
            'items' => [
                (object)[
                    'product' => (object)[
                        'name' => 'Dell XPS 13 Laptop',
                        'specifications' => 'Intel Core i7-1165G7 • 16GB RAM • 512GB SSD',
                        'images' => collect([
                            (object)['path' => 'images/product1.jpg']
                        ])
                    ],
                    'quantity' => 1,
                    'price' => 3499.00
                ]
            ],
            'shippingAddress' => (object)[
                'full_name' => 'John Doe',
                'address_line_1' => '123 Main Street',
                'address_line_2' => 'Apt 4B',
                'city' => 'Kuala Lumpur',
                'state' => 'Wilayah Persekutuan',
                'postal_code' => '50000',
                'country' => 'Malaysia',
                'phone' => '+60 12-345 6789'
            ],
            'billingAddress' => (object)[
                'full_name' => 'John Doe',
                'address_line_1' => '123 Main Street',
                'address_line_2' => 'Apt 4B',
                'city' => 'Kuala Lumpur',
                'state' => 'Wilayah Persekutuan',
                'postal_code' => '50000',
                'country' => 'Malaysia'
            ]
        ];

        return view('partials.order-details-content', compact('order'));
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