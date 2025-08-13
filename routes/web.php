<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\PracticeController;
use App\Http\Controllers\MovieController;
use App\Http\Controllers\AdminMovieController;
use App\Http\Controllers\SheetController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/practice', [PracticeController::class, 'sample'])->name('practice');
Route::get('/practice2', [PracticeController::class, 'sample2'])->name('practice2');
Route::get('/practice3', [PracticeController::class, 'sample3'])->name('practice3');
Route::get('/getPractice', [PracticeController::class, 'getPractice'])->name('getPractice');
Route::get('/movies', [MovieController::class, 'getMovies'])->name('movies.index');
Route::get('/movies/{id}', [MovieController::class, 'getMoviesDetail'])->name('movies.deatil');
Route::get('/admin/movies', [AdminMovieController::class, 'AdminMovies'])->name('admin.movies.index');
Route::get('/admin/movies/create', [AdminMovieController::class, 'AdminMoviesCreate'])->name('admin.movies.create');
Route::post('/admin/movies/store', [AdminMovieController::class, 'AdminMoviesStore'])->name('admin.movies.store');
Route::get('/admin/movies/{id}/edit', [AdminMovieController::class, 'AdminMoviesEdit'])->name('admin.movies.edit');
Route::patch('/admin/movies/{id}/update', [AdminMovieController::class, 'AdminMoviesUpdate'])->name('admin.movies.update');
Route::delete('/admin/movies/{id}/destroy', [AdminMovieController::class, 'AdminMoviesDestroy'])->name('admin.movies.destroy');
Route::get('/sheets', [SheetController::class, 'getSheets'])->name('sheets.index');
