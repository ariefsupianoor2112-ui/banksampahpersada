@extends('layouts.app')

@section('title', 'Dashboard Admin')

@section('content')
    <div class="mb-6">
        <h1 class="text-2xl font-bold text-[#1b4332]">Dashboard Admin</h1>
        <p class="text-slate-500 text-sm mt-1">Ringkasan aktivitas bank sampah hari ini.</p>
    </div>

    <!-- Stat cards -->
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4 mb-8">
        <div class="bg-white rounded-2xl border border-slate-200 shadow-sm p-5">
            <p class="text-xs text-slate-500 font-semibold uppercase tracking-wide">Total Nasabah</p>
            <p class="text-3xl font-bold text-[#1b4332] mt-2">{{ $totalPenjual }}</p>
        </div>
        <div class="bg-white rounded-2xl border border-slate-200 shadow-sm p-5">
            <p class="text-xs text-slate-500 font-semibold uppercase tracking-wide">Jenis Sampah</p>
            <p class="text-3xl font-bold text-[#1b4332] mt-2">{{ $totalJenisSampah }}</p>
        </div>
        <div class="bg-white rounded-2xl border border-slate-200 shadow-sm p-5">
            <p class="text-xs text-slate-500 font-semibold uppercase tracking-wide">Sampah Terkumpul</p>
            <p class="text-3xl font-bold text-[#1b4332] mt-2">{{ number_format($totalBeratKg, 1, ',', '.') }} <span class="text-base font-medium text-slate-400">kg</span></p>
        </div>
        <div class="bg-white rounded-2xl border border-slate-200 shadow-sm p-5">
            <p class="text-xs text-slate-500 font-semibold uppercase tracking-wide">Saldo Aktif Nasabah</p>
            <p class="text-3xl font-bold text-emerald-700 mt-2">Rp {{ number_format($totalSaldoAktif, 0, ',', '.') }}</p>
        </div>
    </div>

    <!-- Pengajuan menunggu persetujuan -->
    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4 mb-8">
        <a href="{{ route('admin.setoran.index') }}" class="flex items-center justify-between bg-amber-50 border border-amber-200 rounded-2xl p-5 hover:bg-amber-100 transition">
            <div>
                <p class="text-xs text-amber-700 font-semibold uppercase tracking-wide">Menunggu Persetujuan</p>
                <p class="text-2xl font-bold text-amber-800 mt-1">{{ $jumlahSetoranPending }} Setoran</p>
            </div>
            <span class="text-amber-600 text-sm font-semibold">Tinjau &rarr;</span>
        </a>
        <a href="{{ route('admin.pencairan.index') }}" class="flex items-center justify-between bg-rose-50 border border-rose-200 rounded-2xl p-5 hover:bg-rose-100 transition">
            <div>
                <p class="text-xs text-rose-700 font-semibold uppercase tracking-wide">Menunggu Persetujuan</p>
                <p class="text-2xl font-bold text-rose-800 mt-1">{{ $jumlahPencairanPending }} Pencairan</p>
            </div>
            <span class="text-rose-600 text-sm font-semibold">Tinjau &rarr;</span>
        </a>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-5 gap-6">
        <!-- Chart -->
        <div class="lg:col-span-2 bg-white rounded-2xl border border-slate-200 shadow-sm p-6">
            <h2 class="font-bold text-[#1b4332] mb-4">Sampah Terbanyak Disetor</h2>
            <canvas id="chartJenisSampah" height="220"></canvas>
        </div>

        <!-- Recent transactions -->
        <div class="lg:col-span-3 bg-white rounded-2xl border border-slate-200 shadow-sm p-6">
            <div class="flex items-center justify-between mb-4">
                <h2 class="font-bold text-[#1b4332]">Transaksi Terbaru</h2>
                <a href="{{ route('admin.transaksi.index') }}" class="text-sm text-emerald-700 font-semibold hover:underline">Lihat semua &rarr;</a>
            </div>
            <div class="overflow-x-auto">
                <table class="w-full text-sm">
                    <thead>
                        <tr class="text-left text-slate-400 border-b border-slate-100">
                            <th class="py-2 pr-3">Nasabah</th>
                            <th class="py-2 pr-3">Jenis</th>
                            <th class="py-2 pr-3">Tipe</th>
                            <th class="py-2 text-right">Nominal</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100">
                        @forelse($transaksiTerbaru as $t)
                            <tr>
                                <td class="py-2.5 pr-3">
                                    <span class="font-medium text-slate-800 block">{{ $t->user->name }}</span>
                                    <span class="text-xs text-slate-400 font-mono">{{ $t->user->id_nasabah ?? '-' }}</span>
                                </td>
                                <td class="py-2.5 pr-3 text-slate-500">{{ $t->jenisSampah->nama_sampah ?? '—' }}</td>
                                <td class="py-2.5 pr-3">
                                    <span class="px-2 py-0.5 rounded-full text-xs font-semibold {{ $t->tipe === 'setor' ? 'bg-emerald-100 text-emerald-700' : 'bg-amber-100 text-amber-700' }}">
                                        {{ $t->tipe === 'setor' ? 'Setoran' : 'Tarik Saldo' }}
                                    </span>
                                </td>
                                <td class="py-2.5 text-right font-semibold">Rp {{ number_format($t->total, 0, ',', '.') }}</td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="4" class="py-6 text-center text-slate-400">Belum ada transaksi.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/chart.js@4"></script>
    <script>
        const ctx = document.getElementById('chartJenisSampah');
        new Chart(ctx, {
            type: 'bar',
            data: {
                labels: @json($grafikJenisSampah->pluck('nama_sampah')),
                datasets: [{
                    label: 'Total Kg Disetor',
                    data: @json($grafikJenisSampah->pluck('total_berat')),
                    backgroundColor: '#2d6a4f',
                    borderRadius: 6,
                }]
            },
            options: {
                responsive: true,
                plugins: { legend: { display: false } },
                scales: { y: { beginAtZero: true } }
            }
        });
    </script>
@endsection
