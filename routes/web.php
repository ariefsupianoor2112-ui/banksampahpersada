<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\Admin\PenjualController;
use App\Http\Controllers\Admin\DashboardController as AdminDashboardController;
use App\Http\Controllers\Penjual\DashboardController as PenjualDashboardController;

/*
|--------------------------------------------------------------------------
| Web Routes - Bank Sampah Persada
|--------------------------------------------------------------------------
*/

// 1. Halaman Utama / Daftar Harga Publik
Route::get('/', function () {
    return view('welcome');
})->name('home');

// 2. Route Tamu / Guest (Hanya untuk yang belum login)
Route::middleware('guest')->group(function () {
    Route::get('/login', [AuthController::class, 'loginForm'])->name('login');
    Route::post('/login', [AuthController::class, 'login']);

    Route::get('/register', [AuthController::class, 'registerForm'])->name('register');
    Route::post('/register', [AuthController::class, 'register']);

    // Rute Lupa Password
    Route::get('/forgot-password', [AuthController::class, 'forgotPasswordForm'])->name('password.request');
    Route::post('/forgot-password', [AuthController::class, 'sendResetLink'])->name('password.email');
});

// 3. Route Terproteksi (Harus Login)
Route::middleware('auth')->group(function () {
    
    // Logout
    Route::post('/logout', [AuthController::class, 'logout'])->name('logout');
    Route::get('/logout', [AuthController::class, 'logout']);

    // AREA ADMIN (Hanya Role Admin)
    Route::middleware('role:admin')->prefix('admin')->name('admin.')->group(function () {
        Route::get('/dashboard', [AdminDashboardController::class, 'index'])->name('dashboard');
        
        // Kelola Penjual / Nasabah (Termasuk Fitur Hapus)
        Route::get('/penjual', [PenjualController::class, 'index'])->name('penjual.index');
        Route::delete('/penjual/{id}', [PenjualController::class, 'destroy'])->name('penjual.destroy');

        // Kelola Jenis Sampah (Perbaikan error RouteNotFoundException)
        Route::get('/jenis-sampah', function() { return view('admin.jenis-sampah.index'); })->name('jenis-sampah.index');

        // Route Menu Admin Lainnya
        Route::get('/setoran', function() { return view('admin.setoran.index'); })->name('setoran.index');
        Route::get('/pencairan', function() { return view('admin.pencairan.index'); })->name('pencairan.index');
        Route::get('/transaksi', function() { return view('admin.transaksi.index'); })->name('transaksi.index');
    });

    // AREA PENJUAL / NASABAH (Hanya Role Penjual)
    Route::middleware('role:penjual')->prefix('penjual')->name('penjual.')->group(function () {
        Route::get('/dashboard', [PenjualDashboardController::class, 'index'])->name('dashboard');
    });

});
