<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>movies</title>
</head>

<body>
    @if ($errors->any())
    <div style="color: red;">
        エラーが出ています
    </div>
    @endif

    <form action="{{ route('admin.schedules.store', ['id' => $movie->id]) }}" method="post">
        @csrf
        <div>
            <label for="start_time_date">開始日付</label>
            <input type="date" id="start_time_date" name="start_time_date" value="{{ old('start_time_date') }}" />
        </div>

        <div>
            <label for="start_time_time">開始時間</label>
            <input type="time" id="start_time_time" name="start_time_time" value="{{ old('start_time_time') }}" />
        </div>

        <div>
            <label for="end_time_date">終了日付</label>
            <input type="date" id="end_time_date" name="end_time_date" value="{{ old('end_time_date') }}" />
        </div>

        <div>
            <label for="end_time_time">終了時間</label>
            <input type="time" id="end_time_time" name="end_time_time" value="{{ old('end_time_time') }}" />
        </div>

        <div class="button">
            <button type="submit">送信</button>
        </div>
    </form>
</body>

</html>