@extends('layouts.app')

@section('title', 'Pengaturan')

@section('content')
    <div class="mb-6">
        <h1 class="text-2xl font-bold text-[#1b4332]">Pengaturan Akun</h1>
        <p class="text-slate-500 text-sm mt-1">Kelola profil dan kata sandi akun admin.</p>
    </div>

    <div class="max-w-xl bg-white rounded-2xl border border-slate-200 shadow-sm p-6">
        <form method="POST" action="{{ route('admin.pengaturan.update') }}" class="space-y-4">
            @csrf
            @method('PUT')

            <div>
                <label class="block text-xs font-bold uppercase tracking-wide text-slate-500 mb-1.5">Nama</label>
                <input type="text" name="name" required value="{{ old('name', $admin->name) }}"
                    class="w-full rounded-lg border border-slate-300 px-4 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-[#1b4332]">
            </div>

            <div>
                <label class="block text-xs font-bold uppercase tracking-wide text-slate-500 mb-1.5">Email</label>
                <input type="email" name="email" required value="{{ old('email', $admin->email) }}"
                    class="w-full rounded-lg border border-slate-300 px-4 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-[#1b4332]">
            </div>

            <div>
                <label class="block text-xs font-bold uppercase tracking-wide text-slate-500 mb-1.5">No. HP (opsional)</label>
                <input type="text" name="no_hp" value="{{ old('no_hp', $admin->no_hp) }}"
                    class="w-full rounded-lg border border-slate-300 px-4 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-[#1b4332]">
            </div>

            <hr class="border-slate-100">

            <p class="text-xs text-slate-500">Kosongkan bagian di bawah jika tidak ingin mengganti kata sandi.</p>

            <div>
                <label class="block text-xs font-bold uppercase tracking-wide text-slate-500 mb-1.5">Kata Sandi Baru</label>
                <input type="password" name="password"
                    class="w-full rounded-lg border border-slate-300 px-4 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-[#1b4332]"
                    placeholder="Minimal 8 karakter">
            </div>

            <div>
                <label class="block text-xs font-bold uppercase tracking-wide text-slate-500 mb-1.5">Konfirmasi Kata Sandi</label>
                <input type="password" name="password_confirmation"
                    class="w-full rounded-lg border border-slate-300 px-4 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-[#1b4332]">
            </div>

            <button type="submit" class="w-full bg-[#1b4332] hover:bg-[#2d6a4f] transition text-white font-semibold text-sm px-5 py-2.5 rounded-lg">
                Simpan Perubahan
            </button>
        </form>
    </div>
@endsection
