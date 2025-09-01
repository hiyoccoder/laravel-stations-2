<?php

namespace App\Http\Controllers;

use App\Models\Sheet;
use App\Models\Schedule;
use App\Models\Movie;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class SheetController extends Controller
{
    public function getSheets()
    {
        $sheets = Sheet::all();
        return view('getSheets', ['sheets' => $sheets]);
    }
    public function moviesSchedulesSheets(Request $request, $movie_id, $schedule_id)
    {
        $sheets = Sheet::all();
        $movie = Movie::findOrFail($movie_id);
        $schedule = Schedule::findOrFail($schedule_id);
        return view('movies.schedules.sheets', compact('sheets', 'movie', 'schedule'));
    }
    public function moviesSchedulesReservationsCreate(Request $request, $movie_id, $schedule_id)
    {
        $sheets = Sheet::all();
        $movie = Movie::findOrFail($movie_id);
        $schedule = Schedule::findOrFail($schedule_id);
        return view('movies.schedules.reservations.create', compact('sheets', 'movie', 'schedule'));
    }
    public function ReservationsStore(Request $request, $id)
    {
        $validated = $request->validated();

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
                    'start_time' => $newStartTime->format('Y-m-d H:i:s'),
                    'end_time' => $newEndTime->format('Y-m-d H:i:s'),
                ]);
            });

            return redirect('/admin/schedules')->with('success', 'スケジュールが正常に作成されました。');
        } catch (\Exception $e) {
            return back()->withErrors(['error' => 'スケジュールの作成中にエラーが発生しました。'])
                ->withInput();
        }
    }
}
