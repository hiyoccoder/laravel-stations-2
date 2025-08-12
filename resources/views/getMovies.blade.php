<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>movies</title>
</head>

<body>
    <form method="GET" action="{{ route('movies.index') }}">
        <input type="text" name="keyword" value="{{ request('keyword') }}" placeholder="キーワードを入力">
        <input type="radio" name="is_showing" value="all" {{ request('is_showing', 'all') === 'all' ? 'checked' : '' }}> すべて
        <input type="radio" name="is_showing" value="0" {{ request('is_showing') === '0' ? 'checked' : '' }}> 公開予定
        <input type="radio" name="is_showing" value="1" {{ request('is_showing') === '1' ? 'checked' : '' }}> 公開中

        <button type="submit" class="search-button">検索</button>
    </form>
    <ul>
        @foreach ($movies as $movie)
        <li>タイトル: {{ $movie->title }}</li>
        <li>画像URL: {{ $movie->image_url }}</li>
        <li>公開年: {{ $movie->published_year }}</li>
        <li>上映中かどうか: {{ $movie->is_showing_text }}</li>
        <li>概要: {{ $movie->description }}</li>
        <li>登録日時: {{ $movie->created_at }}</li>
        <li>更新日時: {{ $movie->updated_at }}</li>
        @endforeach
    </ul>
    {{ $movies->appends(request()->query())->links() }}
</body>

</html>