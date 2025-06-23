<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class AdminLoginController extends Controller
{
    public function showLoginForm()
    {
        return view('auth.admin-login');
    }

    public function login(Request $request)
    {
        // 1. Validasi kedua input
        $request->validate([
            'username' => 'required|string',
            'password' => 'required|string',
        ]);

        // 2. Tentukan username & password yang benar (bisa Anda ganti)
        $correctUsername = 'admin';
        $correctPassword = 'arum1212'; // Ganti password lama agar lebih aman

        // 3. Cek username DAN password
        if ($request->username === $correctUsername && $request->password === $correctPassword) {

            // 4. Jika benar, simpan status admin dan nama admin ke session
            session([
                'is_admin' => true,
                'admin_name' => 'Mas Putra' // Nama yang akan ditampilkan
            ]);

            return redirect()->route('admin.dashboard');
        }

        // 5. Jika salah, kembali dengan pesan error
        return back()->withErrors(['username' => 'Username atau Password salah.'])->withInput($request->only('username'));
    }

    public function logout(Request $request)
    {
        $request->session()->forget(['is_admin', 'admin_name']);
        return redirect()->route('admin.login');
    }
}
