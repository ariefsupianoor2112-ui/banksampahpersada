<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\JenisSampah;
use App\Models\Transaksi;
use App\Models\User;

class DashboardController extends Controller
{
    public function index()
    {
        $totalPenjual = User::where('role', 'penjual')->count();
        $totalJenisSampah = JenisSampah::count();
        $totalBeratKg = (float) Transaksi::where('tipe', 'setor')->where('status', 'approved')->sum('berat_kg');
        $totalSaldoAktif = (int) Transaksi::where('tipe', 'setor')->where('status', 'approved')->sum('total')
            - (int) Transaksi::where('tipe', 'tarik')->where('status', 'approved')->sum('total');

        $jumlahSetoranPending = Transaksi::where('tipe', 'setor')->diajukanNasabah()->pending()->count();
        $jumlahPencairanPending = Transaksi::where('tipe', 'tarik')->diajukanNasabah()->pending()->count();

        $grafikJenisSampah = JenisSampah::withSum(['transaksis as total_berat' => function ($q) {
            $q->where('tipe', 'setor')->where('status', 'approved');
        }], 'berat_kg')->orderByDesc('total_berat')->take(6)->get();

        $transaksiTerbaru = Transaksi::with(['user', 'jenisSampah'])
            ->latest()
            ->take(8)
            ->get();

        return view('admin.dashboard', compact(
            'totalPenjual',
            'totalJenisSampah',
            'totalBeratKg',
            'totalSaldoAktif',
            'jumlahSetoranPending',
            'jumlahPencairanPending',
            'grafikJenisSampah',
            'transaksiTerbaru'
        ));
    }
}
