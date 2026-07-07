<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\ApiConfig;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class ApiConfigAdminController extends Controller
{
    public function index()
    {
        $configs = ApiConfig::orderBy('id')->get();

        return view('admin.api-configs.index', [
            'configs' => $configs,
        ]);
    }

    public function create()
    {
        return view('admin.api-configs.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'service_name' => ['required', 'string', 'max:255'],
            'local_endpoint' => [
                'required',
                'string',
                'max:255',
                'regex:/^\/api\/v2\/.+$/',
                Rule::unique('api_configs', 'local_endpoint')
                    ->where(fn ($query) => $query->where('method', strtoupper($request->method))),
            ],
            'target_endpoint' => [
                'required',
                'string',
                'max:255',
                'regex:/^\/api\/.+$/',
            ],
            'method' => ['required', 'in:GET,POST,PUT,PATCH,DELETE'],
            'status' => ['required', 'in:active,inactive,maintenance'],
            'is_restricted' => ['required', 'boolean'],
            'description' => ['nullable', 'string'],
        ], [
            'local_endpoint.regex' => 'Local endpoint harus diawali dengan /api/v2/. Contoh: /api/v2/berita',
            'target_endpoint.regex' => 'Target endpoint harus diawali dengan /api/. Contoh: /api/berita',
            'local_endpoint.unique' => 'Kombinasi local endpoint dan method sudah digunakan.',
        ]);

        $validated['method'] = strtoupper($validated['method']);
        $validated['local_endpoint'] = '/' . trim($validated['local_endpoint'], '/');
        $validated['target_endpoint'] = '/' . trim($validated['target_endpoint'], '/');

        ApiConfig::create($validated);

        return redirect()
            ->route('admin.api-configs.index')
            ->with('success', 'API config baru berhasil ditambahkan.');
    }

    public function edit($id)
    {
        $config = ApiConfig::findOrFail($id);

        return view('admin.api-configs.edit', [
            'config' => $config,
        ]);
    }

    public function update(Request $request, $id)
    {
        $config = ApiConfig::findOrFail($id);

        $validated = $request->validate([
            'service_name' => ['required', 'string', 'max:255'],
            'local_endpoint' => [
                'required',
                'string',
                'max:255',
                'regex:/^\/api\/v2\/.+$/',
                Rule::unique('api_configs', 'local_endpoint')
                    ->where(fn ($query) => $query->where('method', strtoupper($request->method)))
                    ->ignore($config->id),
            ],
            'target_endpoint' => [
                'required',
                'string',
                'max:255',
                'regex:/^\/api\/.+$/',
            ],
            'method' => ['required', 'in:GET,POST,PUT,PATCH,DELETE'],
            'status' => ['required', 'in:active,inactive,maintenance'],
            'is_restricted' => ['required', 'boolean'],
            'description' => ['nullable', 'string'],
        ], [
            'local_endpoint.regex' => 'Local endpoint harus diawali dengan /api/v2/. Contoh: /api/v2/berita',
            'target_endpoint.regex' => 'Target endpoint harus diawali dengan /api/. Contoh: /api/berita',
            'local_endpoint.unique' => 'Kombinasi local endpoint dan method sudah digunakan.',
        ]);

        $validated['method'] = strtoupper($validated['method']);
        $validated['local_endpoint'] = '/' . trim($validated['local_endpoint'], '/');
        $validated['target_endpoint'] = '/' . trim($validated['target_endpoint'], '/');

        $config->update($validated);

        return redirect()
            ->route('admin.api-configs.index')
            ->with('success', 'API config berhasil diperbarui.');
    }
}