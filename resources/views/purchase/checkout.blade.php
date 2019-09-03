@extends('layouts.app')

@section('stripe-checkout')
    <script src="https://js.stripe.com/v3/"></script>
    <script>
      var stripe = Stripe('{{ $stripeKey }}');

      stripe.redirectToCheckout({
        sessionId: '{{ $stripeSession }}',
      }).then(function (result) {
        // If `redirectToCheckout` fails due to a browser or network
        // error, display the localized error message to your customer
        // using `result.error.message`.
      });


    </script>
@endsection

@section('content')
    <div>

    </div>
    @yield('stripe-checkout')
@endsection
