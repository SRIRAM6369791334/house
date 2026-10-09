@extends('layouts.app')

@section('content')
    <div class="container py-5 text-center">
        <h3>Opening secure payment...</h3>
        <p>Please do not refresh this page.</p>
        <a href="{{ url('checkout') }}" class="btn-def btn2">Back to Checkout</a>
    </div>

    <script src="https://sdk.cashfree.com/js/v3/cashfree.js"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            const paymentSessionId = @json($paymentSessionId);

            if (!paymentSessionId || typeof Cashfree === 'undefined') {
                window.location.href = @json(url('checkout'));
                return;
            }

            const cashfree = Cashfree({ mode: @json($cashfreeMode) });

            cashfree.checkout({
                paymentSessionId: paymentSessionId,
                redirectTarget: '_self'
            });
        });
    </script>
@endsection
