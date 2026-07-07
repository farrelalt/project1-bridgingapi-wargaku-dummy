<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\ApiConfig;
use App\Models\ApiLog;
use Illuminate\Http\Request;

class ApiLogAdminController extends Controller
{
    public function index(Request $request)
    {
        $query = ApiLog::query();

        if ($request->filled('service_name')) {
            $query->where('service_name', $request->service_name);
        }

        if ($request->filled('method')) {
            $query->where('method', strtoupper($request->method));
        }

        if ($request->filled('status_code')) {
            $query->where('status_code', $request->status_code);
        }

        if ($request->filled('is_success')) {
            $query->where('is_success', $request->is_success);
        }

        if ($request->filled('date_from')) {
            $query->whereDate('created_at', '>=', $request->date_from);
        }

        if ($request->filled('date_to')) {
            $query->whereDate('created_at', '<=', $request->date_to);
        }

        if ($request->filled('keyword')) {
            $keyword = $request->keyword;

            $query->where(function ($q) use ($keyword) {
                $q->where('service_name', 'like', "%{$keyword}%")
                    ->orWhere('local_endpoint', 'like', "%{$keyword}%")
                    ->orWhere('target_endpoint', 'like', "%{$keyword}%")
                    ->orWhere('error_message', 'like', "%{$keyword}%");
            });
        }

        $logs = $query->latest()
            ->paginate(10)
            ->withQueryString();

        $serviceNames = ApiConfig::query()
            ->whereNotNull('service_name')
            ->orderBy('service_name')
            ->pluck('service_name')
            ->unique()
            ->values();

        return view('admin.api-logs.index', [
            'logs' => $logs,
            'serviceNames' => $serviceNames,
            'filters' => $request->only([
                'service_name',
                'method',
                'status_code',
                'is_success',
                'date_from',
                'date_to',
                'keyword',
            ]),
        ]);
    }

    public function show($id)
    {
        $log = ApiLog::findOrFail($id);

        return view('admin.api-logs.show', [
            'log' => $log,
        ]);
    }

    public function clearFailed()
    {
        $deletedCount = ApiLog::where('is_success', false)->delete();

        return redirect()
            ->route('admin.api-logs.index')
            ->with('success', "{$deletedCount} failed log berhasil dihapus.");
    }

    public function clearAll()
    {
        $deletedCount = ApiLog::query()->delete();

        return redirect()
            ->route('admin.api-logs.index')
            ->with('success', "{$deletedCount} log berhasil dihapus.");
    }
}
