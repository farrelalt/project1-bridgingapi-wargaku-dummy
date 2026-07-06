<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\DummyKategori;
use App\Models\DummyChannel;
use App\Models\DummyKecamatan;
use App\Models\DummyKelurahan;
use App\Models\DummyTopik;
use App\Models\DummyStatus;
use App\Models\DummyInstansi;

class MasterDataController extends Controller
{
    public function kategori()
    {
        return response()->json([
            'success' => true,
            'source' => 'dummy_media_center',
            'message' => 'Data kategori berhasil diambil',
            'data' => DummyKategori::all(),
        ]);
    }

    public function chanel()
    {
        return response()->json([
            'success' => true,
            'source' => 'dummy_media_center',
            'message' => 'Data channel berhasil diambil',
            'data' => DummyChannel::all(),
        ]);
    }

    public function kecamatan()
    {
        return response()->json([
            'success' => true,
            'source' => 'dummy_media_center',
            'message' => 'Data kecamatan berhasil diambil',
            'data' => DummyKecamatan::all(),
        ]);
    }

    public function kelurahan($id_kec)
    {
        return response()->json([
            'success' => true,
            'source' => 'dummy_media_center',
            'message' => 'Data kelurahan berhasil diambil',
            'data' => DummyKelurahan::where('kecamatan_id', $id_kec)->get(),
        ]);
    }

    public function topik()
    {
        return response()->json([
            'success' => true,
            'source' => 'dummy_media_center',
            'message' => 'Data topik berhasil diambil',
            'data' => DummyTopik::all(),
        ]);
    }

    public function status()
    {
        return response()->json([
            'success' => true,
            'source' => 'dummy_media_center',
            'message' => 'Data status berhasil diambil',
            'data' => DummyStatus::all(),
        ]);
    }

    public function instansi()
    {
        return response()->json([
            'success' => true,
            'source' => 'dummy_media_center',
            'message' => 'Data instansi berhasil diambil',
            'data' => DummyInstansi::all(),
        ]);
    }
}