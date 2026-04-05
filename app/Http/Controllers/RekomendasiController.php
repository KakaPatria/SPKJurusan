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
        $epsilon = 1e-9;

        // ================================================================
        // LANGKAH 1: INPUT DATA
        // ================================================================
        $scores = $request->only(['mtk', 'fisika', 'kimia', 'biologi', 'ekonomi', 'geografi', 'sosiologi', 'sejarah']);
        $minatRaw    = strtolower(trim($request->minat ?? ''));
        $prefStudi   = $request->pref_studi ?? 'Sains & Teknologi';
        $citaRaw     = strtolower(trim($request->cita_cita ?? ''));
        $prestasiRaw = strtolower(trim($request->prestasi ?? ''));

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
            $hasilAkhir[] = [
                'jurusan' => $namaJurusan,
                'skor'    => round($prob, 4),
                'detail'  => $detailPerJurusan[$namaJurusan],
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
                'mtk'               => $request->mtk ?? null,
                'fisika'            => $request->fisika ?? null,
                'kimia'             => $request->kimia ?? null,
                'biologi'           => $request->biologi ?? null,
                'ekonomi'           => $request->ekonomi ?? null,
                'geografi'          => $request->geografi ?? null,
                'sosiologi'         => $request->sosiologi ?? null,
                'sejarah'           => $request->sejarah ?? null,
                'minat'             => $request->minat ?? null,
                'preferensi_studi'  => $request->pref_studi ?? null,
                'cita_cita'         => $request->cita_cita ?? null,
                'prestasi'          => $request->prestasi ?? null,
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