<?php

namespace App\Http\Controllers\Api\V2;

use App\Http\Controllers\Controller;
use App\Services\MediaCenterService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use App\Helpers\ApiResponse;

class TanggapanController extends Controller
{
    protected MediaCenterService $mediaCenter;

    public function __construct(MediaCenterService $mediaCenter)
    {
        $this->mediaCenter = $mediaCenter;
    }

    public function create(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'keluhan_id' => 'required',
            'tanggapan' => 'required|string',
            'foto' => 'nullable|string',
            'tanggal_tanggapan' => 'nullable|string',
            'tanggal_tindak_lanjut' => 'nullable|string',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'source' => 'bridging_api',
                'message' => 'Validasi gagal',
                'errors' => $validator->errors(),
            ], 422);
        }

        $result = $this->mediaCenter->post(
            endpoint: '/tanggapan_create',
            data: $validator->validated(),
            headers: $this->getAuthHeaders($request),
            serviceName: 'Tanggapan Create',
            localEndpoint: '/api/v2/tanggapan/create'
        );

        return $this->sendResponse($result);
    }

    public function index(Request $request)
    {
        $result = $this->mediaCenter->post(
            endpoint: '/tanggapan',
            data: $request->all(),
            headers: $this->getAuthHeaders($request),
            serviceName: 'Tanggapan List',
            localEndpoint: '/api/v2/tanggapan'
        );

        return $this->sendResponse($result);
    }

    private function getAuthHeaders(Request $request): array
    {
        $headers = [];

        if ($request->bearerToken()) {
            $headers['Authorization'] = 'Bearer ' . $request->bearerToken();
        }

        return $headers;
    }

    private function sendResponse(array $result)
    {
      return ApiResponse::fromServiceResult($result);
    }
}