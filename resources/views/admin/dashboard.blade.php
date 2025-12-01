@extends('admin.adminbase')

@section('title', 'Admin Dashboard')
@section('page_title', 'Dashboard Overview')

@section('content')
<div class="dashboard-container">
    <div class="dashboard-cards">
        <div class="card">
            <h3>Total Users</h3>
            <p>{{ $stats['total_users'] }}</p>
        </div>
        <div class="card">
            <h3>Total Products</h3>
            <p>{{ $stats['total_products'] }}</p>
        </div>
        <div class="card">
            <h3>Uncomplete Orders</h3>
            <p>{{ $stats['uncomplete_orders'] }}</p>
        </div>
        <div class="card">
            <h3>Monthly Revenue</h3>
            <p>RM {{ number_format($stats['monthly_revenue'], 2) }}</p>
        </div>
    </div>

    <div class="dashboard-section">
        <h2>Recent Orders</h2>
        <table class="table table-striped align-middle">
            <thead>
                <tr>
                    <th>Order ID</th>
                    <th>Customer</th>
                    <th>Product</th>
                    <th>Status</th>
                    <th>Date</th>
                    <th>Action</th>
                </tr>
            </thead>
            <tbody>
                @foreach($recentOrders as $order)
                <tr>
                    <td>{{ $order->order_number }}</td>
                    <td>{{ $order->user->name ?? 'N/A' }}</td>
                    <td>
                        @if($order->orderItems->count() > 0)
                            {{ $order->orderItems->first()->product->name ?? 'Product' }}
                            @if($order->orderItems->count() > 1)
                                + {{ $order->orderItems->count() - 1 }} more
                            @endif
                        @else
                            No items
                        @endif
                    </td>
                    <td>
                        <span class="badge bg-{{ 
                            $order->status == 'paid' ? 'success' :
                            ($order->status == 'processing' ? 'warning text-dark' : 
                            ($order->status == 'shipped' ? 'info' : 
                            ($order->status == 'delivered' ? 'success' : 
                            ($order->status == 'cancelled' ? 'danger' : 'secondary')))) 
                        }}">
                            {{ $order->status_label }}
                        </span>
                    </td>
                    <td>{{ $order->created_at->format('Y-m-d') }}</td>
                    <td>
                        <a href="{{ route('admin.manageorder.show', $order->id) }}" class="btn btn-sm btn-outline-primary" title="View Order">
                            <i class="fas fa-eye"></i> View
                        </a>
                    </td>
                </tr>
                @endforeach
                @if($recentOrders->count() == 0)
                <tr>
                    <td colspan="6" class="text-center">No recent orders found.</td>
                </tr>
                @endif
            </tbody>
        </table>
    </div>
</div>
@endsection