@extends('layouts.guest')

@section('title', 'Masuk')

@section('content')
    <!-- Banner Header -->
    <div class="bg-[#1b4332] text-white rounded-2xl p-7 shadow-lg">
        <p class="text-[11px] tracking-widest uppercase text-emerald-300 font-semibold">Buku Tabungan Sampah — Digital</p>
        <h1 class="text-xl sm:text-2xl font-bold mt-1 mb-2">{{ config('app.name') }}</h1>
        <p class="text-emerald-100 text-sm leading-relaxed">
            Masuk untuk memantau saldo, riwayat setoran, atau mengelola data bank sampah.
        </p>
    </div>

    <!-- Form Card -->
    <div class="bg-white rounded-2xl border border-slate-200 shadow-sm p-7 mt-6">
        <h2 class="text-xl font-bold text-[#1b4332]">Masuk ke akun kamu</h2>
        <p class="text-slate-500 text-sm mt-1 mb-6">Gunakan email dan kata sandi yang terdaftar.</p>

        <form action="{{ url('/login') }}" method="POST" class="space-y-4">
            @csrf

           <div>
                <label class="block text-xs font-bold uppercase tracking-wide text-slate-500 mb-1.5">Email</label>
                <input type="email" name="email" required autofocus value="{{ old('email') }}"
                    class="w-full rounded-lg border border-slate-300 px-4 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-[#1b4332] focus:border-[#1b4332]"
                    placeholder="email@domain.com">
            </div>

            <div>
                <label class="block text-xs font-bold uppercase tracking-wide text-slate-500 mb-1.5">Kata Sandi</label>
                <input type="password" name="password" required
                    class="w-full rounded-lg border border-slate-300 px-4 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-[#1b4332] focus:border-[#1b4332]"
                    placeholder="Kata sandi">
            </div>

            <label class="flex items-center gap-2 text-sm text-slate-500">
                <input type="checkbox" name="remember" class="rounded border-slate-300 text-[#1b4332] focus:ring-[#1b4332]">
                Ingat saya
            </label>

            <button type="submit"
                class="w-full bg-[#1b4332] hover:bg-[#2d6a4f] transition text-white font-semibold rounded-lg py-2.5">
                Masuk
            </button>
        </form>

        <p class="text-center text-sm text-slate-500 mt-6">
            Belum punya akun?
            <a href="{{ route('register') }}" class="text-[#1b4332] font-semibold underline">Daftar sebagai nasabah</a>
        </p>
        <p class="text-center text-sm mt-2">
            <a href="{{ route('home') }}" class="text-slate-400 hover:text-slate-600">&larr; Kembali ke daftar harga</a>
        </p>
    </div>
@endsection
