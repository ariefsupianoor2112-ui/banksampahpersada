<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    use HasFactory, Notifiable;

    protected $fillable = [
        'id_nasabah',
        'name',
        'email',
        'password',
        'no_hp',
        'alamat',
        'role',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }

    public function isAdmin(): bool
    {
        return $this->role === 'admin';
    }

    /**
     * Relasi ke tabel Transaksi (Satu user punya banyak transaksi)
     */
    public function transaksis()
    {
        return $this->hasMany(Transaksi::class);
    }

    /**
     * Saldo berjalan = total setoran - total penarikan (yang sudah disetujui admin)
     */
    public function getSaldoAttribute(): int
    {
        $setor = $this->transaksis()->where('tipe', 'setor')->where('status', 'approved')->sum('total');
        $tarik = $this->transaksis()->where('tipe', 'tarik')->where('status', 'approved')->sum('total');

        return (int) ($setor - $tarik);
    }

    /**
     * Total berat sampah yang sudah pernah disetor (kg), hanya yang sudah disetujui admin.
     */
    public function getTotalBeratAttribute(): float
    {
        return (float) $this->transaksis()->where('tipe', 'setor')->where('status', 'approved')->sum('berat_kg');
    }

    /**
     * Saldo yang sedang diajukan untuk ditarik (belum disetujui admin).
     */
    public function getSaldoTertahanAttribute(): int
    {
        return (int) $this->transaksis()->where('tipe', 'tarik')->where('status', 'pending')->sum('total');
    }
}
