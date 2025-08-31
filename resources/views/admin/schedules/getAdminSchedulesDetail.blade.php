<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>movies</title>
</head>

<body>
    <p>上映スケジュール</p>
    <table border="1">
        <thead>
            <tr>
                <th>上映開始時刻</th>
                <th>上映終了時刻</th>
            </tr>
        </thead>
        <tbody>
            <tr>
                <td>{{ $schedule->start_time }}</td>
                <td>{{ $schedule->end_time }}</td>
            </tr>
        </tbody>
    </table>
    <a href="{{ route('admin.schedules.edit', $schedule->id) }}">
        <button>編集</button>
    </a>
    <form method="post" action="{{ route('admin.schedules.destroy', $schedule->id) }}">
        @csrf
        @method('delete')
        <button>削除</button>
    </form>
</body>

</html>