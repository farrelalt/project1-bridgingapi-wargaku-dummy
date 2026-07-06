<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\ProfileController;
use App\Http\Controllers\Api\MasterDataController;
use App\Http\Controllers\Api\KeluhanController;
use App\Http\Controllers\Api\TanggapanController;
use App\Http\Controllers\Api\RatingController;

Route::get('/test', function () {
    return response()->json([
        'success' => true,
        'source' => 'dummy_media_center',
        'message' => 'Media Center Dummy API jalan',
    ]);
});

Route::post('/login', [AuthController::class, 'login']);
Route::post('/register', [AuthController::class, 'register']);
Route::get('/profile', [ProfileController::class, 'profile']);

Route::get('/kategori', [MasterDataController::class, 'kategori']);
Route::get('/chanel', [MasterDataController::class, 'chanel']);
Route::get('/kecamatan', [MasterDataController::class, 'kecamatan']);
Route::get('/kelurahan/{id_kec}', [MasterDataController::class, 'kelurahan']);
Route::get('/topik', [MasterDataController::class, 'topik']);
Route::get('/status', [MasterDataController::class, 'status']);
Route::post('/instansi', [MasterDataController::class, 'instansi']);

Route::post('/keluhan_create', [KeluhanController::class, 'create']);
Route::post('/keluhan', [KeluhanController::class, 'index']);
Route::get('/keluhan_detail/{id}', [KeluhanController::class, 'detail']);
Route::post('/keluhan_selesai', [KeluhanController::class, 'selesai']);
Route::post('/keluhan_hapus', [KeluhanController::class, 'hapus']);

Route::post('/tanggapan_create', [TanggapanController::class, 'create']);
Route::post('/tanggapan', [TanggapanController::class, 'index']);

Route::post('/keluhan_rating', [RatingController::class, 'create']);
Route::get('/view_keluhan_rating', [RatingController::class, 'view']);