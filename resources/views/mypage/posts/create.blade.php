@extends('layouts.index')

@section('content')
    <h1>新規投稿</h1>

    <form method="post">
        @csrf
        @include('inc.error')

        タイトル： <input type="text" name="title" style="width: 400px" value="{{ old('title') }}">

        <br>
        本文： <textarea name="body" style="width: 600px" id="" cols="30" rows="10">{{ old('body') }}</textarea>

        <br>

        公開する: <label for=""><input type="checkbox" name="status" value="1" {{ old('status') ? 'checked' : '' }}>公開する</label>

        <br><br>
        <input type="submit" value="送信する">
    </form>
@endsection
