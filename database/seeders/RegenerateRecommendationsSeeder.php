<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\Recommendation;
use App\Models\PolijeMajor;
use Illuminate\Database\Seeder;

class RegenerateRecommendationsSeeder extends Seeder
{
    public function run(): void
    {
        // Cari siswa yang belum punya rekomendasi atau rekomendasi mereka kosong
        $studentsWithoutRecs = User::where('role', 'siswa')
            ->whereNotIn('id', Recommendation::pluck('user_id')->toArray())
            ->get();

        $majorNames = PolijeMajor::pluck('nama_jurusan')->toArray();
        $count = 0;

        foreach ($studentsWithoutRecs as $student) {
            // Jika siswa belum punya rekomendasi sama sekali, generate random recommendation
            if (!$student->hasRecommendation) {
                $scores = [];
                
                // Generate random scores untuk setiap jurusan
                foreach ($majorNames as $majorName) {
                    $major = PolijeMajor::where('nama_jurusan', $majorName)->first();
                    if ($major) {
                        $bobot = $major->bobot_mapel;
                        $score = 0;
                        
                        // Random scoring (untuk demo)
                        foreach ($bobot as $subject => $weight) {
                            $randomValue = rand(70, 95);
                            $score += ($randomValue * $weight) / 100;
                        }
                        
                        // Random bonus untuk preferensi
                        if (rand(0, 1) == 1) {
                            $score += 5;
                        }
                        
                        $scores[$majorName] = $score;
                    }
                }

                arsort($scores);
                $topRecommendations = array_slice($scores, 0, 3, true);

                $recommendations = [];
                foreach ($topRecommendations as $majorName => $score) {
                    $recommendations[] = [
                        'jurusan' => $majorName,
                        'skor' => round($score, 2),
                        'detail' => "Rekomendasi berdasarkan nilai dan minat siswa.",
                    ];
                }

                Recommendation::create([
                    'user_id' => $student->id,
                    'mtk' => rand(60, 95),
                    'fisika' => rand(60, 95),
                    'kimia' => rand(60, 95),
                    'biologi' => rand(60, 95),
                    'ekonomi' => rand(60, 95),
                    'geografi' => rand(60, 95),
                    'sosiologi' => rand(60, 95),
                    'sejarah' => rand(60, 95),
                    'minat' => 'Umum',
                    'preferensi_studi' => 'Sains & Teknologi',
                    'cita_cita' => 'Profesional',
                    'prestasi' => 'Aktif',
                    'hasil_rekomendasi' => $recommendations,  // Pass array langsung, bukan json_encode
                ]);

                $count++;
            }
        }

        $this->command->info("✅ Regenerasi rekomendasi untuk {$count} siswa selesai!");
    }
}
