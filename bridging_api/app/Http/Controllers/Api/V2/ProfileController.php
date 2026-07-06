<?php

namespace App\Http\Controllers\Api\V2;

use App\Http\Controllers\Controller;
use App\Services\MediaCenterService;
use Illuminate\Http\Request;

class ProfileController extends Controller
{
    protected MediaCenterService $mediaCenter;

    public function __construct(MediaCenterService $mediaCenter)
    {
        $this->mediaCenter = $mediaCenter;
    }

    public function profile(Request $request)
    {
        $headers = [];

        if ($request->bearerToken()) {
            $headers['Authorization'] = 'Bearer ' . $request->bearerToken();
        }

        $result = $this->mediaCenter->get(
            endpoint: '/profile',
            query: [],
            headers: $headers,
            serviceName: 'Profile',
            localEndpoint: '/api/v2/profile'
        );

        return response()->json([
            'success' => $result['success'],
            'source' => 'bridging_api',
            'target' => 'media_center',
            'message' => $result['message'],
            'data' => $result['data'],
        ], $result['status']);
    }
}