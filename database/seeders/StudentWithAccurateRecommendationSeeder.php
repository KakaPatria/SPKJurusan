<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\Recommendation;
use App\Models\PolijeMajor;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class StudentWithAccurateRecommendationSeeder extends Seeder
{
    public function run(): void
    {
        // Data siswa dengan nilai yang logis untuk setiap rekomendasi
        $studentsData = [
            // Siswa yang cocok untuk Teknologi Informasi
            [
                'name' => 'Rino Pratama',
                'email' => 'rino.pratama@student.edu',
                'kelompok_asal' => 'IPA',
                'values' => ['mtk' => 92, 'fisika' => 88, 'kimia' => 75, 'biologi' => 70],
                'minat' => 'Logika Komputer',
                'pref_studi' => 'Sains & Teknologi',
                'cita_cita' => 'Software Engineer',
                'prestasi' => 'Juara 1 Kompetisi Coding',
            ],
            [
                'name' => 'Budi Santoso',
                'email' => 'budi.santoso@student.edu',
                'kelompok_asal' => 'IPA',
                'values' => ['mtk' => 88, 'fisika' => 85, 'kimia' => 70, 'biologi' => 68],
                'minat' => 'Pemrograman',
                'pref_studi' => 'Sains & Teknologi',
                'cita_cita' => 'Web Developer',
                'prestasi' => 'Peserta Hackathon 2025',
            ],
            // Siswa yang cocok untuk Teknik
            [
                'name' => 'Adi Wijaya',
                'email' => 'adi.wijaya@student.edu',
                'kelompok_asal' => 'IPA',
                'values' => ['mtk' => 85, 'fisika' => 90, 'kimia' => 70, 'biologi' => 65],
                'minat' => 'Mekanika & Energi',
                'pref_studi' => 'Sains & Teknologi',
                'cita_cita' => 'Insinyur Mesin',
                'prestasi' => 'Juara 2 Robotika',
            ],
            [
                'name' => 'Dani Pratama',
                'email' => 'dani.pratama@student.edu',
                'kelompok_asal' => 'IPA',
                'values' => ['mtk' => 86, 'fisika' => 88, 'kimia' => 72, 'biologi' => 66],
                'minat' => 'Elektronika',
                'pref_studi' => 'Sains & Teknologi',
                'cita_cita' => 'Teknisi Elektronik',
                'prestasi' => 'Aktif di klub robotika',
            ],
            // Siswa yang cocok untuk Kesehatan
            [
                'name' => 'Siti Nurhaliza',
                'email' => 'siti.nurhaliza@student.edu',
                'kelompok_asal' => 'IPA',
                'values' => ['mtk' => 80, 'fisika' => 70, 'kimia' => 88, 'biologi' => 92],
                'minat' => 'Ilmu Hayat & Pelayanan',
                'pref_studi' => 'Kesehatan & Ilmu Hayat',
                'cita_cita' => 'Perawat Profesional',
                'prestasi' => 'Juara Olimpiade Biologi',
            ],
            [
                'name' => 'Tina Susanti',
                'email' => 'tina.susanti@student.edu',
                'kelompok_asal' => 'IPA',
                'values' => ['mtk' => 78, 'fisika' => 68, 'kimia' => 90, 'biologi' => 89],
                'minat' => 'Farmasi & Kesehatan',
                'pref_studi' => 'Kesehatan & Ilmu Hayat',
                'cita_cita' => 'Apoteker',
                'prestasi' => 'Aktif di PMR',
            ],
            // Siswa yang cocok untuk Pertanian
            [
                'name' => 'Wayan Suparta',
                'email' => 'wayan.suparta@student.edu',
                'kelompok_asal' => 'IPA',
                'values' => ['mtk' => 75, 'fisika' => 72, 'kimia' => 82, 'biologi' => 88],
                'minat' => 'Alam & Pertanian',
                'pref_studi' => 'Pertanian & Lingkungan',
                'cita_cita' => 'Ahli Pertanian',
                'prestasi' => 'Peraih Medali Sains',
            ],
            [
                'name' => 'Bambang Hermawan',
                'email' => 'bambang.hermawan@student.edu',
                'kelompok_asal' => 'IPA',
                'values' => ['mtk' => 78, 'fisika' => 75, 'kimia' => 80, 'biologi' => 85],
                'minat' => 'Ternak & Hewan',
                'pref_studi' => 'Pertanian & Lingkungan',
                'cita_cita' => 'Peternak Modern',
                'prestasi' => 'Juara Pameran Ternak',
            ],
            // Siswa cocok untuk Teknologi Pertanian
            [
                'name' => 'Hendra Sutrisno',
                'email' => 'hendra.sutrisno@student.edu',
                'kelompok_asal' => 'IPA',
                'values' => ['mtk' => 87, 'fisika' => 86, 'kimia' => 76, 'biologi' => 78],
                'minat' => 'Inovasi & Teknologi Pertanian',
                'pref_studi' => 'Sains & Teknologi',
                'cita_cita' => 'Engineer Pertanian',
                'prestasi' => 'Penemu Alat Pertanian',
            ],
            // Siswa IPS yang cocok untuk Bisnis/Ekonomi
            [
                'name' => 'Rina Handayani',
                'email' => 'rina.handayani@student.edu',
                'kelompok_asal' => 'IPS',
                'values' => ['ekonomi' => 92, 'geografi' => 85, 'sosiologi' => 80, 'sejarah' => 78],
                'minat' => 'Bisnis & Kewirausahaan',
                'pref_studi' => 'Bisnis & Manajemen',
                'cita_cita' => 'Entrepreneur',
                'prestasi' => 'Juara Kompetisi Bisnis',
            ],
            [
                'name' => 'Agus Suryanto',
                'email' => 'agus.suryanto@student.edu',
                'kelompok_asal' => 'IPS',
                'values' => ['ekonomi' => 88, 'geografi' => 82, 'sosiologi' => 85, 'sejarah' => 80],
                'minat' => 'Manajemen & Akuntansi',
                'pref_studi' => 'Bisnis & Manajemen',
                'cita_cita' => 'Akuntan Profesional',
                'prestasi' => 'Peserta Lomba Case Study',
            ],
            // Siswa IPS untuk Agribisnis
            [
                'name' => 'Mardi Santoso',
                'email' => 'mardi.santoso@student.edu',
                'kelompok_asal' => 'IPS',
                'values' => ['ekonomi' => 85, 'geografi' => 88, 'sosiologi' => 78, 'sejarah' => 75],
                'minat' => 'Pertanian & Bisnis',
                'pref_studi' => 'Bisnis & Manajemen',
                'cita_cita' => 'Manager Agribisnis',
                'prestasi' => 'Aktif di OSIS',
            ],
            // Siswa IPS untuk Bahasa & Komunikasi
            [
                'name' => 'Nadia Putri',
                'email' => 'nadia.putri@student.edu',
                'kelompok_asal' => 'IPS',
                'values' => ['sejarah' => 88, 'sosiologi' => 86, 'geografi' => 80, 'ekonomi' => 78],
                'minat' => 'Bahasa & Komunikasi',
                'pref_studi' => 'Sosial & Humaniora',
                'cita_cita' => 'Jurnalis Profesional',
                'prestasi' => 'Penulis Artikel Terpercaya',
            ],
            [
                'name' => 'Lisa Maharani',
                'email' => 'lisa.maharani@student.edu',
                'kelompok_asal' => 'IPS',
                'values' => ['sejarah' => 85, 'sosiologi' => 82, 'geografi' => 85, 'ekonomi' => 76],
                'minat' => 'Pariwisata & Budaya',
                'pref_studi' => 'Sosial & Humaniora',
                'cita_cita' => 'Tour Guide Profesional',
                'prestasi' => 'Peserta Pelatihan Tour Guide',
            ],
        ];

        $majorNames = PolijeMajor::pluck('nama_jurusan')->toArray();

        foreach ($studentsData as $data) {
            // Buat user siswa
            $user = User::firstOrCreate(
                ['email' => $data['email']],
                [
                    'name' => $data['name'],
                    'password' => bcrypt('password'),
                    'role' => 'siswa',
                    'kelompok_asal' => $data['kelompok_asal'],
                ]
            );

            // Hitung skor rekomendasi berdasarkan bobot mapel
            $scores = $this->calculateScores($data, $majorNames);

            // Ambil top 3 dengan skor tertinggi
            arsort($scores);
            $topRecommendations = array_slice($scores, 0, 3, true);

            // Buat data rekomendasi dengan ranking
            $recommendations = [];
            foreach ($topRecommendations as $majorName => $score) {
                $recommendations[] = [
                    'jurusan' => $majorName,
                    'skor' => round($score, 2),
                    'detail' => "Rekomendasi berdasarkan nilai, minat, dan preferensi studi.",
                ];
            }

            // Simpan rekomendasi ke database
            Recommendation::create([
                'user_id' => $user->id,
                'mtk' => $data['values']['mtk'] ?? 0,
                'fisika' => $data['values']['fisika'] ?? 0,
                'kimia' => $data['values']['kimia'] ?? 0,
                'biologi' => $data['values']['biologi'] ?? 0,
                'ekonomi' => $data['values']['ekonomi'] ?? 0,
                'sejarah' => $data['values']['sejarah'] ?? 0,
                'geografi' => $data['values']['geografi'] ?? 0,
                'sosiologi' => $data['values']['sosiologi'] ?? 0,
                'minat' => $data['minat'],
                'preferensi_studi' => $data['pref_studi'],
                'cita_cita' => $data['cita_cita'],
                'prestasi' => $data['prestasi'],
                'hasil_rekomendasi' => json_encode($recommendations),
            ]);
        }

        $this->command->info('✅ ' . count($studentsData) . ' siswa dengan rekomendasi akurat sudah ditambahkan!');
    }

    private function calculateScores(array $studentData, array $majorNames): array
    {
        $scores = [];
        $majors = PolijeMajor::all()->keyBy('nama_jurusan');

        foreach ($majorNames as $majorName) {
            if (!isset($majors[$majorName])) continue;

            $major = $majors[$majorName];
            $bobot = $major->bobot_mapel;
            $score = 0;

            // Hitung skor berdasarkan bobot dan nilai siswa
            foreach ($bobot as $subject => $weight) {
                $value = $studentData['values'][$subject] ?? 0;
                $score += ($value * $weight) / 100;
            }

            // Bonus untuk preferensi studi yang sesuai
            if (in_array($studentData['pref_studi'], $major->preferensi_studi)) {
                $score += 5;
            }

            $scores[$majorName] = $score;
        }

        return $scores;
    }
}
