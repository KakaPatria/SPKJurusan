<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PolijeMajor extends Model
{
    use HasFactory;

    protected $fillable = [
        'nama_jurusan',
        'deskripsi',
        'keywords',
        'preferensi_studi',
        'bobot_mapel',
        'prospek_kerja',
    ];

    protected $casts = [
        'keywords' => 'array',
        'preferensi_studi' => 'array',
        'bobot_mapel' => 'array',
    ];
}