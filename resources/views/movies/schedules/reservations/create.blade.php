<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>movies</title>
</head>

<body>
    <div class="reservation-info">
        <p><strong>映画:</strong> {{ $movie->title }}</p>
        <p><strong>上映スケジュール:</strong> {{ $schedule->start_time }} - {{ $schedule->end_time }}</p>
        <p><strong>座席番号:</strong> {{ request('sheetId') }}</p>
        <p><strong>日付:</strong> {{ request('date') }}</p>
        <p><strong>予約者名:</strong> {{ auth()->user()->name }}</p>
        <p><strong>メールアドレス:</strong> {{ auth()->user()->email }}</p>
    </div>

    @if ($errors->any())
    <div style="color: red;">
        <strong>エラーが出ています:</strong>
        <ul>
            @foreach ($errors->all() as $error)
            <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>
    @endif

    <form action="{{ route('reservations.store') }}" method="post">
        @csrf
        <input type="hidden" name="movie_id" value="{{ $movie->id }}" />
        <input type="hidden" name="schedule_id" value="{{ $schedule->id }}" />
        <input type="hidden" name="sheet_id" value="{{ request('sheetId') ?: '1' }}" />
        <input type="hidden" name="date" value="{{ request('date') ?: now()->format('Y-m-d') }}" />


        <div class="button">
            <button type="submit">送信</button>
        </div>
    </form>
</body>

</html>