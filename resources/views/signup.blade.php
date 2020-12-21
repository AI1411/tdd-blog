<h1>ユーザー登録</h1>

<form action="" method="POST">
    @csrf
    @include('inc.error')
    名前: <input type="text" name="name" value="{{ old('name') }}">
    <br>
    メールアドレス: <input type="email" name="email" value="{{ old('email') }}">
    <br>
    パスワード: <input type="password" name="password" value="{{ old('password') }}">
    <br><br>
    <input type="submit" value="　送信する　">
</form>
