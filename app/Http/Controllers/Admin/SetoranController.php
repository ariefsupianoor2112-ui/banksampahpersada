<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Transaksi;
use Illuminate\Http\Request;

class SetoranController extends Controller
{
    // Daftar pengajuan setoran dari nasabah
    public function index(Request $request)
    {
        $status = $request->get('status', 'pending');

        $setoran = Transaksi::with(['user', 'jenisSampah'])
            ->where('tipe', 'setor')
            ->diajukanNasabah()
            ->when($status !== 'semua', fn ($q) => $q->where('status', $status))
            ->latest()
            ->paginate(15)
            ->withQueryString();

        $jumlahPending = Transaksi::where('tipe', 'setor')->diajukanNasabah()->pending()->count();

        return view('admin.setoran.index', compact('setoran', 'status', 'jumlahPending'));
    }

    // Setujui pengajuan setoran
    public function approve(Request $request, Transaksi $setoran)
    {
        abort_if($setoran->tipe !== 'setor', 404);

        if ($setoran->status !== 'pending') {
            return back()->withErrors(['status' => 'Pengajuan ini sudah pernah diproses.']);
        }

        $setoran->update([
            'status' => 'approved',
            'admin_id' => $request->user()->id,
        ]);

        return back()->with('status', 'Setoran dari ' . $setoran->user->name . ' berhasil disetujui.');
    }

    // Tolak pengajuan setoran
    public function reject(Request $request, Transaksi $setoran)
    {
        abort_if($setoran->tipe !== 'setor', 404);

        $validated = $request->validate([
            'catatan_admin' => ['nullable', 'string', 'max:255'],
        ]);

        $setoran->update([
            'status' => 'rejected',
            'admin_id' => $request->user()->id,
            'catatan_admin' => $validated['catatan_admin'] ?? null,
        ]);

        return back()->with('status', 'Setoran dari ' . $setoran->user->name . ' ditolak.');
    }
}
