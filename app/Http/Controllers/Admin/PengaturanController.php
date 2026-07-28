<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;

class PengaturanController extends Controller
{
    // Form pengaturan akun admin
    public function index(Request $request)
    {
        $admin = $request->user();

        return view('admin.pengaturan.index', compact('admin'));
    }

    // Simpan perubahan profil / password admin
    public function update(Request $request)
    {
        $admin = $request->user();

        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'email', Rule::unique('users', 'email')->ignore($admin->id)],
            'no_hp' => ['nullable', 'string', 'max:20'],
            'password' => ['nullable', 'confirmed', 'min:8'],
        ]);

        $admin->name = $validated['name'];
        $admin->email = $validated['email'];
        $admin->no_hp = $validated['no_hp'] ?? null;

        if (! empty($validated['password'])) {
            $admin->password = Hash::make($validated['password']);
        }

        $admin->save();

        return back()->with('status', 'Pengaturan akun berhasil diperbarui.');
    }
}
