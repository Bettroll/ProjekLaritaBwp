<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class AuthController extends Controller
{
    // 1. Tampilkan Halaman Login
    public function showLogin() {
        return view('login'); // Langsung panggil file login.blade.php
    }

    // 2. Proses Login
    public function login(Request $request) {
        // [POIN 5] Validasi Input
        $request->validate([
            'email' => 'required|email',
            'password' => 'required',
        ], [
            'email.required' => 'Email wajib diisi ya!',
            'password.required' => 'Passwordnya jangan lupa diisi!',
        ]);

        $credentials = $request->only('email', 'password');

        // [POIN 15] Menggunakan AUTH Laravel
        if (Auth::attempt($credentials)) {
            $request->session()->regenerate();

            // Cek Role User yang sedang login
            if (Auth::user()->role == 'admin') {
                return redirect()->intended('/admin/dashboard');
            } else {
                return redirect()->intended('/home');
            }
        }

        // [POIN 10] Session Flash (Pesan Error jika gagal login)
        return back()->with('error', 'Aduh! Email atau password kamu salah.');
    }

    // 3. Tampilkan Halaman Register
    public function showRegister() {
        return view('register'); // Langsung panggil file register.blade.php
    }

    // 4. Proses Register (Khusus Member)
    public function register(Request $request) {
        // Validasi pendaftaran
        $request->validate([
            'name' => 'required|string|max:100',
            'email' => 'required|email|unique:users',
            'password' => 'required|min:6|confirmed', // Harus ada input password_confirmation
        ], [
            'email.unique' => 'Email ini sudah terdaftar, pakai email lain ya.',
            'password.confirmed' => 'Konfirmasi password tidak cocok nih.',
        ]);

        // Simpan data ke database
        User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password), // Password WAJIB di-hash
            'role' => 'member',
            'points' => 0,
            'status' => 'active'
        ]);

        // [POIN 10] Session Flash (Pesan Sukses setelah daftar)
        return redirect('/login')->with('success', 'Hore! Akun kamu berhasil dibuat. Silakan login.');
    }

    // 5. Proses Logout
    public function logout(Request $request) {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        return redirect('/login');
    }
}