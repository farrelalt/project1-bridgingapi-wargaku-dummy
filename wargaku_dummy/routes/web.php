<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Mobile\AuthController;
use App\Http\Controllers\Mobile\DashboardController;
use App\Http\Controllers\Mobile\KeluhanController;
use App\Http\Controllers\Mobile\RatingController;

Route::get('/', function () {
    return redirect('/login');
});

Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
Route::post('/login', [AuthController::class, 'login'])->name('login.process');

Route::get('/register', [AuthController::class, 'showRegister'])->name('register');
Route::post('/register', [AuthController::class, 'register'])->name('register.process');

Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

Route::get('/keluhan', [KeluhanController::class, 'index'])->name('keluhan.index');
Route::get('/keluhan/create', [KeluhanController::class, 'create'])->name('keluhan.create');
Route::post('/keluhan', [KeluhanController::class, 'store'])->name('keluhan.store');
Route::get('/keluhan/{id}', [KeluhanController::class, 'show'])->name('keluhan.show');

Route::get('/keluhan/{id}/rating', [RatingController::class, 'create'])->name('rating.create');
Route::post('/keluhan/{id}/rating', [RatingController::class, 'store'])->name('rating.store');