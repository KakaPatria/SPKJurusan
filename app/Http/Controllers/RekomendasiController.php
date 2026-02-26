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

    public function proses(Request $request)
    {
        // --- VALIDATION ---
        // Tentukan kelompok asal siswa
        $user = Auth::user();
        $kelompok = $user->kelompok_asal ?? 'IPS';

        // Validasi berbeda untuk IPA dan IPS
        $baseRules = [
            'minat' => 'required|string|max:255',
            'cita_cita' => 'required|string|max:255',
            'pref_studi' => 'required|in:Sains & Teknologi,Pertanian & Lingkungan,Kesehatan & Ilmu Hayat,Bisnis & Manajemen,Sosial & Humaniora',
            'prestasi' => 'nullable|string|max:255',
        ];

        if ($kelompok === 'IPA') {
            $nilaiRules = [
                'mtk' => 'required|numeric|between:0,100',
                'fisika' => 'required|numeric|between:0,100',
                'kimia' => 'required|numeric|between:0,100',
                'biologi' => 'required|numeric|between:0,100',
            ];
        } else {
            $nilaiRules = [
                'ekonomi' => 'required|numeric|between:0,100',
                'geografi' => 'required|numeric|between:0,100',
                'sosiologi' => 'required|numeric|between:0,100',
                'sejarah' => 'required|numeric|between:0,100',
            ];
        }

        $request->validate(array_merge($baseRules, $nilaiRules));

        // --- 1. SKOR NILAI AKADEMIK (40%) - dikumpulkan dulu, dihitung per jurusan ---
        if ($kelompok === 'IPA') {
            $scores = $request->only(['mtk', 'fisika', 'kimia', 'biologi']);
        } else {
            $scores = $request->only(['ekonomi', 'geografi', 'sosiologi', 'sejarah']);
        }
        $validScores = array_filter($scores, fn($v) => !is_null($v) && $v !== '');
        $average = count($validScores) > 0 ? array_sum($validScores) / count($validScores) : 0;

        // Label nilai untuk tampilan
        if ($average >= 85) {
            $katNilai = 'Tinggi';
        } elseif ($average >= 70) {
            $katNilai = 'Sedang';
        } else {
            $katNilai = 'Rendah';
        }

        // --- 2. INPUT SISWA ---
        $minatRaw = strtolower(trim($request->minat ?? ''));
        $citaRaw = strtolower(trim($request->cita_cita ?? ''));
        $prefStudi = $request->pref_studi ?? 'Sains & Teknologi';
        $prestasiRaw = strtolower(trim($request->prestasi ?? ''));
        $prestasiScore = $this->scorePrestasiScore($prestasiRaw);

        // --- 3. GRADUATED SCORING PER JURUSAN ---
        $jurusanList = PolijeMajor::all();
        $hasilAkhir = [];

        // Bobot kriteria
        $W_NILAI = 0.40;
        $W_MINAT = 0.35;
        $W_PREF = 0.15;
        $W_CITA = 0.05;
        $W_PRESTASI = 0.05;

        foreach ($jurusanList as $jurusan) {
            $keywords = $jurusan->keywords ?? [];
            $prefList = $jurusan->preferensi_studi ?? [];
            $bobotMapel = $jurusan->bobot_mapel ?? [];

            // --- Skor Nilai: per-jurusan weighted ---
            $skorNilai = $this->hitungSkorNilaiPerJurusan($scores, $bobotMapel, $average);

            // --- Skor Minat: partial keyword matching ---
            $skorMinat = $this->hitungKecocokanKeyword($minatRaw, $keywords);

            // --- Skor Cita-cita: partial keyword matching ---
            $skorCita = $this->hitungKecocokanKeyword($citaRaw, $keywords);

            // --- Skor Preferensi Studi ---
            if (in_array($prefStudi, $prefList)) {
                $skorPref = 1.0;
            } elseif (!empty($prefList)) {
                $skorPref = 0.3; // Tidak cocok tapi jurusan punya preferensi
            } else {
                $skorPref = 0.5; // Jurusan tidak mendefinisikan preferensi
            }

            // --- Skor Prestasi (sama untuk semua jurusan) ---
            $skorPrestasi = $prestasiScore;

            // --- Hitung skor akhir ---
            $skorAkhir = ($W_NILAI * $skorNilai) +
                         ($W_MINAT * $skorMinat) +
                         ($W_PREF  * $skorPref) +
                         ($W_CITA  * $skorCita) +
                         ($W_PRESTASI * $skorPrestasi);

            $hasilAkhir[] = [
                'jurusan' => $jurusan->nama_jurusan,
                'skor' => round($skorAkhir, 4),
                'detail' => [
                    'nilai' => round($skorNilai, 4),
                    'minat' => round($skorMinat, 4),
                    'pref' => round($skorPref, 4),
                    'cita' => round($skorCita, 4),
                    'prestasi' => round($skorPrestasi, 4),
                ],
            ];
        }

        // Sort berdasarkan skor tertinggi
        usort($hasilAkhir, fn($a, $b) => $b['skor'] <=> $a['skor']);

        // Simpan ke database
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

        // Simpan ke session untuk chatbot
        if (count($hasilAkhir) > 0) {
            $topResult = $hasilAkhir[0];
            session([
                'recomendation_data' => [
                    'jurusan' => $topResult['jurusan'],
                    'skor' => $topResult['skor'],
                    'nilai' => $katNilai,
                    'minat' => $request->minat,
                    'pref_studi' => $prefStudi,
                ]
            ]);
        }

        // Load top jurusan from DB for deskripsi & prospek_kerja
        $topJurusan = null;
        if (count($hasilAkhir) > 0) {
            $topJurusan = PolijeMajor::where('nama_jurusan', $hasilAkhir[0]['jurusan'])->first();
        }

        return view('rekomendasi.hasil', compact('hasilAkhir', 'katNilai', 'average', 'prefStudi', 'prestasiScore', 'topJurusan'));
    }

    /**
     * Hitung skor nilai akademik per jurusan dengan bobot mapel
     * Jika jurusan punya bobot_mapel, hitung weighted average
     * Jika tidak, gunakan rata-rata biasa
     */
    private function hitungSkorNilaiPerJurusan(array $scores, array $bobotMapel, float $averageFallback): float
    {
        // Jika tidak ada bobot khusus, pakai rata-rata biasa
        if (empty($bobotMapel)) {
            return min($averageFallback / 100, 1.0);
        }

        $weightedSum = 0;
        $totalWeight = 0;

        foreach ($bobotMapel as $mapel => $bobot) {
            $nilai = floatval($scores[$mapel] ?? 0);
            $weightedSum += $nilai * $bobot;
            $totalWeight += $bobot;
        }

        // Untuk mapel yang ada di scores tapi tidak di bobot, beri bobot kecil
        foreach ($scores as $mapel => $nilai) {
            if (!isset($bobotMapel[$mapel]) && !is_null($nilai) && $nilai !== '') {
                $weightedSum += floatval($nilai) * 0.1;
                $totalWeight += 0.1;
            }
        }

        if ($totalWeight <= 0) {
            return min($averageFallback / 100, 1.0);
        }

        $weightedAvg = $weightedSum / $totalWeight;
        return min($weightedAvg / 100, 1.0);
    }

    /**
     * Hitung kecocokan teks input dengan array keywords jurusan (graduated)
     * Returns 0.0 - 1.0
     */
    private function hitungKecocokanKeyword(string $inputText, array $keywords): float
    {
        if (empty($keywords) || empty($inputText)) {
            return 0.0;
        }

        $matchCount = 0;
        $inputWords = preg_split('/[\s,;.\/\-]+/', $inputText);

        foreach ($keywords as $keyword) {
            $kw = strtolower(trim($keyword));
            if (empty($kw)) continue;

            // Check if keyword appears in any input word (partial match)
            foreach ($inputWords as $word) {
                if (empty($word)) continue;
                // Match if input word contains keyword or keyword contains input word (min 3 chars)
                if (stripos($inputText, $kw) !== false ||
                    (strlen($word) >= 3 && stripos($kw, $word) !== false)) {
                    $matchCount++;
                    break;
                }
            }
        }

        // Graduated score: ratio of matched keywords
        // Use sqrt to give more credit for partial matches
        $ratio = $matchCount / count($keywords);
        return min(sqrt($ratio) * 0.9 + ($matchCount > 0 ? 0.1 : 0), 1.0);
    }

    /**
     * Scoring prestasi berdasarkan keyword
     */
    private function scorePrestasiScore(string $prestasiRaw): float
    {
        if (empty($prestasiRaw)) {
            return 0.0;
        }

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