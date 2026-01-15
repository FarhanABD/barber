<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AuthController extends Controller
{
    /**
     * Menampilkan formulir login.
     */
    public function login()
    {
        return view('login');
    }

    /**
     * Menangani permintaan login yang diajukan.
     */
    public function loginSubmit(Request $request)
    {
        // 1. Validasi kredensial yang dikirim pengguna (email & password)
        $credentials = $request->validate([
            'email' => ['required', 'email'],
            'password' => ['required'],
        ]);

        // 2. Coba proses otentikasi
        if (Auth::attempt($credentials)) {
            // Otentikasi Berhasil
            $request->session()->regenerate();

            // Ambil objek user yang baru saja login
            $user = Auth::user();

            // 3. Pengecekan Role (Hak Akses)

            // Jika user adalah ADMIN, arahkan ke dashboard admin
            if ($user->role === 'admin') {
                // Menggunakan 'admin/dashboard' atau nama route admin yang sudah kita definisikan
                return redirect()->intended('admin/dashboard'); 
            }

            // Jika role lain (customer, barber), arahkan ke dashboard umum
            return redirect()->intended('admin/dashboard');
        }

        // Otentikasi Gagal
        return back()->withErrors([
            'email' => 'Kredensial yang diberikan tidak cocok dengan data kami.',
        ])->onlyInput('email');
    }
}