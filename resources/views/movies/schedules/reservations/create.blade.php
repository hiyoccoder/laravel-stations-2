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
        <p><strong>映画:</strong> {{ $movie->movie_id }}</p>
        <p><strong>上映スケジュール:</strong> {{ $schedule->schedule_id }}</p>
        <p><strong>座席番号:</strong> {{ $sheets }}</p>
        <p><strong>日付:</strong></p>
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
        <input type="hidden" name="schedule_id" value="{{ $schedule->id }}" />
        <input type="hidden" name="sheet_id" value="{{ request('sheetId') }}" />
        <input type="hidden" name="date" value="{{ request('date') }}" />

        <div>
            <label for="name">予約者氏名:</label>
            <input type="text" id="name" name="name" value="{{ old('name') }}" />
        </div>

        <div>
            <label for="email">予約者メールアドレス:</label>
            <input type="email" id="email" name="email" value="{{ old('email') }}" />
        </div>

        <div class="button">
            <button type="submit">送信</button>
        </div>
    </form>
</body>

</html>