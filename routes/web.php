<?php

use App\Http\Controllers\NonRacikanController;
use App\Http\Controllers\RacikanController;
use Illuminate\Support\Facades\Route;

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

Route::get('/', [NonRacikanController::class, 'index']);

Route::get('non-racikan', [NonRacikanController::class, 'index']);
Route::post('non-racikan/store', [NonRacikanController::class, 'store']);
Route::get('racikan', [RacikanController::class, 'index']);
Route::get('racikan/show', [RacikanController::class, 'show']);
Route::post('racikan', [RacikanController::class, 'store']);
