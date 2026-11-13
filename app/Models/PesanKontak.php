<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class PesanKontak extends Model
{
    use HasFactory;

    protected $table = 'pesan_kontak';

    protected $fillable = [
        'user_id',
        'nama',
        'email',
        'no_telepon',
        'subjek',
        'pesan',
        'balasan_admin',
        'status',
        'dibaca_pada',
        'dibalas_pada',
    ];

    protected $casts = [
        'dibaca_pada' => 'datetime',
        'dibalas_pada' => 'datetime',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function getStatusLabelAttribute(): string
    {
        return match($this->status) {
            'belum_dibaca' => 'Pesan Baru',
            'dibaca' => 'Sudah Dibaca',
            'dibalas' => 'Sudah Dibalas',
            default => ucfirst($this->status),
        };
    }

    public function getStatusBadgeAttribute(): string
    {
        return match($this->status) {
            'belum_dibaca' => 'bg-rose-100 text-rose-800 border-rose-200',
            'dibaca' => 'bg-amber-100 text-amber-800 border-amber-200',
            'dibalas' => 'bg-emerald-100 text-emerald-800 border-emerald-200',
            default => 'bg-slate-100 text-slate-800 border-slate-200',
        };
    }
}
