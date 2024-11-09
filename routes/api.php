<?php

use App\Http\Controllers\GroupController;
use App\Http\Controllers\IdolController;
use App\Http\Controllers\RandomIdolController;
use App\Http\Controllers\ScheduleController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::middleware(['auth:sanctum'])->get('/user', function (Request $request) {
    return $request->user();
});

Route::get('idols/spotlight', RandomIdolController::class);
Route::apiResource('idols', IdolController::class)->only(['index', 'show']);

Route::apiResource('groups', GroupController::class)->only(['index', 'show']);

Route::apiResource('schedules', ScheduleController::class)->only(['index', 'show']);
