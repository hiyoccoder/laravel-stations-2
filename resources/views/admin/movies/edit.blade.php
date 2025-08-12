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

    <form action="{{ route('admin.movies.update', $movie) }}" method="post">
        @csrf
        @method('patch')
        <div>
            <label for="title">映画タイトル:</label>
            <input type="text" id="title" name="title" value="{{ old('title', $movie->title) }}" />
        </div>

        <div>
            <label for="image_url">画像URL:</label>
            <input type="url" id="image_url" name="image_url" value="{{ old('image_url', $movie->image_url) }}" />
        </div>

        <div>
            <label for="published_year">公開年:</label>
            <input type="number" id="published_year" name="published_year" value="{{ old('published_year', $movie->published_year) }}" />
        </div>

        <div>
            <label for="is_showing">上映中かどうか:</label>
            <input type="checkbox" id="is_showing" name="is_showing" value="1"
                {{ old('is_showing', $movie->is_showing) ? 'checked' : '' }} />
        </div>

        <div>
            <label for="description">概要:</label>
            <textarea id="description" name="description" rows="5" cols="33">{{ old('description', $movie->description) }}</textarea>
        </div>

        <div>
            <label for="name">ジャンル:</label>
            <input type="text" id="name" name="genre" value="{{ old('name', $movie->genre->name) }}" />
        </div>

        <div class="button">
            <button type="submit">送信</button>
        </div>
    </form>
</body>

</html>