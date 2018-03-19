@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row">
        <div class="col-md-8 col-md-offset-2">
            <div class="panel panel-default">
                <div class="panel-heading">Dashboard</div>

                <div class="panel-body">
                    <a href="{{url('posts/create') }}" class="btn btn-primary">Create Post</a>
                    <h3>Your Blog Posts</h3>
                    @if(count($posts) > 0)
                        <table class="table table-stripped">
                            <thead>
                                <tr>
                                    <th>Title</th>
                                    <th></th>
                                    <th></th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($posts as $post)
                                    <tr>
                                        <td>{{$post->title}}</td>
                                        <td><a href="{{url('posts', $post->id)}}/edit" class="btn btn-default">Edit</a></td>
                                        <td>
                                            {!!Form::open(['action' => ['PostController@destroy', $post->id], 'method'=>'POST', 'class'=>'pull-right'])!!}
                                                {{Form::hidden('_method','DELETE')}}
                                                {{Form::submit('Delete', ['class'=>'btn btn-danger'])}}
                                            {!!Form::close()!!}
                                        </td>
                                    </tr>
                                @endforeach
                        </table>
                    @else
                    <p>You have no Post</p>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
