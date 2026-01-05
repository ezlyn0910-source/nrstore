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
                @php
                    // Safe product name
                    $firstItem = $order->orderItems->first();
                    $productName = 'No items';

                    if ($firstItem) {
                        if (!empty($firstItem->product) && !empty($firstItem->product->name)) {
                            $productName = $firstItem->product->name;
                        } elseif (!empty($firstItem->product_name)) {
                            $productName = $firstItem->product_name;
                        } else {
                            $productName = 'Product';
                        }
                    }

                    // Fulfilment status badge (order.status)
                    $status = $order->status ?: 'unknown';
                    $statusBadge = 'secondary';

                    if ($status === 'pending') $statusBadge = 'secondary';
                    elseif ($status === 'processing') $statusBadge = 'warning text-dark';
                    elseif ($status === 'shipped') $statusBadge = 'info';
                    elseif ($status === 'delivered') $statusBadge = 'success';
                    elseif ($status === 'cancelled') $statusBadge = 'danger';
                    elseif ($status === 'refunded') $statusBadge = 'dark';

                    // Payment status badge (order.payment_status)
                    $pay = $order->payment_status ?: 'pending';
                    $payBadge = 'secondary';

                    if ($pay === 'paid') $payBadge = 'success';
                    elseif ($pay === 'failed') $payBadge = 'danger';
                    elseif ($pay === 'pending') $payBadge = 'warning text-dark';
                @endphp

                <tr>
                    <td>{{ $order->order_number }}</td>
                    <td>{{ $order->user->name ?? 'N/A' }}</td>

                    <td>
                        @if($order->orderItems->count() > 0)
                            {{ $productName }}
                            @if($order->orderItems->count() > 1)
                                + {{ $order->orderItems->count() - 1 }} more
                            @endif
                        @else
                            No items
                        @endif
                    </td>

                    <td>
                        {{-- Fulfilment Status --}}
                        <span class="badge bg-{{ $statusBadge }}">
                            {{ $order->status_label ?? ucfirst($status) }}
                        </span>

                        {{-- Payment Status --}}
                        <span class="badge bg-{{ $payBadge }}" style="margin-left:6px;">
                            {{ $order->payment_status_label ?? ucfirst($pay) }}
                        </span>
                    </td>

                    <td>{{ $order->created_at->format('Y-m-d') }}</td>

                    <td>
                        <a href="{{ route('admin.manageorder.show', $order->id) }}"
                           class="btn btn-sm btn-outline-primary"
                           title="View Order">
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

<style>
    /* Dashboard */
    .dashboard-container {
        display: flex;
        flex-direction: column;
        gap: 2rem;
    }

    .dashboard-cards {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(220px, 1fr));
        gap: 1.5rem;
    }

    .dashboard-cards .card {
        background-color: var(--white);
        border: 1px solid var(--border-light);
        border-radius: 10px;
        padding: 1.5rem;
        box-shadow: 0 2px 6px rgba(0, 0, 0, 0.05);
        text-align: center;
        transition: transform 0.2s ease, box-shadow 0.2s ease;
    }

    .dashboard-cards .card:hover {
        transform: translateY(-5px);
        box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);
    }

    .dashboard-cards h3 {
        color: var(--primary-green);
        font-size: 1.1rem;
        margin-bottom: 0.5rem;
    }

    .dashboard-cards p {
        font-size: 1.6rem;
        font-weight: 600;
        color: var(--accent-gold);
        margin: 0;
    }

    .dashboard-section h2 {
        font-size: 1.2rem;
        color: var(--primary-green);
        margin-bottom: 1rem;
    }

    .table {
        background-color: var(--white);
        border-radius: 10px;
        overflow: hidden;
    }
</style>
@endsection
