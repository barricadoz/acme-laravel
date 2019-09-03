@extends('layouts.app')

@section('content')
    <div class="row column">
        <h2>Thank you for your order!</h2>
        <p>Your order has been successfully processed. An email has been sent to your address <span>{{ $userEmail }}</span>.</p>
    </div>
@endsection
