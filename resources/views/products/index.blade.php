@extends('layouts.app')

@section('content')
    <h1>Products</h1>
    @if (count($productsByCategory))
        @foreach ($productsByCategory as $category => $products)
            <h2>{{ $category }}</h2>
            <table class="display-products">
                <tbody>
                @foreach ($products as $product)
                    <tr>
                        <td>{{ $product->name }}</td>
                        <td>{{ $product->description }}</td>
                        <td>{{ $product->price }}</td>
                        <td>
                            {!! Form::open(['action' => 'CartController@addItem', 'method' => 'POST']) !!}
                            {{ Form::hidden('product_id', $product->id) }}
                            {{ Form::bsSubmit('Buy', ['class' => 'btn btn-primary']) }}
                            {!! Form::close() !!}
{{--                            <form method="post" action="/basket">--}}
{{--                                <input type="hidden" name="token" value="{{ \App\Classes\CSRFToken::_token() }}">--}}
{{--                                <input type="hidden" name="product_id" value="{{ $product->id }}">--}}
{{--                                @if ($product->quantity < 1)--}}
{{--                                    <button class="button cart expanded" disabled>--}}
{{--                                        Sold out--}}
{{--                                    </button>--}}
{{--                                @elseif ($product->quantity - $product->cartQuantity < 1)--}}
{{--                                    <button type="submit" class="button cart expanded" disabled>--}}
{{--                                        Unavailable--}}
{{--                                    </button>--}}
{{--                                @else--}}
{{--                                    <button type="submit" class="button cart expanded">--}}
{{--                                        Buy--}}
{{--                                    </button>--}}
{{--                                @endif--}}
{{--                            </form>--}}
                        </td>
                    </tr>
                @endforeach
                </tbody>
            </table>
        @endforeach
    @endif
@endsection
