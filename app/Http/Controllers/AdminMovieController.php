<?php

namespace App\Http\Controllers;

use App\Models\Movie;
use Illuminate\Http\Request;

class AdminMovieController extends Controller
{

    public function AdminMovies()
    {
        $movies = Movie::all();
        return view('admin.movies', ['movies' => $movies]);
    }

    public function AdminMoviesCreate()
    {
        return view('admin.movies.create');
    }

    public function AdminMoviesStore(Request $request)
    {
        $validated = $request->validate([
            'title' => 'required|unique:movies,title',
            'image_url' => 'required|url',
            'published_year' => 'required',
            'is_showing' => 'boolean',
            'description' => 'required',
        ]);

        $movie = new Movie();

        $movie->title = $validated['title'];
        $movie->image_url = $validated['image_url'];
        $movie->published_year = $validated['published_year'];
        $movie->is_showing = $request->has('is_showing');
        $movie->description = $validated['description'];

        // データベースに保存
        $movie->save();

        return redirect('/admin/movies')->with('success', '映画が正常に登録されました。');
    }

    public function AdminMoviesEdit($id)
    {
        $movie = Movie::findOrFail($id);
        return view('admin.movies.edit', compact('movie'));
    }

    public function AdminMoviesUpdate(Request $request, $id)
    {
        $movie = Movie::find($id);

        $validated = $request->validate([
            'title' => 'required|unique:movies,title',
            'image_url' => 'required|url',
            'published_year' => 'required',
            'is_showing' => 'boolean',
            'description' => 'required',
        ]);

        $movie->update($validated);

        return redirect('/admin/movies')->with('success', '映画が正常に更新されました。');
    }
}
