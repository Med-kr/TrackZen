<?php

use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\StatsController;
use App\Http\Controllers\Api\ZenController;
use App\Http\Controllers\Api\ZenLogController;
use Illuminate\Support\Facades\Route;

Route::post('/register', [AuthController::class, 'register']);
Route::post('/login', [AuthController::class, 'login']);

Route::middleware('auth:sanctum')->group(function () {
    Route::post('/logout', [AuthController::class, 'logout']);
    Route::get('/me', [AuthController::class, 'me']);

    Route::get('/habits', [ZenController::class, 'index']);
    Route::post('/habits', [ZenController::class, 'store']);
    Route::get('/habits/{id}', [ZenController::class, 'show']);
    Route::put('/habits/{id}', [ZenController::class, 'update']);
    Route::delete('/habits/{id}', [ZenController::class, 'destroy']);

    Route::get('/habits/{id}/logs', [ZenLogController::class, 'index']);
    Route::post('/habits/{id}/logs', [ZenLogController::class, 'store']);
    Route::delete('/habits/{id}/logs/{logId}', [ZenLogController::class, 'destroy']);
    Route::get('/habits/{id}/stats', [StatsController::class, 'habit']);

    Route::get('/stats/overview', [StatsController::class, 'overview']);
});

