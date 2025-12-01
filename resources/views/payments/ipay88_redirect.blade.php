@extends('layouts.app')

@section('content')
<div class="checkout-page">
    <div class="checkout-container">
        <div class="order-summary">
            <h3>Redirecting to Payment...</h3>
            <p>Please wait while we redirect you to our secure payment provider (iPay88).</p>

            <form id="ipay88Form" method="post" action="{{ $paymentUrl }}">
                @foreach($params as $name => $value)
                    <input type="hidden" name="{{ $name }}" value="{{ $value }}">
                @endforeach
                <noscript>
                    <button type="submit" class="btn btn-primary">Click here if you are not redirected</button>
                </noscript>
            </form>
        </div>
    </div>
</div>

<script>
    window.addEventListener('load', function () {
        document.getElementById('ipay88Form').submit();
    });
</script>
@endsection
