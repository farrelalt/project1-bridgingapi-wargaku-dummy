<?php

namespace App\Http\Controllers\Api\V2;

use App\Helpers\ApiResponse;
use App\Http\Controllers\Controller;
use App\Models\ApiConfig;
use App\Models\ApiLog;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Http;

class MonitoringController extends Controller
{
    public function health()
    {
        $databaseConnected = true;

        $database = [
            'status' => 'connected',
            'is_connected' => true,
            'connected' => true,
            'driver' => config('database.default'),
            'message' => 'Database berhasil terhubung',
        ];

        try {
            DB::connection()->getPdo();
        } catch (\Throwable $e) {
            $databaseConnected = false;

            $database = [
                'status' => 'disconnected',
                'is_connected' => false,
                'connected' => false,
                'driver' => config('database.default'),
                'message' => $e->getMessage(),
            ];
        }

        $mediaCenterBaseUrl = rtrim(env('MEDIA_CENTER_BASE_URL', 'http://127.0.0.1:8002/api'), '/');

        $mediaCenter = [
            'status' => 'unreachable',
            'is_reachable' => false,
            'reachable' => false,
            'base_url' => $mediaCenterBaseUrl,
            'http_status' => null,
            'message' => 'Media Center belum berhasil dicek',
        ];

        try {
            $response = Http::timeout(5)
                ->acceptJson()
                ->get($mediaCenterBaseUrl . '/test');

            $mediaCenterReachable = $response->successful();

            $mediaCenter = [
                'status' => $mediaCenterReachable ? 'reachable' : 'unreachable',
                'is_reachable' => $mediaCenterReachable,
                'reachable' => $mediaCenterReachable,
                'base_url' => $mediaCenterBaseUrl,
                'http_status' => $response->status(),
                'message' => $mediaCenterReachable
                    ? 'Media Center berhasil dihubungi'
                    : 'Media Center memberi response gagal',
            ];
      } catch (\Throwable $e) {
            $mediaCenter = [
                'status' => 'unreachable',
                'is_reachable' => false,
                'reachable' => false,
                'base_url' => $mediaCenterBaseUrl,
                'http_status' => null,
                'message' => 'Media Center tidak dapat dihubungi.',
                'error_detail' => $e->getMessage(),
            ];
        }

        return ApiResponse::success(
            data: [
                'bridging_api' => [
                    'status' => 'running',
                    'is_running' => true,
                    'app_name' => config('app.name'),
                    'app_url' => config('app.url'),
                    'timezone' => config('app.timezone'),
                    'timestamp' => now('Asia/Jakarta')->format('Y-m-d H:i:s'),
                ],
                'database' => $database,
                'media_center' => $mediaCenter,
            ],
            message: 'Health check selesai',
            target: null
        );
    }

    public function summary()
    {
        $totalConfigs = ApiConfig::count();
        $activeConfigs = ApiConfig::where('status', 'active')->count();
        $inactiveConfigs = ApiConfig::where('status', 'inactive')->count();
        $maintenanceConfigs = ApiConfig::where('status', 'maintenance')->count();
        $restrictedConfigs = ApiConfig::where('is_restricted', true)->count();

        $totalLogs = ApiLog::count();
        $successLogs = ApiLog::where('is_success', true)->count();
        $failedLogs = ApiLog::where('is_success', false)->count();

        $latestLogs = ApiLog::latest()
            ->limit(5)
            ->get()
            ->map(function ($log) {
                return [
                    'id' => $log->id,
                    'service_name' => $log->service_name,
                    'local_endpoint' => $log->local_endpoint,
                    'target_endpoint' => $log->target_endpoint,
                    'method' => $log->method,
                    'status_code' => $log->status_code,
                    'is_success' => (bool) $log->is_success,
                    'created_at' => $log->created_at
                        ? $log->created_at->timezone('Asia/Jakarta')->format('Y-m-d H:i:s')
                        : null,
                ];
            });

        return ApiResponse::success(
            data: [
                'api_configs' => [
                    'total' => $totalConfigs,
                    'active' => $activeConfigs,
                    'inactive' => $inactiveConfigs,
                    'maintenance' => $maintenanceConfigs,
                    'restricted' => $restrictedConfigs,
                ],
                'api_logs' => [
                    'total' => $totalLogs,
                    'success' => $successLogs,
                    'failed' => $failedLogs,
                ],
                'latest_logs' => $latestLogs,
                'generated_at' => now('Asia/Jakarta')->format('Y-m-d H:i:s'),
            ],
            message: 'Summary monitoring berhasil diambil',
            target: null
        );
    }
}