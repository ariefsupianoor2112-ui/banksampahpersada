<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class JenisSampah extends Model
{
    use HasFactory;

    protected $table = 'jenis_sampah';

    protected $fillable = ['nama_sampah', 'harga_per_kg'];

    public function transaksis(): HasMany
    {
        return $this->hasMany(Transaksi::class);
    }
}
