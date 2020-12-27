@extends('layouts.index')

@section('content')
    <h1>投稿一覧</h1>

    <a href="/mypage/posts/create">新規投稿</a>
    <hr>

    <table>
        <tr>
            <th>投稿名</th>
        </tr>

        @foreach($posts as $post)
            <tr>
                <td>
                    <a href="{{ route('mypage.posts.edit', $post) }}">{{ $post->title }}</a>
                </td>
                <td>
                    <form action="{{ route('mypage.posts.delete', $post) }}" method="post">
                        @csrf
                        @method('DELETE')
                        <input type="submit" value="削除">
                    </form>
                </td>
            </tr>
        @endforeach
    </table>
@endsection
