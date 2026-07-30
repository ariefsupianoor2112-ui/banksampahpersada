<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->string('kode', 20)->unique()->nullable()->after('id');
        });

        $number = 1;
        DB::table('users')->where('role', 'penjual')->orderBy('id')->get()->each(function ($user) use (&$number) {
            DB::table('users')->where('id', $user->id)->update([
                'kode' => 'NS' . str_pad((string) $number, 3, '0', STR_PAD_LEFT),
            ]);
            $number++;
        });
    }

    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn('kode');
        });
    }
};
