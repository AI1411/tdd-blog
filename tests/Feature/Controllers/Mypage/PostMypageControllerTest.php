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

        $this->get('mypage/posts')->assertRedirect($url);
        $this->get('mypage/posts/create')->assertRedirect($url);
        $this->post('mypage/posts/create', [])->assertRedirect($url);
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

    /**
     * @test create
     */
    public function 新規投稿ページを開ける()
    {
        $this->login();

        $this->get('mypage/posts/create')
            ->assertOk();
    }

    /**
    * @test store
    */
    public function 新規登録できる、公開の場合()
    {
        $this->login();
        $validData = Post::factory()->validData();

        $this->post('mypage/posts/create', $validData)
            ->assertRedirect('mypage/posts/edit/1');

        $this->assertDatabaseHas('posts', $validData);
    }

    /**
     * @test store
     */
    public function 新規登録できる、非公開の場合()
    {
        $this->login();
        $validData = Post::factory()->validData();

        unset($validData['status']);

        $this->post('mypage/posts/create', $validData)
            ->assertRedirect('mypage/posts/edit/1');

        $validData['status'] = 0;

        $this->assertDatabaseHas('posts', $validData);
    }

    /**
    * @test
    */
    public function 投稿登録時の入力チェック()
    {
        $url = 'mypage/posts/create';

        $this->login();

        $this->app->setLocale('testing');

        $this->post($url, ['title' => ''])->assertSessionHasErrors(['title' => 'required']);
        $this->post($url, ['title' => str_repeat('a', 256)])->assertSessionHasErrors(['title' => 'max']);
        $this->post($url, ['title' => str_repeat('a', 255)])->assertSessionDoesntHaveErrors(['title' => 'max']);
        $this->post($url, ['body' => ''])->assertSessionHasErrors(['body' => 'required']);

        $this->from($url)->post($url, [])->assertRedirect($url);
    }
}
