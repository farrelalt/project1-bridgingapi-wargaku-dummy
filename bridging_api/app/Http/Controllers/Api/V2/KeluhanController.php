<?php

namespace App\Http\Controllers\Api\V2;

use App\Http\Controllers\Controller;
use App\Services\MediaCenterService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class KeluhanController extends Controller
{
    protected MediaCenterService $mediaCenter;

    public function __construct(MediaCenterService $mediaCenter)
    {
        $this->mediaCenter = $mediaCenter;
    }

    public function create(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'judul' => 'required|string',
            'keluhan' => 'required|string',
            'kategori_id' => 'nullable',
            'topik_id' => 'nullable',
            'kecamatan_id' => 'nullable',
            'kelurahan_id' => 'nullable',
            'alamat' => 'nullable|string',
            'latitude' => 'nullable',
            'longitude' => 'nullable',
            'nomor_telepon' => 'nullable|string',
            'nama' => 'nullable|string',
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
            endpoint: '/keluhan_create',
            data: $validator->validated(),
            headers: $this->getAuthHeaders($request),
            serviceName: 'Keluhan Create',
            localEndpoint: '/api/v2/keluhan/create'
        );

        return $this->sendResponse($result);
    }

    public function index(Request $request)
    {
        $result = $this->mediaCenter->post(
            endpoint: '/keluhan',
            data: $request->all(),
            headers: $this->getAuthHeaders($request),
            serviceName: 'Keluhan List',
            localEndpoint: '/api/v2/keluhan'
        );

        return $this->sendResponse($result);
    }

    public function detail(Request $request, $id)
    {
        $result = $this->mediaCenter->get(
            endpoint: '/keluhan_detail/' . $id,
            query: [],
            headers: $this->getAuthHeaders($request),
            serviceName: 'Keluhan Detail',
            localEndpoint: '/api/v2/keluhan/' . $id
        );

        return $this->sendResponse($result);
    }

    public function selesai(Request $request)
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

        $result = $this->mediaCenter->post(
            endpoint: '/keluhan_selesai',
            data: $validator->validated(),
            headers: $this->getAuthHeaders($request),
            serviceName: 'Keluhan Selesai',
            localEndpoint: '/api/v2/keluhan/selesai'
        );

        return $this->sendResponse($result);
    }

    public function hapus(Request $request)
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

        $result = $this->mediaCenter->post(
            endpoint: '/keluhan_hapus',
            data: $validator->validated(),
            headers: $this->getAuthHeaders($request),
            serviceName: 'Keluhan Hapus',
            localEndpoint: '/api/v2/keluhan/hapus'
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
        return response()->json([
            'success' => $result['success'],
            'source' => 'bridging_api',
            'target' => 'media_center',
            'message' => $result['message'],
            'data' => $result['data'],
        ], $result['status']);
    }
}