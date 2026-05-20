<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\Recommendation;
use App\Models\ChatHistory;
use App\Models\PolijeMajor;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class Generate30StudentsSeeder extends Seeder
{
    /**
     * Generate 30 students dengan data, recommendations, dan chat history
     * Run: php artisan db:seed --class=Generate30StudentsSeeder
     */
    
    public function run(): void
    {
        $studentsData = [
            // KELOMPOK IPA - 15 siswa
            ['name' => 'Akmal Fiqri', 'email' => 'akmal.fiqri@school.id', 'kelompok' => 'IPA', 'mtk' => 85, 'fisika' => 82, 'kimia' => 75, 'biologi' => 70, 'minat' => 'Teknologi & Programming'],
            ['name' => 'Sasha Putri Aulia', 'email' => 'sasha.putri@school.id', 'kelompok' => 'IPA', 'mtk' => 78, 'fisika' => 80, 'kimia' => 85, 'biologi' => 82, 'minat' => 'Kesehatan & Biologi'],
            ['name' => 'Budi Kusuma', 'email' => 'budi.kusuma@school.id', 'kelompok' => 'IPA', 'mtk' => 82, 'fisika' => 88, 'kimia' => 76, 'biologi' => 68, 'minat' => 'Teknik & Mesin'],
            ['name' => 'Dina Hartini', 'email' => 'dina.hartini@school.id', 'kelompok' => 'IPA', 'mtk' => 75, 'fisika' => 72, 'kimia' => 88, 'biologi' => 90, 'minat' => 'Farmasi & Kimia'],
            ['name' => 'Eka Prasetyo', 'email' => 'eka.prasetyo@school.id', 'kelompok' => 'IPA', 'mtk' => 88, 'fisika' => 85, 'kimia' => 72, 'biologi' => 75, 'minat' => 'Pemrograman & Coding'],
            ['name' => 'Fitra Handoko', 'email' => 'fitra.handoko@school.id', 'kelompok' => 'IPA', 'mtk' => 92, 'fisika' => 90, 'kimia' => 88, 'biologi' => 80, 'minat' => 'Sains & Penelitian'],
            ['name' => 'Gita Pramesti', 'email' => 'gita.pramesti@school.id', 'kelompok' => 'IPA', 'mtk' => 80, 'fisika' => 78, 'kimia' => 82, 'biologi' => 85, 'minat' => 'Bioteknologi'],
            ['name' => 'Hendra Wijaya', 'email' => 'hendra.wijaya@school.id', 'kelompok' => 'IPA', 'mtk' => 86, 'fisika' => 84, 'kimia' => 80, 'biologi' => 72, 'minat' => 'Fisika & Teknologi'],
            ['name' => 'Intan Sari', 'email' => 'intan.sari@school.id', 'kelompok' => 'IPA', 'mtk' => 79, 'fisika' => 81, 'kimia' => 83, 'biologi' => 88, 'minat' => 'Kesehatan'],
            ['name' => 'Jaka Surya', 'email' => 'jaka.surya@school.id', 'kelompok' => 'IPA', 'mtk' => 83, 'fisika' => 86, 'kimia' => 78, 'biologi' => 70, 'minat' => 'Teknik Elektro'],
            ['name' => 'Kurniawan Adi', 'email' => 'kurniawan.adi@school.id', 'kelompok' => 'IPA', 'mtk' => 87, 'fisika' => 88, 'kimia' => 85, 'biologi' => 76, 'minat' => 'Teknik Sipil'],
            ['name' => 'Latifa Zahra', 'email' => 'latifa.zahra@school.id', 'kelompok' => 'IPA', 'mtk' => 81, 'fisika' => 80, 'kimia' => 87, 'biologi' => 84, 'minat' => 'Kedokteran'],
            ['name' => 'Maulana Rizki', 'email' => 'maulana.rizki@school.id', 'kelompok' => 'IPA', 'mtk' => 84, 'fisika' => 83, 'kimia' => 81, 'biologi' => 73, 'minat' => 'Teknologi Informasi'],
            ['name' => 'Nisa Rahmawati', 'email' => 'nisa.rahmawati@school.id', 'kelompok' => 'IPA', 'mtk' => 77, 'fisika' => 79, 'kimia' => 84, 'biologi' => 89, 'minat' => 'Perawatan Kesehatan'],
            ['name' => 'Oki Pratama', 'email' => 'oki.pratama@school.id', 'kelompok' => 'IPA', 'mtk' => 89, 'fisika' => 87, 'kimia' => 84, 'biologi' => 71, 'minat' => 'Energi Terbarukan'],

            // KELOMPOK IPS - 15 siswa
            ['name' => 'Fahmi Rizki', 'email' => 'fahmi.rizki@school.id', 'kelompok' => 'IPS', 'ekonomi' => 88, 'geografi' => 80, 'sosiologi' => 78, 'sejarah' => 75, 'minat' => 'Bisnis & Keuangan'],
            ['name' => 'Gina Melani', 'email' => 'gina.melani@school.id', 'kelompok' => 'IPS', 'ekonomi' => 85, 'geografi' => 88, 'sosiologi' => 82, 'sejarah' => 80, 'minat' => 'Pariwisata & Budaya'],
            ['name' => 'Hasan Wijaya', 'email' => 'hasan.wijaya@school.id', 'kelompok' => 'IPS', 'ekonomi' => 82, 'geografi' => 85, 'sosiologi' => 80, 'sejarah' => 78, 'minat' => 'Manajemen & Administrasi'],
            ['name' => 'Irma Santika', 'email' => 'irma.santika@school.id', 'kelompok' => 'IPS', 'ekonomi' => 75, 'geografi' => 92, 'sosiologi' => 85, 'sejarah' => 88, 'minat' => 'Budaya & Komunikasi'],
            ['name' => 'Joko Supriyanto', 'email' => 'joko.supriyanto@school.id', 'kelompok' => 'IPS', 'ekonomi' => 80, 'geografi' => 75, 'sosiologi' => 88, 'sejarah' => 82, 'minat' => 'Sosial & Masyarakat'],
            ['name' => 'Kartika Dewi', 'email' => 'kartika.dewi@school.id', 'kelompok' => 'IPS', 'ekonomi' => 87, 'geografi' => 84, 'sosiologi' => 80, 'sejarah' => 82, 'minat' => 'Akuntansi & Keuangan'],
            ['name' => 'Lucia Pratiwi', 'email' => 'lucia.pratiwi@school.id', 'kelompok' => 'IPS', 'ekonomi' => 83, 'geografi' => 86, 'sosiologi' => 84, 'sejarah' => 85, 'minat' => 'Ilmu Komunikasi'],
            ['name' => 'Mahmud Ali', 'email' => 'mahmud.ali@school.id', 'kelompok' => 'IPS', 'ekonomi' => 81, 'geografi' => 78, 'sosiologi' => 85, 'sejarah' => 80, 'minat' => 'Hukum & Keadilan'],
            ['name' => 'Nadya Salsabila', 'email' => 'nadya.salsabila@school.id', 'kelompok' => 'IPS', 'ekonomi' => 84, 'geografi' => 88, 'sosiologi' => 81, 'sejarah' => 79, 'minat' => 'Perhotelan'],
            ['name' => 'Oman Sutrisno', 'email' => 'oman.sutrisno@school.id', 'kelompok' => 'IPS', 'ekonomi' => 86, 'geografi' => 82, 'sosiologi' => 79, 'sejarah' => 81, 'minat' => 'Bisnis Internasional'],
            ['name' => 'Putri Handini', 'email' => 'putri.handini@school.id', 'kelompok' => 'IPS', 'ekonomi' => 80, 'geografi' => 87, 'sosiologi' => 83, 'sejarah' => 84, 'minat' => 'Pendidikan & Pengajaran'],
            ['name' => 'Qohaf Ramadhan', 'email' => 'qohaf.ramadhan@school.id', 'kelompok' => 'IPS', 'ekonomi' => 82, 'geografi' => 79, 'sosiologi' => 86, 'sejarah' => 83, 'minat' => 'Pemerintahan'],
            ['name' => 'Rita Kusuma', 'email' => 'rita.kusuma@school.id', 'kelompok' => 'IPS', 'ekonomi' => 85, 'geografi' => 86, 'sosiologi' => 82, 'sejarah' => 81, 'minat' => 'Diplomasi & Hubungan Internasional'],
            ['name' => 'Satrio Budi', 'email' => 'satrio.budi@school.id', 'kelompok' => 'IPS', 'ekonomi' => 79, 'geografi' => 83, 'sosiologi' => 88, 'sejarah' => 85, 'minat' => 'Jurnalisme'],
            ['name' => 'Tyas Merika', 'email' => 'tyas.merika@school.id', 'kelompok' => 'IPS', 'ekonomi' => 88, 'geografi' => 85, 'sosiologi' => 80, 'sejarah' => 78, 'minat' => 'Asuransi & Perbankan'],
        ];

        $chatMessages = [
            'Halo, apa yang harus saya lakukan untuk memilih jurusan yang tepat?',
            'Saya bingung antara dua pilihan jurusan yang berbeda.',
            'Bagaimana prospek kerja dari jurusan yang Anda rekomendasikan?',
            'Apakah ada beasiswa untuk jurusan ini?',
            'Apa saja persyaratan untuk masuk ke jurusan tersebut?',
            'Berapa lama program studi ini?',
            'Apa saja mata kuliah yang akan saya pelajari?',
            'Saya tertarik dengan teknologi, jurusan apa yang cocok?',
            'Bagaimana dengan kesempatan magang?',
            'Saya ingin bekerja di luar negeri, jurusan apa yang sesuai?',
        ];

        $majoritasNames = PolijeMajor::pluck('nama_jurusan')->toArray();

        if (empty($majoritasNames)) {
            $this->command->error('❌ Tidak ada jurusan di database. Jalankan seeder jurusan terlebih dahulu!');
            return;
        }

        foreach ($studentsData as $index => $data) {
            // Cek email unik
            if (User::where('email', $data['email'])->exists()) {
                $this->command->warn("⚠️  Email {$data['email']} sudah ada, skip...");
                continue;
            }

            // Create Student User
            $user = User::create([
                'name' => $data['name'],
                'email' => $data['email'],
                'password' => bcrypt('student123'),
                'role' => 'siswa',
                'nis' => '20240' . str_pad($index + 1, 3, '0', STR_PAD_LEFT),
                'kelompok_asal' => $data['kelompok'],
                'email_verified_at' => now(),
            ]);

            $this->command->line("✅ Created student: {$data['name']} ({$data['email']})");

            // Create Recommendation
            if ($data['kelompok'] === 'IPA') {
                $matematika = $data['mtk'];
                $fisika = $data['fisika'];
                $kimia = $data['kimia'];
                $biologi = $data['biologi'];
            } else {
                $ekonomi = $data['ekonomi'];
                $geografi = $data['geografi'];
                $sosiologi = $data['sosiologi'];
                $sejarah = $data['sejarah'];
            }

            $hasRecommendation = Recommendation::where('user_id', $user->id)->exists();
            
            if (!$hasRecommendation) {
                // Simulate recommendation algorithm scores
                $scores = array_fill(0, count($majoritasNames), 0);
                
                // Random weighted scoring
                foreach ($scores as $key => $score) {
                    $scores[$key] = rand(55, 95);
                }

                // Top 9 recommendations
                arsort($scores);
                $topScores = array_slice($scores, 0, 9, true);

                $hasilRekomendasi = [];
                $rank = 1;
                foreach ($topScores as $majorIndex => $score) {
                    $hasilRekomendasi[] = [
                        'ranking' => $rank,
                        'jurusan' => $majoritasNames[$majorIndex] ?? 'Teknologi Informasi',
                        'skor' => round($score),
                    ];
                    $rank++;
                }

                $recommendation = Recommendation::create([
                    'user_id' => $user->id,
                    'mtk' => $data['mtk'] ?? null,
                    'fisika' => $data['fisika'] ?? null,
                    'kimia' => $data['kimia'] ?? null,
                    'biologi' => $data['biologi'] ?? null,
                    'ekonomi' => $data['ekonomi'] ?? null,
                    'geografi' => $data['geografi'] ?? null,
                    'sosiologi' => $data['sosiologi'] ?? null,
                    'sejarah' => $data['sejarah'] ?? null,
                    'minat' => $data['minat'] ?? null,
                    'preferensi_studi' => $data['pref_studi'] ?? ($data['kelompok'] === 'IPA' ? 'Sains & Teknologi' : 'Sosial & Humaniora'),
                    'cita_cita' => $data['cita_cita'] ?? ($data['minat'] ?? null),
                    'prestasi' => $data['prestasi'] ?? 'Aktif',
                    'hasil_rekomendasi' => json_encode($hasilRekomendasi),
                ]);

                $this->command->info("   📊 Recommendation created (id: {$recommendation->id})");
            }

            // Create Chat Histories
            $existingChats = ChatHistory::where('user_id', $user->id)->count();
            
            if ($existingChats === 0) {
                for ($i = 0; $i < rand(2, 5); $i++) {
                    $randomMessage = $chatMessages[array_rand($chatMessages)];

                    ChatHistory::create([
                        'user_id' => $user->id,
                        'id_sesi' => null,
                        'id_rekomendasi' => $recommendation->id ?? null,
                        'pertanyaan' => $randomMessage,
                        'jawaban' => 'Terima kasih atas pertanyaannya. Berdasarkan profil akademik dan minat Anda, saya merekomendasikan jurusan yang sesuai dengan kemampuan dan tujuan karir Anda.',
                    ]);
                }

                $this->command->info("   💬 Chat histories created (2-5 chats)");
            }
        }

        $this->command->info("\n✨ Seeder selesai! 30 students + recommendations + chat histories berhasil dibuat.\n");
    }
}
