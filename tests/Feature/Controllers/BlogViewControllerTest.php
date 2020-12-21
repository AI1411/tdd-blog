<?php

namespace Tests\Feature\Controllers;

use App\Models\Comment;
use App\Models\Post;
use Carbon\Carbon;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class BlogViewControllerTest extends TestCase
{
    use RefreshDatabase;

    /**
     * @test index
     */
    public function トップページ表示テスト()
    {
        $blog1 = Post::factory()->hasComments(1)->create();
        $blog2 = Post::factory()->hasComments(2)->create();
        $blog3 = Post::factory()->hasComments(3)->create();

        $this->get('/')
            ->assertOk()
            ->assertSee($blog1->title)
            ->assertSee($blog2->title)
            ->assertSee($blog3->title)
            ->assertSee($blog1->user->name)
            ->assertSee($blog2->user->name)
            ->assertSee($blog3->user->name)
            ->assertSee("（1件のコメント）")
            ->assertSee("（2件のコメント）")
            ->assertSee("（3件のコメント）")
            ->assertSeeInOrder([$blog3->title, $blog2->title, $blog1->title]);
    }

    /**
     * @test
     */
    public function 詳細画面の表示し、コメントが古い順に表示される()
    {
        $post = Post::factory()->withCommentsData([
            ['created_at' => now()->sub('2 days'), 'name' => 'akira'],
            ['created_at' => now()->sub('3 days'), 'name' => 'ishii'],
            ['created_at' => now()->sub('1 days'), 'name' => 'sakura']
        ])->create();

        $this->get('posts/' . $post->id)
            ->assertOk()
            ->assertSee($post->title)
            ->assertSeeInOrder(['ishii', 'akira', 'sakura']);
    }

    /**
     * @test
     */
    public function 非公開のものは詳細画面を表示しない()
    {
        $post = Post::factory()->closed()->create();

        $this->get('posts/' . $post->id)
            ->assertForbidden();
    }

    /**
     * @test
     */
    public function クリスマスはメリークリスマスと表示する()
    {
        $post = Post::factory()->create();

        Carbon::setTestNow('2020-12-24');

        $this->get('posts/' . $post->id)
            ->assertOk()
            ->assertDontSee('メリークリスマス！');

        Carbon::setTestNow('2020-12-25');

        $this->get('posts/' . $post->id)
            ->assertOk()
            ->assertSee('メリークリスマス！');
    }

    /**
     * @test
     */
    public function 公開の投稿のみ表示()
    {
        Post::factory()->create([
            'status' => Post::CLOSED,
            'title' => 'ブログA'
        ]);

        Post::factory()->create(['title' => 'ブログB']);
        Post::factory()->create(['title' => 'ブログC']);

        $this->get('/')
            ->assertOk()
            ->assertDontSee('ブログA')
            ->assertSee('ブログB')
            ->assertSee('ブログC');

    }
}
