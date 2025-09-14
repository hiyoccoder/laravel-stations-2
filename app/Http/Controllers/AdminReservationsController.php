<?php

namespace App\Http\Controllers;

use App\Models\Sheet;
use App\Models\Schedule;
use App\Models\Movie;
use Illuminate\Http\Request;
use App\Models\Reservation;
use App\Http\Requests\CreateReservationRequest;

class AdminReservationsController extends Controller
{
    public function index()
    {
        $reservations = Reservation::with(['schedule', 'schedule.movie', 'sheet'])
            ->whereHas('schedule', function ($query) {
                $query->where('end_time', '>', now());
            })->get();


        return view('admin.reservations.index', compact('reservations'));
    }

    public function detail(Request $request, $movie_id, $schedule_id)
    {
        if (!$request->has('date')) {
            abort(400, 'date is required');
        }
        $sheets = Sheet::all();
        $movie = Movie::findOrFail($movie_id);
        $schedule = Schedule::findOrFail($schedule_id);

        $reservations = Reservation::where('schedule_id', $schedule_id)
            ->where('date', $request->input('date'))
            ->get();
        return view('movies.schedules.sheets', compact('sheets', 'movie', 'schedule', 'reservations'));
    }

    public function create(Request $request, $movie_id, $schedule_id)
    {
        if (!$request->has('date') || !$request->has('sheetId')) {
            abort(400, 'date is required');
        }

        // 既に予約が存在するかチェック  
        $requestDate = \Carbon\Carbon::parse($request->input('date'))->format('Y-m-d');
        $existingReservation = Reservation::where('schedule_id', $schedule_id)
            ->where('sheet_id', $request->input('sheetId'))
            ->whereDate('date', $requestDate)
            ->first();

        if ($existingReservation) {
            abort(400, 'この座席は既に予約済みです');
        }

        $sheets = Sheet::all();
        $movie = Movie::findOrFail($movie_id);
        $schedule = Schedule::findOrFail($schedule_id);
        return view('movies.schedules.reservations.create', compact('sheets', 'movie', 'schedule'));
    }

    public function store(CreateReservationRequest $request)
    {
        $validated = $request->validated();

        // 重複予約チェック（アプリケーションレベル）
        $existingReservation = Reservation::where('schedule_id', $validated['schedule_id'])
            ->where('sheet_id', $validated['sheet_id'])
            ->where('date', $validated['date'])
            ->first();

        if ($existingReservation) {
            $schedule = Schedule::find($validated['schedule_id']);
            $movie = Movie::find($schedule->movie_id);

            return redirect("/movies/{$movie->id}/schedules/{$validated['schedule_id']}/sheets?date={$validated['date']}")
                ->with('error', 'その座席はすでに予約済みです');
        }

        try {
            Reservation::create([
                'schedule_id' => $validated['schedule_id'],
                'sheet_id' => $validated['sheet_id'],
                'name' => $validated['name'],
                'email' => $validated['email'],
                'date' => $validated['date'],
            ]);

            $schedule = Schedule::find($validated['schedule_id']);
            $movie = Movie::find($schedule->movie_id);

            return redirect("/movies/{$movie->id}")
                ->with('success', '予約が完了しました');
        } catch (\Exception $e) {
            return back()->withErrors(['error' => 'An error occurred while creating the reservation.'])
                ->withInput();
        }
    }

    public function update(Request $request, $id)
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

    public function destory(Request $request)
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
