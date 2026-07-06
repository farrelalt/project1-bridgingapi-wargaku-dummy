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
            $response = Http::timeout(5)->post(config('services.bridging.base_url') . '/login', [
                'user' => $request->user,
                'password' => $request->password,
            ]);

            if ($response->successful()) {
                $data = $response->json();

                session([
                    'token' => $data['token'] ?? null,
                    'user' => $data['data'] ?? null,
                ]);

                return redirect()->route('dashboard')
                    ->with('success', 'Login berhasil.');
            }

            $data = $response->json();
            $message = $data['message'] ?? 'Login gagal dari Bridging API. Status: ' . $response->status();

            return back()->with('error', $message);

        } catch (ConnectionException $e) {
            return back()->with('error', 'Bridging API belum berjalan di port 8000.');
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
            'password' => 'required',
            'name' => 'required',
            'nik' => 'required',
            'phone' => 'required',
        ]);

        if (config('services.wargaku.mock_mode')) {
            return redirect()->route('login')
                ->with('success', 'Register berhasil dalam mode dummy. Silakan login.');
        }

        try {
            $response = Http::timeout(5)->post(config('services.bridging.base_url') . '/register', [
                'user' => $request->user,
                'password' => $request->password,
                'name' => $request->name,
                'nik' => $request->nik,
                'phone' => $request->phone,
            ]);

            if ($response->successful()) {
                return redirect()->route('login')
                    ->with('success', 'Register berhasil. Silakan login.');
            }

            $data = $response->json();
            $message = $data['message'] ?? 'Register gagal dari Bridging API. Status: ' . $response->status();

            return back()->with('error', $message);

        } catch (ConnectionException $e) {
            return back()->with('error', 'Bridging API belum berjalan di port 8000.');
        }
    }

    public function logout()
    {
        session()->flush();

        return redirect()->route('login')
            ->with('success', 'Logout berhasil.');
    }
}