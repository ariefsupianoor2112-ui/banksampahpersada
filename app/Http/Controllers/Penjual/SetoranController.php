<?php

namespace App\Http\Controllers\Penjual;

use App\Http\Controllers\Controller;
use App\Models\JenisSampah;
use App\Models\Transaksi;
use Illuminate\Http\Request;

class SetoranController extends Controller
{
    // Form "Ajukan Setoran"
    public function create()
    {
        $jenisSampah = JenisSampah::orderBy('nama_sampah')->get();

        return view('penjual.setoran.create', compact('jenisSampah'));
    }

    // Simpan pengajuan setoran (berstatus pending, menunggu admin)
    public function store(Request $request)
    {
        $validated = $request->validate([
            'jenis_sampah_id' => ['required', 'exists:jenis_sampah,id'],
            'berat_kg' => ['required', 'numeric', 'min:0.01'],
            'keterangan' => ['nullable', 'string', 'max:255'],
        ]);

        $jenisSampah = JenisSampah::findOrFail($validated['jenis_sampah_id']);
        $total = (int) round($jenisSampah->harga_per_kg * $validated['berat_kg']);

        Transaksi::create([
            'user_id' => $request->user()->id,
            'jenis_sampah_id' => $jenisSampah->id,
            'tipe' => 'setor',
            'berat_kg' => $validated['berat_kg'],
            'harga_per_kg' => $jenisSampah->harga_per_kg,
            'total' => $total,
            'keterangan' => $validated['keterangan'] ?? null,
            'admin_id' => null,
            'status' => 'pending',
            'sumber' => 'nasabah',
        ]);

        return redirect()->route('penjual.dashboard')
            ->with('status', 'Setoran berhasil diajukan. Menunggu persetujuan admin ya, ' . $request->user()->name . '.');
    }
}
