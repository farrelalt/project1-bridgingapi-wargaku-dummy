<?php

namespace App\Http\Controllers\Api\V2;

use App\Http\Controllers\Controller;
use App\Models\ApiLog;
use App\Helpers\ApiResponse;

class ApiLogController extends Controller
{
    public function index()
    {
        $logs = ApiLog::latest()
            ->limit(20)
            ->get();

        return response()->json([
            'success' => true,
            'source' => 'bridging_api',
            'message' => 'Data log request API berhasil diambil',
            'data' => $logs,
        ], 200);
    }

    public function show($id)
    {
        $log = ApiLog::find($id);

        if (!$log) {
            return response()->json([
                'success' => false,
                'source' => 'bridging_api',
                'message' => 'Log tidak ditemukan',
                'data' => null,
            ], 404);
        }

       return ApiResponse::fromServiceResult($result);
    }
}