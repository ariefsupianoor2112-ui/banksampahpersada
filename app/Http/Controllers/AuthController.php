<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\User;

class AuthController extends Controller
{
    // Tampilkan Form Login
    public function loginForm()
    {
        return view('auth.login');
    }

    // Proses Login (Bisa Pakai ID Nasabah ATAU Email)
    public function login(Request $request)
    {
        $request->validate([
            'login'    => ['required', 'string'],
            'password' => ['required'],
        ]);

        $fieldType = filter_var($request->login, FILTER_VALIDATE_EMAIL) ? 'email' : 'id_nasabah';

        $credentials = [
            $fieldType => $request->login,
            'password'  => $request->password,
        ];

        if (Auth::attempt($credentials, $request->boolean('remember'))) {
            $request->session()->regenerate();

            if (Auth::user()->role === 'admin') {
                return redirect()->intended('/admin/dashboard');
            }

            return redirect()->intended('/penjual/dashboard');
        }

        return back()->withErrors([
            'login' => 'ID Nasabah / Email atau password salah.',
        ])->onlyInput('login');
    }

    // Tampilkan Form Registrasi
    public function registerForm()
    {
        return view('auth.register');
    }

    // Proses Registrasi (Auto-generate ID Nasabah: NS001, NS002, dst)
    public function register(Request $request)
    {
        $validated = $request->validate([
            'name'     => ['required', 'string', 'max:255'],
            'email'    => ['required', 'string', 'email', 'max:255', 'unique:users,email'],
            'no_hp'    => ['nullable', 'string', 'max:20'],
            'alamat'   => ['nullable', 'string', 'max:255'],
            'password' => ['required', 'string', 'min:6', 'confirmed'],
        ]);

        // Cari ID Nasabah terakhir
        $lastPenjual = User::whereNotNull('id_nasabah')
            ->orderBy('id', 'desc')
            ->first();

        $nextNumber = 1;
        if ($lastPenjual && preg_match('/NS(\d+)/', $lastPenjual->id_nasabah, $matches)) {
            $nextNumber = (int)$matches[1] + 1;
        }

        $idNasabah = 'NS' . str_pad($nextNumber, 3, '0', STR_PAD_LEFT);

        $user = User::create([
            'id_nasabah' => $idNasabah,
            'name'       => $validated['name'],
            'email'      => $validated['email'],
            'no_hp'      => $validated['no_hp'] ?? null,
            'alamat'     => $validated['alamat'] ?? null,
            'password'   => bcrypt($validated['password']),
            'role'       => 'penjual',
        ]);

        Auth::login($user);
        $request->session()->regenerate();

        return redirect()->intended('/penjual/dashboard')
            ->with('status', "Akun berhasil dibuat! ID Nasabah Anda: {$idNasabah}");
    }

    // Logout
    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect('/login');
    }
}
