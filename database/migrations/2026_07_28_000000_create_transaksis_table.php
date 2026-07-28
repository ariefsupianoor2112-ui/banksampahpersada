<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('transaksis', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->cascadeOnDelete();
            $table->foreignId('jenis_sampah_id')->nullable()->constrained('jenis_sampah')->nullOnDelete();
            $table->enum('tipe', ['setor', 'tarik']);
            $table->decimal('berat_kg', 8, 2)->nullable();
            $table->unsignedBigInteger('harga_per_kg')->nullable();
            $table->unsignedBigInteger('total')->default(0);
            $table->text('keterangan')->nullable();
            $table->foreignId('admin_id')->nullable()->constrained('users')->nullOnDelete();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('transaksis');
    }
};
