<?php

namespace App\Http\Controllers\Api\V2;

use App\Http\Controllers\Controller;
use App\Services\MediaCenterService;
use Illuminate\Http\Request;

class MasterDataController extends Controller
{
    protected MediaCenterService $mediaCenter;

    public function __construct(MediaCenterService $mediaCenter)
    {
        $this->mediaCenter = $mediaCenter;
    }

    public function kategori()
    {
        $result = $this->mediaCenter->get(
            endpoint: '/kategori',
            query: [],
            headers: [],
            serviceName: 'Kategori',
            localEndpoint: '/api/v2/kategori'
        );

        return $this->sendResponse($result);
    }

    public function chanel()
    {
        $result = $this->mediaCenter->get(
            endpoint: '/chanel',
            query: [],
            headers: [],
            serviceName: 'Chanel',
            localEndpoint: '/api/v2/chanel'
        );

        return $this->sendResponse($result);
    }

    public function kecamatan()
    {
        $result = $this->mediaCenter->get(
            endpoint: '/kecamatan',
            query: [],
            headers: [],
            serviceName: 'Kecamatan',
            localEndpoint: '/api/v2/kecamatan'
        );

        return $this->sendResponse($result);
    }

    public function kelurahan($id_kec)
    {
        $result = $this->mediaCenter->get(
            endpoint: '/kelurahan/' . $id_kec,
            query: [],
            headers: [],
            serviceName: 'Kelurahan',
            localEndpoint: '/api/v2/kelurahan/' . $id_kec
        );

        return $this->sendResponse($result);
    }

    public function topik()
    {
        $result = $this->mediaCenter->get(
            endpoint: '/topik',
            query: [],
            headers: [],
            serviceName: 'Topik',
            localEndpoint: '/api/v2/topik'
        );

        return $this->sendResponse($result);
    }

    public function status()
    {
        $result = $this->mediaCenter->get(
            endpoint: '/status',
            query: [],
            headers: [],
            serviceName: 'Status',
            localEndpoint: '/api/v2/status'
        );

        return $this->sendResponse($result);
    }

    public function instansi(Request $request)
    {
        $result = $this->mediaCenter->post(
            endpoint: '/instansi',
            data: $request->all(),
            headers: [],
            serviceName: 'Instansi',
            localEndpoint: '/api/v2/instansi'
        );

        return $this->sendResponse($result);
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