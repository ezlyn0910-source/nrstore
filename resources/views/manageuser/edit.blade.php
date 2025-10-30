@extends('admin.adminbase')

@section('title', 'Edit User - ' . $manageuser->name)
@section('page_title', 'Edit User')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-md-8">
            <div class="card shadow mb-4">
                <div class="card-header">
                    <h6 class="m-0 font-weight-bold">Edit User Information</h6>
                </div>
                <div class="card-body">
                    <form action="{{ route('manageuser.update', $manageuser) }}" method="POST">
                        @csrf
                        @method('PUT')
                        
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="name">Name *</label>
                                    <input type="text" class="form-control @error('name') is-invalid @enderror" 
                                           id="name" name="name" value="{{ old('name', $manageuser->name) }}" required>
                                    @error('name')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="email">Email *</label>
                                    <input type="email" class="form-control @error('email') is-invalid @enderror" 
                                           id="email" name="email" value="{{ old('email', $manageuser->email) }}" required>
                                    @error('email')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="phone">Phone</label>
                                    <input type="text" class="form-control @error('phone') is-invalid @enderror" 
                                           id="phone" name="phone" value="{{ old('phone', $manageuser->phone) }}">
                                    @error('phone')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="status">Status *</label>
                                    <select class="form-control @error('status') is-invalid @enderror" 
                                            id="status" name="status" required>
                                        <option value="active" {{ old('status', $manageuser->status) == 'active' ? 'selected' : '' }}>
                                            Active
                                        </option>
                                        <option value="suspended" {{ old('status', $manageuser->status) == 'suspended' ? 'selected' : '' }}>
                                            Suspended
                                        </option>
                                    </select>
                                    @error('status')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                        </div>

                        <div class="form-group">
                            <button type="submit" class="btn btn-primary">Update User</button>
                            <a href="{{ route('manageuser.show', $manageuser) }}" class="btn btn-secondary">Cancel</a>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        <div class="col-md-4">
            <!-- Quick Actions -->
            <div class="card shadow mb-4">
                <div class="card-header">
                    <h6 class="m-0 font-weight-bold">Quick Actions</h6>
                </div>
                <div class="card-body">
                    @if($manageuser->status === 'active')
                    <form action="{{ route('manageuser.suspend', $manageuser) }}" method="POST" class="mb-3">
                        @csrf
                        <button type="submit" class="btn btn-warning btn-block" 
                                onclick="return confirm('Are you sure you want to suspend this user?')">
                            <i class="fas fa-pause"></i> Suspend User
                        </button>
                    </form>
                    @else
                    <form action="{{ route('manageuser.activate', $manageuser) }}" method="POST" class="mb-3">
                        @csrf
                        <button type="submit" class="btn btn-success btn-block">
                            <i class="fas fa-play"></i> Activate User
                        </button>
                    </form>
                    @endif

                    <div class="text-center">
                        <small class="text-muted">User ID: {{ $manageuser->id }}</small><br>
                        <small class="text-muted">Registered: {{ $manageuser->created_at->format('M d, Y') }}</small>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection