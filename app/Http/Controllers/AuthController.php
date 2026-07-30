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

  // Proses Login
    public function login(Request $request)
    {
        $request->validate([
            'identitas' => ['required', 'string'],
            'password' => ['required'],
        ]);

        $identitas = $request->input('identitas');
        $field = filter_var($identitas, FILTER_VALIDATE_EMAIL) ? 'email' : 'kode';

        $credentials = [
            $field => $field === 'kode' ? strtoupper($identitas) : $identitas,
            'password' => $request->input('password'),
        ];

        if (Auth::attempt($credentials, $request->boolean('remember'))) {
            $request->session()->regenerate();

            // Arahkan berdasarkan role
            if (Auth::user()->role === 'admin') {
                return redirect()->intended('/admin/dashboard');
            }

            return redirect()->intended('/penjual/dashboard');
        }

        return back()->withErrors([
            'identitas' => 'ID Nasabah/Email atau password salah.',
        ])->onlyInput('identitas');
    }
        return back()->withErrors([
            'email' => 'Email atau password salah.',
        ])->onlyInput('email');
    }

    // Tampilkan Form Registrasi (khusus penjual/nasabah)
    public function registerForm()
    {
        return view('auth.register');
    }

    // Proses Registrasi
    public function register(Request $request)
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users,email'],
            'no_hp' => ['nullable', 'string', 'max:20'],
            'alamat' => ['nullable', 'string', 'max:255'],
            'password' => ['required', 'string', 'min:6', 'confirmed'],
        ]);

      $user = User::create([
            'kode' => $this->generateKodeNasabah(),
            'name' => $validated['name'],
            'email' => $validated['email'],
            'no_hp' => $validated['no_hp'] ?? null,
            'alamat' => $validated['alamat'] ?? null,
            'password' => bcrypt($validated['password']),
            'role' => 'penjual',
        ]);

        Auth::login($user);
        $request->session()->regenerate();

        return redirect()->intended('/penjual/dashboard')->with('status', "Akun berhasil dibuat. ID Nasabah kamu: {$user->kode} (catat baik-baik untuk login selanjutnya).");
    }

    private function generateKodeNasabah(): string
    {
        $last = User::where('role', 'penjual')->whereNotNull('kode')->orderByDesc('id')->value('kode');
        $number = $last ? ((int) substr($last, 2)) + 1 : 1;

        return 'NS' . str_pad((string) $number, 3, '0', STR_PAD_LEFT);
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
