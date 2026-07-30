<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use App\Models\User;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->string('id_nasabah')->unique()->nullable()->after('id');
        });

        // Otomatis buatkan ID (NS001, NS002, dst) untuk penjual yang sudah ada
        $penjual = User::where('role', 'penjual')->get();
        $nomor = 1;
        
        foreach ($penjual as $user) {
            $user->id_nasabah = 'NS' . str_pad($nomor, 3, '0', STR_PAD_LEFT);
            $user->save();
            $nomor++;
        }
    }

    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn('id_nasabah');
        });
    }
};
