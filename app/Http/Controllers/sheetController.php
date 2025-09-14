<?php

namespace App\Http\Controllers;

use App\Models\Sheet;
use App\Models\Schedule;
use App\Models\Movie;
use Illuminate\Http\Request;
use App\Models\Reservation;
use App\Http\Requests\StoreReservationRequest;

class SheetController extends Controller
{
    public function getSheets()
    {
        $sheets = Sheet::all();
        return view('getSheets', ['sheets' => $sheets]);
    }

    public function moviesSchedulesSheets(Request $request, $movie_id, $schedule_id)
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

    public function moviesSchedulesReservationsCreate(Request $request, $movie_id, $schedule_id)
    {
        // ログインチェック
        if (!auth()->check()) {
            return redirect('/users/create')->with('error', 'ログインが必要です。');
        }

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

    public function reservationsStore(StoreReservationRequest $request)
    {
        // ログインチェック
        if (!auth()->check()) {
            return redirect('/users/create')->with('error', 'ログインが必要です。');
        }

        $validated = $request->validated();
        $user = auth()->user();

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
                'user_id' => $user->id,
                'name' => $user->name,
                'email' => $user->email,
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
}
