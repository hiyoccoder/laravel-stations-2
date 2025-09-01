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
        エラーが出ています
    </div>
    @endif

    <form action="{{ route('reservations.store') }}" method="post">
        @csrf
        <div>
            <label for="reservations_name">予約者氏名:</label>
            <input type="text" id="reservations_name" name="reservations_name" value="{{ old('reservations_name') }}" />
        </div>

        <div>
            <label for="reservations_mail">予約者メールアドレス:</label>
            <input type="url" id="reservations_mail" name="reservations_mail" value="{{ old('reservations_mail') }}" />
        </div>

        <div class="button">
            <button type="submit">送信</button>
        </div>
    </form>
</body>

</html>