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
                <td>{{ $post->title }}</td>
            </tr>
        @endforeach
    </table>
@endsection
