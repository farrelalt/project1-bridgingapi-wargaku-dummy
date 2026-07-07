<?php

namespace App\Http\Controllers\Mobile;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Http\Client\ConnectionException;

class KeluhanController extends Controller
{
    public function index()
    {
        if (!session('token') && !config('services.wargaku.mock_mode')) {
            return redirect()->route('login')
                ->with('error', 'Silakan login terlebih dahulu.');
        }

        if (config('services.wargaku.mock_mode')) {
            $keluhan = $this->dummyKeluhan();

            return view('mobile.keluhan.index', compact('keluhan'));
        }

        try {
            $response = Http::timeout(10)
                ->acceptJson()
                ->withToken(session('token'))
                ->post(config('services.bridging.base_url') . '/keluhan');

            $json = $response->json();

            if (!$response->successful()) {
                return view('mobile.keluhan.index', [
                    'keluhan' => [],
                ])->with('error', $this->extractMessage($json, 'Gagal mengambil daftar keluhan. Status: ' . $response->status()));
            }

            $keluhan = $this->extractList($json);
            $keluhan = array_map(fn ($item) => $this->normalizeKeluhan($item), $keluhan);

            return view('mobile.keluhan.index', compact('keluhan'));

        } catch (ConnectionException $e) {
            return view('mobile.keluhan.index', [
                'keluhan' => [],
            ])->with('error', 'Bridging API belum berjalan di port 8000.');
        } catch (\Throwable $e) {
            return view('mobile.keluhan.index', [
                'keluhan' => [],
            ])->with('error', 'Terjadi error saat mengambil keluhan: ' . $e->getMessage());
        }
    }

    public function create()
    {
        if (!session('token') && !config('services.wargaku.mock_mode')) {
            return redirect()->route('login')
                ->with('error', 'Silakan login terlebih dahulu.');
        }

        if (config('services.wargaku.mock_mode')) {
            return view('mobile.keluhan.create', [
                'kategori' => $this->dummyKategori(),
                'kecamatan' => $this->dummyKecamatan(),
                'topik' => $this->dummyTopik(),
            ]);
        }

        try {
            $kategori = $this->getMasterData('/kategori');
            $kecamatan = $this->getMasterData('/kecamatan');
            $topik = $this->getMasterData('/topik');

            return view('mobile.keluhan.create', compact('kategori', 'kecamatan', 'topik'));

        } catch (\Throwable $e) {
            return view('mobile.keluhan.create', [
                'kategori' => [],
                'kecamatan' => [],
                'topik' => [],
            ])->with('error', 'Gagal mengambil master data dari Bridging API: ' . $e->getMessage());
        }
    }

    public function store(Request $request)
    {
        if (!session('token') && !config('services.wargaku.mock_mode')) {
            return redirect()->route('login')
                ->with('error', 'Silakan login terlebih dahulu.');
        }

        $request->validate([
            'judul' => 'required|string',
            'konten' => 'required|string',
            'kategori_id' => 'nullable',
            'kecamatan_id' => 'nullable',
            'topik_id' => 'nullable',
            'alamat' => 'nullable|string',
            'latitude' => 'nullable',
            'longitude' => 'nullable',
        ]);

        if (config('services.wargaku.mock_mode')) {
            return redirect()->route('keluhan.index')
                ->with('success', 'Keluhan berhasil dikirim dalam mode dummy.');
        }

        try {
            $user = session('user', []);

            $payload = [
                'judul' => $request->judul,

                // PENTING:
                // Form Wargaku memakai "konten",
                // Bridging API meminta field "keluhan".
                'keluhan' => $request->konten,

                'kategori_id' => $request->kategori_id,
                'kecamatan_id' => $request->kecamatan_id,
                'topik_id' => $request->topik_id,
                'alamat' => $request->alamat,
                'latitude' => $request->latitude,
                'longitude' => $request->longitude,

                'nama' => $user['name'] ?? $user['nama'] ?? null,
                'nomor_telepon' => $user['phone'] ?? null,
            ];

            $response = Http::timeout(10)
                ->acceptJson()
                ->withToken(session('token'))
                ->post(config('services.bridging.base_url') . '/keluhan/create', $payload);

            $json = $response->json();

            if ($response->successful() && ($json['success'] ?? true)) {
                return redirect()->route('keluhan.index')
                    ->with('success', 'Keluhan berhasil dikirim.');
            }

            return back()
                ->withInput()
                ->with('error', $this->extractMessage($json, 'Keluhan gagal dikirim. Status: ' . $response->status()));

        } catch (ConnectionException $e) {
            return back()
                ->withInput()
                ->with('error', 'Bridging API belum berjalan di port 8000.');
        } catch (\Throwable $e) {
            return back()
                ->withInput()
                ->with('error', 'Terjadi error saat mengirim keluhan: ' . $e->getMessage());
        }
    }

    public function show($id)
    {
        if (!session('token') && !config('services.wargaku.mock_mode')) {
            return redirect()->route('login')
                ->with('error', 'Silakan login terlebih dahulu.');
        }

        if (config('services.wargaku.mock_mode')) {
            $keluhan = [
                'id' => $id,
                'judul' => 'Contoh Detail Keluhan',
                'konten' => 'Ini adalah detail keluhan dummy dari Wargaku Dummy.',
                'status' => 'Menunggu verifikasi',
                'alamat' => 'Jl. Contoh Surabaya',
            ];

            return view('mobile.keluhan.detail', compact('keluhan'));
        }

        try {
            $response = Http::timeout(10)
                ->acceptJson()
                ->withToken(session('token'))
                ->get(config('services.bridging.base_url') . '/keluhan/' . $id);

            $json = $response->json();

            if (!$response->successful()) {
                return view('mobile.keluhan.detail', [
                    'keluhan' => null,
                ])->with('error', $this->extractMessage($json, 'Gagal mengambil detail keluhan. Status: ' . $response->status()));
            }

            $keluhan = $this->extractDetail($json);
            $keluhan = $keluhan ? $this->normalizeKeluhan($keluhan) : null;

            return view('mobile.keluhan.detail', compact('keluhan'));

        } catch (ConnectionException $e) {
            return view('mobile.keluhan.detail', [
                'keluhan' => null,
            ])->with('error', 'Bridging API belum berjalan di port 8000.');
        } catch (\Throwable $e) {
            return view('mobile.keluhan.detail', [
                'keluhan' => null,
            ])->with('error', 'Terjadi error saat mengambil detail keluhan: ' . $e->getMessage());
        }
    }

    private function getMasterData(string $endpoint): array
    {
        $response = Http::timeout(10)
            ->acceptJson()
            ->get(config('services.bridging.base_url') . $endpoint);

        $json = $response->json();

        if (!$response->successful()) {
            return [];
        }

        return $this->extractList($json);
    }

    private function extractList(?array $json): array
    {
        if (!$json) {
            return [];
        }

        $payload = $json['data'] ?? $json;

        if (isset($payload['data']) && is_array($payload['data'])) {
            $payload = $payload['data'];
        }

        if (isset($payload['items']) && is_array($payload['items'])) {
            $payload = $payload['items'];
        }

        if (is_array($payload) && array_is_list($payload)) {
            return $payload;
        }

        return [];
    }

    private function extractDetail(?array $json): ?array
    {
        if (!$json) {
            return null;
        }

        $payload = $json['data'] ?? $json;

        if (isset($payload['data']) && is_array($payload['data'])) {
            $payload = $payload['data'];
        }

        if (isset($payload[0]) && is_array($payload[0])) {
            return $payload[0];
        }

        return is_array($payload) ? $payload : null;
    }

    private function normalizeKeluhan(array $item): array
    {
        return [
            'id' => $item['id'] ?? $item['keluhan_id'] ?? null,
            'judul' => $item['judul'] ?? $item['title'] ?? 'Tanpa Judul',

            // Media Center/Bridging bisa pakai "keluhan",
            // sedangkan view Wargaku pakai "konten".
            'konten' => $item['konten'] ?? $item['keluhan'] ?? $item['isi'] ?? '-',

            'status' => $item['status'] ?? $item['status_keluhan'] ?? 'Menunggu verifikasi',
            'alamat' => $item['alamat'] ?? $item['lokasi'] ?? '-',
            'tanggal' => $item['tanggal'] ?? $item['created_at'] ?? null,
        ];
    }

    private function extractMessage(?array $json, string $fallback): string
    {
        if (!$json) {
            return $fallback;
        }

        return data_get($json, 'message')
            ?? data_get($json, 'data.message')
            ?? data_get($json, 'data.error')
            ?? $fallback;
    }

    private function dummyKeluhan(): array
    {
        return [
            [
                'id' => 1,
                'judul' => 'Jalan rusak di depan kos',
                'konten' => 'Jalan berlubang dan membahayakan pengendara.',
                'status' => 'Menunggu verifikasi',
                'alamat' => 'Jl. Contoh Surabaya',
            ],
            [
                'id' => 2,
                'judul' => 'Lampu jalan mati',
                'konten' => 'Lampu jalan mati sejak 3 hari lalu.',
                'status' => 'Diproses',
                'alamat' => 'Jl. Kertajaya Surabaya',
            ],
        ];
    }

    private function dummyKategori(): array
    {
        return [
            ['id' => 1, 'nama' => 'Infrastruktur'],
            ['id' => 2, 'nama' => 'Penerangan Jalan'],
            ['id' => 3, 'nama' => 'Kebersihan'],
            ['id' => 4, 'nama' => 'Keamanan'],
        ];
    }

    private function dummyKecamatan(): array
    {
        return [
            ['id' => 1, 'nama' => 'Gubeng'],
            ['id' => 2, 'nama' => 'Sukolilo'],
            ['id' => 3, 'nama' => 'Wonokromo'],
            ['id' => 4, 'nama' => 'Rungkut'],
        ];
    }

    private function dummyTopik(): array
    {
        return [
            ['id' => 1, 'nama' => 'Jalan Rusak'],
            ['id' => 2, 'nama' => 'Lampu Jalan Mati'],
            ['id' => 3, 'nama' => 'Sampah Menumpuk'],
            ['id' => 4, 'nama' => 'Gangguan Ketertiban'],
        ];
    }
}