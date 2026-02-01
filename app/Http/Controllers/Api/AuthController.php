<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class AuthController extends Controller
{
    public function login(Request $request)
{
    $request->validate([
        'email' => 'required|email',
        'password' => 'required'
    ]);

    $user = User::where('email', $request->email)->first();

    if (!$user || !Hash::check($request->password, $user->password)) {
        return response()->json([
            'status' => false,
            'message' => 'Email atau password salah'
        ], 401);
    }

    // pastikan hanya kasir
    if ($user->role !== 'kasir') {
        return response()->json([
            'status' => false,
            'message' => 'Akun bukan kasir'
        ], 403);
    }

    $token = $user->createToken('mobile-pos')->plainTextToken;

    return response()->json([
        'status' => true,
        'token' => $token,
        'user' => [
            'id' => $user->id,
            'name' => $user->name,
            'email' => $user->email,
            'role' => $user->role
        ]
    ]);
}

}