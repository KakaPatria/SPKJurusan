<?php

namespace Database\Seeders;

use App\Models\Alumni;
use Illuminate\Database\Seeder;

class AlumniSeeder extends Seeder
{
    public function run(): void
    {
        // Alumni SMA Bima Ambulu yang MASUK ke Polije
        // INPUT: Nilai SMA + Variabel Non-Akademik
        // OUTPUT: Jurusan yang dipilih di Polije
        // VALIDASI: Ranking rekomendasi cocok? (success = ranking 1-3, fail = ranking >5)
        
        $alumniData = [
            // === IPA ===
            [
                'nama_alumni' => 'Budi Santoso',
                'nis' => 'SMA001',
                'kelompok_asal' => 'IPA',
                'mtk' => 85,
                'fisika' => 82,
                'kimia' => 88,
                'biologi' => 90,
                'minat' => 'Teknologi & Robotika',
                'cita_cita' => 'Software Developer',
                'preferensi_studi' => 'Sains & Teknologi',
                'prestasi' => 'Juara 1 Olimpiade Komputer Nasional',
                'major_masuk' => 'Teknik Informatika',
                'tahun_lulus_polije' => 2027,
                'catatan' => 'Alumni 2023 - Rekomendasi akurat',
            ],
            [
                'nama_alumni' => 'Siti Nurhaliza',
                'nis' => 'SMA002',
                'kelompok_asal' => 'IPA',
                'mtk' => 90,
                'fisika' => 88,
                'kimia' => 92,
                'biologi' => 94,
                'minat' => 'Kesehatan & Bioteknologi',
                'cita_cita' => 'Biomedical Engineer',
                'preferensi_studi' => 'Kesehatan & Ilmu Hayat',
                'prestasi' => 'Beasiswa Penuh Akademik',
                'major_masuk' => 'Teknik Biomedis',
                'tahun_lulus_polije' => 2027,
                'catatan' => 'Alumni 2023 - Rekomendasi akurat',
            ],
            [
                'nama_alumni' => 'Ahmad Wijaya',
                'nis' => 'SMA003',
                'kelompok_asal' => 'IPA',
                'mtk' => 75,
                'fisika' => 78,
                'kimia' => 80,
                'biologi' => 72,
                'minat' => 'Teknik Mesin',
                'cita_cita' => 'Mechanical Engineer',
                'preferensi_studi' => 'Sains & Teknologi',
                'prestasi' => 'Sertifikat Kompetisi Robotika',
                'major_masuk' => 'Teknik Mesin',
                'tahun_lulus_polije' => 2027,
                'catatan' => 'Alumni 2023',
            ],
            [
                'nama_alumni' => 'Lina Hartini',
                'nis' => 'SMA004',
                'kelompok_asal' => 'IPA',
                'mtk' => 88,
                'fisika' => 86,
                'kimia' => 90,
                'biologi' => 92,
                'minat' => 'Riset & Sains Terapan',
                'cita_cita' => 'Research Scientist',
                'preferensi_studi' => 'Kesehatan & Ilmu Hayat',
                'prestasi' => 'Publikasi Paper Research',
                'major_masuk' => 'Teknik Biomedis',
                'tahun_lulus_polije' => 2027,
                'catatan' => 'Alumni 2023 - Rekomendasi sangat akurat',
            ],
            [
                'nama_alumni' => 'Fajar Maulana',
                'nis' => 'SMA005',
                'kelompok_asal' => 'IPA',
                'mtk' => 72,
                'fisika' => 70,
                'kimia' => 68,
                'biologi' => 65,
                'minat' => 'Teknik Elektro',
                'cita_cita' => 'Electrical Engineer',
                'preferensi_studi' => 'Sains & Teknologi',
                'prestasi' => '-',
                'major_masuk' => 'Teknik Mesin',
                'tahun_lulus_polije' => 2027,
                'catatan' => 'Alumni 2023',
            ],
            
            // === IPS ===
            [
                'nama_alumni' => 'Rina Handayani',
                'nis' => 'SMA006',
                'kelompok_asal' => 'IPS',
                'ekonomi' => 90,
                'geografi' => 87,
                'sosiologi' => 88,
                'sejarah' => 85,
                'minat' => 'Bisnis & Manajemen',
                'cita_cita' => 'Business Manager',
                'preferensi_studi' => 'Bisnis & Manajemen',
                'prestasi' => 'Juara Debat Nasional',
                'major_masuk' => 'Manajemen Bisnis',
                'tahun_lulus_polije' => 2027,
                'catatan' => 'Alumni 2023',
            ],
            [
                'nama_alumni' => 'Dewi Prasetya',
                'nis' => 'SMA007',
                'kelompok_asal' => 'IPS',
                'ekonomi' => 85,
                'geografi' => 86,
                'sosiologi' => 84,
                'sejarah' => 88,
                'minat' => 'Akuntansi & Keuangan',
                'cita_cita' => 'Akuntan Publik',
                'preferensi_studi' => 'Bisnis & Manajemen',
                'prestasi' => 'Sertifikasi ACCA',
                'major_masuk' => 'Akuntansi',
                'tahun_lulus_polije' => 2027,
                'catatan' => 'Alumni 2023',
            ],
            [
                'nama_alumni' => 'Rudi Hermawan',
                'nis' => 'SMA008',
                'kelompok_asal' => 'IPS',
                'ekonomi' => 68,
                'geografi' => 71,
                'sosiologi' => 65,
                'sejarah' => 72,
                'minat' => 'Pemerintahan & Administrasi',
                'cita_cita' => 'PNS',
                'preferensi_studi' => 'Sosial & Humaniora',
                'prestasi' => '-',
                'major_masuk' => 'Administrasi Publik',
                'tahun_lulus_polije' => 2027,
                'catatan' => 'Alumni 2023',
            ],
            [
                'nama_alumni' => 'Indra Setiawan',
                'nis' => 'SMA009',
                'kelompok_asal' => 'IPS',
                'ekonomi' => 82,
                'geografi' => 80,
                'sosiologi' => 78,
                'sejarah' => 84,
                'minat' => 'Marketing & Digital',
                'cita_cita' => 'Marketing Manager',
                'preferensi_studi' => 'Bisnis & Manajemen',
                'prestasi' => 'Kompetisi Business Plan',
                'major_masuk' => 'Manajemen Bisnis',
                'tahun_lulus_polije' => 2027,
                'catatan' => 'Alumni 2023',
            ],
            [
                'nama_alumni' => 'Maya Suntari',
                'nis' => 'SMA010',
                'kelompok_asal' => 'IPS',
                'ekonomi' => 75,
                'geografi' => 73,
                'sosiologi' => 76,
                'sejarah' => 78,
                'minat' => 'Akuntansi & Keuangan',
                'cita_cita' => 'Accountant',
                'preferensi_studi' => 'Bisnis & Manajemen',
                'prestasi' => 'Buku Tahunan Finance Club',
                'major_masuk' => 'Akuntansi',
                'tahun_lulus_polije' => 2027,
                'catatan' => 'Alumni 2023',
            ],
        ];

        foreach ($alumniData as $data) {
            Alumni::firstOrCreate(
                ['nis' => $data['nis']],
                $data
            );
        }
        
        echo "\n✅ AlumniSeeder: " . count($alumniData) . " alumni SMA Bima Ambulu loaded!\n";
        echo "📊 Fokus: Validasi akurasi rekomendasi (ranking 1-3 = sukses, >5 = gagal)\n";
    }
}
