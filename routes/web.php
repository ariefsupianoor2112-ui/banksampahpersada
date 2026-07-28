<?php

use App\Http\Controllers\Admin\DashboardController as AdminDashboardController;
use App\Http\Controllers\Admin\JenisSampahController;
use App\Http\Controllers\Admin\PencairanController as AdminPencairanController;
use App\Http\Controllers\Admin\PengaturanController as AdminPengaturanController;
use App\Http\Controllers\Admin\PenjualController;
use App\Http\Controllers\Admin\SetoranController as AdminSetoranController;
use App\Http\Controllers\Admin\TransaksiController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\Penjual\DashboardController as PenjualDashboardController;
use App\Http\Controllers\Penjual\SetoranController as PenjualSetoranController;
use App\Http\Controllers\Penjual\TarikController as PenjualTarikController;
use App\Http\Controllers\SampahController;
use Illuminate\Support\Facades\Route;

// Halaman utama (Daftar Harga - publik)
Route::get('/', [SampahController::class, 'index'])->name('home');

// Rute Autentikasi
Route::middleware('guest')->group(function () {
    Route::get('/login', [AuthController::class, 'loginForm'])->name('login');
    Route::post('/login', [AuthController::class, 'login']);
    Route::get('/register', [AuthController::class, 'registerForm'])->name('register');
    Route::post('/register', [AuthController::class, 'register']);
});
Route::post('/logout', [AuthController::class, 'logout'])->middleware('auth')->name('logout');

// Rute khusus Admin
Route::middleware(['auth', 'role:admin'])->prefix('admin')->name('admin.')->group(function () {
    Route::get('/dashboard', [AdminDashboardController::class, 'index'])->name('dashboard');

    Route::resource('jenis-sampah', JenisSampahController::class)->except(['show']);

    Route::get('/nasabah', [PenjualController::class, 'index'])->name('nasabah.index');
    Route::get('/nasabah/{penjual}', [PenjualController::class, 'show'])->name('nasabah.show');

    Route::get('/transaksi', [TransaksiController::class, 'index'])->name('transaksi.index');
    Route::get('/transaksi/create', [TransaksiController::class, 'create'])->name('transaksi.create');
    Route::post('/transaksi', [TransaksiController::class, 'store'])->name('transaksi.store');
    Route::post('/transaksi/tarik', [TransaksiController::class, 'tarik'])->name('transaksi.tarik');

    // Persetujuan pengajuan setoran dari nasabah
    Route::get('/setoran', [AdminSetoranController::class, 'index'])->name('setoran.index');
    Route::post('/setoran/{setoran}/approve', [AdminSetoranController::class, 'approve'])->name('setoran.approve');
    Route::post('/setoran/{setoran}/reject', [AdminSetoranController::class, 'reject'])->name('setoran.reject');

    // Persetujuan pengajuan tarik saldo dari nasabah
    Route::get('/pencairan', [AdminPencairanController::class, 'index'])->name('pencairan.index');
    Route::post('/pencairan/{pencairan}/approve', [AdminPencairanController::class, 'approve'])->name('pencairan.approve');
    Route::post('/pencairan/{pencairan}/reject', [AdminPencairanController::class, 'reject'])->name('pencairan.reject');

    // Pengaturan akun admin
    Route::get('/pengaturan', [AdminPengaturanController::class, 'index'])->name('pengaturan.index');
    Route::put('/pengaturan', [AdminPengaturanController::class, 'update'])->name('pengaturan.update');
});

// Rute khusus Penjual (nasabah)
Route::middleware(['auth', 'role:penjual'])->prefix('penjual')->name('penjual.')->group(function () {
    Route::get('/dashboard', [PenjualDashboardController::class, 'index'])->name('dashboard');

    // Ajukan Setoran
    Route::get('/setoran/ajukan', [PenjualSetoranController::class, 'create'])->name('setoran.create');
    Route::post('/setoran', [PenjualSetoranController::class, 'store'])->name('setoran.store');

    // Tarik Saldo
    Route::get('/tarik', [PenjualTarikController::class, 'create'])->name('tarik.create');
    Route::post('/tarik', [PenjualTarikController::class, 'store'])->name('tarik.store');
});
