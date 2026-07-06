<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\ApiConfig;
use App\Models\ApiLog;

class DashboardController extends Controller
{
    public function index()
    {
        $data = [
            'totalConfigs' => ApiConfig::count(),
            'activeConfigs' => ApiConfig::where('status', 'active')->count(),
            'totalLogs' => ApiLog::count(),
            'successLogs' => ApiLog::where('is_success', true)->count(),
            'failedLogs' => ApiLog::where('is_success', false)->count(),
            'latestLogs' => ApiLog::latest()->limit(5)->get(),
            'mediaCenterBaseUrl' => env('MEDIA_CENTER_BASE_URL'),
        ];

        return view('admin.dashboard', $data);
    }
}