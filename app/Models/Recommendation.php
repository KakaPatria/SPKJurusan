<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Recommendation extends Model
{
    use HasFactory;

    protected $table = 'rekomendasi';

    protected $fillable = [
        'user_id',
        'mtk',
        'fisika',
        'kimia',
        'biologi',
        'ekonomi',
        'geografi',
        'sosiologi',
        'sejarah',
        'minat',
        'preferensi_studi',
        'cita_cita',
        'prestasi',
        'hasil_rekomendasi',
    ];

    protected $casts = [
        'hasil_rekomendasi' => 'array',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
