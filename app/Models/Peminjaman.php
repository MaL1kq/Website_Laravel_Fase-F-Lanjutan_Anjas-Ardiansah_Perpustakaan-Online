<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Peminjaman extends Model
{
    use HasFactory;

    protected $table = 'peminjaman';

    protected $fillable = [
        'kode_transaksi',
        'user_id',
        'buku_id',
        'tanggal_peminjaman',
        'tanggal_pengembalian',
        'tanggal_dikembalikan',
        'status_peminjaman',
        'denda',
        'catatan',
        'admin_id',
    ];

    protected $casts = [
        'tanggal_peminjaman' => 'date',
        'tanggal_pengembalian' => 'date',
        'tanggal_dikembalikan' => 'date',
        'denda' => 'decimal:2',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function buku(): BelongsTo
    {
        return $this->belongsTo(Buku::class, 'buku_id');
    }

    public function admin(): BelongsTo
    {
        return $this->belongsTo(User::class, 'admin_id');
    }

    public function petugas(): BelongsTo
    {
        return $this->admin();
    }

    /**
     * Hitung keterlambatan (hari) dan denda (Rp 1.000 / hari)
     */
    public function hitungDenda(): float
    {
        if ($this->status_peminjaman === 'dikembalikan' && $this->tanggal_dikembalikan) {
            $tglSelesai = Carbon::parse($this->tanggal_dikembalikan);
        } else {
            $tglSelesai = Carbon::now();
        }

        $batasWaktu = Carbon::parse($this->tanggal_pengembalian);

        if ($tglSelesai->greaterThan($batasWaktu)) {
            $hariTelat = $batasWaktu->diffInDays($tglSelesai, false);
            if ($hariTelat > 0) {
                return (float) ($hariTelat * 1000);
            }
        }

        return 0;
    }

    public function getHariTerlambatAttribute(): int
    {
        $batasWaktu = Carbon::parse($this->tanggal_pengembalian);
        $tglSelesai = $this->tanggal_dikembalikan ? Carbon::parse($this->tanggal_dikembalikan) : Carbon::now();

        if ($tglSelesai->greaterThan($batasWaktu)) {
            return (int) $batasWaktu->diffInDays($tglSelesai, false);
        }

        return 0;
    }
}
