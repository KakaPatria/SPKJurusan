<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PolijeMajor extends Model
{
    use HasFactory;

    // Tambahkan baris ini agar data bisa masuk
    protected $fillable = ['nama_jurusan', 'deskripsi', 'prospek_kerja'];
}