<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Transaksi;
use Illuminate\Http\Request;

class PencairanController extends Controller
{
    // Daftar pengajuan tarik saldo dari nasabah
    public function index(Request $request)
    {
        $status = $request->get('status', 'pending');

        $pencairan = Transaksi::with(['user'])
            ->where('tipe', 'tarik')
            ->diajukanNasabah()
            ->when($status !== 'semua', fn ($q) => $q->where('status', $status))
            ->latest()
            ->paginate(15)
            ->withQueryString();

        $jumlahPending = Transaksi::where('tipe', 'tarik')->diajukanNasabah()->pending()->count();

        return view('admin.pencairan.index', compact('pencairan', 'status', 'jumlahPending'));
    }

    // Setujui pencairan (uang dianggap sudah dibayar tunai ke nasabah)
    public function approve(Request $request, Transaksi $pencairan)
    {
        abort_if($pencairan->tipe !== 'tarik', 404);

        if ($pencairan->status !== 'pending') {
            return back()->withErrors(['status' => 'Pengajuan ini sudah pernah diproses.']);
        }

        if ($pencairan->total > $pencairan->user->saldo) {
            return back()->withErrors(['status' => 'Saldo nasabah tidak lagi mencukupi untuk pencairan ini.']);
        }

        $pencairan->update([
            'status' => 'approved',
            'admin_id' => $request->user()->id,
        ]);

        return back()->with('status', 'Pencairan saldo untuk ' . $pencairan->user->name . ' berhasil disetujui.');
    }

    // Tolak pencairan
    public function reject(Request $request, Transaksi $pencairan)
    {
        abort_if($pencairan->tipe !== 'tarik', 404);

        $validated = $request->validate([
            'catatan_admin' => ['nullable', 'string', 'max:255'],
        ]);

        $pencairan->update([
            'status' => 'rejected',
            'admin_id' => $request->user()->id,
            'catatan_admin' => $validated['catatan_admin'] ?? null,
        ]);

        return back()->with('status', 'Pencairan saldo untuk ' . $pencairan->user->name . ' ditolak.');
    }
}
