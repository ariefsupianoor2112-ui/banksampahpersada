@extends('layouts.app')

@section('title', 'Dashboard Saya')

@section('content')
    <div class="mb-6 flex flex-col sm:flex-row sm:items-end sm:justify-between gap-4">
        <div>
            <h1 class="text-2xl font-bold text-[#1b4332]">Halo, {{ $user->name }} 👋</h1>
            <p class="text-slate-500 text-sm mt-1">Ini ringkasan tabungan sampahmu.</p>
        </div>
        <div class="flex gap-2">
            <a href="{{ route('penjual.setoran.create') }}" class="bg-[#1b4332] hover:bg-[#2d6a4f] transition text-white font-semibold text-sm px-4 py-2.5 rounded-lg">
                + Ajukan Setoran
            </a>
            <a href="{{ route('penjual.tarik.create') }}" class="bg-amber-600 hover:bg-amber-700 transition text-white font-semibold text-sm px-4 py-2.5 rounded-lg">
                Tarik Saldo
            </a>
        </div>
    </div>

    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4 mb-8">
        <div class="bg-[#1b4332] text-white rounded-2xl p-6">
            <p class="text-xs uppercase tracking-wide text-emerald-300 font-semibold">Saldo Tabungan</p>
            <p class="text-3xl font-bold mt-2">Rp {{ number_format($user->saldo, 0, ',', '.') }}</p>
        </div>
        <div class="bg-white rounded-2xl border border-slate-200 shadow-sm p-6">
            <p class="text-xs uppercase tracking-wide text-slate-500 font-semibold">Total Sampah Disetor</p>
            <p class="text-3xl font-bold text-[#1b4332] mt-2">{{ number_format($user->total_berat, 1, ',', '.') }} <span class="text-base font-medium text-slate-400">kg</span></p>
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-5 gap-6">
        <div class="lg:col-span-3 bg-white rounded-2xl border border-slate-200 shadow-sm overflow-hidden">
            <div class="px-6 py-4 border-b border-slate-100">
                <h2 class="font-bold text-[#1b4332]">Riwayat Transaksi</h2>
            </div>
            <table class="w-full text-sm">
                <thead>
                    <tr class="text-left text-slate-400 border-b border-slate-100">
                        <th class="py-2.5 px-4">Tanggal</th>
                        <th class="py-2.5 px-4">Jenis</th>
                        <th class="py-2.5 px-4">Tipe</th>
                        <th class="py-2.5 px-4">Status</th>
                        <th class="py-2.5 px-4 text-right">Nominal</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100">
                    @forelse($riwayat as $t)
                        <tr>
                            <td class="py-2.5 px-4 text-slate-500">{{ $t->created_at->format('d M Y, H:i') }}</td>
                            <td class="py-2.5 px-4">{{ $t->jenisSampah->nama_sampah ?? '—' }}</td>
                            <td class="py-2.5 px-4">
                                <span class="px-2 py-0.5 rounded-full text-xs font-semibold {{ $t->tipe === 'setor' ? 'bg-emerald-100 text-emerald-700' : 'bg-amber-100 text-amber-700' }}">
                                    {{ $t->tipe === 'setor' ? 'Setoran' : 'Tarik Saldo' }}
                                </span>
                            </td>
                            <td class="py-2.5 px-4">
                                @if($t->status === 'pending')
                                    <span class="px-2 py-0.5 rounded-full text-xs font-semibold bg-slate-200 text-slate-600">Menunggu</span>
                                @elseif($t->status === 'approved')
                                    <span class="px-2 py-0.5 rounded-full text-xs font-semibold bg-emerald-100 text-emerald-700">Disetujui</span>
                                @else
                                    <span class="px-2 py-0.5 rounded-full text-xs font-semibold bg-red-100 text-red-700">Ditolak</span>
                                @endif
                            </td>
                            <td class="py-2.5 px-4 text-right font-semibold">Rp {{ number_format($t->total, 0, ',', '.') }}</td>
                        </tr>
                    @empty
                        <tr><td colspan="5" class="py-6 text-center text-slate-400">Belum ada transaksi. Segera setor sampah pertamamu!</td></tr>
                    @endforelse
                </tbody>
            </table>
            <div class="px-6 py-4">{{ $riwayat->links() }}</div>
        </div>

        <div class="lg:col-span-2 bg-white rounded-2xl border border-slate-200 shadow-sm p-6">
            <h2 class="font-bold text-[#1b4332] mb-4">Daftar Harga Sampah</h2>
            <ul class="divide-y divide-slate-100 text-sm">
                @foreach($daftarHarga as $h)
                    <li class="py-2.5 flex items-center justify-between">
                        <span>{{ $h->nama_sampah }}</span>
                        <span class="font-semibold text-emerald-700">Rp {{ number_format($h->harga_per_kg, 0, ',', '.') }}/kg</span>
                    </li>
                @endforeach
            </ul>
        </div>
    </div>
@endsection
