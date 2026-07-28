@extends('layouts.app')

@section('title', $jenisSampah->exists ? 'Ubah Jenis Sampah' : 'Tambah Jenis Sampah')

@section('content')
    <div class="mb-6">
        <h1 class="text-2xl font-bold text-[#1b4332]">{{ $jenisSampah->exists ? 'Ubah Jenis Sampah' : 'Tambah Jenis Sampah' }}</h1>
        <p class="text-slate-500 text-sm mt-1">Isi nama sampah dan harga per kilogram.</p>
    </div>

    <div class="bg-white rounded-2xl border border-slate-200 shadow-sm p-6 max-w-lg">
        <form method="POST" action="{{ $jenisSampah->exists ? route('admin.jenis-sampah.update', $jenisSampah) : route('admin.jenis-sampah.store') }}" class="space-y-4">
            @csrf
            @if($jenisSampah->exists) @method('PUT') @endif

            <div>
                <label class="block text-xs font-bold uppercase tracking-wide text-slate-500 mb-1.5">Nama Sampah</label>
                <input type="text" name="nama_sampah" required value="{{ old('nama_sampah', $jenisSampah->nama_sampah) }}"
                    class="w-full rounded-lg border border-slate-300 px-4 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-[#1b4332] focus:border-[#1b4332]"
                    placeholder="cth. Botol Kaca">
            </div>

            <div>
                <label class="block text-xs font-bold uppercase tracking-wide text-slate-500 mb-1.5">Harga per Kg (Rp)</label>
                <input type="number" name="harga_per_kg" min="0" required value="{{ old('harga_per_kg', $jenisSampah->harga_per_kg) }}"
                    class="w-full rounded-lg border border-slate-300 px-4 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-[#1b4332] focus:border-[#1b4332]"
                    placeholder="cth. 2000">
            </div>

            <div class="flex gap-3 pt-2">
                <button type="submit" class="bg-[#1b4332] hover:bg-[#2d6a4f] transition text-white font-semibold text-sm px-5 py-2.5 rounded-lg">
                    Simpan
                </button>
                <a href="{{ route('admin.jenis-sampah.index') }}" class="text-sm font-semibold text-slate-500 px-5 py-2.5 rounded-lg hover:bg-slate-100">
                    Batal
                </a>
            </div>
        </form>
    </div>
@endsection
