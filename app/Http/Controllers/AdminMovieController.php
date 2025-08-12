<?php

namespace App\Http\Controllers;

use App\Models\Movie;
use App\Models\Genre;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class AdminMovieController extends Controller
{

    public function AdminMovies()
    {
        $movies = Movie::with('genre')->get();
        return view('admin.movies', ['movies' => $movies]);
    }

    public function AdminMoviesCreate()
    {
        $genres = Genre::orderBy('genre_name')->get();
        return view('admin.movies.create', compact('genres'));
    }

    public function AdminMoviesStore(Request $request)
    {
        $validated = $request->validate([
            'title' => 'required|unique:movies,title',
            'image_url' => 'required|url',
            'published_year' => 'required',
            'is_showing' => 'boolean',
            'description' => 'required',
            'genre_name' => 'required|string|max:255', // ジャンル必須
        ]);

        try {
            $movie = DB::transaction(function () use ($validated, $request) {
                // ジャンルの登録
                $genre = Genre::firstOrCreate(
                    ['genre_name' => $validated['genre_name']]
                );

                // 映画の作成
                $movie = new Movie();
                $movie->title = $validated['title'];
                $movie->image_url = $validated['image_url'];
                $movie->published_year = $validated['published_year'];
                $movie->is_showing = $request->has('is_showing');
                $movie->description = $validated['description'];
                $movie->genre_id = $genre->id; // 外部キーを設定

                $movie->save();

                return $movie;
            });
            return redirect('/admin/movies')->with('success', '映画が正常に登録されました。');
        } catch (\Exception $e) {
            return redirect()->back()
                ->withInput()
                ->with('error', '映画の登録に失敗しました。: ' . $e->getMessage());
        }
    }

    public function AdminMoviesEdit($id)
    {
        $movie = Movie::with('genre')->findOrFail($id);
        $genres = Genre::orderBy('genre_name')->get();
        return view('admin.movies.edit', compact('movie', 'genres'));
    }

    public function AdminMoviesUpdate(Request $request, $id)
    {
        $movie = Movie::find($id);

        $validated = $request->validate([
            'title' => 'required|unique:movies,title,' . $id,
            'image_url' => 'required|url',
            'published_year' => 'required',
            'is_showing' => 'boolean',
            'description' => 'required',
            'genre_name' => 'required|string|max:255',
        ]);

        try {
            $updatedMovie = DB::transaction(function () use ($validated, $request, $movie) {
                // ジャンルの取得または新規作成
                $genre = Genre::firstOrCreate(
                    ['genre_name' => $validated['genre_name']]
                );

                // 映画の更新
                $movie->title = $validated['title'];
                $movie->image_url = $validated['image_url'];
                $movie->published_year = $validated['published_year'];
                $movie->is_showing = $request->has('is_showing');
                $movie->description = $validated['description'];
                $movie->genre_id = $genre->id;

                $movie->save();

                return $movie;
            });

            return redirect('/admin/movies')->with('success', '映画が正常に更新されました。');
        } catch (\Exception $e) {
            return redirect()->back()
                ->withInput()
                ->with('error', '映画の更新に失敗しました。: ' . $e->getMessage());
        }
    }

    public function AdminMoviesDestroy(Request $request)
    {
        $id = $request->route('id');

        try {
            $movie = Movie::findOrFail($id);
            $movie->delete();

            return redirect('/admin/movies')->with('success', '映画が正常に削除されました。');
        } catch (\Exception $e) {
            return redirect('/admin/movies')->with('error', '映画の削除に失敗しました。');
        }
    }
}
