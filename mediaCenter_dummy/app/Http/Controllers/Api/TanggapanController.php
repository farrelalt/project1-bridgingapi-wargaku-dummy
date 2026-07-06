<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\DummyTanggapan;
use Illuminate\Http\Request;

class TanggapanController extends Controller
{
    public function create(Request $request)
    {
        $request->validate([
            'keluhan_id' => 'required|integer',
            'tanggapan' => 'required|string',
        ]);

        $tanggapan = DummyTanggapan::create([
            'keluhan_id' => $request->keluhan_id,
            'tanggapan' => $request->tanggapan,
            'foto' => $request->foto,
            'tanggal_tanggapan' => now()->toDateString(),
            'tanggal_tindak_lanjut' => $request->tanggal_tindak_lanjut,
        ]);

        return response()->json([
            'success' => true,
            'source' => 'dummy_media_center',
            'message' => 'Tanggapan berhasil dibuat',
            'data' => $tanggapan,
        ], 201);
    }

    public function index(Request $request)
    {
        $query = DummyTanggapan::query();

        if ($request->filled('keluhan_id')) {
            $query->where('keluhan_id', $request->keluhan_id);
        }

        return response()->json([
            'success' => true,
            'source' => 'dummy_media_center',
            'message' => 'Data tanggapan berhasil diambil',
            'data' => $query->latest()->get(),
        ]);
    }
}