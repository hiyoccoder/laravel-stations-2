<?php

namespace App\Http\Controllers;

use App\Models\Movie;
use App\Models\Schedule;
use Illuminate\Http\Request;

class MovieController extends Controller
{
    public function getMovies(Request $request)
    {
        $query = Movie::query();

        if ($request->has('keyword') && $request->keyword) {
            $query->where(function ($query) use ($request) {
                $query->where('title', 'like', '%' . $request->keyword . '%')
                    ->orWhere('description', 'like', '%' . $request->keyword . '%');
            });
        }

        if ($request->has('is_showing') && $request->is_showing !== 'all') {
            $query->where('is_showing', $request->is_showing);
        }

        $movies = $query->paginate(20);

        return view('getMovies', ['movies' => $movies]);
    }

    public function getMoviesDetail($id)
    {
        $movie = Movie::findOrFail($id);

        // その映画の上映スケジュールを取得（アソシエーション使わない）
        $schedules = Schedule::where('movie_id', $id)
            ->orderBy('start_time', 'asc') // 上映開始時刻の昇順
            ->get();
        return view('getMoviesDetail', compact('movie', 'schedules'));
    }
}
