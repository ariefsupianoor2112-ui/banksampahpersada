<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', config('app.name')) — {{ config('app.name') }}</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="bg-emerald-50/40 text-slate-800 antialiased min-h-screen flex">

    <!-- Sidebar -->
    <aside class="hidden lg:flex flex-col w-64 shrink-0 bg-[#1b4332] text-white min-h-screen sticky top-0">
        <div class="px-6 py-6 border-b border-white/10">
            <p class="text-[11px] tracking-widest uppercase text-emerald-300 font-semibold">Buku Tabungan Digital</p>
            <p class="text-lg font-bold leading-tight mt-1">♻️ {{ config('app.name') }}</p>
        </div>

        <nav class="flex-1 px-3 py-4 space-y-1 text-sm">
            @if(auth()->user()->role === 'admin')
                <a href="{{ route('admin.dashboard') }}" class="flex items-center gap-3 px-3 py-2.5 rounded-lg transition {{ request()->routeIs('admin.dashboard') ? 'bg-white/15 font-semibold' : 'hover:bg-white/10 text-emerald-100' }}">
                    <span>📊</span> Dashboard
                </a>
                <a href="{{ route('admin.jenis-sampah.index') }}" class="flex items-center gap-3 px-3 py-2.5 rounded-lg transition {{ request()->routeIs('admin.jenis-sampah.*') ? 'bg-white/15 font-semibold' : 'hover:bg-white/10 text-emerald-100' }}">
                    <span>🗑️</span> Jenis &amp; Harga Sampah
                </a>
                <a href="{{ route('admin.nasabah.index') }}" class="flex items-center gap-3 px-3 py-2.5 rounded-lg transition {{ request()->routeIs('admin.nasabah.*') ? 'bg-white/15 font-semibold' : 'hover:bg-white/10 text-emerald-100' }}">
                    <span>👥</span> Data Nasabah
                </a>
                <a href="{{ route('admin.setoran.index') }}" class="flex items-center gap-3 px-3 py-2.5 rounded-lg transition {{ request()->routeIs('admin.setoran.*') ? 'bg-white/15 font-semibold' : 'hover:bg-white/10 text-emerald-100' }}">
                    <span>📥</span> Pengajuan Setoran
                </a>
                <a href="{{ route('admin.pencairan.index') }}" class="flex items-center gap-3 px-3 py-2.5 rounded-lg transition {{ request()->routeIs('admin.pencairan.*') ? 'bg-white/15 font-semibold' : 'hover:bg-white/10 text-emerald-100' }}">
                    <span>💵</span> Pengajuan Pencairan
                </a>
                <a href="{{ route('admin.transaksi.index') }}" class="flex items-center gap-3 px-3 py-2.5 rounded-lg transition {{ request()->routeIs('admin.transaksi.*') ? 'bg-white/15 font-semibold' : 'hover:bg-white/10 text-emerald-100' }}">
                    <span>💸</span> Setor Langsung / Riwayat
                </a>
                <a href="{{ route('admin.pengaturan.index') }}" class="flex items-center gap-3 px-3 py-2.5 rounded-lg transition {{ request()->routeIs('admin.pengaturan.*') ? 'bg-white/15 font-semibold' : 'hover:bg-white/10 text-emerald-100' }}">
                    <span>⚙️</span> Pengaturan
                </a>
            @else
                <a href="{{ route('penjual.dashboard') }}" class="flex items-center gap-3 px-3 py-2.5 rounded-lg transition {{ request()->routeIs('penjual.dashboard') ? 'bg-white/15 font-semibold' : 'hover:bg-white/10 text-emerald-100' }}">
                    <span>📊</span> Saldo &amp; Riwayat
                </a>
                <a href="{{ route('penjual.setoran.create') }}" class="flex items-center gap-3 px-3 py-2.5 rounded-lg transition {{ request()->routeIs('penjual.setoran.*') ? 'bg-white/15 font-semibold' : 'hover:bg-white/10 text-emerald-100' }}">
                    <span>♻️</span> Ajukan Setoran
                </a>
                <a href="{{ route('penjual.tarik.create') }}" class="flex items-center gap-3 px-3 py-2.5 rounded-lg transition {{ request()->routeIs('penjual.tarik.*') ? 'bg-white/15 font-semibold' : 'hover:bg-white/10 text-emerald-100' }}">
                    <span>💵</span> Tarik Saldo
                </a>
                <a href="{{ route('home') }}" class="flex items-center gap-3 px-3 py-2.5 rounded-lg transition hover:bg-white/10 text-emerald-100">
                    <span>💰</span> Daftar Harga
                </a>
            @endif
        </nav>

        <div class="px-3 py-4 border-t border-white/10">
            <div class="px-3 py-2 mb-2">
                <p class="text-sm font-semibold truncate">{{ auth()->user()->name }}</p>
                <p class="text-xs text-emerald-300 capitalize">{{ auth()->user()->role }}</p>
            </div>
            <form method="POST" action="{{ route('logout') }}">
                @csrf
                <button class="w-full text-left flex items-center gap-3 px-3 py-2.5 rounded-lg text-sm text-red-200 hover:bg-white/10 transition">
                    <span>🚪</span> Keluar
                </button>
            </form>
        </div>
    </aside>

    <!-- Main content -->
    <div class="flex-1 min-w-0">
        <!-- Mobile top bar -->
        <header class="lg:hidden bg-[#1b4332] text-white px-4 py-4 flex items-center justify-between">
            <span class="font-bold">♻️ {{ config('app.name') }}</span>
            <form method="POST" action="{{ route('logout') }}">
                @csrf
                <button class="text-sm text-emerald-100 underline">Keluar</button>
            </form>
        </header>

        <main class="p-4 sm:p-6 lg:p-8 max-w-7xl mx-auto">
            @if(session('status'))
                <div class="mb-6 rounded-xl bg-emerald-100 border border-emerald-300 text-emerald-800 px-4 py-3 text-sm font-medium">
                    {{ session('status') }}
                </div>
            @endif

            @if($errors->any())
                <div class="mb-6 rounded-xl bg-red-100 border border-red-300 text-red-800 px-4 py-3 text-sm font-medium">
                    <ul class="list-disc list-inside space-y-0.5">
                        @foreach($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            @yield('content')
        </main>
    </div>
</body>
</html>
