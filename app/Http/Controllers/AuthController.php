<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class AuthController extends Controller
{
    /**
     * Tampilkan Form Login
     */
    public function loginForm()
    {
        return view('auth.login');
    }

    /**
     * Proses Login (Mendukung ID Nasabah maupun Email)
     */
    public function login(Request $request)
    {
        $request->validate([
            'login' => 'required|string',
            'password' => 'required|string',
        ], [
            'login.required' => 'ID Nasabah atau Email wajib diisi.',
            'password.required' => 'Kata sandi wajib diisi.',
        ]);

        // Cek apakah input berupa email atau ID Nasabah
        $fieldType = filter_var($request->login, FILTER_VALIDATE_EMAIL) ? 'email' : 'id_nasabah';

        $credentials = [
            $fieldType => $request->login,
            'password' => $request->password,
        ];

        if (Auth::attempt($credentials, $request->has('remember'))) {
            $request->session()->regenerate();

            if (Auth::user()->role === 'admin') {
                return redirect()->route('admin.dashboard');
            }

            return redirect()->route('penjual.dashboard');
        }

        return back()->withErrors([
            'login' => 'ID Nasabah / Email atau kata sandi yang Anda masukkan salah.',
        ])->onlyInput('login');
    }

    /**
     * Tampilkan Form Register
     */
    public function registerForm()
    {
        return view('auth.register');
    }

    /**
     * Proses Register (Otomatis Generate ID Nasabah)
     */
    public function register(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:8|confirmed',
            'no_hp' => 'nullable|string|max:15',
            'alamat' => 'nullable|string',
        ]);

        // Generate ID Nasabah otomatis (Contoh: NS001, NS002, dst)
        $lastUser = User::whereNotNull('id_nasabah')->orderBy('id', 'desc')->first();
        $nextNumber = 1;
        if ($lastUser && $lastUser->id_nasabah) {
            $numberOnly = (int) preg_replace('/[^0-9]/', '', $lastUser->id_nasabah);
            $nextNumber = $numberOnly + 1;
        }
        $idNasabah = 'NS' . str_pad($nextNumber, 3, '0', STR_PAD_LEFT);

        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'id_nasabah' => $idNasabah,
            'no_hp' => $request->no_hp,
            'alamat' => $request->alamat,
            'role' => 'penjual',
        ]);

        Auth::login($user);

        return redirect()->route('penjual.dashboard')->with('status', 'Registrasi berhasil! ID Nasabah Anda: ' . $idNasabah);
    }

    /**
     * Tampilkan Form Lupa Password
     */
    public function forgotPasswordForm()
    {
        return view('auth.forgot-password');
    }

    /**
     * Proses Kirim Link Reset Password
     */
    public function sendResetLink(Request $request)
    {
        $request->validate([
            'email' => 'required|email|exists:users,email',
        ], [
            'email.required' => 'Email wajib diisi.',
            'email.email' => 'Format email tidak valid.',
            'email.exists' => 'Email ini tidak terdaftar di sistem kami.',
        ]);

        return back()->with('status', 'Tautan reset password telah dikirim ke email kamu!');
    }

    /**
     * Proses Logout
     */
    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('login');
    }
}
