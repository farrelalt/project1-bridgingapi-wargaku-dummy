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

    Route::get('/api-configs', [ApiConfigAdminController::class, 'index'])->name('api-configs.index');
    Route::get('/api-configs/{id}/edit', [ApiConfigAdminController::class, 'edit'])->name('api-configs.edit');
    Route::put('/api-configs/{id}', [ApiConfigAdminController::class, 'update'])->name('api-configs.update');

    Route::get('/api-logs', [ApiLogAdminController::class, 'index'])->name('api-logs.index');
    Route::get('/api-logs/{id}', [ApiLogAdminController::class, 'show'])->name('api-logs.show');
});