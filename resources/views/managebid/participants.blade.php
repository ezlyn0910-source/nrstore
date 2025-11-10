@extends('admin.adminbase')
@section('title', 'Bid Participants - ' . $bid->product->name)

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">Participants for Bid: {{ $bid->product->name }}</h3>
                    <div class="card-tools">
                        <a href="{{ route('admin.bids.show', $bid) }}" class="btn btn-sm btn-secondary">
                            <i class="fas fa-arrow-left"></i> Back to Bid
                        </a>
                    </div>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-hover">
                            <thead>
                                <tr>
                                    <th>User ID</th>
                                    <th>Name</th>
                                    <th>Email</th>
                                    <th>Phone</th>
                                    <th>Bids Placed</th>
                                    <th>Last Bid</th>
                                    <th>Status</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($participants as $user)
                                <tr>
                                    <td>{{ $user->id }}</td>
                                    <td>{{ $user->name }}</td>
                                    <td>{{ $user->email }}</td>
                                    <td>{{ $user->phone ?? 'N/A' }}</td>
                                    <td>
                                        <span class="badge badge-primary">{{ $user->bids_count }}</span>
                                    </td>
                                    <td>
                                        {{ $user->bidBids->where('bid_id', $bid->id)->max('created_at')?->format('M d, H:i') ?? 'N/A' }}
                                    </td>
                                    <td>
                                        <span class="badge badge-{{ $user->isActive() ? 'success' : 'danger' }}">
                                            {{ ucfirst($user->status) }}
                                        </span>
                                    </td>
                                    <td>
                                        <a href="{{ route('admin.bids.user-history', $user) }}" 
                                           class="btn btn-sm btn-info" title="View Bid History">
                                            <i class="fas fa-history"></i>
                                        </a>
                                        @if($bid->status === 'active')
                                        <button class="btn btn-sm btn-warning" 
                                                onclick="assignWinner($user->id )"
                                                title="Assign as Winner">
                                            <i class="fas fa-trophy"></i>
                                        </button>
                                        @endif
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

@if($bid->status === 'active')
<script>
function assignWinner(userId) {
    if(confirm('Are you sure you want to assign this user as winner?')) {
        // Implementation for manual winner assignment
        // You can create a form or modal for this
    }
}
</script>
@endif
@endsection