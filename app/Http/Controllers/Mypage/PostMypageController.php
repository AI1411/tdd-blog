<?php

namespace App\Http\Controllers\Mypage;

use App\Http\Controllers\Controller;

class PostMypageController extends Controller
{
    public function index()
    {
        return view('mypage.posts.index');
    }
}
