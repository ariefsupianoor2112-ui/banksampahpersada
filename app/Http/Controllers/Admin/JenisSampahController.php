<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\JenisSampah;
use Illuminate\Http\Request;

class JenisSampahController extends Controller
{
    public function index()
    {
        $data_sampah = JenisSampah::withCount('transaksis')->orderBy('nama_sampah')->get();
        return view('admin.jenis-sampah.index', compact('data_sampah'));
    }

    public function create()
    {
        return view('admin.jenis-sampah.form', ['jenisSampah' => new JenisSampah()]);
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'nama_sampah' => ['required', 'string', 'max:255'],
            'harga_per_kg' => ['required', 'integer', 'min:0'],
        ]);

        JenisSampah::create($validated);

        return redirect()->route('admin.jenis-sampah.index')->with('status', 'Jenis sampah berhasil ditambahkan.');
    }

    public function edit(JenisSampah $jenisSampah)
    {
        return view('admin.jenis-sampah.form', compact('jenisSampah'));
    }

    public function update(Request $request, JenisSampah $jenisSampah)
    {
        $validated = $request->validate([
            'nama_sampah' => ['required', 'string', 'max:255'],
            'harga_per_kg' => ['required', 'integer', 'min:0'],
        ]);

        $jenisSampah->update($validated);

        return redirect()->route('admin.jenis-sampah.index')->with('status', 'Jenis sampah berhasil diperbarui.');
    }

    public function destroy(JenisSampah $jenisSampah)
    {
        $jenisSampah->delete();

        return back()->with('status', 'Jenis sampah berhasil dihapus.');
    }
}
