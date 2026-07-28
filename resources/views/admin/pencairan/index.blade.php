@extends('layouts.app')

@section('title', 'Pengajuan Pencairan')

@section('content')
    <div class="mb-6">
        <h1 class="text-2xl font-bold text-[#1b4332]">Pengajuan Pencairan Saldo</h1>
        <p class="text-slate-500 text-sm mt-1">Permintaan tarik saldo yang diajukan sendiri oleh nasabah.</p>
    </div>

    <!-- Filter status -->
    <div class="flex flex-wrap gap-2 mb-5">
        @foreach(['pending' => 'Menunggu', 'approved' => 'Disetujui', 'rejected' => 'Ditolak', 'semua' => 'Semua'] as $key => $label)
            <a href="{{ route('admin.pencairan.index', ['status' => $key]) }}"
                class="px-4 py-1.5 rounded-full text-sm font-semibold border transition {{ $status === $key ? 'bg-[#1b4332] text-white border-[#1b4332]' : 'bg-white text-slate-600 border-slate-200 hover:border-[#1b4332]' }}">
                {{ $label }}
            </a>
        @endforeach
    </div>

    <div class="bg-white rounded-2xl border border-slate-200 shadow-sm overflow-hidden">
        <table class="w-full text-sm">
            <thead>
                <tr class="text-left text-slate-400 border-b border-slate-100">
                    <th class="py-3 px-4">Tanggal</th>
                    <th class="py-3 px-4">Nasabah</th>
                    <th class="py-3 px-4">Keterangan</th>
                    <th class="py-3 px-4 text-right">Saldo Saat Ini</th>
                    <th class="py-3 px-4 text-right">Jumlah Diminta</th>
                    <th class="py-3 px-4">Status</th>
                    <th class="py-3 px-4 text-center">Aksi</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-slate-100">
                @forelse($pencairan as $p)
                    <tr>
                        <td class="py-3 px-4 text-slate-500 whitespace-nowrap">{{ $p->created_at->format('d M Y, H:i') }}</td>
                        <td class="py-3 px-4 font-medium">{{ $p->user->name }}</td>
                        <td class="py-3 px-4 text-slate-500">{{ $p->keterangan ?? '—' }}</td>
                        <td class="py-3 px-4 text-right">Rp {{ number_format($p->user->saldo, 0, ',', '.') }}</td>
                        <td class="py-3 px-4 text-right font-semibold">Rp {{ number_format($p->total, 0, ',', '.') }}</td>
                        <td class="py-3 px-4">
                            @if($p->status === 'pending')
                                <span class="px-2 py-0.5 rounded-full text-xs font-semibold bg-slate-200 text-slate-600">Menunggu</span>
                            @elseif($p->status === 'approved')
                                <span class="px-2 py-0.5 rounded-full text-xs font-semibold bg-emerald-100 text-emerald-700">Disetujui</span>
                            @else
                                <span class="px-2 py-0.5 rounded-full text-xs font-semibold bg-red-100 text-red-700">Ditolak</span>
                            @endif
                        </td>
                        <td class="py-3 px-4">
                            @if($p->status === 'pending')
                                <div class="flex items-center justify-center gap-2">
                                    <form method="POST" action="{{ route('admin.pencairan.approve', $p) }}">
                                        @csrf
                                        <button class="px-3 py-1.5 rounded-lg text-xs font-semibold bg-emerald-600 hover:bg-emerald-700 text-white transition">Setujui</button>
                                    </form>
                                    <form method="POST" action="{{ route('admin.pencairan.reject', $p) }}" onsubmit="return confirm('Tolak permintaan ini?')">
                                        @csrf
                                        <button class="px-3 py-1.5 rounded-lg text-xs font-semibold bg-red-100 hover:bg-red-200 text-red-700 transition">Tolak</button>
                                    </form>
                                </div>
                            @else
                                <p class="text-center text-xs text-slate-400">—</p>
                            @endif
                        </td>
                    </tr>
                @empty
                    <tr><td colspan="7" class="py-8 text-center text-slate-400">Tidak ada pengajuan pencairan.</td></tr>
                @endforelse
            </tbody>
        </table>
        <div class="px-6 py-4">{{ $pencairan->links() }}</div>
    </div>
@endsection
