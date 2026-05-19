<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Alumni extends Model
{
    use HasFactory;

    protected $table = 'alumni';

    protected $fillable = [
        'nama_alumni',
        'nis',
        'kelompok_asal',
        'nilai_rata_rata',
        'minat',
        'cita_cita',
        'preferensi_studi',
        'prestasi',
        'major_masuk',
        'tahun_lulus_polije',
        'catatan',
    ];

    protected $casts = [
        'nilai_rata_rata' => 'float',
    ];
}
