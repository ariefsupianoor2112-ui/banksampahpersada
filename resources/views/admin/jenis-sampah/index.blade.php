@extends('layouts.app')

@section('title', 'Jenis Sampah')

@section('content')
    <div class="flex items-center justify-between mb-6">
        <div>
            <h1 class="text-2xl font-bold text-[#1b4332]">Jenis &amp; Harga Sampah</h1>
            <p class="text-slate-500 text-sm mt-1">Kelola daftar jenis sampah beserta harga per kilogram.</p>
        </div>
        <a href="{{ route('admin.jenis-sampah.create') }}" class="bg-[#1b4332] hover:bg-[#2d6a4f] transition text-white text-sm font-semibold px-4 py-2.5 rounded-lg">
            + Tambah Jenis Sampah
        </a>
    </div>

    <div class="bg-white rounded-2xl border border-slate-200 shadow-sm overflow-hidden">
        <table class="w-full text-sm">
            <thead>
                <tr class="bg-[#1b4332] text-white text-left">
                    <th class="py-3 px-4">Jenis Sampah</th>
                    <th class="py-3 px-4 text-right">Harga / Kg</th>
                    <th class="py-3 px-4 text-center">Jumlah Transaksi</th>
                    <th class="py-3 px-4 text-right">Aksi</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-slate-100">
                @forelse($data_sampah as $item)
                    <tr class="hover:bg-emerald-50/50">
                        <td class="py-3 px-4 font-semibold">{{ $item->nama_sampah }}</td>
                        <td class="py-3 px-4 text-right text-emerald-700 font-bold">Rp {{ number_format($item->harga_per_kg, 0, ',', '.') }}</td>
                        <td class="py-3 px-4 text-center text-slate-500">{{ $item->transaksis_count }}</td>
                        <td class="py-3 px-4 text-right">
                            <div class="flex justify-end gap-2">
                                <a href="{{ route('admin.jenis-sampah.edit', $item) }}" class="text-xs font-semibold px-3 py-1.5 rounded-lg bg-slate-100 hover:bg-slate-200 transition">Ubah</a>
                                <form action="{{ route('admin.jenis-sampah.destroy', $item) }}" method="POST" onsubmit="return confirm('Hapus jenis sampah ini?');">
                                    @csrf @method('DELETE')
                                    <button class="text-xs font-semibold px-3 py-1.5 rounded-lg bg-red-50 text-red-600 hover:bg-red-100 transition">Hapus</button>
                                </form>
                            </div>
                        </td>
                    </tr>
                @empty
                    <tr><td colspan="4" class="py-6 text-center text-slate-400">Belum ada jenis sampah.</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>
@endsection
