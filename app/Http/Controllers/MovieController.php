<?php

namespace App\Http\Controllers;

use App\Movie;

class MovieController extends Controller
{
    public function getMovies()
    {
        $movies = Movie::all();
        return view('getMovies', ['movies' => $movies]);
    }
}
