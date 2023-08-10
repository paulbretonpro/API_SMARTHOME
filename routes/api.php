<?php

use App\Http\Controllers\CaptorController;
use App\Http\Controllers\ExportController;
use App\Http\Controllers\SensorController;
use App\Http\Controllers\SyncController;
use App\Http\Controllers\WeatherController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/

Route::resource('sensor', SensorController::class);
Route::resource('weather', WeatherController::class);
Route::resource('captor', CaptorController::class);
Route::resource('export', ExportController::class, ['only' => ['index']]);
Route::resource('sync', SyncController::class, ['only' => ['index']]);
