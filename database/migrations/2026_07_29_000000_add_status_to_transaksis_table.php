<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('transaksis', function (Blueprint $table) {
            // pending  = baru diajukan nasabah, menunggu admin
            // approved = sudah disetujui/dicatat admin, dihitung ke saldo
            // rejected = ditolak admin, tidak dihitung ke saldo
            $table->enum('status', ['pending', 'approved', 'rejected'])
                ->default('pending')
                ->after('admin_id');

            $table->text('catatan_admin')->nullable()->after('status');

            // 'admin'   = dicatat langsung oleh admin (Setor Langsung)
            // 'nasabah' = diajukan sendiri oleh nasabah, perlu persetujuan admin
            $table->enum('sumber', ['admin', 'nasabah'])->default('admin')->after('catatan_admin');
        });

        // Transaksi lama (dicatat langsung oleh admin sebelum fitur ini ada)
        // dianggap otomatis approved supaya saldo nasabah tidak berubah.
        DB::table('transaksis')->update(['status' => 'approved', 'sumber' => 'admin']);
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('transaksis', function (Blueprint $table) {
            $table->dropColumn(['status', 'catatan_admin']);
        });
    }
};
