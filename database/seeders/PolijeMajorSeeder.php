<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\PolijeMajor;

class PolijeMajorSeeder extends Seeder
{
    public function run(): void
    {
        // Bersihkan data agar tidak duplikat
        PolijeMajor::truncate();

        $jurusans = [
            'Produksi Pertanian',
            'Teknologi Pertanian',
            'Peternakan',
            'Manajemen Agribisnis',
            'Teknologi Informasi',
            'Teknis',
            'Kesehatan',
            'Bahasa, Komunikasi dan Pariwisata',
            'Bisnis',
        ];

        foreach ($jurusans as $jur) {
            PolijeMajor::create([
                'nama_jurusan'  => $jur,
                'deskripsi'     => 'Program ini berfokus pada keahlian di bidang ' . $jur,
                'prospek_kerja' => 'Lulusan dapat berkarir di bidang terkait ' . $jur,
            ]);
        }
    }
}