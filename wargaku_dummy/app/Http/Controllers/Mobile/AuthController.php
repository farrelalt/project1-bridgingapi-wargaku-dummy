<?php

namespace App\Http\Controllers\Mobile;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;

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

        $response = Http::post(config('services.bridging.base_url') . '/login', [
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
                ->with('success', 'Login berhasil');
        }

        return back()->with('error', 'Login gagal. Periksa user dan password.');
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

        $response = Http::post(config('services.bridging.base_url') . '/register', [
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

        return back()->with('error', 'Register gagal.');
    }

    public function logout()
    {
        session()->flush();

        return redirect()->route('login')
            ->with('success', 'Logout berhasil');
    }
}