<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class PesananDetail extends Model
{
    use HasFactory;

    protected $table = 'pesanan_detail';

    protected $fillable = [
        'pesanan_id',
        'buku_id',
        'jumlah',
        'harga_satuan',
        'subtotal',
    ];

    protected $casts = [
        'harga_satuan' => 'decimal:2',
        'subtotal' => 'decimal:2',
    ];

    public function pesanan(): BelongsTo
    {
        return $this->belongsTo(Pesanan::class, 'pesanan_id');
    }

    public function buku(): BelongsTo
    {
        return $this->belongsTo(Buku::class, 'buku_id');
    }

    public function getHargaSatuanFormatAttribute(): string
    {
        return 'Rp ' . number_format($this->harga_satuan ?? 0, 0, ',', '.');
    }

    public function getSubtotalFormatAttribute(): string
    {
        return 'Rp ' . number_format($this->subtotal ?? 0, 0, ',', '.');
    }
}
