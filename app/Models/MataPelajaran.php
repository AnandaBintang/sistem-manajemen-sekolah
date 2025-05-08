<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MataPelajaran extends Model
{
    use HasFactory;

    protected $table = 'mata_pelajaran';

    protected $fillable = [
        'nama_mapel',
        'kode_mapel',
    ];

    // Relasi ke guru yang mengajar mata pelajaran ini
    public function guru()
    {
        return $this->belongsToMany(User::class, 'guru_mata_pelajaran')->withTimestamps();
    }
}
