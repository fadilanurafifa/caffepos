<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AuthController extends Controller
{
    // Menampilkan halaman form login
    public function showLoginForm()
    {
        return view('admin.pages.auth.login'); // Mengembalikan tampilan form login
    }

    // Proses login pengguna
    public function login(Request $request)
    {
        // Validasi input email dan password
        $credentials = $request->validate([
            'email' => 'required|email',   // Email wajib diisi dan harus format email yang valid
            'password' => 'required|min:6', // Password wajib diisi dan minimal 6 karakter
        ]);

        // Coba melakukan autentikasi dengan kredensial yang diberikan
        if (Auth::attempt($credentials)) {
            $request->session()->regenerate(); // Regenerasi session untuk keamanan setelah login
            
            // Cek role user setelah login untuk menentukan halaman tujuan
            if (Auth::user()->role == 'kasir') {
                return redirect()->route('penjualan.index'); // Redirect ke halaman penjualan jika user adalah kasir
            } elseif (Auth::user()->role == 'chef') {
                return redirect()->route('chef.index'); // Redirect ke halaman chef jika user adalah chef
            }
            return redirect()->route('dashboard'); // Redirect ke dashboard jika bukan kasir atau chef
        }
        
        // Jika login gagal, kembalikan ke halaman login dengan pesan error
        return back()->withErrors([
            'email' => 'Email atau password salah!', // Menampilkan pesan kesalahan jika login gagal
        ])->onlyInput('email'); // Hanya input email yang tetap diisi, password dikosongkan
    }

    // Logout pengguna
    public function logout(Request $request)
    {
        Auth::logout(); // Logout user dari session
        $request->session()->invalidate(); // Menghapus session yang aktif
        $request->session()->regenerateToken(); // Regenerasi token CSRF untuk keamanan
        return redirect()->route('admin.login'); // Redirect ke halaman login setelah logout
    }
}
