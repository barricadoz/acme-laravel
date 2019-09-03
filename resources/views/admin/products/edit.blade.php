@extends('layouts.app')
@section('title', 'Edit Product')

@section('content')
    <div class="category">
        <div>
            <div>
                <h1>Edit Product <a href="/admin/product" class="btn btn-default btn-xs float-right">Go back</a></h1>
            </div>
        </div>
        <div>
            <div>
                {!! Form::open(['action' => ['ProductsController@update', $product->id], 'method' => 'POST', 'files' => TRUE]) !!}
                {!! Form::label('category', 'Category') !!}
                {!! Form::select('category', $categoryOptions, $selectedCategory) !!}
                {{ Form::bsText('name', $product->name, ['placeholder' => 'Product name']) }}
                {{ Form::bsTextArea('description', $product->description, ['placeholder' => 'Product description']) }}
                {{ Form::bsText('price', $product->price, ['placeholder' => 'Price']) }}
                {{ Form::bsText('quantity', $product->quantity, ['placeholder' => 'Quantity']) }}
                <p>Current Image path: {{ $product->image_path }}</p>
                {{ Form::bsFile('image'), '' }}
                {{Form::hidden('_method', 'PUT')}}
                {{ Form::bsSubmit('Submit', ['class' => 'btn btn-primary']) }}
                {!! Form::close() !!}
            </div>
        </div>
    </div>
@endsection

