<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\SensorController;
use App\Http\Controllers\SystemOverviewController;
use App\Http\Controllers\SimulationController;

Route::middleware('api')->group(function () {
    Route::get('sensors', [SensorController::class, 'index']);
    Route::post('sensors', [SensorController::class, 'store']);
    Route::put('sensors/{sensor}', [SensorController::class, 'update']);
    Route::delete('sensors/{sensor}', [SensorController::class, 'destroy']);
    Route::get('/dashboard-stats', [SystemOverviewController::class, 'getStats']);
    Route::get('/simulation/status', [SimulationController::class, 'getStatus']);
});