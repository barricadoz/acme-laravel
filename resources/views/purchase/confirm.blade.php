@extends('layouts.app')

@section('content')
    <div class="confirm-order">
        @if (isset($items) && count($items) > 0)
            <table>
                @foreach ($items as $item)
                    <tr>
                        <td>{{ $item['name'] }}</td>
                        <td>{{ $item['price'] }}</td>
                        <td>{{ $item['quantity'] }}</td>
                        <td>{{ $item['total'] }}</td>
                    </tr>
                @endforeach
                <tr>
                    <td colspan="3">
                        <strong>Order total</strong>
                    </td>
                    <td>{{ $cartTotal }}</td>
                </tr>
                <tr>
                    <td colspan="2"></td>
                    <td>
                        <a href="/basket" id="continue-shopping" class="button cart">
                            Return to your basket &nbsp; <i class="fas fa-shopping-basket" aria-hidden="true"></i>
                        </a>
                    </td>
                    <td>
                        <a href="/checkout" id="go-to-checkout" class="button success">
                            Place your order &nbsp; <i class="far fa-credit-card" aria-hidden="true"></i>
                        </a>
                    </td>
                </tr>
            </table>
        @endif
    </div>
@endsection
