@extends('layouts.app')

@section('title', 'Data Nasabah')

@section('content')
    <div class="mb-6">
        <h1 class="text-2xl font-bold text-[#1b4332]">Data Nasabah</h1>
        <p class="text-slate-500 text-sm mt-1">Daftar nasabah beserta saldo tabungan sampah masing-masing.</p>
    </div>

    <div class="bg-white rounded-2xl border border-slate-200 shadow-sm overflow-hidden">
        <table class="w-full text-sm">
            <thead>
                <tr class="bg-[#1b4332] text-white text-left">
                    <th class="py-3 px-4">Nama</th>
                    <th class="py-3 px-4">Kontak</th>
                    <th class="py-3 px-4 text-right">Saldo</th>
                    <th class="py-3 px-4 text-right">Aksi</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-slate-100">
                @forelse($penjual as $p)
                    @php $saldo = (int) ($p->total_setor ?? 0) - (int) ($p->total_tarik ?? 0); @endphp
               <tr class="hover:bg-emerald-50/50">
                        <td class="py-3 px-4 font-mono text-xs font-semibold text-[#1b4332]">{{ $p->kode ?? '—' }}</td>
                        <td class="py-3 px-4">
                            <p class="font-semibold">{{ $p->name }}</p>
                            <p class="text-xs text-slate-400">{{ $p->email }}</p>
                        </td>
                        <td class="py-3 px-4 text-slate-500">{{ $p->no_hp ?? '—' }}</td>
                        <td class="py-3 px-4 text-right font-bold text-emerald-700">Rp {{ number_format($saldo, 0, ',', '.') }}</td>
                        <td class="py-3 px-4 text-right space-x-1">
                            <a href="{{ route('admin.nasabah.show', $p) }}" class="text-xs font-semibold px-3 py-1.5 rounded-lg bg-slate-100 hover:bg-slate-200 transition">Lihat Detail</a>
                            <form action="{{ route('admin.nasabah.destroy', $p) }}" method="POST" class="inline" onsubmit="return confirm('Yakin ingin menghapus nasabah ini? Semua riwayat transaksinya juga akan ikut terhapus.');">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="text-xs font-semibold px-3 py-1.5 rounded-lg bg-red-50 text-red-600 hover:bg-red-100 transition">Hapus</button>
                            </form>
                        </td>
                    </tr>
                @empty
                    <tr><td colspan="5" class="py-6 text-center text-slate-400">Belum ada nasabah terdaftar.</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>
@endsection
