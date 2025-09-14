<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\PracticeController;
use App\Http\Controllers\MovieController;
use App\Http\Controllers\AdminMovieController;
use App\Http\Controllers\SheetController;
use App\Http\Controllers\AdminReservationsController;

Route::get('/practice', [PracticeController::class, 'sample'])->name('practice');
Route::get('/practice2', [PracticeController::class, 'sample2'])->name('practice2');
Route::get('/practice3', [PracticeController::class, 'sample3'])->name('practice3');
Route::get('/getPractice', [PracticeController::class, 'getPractice'])->name('getPractice');

Route::get('/movies', [MovieController::class, 'getMovies'])->name('movies.index');
Route::get('/movies/{id}', [MovieController::class, 'getMoviesDetail'])->name('movies.detail');

// ✅ 修正：固定のルートを先に定義
Route::get('/admin/movies', [AdminMovieController::class, 'AdminMovies'])->name('admin.movies.index');
Route::get('/admin/movies/create', [AdminMovieController::class, 'AdminMoviesCreate'])->name('admin.movies.create');
Route::post('/admin/movies/store', [AdminMovieController::class, 'AdminMoviesStore'])->name('admin.movies.store');
Route::get('/admin/movies/{id}/edit', [AdminMovieController::class, 'AdminMoviesEdit'])->name('admin.movies.edit');
Route::patch('/admin/movies/{id}/update', [AdminMovieController::class, 'AdminMoviesUpdate'])->name('admin.movies.update');
Route::delete('/admin/movies/{id}/destroy', [AdminMovieController::class, 'AdminMoviesDestroy'])->name('admin.movies.destroy');
Route::get('/admin/movies/{id}', [AdminMovieController::class, 'getAdminMoviesDetail'])->name('admin.movies.detail');
Route::get('/admin/movies/{id}', [AdminMovieController::class, 'getAdminMoviesDetail'])->name('admin.movies.show');

Route::get('/admin/schedules', [AdminMovieController::class, 'AdminSchedules'])->name('admin.schedules');
Route::get('/admin/movies/{id}/schedules/create', [AdminMovieController::class, 'AdminSchedulesCreate'])->name('admin.schedules.create');
Route::post('/admin/movies/{id}/schedules/store', [AdminMovieController::class, 'AdminSchedulesStore'])->name('admin.schedules.store');
Route::get('/admin/schedules/{scheduleId}/edit', [AdminMovieController::class, 'AdminSchedulesEdit'])->name('admin.schedules.edit');
Route::patch('/admin/schedules/{id}/update', [AdminMovieController::class, 'AdminSchedulesUpdate'])->name('admin.schedules.update');
Route::delete('/admin/schedules/{scheduleId}/destroy', [AdminMovieController::class, 'AdminSchedulesDestroy'])->name('admin.schedules.destroy');
Route::get('/admin/schedules/{id}', [AdminMovieController::class, 'getAdminSchedulesDetail'])->name('admin.schedules.detail');
Route::get('/sheets', [SheetController::class, 'getSheets'])->name('sheets.index');
Route::get('/movies/{movie_id}/schedules/{schedule_id}/sheets', [SheetController::class, 'moviesSchedulesSheets'])->name('movies.schedules.sheets');
Route::get('/movies/{movie_id}/schedules/{schedule_id}/reservations/create', [SheetController::class, 'moviesSchedulesReservationsCreate'])->name('movies.schedules.reservations.create');
Route::post('/reservations/store', [SheetController::class, 'reservationsStore'])->name('reservations.store');

Route::get('/admin/reservations', [AdminReservationsController::class, 'index'])->name('admin.reservations.index');
Route::get('/admin/reservations/create', [AdminReservationsController::class, 'create'])->name('admin.reservations.create');
Route::post('/admin/reservations', [AdminReservationsController::class, 'store'])->name('admin.reservations.store');
Route::get('/admin/reservations/{id}/edit', [AdminReservationsController::class, 'edit'])->name('admin.reservations.edit');
Route::patch('/admin/reservations/{id}', [AdminReservationsController::class, 'update'])->name('admin.reservations.update');
Route::delete('/admin/reservations/{id}', [AdminReservationsController::class, 'destroy'])->name('admin.reservations.destroy');
