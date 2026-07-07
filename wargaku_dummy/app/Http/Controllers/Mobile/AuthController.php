<?php

namespace App\Http\Controllers\Mobile;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Http\Client\ConnectionException;

class AuthController extends Controller
{
    public function showLogin()
    {
        return view('mobile.login');
    }

    public function login(Request $request)
    {
        $request->validate([
            'user' => 'required',
            'password' => 'required',
        ]);

        if (config('services.wargaku.mock_mode')) {
            session([
                'token' => 'dummy-token-wargaku',
                'user' => [
                    'user' => $request->user,
                    'name' => 'User Dummy Wargaku',
                ],
            ]);

            return redirect()->route('dashboard')
                ->with('success', 'Login berhasil dalam mode dummy.');
        }

        try {
            $response = Http::timeout(10)
                ->acceptJson()
                ->post(config('services.bridging.base_url') . '/login', [
                    'user' => $request->user,
                    'password' => $request->password,
                ]);

            $json = $response->json();

            if ($response->successful() && ($json['success'] ?? true)) {
                $token = $this->extractToken($json);

                if (!$token) {
                    return back()->with('error', 'Login berhasil dari API, tetapi token tidak ditemukan di response.');
                }

                session([
                    'token' => $token,
                    'user' => $this->extractUser($json, $request->user),
                ]);

                return redirect()->route('dashboard')
                    ->with('success', 'Login berhasil.');
            }

            return back()->with('error', $this->extractMessage($json, 'Login gagal dari Bridging API. Status: ' . $response->status()));

        } catch (ConnectionException $e) {
            return back()->with('error', 'Bridging API belum berjalan di port 8000.');
        } catch (\Throwable $e) {
            return back()->with('error', 'Terjadi error saat login: ' . $e->getMessage());
        }
    }

    public function showRegister()
    {
        return view('mobile.register');
    }

    public function register(Request $request)
    {
        $request->validate([
            'user' => 'required',
            'password' => 'required|min:6',
            'name' => 'required',
            'nik' => 'nullable',
            'phone' => 'nullable',
        ]);

        if (config('services.wargaku.mock_mode')) {
            return redirect()->route('login')
                ->with('success', 'Register berhasil dalam mode dummy. Silakan login.');
        }

        try {
            $response = Http::timeout(10)
                ->acceptJson()
                ->post(config('services.bridging.base_url') . '/register', [
                    'user' => $request->user,
                    'password' => $request->password,
                    'name' => $request->name,
                    'nik' => $request->nik,
                    'phone' => $request->phone,
                ]);

            $json = $response->json();

            if ($response->successful() && ($json['success'] ?? true)) {
                return redirect()->route('login')
                    ->with('success', 'Register berhasil. Silakan login.');
            }

            return back()
                ->withInput()
                ->with('error', $this->extractMessage($json, 'Register gagal dari Bridging API. Status: ' . $response->status()));

        } catch (ConnectionException $e) {
            return back()
                ->withInput()
                ->with('error', 'Bridging API belum berjalan di port 8000.');
        } catch (\Throwable $e) {
            return back()
                ->withInput()
                ->with('error', 'Terjadi error saat register: ' . $e->getMessage());
        }
    }

    public function logout()
    {
        session()->flush();

        return redirect()->route('login')
            ->with('success', 'Logout berhasil.');
    }

    private function extractToken(?array $json): ?string
    {
        if (!$json) {
            return null;
        }

        return data_get($json, 'token')
            ?? data_get($json, 'access_token')
            ?? data_get($json, 'data.token')
            ?? data_get($json, 'data.access_token')
            ?? data_get($json, 'data.data.token')
            ?? data_get($json, 'data.data.access_token');
    }

    private function extractUser(?array $json, string $fallbackUser): array
    {
        if (!$json) {
            return [
                'user' => $fallbackUser,
                'name' => $fallbackUser,
            ];
        }

        $user = data_get($json, 'data.user')
            ?? data_get($json, 'data.data.user')
            ?? data_get($json, 'data.data')
            ?? [];

        if (!is_array($user)) {
            $user = [];
        }

        return [
            'id' => $user['id'] ?? null,
            'user' => $user['user'] ?? $fallbackUser,
            'name' => $user['name'] ?? $user['nama'] ?? $fallbackUser,
            'phone' => $user['phone'] ?? null,
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
}