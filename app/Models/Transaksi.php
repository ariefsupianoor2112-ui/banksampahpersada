<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Transaksi extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'jenis_sampah_id',
        'tipe',
        'berat_kg',
        'harga_per_kg',
        'total',
        'keterangan',
        'admin_id',
        'status',
        'catatan_admin',
        'sumber',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function jenisSampah(): BelongsTo
    {
        return $this->belongsTo(JenisSampah::class, 'jenis_sampah_id');
    }

    public function admin(): BelongsTo
    {
        return $this->belongsTo(User::class, 'admin_id');
    }

    public function scopePending($query)
    {
        return $query->where('status', 'pending');
    }

    public function scopeApproved($query)
    {
        return $query->where('status', 'approved');
    }

    public function scopeRejected($query)
    {
        return $query->where('status', 'rejected');
    }

    // Transaksi yang diajukan sendiri oleh nasabah (bukan dicatat langsung oleh admin)
    public function scopeDiajukanNasabah($query)
    {
        return $query->where('sumber', 'nasabah');
    }
}
