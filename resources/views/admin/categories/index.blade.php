@extends('layouts.app')

@section('content')
    <h1>Categories</h1>
    @if(count($categories) > 0)
        <table>
            @foreach($categories as $category)
                <tr>
                    <td>{{$category->name}}</td>
                    <td><a class="btn btn-primary" href="/admin/category/{{$category->id}}/edit">Edit</a></td>
                    <td>
                        {!! Form::open(['action' => ['CategoriesController@destroy', $category->id], 'method' => 'POST', 'class' => 'pull-left', 'onsubmit' => 'return confirm("Are you sure you want to delete this category?")']) !!}
                        {{ Form::hidden('_method', 'DELETE') }}
                        {{ Form::bsSubmit('Delete', ['class' => 'btn btn-danger']) }}
                        {!! Form::close() !!}
                    </td>
                </tr>
            @endforeach
        </table>
    @endif
@endsection
