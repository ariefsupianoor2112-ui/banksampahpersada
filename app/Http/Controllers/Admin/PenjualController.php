<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;

class PenjualController extends Controller
{
    public function index()
    {
        $penjual = User::where('role', 'penjual')
            ->withSum(['transaksis as total_setor' => fn ($q) => $q->where('tipe', 'setor')->where('status', 'approved')], 'total')
            ->withSum(['transaksis as total_tarik' => fn ($q) => $q->where('tipe', 'tarik')->where('status', 'approved')], 'total')
            ->orderBy('name')
            ->get();

        return view('admin.penjual.index', compact('penjual'));
    }

   public function show(User $penjual)
    {
        abort_if($penjual->role !== 'penjual', 404);

        $riwayat = $penjual->transaksis()->with('jenisSampah')->latest()->paginate(15);

        return view('admin.penjual.show', compact('penjual', 'riwayat'));
    }

    public function destroy(User $penjual)
    {
        abort_if($penjual->role !== 'penjual', 404);

        $penjual->delete();

        return redirect()->route('admin.nasabah.index')->with('status', 'Data nasabah berhasil dihapus.');
    }
}
