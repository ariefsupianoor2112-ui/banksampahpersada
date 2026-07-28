<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\JenisSampah;
use App\Models\Transaksi;
use App\Models\User;
use Illuminate\Http\Request;

class TransaksiController extends Controller
{
    public function index(Request $request)
    {
        $transaksi = Transaksi::with(['user', 'jenisSampah'])
            ->when($request->filled('tipe'), fn ($q) => $q->where('tipe', $request->tipe))
            ->latest()
            ->paginate(20)
            ->withQueryString();

        return view('admin.transaksi.index', compact('transaksi'));
    }

    public function create()
    {
        $penjual = User::where('role', 'penjual')->orderBy('name')->get();
        $jenisSampah = JenisSampah::orderBy('nama_sampah')->get();

        return view('admin.transaksi.create', compact('penjual', 'jenisSampah'));
    }

    // Simpan transaksi setoran sampah dari penjual
    public function store(Request $request)
    {
        $validated = $request->validate([
            'user_id' => ['required', 'exists:users,id'],
            'jenis_sampah_id' => ['required', 'exists:jenis_sampah,id'],
            'berat_kg' => ['required', 'numeric', 'min:0.01'],
            'keterangan' => ['nullable', 'string', 'max:255'],
        ]);

        $jenisSampah = JenisSampah::findOrFail($validated['jenis_sampah_id']);
        $total = (int) round($jenisSampah->harga_per_kg * $validated['berat_kg']);

        Transaksi::create([
            'user_id' => $validated['user_id'],
            'jenis_sampah_id' => $jenisSampah->id,
            'tipe' => 'setor',
            'berat_kg' => $validated['berat_kg'],
            'harga_per_kg' => $jenisSampah->harga_per_kg,
            'total' => $total,
            'keterangan' => $validated['keterangan'] ?? null,
            'admin_id' => $request->user()->id,
            'status' => 'approved',
            'sumber' => 'admin',
        ]);

        return redirect()->route('admin.transaksi.index')->with('status', 'Setoran sampah berhasil dicatat.');
    }

    // Proses penarikan saldo oleh penjual
    public function tarik(Request $request)
    {
        $validated = $request->validate([
            'user_id' => ['required', 'exists:users,id'],
            'total' => ['required', 'integer', 'min:1'],
            'keterangan' => ['nullable', 'string', 'max:255'],
        ]);

        $penjual = User::findOrFail($validated['user_id']);

        if ($validated['total'] > $penjual->saldo) {
            return back()->withErrors(['total' => 'Jumlah penarikan melebihi saldo nasabah (Rp ' . number_format($penjual->saldo, 0, ',', '.') . ').']);
        }

        Transaksi::create([
            'user_id' => $penjual->id,
            'jenis_sampah_id' => null,
            'tipe' => 'tarik',
            'berat_kg' => null,
            'harga_per_kg' => null,
            'total' => $validated['total'],
            'keterangan' => $validated['keterangan'] ?? null,
            'admin_id' => $request->user()->id,
            'status' => 'approved',
            'sumber' => 'admin',
        ]);

        return back()->with('status', 'Penarikan saldo berhasil dicatat.');
    }
}
