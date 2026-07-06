<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\DummyRating;
use Illuminate\Http\Request;

class RatingController extends Controller
{
    public function create(Request $request)
    {
        $request->validate([
            'keluhan_id' => 'required|integer',
        ]);

        $rating = DummyRating::create([
            'keluhan_id' => $request->keluhan_id,
            'responsivitas' => $request->responsivitas,
            'informasi' => $request->informasi,
            'tindak_lanjut' => $request->tindak_lanjut,
            'keramahan' => $request->keramahan,
            'kepuasan' => $request->kepuasan,
            'kritik_saran' => $request->kritik_saran,
        ]);

        return response()->json([
            'success' => true,
            'source' => 'dummy_media_center',
            'message' => 'Rating berhasil dibuat',
            'data' => $rating,
        ], 201);
    }

    public function view(Request $request)
    {
        $query = DummyRating::query();

        if ($request->filled('keluhan_id')) {
            $query->where('keluhan_id', $request->keluhan_id);
        }

        return response()->json([
            'success' => true,
            'source' => 'dummy_media_center',
            'message' => 'Data rating berhasil diambil',
            'data' => $query->latest()->get(),
        ]);
    }
}