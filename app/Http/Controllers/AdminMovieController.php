<?php

namespace App\Http\Controllers;

use App\Models\Movie;
use App\Models\Genre;
use App\Models\Schedule;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

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

    public function getAdminMoviesDetail($id)
    {
        $movie = Movie::findOrFail($id);

        // その映画の上映スケジュールを取得（アソシエーション使わない）
        $schedules = Schedule::where('movie_id', $id)
            ->orderBy('start_time', 'asc') // 上映開始時刻の昇順
            ->get();
        return view('admin.movies.getAdminMoviesDetail', compact('movie', 'schedules'));
    }


    public function AdminSchedules(Request $request)
    {
        $movies = Movie::with('schedules')->get();
        return view('admin.schedules', compact('movies'));
    }

    public function AdminSchedulesCreate($id)
    {
        $movie = Movie::findOrFail($id);
        $schedule = new Schedule();
        return view('admin.schedules.create', compact('schedule', 'movie'));
    }

    public function AdminSchedulesStore(Request $request, $id)
    {
        $validated = $request->validate([
            'movie_id' => 'required|exists:movies,id',
            'start_time_date' => 'required|date_format:Y-m-d',    // ← 厳密なフォーマット指定
            'start_time_time' => 'required|date_format:H:i',
            'end_time_date' => 'required|date_format:Y-m-d',      // ← 厳密なフォーマット指定
            'end_time_time' => 'required|date_format:H:i',
        ]);

        try {
            DB::transaction(function () use ($validated) {
                $newStartTime = Carbon::createFromFormat(
                    'Y-m-d H:i',
                    $validated['start_time_date'] . ' ' . $validated['start_time_time'],
                    'Asia/Tokyo'
                );
                $newEndTime = Carbon::createFromFormat(
                    'Y-m-d H:i',
                    $validated['end_time_date'] . ' ' . $validated['end_time_time'],
                    'Asia/Tokyo'
                );

                Schedule::create([
                    'movie_id' => $validated['movie_id'],
                    'start_time' => $newStartTime,
                    'end_time' => $newEndTime,
                ]);
            });
            return redirect('/admin/schedules');
        } catch (\Exception $e) {
            throw $e; // 500エラー
        }
    }

    public function AdminSchedulesEdit($id)
    {
        $schedule = Schedule::findOrFail($id);

        // start_timeとend_timeを分割
        $schedule->start_time_date = $schedule->start_time ? $schedule->start_time->format('Y-m-d') : '';
        $schedule->start_time_time = $schedule->start_time ? $schedule->start_time->format('H:i') : '';
        $schedule->end_time_date = $schedule->end_time ? $schedule->end_time->format('Y-m-d') : '';
        $schedule->end_time_time = $schedule->end_time ? $schedule->end_time->format('H:i') : '';

        return view('admin.schedules.edit', compact('schedule'));
    }

    public function AdminSchedulesUpdate(Request $request, $id)
    {
        $schedule = Schedule::findOrFail($id);

        $validated = $request->validate([
            'movie_id' => 'required|exists:movies,id',  // ← 追加
            'start_time_date' => 'required|date_format:Y-m-d',    // ← 厳密なフォーマット指定
            'start_time_time' => 'required|date_format:H:i',
            'end_time_date' => 'required|date_format:Y-m-d',      // ← 厳密なフォーマット指定
            'end_time_time' => 'required|date_format:H:i',
        ]);

        try {
            DB::transaction(function () use ($validated, $schedule) {
                $newStartTime = Carbon::createFromFormat(
                    'Y-m-d H:i',
                    $validated['start_time_date'] . ' ' . $validated['start_time_time'],
                    'Asia/Tokyo'
                );
                $newEndTime = Carbon::createFromFormat(
                    'Y-m-d H:i',
                    $validated['end_time_date'] . ' ' . $validated['end_time_time'],
                    'Asia/Tokyo'
                );

                $schedule->update([
                    'movie_id' => $validated['movie_id'],  // ← movie_idも更新
                    'start_time' => $newStartTime,
                    'end_time' => $newEndTime,
                ]);
            });

            return redirect('/admin/schedules');
        } catch (\Exception $e) {
            throw $e; // 500エラー
        }
    }

    public function AdminSchedulesDestroy(Request $request)
    {
        $id = $request->route('scheduleId');

        $schedule = Schedule::findOrFail($id); // 存在しない場合は自動で404

        try {
            $schedule->delete();
            return redirect('/admin/schedules');
        } catch (\Exception $e) {
            throw $e; // 500エラー
        }
    }

    public function getAdminSchedulesDetail($id)
    {
        // withでリレーション先のMovieも一緒に取得
        $schedule = Schedule::with('movie')->findOrFail($id);

        return view('admin.schedules.getAdminSchedulesDetail', compact('schedule'));
    }
}
