<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>movies</title>
</head>

<body>
    @foreach ($movies as $movie)
    <h2>ID: {{ $movie->id }}</h2>
    <h2>タイトル: {{ $movie->title }}</h2>
    <p>上映スケジュール</p>
    @if($movie->schedules->count() > 0)
    <table border="1">
        <thead>
            <tr>
                <th>上映開始時刻</th>
                <th>上映終了時刻</th>
            </tr>
        </thead>
        <tbody>
            {{-- ここが重要：映画ごとのスケジュールをループ --}}
            @foreach($movie->schedules as $schedule)
            <tr>
                <td>{{ $schedule->start_time }}</td>
                <td>{{ $schedule->end_time }}</td>
            </tr>
            @endforeach
        </tbody>
    </table>
    <a href="{{ route('admin.movies.detail', $movie) }}">
        <button>リンク</button>
    </a>
    @else
    <p>スケジュールがありません</p>
    @endif
    @endforeach
</body>

</html>