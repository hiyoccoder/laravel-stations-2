<?php

namespace App\Http\Controllers;

use App\Models\Sheet;
use App\Models\Schedule;
use App\Models\Movie;
use Illuminate\Http\Request;
use App\Models\Reservation;
use App\Http\Requests\CreateReservationRequest;
use Illuminate\Support\Facades\DB;

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

    public function create(Request $request)
    {
        // パラメータがある場合は特定の予約作成画面、ない場合は一般的な作成画面
        if ($request->has('date') && $request->has('sheetId') && $request->has('movieId') && $request->has('scheduleId')) {
            // 既に予約が存在するかチェック  
            $requestDate = \Carbon\Carbon::parse($request->input('date'))->format('Y-m-d');
            $existingReservation = Reservation::where('schedule_id', $request->input('scheduleId'))
                ->where('sheet_id', $request->input('sheetId'))
                ->whereDate('date', $requestDate)
                ->first();

            if ($existingReservation) {
                abort(400, 'この座席は既に予約済みです');
            }

            $sheets = Sheet::all();
            $movie = Movie::findOrFail($request->input('movieId'));
            $schedule = Schedule::findOrFail($request->input('scheduleId'));
            return view('admin.reservations.create', compact('sheets', 'movie', 'schedule'));
        }

        // パラメータなしの場合は基本的な作成フォームを表示
        $sheets = Sheet::all();
        $movie = Movie::first() ?? new Movie(); // 最初の映画またはダミー
        $schedule = Schedule::first() ?? new Schedule(); // 最初のスケジュールまたはダミー
        return view('admin.reservations.create', compact('sheets', 'movie', 'schedule'));
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

            return redirect("/admin/reservations/")
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

    public function edit($id)
    {
        $reservation = Reservation::findOrFail($id);
        return view('admin.reservations.edit', compact('reservation'));
    }

    public function update(Request $request, $id)
    {
        $reservation = Reservation::findOrFail($id);

        $validated = $request->validate([
            'movie_id' => ['required'],
            'schedule_id' => ['required'],
            'sheet_id' => ['required'],
            'name' => ['required'],
            'email' => ['required', 'email'],
            'date' => ['required', 'date_format:Y-m-d']
        ]);

        try {
            DB::transaction(function () use ($validated, $reservation) {
                // 重複チェック
                $duplicateReservation = Reservation::where('schedule_id', $validated['schedule_id'])
                    ->where('sheet_id', $validated['sheet_id'])
                    ->whereDate('date', $validated['date'])
                    ->where('id', '!=', $reservation->id)
                    ->where('is_canceled', false)
                    ->first();

                if ($duplicateReservation) {
                    throw new \Exception('指定された座席は既に予約されています。');
                }

                $reservation->update($validated);
            });
            return redirect('/admin/reservations/');
        } catch (\Exception $e) {
            return redirect('/admin/reservations/')->withErrors(['error' => $e->getMessage()]);
        }
    }

    public function destroy(Request $request, $id)
    {
        $id = $request->route('id');

        $reservation = Reservation::findOrFail($id); // 存在しない場合は自動で404

        try {
            $reservation->delete();
            return redirect('/admin/reservations');
        } catch (\Exception $e) {
            throw $e; // 500エラー
        }
    }
}
