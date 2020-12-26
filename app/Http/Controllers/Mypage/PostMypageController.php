<?php

namespace App\Http\Controllers\Mypage;

use App\Http\Controllers\Controller;
use App\Models\Post;

class PostMypageController extends Controller
{
    public function index()
    {
        $posts = auth()->user()->posts;

        return view('mypage.posts.index', compact('posts'));
    }
}
