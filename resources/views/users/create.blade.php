<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>ユーザ登録</title>
</head>
<body>
    <h1>ユーザ登録</h1>

    @if ($errors->any())
        <div style="color: red;">
            <ul>
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <form method="POST" action="{{ route('users.store') }}">
        @csrf

        <div>
            <label for="name">名前:</label>
            <input type="text" id="name" name="name" value="{{ old('name') }}" required>
        </div>

        <div>
            <label for="email">メールアドレス:</label>
            <input type="email" id="email" name="email" value="{{ old('email') }}" required>
        </div>

        <div>
            <label for="password">パスワード:</label>
            <input type="password" id="password" name="password" required>
        </div>

        <div>
            <label for="password_confirmation">確認用パスワード:</label>
            <input type="password" id="password_confirmation" name="password_confirmation" required>
        </div>

        <div>
            <button type="submit">登録</button>
        </div>
    </form>
</body>
</html>