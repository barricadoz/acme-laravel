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
                                {!! Form::open(['action' => 'CartController@updateItem', 'method' => 'POST']) !!}
                                {{ Form::hidden('product_id', $item['id']) }}
                                {{ Form::hidden('operation', 'increment') }}
                                {{ Form::bsSubmit('+', ['class' => 'btn btn-primary']) }}
                                {!! Form::close() !!}
                            @endif
                            @if ($item['quantity'] > 1)
                                {!! Form::open(['action' => 'CartController@updateItem', 'method' => 'POST']) !!}
                                {{ Form::hidden('product_id', $item['id']) }}
                                {{ Form::hidden('operation', 'decrement') }}
                                {{ Form::bsSubmit('-', ['class' => 'btn btn-primary']) }}
                                {!! Form::close() !!}
                            @endif
                        </td>
                        <td>{{ $item['total'] }}</td>
                        <td>
                            {!! Form::open(['action' => 'CartController@removeItem', 'method' => 'POST']) !!}
                            {{ Form::hidden('product_id', $item['id']) }}
                            {{ Form::bsSubmit('Remove', ['class' => 'btn btn-primary']) }}
                            {!! Form::close() !!}
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
