<?php

namespace App\Http\Controllers\Api;

use App\Models\User;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class AuthController extends Controller
{
    public function login(Request $request)
{
    $credentials = $request->validate([
        'email' => 'required',
        'password' => 'required'
    ]);

    if (!Auth::attempt($credentials)) {
        return response()->json([
            'message' => 'Login gagal'
        ], 401);
    }

    $user = Auth::user();

    $token = $user->createToken('mobile-token')->plainTextToken;

    return response()->json([
        'token' => $token,
        'user' => $user
    ]);
}



}