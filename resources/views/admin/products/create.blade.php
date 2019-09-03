@extends('layouts.app')
@section('title', 'Add Product')

@section('content')
    <div class="category">
        <div>
            <div>
                <h1>Add Product <a href="/admin/product" class="btn btn-default btn-xs float-right">Go back</a></h1>
            </div>
        </div>
        <div>
            <div>
                {!! Form::open(['action' => 'ProductsController@store', 'method' => 'POST', 'files' => TRUE]) !!}
                {!! Form::label('category', 'Category') !!}
                {!! Form::select('category', $categoryOptions) !!}
                {{ Form::bsText('name', '', ['placeholder' => 'Product name']) }}
                {{ Form::bsTextArea('description', '', ['placeholder' => 'Product description']) }}
                {{ Form::bsText('price', '', ['placeholder' => 'Price']) }}
                {{ Form::bsText('quantity', '', ['placeholder' => 'Quantity']) }}
                {{ Form::bsFile('image'), '' }}
                {{ Form::bsSubmit('Submit', ['class' => 'btn btn-primary']) }}
                {!! Form::close() !!}
            </div>
        </div>
    </div>
@endsection
