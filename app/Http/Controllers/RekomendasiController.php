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
        $user = Auth::user();
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

    /**
     * ============================================================
     *  ALGORITMA NAIVE BAYES UNTUK REKOMENDASI JURUSAN
     *  Sesuai flowchart:
     *  1. Input Data
     *  2. Preprocessing Data
     *  3. Tentukan Hipotesis (H)
     *  4. Hitung Probabilitas Awal (Prior) P(H)
     *  5. Hitung Likelihood P(X|H) per fitur
     *  6. Hitung Probabilitas Gabungan (Rumus Naive Bayes)
     *     P(H|X) ∝ P(H) × P(X1|H) × P(X2|H) × ... × P(Xn|H)
     *  7. Klasifikasi (Hasil Rekomendasi)
     * ============================================================
     */
    public function proses(Request $request)
    {
        $validated = $request->validate([
            'mtk' => ['nullable', 'numeric', 'min:0', 'max:100'],
            'fisika' => ['nullable', 'numeric', 'min:0', 'max:100'],
            'kimia' => ['nullable', 'numeric', 'min:0', 'max:100'],
            'biologi' => ['nullable', 'numeric', 'min:0', 'max:100'],
            'ekonomi' => ['nullable', 'numeric', 'min:0', 'max:100'],
            'geografi' => ['nullable', 'numeric', 'min:0', 'max:100'],
            'sosiologi' => ['nullable', 'numeric', 'min:0', 'max:100'],
            'sejarah' => ['nullable', 'numeric', 'min:0', 'max:100'],
            'minat' => ['required', 'string', 'max:255'],
            'pref_studi' => ['required', 'string', 'in:Sains & Teknologi,Pertanian & Lingkungan,Kesehatan & Ilmu Hayat,Bisnis & Manajemen,Sosial & Humaniora,Praktikum,Teori'],
            'cita_cita' => ['required', 'string', 'max:255'],
            'prestasi' => ['nullable', 'string', 'max:255'],
        ]);

        $kelompokAsal = Auth::user()?->kelompok_asal;
        if ($kelompokAsal === 'IPA') {
            $request->validate([
                'mtk' => ['required', 'numeric', 'min:0', 'max:100'],
                'fisika' => ['required', 'numeric', 'min:0', 'max:100'],
                'kimia' => ['required', 'numeric', 'min:0', 'max:100'],
                'biologi' => ['required', 'numeric', 'min:0', 'max:100'],
            ]);
        } elseif ($kelompokAsal === 'IPS') {
            $request->validate([
                'ekonomi' => ['required', 'numeric', 'min:0', 'max:100'],
                'geografi' => ['required', 'numeric', 'min:0', 'max:100'],
                'sosiologi' => ['required', 'numeric', 'min:0', 'max:100'],
                'sejarah' => ['required', 'numeric', 'min:0', 'max:100'],
            ]);
        }

        $epsilon = 1e-9;

        // ================================================================
        // LANGKAH 1: INPUT DATA
        // ================================================================
        $scores = [
            'mtk' => $validated['mtk'] ?? null,
            'fisika' => $validated['fisika'] ?? null,
            'kimia' => $validated['kimia'] ?? null,
            'biologi' => $validated['biologi'] ?? null,
            'ekonomi' => $validated['ekonomi'] ?? null,
            'geografi' => $validated['geografi'] ?? null,
            'sosiologi' => $validated['sosiologi'] ?? null,
            'sejarah' => $validated['sejarah'] ?? null,
        ];
        $minatRaw    = strtolower(trim($validated['minat'] ?? ''));
        $prefStudi   = $validated['pref_studi'] ?? 'Sains & Teknologi';
        $citaRaw     = strtolower(trim($validated['cita_cita'] ?? ''));
        $prestasiRaw = strtolower(trim($validated['prestasi'] ?? ''));

        // ================================================================
        // LANGKAH 2: PREPROCESSING DATA
        // ================================================================

        // 2a. Hitung rata-rata nilai
        $validScores = array_filter($scores, fn($v) => $v !== null && $v !== '');
        $average = count($validScores) > 0 ? array_sum($validScores) / count($validScores) : 0;

        // 2b. Kategorisasi nilai
        if ($average >= 85) {
            $katNilai = 'Tinggi';
        } elseif ($average >= 70) {
            $katNilai = 'Sedang';
        } else {
            $katNilai = 'Rendah';
        }

        // 2c. Skor prestasi
        $prestasiScore = $this->hitungSkorPrestasi($prestasiRaw);

        // ================================================================
        // LANGKAH 3: TENTUKAN HIPOTESIS (H)
        // H = {Jurusan1, Jurusan2, ..., JurusanN} dari database
        // ================================================================
        $jurusanList = PolijeMajor::all();

        if ($jurusanList->isEmpty()) {
            return back()->with('error', 'Data jurusan belum tersedia di database.');
        }

        $jumlahJurusan = $jurusanList->count();

        // ================================================================
        // LANGKAH 4: HITUNG PROBABILITAS AWAL (PRIOR) P(H)
        // Prior uniform: P(H) = 1 / jumlah_jurusan
        // ================================================================
        $prior = 1 / $jumlahJurusan;

        // ================================================================
        // LANGKAH 5 & 6: HITUNG LIKELIHOOD DAN PROBABILITAS GABUNGAN
        // Rumus Naive Bayes:
        //   P(H|X) ∝ P(H) × P(X1|H) × P(X2|H) × P(X3|H) × P(X4|H) × P(X5|H)
        //
        // Fitur (Xi):
        //   X1 = Nilai Akademik   → P(nilai|H)
        //   X2 = Minat            → P(minat|H)
        //   X3 = Preferensi Studi → P(pref|H)
        //   X4 = Cita-cita        → P(cita|H)
        //   X5 = Prestasi         → P(prestasi|H)
        //
        // Weighted Naive Bayes (log-space):
        //   log P(H|X) = log P(H) + Σ wi × log P(Xi|H)
        //
        // Bobot (wi):
        //   w1 = 0.40 (Nilai), w2 = 0.35 (Minat), w3 = 0.15 (Pref),
        //   w4 = 0.05 (Cita-cita), w5 = 0.05 (Prestasi)
        // ================================================================
        $weights = [
            'nilai'    => 0.40,
            'minat'    => 0.35,
            'pref'     => 0.15,
            'cita'     => 0.05,
            'prestasi' => 0.05,
        ];

        $logPosteriors = [];
        $detailPerJurusan = [];

        foreach ($jurusanList as $jurusan) {
            // --- Log Prior ---
            $logPrior = log(max($prior, $epsilon));

            // --- X1: Likelihood Nilai Akademik P(nilai|H) ---
            $pNilai = $this->hitungLikelihoodNilai($scores, $jurusan->bobot_mapel);

            // --- X2: Likelihood Minat P(minat|H) ---
            $pMinat = $this->hitungLikelihoodMinat($minatRaw, $jurusan->keywords);

            // --- X3: Likelihood Preferensi Studi P(pref|H) ---
            $pPref = $this->hitungLikelihoodPref($prefStudi, $jurusan->preferensi_studi);

            // --- X4: Likelihood Cita-cita P(cita|H) ---
            $pCita = $this->hitungLikelihoodCitaCita($citaRaw, $jurusan->keywords);

            // --- X5: Likelihood Prestasi P(prestasi|H) ---
            $pPrestasi = $this->hitungLikelihoodPrestasi($prestasiScore);

            // --- Probabilitas Gabungan (Weighted Naive Bayes) ---
            // log P(H|X) = log P(H) + w1·log P(X1|H) + w2·log P(X2|H) + ... + w5·log P(X5|H)
            $logPosterior = $logPrior
                + $weights['nilai']    * log(max($pNilai, $epsilon))
                + $weights['minat']    * log(max($pMinat, $epsilon))
                + $weights['pref']     * log(max($pPref, $epsilon))
                + $weights['cita']     * log(max($pCita, $epsilon))
                + $weights['prestasi'] * log(max($pPrestasi, $epsilon));

            $logPosteriors[$jurusan->nama_jurusan] = $logPosterior;

            // Simpan detail per kriteria untuk tampilan
            $detailPerJurusan[$jurusan->nama_jurusan] = [
                'nilai'    => round($pNilai, 4),
                'minat'    => round($pMinat, 4),
                'pref'     => round($pPref, 4),
                'cita'     => round($pCita, 4),
                'prestasi' => round($pPrestasi, 4),
            ];
        }

        // ================================================================
        // LANGKAH 7: KLASIFIKASI (HASIL REKOMENDASI)
        // Konversi log-posterior ke probabilitas menggunakan softmax
        // P(Hk|X) = exp(log Pk) / Σ exp(log Pi)
        // ================================================================
        $maxLog = max($logPosteriors);
        $expVals = [];
        $sumExp = 0.0;

        foreach ($logPosteriors as $namaJurusan => $lv) {
            $expVals[$namaJurusan] = exp($lv - $maxLog);
            $sumExp += $expVals[$namaJurusan];
        }

        $hasilAkhir = [];
        foreach ($expVals as $namaJurusan => $val) {
            $prob = $val / max($sumExp, $epsilon);
            $detail = $detailPerJurusan[$namaJurusan];
            $explanations = $this->generateExplanation(
                $namaJurusan,
                $detail,
                $katNilai,
                $minatRaw,
                $prefStudi,
                $prestasiRaw
            );
            $hasilAkhir[] = [
                'jurusan' => $namaJurusan,
                'skor'    => round($prob, 4),
                'detail'  => $detail,
                'explanation' => $explanations,
                'kecocokan_nilai' => $katNilai,
                'kecocokan_minat' => $minatRaw,
                'kecocokan_pref'  => $prefStudi,
            ];
        }

        // Urutkan berdasarkan skor tertinggi
        usort($hasilAkhir, fn($a, $b) => $b['skor'] <=> $a['skor']);

        // Ambil data jurusan teratas untuk detail view
        $topJurusan = !empty($hasilAkhir) ? PolijeMajor::where('nama_jurusan', $hasilAkhir[0]['jurusan'] ?? '')->first() : null;

        // Simpan ke database
        $user = Auth::user();
        $savedRec = null;
        if ($user) {
            $savedRec = Recommendation::create([
                'user_id'           => $user->id,
                'mtk'               => $validated['mtk'] ?? null,
                'fisika'            => $validated['fisika'] ?? null,
                'kimia'             => $validated['kimia'] ?? null,
                'biologi'           => $validated['biologi'] ?? null,
                'ekonomi'           => $validated['ekonomi'] ?? null,
                'geografi'          => $validated['geografi'] ?? null,
                'sosiologi'         => $validated['sosiologi'] ?? null,
                'sejarah'           => $validated['sejarah'] ?? null,
                'minat'             => $validated['minat'],
                'preferensi_studi'  => $validated['pref_studi'],
                'cita_cita'         => $validated['cita_cita'],
                'prestasi'          => $validated['prestasi'] ?? '',
                'hasil_rekomendasi' => $hasilAkhir,
            ]);
        }

        // Simpan recommendation_id ke session agar bisa dipakai link chatbot
        $recId = $savedRec ? $savedRec->id : null;
        session(['last_recommendation_id' => $recId]);

        // Simpan ke session untuk chatbot
        if (count($hasilAkhir) > 0) {
            $topResult = $hasilAkhir[0];
            // Ambil top 3 untuk konteks chatbot
            $top3 = array_slice($hasilAkhir, 0, 3);
            session([
                'recomendation_data' => [
                    'jurusan'    => $topResult['jurusan'],
                    'skor'       => $topResult['skor'],  // Sudah 0-1, jangan ×100
                    'detail'     => $topResult['detail'] ?? [],
                    'nilai'      => $katNilai,
                    'rata_rata'  => round($average, 1),
                    'minat'      => $minatRaw,
                    'pref_studi' => $prefStudi,
                    'cita_cita'  => $citaRaw,
                    'prestasi'   => $prestasiRaw,
                    'top3'       => array_map(fn($r) => [
                        'jurusan' => $r['jurusan'],
                        'skor'    => $r['skor'],
                    ], $top3),
                ]
            ]);
        }

        return view('rekomendasi.hasil', compact(
            'hasilAkhir', 'katNilai', 'average', 'prefStudi', 'prestasiScore', 'topJurusan'
        ));
    }

    // ==================================================================
    //  FUNGSI LIKELIHOOD — P(Xi | H)
    // ==================================================================

    /**
     * P(nilai | H) — Likelihood nilai akademik terhadap jurusan
     * Menggunakan bobot_mapel dari database untuk menghitung
     * weighted average yang dinormalisasi ke range probabilitas.
     */
    private function hitungLikelihoodNilai(array $scores, ?array $bobotMapel): float
    {
        // Jika tidak ada bobot, gunakan rata-rata biasa
        if (empty($bobotMapel)) {
            $valid = array_filter($scores, fn($v) => $v !== null && $v !== '');
            if (empty($valid)) return 0.3;
            $avg = array_sum($valid) / count($valid);
            return $this->normalisasiProbabilitas($avg / 100, 0.10, 0.95);
        }

        $weightedSum = 0;
        $totalWeight = 0;

        foreach ($bobotMapel as $subject => $weight) {
            $nilai = floatval($scores[$subject] ?? 0);
            if ($nilai > 0 && $weight > 0) {
                $weightedSum += $weight * ($nilai / 100);
                $totalWeight += $weight;
            }
        }

        if ($totalWeight == 0) return 0.3;

        $weightedAvg = $weightedSum / $totalWeight;
        return $this->normalisasiProbabilitas($weightedAvg, 0.10, 0.95);
    }

    /**
     * P(minat | H) — Likelihood minat terhadap jurusan
     * Menggunakan keyword matching terhadap keywords jurusan dari database.
     */
    private function hitungLikelihoodMinat(string $minatRaw, ?array $keywords): float
    {
        if (empty($keywords) || empty($minatRaw)) {
            return 0.20; // probabilitas dasar jika tidak ada data
        }

        $matchCount = 0;
        foreach ($keywords as $keyword) {
            if (stripos($minatRaw, strtolower($keyword)) !== false) {
                $matchCount++;
            }
        }

        // Rasio kecocokan keyword
        $matchRatio = $matchCount / count($keywords);

        // Konversi ke range probabilitas: 0 match → 0.10, full match → 0.95
        return $this->normalisasiProbabilitas($matchRatio, 0.10, 0.95);
    }

    /**
     * P(pref | H) — Likelihood preferensi studi terhadap jurusan
     * Membandingkan preferensi siswa dengan preferensi_studi jurusan dari database.
     */
    private function hitungLikelihoodPref(string $prefStudi, ?array $jurusanPref): float
    {
        if (empty($jurusanPref)) {
            return 0.40; // probabilitas netral
        }

        // Cek apakah preferensi siswa ada di list preferensi jurusan
        if (in_array($prefStudi, $jurusanPref)) {
            return 0.85; // cocok
        }

        return 0.15; // tidak cocok
    }

    /**
     * P(cita_cita | H) — Likelihood cita-cita terhadap jurusan
     * Menggunakan keyword matching dari cita-cita siswa terhadap keywords jurusan.
     */
    private function hitungLikelihoodCitaCita(string $citaRaw, ?array $keywords): float
    {
        if (empty($keywords) || empty($citaRaw)) {
            return 0.25; // probabilitas dasar
        }

        $matchCount = 0;
        foreach ($keywords as $keyword) {
            if (stripos($citaRaw, strtolower($keyword)) !== false) {
                $matchCount++;
            }
        }

        $matchRatio = $matchCount / count($keywords);
        return $this->normalisasiProbabilitas($matchRatio, 0.10, 0.90);
    }

    /**
     * P(prestasi | H) — Likelihood prestasi
     * Prestasi bersifat umum (tidak spesifik per jurusan), sehingga
     * memberikan boost yang sama untuk semua jurusan.
     */
    private function hitungLikelihoodPrestasi(float $prestasiScore): float
    {
        // Konversi skor prestasi (0-1) ke range probabilitas
        return $this->normalisasiProbabilitas($prestasiScore, 0.20, 0.90);
    }

    // ==================================================================
    //  FUNGSI HELPER
    // ==================================================================

    /**
     * Normalisasi nilai (0-1) ke range probabilitas [min, max]
     * Agar tidak ada likelihood 0 atau 1 (menghindari dominasi)
     */
    private function normalisasiProbabilitas(float $value, float $min = 0.10, float $max = 0.95): float
    {
        return $min + ($value * ($max - $min));
    }

    /**
     * Hitung skor prestasi berdasarkan keyword
     */
    private function hitungSkorPrestasi(string $prestasiRaw): float
    {
        $prestasiRaw = strtolower(trim($prestasiRaw));

        if (empty($prestasiRaw)) {
            return 0.0;
        }

        if (preg_match('/(juara|menang|champion|first|gold|emas|terbaik)/', $prestasiRaw)) {
            return 0.90;
        } elseif (preg_match('/(finalis|semifinal|peringkat|ranking|podium|medali|silver|perak)/', $prestasiRaw)) {
            return 0.75;
        } elseif (preg_match('/(sertifikat|training|kursus|workshop|peserta|mengikuti)/', $prestasiRaw)) {
            return 0.60;
        }

        return 0.30;
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