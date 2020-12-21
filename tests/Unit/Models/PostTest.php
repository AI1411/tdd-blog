<?php

namespace Tests\Unit\Models;

use App\Models\Post;
use App\Models\User;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class PostTest extends TestCase
{
    use RefreshDatabase;

    public function test_Userリレーションを返す()
    {
        $post = Post::factory()->create();

        $this->assertInstanceOf(User::class, $post->user);
    }

    public function test_コメントリレーションを返す()
    {
        $post = Post::factory()->create();

        $this->assertInstanceOf(Collection::class, $post->comments);
    }

    public function test_公開の投稿のみ表示する()
    {
        $post1 = Post::factory()->closed()->create([
            'status' => Post::CLOSED,
            'title' => 'ブログA'
        ]);

        $post2 = Post::factory()->create(['title' => 'ブログB']);
        $post3 = Post::factory()->create(['title' => 'ブログC']);

        $posts = Post::onlyOpen()->get();

        $this->assertFalse($posts->contains($post1));
        $this->assertTrue($posts->contains($post2));
        $this->assertTrue($posts->contains($post3));
    }

    /*
     * @test isClosed()
     */
    public function test_公開時はTrue非公開時はFalse()
    {
        $post = Post::factory()->make();

        $this->assertFalse($post->isClosed());

        $post = Post::factory()->closed()->make();

        $this->assertTrue($post->isClosed());
    }
}
