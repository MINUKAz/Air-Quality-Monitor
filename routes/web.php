<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\SensorController;
use App\Http\Controllers\SystemOverviewController;
use App\Http\Controllers\SimulationController;
use App\Http\Controllers\MapController;
use App\Http\Controllers\AlertController;
use App\Http\Controllers\HistoricalDataController;
use App\Http\Controllers\UserManagementController;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;

// Replace the existing root route with this:
Route::get('/', function () {
    return redirect()->route('system-overview');
})->middleware('auth');

Route::middleware(['auth', \App\Http\Middleware\AdminMiddleware::class])->group(function () {
    Route::get('/system-overview', [SystemOverviewController::class, 'index'])
        ->name('system-overview');

    // Change this route to use a view directly
    Route::get('/sensor-management', function () {
        return view('sensor-management');
    })->name('sensor-management');

    Route::get('/sensors', [SensorController::class, 'index']);
    Route::post('/sensors', [SensorController::class, 'store']);
    Route::put('/sensors/{sensor}', [SensorController::class, 'update']);
    Route::delete('/sensors/{sensor}', [SensorController::class, 'destroy']);

    Route::get('/simulation', [SimulationController::class, 'index'])
        ->name('simulation');
    Route::prefix('simulation')->group(function(){
        Route::patch('/config', [SimulationController::class, 'updateConfig']);
        Route::post('/start', [SimulationController::class, 'start']);
        Route::post('/stop', [SimulationController::class, 'stop']);
        Route::post('/update-aqi', [SimulationController::class, 'updateAQI']); // Add this line
    });

    // Placeholder routes - implement these controllers later
    Route::view('/map', 'map')->name('map');
    Route::view('/historical-data', 'historical-data')->name('historical-data');
    Route::view('/simulation', 'simulation')->name('simulation');
    Route::view('/alerts', 'alerts')->name('alerts');

    Route::get('/map', [MapController::class, 'index'])->name('map');
    Route::get('/api/sensors', [MapController::class, 'getSensors']);

    Route::get('/alerts', [AlertController::class, 'index'])->name('alerts');

    // User Management routes
    Route::get('/user-management', [UserManagementController::class, 'index'])->name('user-management');

    Route::prefix('api')->group(function () {
        Route::get('/users', [UserManagementController::class, 'getUsers']);
        Route::post('/users', [UserManagementController::class, 'store']);
        Route::get('/users/{user}', [UserManagementController::class, 'show']);
        Route::put('/users/{user}', [UserManagementController::class, 'update']);
        Route::delete('/users/{user}', [UserManagementController::class, 'destroy']);
        Route::patch('/users/{user}/status', [UserManagementController::class, 'updateStatus']);

        Route::get('/sensors', [SensorController::class, 'index']);
        Route::post('/sensors', [SensorController::class, 'store']);
        Route::put('/sensors/{sensor}', [SensorController::class, 'update']);
        Route::delete('/sensors/{sensor}', [SensorController::class, 'destroy']);
    });
});

Route::middleware(['auth'])->group(function () {
    Route::get('/dashboard', function () {
        return view('dashboard');
    })->name('dashboard');
    
    Route::get('/map', [MapController::class, 'index'])->name('map');
    Route::get('/historical-data', [HistoricalDataController::class, 'index'])->name('historical-data');
    Route::get('/user-management', [UserManagementController::class, 'index'])->name('user-management');
    Route::get('/simulation', [SimulationController::class, 'index'])->name('simulation');
    Route::get('/alerts', [AlertController::class, 'index'])->name('alerts');
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    // Add these inside your auth middleware group
    Route::get('/simulation', [SimulationController::class, 'index'])
        ->name('simulation');

    Route::prefix('simulation')->group(function(){
        Route::patch('/config', [SimulationController::class, 'updateConfig']);
        Route::post('/start', [SimulationController::class, 'start']);
        Route::post('/stop', [SimulationController::class, 'stop']);
        Route::post('/update-aqi', [SimulationController::class, 'updateAQI']); // Add this line
    });

    // Add these inside your auth middleware group
    Route::get('/map', [MapController::class, 'index'])->name('map');
    Route::get('/api/sensors', [MapController::class, 'getSensors']);

    Route::get('/historical-data', [HistoricalDataController::class, 'index'])->name('historical-data');
    Route::get('/api/historical-data/{timeRange}', [HistoricalDataController::class, 'getData']);
    Route::get('/api/historical-data/{timeRange}/export', [HistoricalDataController::class, 'export']);

    Route::prefix('api')->group(function () {
        Route::get('/users', [UserManagementController::class, 'getUsers']);
        Route::post('/users', [UserManagementController::class, 'store']);
        Route::delete('/users/{user}', [UserManagementController::class, 'destroy'])->middleware('auth:sanctum');
        Route::put('/users/{user}', [UserManagementController::class, 'update']);
        Route::patch('/users/{user}/status', [UserManagementController::class, 'updateStatus']);
    });
});

require __DIR__.'/auth.php';