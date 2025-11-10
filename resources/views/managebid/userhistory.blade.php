@extends('admin.adminbase')
@section('title', 'Bid History - ' . $user->name)

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">Bid History for {{ $user->name }}</h3>
                    <div class="card-tools">
                        <a href="{{ route('admin.users.index') }}" class="btn btn-sm btn-secondary">
                            <i class="fas fa-arrow-left"></i> Back to Users
                        </a>
                    </div>
                </div>
                <div class="card-body">
                    <!-- User Summary -->
                    <div class="row mb-4">
                        <div class="col-md-3">
                            <div class="info-box">
                                <span class="info-box-icon bg-info"><i class="fas fa-gavel"></i></span>
                                <div class="info-box-content">
                                    <span class="info-box-text">Total Bids</span>
                                    <span class="info-box-number">{{ $user->bidding_stats['total_bids_placed'] }}</span>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="info-box">
                                <span class="info-box-icon bg-success"><i class="fas fa-trophy"></i></span>
                                <div class="info-box-content">
                                    <span class="info-box-text">Bids Won</span>
                                    <span class="info-box-number">{{ $user->bidding_stats['bids_won'] }}</span>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="info-box">
                                <span class="info-box-icon bg-warning"><i class="fas fa-chart-line"></i></span>
                                <div class="info-box-content">
                                    <span class="info-box-text">Success Rate</span>
                                    <span class="info-box-number">{{ $user->bidding_stats['success_rate'] }}%</span>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="info-box">
                                <span class="info-box-icon bg-primary"><i class="fas fa-clock"></i></span>
                                <div class="info-box-content">
                                    <span class="info-box-text">Active Bids</span>
                                    <span class="info-box-number">{{ $user->bidding_stats['active_participations'] }}</span>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Bid History Table -->
                    <div class="table-responsive">
                        <table class="table table-hover">
                            <thead>
                                <tr>
                                    <th>Bid ID</th>
                                    <th>Product</th>
                                    <th>Bid Amount</th>
                                    <th>Bid Type</th>
                                    <th>Date</th>
                                    <th>Status</th>
                                    <th>Result</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($userBids as $bidRecord)
                                <tr>
                                    <td>#{{ $bidRecord->bid_id }}</td>
                                    <td>
                                        <a href="{{ route('admin.bids.show', $bidRecord->bid) }}">
                                            {{ $bidRecord->bid->product->name }}
                                        </a>
                                    </td>
                                    <td>
                                        <strong>{{ $bidRecord->formatted_amount }}</strong>
                                        @if($bidRecord->is_auto_bid)
                                        <span class="badge badge-info">Auto</span>
                                        @endif
                                    </td>
                                    <td>
                                        @if($bidRecord->is_auto_bid)
                                        <span class="text-info">Auto Bid</span>
                                        @else
                                        <span class="text-success">Manual Bid</span>
                                        @endif
                                    </td>
                                    <td>{{ $bidRecord->created_at->format('M d, Y H:i') }}</td>
                                    <td>
                                        <span class="badge badge-{{ $bidRecord->bid->status === 'active' ? 'success' : 'secondary' }}">
                                            {{ ucfirst($bidRecord->bid->status) }}
                                        </span>
                                    </td>
                                    <td>
                                        @if($bidRecord->bid->winner_id === $user->id && $bidRecord->bid->status === 'completed')
                                        <span class="badge badge-success">Won</span>
                                        @elseif($bidRecord->outbid_at)
                                        <span class="badge badge-danger">Outbid</span>
                                        @elseif($bidRecord->bid->status === 'active')
                                        <span class="badge badge-warning">Leading</span>
                                        @else
                                        <span class="badge badge-secondary">Lost</span>
                                        @endif
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>

                    <!-- Pagination -->
                    <div class="d-flex justify-content-center">
                        {{ $userBids->links() }}
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection