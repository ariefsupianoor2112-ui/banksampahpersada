@extends('layouts.app')

@section('title', $penjual->name)

@section('content')
    <a href="{{ route('admin.nasabah.index') }}" class="text-sm text-slate-500 hover:text-slate-700">&larr; Kembali ke Data Nasabah</a>

    <div class="grid grid-cols-1 sm:grid-cols-3 gap-4 mt-4 mb-8">
        <div class="bg-white rounded-2xl border border-slate-200 shadow-sm p-5 sm:col-span-2">
            <h1 class="text-xl font-bold text-[#1b4332]">{{ $penjual->name }}</h1>
            <p class="text-sm text-slate-500 mt-1">{{ $penjual->email }}</p>
            <p class="text-sm text-slate-500">{{ $penjual->no_hp ?? '—' }} &middot; {{ $penjual->alamat ?? 'Alamat belum diisi' }}</p>
        </div>
        <div class="bg-[#1b4332] text-white rounded-2xl p-5 flex flex-col justify-center">
            <p class="text-xs uppercase tracking-wide text-emerald-300 font-semibold">Saldo Saat Ini</p>
            <p class="text-2xl font-bold mt-1">Rp {{ number_format($penjual->saldo, 0, ',', '.') }}</p>
        </div>
    </div>

    <div class="bg-white rounded-2xl border border-slate-200 shadow-sm overflow-hidden">
        <div class="px-6 py-4 border-b border-slate-100">
            <h2 class="font-bold text-[#1b4332]">Riwayat Transaksi</h2>
        </div>
        <table class="w-full text-sm">
            <thead>
                <tr class="text-left text-slate-400 border-b border-slate-100">
                    <th class="py-2.5 px-4">Tanggal</th>
                    <th class="py-2.5 px-4">Jenis</th>
                    <th class="py-2.5 px-4">Tipe</th>
                    <th class="py-2.5 px-4 text-right">Berat</th>
                    <th class="py-2.5 px-4 text-right">Nominal</th>
                    <th class="py-2.5 px-4">Status</th>
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
                        <td class="py-2.5 px-4 text-right">{{ $t->berat_kg ? number_format($t->berat_kg, 1, ',', '.') . ' kg' : '—' }}</td>
                        <td class="py-2.5 px-4 text-right font-semibold">Rp {{ number_format($t->total, 0, ',', '.') }}</td>
                        <td class="py-2.5 px-4">
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
                    <tr><td colspan="6" class="py-6 text-center text-slate-400">Belum ada transaksi.</td></tr>
                @endforelse
            </tbody>
        </table>
        <div class="px-6 py-4">{{ $riwayat->links() }}</div>
    </div>
@endsection
