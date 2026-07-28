@extends('layouts.app')

@section('title', 'Tarik Saldo')

@section('content')
    <div class="mb-6">
        <h1 class="text-2xl font-bold text-[#1b4332]">Tarik Saldo</h1>
        <p class="text-slate-500 text-sm mt-1">Ajukan penarikan saldo tabunganmu. Uang cair setelah disetujui admin.</p>
    </div>

    <div class="max-w-xl space-y-4">
        <div class="bg-[#1b4332] text-white rounded-2xl p-6">
            <p class="text-xs uppercase tracking-wide text-emerald-300 font-semibold">Saldo Tersedia</p>
            <p class="text-3xl font-bold mt-2">Rp {{ number_format($user->saldo - $user->saldo_tertahan, 0, ',', '.') }}</p>
            @if($user->saldo_tertahan > 0)
                <p class="text-xs text-emerald-200 mt-2">Rp {{ number_format($user->saldo_tertahan, 0, ',', '.') }} sedang menunggu persetujuan penarikan lain.</p>
            @endif
        </div>

        <div class="bg-white rounded-2xl border border-slate-200 shadow-sm p-6">
            <form method="POST" action="{{ route('penjual.tarik.store') }}" class="space-y-4">
                @csrf

                <div>
                    <label class="block text-xs font-bold uppercase tracking-wide text-slate-500 mb-1.5">Jumlah Penarikan (Rp)</label>
                    <input type="number" min="1" max="{{ $user->saldo - $user->saldo_tertahan }}" name="total" required value="{{ old('total') }}"
                        class="w-full rounded-lg border border-slate-300 px-4 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-[#1b4332]"
                        placeholder="cth. 20000">
                </div>

                <div>
                    <label class="block text-xs font-bold uppercase tracking-wide text-slate-500 mb-1.5">Keterangan (opsional)</label>
                    <input type="text" name="keterangan" value="{{ old('keterangan') }}"
                        class="w-full rounded-lg border border-slate-300 px-4 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-[#1b4332]"
                        placeholder="cth. untuk kebutuhan mendesak">
                </div>

                <button type="submit" class="w-full bg-amber-600 hover:bg-amber-700 transition text-white font-semibold text-sm px-5 py-2.5 rounded-lg">
                    Ajukan Tarik Saldo
                </button>
                <p class="text-xs text-slate-400 text-center">Saldo baru akan berkurang setelah admin menyetujui penarikan ini.</p>
            </form>
        </div>
    </div>
@endsection
