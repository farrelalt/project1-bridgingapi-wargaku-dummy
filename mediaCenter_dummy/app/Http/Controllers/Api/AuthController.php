<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\DummyUser;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class AuthController extends Controller
{
    public function login(Request $request)
    {
        $request->validate([
            'user' => 'required|string',
            'password' => 'required|string',
        ]);

        $user = DummyUser::where('user', $request->user)->first();

        if (!$user || !Hash::check($request->password, $user->password)) {
            return response()->json([
                'success' => false,
                'source' => 'dummy_media_center',
                'message' => 'User atau password salah',
                'data' => null,
            ], 401);
        }

        $token = 'dummy-token-' . Str::random(40);

        $user->update([
            'token' => $token,
        ]);

        return response()->json([
            'success' => true,
            'source' => 'dummy_media_center',
            'message' => 'Login berhasil',
            'data' => [
                'id' => $user->id,
                'user' => $user->user,
                'name' => $user->name,
                'nik' => $user->nik,
                'phone' => $user->phone,
                'token' => $token,
            ],
        ]);
    }

    public function register(Request $request)
    {
        $request->validate([
            'user' => 'required|string|unique:dummy_users,user',
            'password' => 'required|string|min:6',
            'name' => 'required|string',
            'nik' => 'nullable|string',
            'phone' => 'nullable|string',
        ]);

        $user = DummyUser::create([
            'user' => $request->user,
            'password' => Hash::make($request->password),
            'name' => $request->name,
            'nik' => $request->nik,
            'phone' => $request->phone,
            'jenis_kelamin' => $request->jenis_kelamin,
            'tanggal_lahir' => $request->tanggal_lahir,
            'alamat' => $request->alamat,
            'kecamatan_id' => $request->kecamatan_id,
            'kelurahan_id' => $request->kelurahan_id,
            'token' => 'dummy-token-' . Str::random(40),
        ]);

        return response()->json([
            'success' => true,
            'source' => 'dummy_media_center',
            'message' => 'Register berhasil',
            'data' => [
                'id' => $user->id,
                'user' => $user->user,
                'name' => $user->name,
                'nik' => $user->nik,
                'phone' => $user->phone,
                'token' => $user->token,
            ],
        ], 201);
    }
}