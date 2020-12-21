<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Support\Facades\Hash;
use Tests\TestCase;

/**
 * @see \App\Http\Controllers\SignUpController
 * @package Tests\Feature
 */
class SignUpControllerTest extends TestCase
{
    use RefreshDatabase;
    /**
    * @test index
    */
    public function ユーザー登録画面を開ける()
    {
        $this->get('signup')
            ->assertOk();
    }

    /**
    * @test store
    */
    public function ユーザー登録できる()
    {
        //データ検証
        //DBに保存
        //ログインさせてからマイページにリダイレクト
        $validData = User::factory()->validData();

        $this->post('signup', $validData)
            ->assertRedirect('mypage/posts');

        unset($validData['password']);

        $this->assertDatabaseHas('users', $validData);

        //パスワードの検証
        $user = User::firstWhere($validData);

        $this->assertNotNull($user);
        $this->assertTrue(Hash::check('password', $user->password));

        $this->assertAuthenticatedAs($user);
    }

    /**
    * @test store
    */
    public function 不正なデータではユーザ登録できない()
    {
        $url = 'signup';

        $this->from($url)->post($url, [])->assertRedirect();

        $this->app->setLocale('testing');

        $this->post($url, ['name' => ''])->assertSessionHasErrors(['name' => 'required']);
        $this->post($url, ['name' => str_repeat('あ', 21)])->assertSessionHasErrors(['name' => 'max']);
        $this->post($url, ['name' => str_repeat('あ', 20)])->assertSessionDoesntHaveErrors('name');

        $this->post($url, ['email' => ''])->assertSessionHasErrors(['email' => 'required']);
        $this->post($url, ['email' => 'aa@dd@'])->assertSessionHasErrors(['email' => 'email']);
        $this->post($url, ['email' => 'ああ@dd@ファ'])->assertSessionHasErrors(['email' => 'email']);

        User::factory()->create(['email' => 'goethe0719@gmail.com']);
        $this->post($url, ['email' => 'goethe0719@gmail.com'])->assertSessionHasErrors(['email' => 'unique']);

        $this->post($url, ['password' => ''])->assertSessionHasErrors(['password' => 'required']);
        $this->post($url, ['password' => 'abcdef'])->assertSessionHasErrors(['password' => 'min']);
        $this->post($url, ['password' => 'password'])->assertSessionDoesntHaveErrors(['password']);
    }
}
