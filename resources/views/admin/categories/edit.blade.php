@extends('layouts.app')
@section('title', 'Edit Category')
@section('data-page-id', 'adminEditCategory')

@section('content')
    <div class="category">
        <div>
            <div>
                <h1>Edit Category <a href="/admin/category" class="btn btn-default btn-xs float-right">Go back</a></h1>
            </div>
        </div>
        <div>
            <div>
                {!! Form::open(['action' => ['CategoriesController@update', $category->id], 'method' => 'POST']) !!}
                {{ Form::bsText('name', $category->name, ['placehoder' => 'Category name']) }}
                {{Form::hidden('_method', 'PUT')}}
                {{ Form::bsSubmit('Submit', ['class' => 'btn btn-primary']) }}
                {!! Form::close() !!}
            </div>
        </div>
    </div>
@endsection
