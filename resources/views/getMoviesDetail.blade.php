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
        <li>タイトル: {{ $movie->title }}</li>
        <li>
            <img src="{{ $movie->image_url }}" alt="{{ $movie->title }}">
        </li>
        <li>公開年: {{ $movie->published_year }}</li>
        <li>上映中かどうか: {{ $movie->is_showing_text }}</li>
        <li>概要: {{ $movie->description }}</li>
        <li>ジャンル: {{ $movie->genre->name }}</li>
        <li>登録日時: {{ $movie->created_at }}</li>
        <li>更新日時: {{ $movie->updated_at }}</li>
    </ul>
    <p>上映スケジュール</p>
    <table border="1">
        <thead>
            <tr>
                <th>上映開始時刻</th>
                <th>上映終了時刻</th>
                <th>座席予約</th>
            </tr>
        </thead>
        <tbody>
            @foreach($schedules as $schedule)
            <tr>
                <td>{{ $schedule->start_time }}</td>
                <td>{{ $schedule->end_time }}</td>
                <td>
                    <a href="{{ route('movies.schedules.sheets',[$movie->id, $schedule->id,'date' => now()->format('Y-m-d'),]) }}">
                        <button>座席を予約する</button>
                    </a>
                </td>
            </tr>
            @endforeach
        </tbody>
    </table>
</body>

</html>