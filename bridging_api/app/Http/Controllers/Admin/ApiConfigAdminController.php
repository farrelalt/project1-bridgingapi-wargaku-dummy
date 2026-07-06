<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\ApiConfig;
use Illuminate\Http\Request;

class ApiConfigAdminController extends Controller
{
    public function index()
    {
        $configs = ApiConfig::orderBy('id', 'asc')->get();

        return view('admin.api-configs.index', compact('configs'));
    }

    public function edit($id)
    {
        $config = ApiConfig::findOrFail($id);

        return view('admin.api-configs.edit', compact('config'));
    }

    public function update(Request $request, $id)
    {
        $config = ApiConfig::findOrFail($id);

        $validated = $request->validate([
            'target_endpoint' => 'required|string',
            'status' => 'required|in:active,inactive,maintenance',
            'is_restricted' => 'nullable|boolean',
            'description' => 'nullable|string',
        ]);

        $validated['is_restricted'] = $request->has('is_restricted');

        $config->update($validated);

        return redirect()
            ->route('admin.api-configs.index')
            ->with('success', 'Konfigurasi endpoint berhasil diperbarui.');
    }
}