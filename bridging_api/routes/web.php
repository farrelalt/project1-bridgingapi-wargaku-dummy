<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Admin\ApiConfigAdminController;
use App\Http\Controllers\Admin\ApiLogAdminController;

Route::get('/', function () {
    return redirect()->route('admin.dashboard');
});

Route::prefix('admin')->name('admin.')->group(function () {
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

    Route::get('/api-configs', [ApiConfigAdminController::class, 'index'])
        ->name('api-configs.index');

    Route::get('/api-configs/create', [ApiConfigAdminController::class, 'create'])
        ->name('api-configs.create');

    Route::post('/api-configs', [ApiConfigAdminController::class, 'store'])
        ->name('api-configs.store');

    Route::get('/api-configs/{id}/edit', [ApiConfigAdminController::class, 'edit'])
        ->name('api-configs.edit');
        
    Route::put('/api-configs/{id}', [ApiConfigAdminController::class, 'update'])
        ->name('api-configs.update');

    Route::delete('/api-configs/{id}', [ApiConfigAdminController::class, 'destroy'])
    ->name('api-configs.destroy');

    Route::get('/api-logs', [ApiLogAdminController::class, 'index'])->name('api-logs.index');

    Route::delete('/api-logs/clear/failed', [ApiLogAdminController::class, 'clearFailed'])
    ->name('api-logs.clear-failed');

    Route::delete('/api-logs/clear/all', [ApiLogAdminController::class, 'clearAll'])
    ->name('api-logs.clear-all');

    Route::get('/api-logs/live-data', [ApiLogAdminController::class, 'liveData'])
    ->name('api-logs.live-data');
    
    Route::get('/api-logs/{id}', [ApiLogAdminController::class, 'show'])->name('api-logs.show');
});