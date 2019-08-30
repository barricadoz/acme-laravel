@extends('layouts.app')
@section('title', 'Add Category')
@section('data-page-id', 'adminAddCategory')

@section('content')
    <div class="category">
        <div>
            <div>
                <h1>Add Category <a href="/admin/category" class="btn btn-default btn-xs float-right">Go back</a></h1>
            </div>
        </div>
        <div>
            <div>
                {!! Form::open(['action' => 'CategoriesController@store', 'method' => 'POST']) !!}
                {{ Form::bsText('name', '', ['placeholder' => 'Category name']) }}
                {{ Form::bsSubmit('Submit', ['class' => 'btn btn-primary']) }}
                {!! Form::close() !!}
            </div>
        </div>
    </div>
@endsection
