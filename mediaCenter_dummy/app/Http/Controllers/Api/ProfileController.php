<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\DummyUser;
use Illuminate\Http\Request;

class ProfileController extends Controller
{
    public function profile(Request $request)
    {
        $token = $request->bearerToken() ?? $request->query('token') ?? $request->input('token');

        if (!$token) {
            return response()->json([
                'success' => false,
                'source' => 'dummy_media_center',
                'message' => 'Token tidak ditemukan',
                'data' => null,
            ], 401);
        }

        $user = DummyUser::where('token', $token)->first();

        if (!$user) {
            return response()->json([
                'success' => false,
                'source' => 'dummy_media_center',
                'message' => 'Token tidak valid',
                'data' => null,
            ], 401);
        }

        return response()->json([
            'success' => true,
            'source' => 'dummy_media_center',
            'message' => 'Profile berhasil diambil',
            'data' => $user,
        ]);
    }
}