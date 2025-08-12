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
        $genres = Genre::orderBy('name')->get();
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
            'genre' => 'required|string|max:255',
        ]);

        try {
            DB::transaction(function () use ($validated, $request) {
                $genre = Genre::firstOrCreate(['name' => $validated['genre']]);

                Movie::create([
                    'title' => $validated['title'],
                    'image_url' => $validated['image_url'],
                    'published_year' => $validated['published_year'],
                    'is_showing' => $request->boolean('is_showing'),
                    'description' => $validated['description'],
                    'genre_id' => $genre->id,
                ]);
            });

            return redirect('/admin/movies');
        } catch (\Exception $e) {
            throw $e; // 500エラー
        }
    }

    public function AdminMoviesEdit($id)
    {
        $movie = Movie::with('genre')->findOrFail($id);
        $genres = Genre::orderBy('name')->get();
        return view('admin.movies.edit', compact('movie', 'genres'));
    }

    public function AdminMoviesUpdate(Request $request, $id)
    {
        $movie = Movie::findOrFail($id);

        $validated = $request->validate([
            'title' => 'required|unique:movies,title,' . $id,
            'image_url' => 'required|url',
            'published_year' => 'required',
            'is_showing' => 'boolean',
            'description' => 'required',
            'genre' => 'required|string|max:255',
        ]);

        try {
            DB::transaction(function () use ($validated, $request, $movie) {
                $genre = Genre::firstOrCreate(['name' => $validated['genre']]);

                $movie->update([
                    'title' => $validated['title'],
                    'image_url' => $validated['image_url'],
                    'published_year' => $validated['published_year'],
                    'is_showing' => $request->boolean('is_showing'),
                    'description' => $validated['description'],
                    'genre_id' => $genre->id,
                ]);
            });

            return redirect('/admin/movies');
        } catch (\Exception $e) {
            throw $e; // 500エラー
        }
    }

    public function AdminMoviesDestroy(Request $request)
    {
        $id = $request->route('id');

        $movie = Movie::findOrFail($id); // 存在しない場合は自動で404

        try {
            $movie->delete();
            return redirect('/admin/movies');
        } catch (\Exception $e) {
            throw $e; // 500エラー
        }
    }
}
