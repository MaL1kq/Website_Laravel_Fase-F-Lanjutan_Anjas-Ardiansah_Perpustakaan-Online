<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Str;

class KategoriBuku extends Model
{
    use HasFactory;

    protected $table = 'kategori_buku';

    protected $fillable = [
        'nama_kategori',
        'slug',
        'deskripsi',
    ];

    protected static function boot()
    {
        parent::boot();
        static::creating(function ($kategori) {
            if (empty($kategori->slug)) {
                $kategori->slug = Str::slug($kategori->nama_kategori);
            }
        });
    }

    public function buku(): HasMany
    {
        return $this->hasMany(Buku::class, 'kategori_id');
    }

    public function bukuRelasi(): BelongsToMany
    {
        return $this->belongsToMany(Buku::class, 'kategori_buku_relasi', 'kategori_id', 'buku_id')->withTimestamps();
    }
}
