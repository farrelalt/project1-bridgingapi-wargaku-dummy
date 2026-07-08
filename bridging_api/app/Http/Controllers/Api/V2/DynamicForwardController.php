<?php

namespace App\Http\Controllers\Api\V2;

use App\Helpers\ApiResponse;
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
        $localEndpoint = $this->normalizeEndpoint($request->path());
        $method = strtoupper(trim($request->method()));

        $config = $this->findConfig($localEndpoint, $method);

        if (!$config) {
            return ApiResponse::error(
                message: 'Endpoint tidak ditemukan di API Config.',
                statusCode: 404,
                data: [
                    'local_endpoint' => $localEndpoint,
                    'method' => $method,
                ],
                target: null
            );
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

        return ApiResponse::fromServiceResult($result);
    }

    private function findConfig(string $localEndpoint, string $method): ?ApiConfig
    {
        $localEndpoint = $this->normalizeEndpoint($localEndpoint);
        $method = strtoupper(trim($method));

        $configs = ApiConfig::query()
            ->whereRaw('UPPER(TRIM(method)) = ?', [$method])
            ->get();

        foreach ($configs as $config) {
            $configEndpoint = $this->normalizeEndpoint($config->local_endpoint);

            if ($configEndpoint === $localEndpoint) {
                return $config;
            }
        }

        foreach ($configs as $config) {
            $configEndpoint = $this->normalizeEndpoint($config->local_endpoint);
            $regex = $this->endpointToRegex($configEndpoint);

            if (preg_match($regex, $localEndpoint)) {
                return $config;
            }
        }

        return null;
    }

    private function normalizeEndpoint(?string $endpoint): string
    {
        $endpoint = trim((string) $endpoint);
        $endpoint = str_replace('\\', '/', $endpoint);
        $endpoint = preg_replace('#/+#', '/', $endpoint);

        return '/' . trim($endpoint, '/');
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
        $localPattern = $this->normalizeEndpoint($localPattern);
        $targetPattern = $this->normalizeEndpoint($targetPattern);
        $actualLocalEndpoint = $this->normalizeEndpoint($actualLocalEndpoint);

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

        return $this->normalizeEndpoint($targetPattern);
    }
}