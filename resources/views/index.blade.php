<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ config('app.name') }}</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="min-h-screen bg-[#f4f6f0] text-[#2b3a2a] antialiased">

    <div class="max-w-3xl mx-auto px-4 py-10">
        <!-- Banner -->
        <div class="bg-[#1b4332] text-white rounded-2xl p-8 shadow-lg relative overflow-hidden">
            <p class="text-[11px] tracking-widest uppercase text-emerald-300 font-semibold">Buku Tabungan Sampah — Digital</p>
            <h1 class="text-2xl sm:text-3xl font-bold mt-1 mb-3">♻️ {{ config('app.name') }}</h1>
            <p class="text-emerald-100 text-sm leading-relaxed max-w-md">
                Tukar sampah jadi tabungan. Daftar sebagai nasabah, setorkan sampahmu, dan pantau saldo secara digital &mdash; kapan saja.
            </p>
            <div class="flex flex-wrap gap-3 mt-6">
                <a href="{{ route('register') }}" class="bg-white text-[#1b4332] font-semibold text-sm px-5 py-2.5 rounded-lg hover:bg-emerald-50 transition">
                    Daftar Jadi Nasabah
                </a>
                <a href="{{ route('login') }}" class="border border-white/40 text-white font-semibold text-sm px-5 py-2.5 rounded-lg hover:bg-white/10 transition">
                    Masuk
                </a>
            </div>
        </div>

        <!-- Price list -->
        <div class="bg-white rounded-2xl border border-emerald-900/10 shadow-sm mt-6 p-6 sm:p-8">
            <h2 class="text-lg font-bold text-[#1b4332]">Daftar Harga Sampah Hari Ini</h2>
            <p class="text-sm text-slate-500 mt-1 mb-5">Harga bisa berubah sewaktu-waktu sesuai kebijakan pengelola.</p>

            <div class="overflow-x-auto">
                <table class="w-full text-sm">
                    <thead>
                        <tr class="bg-[#1b4332] text-white">
                            <th class="py-3 px-4 text-left rounded-l-lg w-14">No</th>
                            <th class="py-3 px-4 text-left">Jenis Sampah</th>
                            <th class="py-3 px-4 text-right rounded-r-lg">Harga / Kg</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100">
                        @forelse($data_sampah as $index => $item)
                            <tr class="hover:bg-emerald-50/60 transition">
                                <td class="py-3 px-4 text-slate-500">{{ $index + 1 }}</td>
                                <td class="py-3 px-4 font-semibold">{{ $item->nama_sampah }}</td>
                                <td class="py-3 px-4 text-right font-bold text-emerald-700">
                                    Rp {{ number_format($item->harga_per_kg, 0, ',', '.') }}
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="3" class="py-6 text-center text-amber-600">Data sampah masih kosong.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

        <p class="text-center text-xs text-slate-400 mt-8">
            &copy; {{ date('Y') }} {{ config('app.name') }}. Dibuat untuk tugas akhir.
        </p>
    </div>
</body>
</html>
