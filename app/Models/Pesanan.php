<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Pesanan extends Model
{
    use HasFactory;

    protected $table = 'pesanan';

    protected $fillable = [
        'kode_pesanan',
        'user_id',
        'total_harga',
        'status',
        'metode_pembayaran',
        'nama_penerima',
        'no_telepon',
        'alamat_pengiriman',
        'catatan',
    ];

    protected $casts = [
        'total_harga' => 'decimal:2',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function detail(): HasMany
    {
        return $this->hasMany(PesananDetail::class, 'pesanan_id');
    }

    public function getNoPesananAttribute(): string
    {
        return $this->kode_pesanan ?? '';
    }

    public function setNoPesananAttribute($value): void
    {
        $this->attributes['kode_pesanan'] = $value;
    }

    public function getTotalHargaFormatAttribute(): string
    {
        return 'Rp ' . number_format($this->total_harga ?? 0, 0, ',', '.');
    }

    public function getStatusLabelAttribute(): string
    {
        return match($this->status) {
            'menunggu_pembayaran' => 'Menunggu Pembayaran',
            'diproses' => 'Sedang Diproses',
            'dikirim' => 'Dalam Pengiriman',
            'selesai' => 'Selesai',
            'dibatalkan' => 'Dibatalkan',
            default => ucfirst($this->status),
        };
    }

    public function getStatusBadgeAttribute(): string
    {
        return $this->status_badge_class;
    }

    public function getStatusBadgeClassAttribute(): string
    {
        return match($this->status) {
            'menunggu_pembayaran' => 'bg-amber-100 text-amber-800 border-amber-200',
            'diproses' => 'bg-blue-100 text-blue-800 border-blue-200',
            'dikirim' => 'bg-indigo-100 text-indigo-800 border-indigo-200',
            'selesai' => 'bg-emerald-100 text-emerald-800 border-emerald-200',
            'dibatalkan' => 'bg-rose-100 text-rose-800 border-rose-200',
            default => 'bg-slate-100 text-slate-800 border-slate-200',
        };
    }
}
