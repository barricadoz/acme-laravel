@extends('layouts.app')

@section('content')
    <div>
        <ul>
            <li>Name: {{$product->name}}</li>
            <li>Price: {{$product->price}}</li>
            <li>Description: {{$product->description}}</li>
            <li>Quantity: {{$product->quantity}}</li>
            <li>Category: {{$category->name}}</li>
            <li>Image path: {{$product->image_path}}</li>
        </ul>
    </div>
@endsection
