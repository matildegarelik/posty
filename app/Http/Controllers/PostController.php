<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Post;

class PostController extends Controller
{
    public function __construct()
    {
        $this->middleware(['auth'])->only(['store', 'destroy']);
    }
    public function index(){
        //$posts = Post::get(); //Object of type Laravel Collection
        //$posts = Post::paginate(20); //Object of LengthAwarePaginator that contains a collection within it
        $posts = Post::latest()->with(['user', 'likes'])->paginate(20); //La linea de abajo hace exactamente lo mismo
        //$posts = Post::orderBy('created_at', 'desc')->with(['user', 'likes'])->paginate(20);
        return view('posts.index', [
            'posts' => $posts
        ]);
    }
    public function store(Request $request){
        $this->validate($request, [
            'body'=> 'required'
        ]);
        $request->user()->posts()->create([
            //automatically sets user_id
            'body'=> $request->body
        ]);
        return back();
    }
    public function destroy(Post $post){
        $this->authorize('delete', $post);
        $post->delete();
        return back();
    }
    public function show(Post $post){
        return view('posts.show', ['post'=>$post]);
    }
}
