<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\Recommendation;
use App\Models\PolijeMajor;
use Illuminate\Database\Seeder;

class BimaAmbulustudentSeeder extends Seeder
{
    /**
     * SEEDER UNTUK DATA SISWA SMK BIMA AMBULU
     * 
     * Edit data siswa di bawah sesuai dengan data siswa dari SMK Bima Ambulu
     * Format: 
     * - Kelompok: IPA atau IPS
     * - Nilai: 0-100
     * - Email: harus unik
     * 
     * Run: php artisan db:seed --class=BimaAmbulustudentSeeder
     */
    
    public function run(): void
    {
        $studentsData = [
            // ========== SISWA SMK BIMA AMBULU - KELOMPOK IPA ==========
            
            [
                'name' => 'Akmal Fiqri',
                'email' => 'akmal.fiqri@bima.student',
                'kelompok_asal' => 'IPA',
                'values' => ['mtk' => 85, 'fisika' => 82, 'kimia' => 75, 'biologi' => 70],
                'minat' => 'Teknologi & Inovasi',
                'pref_studi' => 'Sains & Teknologi',
                'cita_cita' => 'Software Engineer',
                'prestasi' => 'Aktif di klub robotika',
            ],
            [
                'name' => 'Sasha Putri Aulia',
                'email' => 'sasha.putri@bima.student',
                'kelompok_asal' => 'IPA',
                'values' => ['mtk' => 78, 'fisika' => 80, 'kimia' => 85, 'biologi' => 82],
                'minat' => 'Kesehatan & Biologi',
                'pref_studi' => 'Kesehatan & Ilmu Hayat',
                'cita_cita' => 'Dokter Umum',
                'prestasi' => 'Juara Olimpiade Biologi',
            ],
            [
                'name' => 'Budi Kusuma',
                'email' => 'budi.kusuma@bima.student',
                'kelompok_asal' => 'IPA',
                'values' => ['mtk' => 82, 'fisika' => 88, 'kimia' => 76, 'biologi' => 68],
                'minat' => 'Teknik & Mesin',
                'pref_studi' => 'Sains & Teknologi',
                'cita_cita' => 'Insinyur Mesin',
                'prestasi' => 'Peserta Kompetisi Teknik',
            ],
            [
                'name' => 'Dina Hartini',
                'email' => 'dina.hartini@bima.student',
                'kelompok_asal' => 'IPA',
                'values' => ['mtk' => 75, 'fisika' => 72, 'kimia' => 88, 'biologi' => 90],
                'minat' => 'Farmasi & Kimia',
                'pref_studi' => 'Kesehatan & Ilmu Hayat',
                'cita_cita' => 'Apoteker',
                'prestasi' => 'Ranking 5 Besar',
            ],
            [
                'name' => 'Eka Prasetyo',
                'email' => 'eka.prasetyo@bima.student',
                'kelompok_asal' => 'IPA',
                'values' => ['mtk' => 88, 'fisika' => 85, 'kimia' => 72, 'biologi' => 75],
                'minat' => 'Pemrograman & Coding',
                'pref_studi' => 'Sains & Teknologi',
                'cita_cita' => 'Web Developer',
                'prestasi' => 'Peserta Hackathon',
            ],
            
            // ========== SISWA SMK BIMA AMBULU - KELOMPOK IPS ==========
            
            [
                'name' => 'Fahmi Rizki',
                'email' => 'fahmi.rizki@bima.student',
                'kelompok_asal' => 'IPS',
                'values' => ['ekonomi' => 88, 'geografi' => 80, 'sosiologi' => 78, 'sejarah' => 75],
                'minat' => 'Bisnis & Keuangan',
                'pref_studi' => 'Bisnis & Manajemen',
                'cita_cita' => 'Akuntan Profesional',
                'prestasi' => 'Aktif di organisasi bisnis',
            ],
            [
                'name' => 'Gina Melani',
                'email' => 'gina.melani@bima.student',
                'kelompok_asal' => 'IPS',
                'values' => ['ekonomi' => 85, 'geografi' => 88, 'sosiologi' => 82, 'sejarah' => 80],
                'minat' => 'Pariwisata & Budaya',
                'pref_studi' => 'Sosial & Humaniora',
                'cita_cita' => 'Tour Guide Profesional',
                'prestasi' => 'Pelatihan Pariwisata',
            ],
            [
                'name' => 'Hasan Wijaya',
                'email' => 'hasan.wijaya@bima.student',
                'kelompok_asal' => 'IPS',
                'values' => ['ekonomi' => 82, 'geografi' => 85, 'sosiologi' => 80, 'sejarah' => 78],
                'minat' => 'Manajemen & Administrasi',
                'pref_studi' => 'Bisnis & Manajemen',
                'cita_cita' => 'Manager Perusahaan',
                'prestasi' => 'Peserta Case Study',
            ],
            [
                'name' => 'Irma Santika',
                'email' => 'irma.santika@bima.student',
                'kelompok_asal' => 'IPS',
                'values' => ['ekonomi' => 75, 'geografi' => 92, 'sosiologi' => 85, 'sejarah' => 88],
                'minat' => 'Budaya & Komunikasi',
                'pref_studi' => 'Sosial & Humaniora',
                'cita_cita' => 'Jurnalis',
                'prestasi' => 'Penulis artikel',
            ],
            [
                'name' => 'Joko Supriyanto',
                'email' => 'joko.supriyanto@bima.student',
                'kelompok_asal' => 'IPS',
                'values' => ['ekonomi' => 80, 'geografi' => 75, 'sosiologi' => 88, 'sejarah' => 82],
                'minat' => 'Sosial & Masyarakat',
                'pref_studi' => 'Sosial & Humaniora',
                'cita_cita' => 'Pekerja Sosial',
                'prestasi' => 'Aktif di kegiatan sosial',
            ],
        ];

        $majorNames = PolijeMajor::pluck('nama_jurusan')->toArray();

        foreach ($studentsData as $data) {
            // Cek apakah siswa sudah ada
            $existingUser = User::where('email', $data['email'])->first();
            
            if ($existingUser) {
                $this->command->warn("⚠ Siswa {$data['name']} ({$data['email']}) sudah ada di sistem, skip...");
                continue;
            }

            // Buat user siswa baru
            $user = User::create([
                'name' => $data['name'],
                'email' => $data['email'],
                'password' => bcrypt('password'), // Password default
                'role' => 'siswa',
                'kelompok_asal' => $data['kelompok_asal'],
            ]);

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
                    'detail' => "Rekomendasi berdasarkan nilai, minat, dan preferensi studi siswa.",
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

            $this->command->info("✅ Siswa {$data['name']} ({$data['email']}) ditambahkan dengan rekomendasi jurusan.");
        }

        $this->command->info("\n✅ Impor data siswa SMK Bima Ambulu selesai!");
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
