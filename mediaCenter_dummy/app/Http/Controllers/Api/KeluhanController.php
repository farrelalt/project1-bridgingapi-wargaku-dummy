<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\DummyKeluhan;
use Illuminate\Http\Request;

class KeluhanController extends Controller
{
    public function create(Request $request)
    {
        $request->validate([
            'judul' => 'required|string',
        ]);

        $keluhan = DummyKeluhan::create([
            'user_id' => $request->user_id,
            'judul' => $request->judul,
            'keluhan' => $request->keluhan,
            'konten' => $request->konten,
            'tanggal_keluhan' => now()->toDateString(),
            'kecamatan_id' => $request->kecamatan_id,
            'kelurahan_id' => $request->kelurahan_id,
            'kategori_id' => $request->kategori_id,
            'topik_id' => $request->topik_id,
            'instansi_id' => $request->instansi_id,
            'channel_id' => $request->channel_id,
            'status_id' => 1,
            'latitude' => $request->latitude,
            'longitude' => $request->longitude,
            'alamat' => $request->alamat,
            'sosial_media' => $request->sosial_media,
            'jenis_kelamin' => $request->jenis_kelamin,
            'nomor_telepon' => $request->nomor_telepon,
            'nama' => $request->nama,
        ]);

        return response()->json([
            'success' => true,
            'source' => 'dummy_media_center',
            'message' => 'Keluhan berhasil dibuat',
            'data' => $keluhan,
        ], 201);
    }

    public function index(Request $request)
    {
        $query = DummyKeluhan::query();

        if ($request->filled('user_id')) {
            $query->where('user_id', $request->user_id);
        }

        return response()->json([
            'success' => 'keluhan sukses diproses',
            'source' => 'dummy_media_center',
            'message' => 'Data keluhan berhasil diambil',
            'data' => $query->latest()->get(),
        ]);
    }

    public function detail($id)
    {
        $keluhan = DummyKeluhan::find($id);

        if (!$keluhan) {
            return response()->json([
                'success' => false,
                'source' => 'dummy_media_center',
                'message' => 'Keluhan tidak ditemukan',
                'data' => null,
            ], 404);
        }

        return response()->json([
            'success' => true,
            'source' => 'dummy_media_center',
            'message' => 'Detail keluhan berhasil diambil',
            'data' => $keluhan,
        ]);
    }

    public function selesai(Request $request)
    {
        $request->validate([
            'keluhan_id' => 'required|integer',
        ]);

        $keluhan = DummyKeluhan::find($request->keluhan_id);

        if (!$keluhan) {
            return response()->json([
                'success' => false,
                'source' => 'dummy_media_center',
                'message' => 'Keluhan tidak ditemukan',
                'data' => null,
            ], 404);
        }

        $keluhan->update([
            'status_id' => 3,
        ]);

        return response()->json([
            'success' => true,
            'source' => 'dummy_media_center',
            'message' => 'Keluhan berhasil ditandai selesai',
            'data' => $keluhan,
        ]);
    }

    public function hapus(Request $request)
    {
        $request->validate([
            'keluhan_id' => 'required|integer',
        ]);

        $keluhan = DummyKeluhan::find($request->keluhan_id);

        if (!$keluhan) {
            return response()->json([
                'success' => false,
                'source' => 'dummy_media_center',
                'message' => 'Keluhan tidak ditemukan',
                'data' => null,
            ], 404);
        }

        $keluhan->delete();

        return response()->json([
            'success' => true,
            'source' => 'dummy_media_center',
            'message' => 'Keluhan berhasil dihapus',
            'data' => null,
        ]);
    }
}