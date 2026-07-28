<?php

namespace App\Http\Controllers\Penjual;

use App\Http\Controllers\Controller;
use App\Models\Transaksi;
use Illuminate\Http\Request;

class TarikController extends Controller
{
    // Form "Tarik Saldo"
    public function create(Request $request)
    {
        $user = $request->user();

        return view('penjual.tarik.create', compact('user'));
    }

    // Simpan pengajuan tarik saldo (berstatus pending, menunggu admin)
    public function store(Request $request)
    {
        $user = $request->user();

        $validated = $request->validate([
            'total' => ['required', 'integer', 'min:1'],
            'keterangan' => ['nullable', 'string', 'max:255'],
        ]);

        // saldo yang benar-benar bisa ditarik = saldo aktif dikurangi pengajuan tarik lain yang masih pending
        $saldoTersedia = $user->saldo - $user->saldo_tertahan;

        if ($validated['total'] > $saldoTersedia) {
            return back()
                ->withErrors(['total' => 'Jumlah melebihi saldo yang tersedia (Rp ' . number_format($saldoTersedia, 0, ',', '.') . ').'])
                ->withInput();
        }

        Transaksi::create([
            'user_id' => $user->id,
            'jenis_sampah_id' => null,
            'tipe' => 'tarik',
            'berat_kg' => null,
            'harga_per_kg' => null,
            'total' => $validated['total'],
            'keterangan' => $validated['keterangan'] ?? null,
            'admin_id' => null,
            'status' => 'pending',
            'sumber' => 'nasabah',
        ]);

        return redirect()->route('penjual.dashboard')
            ->with('status', 'Permintaan tarik saldo berhasil diajukan. Menunggu persetujuan admin.');
    }
}
