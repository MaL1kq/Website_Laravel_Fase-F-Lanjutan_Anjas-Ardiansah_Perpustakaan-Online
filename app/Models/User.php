<?php

namespace App\Models;

use Database\Factories\UserFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    /** @use HasFactory<UserFactory> */
    use HasFactory, Notifiable;

    protected $fillable = [
        'name',
        'username',
        'email',
        'password',
        'role',
        'alamat',
        'no_telepon',
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

    public function isPeminjam(): bool
    {
        return $this->role === 'peminjam';
    }

    public function isAdminOrPetugas(): bool
    {
        return $this->role === 'admin';
    }

    public function peminjaman(): HasMany
    {
        return $this->hasMany(Peminjaman::class, 'user_id');
    }

    public function ulasanBuku(): HasMany
    {
        return $this->hasMany(UlasanBuku::class, 'user_id');
    }

    public function koleksiPribadi(): HasMany
    {
        return $this->hasMany(KoleksiPribadi::class, 'user_id');
    }

    public function keranjang(): HasMany
    {
        return $this->hasMany(Keranjang::class, 'user_id');
    }

    public function pesanan(): HasMany
    {
        return $this->hasMany(Pesanan::class, 'user_id');
    }

    public function pesanKontak(): HasMany
    {
        return $this->hasMany(PesanKontak::class, 'user_id');
    }
}
