@extends('layouts.app')

@section('content')
<div class="container py-5">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header bg-primary text-white">
                    <h4 class="mb-0">Verify Your Email Address</h4>
                </div>
                <div class="card-body">
                    @if (session('resent'))
                        <div class="alert alert-success">
                            A fresh verification link has been sent to your email address.
                        </div>
                    @endif

                    <p>Before proceeding, please check your email for a verification link.</p>
                    <p>If you did not receive the email:</p>
                    
                    <form method="POST" action="{{ route('verification.resend') }}">
                        @csrf
                        <button type="submit" class="btn btn-primary">
                            Click here to request another verification email
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection