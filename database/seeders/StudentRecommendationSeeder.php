<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\Recommendation;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class StudentRecommendationSeeder extends Seeder
{
    /**
     * Run the database seeds.
     * Data siswa dan rekomendasi dengan logika yang akurat
     */
    public function run(): void
    {
        // Majors data mapping untuk rekomendasi
        $majorsWeights = [
            'teknologi_informasi' => ['mtk' => 0.30, 'fisika' => 0.25, 'kimia' => 0.15, 'biologi' => 0.05],
            'teknik' => ['mtk' => 0.25, 'fisika' => 0.35, 'kimia' => 0.20, 'biologi' => 0.05],
            'produksi_pertanian' => ['mtk' => 0.15, 'biologi' => 0.35, 'kimia' => 0.25, 'fisika' => 0.10],
            'peternakan' => ['biologi' => 0.40, 'kimia' => 0.20, 'mtk' => 0.15, 'fisika' => 0.05],
            'manajemen_agribisnis' => ['ekonomi' => 0.35, 'geografi' => 0.25, 'mtk' => 0.20, 'sosiologi' => 0.15],
            'akuntansi' => ['ekonomi' => 0.40, 'mtk' => 0.25, 'sejarah' => 0.15, 'sosiologi' => 0.10],
            'bahasa_komunikasi' => ['sosiologi' => 0.30, 'sejarah' => 0.25, 'ekonomi' => 0.20, 'geografi' => 0.15],
        ];

        $studentData = [
            // IPA Students
            [
                'name' => 'Adi Pratama',
                'nis' => '001',
                'kelompok_asal' => 'IPA',
                'values' => ['mtk' => 92, 'fisika' => 88, 'kimia' => 85, 'biologi' => 78],
                'minat' => 'Logika Komputer',
                'preferensi_studi' => 'Sains & Teknologi',
                'cita_cita' => 'Software Engineer',
                'prestasi' => 'Juara 2 Olimpiade Informatika',
                'expected_major' => 'Teknologi Informasi',
            ],
            [
                'name' => 'Bella Maharani',
                'nis' => '002',
                'kelompok_asal' => 'IPA',
                'values' => ['mtk' => 85, 'fisika' => 90, 'kimia' => 88, 'biologi' => 80],
                'minat' => 'Mesin & Otomasi',
                'preferensi_studi' => 'Sains & Teknologi',
                'cita_cita' => 'Insinyur Mesin',
                'prestasi' => 'Juara Lomba Robotika Regional',
                'expected_major' => 'Teknik',
            ],
            [
                'name' => 'Citra Dewi',
                'nis' => '003',
                'kelompok_asal' => 'IPA',
                'values' => ['mtk' => 78, 'fisika' => 75, 'kimia' => 88, 'biologi' => 92],
                'minat' => 'Alam Tanaman',
                'preferensi_studi' => 'Pertanian & Lingkungan',
                'cita_cita' => 'Agronomis',
                'prestasi' => 'Juara Pameran Tanaman Hidroponik',
                'expected_major' => 'Produksi Pertanian',
            ],
            [
                'name' => 'Doni Kusuma',
                'nis' => '004',
                'kelompok_asal' => 'IPA',
                'values' => ['mtk' => 82, 'fisika' => 80, 'kimia' => 92, 'biologi' => 88],
                'minat' => 'Kimia & Biologi',
                'preferensi_studi' => 'Kesehatan & Ilmu Hayat',
                'cita_cita' => 'Peneliti Biologi',
                'prestasi' => 'Penulis Jurnal Ilmiah Tingkat Sekolah',
                'expected_major' => 'Peternakan',
            ],

            // IPS Students
            [
                'name' => 'Eka Prasetyo',
                'nis' => '005',
                'kelompok_asal' => 'IPS',
                'values' => ['ekonomi' => 90, 'geografi' => 87, 'sosiologi' => 85, 'sejarah' => 80],
                'minat' => 'Bisnis',
                'preferensi_studi' => 'Bisnis & Manajemen',
                'cita_cita' => 'Entrepreneur Muda',
                'prestasi' => 'Juara Kompetisi Bisnis Plan Nasional',
                'expected_major' => 'Manajemen Agribisnis',
            ],
            [
                'name' => 'Fitri Handayani',
                'nis' => '006',
                'kelompok_asal' => 'IPS',
                'values' => ['ekonomi' => 88, 'mtk' => 85, 'sejarah' => 82, 'sosiologi' => 80],
                'minat' => 'Akuntansi',
                'preferensi_studi' => 'Bisnis & Manajemen',
                'cita_cita' => 'Akuntan Profesional',
                'prestasi' => 'Finalis Kompetisi Akuntansi Wilayah',
                'expected_major' => 'Akuntansi',
            ],
            [
                'name' => 'Gita Salsabila',
                'nis' => '007',
                'kelompok_asal' => 'IPS',
                'values' => ['sosiologi' => 92, 'sejarah' => 88, 'ekonomi' => 80, 'geografi' => 85],
                'minat' => 'Komunikasi Sosial',
                'preferensi_studi' => 'Seni & Komunikasi',
                'cita_cita' => 'Presenter Berita',
                'prestasi' => 'Juara Debat Tingkat Propinsi',
                'expected_major' => 'Bahasa Komunikasi',
            ],
            [
                'name' => 'Hendra Wijaya',
                'nis' => '008',
                'kelompok_asal' => 'IPS',
                'values' => ['ekonomi' => 85, 'geografi' => 88, 'mtk' => 80, 'sosiologi' => 82],
                'minat' => 'Bisnis',
                'preferensi_studi' => 'Bisnis & Manajemen',
                'cita_cita' => 'Manajer Bisnis',
                'prestasi' => 'Pengusaha Kuliner Muda',
                'expected_major' => 'Manajemen Agribisnis',
            ],

            // More diverse students
            [
                'name' => 'Ibu Musim',
                'nis' => '009',
                'kelompok_asal' => 'IPA',
                'values' => ['mtk' => 88, 'fisika' => 85, 'kimia' => 86, 'biologi' => 84],
                'minat' => 'Teknologi & Inovasi',
                'preferensi_studi' => 'Sains & Teknologi',
                'cita_cita' => 'Product Manager Tech',
                'prestasi' => 'Top 10 Innovation Challenge',
                'expected_major' => 'Teknologi Informasi',
            ],
            [
                'name' => 'Joko Santoso',
                'nis' => '010',
                'kelompok_asal' => 'IPA',
                'values' => ['fisika' => 92, 'mtk' => 90, 'kimia' => 84, 'biologi' => 76],
                'minat' => 'Listrik & Energi',
                'preferensi_studi' => 'Sains & Teknologi',
                'cita_cita' => 'Insinyur Elektro',
                'prestasi' => 'Juara Kompetisi Energi Terbarukan',
                'expected_major' => 'Teknik',
            ],
        ];

        foreach ($studentData as $data) {
            // Create student user
            $user = User::create([
                'name' => $data['name'],
                'email' => strtolower(str_replace(' ', '.', $data['name'])) . '@student.polije.ac.id',
                'password' => Hash::make('password123'),
                'role' => 'siswa',
                'nis' => $data['nis'],
                'kelompok_asal' => $data['kelompok_asal'],
                'foto' => null,
            ]);

            // Create recommendation with logical scoring
            $rekomendasiHasil = $this->generateRecommendationResult(
                $data['values'],
                $data['preferensi_studi'],
                $data['expected_major'],
                $majorsWeights
            );

            $recommendation = Recommendation::create([
                'user_id' => $user->id,
                'mtk' => $data['values']['mtk'] ?? null,
                'fisika' => $data['values']['fisika'] ?? null,
                'kimia' => $data['values']['kimia'] ?? null,
                'biologi' => $data['values']['biologi'] ?? null,
                'ekonomi' => $data['values']['ekonomi'] ?? null,
                'geografi' => $data['values']['geografi'] ?? null,
                'sosiologi' => $data['values']['sosiologi'] ?? null,
                'sejarah' => $data['values']['sejarah'] ?? null,
                'minat' => $data['minat'],
                'preferensi_studi' => $data['preferensi_studi'],
                'cita_cita' => $data['cita_cita'],
                'prestasi' => $data['prestasi'],
                'hasil_rekomendasi' => $rekomendasiHasil,
            ]);

            // Create sample chat history
            $sessionId = Str::uuid()->toString();
            $this->createSampleChatHistory($user->id, $recommendation->id, $sessionId, $data);
        }

        $this->command->info('Student dan Recommendation data berhasil diisi dengan ' . count($studentData) . ' siswa!');
    }

    /**
     * Generate logical recommendation result based on values
     */
    private function generateRecommendationResult($values, $preferensi, $expectedMajor, $majorsWeights)
    {
        $majors = [
            [
                'jurusan' => 'Teknologi Informasi',
                'skor' => $this->calculateScore($values, ['mtk' => 0.30, 'fisika' => 0.25, 'kimia' => 0.15, 'biologi' => 0.05]),
                'detail' => 'Cocok untuk minat teknologi dan programming.'
            ],
            [
                'jurusan' => 'Teknik',
                'skor' => $this->calculateScore($values, ['mtk' => 0.25, 'fisika' => 0.35, 'kimia' => 0.20, 'biologi' => 0.05]),
                'detail' => 'Sesuai dengan kemampuan fisika dan matematika tinggi.'
            ],
            [
                'jurusan' => 'Produksi Pertanian',
                'skor' => $this->calculateScore($values, ['mtk' => 0.15, 'biologi' => 0.35, 'kimia' => 0.25, 'fisika' => 0.10]),
                'detail' => 'Cocok untuk minat di bidang pertanian dan lingkungan.'
            ],
            [
                'jurusan' => 'Peternakan',
                'skor' => $this->calculateScore($values, ['biologi' => 0.40, 'kimia' => 0.20, 'mtk' => 0.15, 'fisika' => 0.05]),
                'detail' => 'Bidang peternakan dan manajemen hewan ternak.'
            ],
            [
                'jurusan' => 'Manajemen Agribisnis',
                'skor' => $this->calculateScore($values, ['ekonomi' => 0.35, 'geografi' => 0.25, 'mtk' => 0.20, 'sosiologi' => 0.15]),
                'detail' => 'Kombinasi bisnis dan pertanian yang sempurna.'
            ],
            [
                'jurusan' => 'Akuntansi',
                'skor' => $this->calculateScore($values, ['ekonomi' => 0.40, 'mtk' => 0.25, 'sejarah' => 0.15, 'sosiologi' => 0.10]),
                'detail' => 'Untuk yang tertarik dengan keuangan dan akuntansi.'
            ],
            [
                'jurusan' => 'Bahasa Komunikasi',
                'skor' => $this->calculateScore($values, ['sosiologi' => 0.30, 'sejarah' => 0.25, 'ekonomi' => 0.20, 'geografi' => 0.15]),
                'detail' => 'Bidang komunikasi dan seni untuk yang ekspresif.'
            ],
        ];

        // Sort by score descending
        usort($majors, fn($a, $b) => $b['skor'] <=> $a['skor']);

        return array_slice($majors, 0, 3);
    }

    /**
     * Calculate score based on weighted values
     */
    private function calculateScore($values, $weights)
    {
        $score = 0;
        $totalWeight = 0;

        foreach ($weights as $subject => $weight) {
            if (isset($values[$subject])) {
                $score += ($values[$subject] / 100) * $weight;
                $totalWeight += $weight;
            }
        }

        return $totalWeight > 0 ? round(($score / $totalWeight) * 100, 1) : 0;
    }

    /**
     * Create sample chat history for a student
     */
    private function createSampleChatHistory($userId, $recommendationId, $sessionId, $studentData)
    {
        $chatSamples = [
            [
                'pertanyaan' => 'Jurusan apa yang cocok untuk saya?',
                'jawaban' => 'Berdasarkan nilai dan minat Anda, ' . $studentData['expected_major'] . ' adalah pilihan terbaik. Dengan prestasi di bidang ' . strtolower($studentData['prestasi']) . ', Anda memiliki potensi besar untuk sukses.'
            ],
            [
                'pertanyaan' => 'Mengapa jurusan tersebut cocok untuk saya?',
                'jawaban' => 'Karena nilai ' . implode(', ', array_keys($studentData['values'])) . ' Anda menunjukkan keunggulan di area yang relevan dengan jurusan tersebut.'
            ],
        ];

        foreach ($chatSamples as $index => $chat) {
            \App\Models\ChatHistory::create([
                'user_id' => $userId,
                'id_sesi' => $sessionId,
                'id_rekomendasi' => $recommendationId,
                'pertanyaan' => $chat['pertanyaan'],
                'jawaban' => $chat['jawaban'],
            ]);
        }
    }
}
