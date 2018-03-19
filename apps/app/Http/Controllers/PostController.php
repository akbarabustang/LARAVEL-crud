<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use App\Post;
use DB;

class PostController extends Controller
{

    public function __construct()
    {
        $this->middleware('auth', ['except'=>['index', 'show']]);
    }


    public function index()
    {
        //$posts = Post::all();
        //$posts = Post::where('title', 'Post Two')->get();
        //$posts = DB::select('SELECT * FROM posts');
        //$posts = Post::orderBy('title', 'desc')->take(2)->get();
        //$posts = Post::orderBy('title', 'desc')->get();
        
        $posts = Post::orderBy('created_at', 'desc')->paginate(4);
        return view('posts.index')->with('posts', $posts);
    }

 
    public function create()
    {
        return view('posts.create');
    }

    public function store(Request $request)
    {
        $this->validate($request, [
            'title' => 'required',
            'body' => 'required',
            'cover_image' => 'image|nullable|max:1999'
        ]);
        
        //handle upload image
        if ($request->hasFile('cover_image')) {
            //get file name with the extension
            $fileNameWithExt =$request->file('cover_image')->getClientOriginalName();
            //get just file name
            $fileName = pathinfo($fileNameWithExt, PATHINFO_FILENAME);
            //
            //get just ext
            $extension = $request->file('cover_image')->getClientOriginalExtension();
            //filename to store
            $fileNameToStore = $fileName.'_'.time().'.'.$extension;
            //upload image
            $path = $request->file('cover_image')->storeAs('public/cover_image', $fileNameToStore);
        }else{
            $fileNameToStore = 'noimage.png';
        }

        //Create Post
        $post = new Post;
        $post->title = $request->input('title');
        $post->body = $request->input('body');
        $post->user_id = auth()->user()->id;
        $post->cover_image = $fileNameToStore;
        $post->save();

        return redirect('/posts')->with('success', 'Post created');
    }


    public function show($id)
    {
        $post = Post::find($id);
        return view('posts.show')->with('post', $post);
    }

    public function edit($id)
    {
        $post = Post::find($id);

        //check for correct user
        if (auth()->user()->id !== $post->user_id ) {
            return redirect('/posts')->with('error', 'Unauthorized Page !');
        }
        return view('posts.edit')->with('post', $post);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $this->validate($request, [
            'title' => 'required',
            'body' => 'required'
        ]);

        //handle upload image
        if ($request->hasFile('cover_image')) {
            //get file name with the extension
            $fileNameWithExt =$request->file('cover_image')->getClientOriginalName();
            //get just file name
            $fileName = pathinfo($fileNameWithExt, PATHINFO_FILENAME);
            //
            //get just ext
            $extension = $request->file('cover_image')->getClientOriginalExtension();
            //filename to store
            $fileNameToStore = $fileName.'_'.time().'.'.$extension;
            //upload image
            $path = $request->file('cover_image')->storeAs('public/cover_image', $fileNameToStore);
        }

        //update post
        $post = Post::find($id);
        $post->title = $request->input('title');
        $post->body = $request->input('body');
        if ($request->hasFile('cover_image')) {
            $post->cover_image = $fileNameToStore;
        }
        $post->save();

        return redirect('/posts')->with('success', 'Post Updated');
    }


    public function destroy($id)
    {
        $post = Post::find($id);
        //check for correct user
        if (auth()->user()->id !== $post->user_id ) {
            return redirect('/posts')->with('error', 'Unauthorized Page !');
        }
        
        if ($post->cover_image != 'noimage.png') {
            //delete image
            Storage::delete('public/cover_image/'.$post->cover_image);
        }
        $post->delete();
        return redirect('/posts')->with('success', 'Post Removed');
    }
}
