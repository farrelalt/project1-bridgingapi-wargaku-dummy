<?php

namespace App\Helpers;

class ApiResponse
{
    public static function success(
        mixed $data = null,
        string $message = 'Request berhasil diproses',
        int $statusCode = 200,
        ?string $target = 'media_center'
    ) {
        return response()->json([
            'success' => true,
            'source' => 'bridging_api',
            'target' => $target,
            'message' => $message,
            'data' => $data ?? [],
        ], $statusCode);
    }

    public static function error(
        string $message = 'Request gagal diproses',
        int $statusCode = 500,
        mixed $data = null,
        ?string $error = null,
        ?string $target = 'media_center'
    ) {
        $response = [
            'success' => false,
            'source' => 'bridging_api',
            'target' => $target,
            'message' => $message,
            'data' => $data ?? [],
        ];

        if ($error !== null) {
            $response['error'] = $error;
        }

        return response()->json($response, $statusCode);
    }

    public static function fromServiceResult(
        array $result,
        ?string $target = 'media_center'
    ) {
        $isSuccess = (bool) ($result['success'] ?? false);

        $statusCode = (int) ($result['status'] ?? ($isSuccess ? 200 : 500));

        $message = $result['message'] ?? (
            $isSuccess
                ? 'Request berhasil diproses'
                : 'Request gagal diproses'
        );

        $data = $result['data'] ?? [];

        if ($isSuccess) {
            return self::success(
                data: $data,
                message: $message,
                statusCode: $statusCode,
                target: $target
            );
        }

        return self::error(
            message: $message,
            statusCode: $statusCode,
            data: $data,
            error: $data['error'] ?? ($result['error'] ?? null),
            target: $target
        );
    }
}