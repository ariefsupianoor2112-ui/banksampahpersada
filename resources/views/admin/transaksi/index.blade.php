@extends('layouts.app')

@section('title', 'Transaksi')

@section('content')
    <div class="flex flex-wrap items-center justify-between gap-3 mb-6">
        <div>
            <h1 class="text-2xl font-bold text-[#1b4332]">Transaksi</h1>
            <p class="text-slate-500 text-sm mt-1">Riwayat setoran sampah dan penarikan saldo nasabah.</p>
        </div>
        <a href="{{ route('admin.transaksi.create') }}" class="bg-[#1b4332] hover:bg-[#2d6a4f] transition text-white text-sm font-semibold px-4 py-2.5 rounded-lg">
            + Catat Setoran
        </a>
    </div>

    <div class="flex gap-2 mb-4 text-sm">
        <a href="{{ route('admin.transaksi.index') }}" class="px-3 py-1.5 rounded-lg {{ request('tipe') ? 'bg-slate-100 text-slate-600' : 'bg-[#1b4332] text-white' }}">Semua</a>
        <a href="{{ route('admin.transaksi.index', ['tipe' => 'setor']) }}" class="px-3 py-1.5 rounded-lg {{ request('tipe') === 'setor' ? 'bg-[#1b4332] text-white' : 'bg-slate-100 text-slate-600' }}">Setoran</a>
        <a href="{{ route('admin.transaksi.index', ['tipe' => 'tarik']) }}" class="px-3 py-1.5 rounded-lg {{ request('tipe') === 'tarik' ? 'bg-[#1b4332] text-white' : 'bg-slate-100 text-slate-600' }}">Tarik Saldo</a>
    </div>

    <div class="bg-white rounded-2xl border border-slate-200 shadow-sm overflow-hidden">
        <table class="w-full text-sm">
            <thead>
                <tr class="bg-[#1b4332] text-white text-left">
                    <th class="py-3 px-4">Tanggal</th>
                    <th class="py-3 px-4">Nasabah</th>
                    <th class="py-3 px-4">Jenis</th>
                    <th class="py-3 px-4">Tipe</th>
                    <th class="py-3 px-4 text-right">Berat</th>
                    <th class="py-3 px-4 text-right">Nominal</th>
                    <th class="py-3 px-4">Status</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-slate-100">
                @forelse($transaksi as $t)
                    <tr class="hover:bg-emerald-50/50">
                        <td class="py-3 px-4 text-slate-500">{{ $t->created_at->format('d M Y, H:i') }}</td>
                        <td class="py-3 px-4 font-medium">{{ $t->user->name }}</td>
                        <td class="py-3 px-4 text-slate-500">{{ $t->jenisSampah->nama_sampah ?? '—' }}</td>
                        <td class="py-3 px-4">
                            <span class="px-2 py-0.5 rounded-full text-xs font-semibold {{ $t->tipe === 'setor' ? 'bg-emerald-100 text-emerald-700' : 'bg-amber-100 text-amber-700' }}">
                                {{ $t->tipe === 'setor' ? 'Setoran' : 'Tarik Saldo' }}
                            </span>
                        </td>
                        <td class="py-3 px-4 text-right">{{ $t->berat_kg ? number_format($t->berat_kg, 1, ',', '.') . ' kg' : '—' }}</td>
                        <td class="py-3 px-4 text-right font-semibold">Rp {{ number_format($t->total, 0, ',', '.') }}</td>
                        <td class="py-3 px-4">
                            @if($t->status === 'pending')
                                <span class="px-2 py-0.5 rounded-full text-xs font-semibold bg-slate-200 text-slate-600">Menunggu</span>
                            @elseif($t->status === 'approved')
                                <span class="px-2 py-0.5 rounded-full text-xs font-semibold bg-emerald-100 text-emerald-700">Disetujui</span>
                            @else
                                <span class="px-2 py-0.5 rounded-full text-xs font-semibold bg-red-100 text-red-700">Ditolak</span>
                            @endif
                        </td>
                    </tr>
                @empty
                    <tr><td colspan="7" class="py-6 text-center text-slate-400">Belum ada transaksi.</td></tr>
                @endforelse
            </tbody>
        </table>
        <div class="px-6 py-4">{{ $transaksi->links() }}</div>
    </div>
@endsection
