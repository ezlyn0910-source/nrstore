@extends('admin.adminbase')

@section('title', 'Admin Dashboard')
@section('page_title', 'Dashboard Overview')

@section('content')
<div class="dashboard-container">
    <div class="dashboard-cards">
        <div class="card">
            <h3>Total Users</h3>
            <p>120</p>
        </div>
        <div class="card">
            <h3>Total Products</h3>
            <p>85</p>
        </div>
        <div class="card">
            <h3>Pending Orders</h3>
            <p>15</p>
        </div>
        <div class="card">
            <h3>Monthly Revenue</h3>
            <p>RM 12,450</p>
        </div>
    </div>

    <div class="dashboard-section">
        <h2>Recent Orders</h2>
        <table class="table table-striped align-middle">
            <thead>
                <tr>
                    <th>#</th>
                    <th>Customer</th>
                    <th>Product</th>
                    <th>Status</th>
                    <th>Date</th>
                </tr>
            </thead>
            <tbody>
                <tr>
                    <td>001</td>
                    <td>Aina Rahman</td>
                    <td>Matte Foundation</td>
                    <td><span class="badge bg-warning text-dark">Processing</span></td>
                    <td>2025-10-15</td>
                </tr>
                <tr>
                    <td>002</td>
                    <td>Sarah Lim</td>
                    <td>Lip Tint</td>
                    <td><span class="badge bg-success">Delivered</span></td>
                    <td>2025-10-14</td>
                </tr>
                <tr>
                    <td>003</td>
                    <td>Nora Zulkifli</td>
                    <td>BB Cream</td>
                    <td><span class="badge bg-danger">Cancelled</span></td>
                    <td>2025-10-13</td>
                </tr>
            </tbody>
        </table>
    </div>
</div>
@endsection
