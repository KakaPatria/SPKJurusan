<?php

namespace App\Http\Controllers;

use App\Models\Student;
use App\Models\PolijeMajor;
use App\Models\Recommendation;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class RekomendasiController extends Controller
{
    public function index()
    {
        // Ambil data siswa dari akun (kolom `nis`, `kelompok_asal` di tabel `users`)
        $user = Auth::user();
        // Jika masih ada model Student di beberapa kode lama, abaikan; gunakan properti di User
        $student = null;
        if ($user) {
            $student = (object) [
                'user_id' => $user->id,
                'nis' => $user->nis ?? null,
                'kelompok_asal' => $user->kelompok_asal ?? null,
                'foto' => $user->foto ?? null,
            ];
        }

        return view('rekomendasi.input', compact('student'));
    }

    /**
     * Generate textual explanation untuk setiap kriteria
     * Menjelaskan "mengapa jurusan ini cocok" berdasarkan scoring detail
     */
    private function generateExplanation($jurusanNama, $detail, $katNilai, $kategoriMinat, $prefStudi, $prestasi)
    {
        $explanations = [];

        // 1. Penjelasan Nilai Akademik
        $skorNilai = $detail['nilai'] ?? 0;
        if ($skorNilai >= 0.8) {
            $explanations['nilai'] = "✅ Nilai akademik Anda ($katNilai) sangat sesuai dengan jalur pendidikan yang dibutuhkan jurusan ini.";
        } elseif ($skorNilai >= 0.6) {
            $explanations['nilai'] = "✓ Nilai akademik Anda ($katNilai) cukup sesuai dengan persyaratan jurusan ini.";
        } else {
            $explanations['nilai'] = "⚠️ Nilai akademik Anda ($katNilai) masih perlu ditingkatkan untuk optimal di jurusan ini, namun tetap relevan.";
        }

        // 2. Penjelasan Minat
        $skorMinat = $detail['minat'] ?? 0;
        if ($skorMinat >= 0.8) {
            $explanations['minat'] = "✅ Minat Anda sangat sesuai dan cocok dengan fokus kurikulum $jurusanNama.";
        } elseif ($skorMinat >= 0.6) {
            $explanations['minat'] = "✓ Minat Anda cukup relevan dan sesuai dengan area pembelajaran di $jurusanNama.";
        } else {
            $explanations['minat'] = "ℹ️ Minat Anda memiliki kesamaan dan relevansi dengan aspek-aspek tertentu di $jurusanNama.";
        }

        // 3. Penjelasan Preferensi Studi
        $skorPref = $detail['pref'] ?? 0;
        if ($skorPref >= 0.8) {
            $explanations['pref'] = "✅ Metode pembelajaran \"$prefStudi\" yang Anda pilih sangat sesuai dengan pendekatan pembelajaran $jurusanNama.";
        } elseif ($skorPref >= 0.6) {
            $explanations['pref'] = "✓ Preferensi studi \"$prefStudi\" Anda cocok dengan sistem pembelajaran yang diterapkan.";
        } else {
            $explanations['pref'] = "ℹ️ Jurusan ini menawarkan elemen pembelajaran \"$prefStudi\" yang relevan dengan preferensi Anda.";
        }

        // 4. Penjelasan Cita-cita
        $skorCita = $detail['cita'] ?? 0;
        if ($skorCita >= 0.8) {
            $explanations['cita'] = "✅ Cita-cita karir Anda sangat sesuai dan aligned dengan standar lulusan bidang ini.";
        } elseif ($skorCita >= 0.6) {
            $explanations['cita'] = "✓ Cita-cita Anda memiliki potensi besar untuk dicapai melalui jurusan ini.";
        } else {
            $explanations['cita'] = "ℹ️ Jurusan ini membuka jalur karir yang sesuai dengan cita-cita dan aspirasi Anda.";
        }

        // 5. Penjelasan Prestasi
        $skorPrestasi = $detail['prestasi'] ?? 0;
        if ($skorPrestasi >= 0.7) {
            $explanations['prestasi'] = "✅ Prestasi Anda mencerminkan potensi kuat untuk sukses dan berkembang di jurusan ini.";
        } elseif ($skorPrestasi >= 0.4) {
            $explanations['prestasi'] = "✓ Prestasi Anda menunjukkan kemampuan dasar yang memadai dan relevan.";
        } else {
            $explanations['prestasi'] = "ℹ️ Prestasi tidak menjadi hambatan untuk mengembangkan diri dan berkembang di jurusan ini.";
        }

        return $explanations;
    }

    public function proses(Request $request)
    {
        // --- 1. PREPROCESSING NILAI (Kriteria 1: Akademik) ---
        $scores = $request->only(['mtk', 'fisika', 'kimia', 'biologi', 'ekonomi', 'geografi', 'sosiologi', 'sejarah']);
        $validScores = array_filter($scores);
        $average = count($validScores) > 0 ? array_sum($validScores) / count($validScores) : 0;

        // Kategorisasi Nilai berdasarkan config
        $nilaiCategories = config('polije.nilai_category', []);
        $katNilai = 'Rendah';
        foreach ($nilaiCategories as $category => $range) {
            if ($average >= $range['min'] && $average <= $range['max']) {
                $katNilai = $category;
                break;
            }
        }

        // --- 2. ANALISIS MINAT (Kriteria 2) ---
        $minatRaw = strtolower($request->minat ?? '');
        $minatMapped = $this->mapMinat($minatRaw);

        // --- 3. ANALISIS CITA-CITA (Kriteria 3) ---
        $citaRaw = strtolower($request->cita_cita ?? '');
        $citaMapped = $this->mapCitaCita($citaRaw);

        // --- 4. PEMETAAN PREFERENSI STUDI (Kriteria 4) ---
        $prefStudi = $request->pref_studi ?? 'Blended';
        $prefMapping = config('polije.pref_mapping', []);

        // --- 5. ANALISIS PRESTASI (Kriteria 5) ---
        $prestasiRaw = strtolower($request->prestasi ?? '');
        $prestasiScore = $this->scorePrestasiScore($prestasiRaw);

        // --- 6. PERHITUNGAN NAIVE BAYES BERBOBOT ---
        $cfg = config('polije.criteria', []);
        $logPosteriors = [];
        $detailPerJurusan = [];
        $epsilon = 1e-9;

        foreach ($cfg as $jurusan => $c) {
            // Prior: uniform
            $prior = 1 / count($cfg);
            $logPrior = log(max($prior, $epsilon));

            // Weights dan match probabilities
            $weights = $c['weights'] ?? ['nilai' => 0.40, 'minat' => 0.35, 'pref' => 0.15, 'prestasi' => 0.05, 'cita_cita' => 0.05];
            $matchProb = $c['match_prob'] ?? ['nilai' => 0.80, 'minat' => 0.90, 'pref' => 0.85, 'prestasi' => 0.65, 'cita_cita' => 0.85];

            // 1. Likelihood untuk Nilai
            $p_nilai = ($katNilai == ($c['nilai'] ?? 'Sedang')) ? $matchProb['nilai'] : max(1 - $matchProb['nilai'], $epsilon);

            // 2. Likelihood untuk Minat
            $p_minat = ($minatMapped == ($c['minat'] ?? 'Umum')) ? $matchProb['minat'] : max(1 - $matchProb['minat'], $epsilon);

            // 3. Likelihood untuk Preferensi Studi
            $prefList = $c['pref'] ?? ['Praktik Langsung', 'DuDi', 'Project Based'];
            if (!is_array($prefList)) {
                $prefList = [$prefList];
            }
            $p_pref = in_array($prefStudi, $prefList) ? $matchProb['pref'] : max(1 - $matchProb['pref'], $epsilon);

            // 4. Likelihood untuk Cita-cita
            $citaCitaKeywords = $c['cita_cita_keywords'] ?? [];
            $matchCitaCita = false;
            if (!empty($citaCitaKeywords)) {
                foreach ($citaCitaKeywords as $keyword) {
                    if (stripos($citaMapped, $keyword) !== false) {
                        $matchCitaCita = true;
                        break;
                    }
                }
            }
            $p_cita_cita = $matchCitaCita ? $matchProb['cita_cita'] : max(1 - $matchProb['cita_cita'], $epsilon);

            // 5. Likelihood untuk Prestasi (boost jika ada prestasi)
            $p_prestasi = ($prestasiScore > 0.5) ? $matchProb['prestasi'] : max(1 - $matchProb['prestasi'], $epsilon);

            // Simpan detail per kriteria untuk tampilan
            $detailPerJurusan[$jurusan] = [
                'nilai'    => round($p_nilai, 4),
                'minat'    => round($p_minat, 4),
                'pref'     => round($p_pref, 4),
                'cita'     => round($p_cita_cita, 4),
                'prestasi' => round($p_prestasi, 4),
            ];

            // Hitung log-likelihood dengan bobot
            $logLikelihood =
                ($weights['nilai'] ?? 0) * log(max($p_nilai, $epsilon)) +
                ($weights['minat'] ?? 0) * log(max($p_minat, $epsilon)) +
                ($weights['pref'] ?? 0) * log(max($p_pref, $epsilon)) +
                ($weights['cita_cita'] ?? 0) * log(max($p_cita_cita, $epsilon)) +
                ($weights['prestasi'] ?? 0) * log(max($p_prestasi, $epsilon));

            $logPosteriors[$jurusan] = $logPrior + $logLikelihood;
        }

        // Convert log-posteriors ke probabilitas (softmax)
        $maxLog = max($logPosteriors);
        $expVals = [];
        $sumExp = 0.0;
        foreach ($logPosteriors as $jurusan => $lv) {
            $expVals[$jurusan] = exp($lv - $maxLog);
            $sumExp += $expVals[$jurusan];
        }

        $hasilAkhir = [];
        foreach ($expVals as $jurusan => $val) {
            $prob = $val / max($sumExp, $epsilon);
            $detail = $detailPerJurusan[$jurusan] ?? [];
            $explanations = $this->generateExplanation(
                $jurusan,
                $detail,
                $katNilai,
                $minatMapped,
                $prefStudi,
                $prestasiRaw
            );
            $hasilAkhir[] = [
                'jurusan' => $jurusan,
                'skor' => round($prob, 4),
                'detail'  => $detail,
                'explanation' => $explanations,
                'kecocokan_nilai' => $katNilai,
                'kecocokan_minat' => $minatMapped,
                'kecocokan_pref' => $prefStudi,
            ];
        }

        // Sort hasil berdasarkan skor (tertinggi dulu)
        usort($hasilAkhir, fn($a, $b) => $b['skor'] <=> $a['skor']);

        // Simpan data rekomendasi ke database
        $user = Auth::user();
        if ($user) {
            Recommendation::create([
                'user_id' => $user->id,
                'mtk' => $request->mtk ?? null,
                'fisika' => $request->fisika ?? null,
                'kimia' => $request->kimia ?? null,
                'biologi' => $request->biologi ?? null,
                'ekonomi' => $request->ekonomi ?? null,
                'geografi' => $request->geografi ?? null,
                'sosiologi' => $request->sosiologi ?? null,
                'sejarah' => $request->sejarah ?? null,
                'minat' => $request->minat ?? null,
                'preferensi_studi' => $request->pref_studi ?? null,
                'cita_cita' => $request->cita_cita ?? null,
                'prestasi' => $request->prestasi ?? null,
                'hasil_rekomendasi' => $hasilAkhir,
            ]);
        }

        // Simpan data rekomendasi ke session untuk chatbot
        if (count($hasilAkhir) > 0) {
            $topResult = $hasilAkhir[0];
            session([
                'recomendation_data' => [
                    'jurusan' => $topResult['jurusan'],
                    'skor' => $topResult['skor'],
                    'nilai' => $katNilai,
                    'minat' => $minatMapped,
                    'pref_studi' => $prefStudi,
                ]
            ]);
        }

        return view('rekomendasi.hasil', compact('hasilAkhir', 'katNilai', 'minatMapped', 'citaMapped', 'prefStudi', 'prestasiScore'));
    }

    /**
     * Pemetaan minat ke kategori yang dipahami sistem
     */
    private function mapMinat(string $minatRaw): string
    {
        if (preg_match('/(coding|komputer|laptop|web|aplikasi|logika|programming|software|development)/', $minatRaw)) {
            return 'Logika & Komputer';
        } elseif (preg_match('/(tanam|kebun|sawah|hewan|ternak|alam|pertanian|agri)/', $minatRaw)) {
            return 'Alam & Tanaman';
        } elseif (preg_match('/(obat|sakit|rawat|medis|gizi|sehat|kesehatan|perawat|dokter)/', $minatRaw)) {
            return 'Pelayanan & Kesehatan';
        } elseif (preg_match('/(bisnis|uang|jual|kantor|hitung|ekonomi|dagang|usaha|entrepreneur)/', $minatRaw)) {
            return 'Manajemen & Bisnis';
        } elseif (preg_match('/(mesin|bengkel|listrik|las|robot|motor|teknik|otomasi|elektronik)/', $minatRaw)) {
            return 'Mesin & Listrik';
        }
        return 'Umum';
    }

    /**
     * Pemetaan cita-cita ke kategori jurusan
     */
    private function mapCitaCita(string $citaRaw): string
    {
        // Return raw mapped text untuk matching dengan keywords
        return $citaRaw;
    }

    /**
     * Scoring prestasi berdasarkan keyword
     */
    private function scorePrestasiScore(string $prestasiRaw): float
    {
        if (empty($prestasiRaw)) {
            return 0.0;
        }

        $prestasiRaw = strtolower(trim($prestasiRaw));
        $prestasiScore = 0.0;

        // Berbagai tingkat prestasi
        if (preg_match('/(juara|menang|champion|first|gold|emas|terbaik)/', $prestasiRaw)) {
            $prestasiScore = 0.90; // Prestasi tinggi
        } elseif (preg_match('/(finalis|semifinal|peringkat|ranking|podium|medali|silver|silver|perak)/', $prestasiRaw)) {
            $prestasiScore = 0.75; // Prestasi sedang
        } elseif (preg_match('/(sertifikat|training|kursus|workshop|peserta|mengikuti)/', $prestasiRaw)) {
            $prestasiScore = 0.60; // Prestasi cukup
        } else {
            $prestasiScore = 0.30; // Prestasi minimal
        }

        return $prestasiScore;
    }

    /**
     * Tampilkan history rekomendasi
     */
    public function historyRekomendasi()
    {
        $user = Auth::user();
        $recommendations = Recommendation::where('user_id', $user->id)
            ->orderBy('created_at', 'desc')
            ->get();

        return view('history.rekomendasi', compact('recommendations'));
    }
}