<ul>
    @foreach($posts as $post)
        <li><a href="{{ route('post.show', $post->id) }}">{{ $post->title }}</a> {{ $post->user->name }} （{{ $post->comments_count }}件のコメント）</li>
    @endforeach
</ul>
