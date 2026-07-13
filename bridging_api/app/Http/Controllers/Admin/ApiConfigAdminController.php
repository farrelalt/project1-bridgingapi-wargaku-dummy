<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\ApiConfig;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Illuminate\Validation\ValidationException;

class ApiConfigAdminController extends Controller
{
   public function index(Request $request)
    {
        $query = ApiConfig::query();

        if ($request->filled('keyword')) {
            $keyword = $request->keyword;

            $query->where(function ($q) use ($keyword) {
                $q->where('service_name', 'like', "%{$keyword}%")
                    ->orWhere('local_endpoint', 'like', "%{$keyword}%")
                    ->orWhere('target_endpoint', 'like', "%{$keyword}%")
                    ->orWhere('description', 'like', "%{$keyword}%");
            });
        }

        if ($request->filled('method')) {
            $query->where('method', strtoupper($request->method));
        }

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        if ($request->filled('is_restricted')) {
            $query->where('is_restricted', $request->is_restricted);
        }

        $perPage = (int) $request->input('per_page', 10);

        if (!in_array($perPage, [10, 20, 50])) {
            $perPage = 10;
        }

        $configs = $query
            ->orderBy('id')
            ->paginate($perPage)
            ->withQueryString();

        return view('admin.api-configs.index', [
            'configs' => $configs,
            'filters' => $request->only([
                'keyword',
                'method',
                'status',
                'is_restricted',
                'per_page',
            ]),
        ]);
    }

    public function create()
    {
        return view('admin.api-configs.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'request_mapping' => ['nullable', 'string'],
            'response_mapping' => ['nullable', 'string'],
            'response_mode' => ['required', 'in:standard,legacy'],
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
        $validated['request_mapping'] = $this->decodeMappingInput(
            value: $request->input('request_mapping'),
            field: 'request_mapping'
        );

        $validated['response_mapping'] = $this->decodeMappingInput(
            value: $request->input('response_mapping'),
            field: 'response_mapping'
        );

        $validated['response_mode'] = $validated['response_mode'] ?? 'standard';

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
                'request_mapping' => ['nullable', 'string'],
                'response_mapping' => ['nullable', 'string'],
                'response_mode' => ['required', 'in:standard,legacy'],
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

            

            $validated['method'] = strtoupper(trim($validated['method']));
            $validated['local_endpoint'] = $this->normalizeEndpointInput($validated['local_endpoint']);
            $validated['target_endpoint'] = $this->normalizeEndpointInput($validated['target_endpoint']);
            $validated['request_mapping'] = $this->decodeMappingInput(
                value: $request->input('request_mapping'),
                field: 'request_mapping'
            );

            $validated['response_mapping'] = $this->decodeMappingInput(
                value: $request->input('response_mapping'),
                field: 'response_mapping'
            );

            $validated['response_mode'] = $validated['response_mode'] ?? 'standard';

            $config->update($validated);

            return redirect()
                ->route('admin.api-configs.index')
                ->with('success', 'API config berhasil diperbarui.');
        }

    public function destroy($id)
        {
            $config = ApiConfig::findOrFail($id);

            $serviceName = $config->service_name;
            $localEndpoint = $config->local_endpoint;

            $config->delete();

            return redirect()
                ->route('admin.api-configs.index')
                ->with('success', "Endpoint {$serviceName} ({$localEndpoint}) berhasil dihapus.");
        }
        private function decodeMappingInput(?string $value, string $field): ?array
        {
            if ($value === null || trim($value) === '') {
                return null;
            }

            $decoded = json_decode($value, true);

            if (json_last_error() !== JSON_ERROR_NONE || !is_array($decoded)) {
                throw ValidationException::withMessages([
                    $field => 'Format mapping harus berupa JSON object yang valid. Contoh: {"success":"status"}',
                ]);
            }

            return $decoded;
        }
    private function normalizeEndpointInput(string $endpoint): string
        {
            $endpoint = trim($endpoint);

            return '/' . trim($endpoint, '/');
        }
}