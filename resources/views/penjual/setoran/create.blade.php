@extends('layouts.app')

@section('title', 'Ajukan Setoran')

@section('content')
    <div class="mb-6">
        <h1 class="text-2xl font-bold text-[#1b4332]">Ajukan Setoran Sampah</h1>
        <p class="text-slate-500 text-sm mt-1">Isi jenis dan berat sampah yang kamu bawa. Setoran akan masuk ke saldo setelah disetujui admin.</p>
    </div>

    <div class="max-w-xl bg-white rounded-2xl border border-slate-200 shadow-sm p-6">
        <form method="POST" action="{{ route('penjual.setoran.store') }}" class="space-y-4">
            @csrf

            <div>
                <label class="block text-xs font-bold uppercase tracking-wide text-slate-500 mb-1.5">Jenis Sampah</label>
                <select id="jenisSampahSelect" name="jenis_sampah_id" required
                    class="w-full rounded-lg border border-slate-300 px-4 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-[#1b4332]">
                    <option value="">— Pilih jenis sampah —</option>
                    @foreach($jenisSampah as $j)
                        <option value="{{ $j->id }}" data-harga="{{ $j->harga_per_kg }}" {{ old('jenis_sampah_id') == $j->id ? 'selected' : '' }}>
                            {{ $j->nama_sampah }} (Rp {{ number_format($j->harga_per_kg, 0, ',', '.') }}/kg)
                        </option>
                    @endforeach
                </select>
            </div>

            <div>
                <label class="block text-xs font-bold uppercase tracking-wide text-slate-500 mb-1.5">Berat (Kg)</label>
                <input id="beratInput" type="number" step="0.01" min="0.01" name="berat_kg" required value="{{ old('berat_kg') }}"
                    class="w-full rounded-lg border border-slate-300 px-4 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-[#1b4332]"
                    placeholder="cth. 2.5">
            </div>

            <div class="bg-emerald-50 border border-emerald-200 rounded-lg px-4 py-3 flex items-center justify-between">
                <span class="text-sm font-semibold text-emerald-800">Estimasi Total</span>
                <span id="totalPreview" class="text-lg font-bold text-emerald-700">Rp 0</span>
            </div>

            <div>
                <label class="block text-xs font-bold uppercase tracking-wide text-slate-500 mb-1.5">Keterangan (opsional)</label>
                <input type="text" name="keterangan" value="{{ old('keterangan') }}"
                    class="w-full rounded-lg border border-slate-300 px-4 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-[#1b4332]"
                    placeholder="cth. dikumpulkan dari kantor RT">
            </div>

            <button type="submit" class="w-full bg-[#1b4332] hover:bg-[#2d6a4f] transition text-white font-semibold text-sm px-5 py-2.5 rounded-lg">
                Ajukan Setoran
            </button>
            <p class="text-xs text-slate-400 text-center">Total baru akan ditambahkan ke saldo setelah admin menyetujui pengajuan ini.</p>
        </form>
    </div>

    <script>
        const jenisSelect = document.getElementById('jenisSampahSelect');
        const beratInput = document.getElementById('beratInput');
        const totalPreview = document.getElementById('totalPreview');

        function hitungTotal() {
            const opt = jenisSelect.options[jenisSelect.selectedIndex];
            const harga = parseFloat(opt?.dataset?.harga || 0);
            const berat = parseFloat(beratInput.value || 0);
            const total = isNaN(harga * berat) ? 0 : Math.round(harga * berat);
            totalPreview.textContent = 'Rp ' + total.toLocaleString('id-ID');
        }

        jenisSelect.addEventListener('change', hitungTotal);
        beratInput.addEventListener('input', hitungTotal);
    </script>
@endsection
