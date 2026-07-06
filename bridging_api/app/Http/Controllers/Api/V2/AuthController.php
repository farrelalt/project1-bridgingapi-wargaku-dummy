<?php

namespace App\Http\Controllers\Api\V2;

use App\Http\Controllers\Controller;
use App\Services\MediaCenterService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class AuthController extends Controller
{
    protected MediaCenterService $mediaCenter;

    public function __construct(MediaCenterService $mediaCenter)
    {
        $this->mediaCenter = $mediaCenter;
    }

    public function login(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'user' => 'required|string',
            'password' => 'required|string',
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
            endpoint: '/login',
            data: [
                'user' => $request->user,
                'password' => $request->password,
            ],
            serviceName: 'Login',
            localEndpoint: '/api/v2/login'
        );

        return response()->json([
            'success' => $result['success'],
            'source' => 'bridging_api',
            'target' => 'media_center',
            'message' => $result['message'],
            'data' => $result['data'],
        ], $result['status']);
    }

    public function register(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'user' => 'required|string',
            'password' => 'required|string|min:6',
            'nik' => 'nullable|string',
            'name' => 'required|string',
            'jenis_kelamin' => 'nullable|string',
            'tanggal_lahir' => 'nullable|string',
            'alamat' => 'nullable|string',
            'kecamatan_id' => 'nullable',
            'kelurahan_id' => 'nullable',
            'phone' => 'nullable|string',
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
            endpoint: '/register',
            data: $validator->validated(),
            serviceName: 'Register',
            localEndpoint: '/api/v2/register'
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