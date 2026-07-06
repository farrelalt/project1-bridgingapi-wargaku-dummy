<?php

namespace App\Http\Controllers\Api\V2;

use App\Http\Controllers\Controller;
use App\Models\ApiConfig;
use App\Models\ApiLog;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Http;

class MonitoringController extends Controller
{
    public function summary()
    {
        $totalConfigs = ApiConfig::count();
        $activeConfigs = ApiConfig::where('status', 'active')->count();

        $totalLogs = ApiLog::count();
        $successLogs = ApiLog::where('is_success', true)->count();
        $failedLogs = ApiLog::where('is_success', false)->count();

        $latestLogs = ApiLog::latest()
            ->limit(5)
            ->get();

        $lastError = ApiLog::where('is_success', false)
            ->latest()
            ->first();

        return response()->json([
            'success' => true,
            'source' => 'bridging_api',
            'message' => 'Ringkasan monitoring bridging API berhasil diambil',
            'data' => [
                'configs' => [
                    'total_endpoint' => $totalConfigs,
                    'active_endpoint' => $activeConfigs,
                ],
                'logs' => [
                    'total_request' => $totalLogs,
                    'success_request' => $successLogs,
                    'failed_request' => $failedLogs,
                ],
                'media_center' => [
                    'base_url' => env('MEDIA_CENTER_BASE_URL'),
                    'status_note' => 'Status koneksi aktual dilihat dari health check dan log request terakhir.',
                ],
                'last_error' => $lastError,
                'latest_logs' => $latestLogs,
            ],
        ], 200);
    }

    public function health()
    {
        $databaseStatus = $this->checkDatabase();
        $mediaCenterStatus = $this->checkMediaCenter();

        $overallStatus = $databaseStatus['connected']
            ? 'running'
            : 'degraded';

        return response()->json([
            'success' => true,
            'source' => 'bridging_api',
            'message' => 'Health check bridging API berhasil dijalankan',
            'data' => [
                'bridging_api' => [
                    'app_name' => env('APP_NAME'),
                    'app_url' => env('APP_URL'),
                    'status' => $overallStatus,
                ],
                'database' => $databaseStatus,
                'media_center' => $mediaCenterStatus,
            ],
        ], 200);
    }

    private function checkDatabase(): array
    {
        try {
            DB::connection()->getPdo();

            return [
                'connected' => true,
                'connection' => config('database.default'),
                'message' => 'Database terkoneksi.',
            ];
        } catch (\Throwable $e) {
            return [
                'connected' => false,
                'connection' => config('database.default'),
                'message' => 'Database tidak terkoneksi.',
                'error' => $e->getMessage(),
            ];
        }
    }

    private function checkMediaCenter(): array
    {
        $baseUrl = rtrim(env('MEDIA_CENTER_BASE_URL', 'http://127.0.0.1:8002/api'), '/');

        try {
            $response = Http::timeout(3)
                ->acceptJson()
                ->get($baseUrl);

            return [
                'reachable' => true,
                'base_url' => $baseUrl,
                'http_status' => $response->status(),
                'message' => 'Media Center dapat dihubungi, meskipun endpoint health/root mungkin mengembalikan status tertentu.',
            ];
        } catch (\Throwable $e) {
            return [
                'reachable' => false,
                'base_url' => $baseUrl,
                'http_status' => null,
                'message' => 'Media Center tidak dapat dihubungi.',
                'error' => $e->getMessage(),
            ];
        }
    }
}