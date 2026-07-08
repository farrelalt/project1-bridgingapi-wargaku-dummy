<?php

namespace App\Http\Controllers\Api\V2;

use App\Http\Controllers\Controller;
use App\Models\ApiConfig;
use App\Helpers\ApiResponse;

class ApiConfigController extends Controller
{
    public function index()
    {
        $configs = ApiConfig::orderBy('id', 'asc')->get();

        return response()->json([
            'success' => true,
            'source' => 'bridging_api',
            'message' => 'Data konfigurasi endpoint API berhasil diambil',
            'data' => $configs,
        ], 200);
    }

    public function show($id)
    {
        $config = ApiConfig::find($id);

        if (!$config) {
            return response()->json([
                'success' => false,
                'source' => 'bridging_api',
                'message' => 'Konfigurasi endpoint tidak ditemukan',
                'data' => null,
            ], 404);
        }

        return ApiResponse::fromServiceResult($result);
    }
}