<?php

namespace App\Http\Controllers\Api\V2;

use App\Http\Controllers\Controller;
use App\Models\ApiConfig;
use App\Services\MediaCenterService;
use Illuminate\Http\Request;

class DynamicForwardController extends Controller
{
    public function __construct(
        protected MediaCenterService $mediaCenterService
    ) {
    }

    public function handle(Request $request, ?string $any = null)
    {
        $localEndpoint = '/' . trim($request->path(), '/');
        $method = strtoupper($request->method());

        $config = $this->findConfig($localEndpoint, $method);

        if (!$config) {
            return response()->json([
                'success' => false,
                'source' => 'bridging_api',
                'message' => 'Endpoint tidak ditemukan di API Config.',
                'data' => [
                    'local_endpoint' => $localEndpoint,
                    'method' => $method,
                ],
            ], 404);
        }

        $headers = $this->getForwardHeaders($request);

        $payload = $request->isMethod('GET')
            ? $request->query()
            : $request->all();

        $targetEndpoint = $this->replaceTargetPathParameters(
            localPattern: $config->local_endpoint,
            targetPattern: $config->target_endpoint,
            actualLocalEndpoint: $localEndpoint
        );

        $result = $this->mediaCenterService->forward(
            method: $method,
            endpoint: $targetEndpoint,
            data: $payload,
            headers: $headers,
            serviceName: $config->service_name,
            localEndpoint: $localEndpoint
        );

        return response()->json([
            'success' => $result['success'],
            'source' => 'bridging_api',
            'target' => 'media_center',
            'message' => $result['message'],
            'data' => $result['data'],
        ], $result['status']);
    }

    private function findConfig(string $localEndpoint, string $method): ?ApiConfig
    {
        $exactConfig = ApiConfig::where('method', $method)
            ->where('local_endpoint', $localEndpoint)
            ->first();

        if ($exactConfig) {
            return $exactConfig;
        }

        $configs = ApiConfig::where('method', $method)->get();

        foreach ($configs as $config) {
            $regex = $this->endpointToRegex($config->local_endpoint);

            if (preg_match($regex, $localEndpoint)) {
                return $config;
            }
        }

        return null;
    }

    private function endpointToRegex(string $endpoint): string
    {
        $segments = explode('/', trim($endpoint, '/'));

        $segments = array_map(function ($segment) {
            if (preg_match('/^\{.+\}$/', $segment)) {
                return '[^/]+';
            }

            return preg_quote($segment, '#');
        }, $segments);

        return '#^/' . implode('/', $segments) . '$#';
    }

    private function getForwardHeaders(Request $request): array
    {
        $headers = [
            'Accept' => 'application/json',
        ];

        if ($request->bearerToken()) {
            $headers['Authorization'] = 'Bearer ' . $request->bearerToken();
        }

        return $headers;
    }

    private function replaceTargetPathParameters(
        string $localPattern,
        string $targetPattern,
        string $actualLocalEndpoint
    ): string {
        $localSegments = explode('/', trim($localPattern, '/'));
        $actualSegments = explode('/', trim($actualLocalEndpoint, '/'));

        $params = [];

        foreach ($localSegments as $index => $segment) {
            if (preg_match('/^\{(.+)\}$/', $segment, $matches)) {
                $paramName = $matches[1];
                $params[$paramName] = $actualSegments[$index] ?? null;
            }
        }

        foreach ($params as $key => $value) {
            if ($value !== null) {
                $targetPattern = str_replace('{' . $key . '}', $value, $targetPattern);
            }
        }

        return '/' . trim($targetPattern, '/');
    }
}