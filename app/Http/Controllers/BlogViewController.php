<?php

namespace App\Http\Controllers;

use App\Models\Post;

class BlogViewController extends Controller
{
    public function index()
    {
//        $posts = Post::all();
        $posts = Post::with('user')
            ->onlyOpen()
            ->withCount('comments')
            ->orderByDesc('comments_count')
            ->get();

        return view('posts.index', compact('posts'));
    }

    public function show(Post $post)
    {
        if ($post->isClosed()) {
            abort(403);
        }
        return view('posts.show', compact('post'));
    }
}
