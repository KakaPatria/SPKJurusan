<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ChatHistory extends Model
{
    use HasFactory;

    protected $table = 'riwayat_chat';

    protected $fillable = [
        'user_id',
        'id_sesi',
        'id_rekomendasi',
        'pertanyaan',
        'jawaban',
    ];

    public function getSessionIdAttribute()
    {
        return $this->attributes['id_sesi'] ?? null;
    }

    public function setSessionIdAttribute($value): void
    {
        $this->attributes['id_sesi'] = $value;
    }

    public function getPromptAttribute()
    {
        return $this->attributes['pertanyaan'] ?? null;
    }

    public function setPromptAttribute($value): void
    {
        $this->attributes['pertanyaan'] = $value;
    }

    public function getResponseAttribute()
    {
        return $this->attributes['jawaban'] ?? null;
    }

    public function setResponseAttribute($value): void
    {
        $this->attributes['jawaban'] = $value;
    }

    public function getRecommendationIdAttribute()
    {
        return $this->attributes['id_rekomendasi'] ?? null;
    }

    public function setRecommendationIdAttribute($value): void
    {
        $this->attributes['id_rekomendasi'] = $value;
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function recommendation()
    {
        return $this->belongsTo(Recommendation::class, 'id_rekomendasi');
    }
}
