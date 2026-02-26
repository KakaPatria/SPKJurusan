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
        'mtk',
        'fisika',
        'kimia',
        'biologi',
        'ekonomi',
        'geografi',
        'sosiologi',
        'sejarah',
        'minat',
        'cita_cita',
        'preferensi_studi',
        'prestasi',
        'major_masuk',
        'ranking_saat_rekomendasi',
        'success_status',
        'catatan',
    ];

    protected $casts = [
        'mtk' => 'float',
        'fisika' => 'float',
        'kimia' => 'float',
        'biologi' => 'float',
        'ekonomi' => 'float',
        'geografi' => 'float',
        'sosiologi' => 'float',
        'sejarah' => 'float',
        'nilai_rata_rata' => 'float',
        'ipk_lulus' => 'float',
        'predicted_score' => 'float',
    ];

    /**
     * Hitung nilai rata-rata otomatis
     */
    public static function booted()
    {
        static::saving(function ($alumni) {
            // Gather nilai based on kelompok_asal
            $nilaiFields = ['mtk'];
            
            if ($alumni->kelompok_asal == 'IPA') {
                $nilaiFields = ['mtk', 'fisika', 'kimia', 'biologi'];
            } else {
                $nilaiFields = ['mtk', 'ekonomi', 'geografi', 'sosiologi', 'sejarah'];
            }

            $nilaiValues = [];
            foreach ($nilaiFields as $field) {
                if (!is_null($alumni->$field)) {
                    $nilaiValues[] = $alumni->$field;
                }
            }

            $alumni->nilai_rata_rata = count($nilaiValues) > 0 
                ? round(array_sum($nilaiValues) / count($nilaiValues), 2)
                : null;
        });
    }
}
