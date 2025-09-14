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

    <form action="{{ route('admin.reservations.update', $reservation) }}" method="put">
        @csrf
        @method('patch')
        <div>
            <label for="name">予約者氏名:</label>
            <input type="text" id="name" name="name" value="{{ old('name', $reservation->name ) }}" />
        </div>

        <div>
            <label for="email">予約者メールアドレス:</label>
            <input type="email" id="email" name="email" value="{{ old('email', $reservation->email ) }}" />
        </div>


        <div>
            <label for="title">映画作品:</label>
            <input type="text" id="title" name="title" value="{{ old('title', $reservation->schedule->movie->title) }}" />
        </div>

        <div>
            <label for="sheet_id">座席:</label>
            <input type="text" id="sheet_id" name="sheet_id" value="{{ old('sheet_id', request('sheetId') ?: '1') }}" />
        </div>

        <div>
            <label for="date">日時:</label>
            <input type="date" id="date" name="date" value="{{ old('date', $reservation->date) }}" />
        </div>

        <div class="button">
            <button type="submit">送信</button>
        </div>
    </form>
</body>

</html>