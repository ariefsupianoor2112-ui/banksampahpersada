<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ config('app.name', 'Bank Sampah Persada') }} — Ubah Sampah Jadi Tabungan</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700&display=swap" rel="stylesheet">
    <style>
        body { font-family: 'Plus Jakarta Sans', sans-serif; }
    </style>
</head>
<body class="bg-slate-50 text-slate-800 min-h-screen flex flex-col justify-between">

    <!-- NAVBAR -->
    <header class="bg-white border-b border-slate-200 sticky top-0 z-50">
        <div class="max-w-6xl mx-auto px-4 sm:px-6 py-4 flex items-center justify-between">
            <div class="flex items-center gap-2.5">
                <div class="w-9 h-9 rounded-xl bg-[#1b4332] flex items-center justify-center text-white font-bold text-lg shadow-sm">
                    ♻️
                </div>
                <div>
                    <span class="font-bold text-lg text-[#1b4332] block leading-none">Bank Sampah Persada</span>
                    <span class="text-[10px] uppercase tracking-wider text-slate-400 font-semibold">Digital System</span>
                </div>
            </div>

            <div class="flex items-center gap-3">
                @auth
                    @if(Auth::user()->role === 'admin')
                        <a href="{{ route('admin.dashboard') }}" class="px-4 py-2 bg-[#1b4332] text-white text-sm font-semibold rounded-xl hover:bg-emerald-800 transition shadow-sm">
                            Dashboard Admin &rarr;
                        </a>
                    @else
                        <a href="{{ route('penjual.dashboard') }}" class="px-4 py-2 bg-[#1b4332] text-white text-sm font-semibold rounded-xl hover:bg-emerald-800 transition shadow-sm">
                            Dashboard Nasabah &rarr;
                        </a>
                    @endif
                @else
                    <a href="{{ route('login') }}" class="px-4 py-2 text-sm font-semibold text-slate-600 hover:text-[#1b4332] transition">
                        Masuk
                    </a>
                    <a href="{{ route('register') }}" class="px-4 py-2 bg-[#1b4332] text-white text-sm font-semibold rounded-xl hover:bg-emerald-800 transition shadow-sm">
                        Daftar Nasabah
                    </a>
                @endauth
            </div>
        </div>
    </header>

    <!-- HERO SECTION -->
    <main class="flex-1">
        <section class="bg-gradient-to-b from-[#1b4332] to-[#2d6a4f] text-white py-16 sm:py-24 px-4 text-center">
            <div class="max-w-3xl mx-auto">
                <span class="inline-block px-3 py-1 bg-emerald-800/60 border border-emerald-400/30 text-emerald-300 text-xs font-semibold rounded-full uppercase tracking-wider mb-4">
                    Peduli Lingkungan & Finansial
                </span>
                <h1 class="text-3xl sm:text-5xl font-bold leading-tight mb-4">
                    Ubah Sampah Rumah Tangga Menjadi Tabungan Digital
                </h1>
                <p class="text-emerald-100 text-sm sm:text-base leading-relaxed max-w-xl mx-auto mb-8">
                    Kelola sampah dengan lebih bijak. Setor sampah organik maupun anorganik, pantau harga secara transparan, dan tarik saldo tabunganmu kapan saja.
                </p>
                @guest
                    <div class="flex flex-col sm:flex-row items-center justify-center gap-3">
                        <a href="{{ route('register') }}" class="w-full sm:w-auto px-6 py-3 bg-white text-[#1b4332] font-bold text-sm rounded-xl hover:bg-emerald-50 transition shadow-lg">
                            Mulai Menabung Sekarang &rarr;
                        </a>
                        <a href="#daftar-harga" class="w-full sm:w-auto px-6 py-3 bg-emerald-800/80 border border-emerald-600 text-white font-semibold text-sm rounded-xl hover:bg-emerald-800 transition">
                            Lihat Daftar Harga
                        </a>
                    </div>
                @endguest
            </div>
        </section>

        <!-- SECTION DAFTAR HARGA SAMPAH -->
        <section id="daftar-harga" class="max-w-6xl mx-auto px-4 sm:px-6 py-16">
            <div class="text-center max-w-xl mx-auto mb-10">
                <h2 class="text-2xl sm:text-3xl font-bold text-[#1b4332]">Daftar Harga Sampah</h2>
                <p class="text-slate-500 text-sm mt-1">
                    Harga acuan penerimaan sampah per kilogram (Kg) di Bank Sampah Persada hari ini.
                </p>
            </div>

            @php
                // Mengambil data jenis sampah secara aman dari database jika tabel tersedia
                $jenisSampah = \Illuminate\Support\Facades\Schema::hasTable('jenis_sampahs') 
                    ? \App\Models\JenisSampah::all() 
                    : collect();
            @endphp

            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-5">
                @forelse($jenisSampah as $item)
                    <div class="bg-white rounded-2xl border border-slate-200 p-5 shadow-sm hover:shadow-md transition flex items-center justify-between">
                        <div>
                            <span class="text-xs font-semibold text-emerald-700 bg-emerald-100 px-2.5 py-1 rounded-full uppercase">
                                {{ $item->kategori ?? 'Umum' }}
                            </span>
                            <h3 class="font-bold text-slate-800 text-lg mt-2">{{ $item->nama_sampah }}</h3>
                            <p class="text-xs text-slate-400 mt-0.5">{{ $item->deskripsi ?? 'Sampah daur ulang' }}</p>
                        </div>
                        <div class="text-right">
                            <p class="text-xs text-slate-400 uppercase font-semibold">Harga / Kg</p>
                            <p class="text-lg font-bold text-[#1b4332]">
                                Rp {{ number_format($item->harga_per_kg ?? 0, 0, ',', '.') }}
                            </p>
                        </div>
                    </div>
                @empty
                    <!-- Fallback Tampilan Jika Data Harga Belum Diisi Admin -->
                    <div class="col-span-full bg-white rounded-2xl border border-slate-200 p-8 text-center">
                        <p class="text-slate-400 text-sm">Belum ada daftar harga sampah yang dipublikasikan oleh Admin.</p>
                    </div>
                @endforelse
            </div>
        </section>
    </main>

    <!-- FOOTER -->
    <footer class="bg-white border-t border-slate-200 py-6 text-center text-xs text-slate-400">
        <p>&copy; {{ date('Y') }} Bank Sampah Persada. Semua Hak Dilindungi.</p>
    </footer>

</body>
</html>
