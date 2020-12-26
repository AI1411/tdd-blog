<?php

namespace Tests\Feature\Controllers\Mypage;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Validation\ValidationException;
use Tests\TestCase;

class UserLoginControllerTest extends TestCase
{
    use RefreshDatabase;
    /**
     * @test \App\Http\Controllers\Controller\UserLoginController
     */
    public function ログイン画面を開ける()
    {
        $this->get('mypage/login')
            ->assertOk();
    }

    /**
     * @test
     */
    public function ログイン時の入力チェック()
    {
        $url = 'mypage/login';

        $this->app->setLocale('testing');

//        $this->from($url)->post($url, [
//
//        ])->assertRedirect($url);
        $this->post($url, ['email' => ''])->assertSessionHasErrors(['email' => 'required']);
        $this->post($url, ['email' => 'da@vda@@'])->assertSessionHasErrors(['email' => 'email']);
        $this->post($url, ['email' => 'da@だだ@@'])->assertSessionHasErrors(['email' => 'email']);
        $this->post($url, ['password' => ''])->assertSessionHasErrors(['password' => 'required']);
    }

    /**
    * @test login
    */
    public function ログインできる()
    {
        $postData = [
            'email' => 'akira@gmail.com',
            'password' => 'password'
        ];

        $dbData = [
            'email' => 'akira@gmail.com',
            'password' => bcrypt('password')
        ];

        $user = User::factory()->create($dbData);

        $this->post('mypage/login', $postData)
            ->assertRedirect('mypage/posts');

        $this->assertAuthenticatedAs($user);
    }

    /**
    * @test
    */
    function IDを間違えているのでログインできない()
    {
        $postData = [
            'email' => 'aaa@bbb.net',
            'password' => 'abcd1234',
        ];

        $dbData = [
            'email' => 'ccc@bbb.net',
            'password' => bcrypt('abcd1234'),
        ];

        $user = User::factory()->create($dbData);

        $url = 'mypage/login';

        $this->from($url)->post($url, $postData)
            ->assertRedirect($url);

        $this->from($url)->followingRedirects()->post($url, $postData)
            ->assertSee('<h1>ログイン画面</h1>', false);
    }


    /**
     * @test
     */
    function パスワードを間違えているのでログインできない()
    {
        $postData = [
            'email' => 'aaa@bbb.net',
            'password' => 'abcd1234',
        ];

        $dbData = [
            'email' => 'aaa@bbb.net',
            'password' => bcrypt('abcd5678'),
        ];

        $user = User::factory()->create($dbData);

        $url = 'mypage/login';

        $this->from($url)->post($url, $postData)
            ->assertRedirect($url);


        $this->from($url)->followingRedirects()->post($url, $postData)
            ->assertSee('<h1>ログイン画面</h1>', false);
    }

    /**
    * @test
    */
    public function 認証エラーなのでValidationExceptionが発生する()
    {
        $this->withoutExceptionHandling();
        $postData = [
            'email' => 'akira@gmail.com',
            'password' => 'password'
        ];

//        $dbData = [
//            'email' => 'akira@gmail.com',
//            'password' => bcrypt('password')
//        ];
//
//        $user = User::factory()->create($dbData);
//        $this->expectException(ValidationException::class);

        try {
            $this->post('mypage/login', $postData)
                ->assertRedirect('mypage/posts');
            $this->fail('validationExceptionの例外が発生しませんでした。');
        } catch (ValidationException $exception) {
            $this->assertEquals('メールアドレスかパスワードが間違っています。',
            $exception->errors()['email'][0] ?? ''
            );
        }
    }

    /**
    * @test
    */
    public function 認証OkでvalidationExceptionの例外が出ない()
    {
        $postData = [
            'email' => 'akira@gmail.com',
            'password' => 'password'
        ];

        $dbData = [
            'email' => 'akira@gmail.com',
            'password' => bcrypt('password')
        ];

        $user = User::factory()->create($dbData);

        try {
            $this->post('mypage/login', $postData);
            $this->assertTrue(true);
        } catch(ValidationException $e) {
            $this->fail('validationExceptionの例外が発生しました。');
        }
    }

    /**
    * @test logout
    */
    public function ログアウトできる()
    {
        $this->login();

        $this->post('mypage/logout')
            ->assertRedirect($url = 'mypage/login');

        $this->get($url)->assertSee('ログアウトしました');

        $this->assertGuest();
    }
}
