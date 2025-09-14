<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>reservations</title>
</head>

<body>
    <table>
        <tbody>
            <tr>
                <th>映画作品</th>
                <th>座席</th>
                <th>日時</th>
                <th>名前</th>
                <th>メールアドレス</th>
            </tr>
            @foreach ($reservations as $reservation)
            <tr>
                <td>{{ $reservation->schedule->movie->title }}</td>
                <td>{{ strtoupper($reservation->sheet->row) }}{{ $reservation->sheet->column }}</td>
                <td>{{ $reservation->date }}</td>
                <td>{{ $reservation->name }}</td>
                <td>{{ $reservation->email }}</td>
                <td>
                    <a href="{{ route('admin.reservations.edit', $reservation) }}">
                        <button>編集</button>
                    </a>
                </td>
                <td>
                    <form method="post" action="{{ route('admin.reservations.destroy', $reservation) }}">
                        @csrf
                        @method('delete')
                        <button>削除</button>
                    </form>
                </td>
            </tr>
            @endforeach
        </tbody>
    </table>
</body>

</html>