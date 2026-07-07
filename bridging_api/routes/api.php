<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\V2\AuthController;
use App\Http\Controllers\Api\V2\ApiLogController;
use App\Http\Controllers\Api\V2\ProfileController;
use App\Http\Controllers\Api\V2\MasterDataController;
use App\Http\Controllers\Api\V2\KeluhanController;
use App\Http\Controllers\Api\V2\TanggapanController;
use App\Http\Controllers\Api\V2\RatingController;
use App\Http\Controllers\Api\V2\ApiConfigController;
use App\Http\Controllers\Api\V2\MonitoringController;
use App\Http\Controllers\Api\V2\DynamicForwardController;

Route::prefix('v2')->middleware('restricted.endpoint')->group(function () {
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

    
    Route::post('/keluhan/create', [KeluhanController::class, 'create']);
    Route::post('/keluhan', [KeluhanController::class, 'index']);
    Route::post('/keluhan/selesai', [KeluhanController::class, 'selesai']);
    Route::post('/keluhan/hapus', [KeluhanController::class, 'hapus']);

    Route::post('/keluhan/rating', [RatingController::class, 'store']);
    Route::get('/keluhan/rating', [RatingController::class, 'show']);   

    Route::get('/keluhan/{id}', [KeluhanController::class, 'detail']);

    Route::post('/tanggapan/create', [TanggapanController::class, 'create']);
    Route::post('/tanggapan', [TanggapanController::class, 'index']);

    Route::get('/logs', [ApiLogController::class, 'index']);
    Route::get('/logs/{id}', [ApiLogController::class, 'show']);

    Route::get('/configs', [ApiConfigController::class, 'index']);
    Route::get('/configs/{id}', [ApiConfigController::class, 'show']);

    Route::get('/monitoring/summary', [MonitoringController::class, 'summary']);
    Route::get('/health', [MonitoringController::class, 'health']);

    Route::match(['GET', 'POST', 'PUT', 'PATCH', 'DELETE'], '/{any}', [DynamicForwardController::class, 'handle'])
    ->where('any', '.*');
});
