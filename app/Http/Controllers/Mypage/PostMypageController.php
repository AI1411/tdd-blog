<?php

namespace App\Http\Controllers\Mypage;

use App\Http\Controllers\Controller;
use App\Models\Post;
use Illuminate\Http\Request;

class PostMypageController extends Controller
{
    public function index()
    {
        $posts = auth()->user()->posts;

        return view('mypage.posts.index', compact('posts'));
    }

    public function create()
    {
        return view('mypage.posts.create');
    }

    public function store(Request $request)
    {
        $data = $this->validateInput();

        $data['status'] = $request->boolean('status');

        $post = auth()->user()->posts()->create($data);

        return redirect('mypage/posts/edit/' . $post->id);
    }

    public function edit(Post $post, Request $request)
    {
        if ($request->user()->isNot($post->user)) {
            abort(403);
        }
        $data = old() ?: $post;

        return view('mypage.posts.edit', compact('post', 'data'));
    }

    public function update(Post $post, Request $request)
    {
        if ($request->user()->isNot($post->user)) {
            abort(403);
        }
        $data = $this->validateInput();

        $data['status'] = $request->boolean('status');

        $post->update($data);

        return redirect()->route('mypage.posts.edit', $post)->with('status', 'ブログを更新しました');
    }

    private function validateInput()
    {
        return request()->validate([
            'title' => ['required','max:255'],
            'body' => ['required']
        ]);
    }
}
