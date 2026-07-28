<?php

namespace App\Http\Controllers\Penjual;

use App\Http\Controllers\Controller;
use App\Models\JenisSampah;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function index(Request $request)
    {
        $user = $request->user();
        $riwayat = $user->transaksis()->with('jenisSampah')->latest()->paginate(10);
        $daftarHarga = JenisSampah::orderBy('nama_sampah')->get();

        return view('penjual.dashboard', compact('user', 'riwayat', 'daftarHarga'));
    }
}
