# Tutorial: Fitur Ajukan Setoran, Tarik Saldo, Setoran & Pencairan (Admin)

Dokumen ini menjelaskan **apa yang ditambahkan**, **kenapa**, dan **cara menjalankannya**
langkah demi langkah di project `banksampahpersada-laravel`.

---

## 0. Konsep besar perubahan

Sebelumnya, semua transaksi (setor & tarik) hanya bisa dicatat **langsung oleh admin**
lewat menu "Setor Langsung". Sekarang nasabah bisa **mengajukan sendiri**:

```
Nasabah ajukan setoran/tarik  →  status: pending
                                     │
                          Admin buka menu Setoran / Pencairan
                                     │
                         Setujui ──────────► status: approved  → masuk hitungan saldo
                         Tolak   ──────────► status: rejected  → tidak masuk saldo
```

Kolom baru di tabel `transaksis`:
| Kolom | Isi | Fungsi |
|---|---|---|
| `status` | `pending` / `approved` / `rejected` | Menentukan apakah transaksi sudah dihitung ke saldo |
| `sumber` | `admin` / `nasabah` | Menandai siapa yang membuat transaksi (untuk filter di halaman admin) |
| `catatan_admin` | teks bebas | Alasan admin saat menolak pengajuan (opsional) |

Saldo nasabah (`$user->saldo`) sekarang **hanya menjumlahkan transaksi berstatus `approved`**,
jadi pengajuan yang masih pending tidak akan mengubah saldo dulu.

---

## 1. Jalankan migration

Buka terminal di folder project:

```bash
cd banksampahpersada-laravel
php artisan migrate
```

Ini akan menambahkan kolom `status`, `catatan_admin`, `sumber` ke tabel `transaksis`
lewat file baru: `database/migrations/2026_07_29_000000_add_status_to_transaksis_table.php`.

> Kalau ingin mulai dari database bersih sekalian dengan data contoh:
> ```bash
> php artisan migrate:fresh --seed
> ```
> Ini akan membuat ulang semua tabel + mengisi akun contoh (lihat langkah 2).

---

## 2. Akun contoh (dari seeder)

| Role | Email | Password |
|---|---|---|
| Admin | admin@persada.com | password123 |
| Nasabah | budi@persada.com | password123 |
| Nasabah | siti@persada.com | password123 |

Seeder juga membuat **1 pengajuan setoran pending** dari akun Siti, supaya begitu login
sebagai admin kamu langsung bisa lihat & coba fitur approve/reject.

---

## 3. Jalankan aplikasi

```bash
composer install      # kalau belum
npm install && npm run build   # atau: npm run dev
php artisan serve
```

Buka `http://127.0.0.1:8000`.

---

## 4. Uji coba fitur — sisi Nasabah

1. Login sebagai **budi@persada.com**.
2. Di sidebar akan ada menu baru:
   - **Ajukan Setoran** → `/penjual/setoran/ajukan`
   - **Tarik Saldo** → `/penjual/tarik`
3. Klik **Ajukan Setoran**, pilih jenis sampah, isi berat, submit.
   → Muncul pesan "Menunggu persetujuan admin" dan transaksi baru muncul di
   tabel Riwayat dengan badge abu-abu **"Menunggu"**.
4. Klik **Tarik Saldo**, coba masukkan jumlah lebih besar dari saldo tersedia
   → sistem otomatis menolak dengan pesan error (validasi di `TarikController::store`).
5. Masukkan jumlah yang valid → tersimpan sebagai pending juga.

**Kode kunci:** `app/Http/Controllers/Penjual/SetoranController.php`
dan `app/Http/Controllers/Penjual/TarikController.php` — keduanya membuat
`Transaksi::create([... 'status' => 'pending', 'sumber' => 'nasabah'])`.

---

## 5. Uji coba fitur — sisi Admin

1. Logout, login sebagai **admin@persada.com**.
2. Sidebar sekarang punya menu **Pengajuan Setoran** dan **Pengajuan Pencairan**.
3. Buka **Pengajuan Setoran** (`/admin/setoran`) → akan terlihat daftar pending,
   termasuk data contoh dari seeder + yang baru saja diajukan Budi.
4. Klik tombol **Setujui** pada salah satu baris.
   → Redirect kembali dengan notifikasi hijau, status berubah jadi "Disetujui",
   dan saldo nasabah otomatis bertambah (karena `getSaldoAttribute()` hanya
   menghitung status `approved`).
5. Coba juga tombol **Tolak** pada baris lain → status jadi "Ditolak", saldo tidak berubah.
6. Buka **Pengajuan Pencairan** (`/admin/pencairan`) dan ulangi hal yang sama
   untuk permintaan tarik saldo dari Budi.
7. Buka **Pengaturan** (`/admin/pengaturan`) → coba ubah nama/email, atau isi
   password baru lalu simpan. Cek login ulang pakai password baru untuk memastikan.

**Kode kunci:**
- `app/Http/Controllers/Admin/SetoranController.php` → `approve()` / `reject()`
- `app/Http/Controllers/Admin/PencairanController.php` → `approve()` juga
  mengecek ulang `total > saldo` (jaga-jaga saldo berubah sejak pengajuan dibuat)
- `app/Http/Controllers/Admin/PengaturanController.php` → update profil & hash password baru

---

## 6. Verifikasi saldo otomatis benar

Cara paling gampang membuktikan logika saldo bekerja:

1. Login sebagai Budi, catat saldo saat ini di dashboard.
2. Ajukan setoran 1 kg sampah apa saja.
3. Saldo di dashboard **tidak berubah** dulu (karena masih pending).
4. Login admin, approve pengajuan itu.
5. Login lagi sebagai Budi → saldo sudah bertambah sesuai `berat_kg × harga_per_kg`.

---

## 7. Daftar file yang ditambahkan / diubah

**File baru:**
```
database/migrations/2026_07_29_000000_add_status_to_transaksis_table.php
app/Http/Controllers/Penjual/SetoranController.php
app/Http/Controllers/Penjual/TarikController.php
app/Http/Controllers/Admin/SetoranController.php
app/Http/Controllers/Admin/PencairanController.php
app/Http/Controllers/Admin/PengaturanController.php
resources/views/penjual/setoran/create.blade.php
resources/views/penjual/tarik/create.blade.php
resources/views/admin/setoran/index.blade.php
resources/views/admin/pencairan/index.blade.php
resources/views/admin/pengaturan/index.blade.php
```

**File diubah:**
```
routes/web.php                                   → route baru untuk semua di atas
app/Models/Transaksi.php                         → fillable + scope status
app/Models/User.php                              → saldo hanya hitung approved + saldo_tertahan
app/Http/Controllers/Admin/DashboardController.php  → hitung saldo approved + jumlah pending
app/Http/Controllers/Admin/TransaksiController.php  → set status=approved, sumber=admin
app/Http/Controllers/Admin/PenjualController.php    → total setor/tarik hanya approved
resources/views/layouts/app.blade.php            → menu sidebar baru (nasabah & admin)
resources/views/admin/dashboard.blade.php        → kartu jumlah pengajuan pending
resources/views/penjual/dashboard.blade.php      → tombol aksi + kolom status
resources/views/admin/transaksi/index.blade.php  → kolom status
resources/views/admin/penjual/show.blade.php     → kolom status
database/seeders/DatabaseSeeder.php              → status/sumber pada data contoh + 1 pending
```

---

## 8. Ide pengembangan lanjutan (opsional)

- Kirim notifikasi email/WhatsApp ke nasabah saat pengajuannya disetujui/ditolak.
- Tampilkan `catatan_admin` (alasan tolak) di dashboard nasabah.
- Upload foto sampah saat "Ajukan Setoran" (butuh kolom `foto` + storage disk).
- Export riwayat transaksi ke Excel/PDF di halaman admin.
