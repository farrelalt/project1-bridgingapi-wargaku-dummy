<?php

namespace App\Http\Controllers\Mobile;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Http\Client\ConnectionException;

class RatingController extends Controller
{
    public function create($id)
    {
        return view('mobile.rating', [
            'keluhanId' => $id,
        ]);
    }

    public function store(Request $request, $id)
    {
        if (!session('token') && !config('services.wargaku.mock_mode')) {
            return redirect()->route('login')
                ->with('error', 'Silakan login terlebih dahulu.');
        }

        $request->validate([
            'responsivitas' => 'required|integer|min:1|max:5',
            'informasi' => 'required|integer|min:1|max:5',
            'tindak_lanjut' => 'required|integer|min:1|max:5',
            'keramahan' => 'required|integer|min:1|max:5',
            'kepuasan' => 'required|integer|min:1|max:5',
            'kritik_saran' => 'nullable|string',
        ]);

        if (config('services.wargaku.mock_mode')) {
            return redirect()->route('keluhan.show', $id)
                ->with('success', 'Rating berhasil dikirim dalam mode dummy.');
        }

        try {
            $response = Http::timeout(10)
                ->acceptJson()
                ->withToken(session('token'))
                ->post(config('services.bridging.base_url') . '/keluhan/rating', [
                    'keluhan_id' => $id,
                    'responsivitas' => $request->responsivitas,
                    'informasi' => $request->informasi,
                    'tindak_lanjut' => $request->tindak_lanjut,
                    'keramahan' => $request->keramahan,
                    'kepuasan' => $request->kepuasan,
                    'kritik_saran' => $request->kritik_saran,
                ]);

            $json = $response->json();

            if ($response->successful() && ($json['success'] ?? true)) {
                return redirect()->route('keluhan.show', $id)
                    ->with('success', 'Rating berhasil dikirim.');
            }

            return back()
                ->withInput()
                ->with('error', data_get($json, 'message') ?? data_get($json, 'data.message') ?? 'Rating gagal dikirim. Status: ' . $response->status());

        } catch (ConnectionException $e) {
            return back()
                ->withInput()
                ->with('error', 'Bridging API belum berjalan di port 8000.');
        } catch (\Throwable $e) {
            return back()
                ->withInput()
                ->with('error', 'Terjadi error saat mengirim rating: ' . $e->getMessage());
        }
    }
}