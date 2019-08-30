@extends('layouts.app')

@section('content')
    <div class="cart">
        @if (isset($items) && count($items) > 0)
            <table>
                @foreach ($items as $item)
                    <tr>
                        <td>{{ $item['name'] }}</td>
                        <td>{{ $item['price'] }}</td>
                        <td>
                            <span>{{ $item['quantity'] }}</span>
                            @if ($item['stock'] - $item['quantity'] > 0)
                                <button class="update-quantity update-quantity-plus"
                                        style="cursor: pointer; color: #00a000"
                                        data-id="{{ $item['id'] }}" data-operator="+"
                                        data-token="">
                                    <i class="fa fa-plus-square" aria-hidden="true"></i>
                                </button>
                            @endif
                            @if ($item['quantity'] > 1)
                                <button class="update-quantity update-quantity-minus"
                                        style="cursor: pointer; color: #a00000" data-id="{{ $item['id'] }}"
                                        data-operator="-" data-token="">
                                    <i class="fa fa-minus-square" aria-hidden="true"></i>
                                </button>
                            @endif
                        </td>
                        <td>{{ $item['total'] }}</td>
                        <td>
                            <button class="cart-remove-item" style="cursor: pointer" data-id="{{ $item['id'] }}"
                                    data-token="">
                                <i class="fa fa-times" aria-hidden="true"></i>
                            </button>
                        </td>
                    </tr>
                @endforeach
                <tr>
                    <td colspan="3"></td>
                    <td>{{ $cartTotal }}</td>
                    <td></td>
                </tr>
                <tr>
                    <td colspan="2"></td>
                    <td>
                        <a href="/products" id="continue-shopping" class="button cart">
                            Continue shopping &nbsp; <i class="fas fa-shopping-basket" aria-hidden="true"></i>
                        </a>
                    </td>
                    <td>
                        <a href="/checkout/confirm-order" id="go-to-checkout" class="button success">
                            Checkout &nbsp; <i class="far fa-credit-card" aria-hidden="true"></i>
                        </a>
                    </td>
                    <td></td>
                </tr>
            </table>
        @endif
    </div>
@endsection
