<?php

namespace App\Http\Controllers\Api\V2;

use App\Http\Controllers\Controller;
use App\Services\MediaCenterService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use App\Helpers\ApiResponse;

class RatingController extends Controller
{
    protected MediaCenterService $mediaCenter;

    public function __construct(MediaCenterService $mediaCenter)
    {
        $this->mediaCenter = $mediaCenter;
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'keluhan_id' => 'required',
            'responsivitas' => 'required|integer|min:1|max:5',
            'informasi' => 'required|integer|min:1|max:5',
            'tindak_lanjut' => 'required|integer|min:1|max:5',
            'keramahan' => 'required|integer|min:1|max:5',
            'kepuasan' => 'required|integer|min:1|max:5',
            'kritik_saran' => 'nullable|string',
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
            endpoint: '/keluhan_rating',
            data: $validator->validated(),
            headers: $this->getAuthHeaders($request),
            serviceName: 'Keluhan Rating',
            localEndpoint: '/api/v2/keluhan/rating'
        );

        return $this->sendResponse($result);
    }

    public function show(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'keluhan_id' => 'required',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'source' => 'bridging_api',
                'message' => 'Validasi gagal',
                'errors' => $validator->errors(),
            ], 422);
        }

        $result = $this->mediaCenter->get(
            endpoint: '/view_keluhan_rating',
            query: $validator->validated(),
            headers: $this->getAuthHeaders($request),
            serviceName: 'View Keluhan Rating',
            localEndpoint: '/api/v2/keluhan/rating'
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