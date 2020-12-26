<?php

namespace Tests\Feature\Controllers\Mypage;

use App\Models\Post;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

/**
 * @see \App\Http\Controllers\Mypage\PostMypageController
 */
class PostMypageControllerTest extends TestCase
{
    use RefreshDatabase;

    /**
    * @test
    */
    public function ゲストは投稿を管理できない()
    {
        $url = 'mypage/login';

        $this->get('mypage/posts')
            ->assertRedirect($url);
    }
    /**
    * @test index
    */
    public function マイページ、投稿一覧で自分のデータのみ表示される()
    {
        //認証している場合
        $user = $this->login();

        $other = Post::factory()->create();
        $myPost = Post::factory()->create(['user_id' => $user]);

        $this->get('mypage/posts')
            ->assertOk()
            ->assertDontSee($other->title)
            ->assertSee($myPost->title);
    }
}
