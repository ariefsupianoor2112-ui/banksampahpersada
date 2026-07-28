<?php

namespace Database\Seeders;

use App\Models\JenisSampah;
use App\Models\Transaksi;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        // 1. Akun Admin
        $admin = User::create([
            'name' => 'Admin Persada',
            'email' => 'admin@persada.com',
            'password' => Hash::make('password123'),
            'role' => 'admin',
        ]);

        // 2. Akun Penjual (nasabah) contoh
        $budi = User::create([
            'name' => 'Budi Santoso',
            'email' => 'budi@persada.com',
            'no_hp' => '081234567890',
            'alamat' => 'Jl. Melati No. 5',
            'password' => Hash::make('password123'),
            'role' => 'penjual',
        ]);

        $siti = User::create([
            'name' => 'Siti Aminah',
            'email' => 'siti@persada.com',
            'no_hp' => '081298765432',
            'alamat' => 'Jl. Kenanga No. 12',
            'password' => Hash::make('password123'),
            'role' => 'penjual',
        ]);

        // 3. Jenis Sampah & Harga — sesuai "Master Harga Sampah"
        $daftarHarga = [
            ['nama_sampah' => 'Botol Biru Kotor', 'harga_per_kg' => 900],
            ['nama_sampah' => 'Botol Biru Bersih', 'harga_per_kg' => 1500],
            ['nama_sampah' => 'Botol Bening Kotor', 'harga_per_kg' => 1000],
            ['nama_sampah' => 'Botol Bening Bersih', 'harga_per_kg' => 2400],
            ['nama_sampah' => 'Botol Plastik Warna Bersih (Hijau atau Sejenis)', 'harga_per_kg' => 1000],
            ['nama_sampah' => 'Botol Plastik Warna Kotor (Hijau atau Sejenis)', 'harga_per_kg' => 400],
            ['nama_sampah' => 'Kardus Bekas Kering', 'harga_per_kg' => 500],
            ['nama_sampah' => 'Kardus Bekas Basah', 'harga_per_kg' => 100],
            ['nama_sampah' => 'HVS', 'harga_per_kg' => 800],
            ['nama_sampah' => 'Plastik Gelas Kotor', 'harga_per_kg' => 800],
            ['nama_sampah' => 'Plastik Gelas Bersih', 'harga_per_kg' => 2000],
            ['nama_sampah' => 'Plastik Gelas Minuman Berlabel', 'harga_per_kg' => 700],
            ['nama_sampah' => 'Plastik Emberan (Tutup Botol)', 'harga_per_kg' => 500],
            ['nama_sampah' => 'Minyak Jelantah', 'harga_per_kg' => 4000],
            ['nama_sampah' => 'Botol Kaca', 'harga_per_kg' => 100],
            ['nama_sampah' => 'Duplex', 'harga_per_kg' => 250],
            ['nama_sampah' => 'Plastik Campur (Botol dan Sebagainya)', 'harga_per_kg' => 500],
        ];

        foreach ($daftarHarga as $item) {
            JenisSampah::create($item);
        }

        $kardus = JenisSampah::where('nama_sampah', 'Kardus Bekas Kering')->first();
        $botol = JenisSampah::where('nama_sampah', 'Botol Bening Bersih')->first();
        $minyak = JenisSampah::where('nama_sampah', 'Minyak Jelantah')->first();

        // 4. Contoh transaksi yang sudah disetujui (dicatat langsung oleh admin)
        Transaksi::create([
            'user_id' => $budi->id,
            'jenis_sampah_id' => $kardus->id,
            'tipe' => 'setor',
            'berat_kg' => 5.5,
            'harga_per_kg' => $kardus->harga_per_kg,
            'total' => $kardus->harga_per_kg * 5.5,
            'keterangan' => 'Setoran pertama',
            'admin_id' => $admin->id,
            'status' => 'approved',
            'sumber' => 'admin',
        ]);

        Transaksi::create([
            'user_id' => $budi->id,
            'jenis_sampah_id' => $botol->id,
            'tipe' => 'setor',
            'berat_kg' => 3,
            'harga_per_kg' => $botol->harga_per_kg,
            'total' => $botol->harga_per_kg * 3,
            'admin_id' => $admin->id,
            'status' => 'approved',
            'sumber' => 'admin',
        ]);

        Transaksi::create([
            'user_id' => $siti->id,
            'jenis_sampah_id' => $minyak->id,
            'tipe' => 'setor',
            'berat_kg' => 2,
            'harga_per_kg' => $minyak->harga_per_kg,
            'total' => $minyak->harga_per_kg * 2,
            'admin_id' => $admin->id,
            'status' => 'approved',
            'sumber' => 'admin',
        ]);

        Transaksi::create([
            'user_id' => $budi->id,
            'tipe' => 'tarik',
            'total' => 5000,
            'keterangan' => 'Penarikan tunai',
            'admin_id' => $admin->id,
            'status' => 'approved',
            'sumber' => 'admin',
        ]);

        // 5. Contoh pengajuan dari nasabah yang masih menunggu persetujuan admin
        //    (untuk mendemokan fitur "Ajukan Setoran" & halaman "Pengajuan Setoran" di sisi admin)
        Transaksi::create([
            'user_id' => $siti->id,
            'jenis_sampah_id' => $kardus->id,
            'tipe' => 'setor',
            'berat_kg' => 2,
            'harga_per_kg' => $kardus->harga_per_kg,
            'total' => $kardus->harga_per_kg * 2,
            'keterangan' => 'Diajukan lewat aplikasi',
            'admin_id' => null,
            'status' => 'pending',
            'sumber' => 'nasabah',
        ]);
    }
}
