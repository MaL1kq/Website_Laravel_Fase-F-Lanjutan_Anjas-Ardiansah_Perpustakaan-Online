<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Str;

class Buku extends Model
{
    use HasFactory;

    protected $table = 'buku';

    protected $fillable = [
        'judul',
        'slug',
        'penulis',
        'penerbit',
        'tahun_terbit',
        'isbn',
        'kategori_id',
        'harga',
        'stok',
        'cover',
        'lokasi_rak',
        'deskripsi',
    ];

    protected static function boot()
    {
        parent::boot();
        static::creating(function ($buku) {
            if (empty($buku->slug)) {
                $buku->slug = Str::slug($buku->judul) . '-' . Str::random(5);
            }
        });
    }

    public function kategori(): BelongsTo
    {
        return $this->belongsTo(KategoriBuku::class, 'kategori_id');
    }

    public function kategoriRelasi(): BelongsToMany
    {
        return $this->belongsToMany(KategoriBuku::class, 'kategori_buku_relasi', 'buku_id', 'kategori_id')->withTimestamps();
    }

    public function peminjaman(): HasMany
    {
        return $this->hasMany(Peminjaman::class, 'buku_id');
    }

    public function ulasan(): HasMany
    {
        return $this->hasMany(UlasanBuku::class, 'buku_id');
    }

    public function koleksiPribadi(): HasMany
    {
        return $this->hasMany(KoleksiPribadi::class, 'buku_id');
    }

    public function rataRataRating(): float
    {
        return round($this->ulasan()->avg('rating') ?? 0, 1);
    }

    public function totalUlasan(): int
    {
        return $this->ulasan()->count();
    }

    public function keranjang(): HasMany
    {
        return $this->hasMany(Keranjang::class, 'buku_id');
    }

    public function pesananDetail(): HasMany
    {
        return $this->hasMany(PesananDetail::class, 'buku_id');
    }

    public function getHargaFormatAttribute(): string
    {
        return 'Rp ' . number_format($this->harga ?? 0, 0, ',', '.');
    }

    public function getCoverUrlAttribute(): string
    {
        if ($this->cover && (Str::startsWith($this->cover, 'http://') || Str::startsWith($this->cover, 'https://'))) {
            return $this->cover;
        }
        if ($this->cover && file_exists(public_path('storage/' . $this->cover))) {
            return asset('storage/' . $this->cover);
        }
        // Fallback default placeholder
        return 'https://images.unsplash.com/photo-1544716278-ca5e3f4abd8c?auto=format&fit=crop&w=600&q=80';
    }
}
