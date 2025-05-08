<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;
use Filament\Models\Contracts\FilamentUser;
use Filament\Panel;

class User extends Authenticatable implements FilamentUser
{
    use HasApiTokens, HasFactory, Notifiable;

    protected $fillable = [
        'name',
        'email',
        'password',
        'role',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected $casts = [
        'email_verified_at' => 'datetime',
        'password' => 'hashed',
    ];

    public function canAccessPanel(Panel $panel): bool
    {
        if ($this->role === 'admin') {
            return true;
        }

        if ($this->role === 'guru' && $panel->getId() === 'guru') {
            return true;
        }

        if ($this->role === 'siswa' && $panel->getId() === 'siswa') {
            return true;
        }

        return false;
    }

    public function kelasWali()
    {
        return $this->hasMany(Kelas::class, 'wali_kelas_id');
    }

    public function kelas()
    {
        return $this->belongsToMany(Kelas::class, 'siswa_kelas')->withTimestamps();
    }

    public function mataPelajaran()
    {
        return $this->belongsToMany(MataPelajaran::class, 'guru_mata_pelajaran')->withTimestamps();
    }

    public function isAdmin()
    {
        return $this->role === 'admin';
    }

    public function isGuru()
    {
        return $this->role === 'guru';
    }

    public function isSiswa()
    {
        return $this->role === 'siswa';
    }
}
