<?php

namespace App\Models;

use Database\Factories\UserFactory;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Attributes\Hidden;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

#[Fillable(['kode', 'name', 'email', 'password', 'role', 'no_hp', 'alamat'])]
#[Hidden(['password', 'remember_token'])]
class User extends Authenticatable
{
    /** @use HasFactory<UserFactory> */
    use HasFactory, Notifiable;

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
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
     * Semua transaksi (setoran & tarik saldo) milik user ini sebagai penjual/nasabah.
     */
    public function transaksis(): HasMany
    {
        return $this->hasMany(Transaksi::class);
    }

    /**
     * Saldo berjalan = total setoran - total penarikan.
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
