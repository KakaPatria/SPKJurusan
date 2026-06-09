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

        // Ambil riwayat rekomendasi user (limit 10 terakhir, diurutkan dari terbaru)
        $recommendations = Recommendation::where('user_id', $user->id)
            ->orderBy('created_at', 'desc')
            ->limit(10)
            ->get();

        return view('rekomendasi.input', compact('student', 'recommendations'));
    }

    /**
     * Generate textual explanation untuk setiap kriteria
     * Menjelaskan "mengapa jurusan ini cocok" berdasarkan scoring detail
     */
    private function generateExplanation($jurusanNama, $detail, $katNilai, $kategoriMinat, $prefStudi, $prestasiRaw, array $prestasiAnalysis = [])
    {
        $explanations = [];

        // 1. Penjelasan Nilai Akademik (Kriteria 1)
        $skorNilai = $detail['nilai'] ?? 0;
        if ($skorNilai >= 0.8) {
            $explanations['nilai'] = "✅ Nilai akademik Anda ($katNilai: avg score tinggi) sangat sesuai dengan jalur pendidikan yang dibutuhkan jurusan ini.";
        } elseif ($skorNilai >= 0.6) {
            $explanations['nilai'] = "✓ Nilai akademik Anda ($katNilai) cukup sesuai dengan persyaratan jurusan ini.";
        } else {
            $explanations['nilai'] = "⚠️ Nilai akademik Anda ($katNilai) masih perlu ditingkatkan untuk optimal di jurusan ini, namun tetap relevan.";
        }

        // 2. Penjelasan Minat (Kriteria 2)
        $skorMinat = $detail['minat'] ?? 0;
        if ($skorMinat >= 0.8) {
            $explanations['minat'] = "✅ Minat Anda ($kategoriMinat) sangat sesuai dan cocok dengan fokus kurikulum $jurusanNama. Anda akan mempelajari hal-hal yang Anda sukai.";
        } elseif ($skorMinat >= 0.6) {
            $explanations['minat'] = "✓ Minat Anda ($kategoriMinat) cukup relevan dan sesuai dengan area pembelajaran di $jurusanNama.";
        } else {
            $explanations['minat'] = "ℹ️ Minat Anda ($kategoriMinat) memiliki kesamaan dan relevansi dengan aspek-aspek tertentu di $jurusanNama.";
        }

        // 3. Penjelasan Preferensi Studi (Kriteria 3)
        $skorPref = $detail['pref'] ?? 0;
        if ($skorPref >= 0.8) {
            $explanations['pref'] = "✅ Preferensi studi \"$prefStudi\" Anda sangat sesuai dengan karakter jurusan $jurusanNama.";
        } elseif ($skorPref >= 0.6) {
            $explanations['pref'] = "✓ Preferensi studi \"$prefStudi\" Anda cukup relevan dengan jurusan ini.";
        } else {
            $explanations['pref'] = "ℹ️ Jurusan ini masih memiliki keterkaitan dengan preferensi studi \"$prefStudi\" Anda.";
        }

        // 4. Penjelasan Cita-cita (Kriteria 4) - IMPROVED with more detail
        $skorCita = $detail['cita'] ?? 0;
        if ($skorCita >= 0.8) {
            $explanations['cita'] = "✅ Cita-cita karir Anda sangat sesuai dan aligned dengan standar lulusan $jurusanNama. Jurusan ini secara langsung mempersiapkan Anda untuk mencapai cita-cita tersebut.";
        } elseif ($skorCita >= 0.6) {
            $explanations['cita'] = "✓ Cita-cita Anda memiliki potensi besar untuk dicapai melalui pendidikan di $jurusanNama. Kurikulum akan membekali skills yang relevan.";
        } else {
            $explanations['cita'] = "ℹ️ Jurusan ini membuka jalur karir yang sesuai dengan cita-cita dan aspirasi Anda, meski tidak secara langsung target-nya.";
        }

        // 5. Penjelasan Prestasi (Kriteria 5) - IMPROVED with more detail
        $skorPrestasi = $detail['prestasi'] ?? 0;
        if (!($prestasiAnalysis['provided'] ?? false)) {
            $explanations['prestasi'] = "ℹ️ Prestasi tidak diisi. Jika Anda memiliki prestasi atau achievement, itu dapat meningkatkan score untuk jurusan ini.";
            return $explanations;
        }

        $levelPrestasi = $prestasiAnalysis['level'] ?? 'minimal';
        $rawPrestasi = $prestasiAnalysis['raw'] ?? '';
        
        $labelLevel = [
            'tinggi' => 'TINGGI (Juara/Winner)',
            'sedang' => 'MENENGAH (Finalis/Medalist)',
            'cukup' => 'DASAR (Peserta/Sertifikat)',
            'minimal' => 'MINIMAL',
        ];
        
        if ($skorPrestasi >= 0.8) {
            $explanations['prestasi'] = "✅ Prestasi Anda ($labelLevel[$levelPrestasi]): \"$rawPrestasi\" sangat relevan dengan $jurusanNama. Ini menunjukkan Anda memiliki dedication dan capability.";
        } elseif ($skorPrestasi >= 0.6) {
            $explanations['prestasi'] = "✓ Prestasi Anda ($labelLevel[$levelPrestasi]): \"$rawPrestasi\" cukup relevan dan menunjukkan potensi di bidang ini.";
        } else {
            $explanations['prestasi'] = "ℹ️ Prestasi Anda ($labelLevel[$levelPrestasi]): \"$rawPrestasi\" menunjukkan usaha yang dapat dikembangkan lebih lanjut di $jurusanNama.";
        }
        
        return $explanations;
    }

    public function proses(Request $request)
    {
        // Pastikan perhitungan Naive Bayes selalu berbasis 5 atribut input rekomendasi:
        // 1) Nilai akademik, 2) Minat, 3) Preferensi studi, 4) Cita-cita, 5) Prestasi.
        $user = Auth::user();
        $kelompokAsal = strtoupper($user->kelompok_asal ?? 'IPA');

        // Enhanced validation rules dengan lebih strict untuk non-akademik fields
        $rules = [
            'mtk' => 'nullable|numeric|between:0,100',
            'fisika' => 'nullable|numeric|between:0,100',
            'kimia' => 'nullable|numeric|between:0,100',
            'biologi' => 'nullable|numeric|between:0,100',
            'ekonomi' => 'nullable|numeric|between:0,100',
            'geografi' => 'nullable|numeric|between:0,100',
            'sosiologi' => 'nullable|numeric|between:0,100',
            'sejarah' => 'nullable|numeric|between:0,100',
            'minat' => 'required|string|min:3|max:255',
            'pref_studi' => 'required|string|in:Sains & Teknologi,Pertanian & Lingkungan,Kesehatan & Ilmu Hayat,Bisnis & Manajemen,Sosial & Humaniora',
            'cita_cita' => 'required|string|min:3|max:255',
            'prestasi' => 'nullable|string|min:3|max:255',
        ];

        if ($kelompokAsal === 'IPA') {
            $rules['mtk'] = 'required|numeric|between:0,100';
            $rules['fisika'] = 'required|numeric|between:0,100';
            $rules['kimia'] = 'required|numeric|between:0,100';
            $rules['biologi'] = 'required|numeric|between:0,100';
        } else {
            $rules['ekonomi'] = 'required|numeric|between:0,100';
            $rules['geografi'] = 'required|numeric|between:0,100';
            $rules['sosiologi'] = 'required|numeric|between:0,100';
            $rules['sejarah'] = 'required|numeric|between:0,100';
        }

        // Custom error messages untuk lebih informatif
        $messages = [
            '*.required' => ':attribute wajib diisi.',
            '*.numeric' => ':attribute harus berupa angka.',
            '*.between' => ':attribute harus di antara 0 sampai 100.',
            '*.max' => ':attribute melebihi batas maksimum yang diizinkan.',
            'minat.required' => 'Minat harus diisi (minimal 3 karakter)',
            'minat.min' => 'Minat terlalu pendek, jelaskan lebih detail',
            'minat.max' => 'Minat maksimal 255 karakter',
            'cita_cita.required' => 'Cita-cita harus diisi (minimal 3 karakter)',
            'cita_cita.min' => 'Cita-cita terlalu pendek, jelaskan lebih detail',
            'cita_cita.max' => 'Cita-cita maksimal 255 karakter',
            'prestasi.min' => 'Prestasi terlalu pendek, jelaskan lebih detail',
            'prestasi.max' => 'Prestasi maksimal 255 karakter',
            'pref_studi.required' => 'Pilih salah satu preferensi studi',
            'pref_studi.in' => 'Preferensi studi tidak valid',
        ];

        $attributes = [
            'mtk' => 'Nilai Matematika',
            'fisika' => 'Nilai Fisika',
            'kimia' => 'Nilai Kimia',
            'biologi' => 'Nilai Biologi',
            'ekonomi' => 'Nilai Ekonomi',
            'geografi' => 'Nilai Geografi',
            'sosiologi' => 'Nilai Sosiologi',
            'sejarah' => 'Nilai Sejarah',
            'minat' => 'Minat',
            'pref_studi' => 'Preferensi Studi',
            'cita_cita' => 'Cita-cita',
            'prestasi' => 'Prestasi',
        ];

        $validated = $request->validate($rules, $messages, $attributes);

        // Validasi berbasis mapping tree agar typo/singkatan tidak lolos sebagai kategori umum.
        $minatInput = trim((string) ($validated['minat'] ?? ''));
        $minatRaw = strtolower($minatInput);
        $minatMapping = $this->mapMinat($minatRaw);
        if (!($minatMapping['is_valid'] ?? false)) {
            $suggestion = !empty($minatMapping['suggestion'])
                ? ' Mungkin maksud Anda: "' . $minatMapping['suggestion'] . '".'
                : '';

            return back()
                ->withErrors([
                    'minat' => 'Minat tidak dikenali. Gunakan kata yang lebih spesifik sesuai bidang minat.' . $suggestion,
                ])
                ->withInput();
        }

        $prefInput = trim((string) ($validated['pref_studi'] ?? ''));
        $prefMapping = $this->mapPreferensiStudi($prefInput);
        if (!($prefMapping['is_valid'] ?? false)) {
            $suggestion = !empty($prefMapping['suggestion'])
                ? ' Mungkin maksud Anda: "' . $prefMapping['suggestion'] . '".'
                : '';

            return back()
                ->withErrors([
                    'pref_studi' => 'Preferensi studi tidak valid.' . $suggestion,
                ])
                ->withInput();
        }

        $minatMapped = $minatMapping['category'];
        $prefStudi = $prefMapping['canonical'];

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
        // Log untuk audit trail
        \Log::debug('Minat Analysis', [
            'input' => $minatInput,
            'normalized' => $minatRaw,
            'mapped' => $minatMapped,
            'confidence' => $minatMapping['score'] ?? 0,
        ]);

        // --- 3. PREFERENSI STUDI LANJUTAN (Kriteria 3) ---
        \Log::debug('Preferensi Studi Analysis', [
            'input' => $prefInput,
            'mapped' => $prefStudi,
        ]);

        // --- 4. ANALISIS CITA-CITA (Kriteria 4) ---
        $citaInput = trim((string) ($validated['cita_cita'] ?? ''));
        
        $citaRaw = strtolower($citaInput);
        $citaMapping = $this->mapCitaCita($citaRaw);
        if (!($citaMapping['is_valid'] ?? false)) {
            $suggestion = !empty($citaMapping['suggestion'])
                ? ' Mungkin maksud Anda: "' . $citaMapping['suggestion'] . '".'
                : '';

            return back()
                ->withErrors([
                    'cita_cita' => 'Cita-cita tidak dikenali. Gunakan kata yang lebih spesifik sesuai bidang karir.' . $suggestion,
                ])
                ->withInput();
        }
        
        $citaMapped = $citaMapping['category'];
        
        // Log untuk audit trail
        \Log::debug('Cita-cita Analysis', [
            'input' => $citaInput,
            'normalized' => $citaRaw,
            'mapped' => $citaMapped,
        ]);

        // --- 5. ANALISIS PRESTASI (Kriteria 5) ---
        $prestasiInput = trim((string) ($validated['prestasi'] ?? ''));
        $isPrestasiFilled = $prestasiInput !== '' && strlen($prestasiInput) >= 3;
        $prestasiRaw = strtolower($prestasiInput);
        $prestasiAnalysis = $this->analyzePrestasi($prestasiRaw);
        $prestasiScore = $prestasiAnalysis['score'];
        
        // Log untuk audit trail
        \Log::debug('Prestasi Analysis', [
            'input' => $prestasiInput,
            'is_filled' => $isPrestasiFilled,
            'normalized' => $prestasiRaw,
            'level' => $prestasiAnalysis['level'] ?? 'not provided',
            'score' => $prestasiScore,
        ]);

        // --- 6. PERHITUNGAN NAIVE BAYES BERBOBOT ---
        $cfg = config('polije.criteria', []);
        $majorMap = PolijeMajor::all()->keyBy('nama_jurusan');
        $logPosteriors = [];
        $detailPerJurusan = [];
        $epsilon = 1e-9;

        // Validate config exists
        if (empty($cfg)) {
            return response()->json([
                'success' => false,
                'message' => 'Konfigurasi sistem rekomendasi tidak ditemukan',
            ])->setStatusCode(500);
        }

        foreach ($cfg as $jurusan => $c) {
            // Prior: uniform dengan safety check
            $cfgCount = max(1, count($cfg)); // Prevent division by zero
            $prior = 1 / $cfgCount;
            $logPrior = log(max($prior, $epsilon));

            // Use global ROC weights (override any per-jurusan editable weights)
            // ROC-based: nilai 15.6%, minat 45.6%, pref 25.6%, cita 9%, prestasi 4%
            $globalWeights = ['nilai' => 0.156, 'minat' => 0.456, 'pref' => 0.256, 'cita_cita' => 0.090, 'prestasi' => 0.040];
            $weights = $globalWeights;

            // Jika prestasi kosong, atribut prestasi tidak dihitung dan lakukan normalisasi ulang pada atribut lain
            if (!$isPrestasiFilled) {
                $weights['prestasi'] = 0.0;
                $sumNonPrestasi = ($weights['nilai'] ?? 0) + ($weights['minat'] ?? 0) + ($weights['pref'] ?? 0) + ($weights['cita_cita'] ?? 0);

                // Normalize weights dengan safety check
                if ($sumNonPrestasi > $epsilon) {
                    $weights['nilai'] = ($weights['nilai'] ?? 0) / $sumNonPrestasi;
                    $weights['minat'] = ($weights['minat'] ?? 0) / $sumNonPrestasi;
                    $weights['pref'] = ($weights['pref'] ?? 0) / $sumNonPrestasi;
                    $weights['cita_cita'] = ($weights['cita_cita'] ?? 0) / $sumNonPrestasi;
                } else {
                    // Fallback ke global jika normalisasi gagal
                    $weights = $globalWeights;
                }
            }
            
            $matchProb = $c['match_prob'] ?? ['nilai' => 0.80, 'minat' => 0.90, 'prestasi' => 0.65, 'cita_cita' => 0.85];
            
            // Ensure matchProb is array
            if (!is_array($matchProb)) {
                $matchProb = ['nilai' => 0.80, 'minat' => 0.90, 'prestasi' => 0.65, 'cita_cita' => 0.85];
            }

            // 1. Likelihood untuk Nilai
            $p_nilai_category = ($katNilai == ($c['nilai'] ?? 'Sedang'))
                ? ($matchProb['nilai'] ?? 0.80)
                : max(1 - ($matchProb['nilai'] ?? 0.80), $epsilon);
            
            // Safe access to majorMap with null check
            $majorRecord = $majorMap[$jurusan] ?? null;
            $bobotMapel = $majorRecord ? $this->getBobotMapelForKelompok($majorRecord->bobot_mapel ?? [], $kelompokAsal) : [];
            
            $p_nilai_subject = $this->scoreSubjectFitLikelihood(
                $bobotMapel,
                $scores,
                $p_nilai_category
            );
            $p_nilai = max(0.05, min(0.98, (0.6 * $p_nilai_category) + (0.4 * $p_nilai_subject)));

            // 2. Likelihood untuk Minat
            $p_minat = $this->scoreMinatLikelihood(
                $minatRaw,
                $minatMapped,
                $c['minat'] ?? 'Umum',
                $matchProb['minat'] ?? 0.90
            );

            // 3. Likelihood untuk Preferensi Studi
            $prefList = $c['pref'] ?? ['Sains & Teknologi', 'Pertanian & Lingkungan', 'Kesehatan & Ilmu Hayat', 'Bisnis & Manajemen', 'Sosial & Humaniora'];
            if (!is_array($prefList)) {
                $prefList = [$prefList];
            }
            $p_pref = in_array($prefStudi, $prefList) ? ($matchProb['pref'] ?? 0.85) : max(1 - ($matchProb['pref'] ?? 0.85), $epsilon);

            // 4. Likelihood untuk Cita-cita
            $citaCitaKeywords = $c['cita_cita_keywords'] ?? [];
            
            // Check if the mapped career category matches the department
            $categoryToMajors = [
                'IT & Software' => ['Teknologi Informasi'],
                'Agriculture' => ['Produksi Pertanian', 'Teknologi Pertanian', 'Peternakan', 'Manajemen Agribisnis'],
                'Healthcare' => ['Kesehatan'],
                'Business' => ['Manajemen Agribisnis', 'Bisnis'],
                'Engineering' => ['Teknik', 'Teknologi Pertanian'],
                'Communication' => ['Bahasa, Komunikasi, dan Pariwisata'],
            ];
            
            $categoryMatches = in_array($jurusan, $categoryToMajors[$citaMapped] ?? []);
            
            // Score based on raw text keyword coverage
            $rawCoverage = $this->keywordCoverage($citaRaw, $citaCitaKeywords);
            
            // Combine both: if category matches OR raw keywords match
            $citaMatchScore = max($categoryMatches ? 1.0 : 0.0, $rawCoverage);
            
            $p_cita_cita = 0.20 + ($citaMatchScore * (($matchProb['cita_cita'] ?? 0.85) - 0.20));
            $p_cita_cita = max(0.05, min(0.98, $p_cita_cita));

            // 5. Likelihood untuk Prestasi (bertingkat: tinggi/menengah/dasar/minimal)
            $p_prestasi = $this->scorePrestasiLikelihood(
                $prestasiAnalysis,
                $citaCitaKeywords,
                $matchProb['prestasi'] ?? 0.65
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
        $recommendationId = null;
        if ($user) {
            $recommendation = Recommendation::create([
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
            $recommendationId = $recommendation->id;
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

        return view('rekomendasi.hasil', compact('hasilAkhir', 'katNilai', 'average', 'minatRaw', 'minatMapped', 'citaRaw', 'citaMapped', 'prefStudi', 'prestasiScore', 'topJurusan', 'isPrestasiFilled', 'recommendationId'));
    }

    /**
     * Pemetaan minat ke kategori yang dipahami sistem
     */
    /**
     * Normalize text untuk better keyword matching
     * Menangani variasi kata seperti programmer/programming, coding/code
     */
    private function normalizeText(string $text): string
    {
        $text = strtolower(trim($text));
        
        // Simple stemming untuk common variations
        $replacements = [
            'programmer' => 'programming',
            'coder' => 'coding',
            'code' => 'coding',
            'codes' => 'coding',
            'develop' => 'development',
            'developer' => 'development',
            'develops' => 'development',
            'manager' => 'manajemen',
            'manages' => 'manajemen',
            'doctor' => 'dokter',
            'doctors' => 'dokter',
            'nurse' => 'perawat',
            'nurses' => 'perawat',
            'engineer' => 'teknik',
            'engineers' => 'teknik',
            'farming' => 'pertanian',
            'farmer' => 'pertanian',
            'farmers' => 'pertanian',
            'business' => 'bisnis',
            'businessmen' => 'bisnis',
        ];
        
        foreach ($replacements as $from => $to) {
            $text = str_replace($from, $to, $text);
        }
        
        return $text;
    }

    private function getMinatMappingTree(): array
    {
        return [
            'Logika & Komputer' => [
                'coding', 'pemrograman', 'programming', 'komputer', 'informatika', 'software', 'aplikasi', 'web', 'website', 'data', 'ai', 'it', 'database', 'jaringan', 'cybersecurity',
                'teknologi', 'internet', 'gadget', 'game', 'gaming', 'robot', 'cyber', 'sistem', 'desain grafis', 'grafika', 'digital', 'animasi', 'editing', 'video', 'multimedia', 'ui/ux', 'komputerisasi'
            ],
            'Alam & Tanaman' => [
                'pertanian', 'tanaman', 'kebun', 'sawah', 'hortikultura', 'agribisnis', 'petani', 'panen', 'tanah', 'lingkungan', 'kehutanan', 'budidaya',
                'hewan', 'peternakan', 'ternak', 'veteriner', 'botani', 'zoologi', 'biologi', 'pangan', 'perikanan', 'kelautan', 'ikan', 'pupuk', 'hidroponik', 'agronomi', 'alam'
            ],
            'Pelayanan & Kesehatan' => [
                'kesehatan', 'medis', 'dokter', 'perawat', 'farmasi', 'gizi', 'klinik', 'rumah sakit', 'terapi', 'keperawatan', 'laboratorium',
                'makanan', 'obat', 'apoteker', 'kebidanan', 'bidan', 'biomedis', 'psikologi', 'psikiater', 'konseling', 'sosial', 'pelayanan', 'bantuan'
            ],
            'Manajemen & Bisnis' => [
                'bisnis', 'manajemen', 'usaha', 'wirausaha', 'entrepreneur', 'marketing', 'keuangan', 'akuntansi', 'ekonomi', 'penjualan', 'perbankan',
                'investasi', 'saham', 'bank', 'sales', 'pemasaran', 'administrasi', 'toko', 'dagang', 'perdagangan', 'kantor'
            ],
            'Mesin & Listrik' => [
                'mesin', 'listrik', 'teknik', 'otomasi', 'elektronik', 'mekanik', 'industri', 'bengkel', 'las', 'motor', 'robotik',
                'otomotif', 'mobil', 'alat berat', 'pabrik', 'panel', 'instalasi', 'solder', 'logam', 'konstruksi', 'sipil', 'arsitektur'
            ],
        ];
    }

    private function getPreferensiStudiMappingTree(): array
    {
        return [
            'Sains & Teknologi' => ['sains & teknologi', 'sains dan teknologi', 'saintek', 'science and technology'],
            'Pertanian & Lingkungan' => ['pertanian & lingkungan', 'pertanian dan lingkungan', 'agriculture and environment', 'agro lingkungan'],
            'Kesehatan & Ilmu Hayat' => ['kesehatan & ilmu hayat', 'kesehatan dan ilmu hayat', 'health and life science', 'ilmu hayat'],
            'Bisnis & Manajemen' => ['bisnis & manajemen', 'bisnis dan manajemen', 'business and management'],
            'Sosial & Humaniora' => ['sosial & humaniora', 'sosial dan humaniora', 'social and humanities', 'soshum'],
        ];
    }

    private function mapMinat(string $minatRaw): array
    {
        // Normalize text untuk better matching
        $minatNormalized = $this->normalizeText($minatRaw);
        $categoryKeywords = $this->getMinatMappingTree();
        
        // Score setiap kategori berdasarkan keyword coverage
        $scores = [];
        foreach ($categoryKeywords as $category => $keywords) {
            $scores[$category] = $this->keywordCoverage($minatNormalized, $keywords);
        }
        
        // Return kategori dengan coverage tertinggi
        $bestCategory = null;
        $maxScore = 0;
        foreach ($scores as $category => $score) {
            if ($score > $maxScore) {
                $maxScore = $score;
                $bestCategory = $category;
            }
        }

        // Ketatkan validasi: jika skor terlalu rendah maka dianggap typo/terlalu umum.
        if ($maxScore < 0.12 || $bestCategory === null) {
            return [
                'is_valid' => false,
                'category' => null,
                'score' => $maxScore,
                'suggestion' => $this->closestKeywordSuggestion($minatNormalized, $categoryKeywords),
            ];
        }

        return [
            'is_valid' => true,
            'category' => $bestCategory,
            'score' => $maxScore,
            'suggestion' => null,
        ];
    }

    private function mapPreferensiStudi(string $prefRaw): array
    {
        $input = strtolower(trim($prefRaw));
        $tree = $this->getPreferensiStudiMappingTree();

        foreach ($tree as $canonical => $aliases) {
            $normalizedAliases = array_map(static fn($value) => strtolower(trim($value)), $aliases);
            if (in_array($input, $normalizedAliases, true)) {
                return [
                    'is_valid' => true,
                    'canonical' => $canonical,
                    'suggestion' => null,
                ];
            }
        }

        // Cari saran opsi terdekat berdasarkan canonical name.
        $closest = null;
        $closestDistance = PHP_INT_MAX;
        foreach (array_keys($tree) as $candidate) {
            $distance = levenshtein($input, strtolower($candidate));
            if ($distance < $closestDistance) {
                $closestDistance = $distance;
                $closest = $candidate;
            }
        }

        return [
            'is_valid' => false,
            'canonical' => null,
            'suggestion' => $closestDistance <= 10 ? $closest : null,
        ];
    }

    private function closestKeywordSuggestion(string $text, array $categoryKeywords): ?string
    {
        $tokens = preg_split('/[^a-z0-9]+/i', strtolower($text)) ?: [];
        $tokens = array_values(array_filter($tokens, static fn($token) => strlen($token) >= 3));
        if (empty($tokens)) {
            return null;
        }

        $allKeywords = [];
        foreach ($categoryKeywords as $keywords) {
            foreach ($keywords as $keyword) {
                $allKeywords[strtolower($keyword)] = true;
            }
        }

        $bestKeyword = null;
        $bestDistance = PHP_INT_MAX;

        foreach ($tokens as $token) {
            foreach (array_keys($allKeywords) as $keyword) {
                $distance = levenshtein($token, $keyword);
                if ($distance < $bestDistance) {
                    $bestDistance = $distance;
                    $bestKeyword = $keyword;
                }
            }
        }

        return $bestDistance <= 2 ? $bestKeyword : null;
    }

    /**
     * Pemetaan cita-cita ke kategori jurusan yang relevan
     * Mengevaluasi input cita-cita dengan lebih detail
     */
    private function mapCitaCita(string $citaRaw): array
    {
        // Normalize text untuk better matching
        $citaNormalized = $this->normalizeText($citaRaw);
        
        // Map cita-cita ke category berdasarkan keywords
        $careerCategories = [
            'IT & Software' => [
                'programmer', 'programming', 'developer', 'development', 'software', 'coding', 'web', 'database', 'it', 'scientist', 'analyst', 'data', 'cloud', 'architect', 'cybersecurity', 'security', 'devops', 'backend', 'frontend', 'fullstack', 'sysadmin', 'network admin', 'cto', 'tech lead', 'ai', 'machine learning',
                'analis data', 'pembuat web', 'data scientist', 'administrator jaringan', 'hacker', 'keamanan siber', 'gamedev', 'game developer'
            ],
            'Agriculture' => [
                'petani', 'pertanian', 'agribisnis', 'kebun', 'ternak', 'peternak', 'agronomi', 'farming', 'livestock', 'agronomist', 'farmer', 'farm manager', 'plantation', 'crops specialist', 'agritech', 'horticultural', 'agricultural scientist', 'soil scientist', 'breeding specialist', 'extension officer', 'crop consultant', 'forestry', 'fishery manager',
                'penyuluh pertanian', 'pengusaha kebun', 'dokter hewan', 'peternak sapi', 'peternak ayam', 'petani modern'
            ],
            'Healthcare' => [
                'dokter', 'perawat', 'medis', 'gizi', 'terapis', 'farmasi', 'kesehatan', 'nursing', 'therapist', 'pharmacist', 'nutritionist', 'clinician', 'public health', 'midwife', 'radiologist', 'dentist', 'nurse', 'surgeon', 'diagnostician', 'laboratory technician', 'paramedic', 'health educator', 'epidemiologist', 'wellness coach',
                'bidan', 'apoteker', 'ahli gizi', 'analis kesehatan', 'mantri', 'dokter spesialis', 'perawat kesehatan'
            ],
            'Business' => [
                'entrepreneur', 'manager', 'marketing', 'sales', 'akuntan', 'keuangan', 'bisnis', 'accountant', 'consultant', 'finance', 'cfo', 'ceo', 'director', 'treasurer', 'auditor', 'trader', 'investor', 'controller', 'operations manager', 'strategic planner', 'business analyst', 'supply chain manager', 'hr manager', 'corporate executive',
                'pengusaha', 'wirausahawan', 'akuntan publik', 'pemasar', 'direktur', 'staf administrasi', 'manajer keuangan', 'bankir', 'sales representative'
            ],
            'Engineering' => [
                'teknik', 'engineer', 'mesin', 'listrik', 'bengkel', 'maintenance', 'industri', 'technician', 'constructor', 'mechanical engineer', 'electrical engineer', 'automation', 'supervisor', 'foreman', 'technologist', 'specialist', 'civil engineer', 'welding specialist', 'hydraulics engineer', 'power engineer', 'manufacturing engineer', 'maintenance supervisor',
                'teknisi listrik', 'mekanik mobil', 'operator mesin', 'drafter', 'mandor', 'arsitek', 'ahli las', 'tukang bubut'
            ],
            'Communication' => [
                'jurnalis', 'komunikator', 'presenter', 'content', 'pariwisata', 'hospitality', 'tour', 'guide', 'public relations', 'ambassador', 'interpreter', 'diplomat', 'broadcaster', 'event organizer', 'marketing specialist', 'pr specialist', 'copywriter', 'social media manager', 'travel consultant', 'hospitality manager', 'cultural ambassador', 'media producer',
                'pembuat konten', 'pemandu wisata', 'penerjemah', 'wartawan', 'penulis berita', 'humas', 'resepsionis'
            ],
        ];
        
        // Score setiap kategori
        $scores = [];
        foreach ($careerCategories as $category => $keywords) {
            $scores[$category] = $this->keywordCoverage($citaNormalized, $keywords);
        }
        
        // Return kategori dengan coverage tertinggi
        $bestCategory = null;
        $maxScore = 0;
        foreach ($scores as $category => $score) {
            if ($score > $maxScore) {
                $maxScore = $score;
                $bestCategory = $category;
            }
        }
        
        if ($maxScore < 0.12 || $bestCategory === null) {
            return [
                'is_valid' => false,
                'category' => null,
                'score' => $maxScore,
                'suggestion' => $this->closestKeywordSuggestion($citaNormalized, $careerCategories),
            ];
        }
        
        return [
            'is_valid' => true,
            'category' => $bestCategory,
            'score' => $maxScore,
            'suggestion' => null,
        ];
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
     * Digunakan untuk cita-cita dan prestasi scoring.
     */
    private function scoreKeywordLikelihood(string $text, array $keywords, float $matchProb): float
    {
        if (empty($keywords)) {
            return 0.50;
        }

        $coverage = $this->keywordCoverage($text, $keywords);

        // Log untuk debugging
        if ($coverage > 0) {
            \Log::debug('Keyword Coverage', [
                'text' => $text,
                'keywords_count' => count($keywords),
                'coverage' => $coverage,
                'match_prob' => $matchProb,
            ]);
        }

        // Base 0.2 agar tidak 0 total, lalu naik proporsional coverage.
        $likelihood = 0.20 + ($coverage * ($matchProb - 0.20));

        return max(0.05, min(0.98, $likelihood));
    }

    private function scoreMinatLikelihood(string $minatRaw, string $minatMapped, string $targetMinat, float $matchProb): float
    {
        // Expanded keyword bank dengan lebih banyak variasi
        $keywordBank = [
            'Logika & Komputer' => ['coding', 'programming', 'komputer', 'software', 'web', 'data', 'ai', 'digital', 'aplikasi', 'developer', 'coding', 'programer', 'it', 'database', 'network'],
            'Alam & Tanaman' => ['pertanian', 'tanaman', 'kebun', 'sawah', 'alam', 'peternakan', 'agribisnis', 'hewan', 'ternak', 'panen', 'tani', 'petani', 'hortikultura'],
            'Pelayanan & Kesehatan' => ['kesehatan', 'medis', 'gizi', 'perawat', 'dokter', 'klinik', 'rumah sakit', 'farmasi', 'terapis', 'kesehatan masyarakat', 'kesehatan', 'sehat', 'rawat'],
            'Manajemen & Bisnis' => ['bisnis', 'usaha', 'marketing', 'keuangan', 'manajemen', 'akuntansi', 'entrepreneur', 'sales', 'marketing', 'penjualan', 'perbankan', 'akuntan'],
            'Mesin & Listrik' => ['mesin', 'listrik', 'teknik', 'otomasi', 'elektronik', 'mekanik', 'industri', 'bengkel', 'las', 'motor', 'teknis'],
        ];

        $targetKeywords = $keywordBank[$targetMinat] ?? [];
        $coverage = $this->keywordCoverage($minatRaw, $targetKeywords);
        
        // Perfect match jika mapped minat sama dengan target
        $categoryMatch = ($minatMapped === $targetMinat) ? 1.0 : 0.0;

        // Weighted combination: kategori match lebih penting (60%) daripada coverage (40%)
        $combined = (0.6 * $categoryMatch) + (0.4 * $coverage);
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

        // Split text into word tokens
        $tokens = preg_split('/[^a-z0-9]+/i', $text) ?: [];
        $tokens = array_values(array_filter($tokens, static fn($t) => strlen($t) >= 3));

        $matched = 0;
        foreach (array_unique($keywords) as $keyword) {
            $keywordLower = strtolower($keyword);
            if ($keywordLower === '') continue;

            // Direct containment with word boundaries
            if (preg_match('/\b' . preg_quote($keywordLower, '/') . '\b/i', $text)) {
                $matched++;
                continue;
            }

            // Word-level prefix/substring match only for keywords of length >= 4
            if (strlen($keywordLower) >= 4) {
                foreach ($tokens as $token) {
                    if (str_contains($keywordLower, $token) || str_contains($token, $keywordLower)) {
                        $matched++;
                        break;
                    }
                }
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

    private function getBobotMapelForKelompok(array $bobotMapel, string $kelompokAsal): array
    {
        $kelompokKey = strtoupper($kelompokAsal) === 'IPS' ? 'ips' : 'ipa';
        $subjects = $kelompokKey === 'ipa'
            ? ['mtk', 'fisika', 'kimia', 'biologi']
            : ['ekonomi', 'geografi', 'sosiologi', 'sejarah'];

        if (isset($bobotMapel['ipa']) || isset($bobotMapel['ips'])) {
            $groupValues = $bobotMapel[$kelompokKey] ?? [];
            return $this->normalizeBobotGroup(is_array($groupValues) ? $groupValues : [], $subjects);
        }

        $legacyValues = array_intersect_key($bobotMapel, array_flip($subjects));

        return $this->normalizeBobotGroup($legacyValues, $subjects);
    }

    private function normalizeBobotGroup(array $values, array $subjects): array
    {
        $normalized = [];

        foreach ($subjects as $subject) {
            $value = $values[$subject] ?? null;
            $normalized[$subject] = is_numeric($value) ? (float) $value : 0.0;
        }

        return $normalized;
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