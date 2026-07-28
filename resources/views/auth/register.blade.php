@extends('layouts.guest')

@section('title', 'Daftar Nasabah')

@section('content')
    <div class="bg-[#1b4332] text-white rounded-2xl p-7 shadow-lg">
        <p class="text-[11px] tracking-widest uppercase text-emerald-300 font-semibold">Gabung Sekarang</p>
        <h1 class="text-xl sm:text-2xl font-bold mt-1 mb-2">Daftar Jadi Nasabah</h1>
        <p class="text-emerald-100 text-sm leading-relaxed">
            Buat akun untuk mulai menabung sampah dan memantau saldo kamu secara digital.
        </p>
    </div>

    <div class="bg-white rounded-2xl border border-slate-200 shadow-sm p-7 mt-6">
        <h2 class="text-xl font-bold text-[#1b4332]">Buat Akun Baru</h2>
        <p class="text-slate-500 text-sm mt-1 mb-6">Semua kolom bertanda * wajib diisi.</p>

        <form action="{{ route('register') }}" method="POST" class="space-y-4">
            @csrf

            <div>
                <label class="block text-xs font-bold uppercase tracking-wide text-slate-500 mb-1.5">Nama Lengkap *</label>
                <input type="text" name="name" required value="{{ old('name') }}"
                    class="w-full rounded-lg border border-slate-300 px-4 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-[#1b4332] focus:border-[#1b4332]"
                    placeholder="Nama sesuai KTP">
            </div>

            <div>
                <label class="block text-xs font-bold uppercase tracking-wide text-slate-500 mb-1.5">Email *</label>
                <input type="email" name="email" required value="{{ old('email') }}"
                    class="w-full rounded-lg border border-slate-300 px-4 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-[#1b4332] focus:border-[#1b4332]"
                    placeholder="email@domain.com">
            </div>

            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                <div>
                    <label class="block text-xs font-bold uppercase tracking-wide text-slate-500 mb-1.5">No. HP</label>
                    <input type="text" name="no_hp" value="{{ old('no_hp') }}"
                        class="w-full rounded-lg border border-slate-300 px-4 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-[#1b4332] focus:border-[#1b4332]"
                        placeholder="08xxxxxxxxxx">
                </div>
                <div>
                    <label class="block text-xs font-bold uppercase tracking-wide text-slate-500 mb-1.5">Alamat</label>
                    <input type="text" name="alamat" value="{{ old('alamat') }}"
                        class="w-full rounded-lg border border-slate-300 px-4 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-[#1b4332] focus:border-[#1b4332]"
                        placeholder="Jl. Contoh No. 1">
                </div>
            </div>

            <div>
                <label class="block text-xs font-bold uppercase tracking-wide text-slate-500 mb-1.5">Kata Sandi *</label>
                <input type="password" name="password" required
                    class="w-full rounded-lg border border-slate-300 px-4 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-[#1b4332] focus:border-[#1b4332]"
                    placeholder="Minimal 6 karakter">
            </div>

            <div>
                <label class="block text-xs font-bold uppercase tracking-wide text-slate-500 mb-1.5">Ulangi Kata Sandi *</label>
                <input type="password" name="password_confirmation" required
                    class="w-full rounded-lg border border-slate-300 px-4 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-[#1b4332] focus:border-[#1b4332]"
                    placeholder="Ulangi kata sandi">
            </div>

            <button type="submit"
                class="w-full bg-[#1b4332] hover:bg-[#2d6a4f] transition text-white font-semibold rounded-lg py-2.5">
                Daftar
            </button>
        </form>

        <p class="text-center text-sm text-slate-500 mt-6">
            Sudah punya akun?
            <a href="{{ route('login') }}" class="text-[#1b4332] font-semibold underline">Masuk di sini</a>
        </p>
    </div>
@endsection
