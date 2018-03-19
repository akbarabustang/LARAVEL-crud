@extends('layouts.app')

@section('content')
    <a href="{{url('posts')}}" class="btn btn-default">Go Back</a>
    <h3>{{$post->title}}</h3>
    <img style="width: 50%;" src="{{url('apps/storage/app/public/cover_image', $post->cover_image)}}" alt="">
    <br><br>
    <div>
        {!!$post->body!!}    
    </div>
    <hr>
    <small>Written on {{$post->created_at}}  by {{$post->user->name}}</small>
    <hr>
    @if(!Auth::guest())
        @if(Auth::user()->id == $post->user_id)
            <a href="{{ url('posts', $post->id) }}/edit" class="btn btn-primary">Edit</a>
            {!!Form::open(['action' => ['PostController@destroy', $post->id], 'method'=>'POST', 'class'=>'pull-right'])!!}
                {{Form::hidden('_method','DELETE')}}
                {{Form::submit('Delete', ['class'=>'btn btn-danger'])}}
            {!!Form::close()!!}
        @endif
    @endif
@endsection
