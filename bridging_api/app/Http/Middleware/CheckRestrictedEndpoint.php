<?php

namespace App\Http\Middleware;

use App\Helpers\ApiResponse;
use App\Models\ApiConfig;
use App\Models\ApiLog;
use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class CheckRestrictedEndpoint
{
    public function handle(Request $request, Closure $next): Response
    {
        $config = $this->findConfig($request);

        if (!$config) {
            return $next($request);
        }

        if ($config->status === 'inactive') {
            return $this->blockedResponse(
                request: $request,
                config: $config,
                statusCode: 403,
                message: 'Endpoint sedang dinonaktifkan oleh admin.'
            );
        }

        if ($config->status === 'maintenance') {
            return $this->blockedResponse(
                request: $request,
                config: $config,
                statusCode: 503,
                message: 'Endpoint sedang dalam mode maintenance.'
            );
        }

        if ($config->is_restricted && !$request->bearerToken()) {
            return $this->blockedResponse(
                request: $request,
                config: $config,
                statusCode: 401,
                message: 'Endpoint ini membutuhkan Authorization Bearer token.'
            );
        }

        return $next($request);
    }

    private function findConfig(Request $request): ?ApiConfig
    {
        $path = $this->normalizeEndpoint($request->path());
        $method = strtoupper(trim($request->method()));

        $configs = ApiConfig::all()
            ->filter(function ($config) use ($method) {
                return strtoupper(trim($config->method)) === $method;
            })
            ->values();

        foreach ($configs as $config) {
            if ($this->normalizeEndpoint($config->local_endpoint) === $path) {
                return $config;
            }
        }

        foreach ($configs as $config) {
            $regex = $this->endpointToRegex(
                $this->normalizeEndpoint($config->local_endpoint)
            );

            if (preg_match($regex, $path)) {
                return $config;
            }
        }

        return null;
    }

    private function normalizeEndpoint(?string $endpoint): string
    {
        $endpoint = trim((string) $endpoint);

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

    private function blockedResponse(
        Request $request,
        ApiConfig $config,
        int $statusCode,
        string $message
    ): Response {
        $responsePayload = [
            'success' => false,
            'source' => 'bridging_api',
            'message' => $message,
            'data' => [
                'service_name' => $config->service_name,
                'local_endpoint' => $config->local_endpoint,
                'method' => $config->method,
                'status' => $config->status,
                'is_restricted' => $config->is_restricted,
            ],
        ];

        $this->saveBlockedLog(
            request: $request,
            config: $config,
            responsePayload: $responsePayload,
            statusCode: $statusCode,
            errorMessage: $message
        );

        return ApiResponse::error(
            message: $message,
            statusCode: $statusCode,
            data: [
                'service_name' => $config->service_name,
                'local_endpoint' => $config->local_endpoint,
                'method' => $config->method,
                'status' => $config->status,
                'is_restricted' => $config->is_restricted,
            ],
            target: 'media_center'
        );
    }

    private function saveBlockedLog(
        Request $request,
        ApiConfig $config,
        array $responsePayload,
        int $statusCode,
        string $errorMessage
    ): void {
        try {
            ApiLog::create([
                'service_name' => $config->service_name,
                'local_endpoint' => '/' . trim($request->path(), '/'),
                'target_endpoint' => $this->getFullTargetEndpoint($config),
                'method' => strtoupper($request->method()),
                'request_payload' => $this->sanitizePayload($request->all()),
                'response_payload' => $responsePayload,
                'status_code' => $statusCode,
                'is_success' => false,
                'error_message' => $errorMessage,
            ]);
        } catch (\Throwable $e) {
            // Jika logging gagal, request utama tidak boleh ikut crash.
        }
    }

    private function getFullTargetEndpoint(ApiConfig $config): string
{
    return trim($config->target_endpoint);
}

    private function sanitizePayload(mixed $payload): mixed
    {
        if (!is_array($payload)) {
            return $payload;
        }

        $sensitiveKeys = [
            'password',
            'token',
            'access_token',
            'refresh_token',
            'authorization',
        ];

        foreach ($payload as $key => $value) {
            if (in_array(strtolower($key), $sensitiveKeys)) {
                $payload[$key] = '******';
                continue;
            }

            if (is_array($value)) {
                $payload[$key] = $this->sanitizePayload($value);
            }
        }

        return $payload;
    }
}