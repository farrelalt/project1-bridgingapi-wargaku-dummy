<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\ApiLog;

class ApiLogAdminController extends Controller
{
    public function index()
    {
        $logs = ApiLog::latest()->paginate(20);

        return view('admin.api-logs.index', compact('logs'));
    }

    public function show($id)
    {
        $log = ApiLog::findOrFail($id);

        return view('admin.api-logs.show', compact('log'));
    }
}