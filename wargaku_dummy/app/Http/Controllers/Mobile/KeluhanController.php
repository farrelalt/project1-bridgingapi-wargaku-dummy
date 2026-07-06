<?php

namespace App\Http\Controllers\Mobile;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;

class KeluhanController extends Controller
{
    public function index()
    {
        $response = Http::withToken(session('token'))
            ->post(config('services.bridging.base_url') . '/keluhan');

        $keluhan = [];

        if ($response->successful()) {
            $keluhan = $response->json()['data'] ?? [];
        }

        return view('mobile.keluhan.index', compact('keluhan'));
    }

    public function create()
    {
        $kategori = Http::get(config('services.bridging.base_url') . '/kategori')->json()['data'] ?? [];
        $kecamatan = Http::get(config('services.bridging.base_url') . '/kecamatan')->json()['data'] ?? [];
        $topik = Http::get(config('services.bridging.base_url') . '/topik')->json()['data'] ?? [];

        return view('mobile.keluhan.create', compact('kategori', 'kecamatan', 'topik'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'judul' => 'required',
            'konten' => 'required',
            'kategori_id' => 'required',
            'kecamatan_id' => 'required',
            'topik_id' => 'required',
        ]);

        $response = Http::withToken(session('token'))
            ->post(config('services.bridging.base_url') . '/keluhan/create', [
                'judul' => $request->judul,
                'konten' => $request->konten,
                'kategori_id' => $request->kategori_id,
                'kecamatan_id' => $request->kecamatan_id,
                'topik_id' => $request->topik_id,
                'alamat' => $request->alamat,
                'latitude' => $request->latitude,
                'longitude' => $request->longitude,
            ]);

        if ($response->successful()) {
            return redirect()->route('keluhan.index')
                ->with('success', 'Keluhan berhasil dikirim');
        }

        return back()->with('error', 'Keluhan gagal dikirim');
    }

    public function show($id)
    {
        $response = Http::withToken(session('token'))
            ->get(config('services.bridging.base_url') . '/keluhan/' . $id);

        $keluhan = null;

        if ($response->successful()) {
            $keluhan = $response->json()['data'] ?? null;
        }

        return view('mobile.keluhan.detail', compact('keluhan'));
    }
}