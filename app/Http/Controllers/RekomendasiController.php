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
    private function generateExplanation($jurusanNama, $detail, $katNilai, $kategoriMinat, $prefStudi, $prestasi, array $prestasiAnalysis = [])
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
        if (!($prestasiAnalysis['provided'] ?? false)) {
            $explanations['prestasi'] = "ℹ️ Prestasi tidak diisi, sehingga atribut prestasi tidak dihitung pada proses skoring.";
            return $explanations;
        }

        $levelPrestasi = $prestasiAnalysis['level'] ?? 'minimal';
        $labelLevel = [
            'tinggi' => 'tinggi',
            'sedang' => 'menengah',
            'cukup' => 'dasar',
            'minimal' => 'minimal',
        ][$levelPrestasi] ?? 'minimal';

        if ($skorPrestasi >= 0.7) {
            $explanations['prestasi'] = "✅ Prestasi Anda terdeteksi pada level {$labelLevel} dan memberi kontribusi kuat untuk kecocokan jurusan ini.";
        } elseif ($skorPrestasi >= 0.4) {
            $explanations['prestasi'] = "✓ Prestasi Anda berada pada level {$labelLevel} dan tetap dipertimbangkan sebagai faktor pendukung.";
        } else {
            $explanations['prestasi'] = "ℹ️ Input prestasi tetap dihitung (level {$labelLevel}), namun saat ini kontribusinya relatif kecil dibanding faktor utama lain.";
        }

        return $explanations;
    }

    public function proses(Request $request)
    {
        // Pastikan perhitungan Naive Bayes selalu berbasis 5 atribut input rekomendasi:
        // 1) Nilai akademik, 2) Minat, 3) Preferensi studi, 4) Cita-cita, 5) Prestasi.
        $user = Auth::user();
        $kelompokAsal = strtoupper($user->kelompok_asal ?? 'IPA');

        $rules = [
            'mtk' => 'nullable|numeric|min:0|max:100',
            'fisika' => 'nullable|numeric|min:0|max:100',
            'kimia' => 'nullable|numeric|min:0|max:100',
            'biologi' => 'nullable|numeric|min:0|max:100',
            'ekonomi' => 'nullable|numeric|min:0|max:100',
            'geografi' => 'nullable|numeric|min:0|max:100',
            'sosiologi' => 'nullable|numeric|min:0|max:100',
            'sejarah' => 'nullable|numeric|min:0|max:100',
            'minat' => 'required|string|max:255',
            'pref_studi' => 'required|string',
            'cita_cita' => 'required|string|max:255',
            'prestasi' => 'nullable|string|max:255',
        ];

        if ($kelompokAsal === 'IPA') {
            $rules['mtk'] = 'required|numeric|min:0|max:100';
            $rules['fisika'] = 'required|numeric|min:0|max:100';
            $rules['kimia'] = 'required|numeric|min:0|max:100';
            $rules['biologi'] = 'required|numeric|min:0|max:100';
        } else {
            $rules['ekonomi'] = 'required|numeric|min:0|max:100';
            $rules['geografi'] = 'required|numeric|min:0|max:100';
            $rules['sosiologi'] = 'required|numeric|min:0|max:100';
            $rules['sejarah'] = 'required|numeric|min:0|max:100';
        }

        $validated = $request->validate($rules);

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
        $minatInput = trim((string) ($validated['minat'] ?? ''));
        $minatRaw = strtolower($minatInput);
        $minatMapped = $this->mapMinat($minatRaw);

        // --- 3. PREFERENSI STUDI LANJUTAN (Kriteria 3) ---
        $prefStudi = $validated['pref_studi'];
        $prefMapping = config('polije.pref_mapping', []);

        // --- 4. ANALISIS CITA-CITA (Kriteria 4) ---
        $citaInput = trim((string) ($validated['cita_cita'] ?? ''));
        $citaRaw = strtolower($citaInput);
        $citaMapped = $this->mapCitaCita($citaRaw);

        // --- 5. ANALISIS PRESTASI (Kriteria 5) ---
        $prestasiInput = trim((string) ($validated['prestasi'] ?? ''));
        $isPrestasiFilled = $prestasiInput !== '';
        $prestasiRaw = strtolower($prestasiInput);
        $prestasiAnalysis = $this->analyzePrestasi($prestasiRaw);
        $prestasiScore = $prestasiAnalysis['score'];

        // --- 6. PERHITUNGAN NAIVE BAYES BERBOBOT ---
        $cfg = config('polije.criteria', []);
        $majorMap = PolijeMajor::all()->keyBy('nama_jurusan');
        $logPosteriors = [];
        $detailPerJurusan = [];
        $epsilon = 1e-9;

        foreach ($cfg as $jurusan => $c) {
            // Prior: uniform
            $prior = 1 / count($cfg);
            $logPrior = log(max($prior, $epsilon));

            // Weights dan match probabilities
            $weights = $c['weights'] ?? ['nilai' => 0.40, 'minat' => 0.35, 'pref' => 0.15, 'prestasi' => 0.05, 'cita_cita' => 0.05];

            // Jika prestasi kosong, atribut prestasi tidak dihitung.
            if (!$isPrestasiFilled) {
                $weights['prestasi'] = 0.0;
                $sumNonPrestasi = ($weights['nilai'] ?? 0) + ($weights['minat'] ?? 0) + ($weights['pref'] ?? 0) + ($weights['cita_cita'] ?? 0);
                if ($sumNonPrestasi > 0) {
                    $weights['nilai'] = ($weights['nilai'] ?? 0) / $sumNonPrestasi;
                    $weights['minat'] = ($weights['minat'] ?? 0) / $sumNonPrestasi;
                    $weights['pref'] = ($weights['pref'] ?? 0) / $sumNonPrestasi;
                    $weights['cita_cita'] = ($weights['cita_cita'] ?? 0) / $sumNonPrestasi;
                }
            }
            $matchProb = $c['match_prob'] ?? ['nilai' => 0.80, 'minat' => 0.90, 'pref' => 0.85, 'prestasi' => 0.65, 'cita_cita' => 0.85];

            // 1. Likelihood untuk Nilai
            // Tetap berbasis atribut Nilai Akademik, namun dibuat lebih granular:
            // kombinasi kategori nilai + kecocokan bobot mapel per jurusan (dari data PolijeMajor).
            $p_nilai_category = ($katNilai == ($c['nilai'] ?? 'Sedang'))
                ? $matchProb['nilai']
                : max(1 - $matchProb['nilai'], $epsilon);
            $p_nilai_subject = $this->scoreSubjectFitLikelihood(
                $majorMap[$jurusan]->bobot_mapel ?? [],
                $scores,
                $p_nilai_category
            );
            $p_nilai = max(0.05, min(0.98, (0.6 * $p_nilai_category) + (0.4 * $p_nilai_subject)));

            // 2. Likelihood untuk Minat
            $p_minat = $this->scoreMinatLikelihood(
                $minatRaw,
                $minatMapped,
                $c['minat'] ?? 'Umum',
                $matchProb['minat']
            );

            // 3. Likelihood untuk Preferensi Studi
            $prefList = $c['pref'] ?? ['Sains & Teknologi', 'Pertanian & Lingkungan', 'Kesehatan & Ilmu Hayat', 'Bisnis & Manajemen', 'Sosial & Humaniora'];
            if (!is_array($prefList)) {
                $prefList = [$prefList];
            }
            $p_pref = in_array($prefStudi, $prefList) ? $matchProb['pref'] : max(1 - $matchProb['pref'], $epsilon);

            // 4. Likelihood untuk Cita-cita
            $citaCitaKeywords = $c['cita_cita_keywords'] ?? [];
            $p_cita_cita = $this->scoreKeywordLikelihood($citaMapped, $citaCitaKeywords, $matchProb['cita_cita']);

            // 5. Likelihood untuk Prestasi (bertingkat: tinggi/menengah/dasar/minimal)
            $p_prestasi = $this->scorePrestasiLikelihood(
                $prestasiAnalysis,
                $citaCitaKeywords,
                $matchProb['prestasi']
            );

            // Simpan detail per kriteria untuk tampilan
            $detailPerJurusan[$jurusan] = [
                'nilai'    => round($p_nilai, 4),
                'minat'    => round($p_minat, 4),
                'pref'     => round($p_pref, 4),
                'cita'     => round($p_cita_cita, 4),
                'prestasi' => $isPrestasiFilled ? round($p_prestasi, 4) : null,
            ];

            // Hitung log-likelihood dengan bobot
            $logLikelihood =
                ($weights['nilai'] ?? 0) * log(max($p_nilai, $epsilon)) +
                ($weights['minat'] ?? 0) * log(max($p_minat, $epsilon)) +
                ($weights['pref'] ?? 0) * log(max($p_pref, $epsilon)) +
                ($weights['cita_cita'] ?? 0) * log(max($p_cita_cita, $epsilon));

            if (($weights['prestasi'] ?? 0) > 0) {
                $logLikelihood += ($weights['prestasi'] ?? 0) * log(max($p_prestasi, $epsilon));
            }

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
                $prestasiRaw,
                $prestasiAnalysis
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
                'minat' => $minatInput,
                'preferensi_studi' => $prefStudi,
                'cita_cita' => $citaInput,
                // Kolom prestasi di DB bersifat NOT NULL, jadi simpan string kosong jika tidak diisi.
                'prestasi' => $prestasiInput,
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

        // Ambil data jurusan teratas dari database untuk deskripsi/prospek pada halaman hasil
        $topJurusan = null;
        if (count($hasilAkhir) > 0) {
            $topJurusan = PolijeMajor::where('nama_jurusan', $hasilAkhir[0]['jurusan'] ?? '')->first();
        }

        return view('rekomendasi.hasil', compact('hasilAkhir', 'katNilai', 'average', 'minatMapped', 'citaMapped', 'prefStudi', 'prestasiScore', 'topJurusan', 'isPrestasiFilled'));
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
     * Ringkas level prestasi dari input teks agar lebih transparan.
     */
    private function analyzePrestasi(string $prestasiRaw): array
    {
        if (empty(trim($prestasiRaw))) {
            return [
                'provided' => false,
                'level' => 'minimal',
                'score' => 0.0,
                'raw' => '',
            ];
        }

        $text = strtolower(trim($prestasiRaw));
        $level = 'minimal';

        if (preg_match('/(juara|menang|champion|first|gold|emas|terbaik)/', $text)) {
            $level = 'tinggi';
        } elseif (preg_match('/(finalis|semifinal|peringkat|ranking|podium|medali|silver|perak)/', $text)) {
            $level = 'sedang';
        } elseif (preg_match('/(sertifikat|training|kursus|workshop|peserta|mengikuti)/', $text)) {
            $level = 'cukup';
        }

        return [
            'provided' => true,
            'level' => $level,
            'score' => $this->scorePrestasiScore($text),
            'raw' => $text,
        ];
    }

    /**
     * Skor atribut teks berdasarkan coverage keyword (0..1) lalu dipetakan menjadi likelihood.
     */
    private function scoreKeywordLikelihood(string $text, array $keywords, float $matchProb): float
    {
        if (empty($keywords)) {
            return 0.50;
        }

        $coverage = $this->keywordCoverage($text, $keywords);

        // Base 0.2 agar tidak 0 total, lalu naik proporsional coverage.
        $likelihood = 0.20 + ($coverage * ($matchProb - 0.20));

        return max(0.05, min(0.98, $likelihood));
    }

    private function scoreMinatLikelihood(string $minatRaw, string $minatMapped, string $targetMinat, float $matchProb): float
    {
        $keywordBank = [
            'Logika & Komputer' => ['coding', 'programming', 'komputer', 'software', 'web', 'data', 'ai', 'digital'],
            'Alam & Tanaman' => ['pertanian', 'tanaman', 'kebun', 'sawah', 'alam', 'peternakan', 'agribisnis'],
            'Pelayanan & Kesehatan' => ['kesehatan', 'medis', 'gizi', 'perawat', 'dokter', 'klinik', 'rumah sakit'],
            'Manajemen & Bisnis' => ['bisnis', 'usaha', 'marketing', 'keuangan', 'manajemen', 'akuntansi', 'entrepreneur'],
            'Mesin & Listrik' => ['mesin', 'listrik', 'teknik', 'otomasi', 'elektronik', 'mekanik', 'industri'],
        ];

        $targetKeywords = $keywordBank[$targetMinat] ?? [];
        $coverage = $this->keywordCoverage($minatRaw, $targetKeywords);
        $categoryMatch = ($minatMapped === $targetMinat) ? 1.0 : 0.0;

        // Kombinasi semantic match + category match.
        $combined = (0.6 * $coverage) + (0.4 * $categoryMatch);
        $likelihood = 0.20 + ($combined * ($matchProb - 0.20));

        return max(0.05, min(0.98, $likelihood));
    }

    private function scorePrestasiLikelihood(array $prestasiAnalysis, array $jurusanKeywords, float $matchProb): float
    {
        $baseScore = $prestasiAnalysis['score'] ?? 0.0; // 0..1

        if ($baseScore <= 0.0) {
            return 0.20;
        }

        // Relevansi prestasi terhadap konteks jurusan (dari teks prestasi vs keyword jurusan)
        $relevance = 0.0;
        if (!empty($jurusanKeywords)) {
            $relevance = $this->keywordCoverage($prestasiAnalysis['level'] . ' ' . ($prestasiAnalysis['raw'] ?? ''), $jurusanKeywords);
        }

        // Prestasi level dominan, relevance sebagai penguat.
        $combined = (0.75 * $baseScore) + (0.25 * $relevance);
        $likelihood = 0.20 + ($combined * ($matchProb - 0.20));

        return max(0.05, min(0.98, $likelihood));
    }

    private function keywordCoverage(string $text, array $keywords): float
    {
        $text = strtolower(trim($text));
        if ($text === '' || empty($keywords)) {
            return 0.0;
        }

        $matched = 0;
        foreach (array_unique($keywords) as $keyword) {
            if ($keyword !== '' && str_contains($text, strtolower($keyword))) {
                $matched++;
            }
        }

        // Normalisasi agar tidak terlalu menghukum list keyword yang panjang.
        $denominator = max(1, min(count(array_unique($keywords)), 6));

        return min(1.0, $matched / $denominator);
    }

    /**
     * Hitung kecocokan nilai mapel terhadap bobot mapel jurusan (0..1) lalu ubah ke likelihood.
     * Ini tetap bagian dari atribut "Nilai Akademik" agar tidak keluar dari 5 atribut.
     */
    private function scoreSubjectFitLikelihood(array $bobotMapel, array $scores, float $fallback): float
    {
        if (empty($bobotMapel)) {
            return max(0.05, min(0.98, $fallback));
        }

        $weighted = 0.0;
        $weightSum = 0.0;

        foreach ($bobotMapel as $mapel => $w) {
            $nilai = $scores[$mapel] ?? null;
            if ($nilai === null || $nilai === '') {
                continue;
            }

            $num = (float) $nilai;
            $normalized = max(0.0, min(1.0, $num / 100));
            $weighted += $normalized * (float) $w;
            $weightSum += (float) $w;
        }

        if ($weightSum <= 0) {
            return max(0.05, min(0.98, $fallback));
        }

        $fitScore = $weighted / $weightSum; // 0..1

        // Map ke likelihood agar tidak terlalu ekstrem.
        return max(0.05, min(0.98, 0.25 + (0.70 * $fitScore)));
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