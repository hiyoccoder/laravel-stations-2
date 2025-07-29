<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>movies</title>
</head>

<body>
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
</body>

</html>