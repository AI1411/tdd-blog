<?php

namespace Tests\Feature\Controllers\Mypage;

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
    * @test index
    */
    public function 認証している場合に限りマイページを開ける()
    {
        //認証していない場合
        $this->get('mypage/posts')
            ->assertRedirect('mypage/login');
        //認証している場合
        $this->login();

        $this->get('mypage/posts')->assertOk();
    }
}
