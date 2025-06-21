<?php

namespace App\Http\Controllers;
use App\Movies;

class MoviesController extends Controller
{
    public function getMovies()
    {
        $movies = Movies::all();
        return view('getMovies', ['movies' => $movies]);
    }
}