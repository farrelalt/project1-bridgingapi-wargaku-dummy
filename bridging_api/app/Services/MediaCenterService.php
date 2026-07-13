<?php

namespace App\Services;

use App\Models\ApiConfig;

use App\Models\ApiLog;
use Illuminate\Support\Facades\Http;

class MediaCenterService
{
    protected string $baseUrl;
    protected int $timeout;
    protected DataMappingService $dataMappingService;
    
    public function __construct(DataMappingService $dataMappingService)
    {
        $this->dataMappingService = $dataMappingService;
        $this->baseUrl = rtrim(env('MEDIA_CENTER_BASE_URL', 'http://127.0.0.1:8002/api'), '/');
        $this->timeout = (int) env('MEDIA_CENTER_TIMEOUT', 10);
    }

    public function post(
        string $endpoint,
        array $data = [],
        array $headers = [],
        ?string $serviceName = null,
        ?string $localEndpoint = null
    ): array {
        $config = $this->findApiConfigForMapping($localEndpoint, 'POST');

        $data = $this->dataMappingService->mapKeys(
            payload: $data,
            mapping: $config?->request_mapping
        );

        $targetUrl = $this->resolveTargetUrl(
            fallbackEndpoint: $endpoint,
            method: 'POST',
            localEndpoint: $localEndpoint
        );

        try {
            $response = Http::timeout($this->timeout)
                ->acceptJson()
                ->withHeaders($headers)
                ->post($targetUrl, $data);

            $responseData = $response->json();

            if ($responseData === null) {
                $responseData = [
                    'raw_response' => $response->body(),
                ];
            }

            $responseData = $this->dataMappingService->mapKeys(
                payload: $responseData,
                mapping: $config?->response_mapping
            );

            $result = [
                'success' => $response->successful(),
                'status' => $response->status(),
                'message' => $response->successful()
                    ? 'Request berhasil diteruskan ke Media Center'
                    : 'Request gagal dari Media Center',
                'data' => $responseData,
                'response_mode' => $config?->response_mode ?? 'standard',
            ];

            $this->saveLog(
                serviceName: $serviceName,
                localEndpoint: $localEndpoint,
                targetEndpoint: $targetUrl,
                method: 'POST',
                requestPayload: $data,
                responsePayload: $responseData,
                statusCode: $response->status(),
                isSuccess: $response->successful(),
                errorMessage: $response->successful() ? null : 'Media Center mengembalikan response gagal'
            );

            return $result;
        } catch (\Throwable $e) {
            $result = [
                'success' => false,
                'status' => 503,
                'message' => 'Media Center tidak bisa dihubungi',
                'data' => [
                    'error' => $e->getMessage(),
                ],
                'response_mode' => $config?->response_mode ?? 'standard',
            ];

            $this->saveLog(
                serviceName: $serviceName,
                localEndpoint: $localEndpoint,
                targetEndpoint: $targetUrl,
                method: 'POST',
                requestPayload: $data,
                responsePayload: $result['data'],
                statusCode: 503,
                isSuccess: false,
                errorMessage: $e->getMessage()
            );

            return $result;
        }
    }

    public function get(
        string $endpoint,
        array $query = [],
        array $headers = [],
        ?string $serviceName = null,
        ?string $localEndpoint = null
    ): array {
        $config = $this->findApiConfigForMapping($localEndpoint, 'GET');

        $query = $this->dataMappingService->mapKeys(
            payload: $query,
            mapping: $config?->request_mapping
        );

        $targetUrl = $this->resolveTargetUrl(
            fallbackEndpoint: $endpoint,
            method: 'GET',
            localEndpoint: $localEndpoint
        );

        try {
            $response = Http::timeout($this->timeout)
                ->acceptJson()
                ->withHeaders($headers)
                ->get($targetUrl, $query);

            $responseData = $response->json();

            if ($responseData === null) {
                $responseData = [
                    'raw_response' => $response->body(),
                ];
            }

            $responseData = $this->dataMappingService->mapKeys(
                payload: $responseData,
                mapping: $config?->response_mapping
            );

            $result = [
                'success' => $response->successful(),
                'status' => $response->status(),
                'message' => $response->successful()
                    ? 'Request berhasil diteruskan ke Media Center'
                    : 'Request gagal dari Media Center',
                'data' => $responseData,
                'response_mode' => $config?->response_mode ?? 'standard',
            ];

            $this->saveLog(
                serviceName: $serviceName,
                localEndpoint: $localEndpoint,
                targetEndpoint: $targetUrl,
                method: 'GET',
                requestPayload: $query,
                responsePayload: $responseData,
                statusCode: $response->status(),
                isSuccess: $response->successful(),
                errorMessage: $response->successful() ? null : 'Media Center mengembalikan response gagal'
            );

            return $result;
        } catch (\Throwable $e) {
            $result = [
                'success' => false,
                'status' => 503,
                'message' => 'Media Center tidak bisa dihubungi',
                'data' => [
                    'error' => $e->getMessage(),
                ],
                'response_mode' => $config?->response_mode ?? 'standard',
            ];

            $this->saveLog(
                serviceName: $serviceName,
                localEndpoint: $localEndpoint,
                targetEndpoint: $targetUrl,
                method: 'GET',
                requestPayload: $query,
                responsePayload: $result['data'],
                statusCode: 503,
                isSuccess: false,
                errorMessage: $e->getMessage()
            );

            return $result;
        }
    }

    public function forward(
        string $method,
        string $endpoint,
        array $data = [],
        array $headers = [],
        ?string $serviceName = null,
        ?string $localEndpoint = null
    ): array {
        $method = strtoupper($method);

        $config = $this->findApiConfigForMapping($localEndpoint, $method);

        $data = $this->dataMappingService->mapKeys(
            payload: $data,
            mapping: $config?->request_mapping
        );

        $targetUrl = $this->resolveTargetUrl(
            fallbackEndpoint: $endpoint,
            method: $method,
            localEndpoint: $localEndpoint
        );

        try {
            $http = Http::timeout($this->timeout)
                ->acceptJson()
                ->withHeaders($headers);

            if ($method === 'GET') {
                $response = $http->get($targetUrl, $data);
            } else {
                $response = $http->send($method, $targetUrl, [
                    'json' => $data,
                ]);
            }

            $responseData = $response->json();

            if ($responseData === null) {
                $responseData = [
                    'raw_response' => $response->body(),
                ];
            }

            $responseData = $this->dataMappingService->mapKeys(
                payload: $responseData,
                mapping: $config?->response_mapping
            );

            $result = [
                'success' => $response->successful(),
                'status' => $response->status(),
                'message' => $response->successful()
                    ? 'Request berhasil diteruskan ke Media Center'
                    : 'Request gagal dari Media Center',
                'data' => $responseData,
                'response_mode' => $config?->response_mode ?? 'standard',
            ];

            $this->saveLog(
                serviceName: $serviceName,
                localEndpoint: $localEndpoint,
                targetEndpoint: $targetUrl,
                method: $method,
                requestPayload: $data,
                responsePayload: $responseData,
                statusCode: $response->status(),
                isSuccess: $response->successful(),
                errorMessage: $response->successful() ? null : 'Media Center mengembalikan response gagal'
            );

            return $result;
        } catch (\Throwable $e) {
            $result = [
                'success' => false,
                'status' => 503,
                'message' => 'Media Center tidak bisa dihubungi',
                'data' => [
                    'error' => $e->getMessage(),
                ],
                'response_mode' => $config?->response_mode ?? 'standard',
            ];

            $this->saveLog(
                serviceName: $serviceName,
                localEndpoint: $localEndpoint,
                targetEndpoint: $targetUrl,
                method: $method,
                requestPayload: $data,
                responsePayload: $result['data'],
                statusCode: 503,
                isSuccess: false,
                errorMessage: $e->getMessage()
            );

            return $result;
        }
    }

    private function resolveTargetUrl(
        string $fallbackEndpoint,
        string $method,
        ?string $localEndpoint = null
    ): string {
        $targetEndpoint = $fallbackEndpoint;

        if ($localEndpoint) {
            $configTargetEndpoint = $this->getTargetEndpointFromConfig(
                localEndpoint: $localEndpoint,
                method: $method
            );

            if ($configTargetEndpoint) {
                $targetEndpoint = $configTargetEndpoint;
            }
        }

        return $this->buildTargetUrl($targetEndpoint);
    }

    private function getTargetEndpointFromConfig(
        string $localEndpoint,
        string $method
    ): ?string {
        $localEndpoint = '/' . trim($localEndpoint, '/');
        $method = strtoupper($method);

        $exactConfig = ApiConfig::where('method', $method)
            ->where('local_endpoint', $localEndpoint)
            ->first();

        if ($exactConfig) {
            return $exactConfig->target_endpoint;
        }

        $configs = ApiConfig::where('method', $method)->get();

        foreach ($configs as $config) {
            $regex = $this->endpointToRegex($config->local_endpoint);

            if (preg_match($regex, $localEndpoint, $matches)) {
                $targetEndpoint = $config->target_endpoint;

                foreach ($matches as $key => $value) {
                    if (is_string($key)) {
                        $targetEndpoint = str_replace('{' . $key . '}', $value, $targetEndpoint);
                    }
                }

                return $targetEndpoint;
            }
        }

        return null;
    }

    private function endpointToRegex(string $endpoint): string
    {
        $segments = explode('/', trim($endpoint, '/'));

        $segments = array_map(function ($segment) {
            if (preg_match('/^\{(.+)\}$/', $segment, $matches)) {
                $paramName = $matches[1];

                return '(?P<' . $paramName . '>[^/]+)';
            }

            return preg_quote($segment, '#');
        }, $segments);

        return '#^/' . implode('/', $segments) . '$#';
    }

    private function buildTargetUrl(string $targetEndpoint): string
    {
        $targetEndpoint = trim($targetEndpoint);

        if (str_starts_with($targetEndpoint, 'http://') || str_starts_with($targetEndpoint, 'https://')) {
            return $targetEndpoint;
        }

        $targetEndpoint = '/' . ltrim($targetEndpoint, '/');

        $rootUrl = preg_replace('#/api$#', '', $this->baseUrl);

        if (str_starts_with($targetEndpoint, '/api/')) {
            return $rootUrl . $targetEndpoint;
        }

        return $this->baseUrl . $targetEndpoint;
    }

    private function saveLog(
        ?string $serviceName,
        ?string $localEndpoint,
        string $targetEndpoint,
        string $method,
        array $requestPayload,
        mixed $responsePayload,
        int $statusCode,
        bool $isSuccess,
        ?string $errorMessage = null
    ): void {
        try {
            ApiLog::create([
                'service_name' => $serviceName,
                'local_endpoint' => $localEndpoint,
                'target_endpoint' => $targetEndpoint,
                'method' => $method,
                'request_payload' => $this->sanitizePayload($requestPayload),
                'response_payload' => $this->sanitizePayload($responsePayload),
                'status_code' => $statusCode,
                'is_success' => $isSuccess,
                'error_message' => $errorMessage,
            ]);
        } catch (\Throwable $e) {
            // Logging gagal tidak boleh membuat API utama ikut gagal.
        }
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

    private function findApiConfigForMapping(?string $localEndpoint, string $method): ?ApiConfig
    {
        if (!$localEndpoint) {
            return null;
        }

        $localEndpoint = $this->normalizeEndpoint($localEndpoint);
        $method = strtoupper(trim($method));

        $configs = ApiConfig::query()
            ->whereRaw('UPPER(TRIM(method)) = ?', [$method])
            ->get();

        foreach ($configs as $config) {
            if ($this->normalizeEndpoint($config->local_endpoint) === $localEndpoint) {
                return $config;
            }
        }

        foreach ($configs as $config) {
            $regex = $this->endpointToRegex($this->normalizeEndpoint($config->local_endpoint));

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
}