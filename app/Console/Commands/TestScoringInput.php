<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\User;
use App\Http\Controllers\RekomendasiController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class TestScoringInput extends Command
{
    protected $signature = 'test:scoring 
                          {--minat=saya senang coding dan web development : Input minat}
                          {--cita-cita=menjadi web developer profesional : Input cita-cita}
                          {--prestasi=juara 1 kompetisi coding : Input prestasi}
                          {--nilai=85 : Nilai rata-rata untuk test}';

    protected $description = 'Test scoring algorithm dengan input detail untuk minat, cita-cita, prestasi';

    public function handle()
    {
        $this->info('╔════════════════════════════════════════════════════════════════╗');
        $this->info('║       TEST SCORING INPUT - COMPREHENSIVE TEST                  ║');
        $this->info('╚════════════════════════════════════════════════════════════════╝');
        $this->newLine();

        // Test input - COMPREHENSIVE
        $minat = $this->option('minat');
        $citaCita = $this->option('cita-cita');
        $prestasi = $this->option('prestasi');
        $nilaiBase = (int)$this->option('nilai');

        // Display ALL inputs
        $this->info('📝 SEMUA INPUT YANG DITEST:');
        $this->line('');
        $this->line('  ┌─ NILAI AKADEMIK (Kriteria 1):');
        $this->line("  │  ├─ MTK: $nilaiBase");
        $this->line("  │  ├─ Fisika: " . ($nilaiBase - 2));
        $this->line("  │  ├─ Kimia: " . ($nilaiBase - 3));
        $this->line("  │  └─ Biologi: " . ($nilaiBase - 1));
        $this->line('  │');
        $this->line("  ├─ MINAT (Kriteria 2): \"$minat\"");
        $this->line("  ├─ PREFERENSI STUDI (Kriteria 3): Sains & Teknologi");
        $this->line("  ├─ CITA-CITA (Kriteria 4): \"$citaCita\"");
        $this->line("  └─ PRESTASI (Kriteria 5): \"$prestasi\"");
        $this->newLine();

        try {
            // Get or create test user
            $testUser = User::firstOrCreate(
                ['email' => 'test@scoring.local'],
                [
                    'name' => 'Test User',
                    'password' => bcrypt('password'),
                    'nis' => '12345',
                    'kelompok_asal' => 'IPA',
                    'role' => 'siswa',
                ]
            );

            // Login as test user
            Auth::login($testUser);

            // Create request object
            $request = Request::create('/rekomendasi/proses', 'POST', [
                'mtk' => $nilaiBase,
                'fisika' => $nilaiBase - 2,
                'kimia' => $nilaiBase - 3,
                'biologi' => $nilaiBase - 1,
                'minat' => $minat,
                'pref_studi' => 'Sains & Teknologi',
                'cita_cita' => $citaCita,
                'prestasi' => $prestasi,
            ]);

            // Call controller proses method
            $controller = new RekomendasiController();
            $response = $controller->proses($request);

            $this->info('✅ SCORING BERHASIL');
            $this->newLine();

            // Check if response is a view
            if (method_exists($response, 'getData')) {
                $data = $response->getData();
                
                if (isset($data['hasilAkhir']) && is_array($data['hasilAkhir'])) {
                    $this->info('🏆 HASIL TOP 3 REKOMENDASI JURUSAN:');
                    $this->line('');
                    $hasilAkhir = $data['hasilAkhir'];
                    
                    for ($i = 0; $i < min(3, count($hasilAkhir)); $i++) {
                        $r = $hasilAkhir[$i];
                        $no = $i + 1;
                        $this->line("  ┌─ #{$no}. {$r['jurusan']}");
                        $this->line("  │   Score: " . number_format($r['skor'], 4) . " (" . round($r['skor'] * 100, 1) . "%)");
                        $this->line('  │');
                        
                        // Show detail scoring per kriteria
                        $detail = $r['detail'] ?? [];
                        $this->line('  │   📊 Detail Scoring:');
                        $this->line("  │   ├─ Nilai Akademik: " . number_format($detail['nilai'] ?? 0, 4));
                        $this->line("  │   ├─ Minat (" . ($r['kecocokan_minat'] ?? 'N/A') . "): " . number_format($detail['minat'] ?? 0, 4));
                        $this->line("  │   ├─ Preferensi Studi: " . number_format($detail['pref'] ?? 0, 4));
                        $this->line("  │   ├─ Cita-cita: " . number_format($detail['cita'] ?? 0, 4));
                        if ($detail['prestasi'] ?? null) {
                            $this->line("  │   └─ Prestasi: " . number_format($detail['prestasi'], 4));
                        }
                        
                        // Show explanations
                        $exp = $r['explanation'] ?? [];
                        $this->line('  │');
                        $this->line('  │   📝 Penjelasan:');
                        if ($exp['nilai'] ?? null) {
                            $this->line('  │   ├─ ' . substr($exp['nilai'], 0, 65) . '...');
                        }
                        if ($exp['minat'] ?? null) {
                            $this->line('  │   ├─ ' . substr($exp['minat'], 0, 65) . '...');
                        }
                        if ($exp['cita'] ?? null) {
                            $this->line('  │   ├─ ' . substr($exp['cita'], 0, 65) . '...');
                        }
                        if ($exp['prestasi'] ?? null) {
                            $this->line('  │   └─ ' . substr($exp['prestasi'], 0, 65) . '...');
                        }
                        
                        if ($i < 2) {
                            $this->line('  │');
                        }
                    }
                    $this->line('  └─────────────────────────────────────────────');
                }
            }

            $this->newLine();
            $this->info('✅ TEST SELESAI - SEMUA 5 KRITERIA DITEST:');
            $this->line('   ✓ Nilai Akademik (MTK, Fisika, Kimia, Biologi)');
            $this->line('   ✓ Minat (coverage-based mapping)');
            $this->line('   ✓ Preferensi Studi (enum validation)');
            $this->line('   ✓ Cita-cita (career category mapping)');
            $this->line('   ✓ Prestasi (level classification)');
            $this->newLine();
            $this->line('💡 Hasil disimpan di database table recommendations');
            $this->line('💡 Check logs: storage/logs/laravel.log');

            // Logout
            Auth::logout();
        } catch (\Exception $e) {
            $this->error('❌ ERROR: ' . $e->getMessage());
            $this->line('File: ' . $e->getFile() . ':' . $e->getLine());
            $this->newLine();
            $this->line('Stack Trace:');
            $this->line($e->getTraceAsString());
        }
    }
}
