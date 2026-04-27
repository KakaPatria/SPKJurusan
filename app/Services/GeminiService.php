<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use App\Models\PolijeMajor;

class GeminiService
{
    protected $apiKey;
    protected $backendUrl;
    protected $backendToken;
    protected $baseUrl = 'https://generativelanguage.googleapis.com/v1beta/models/';
    
    // Model priority list - try each if previous fails
    protected $models = [
        'gemini-2.5-flash',
        'gemini-2.0-flash',
        'gemini-2.0-flash-lite',
    ];

    public function __construct()
    {
        $this->apiKey = config('services.gemini.api_key');
        $this->backendUrl = rtrim((string) config('services.gemini.backend_url', ''), '/');
        $this->backendToken = (string) config('services.gemini.backend_token', '');
    }

    public function chat($message, $context = [], $chatHistory = [])
    {
        try {
            if (empty($this->apiKey)) {
                return [
                    'success' => false,
                    'message' => 'Layanan chatbot belum siap digunakan. Silakan hubungi pengelola sistem.'
                ];
            }

            // Intent router: mode perbandingan jurusan ditangani terstruktur agar konsisten.
            if (($context['intent'] ?? '') === 'compare_majors') {
                $comparison = $this->buildStructuredComparisonResponse($message, $context);
                if (!empty($comparison)) {
                    return [
                        'success' => true,
                        'message' => $comparison,
                    ];
                }
            }

            $systemPrompt = $this->buildSystemPrompt($context);

            // Build multi-turn conversation for Gemini
            $contents = [];

            // First message: system prompt + first user message (or standalone if no history)
            if (!empty($chatHistory)) {
                // Inject system prompt into first user turn
                $firstUserMsg = $systemPrompt . "\n\n(Percakapan dimulai)\n\nSiswa: " . ($chatHistory[0]['text'] ?? 'Halo');
                $contents[] = ['role' => 'user', 'parts' => [['text' => $firstUserMsg]]];

                // Add rest of history as alternating user/model turns
                for ($i = 1; $i < count($chatHistory); $i++) {
                    $role = $chatHistory[$i]['role'] === 'user' ? 'user' : 'model';
                    $contents[] = ['role' => $role, 'parts' => [['text' => $chatHistory[$i]['text']]]];
                }

                // Add current message
                $contents[] = ['role' => 'user', 'parts' => [['text' => $message]]];
            } else {
                // No history, single message with system prompt
                $fullMessage = $systemPrompt . "\n\nSiswa: " . $message;
                $contents[] = ['role' => 'user', 'parts' => [['text' => $fullMessage]]];
            }

            $payload = [
                'contents' => $contents,
                'generationConfig' => [
                    'temperature' => 0.8,
                    'maxOutputTokens' => 4096,
                    'topP' => 0.95,
                    'topK' => 40
                ]
            ];

            // Mode Python-only: chatbot wajib melalui backend Python.
            if (empty($this->backendUrl)) {
                return [
                    'success' => false,
                    'message' => 'Layanan chatbot belum siap digunakan saat ini. Silakan coba kembali beberapa saat lagi.',
                ];
            }

            $proxyResponse = $this->sendViaPythonBackend($payload);
            if (($proxyResponse['success'] ?? false) === true) {
                return $proxyResponse;
            }

            Log::error('Python Gemini backend failed in Python-only mode', [
                'error' => $proxyResponse['message'] ?? 'unknown',
            ]);

            return [
                'success' => false,
                'message' => $proxyResponse['message'] ?? 'Maaf, layanan chatbot sedang mengalami gangguan. Silakan coba lagi.',
            ];

        } catch (\Exception $e) {
            Log::error('Gemini Service Exception', [
                'error' => $e->getMessage(),
                'file' => $e->getFile(),
                'line' => $e->getLine()
            ]);
            
            return [
                'success' => false,
                'message' => 'Maaf, layanan chatbot sedang mengalami kendala. Silakan coba kembali beberapa saat lagi.',
            ];
        }
    }

    protected function sendViaPythonBackend(array $payload): array
    {
        try {
            $url = $this->backendUrl . '/api/chat';
            $request = Http::timeout(35)->withHeaders([
                'Content-Type' => 'application/json',
                'Accept' => 'application/json',
            ]);

            if (!empty($this->backendToken)) {
                $request = $request->withToken($this->backendToken);
            }

            $response = $request->post($url, [
                'payload' => $payload,
                'models' => $this->models,
            ]);

            if (!$response->successful()) {
                $status = $response->status();

                $mappedMessage = match ($status) {
                    401, 403 => 'Maaf, layanan chatbot sedang dibatasi sementara. Silakan coba kembali nanti.',
                    422 => 'Pesan belum dapat diproses. Silakan perjelas pertanyaan Anda dan coba lagi.',
                    429 => 'Layanan chatbot sedang ramai digunakan. Silakan tunggu sebentar lalu coba lagi.',
                    500, 502, 503, 504 => 'Maaf, layanan chatbot sedang mengalami gangguan. Silakan coba kembali beberapa saat lagi.',
                    default => 'Layanan chatbot sedang tidak tersedia. Silakan coba kembali nanti.',
                };

                return [
                    'success' => false,
                    'message' => $mappedMessage,
                ];
            }

            $data = $response->json();
            if (($data['success'] ?? false) === true && !empty($data['message'])) {
                return [
                    'success' => true,
                    'message' => $data['message'],
                ];
            }

            return [
                'success' => false,
                'message' => $data['message'] ?? 'Maaf, layanan chatbot belum dapat memberikan jawaban saat ini.',
            ];
        } catch (\Exception $e) {
            Log::warning('Python backend exception', [
                'error' => $e->getMessage(),
            ]);

            return [
                'success' => false,
                'message' => 'Koneksi layanan chatbot sedang bermasalah. Silakan coba kembali beberapa saat lagi.',
            ];
        }
    }

    protected function getFallbackResponse($message, $context = [], $chatHistory = [])
    {
        $jurusan = $context['recommendation'] ?? null;
        $score = isset($context['score']) ? floatval($context['score']) : 0;
        $hasRecommendation = !empty($jurusan);

        if (($context['intent'] ?? '') === 'compare_majors') {
            $comparison = $this->buildStructuredComparisonResponse($message, $context);
            if (!empty($comparison)) {
                return [
                    'success' => true,
                    'message' => $comparison,
                ];
            }
        }

        $major = null;
        if ($hasRecommendation) {
            $major = PolijeMajor::where('nama_jurusan', $jurusan)->first();
        }
        $majorDesc = $major->deskripsi ?? null;
        $majorProspek = $major->prospek_kerja ?? null;

        // Keyword-based responses
        $messageLower = strtolower($message);
        $lastAiMessage = $this->getLastAssistantMessage($chatHistory);

        // Tangani pertanyaan lanjutan agar tetap nyambung saat fallback aktif
        if ($this->isFollowUpMessage($messageLower)) {
            if (
                strpos($messageLower, 'jelaskan semua') !== false ||
                strpos($messageLower, 'semua jurusan') !== false ||
                strpos($messageLower, 'satu satu') !== false ||
                strpos($messageLower, 'satu-satu') !== false
            ) {
                return [
                    'success' => true,
                    'message' => $this->buildAllMajorsResponse($hasRecommendation, $jurusan, $score),
                ];
            }

            if ($hasRecommendation) {
                $parts = [];
                $parts[] = "Menindaklanjuti pembahasan sebelumnya, fokus utama Anda saat ini tetap pada jurusan \"{$jurusan}\" dengan skor kesesuaian {$score}%.";
                if (!empty($majorDesc)) {
                    $parts[] = "Fokus pembelajaran jurusan: {$majorDesc}";
                }
                if (!empty($majorProspek)) {
                    $parts[] = "Prospek kerja yang relevan: {$majorProspek}";
                }
                $parts[] = "Jika Anda berkenan, saya dapat lanjutkan secara bertahap: (1) kompetensi yang harus dipersiapkan, (2) mata kuliah inti, dan (3) perbandingan dengan alternatif jurusan lain.";

                return [
                    'success' => true,
                    'message' => implode(' ', $parts),
                ];
            }
        }

        if (strpos($messageLower, 'halo') !== false || strpos($messageLower, 'hai') !== false || strpos($messageLower, 'hallo') !== false || strpos($messageLower, 'hi') !== false) {
            $hour = (int) now()->format('H');
            if ($hour >= 3 && $hour < 11) {
                $sapaan = 'Selamat pagi';
            } elseif ($hour >= 11 && $hour < 15) {
                $sapaan = 'Selamat siang';
            } elseif ($hour >= 15 && $hour < 18) {
                $sapaan = 'Selamat sore';
            } else {
                $sapaan = 'Selamat malam';
            }
            $greeting = "{$sapaan}. Saya adalah konselor BK virtual SMA Bima Ambulu yang siap membantu Anda dalam pemilihan jurusan kuliah. ";
            if ($hasRecommendation) {
                $greeting .= "Berdasarkan data yang tersedia, Anda telah memperoleh rekomendasi jurusan \"{$jurusan}\" dengan skor kesesuaian {$score}%. Apakah Anda ingin membahas lebih lanjut mengenai jurusan tersebut, atau ada pertanyaan lain yang ingin disampaikan?";
            } else {
                $greeting .= "Anda dapat mengajukan pertanyaan seputar jurusan kuliah, prospek karier, maupun panduan dalam memilih jurusan yang tepat. Silakan sampaikan pertanyaan Anda.";
            }
            return ['success' => true, 'message' => $greeting];
        }
        
        if (strpos($messageLower, 'kenapa') !== false || strpos($messageLower, 'mengapa') !== false) {
            if ($hasRecommendation) {
                return [
                    'success' => true,
                    'message' => "Jurusan \"{$jurusan}\" direkomendasikan karena paling sesuai dengan kombinasi profil Anda. Skor kesesuaian {$score}% diperoleh dari analisis lima atribut: nilai akademik, minat, preferensi studi lanjutan, cita-cita, dan prestasi (jika diisi). Berdasarkan data tersebut, jurusan ini memiliki kecocokan paling kuat dibanding alternatif lain pada sesi analisis Anda."
                ];
            }
            return [
                'success' => true,
                'message' => "Untuk dapat menjawab pertanyaan tersebut secara akurat, disarankan agar Anda terlebih dahulu melakukan analisis rekomendasi. Melalui proses tersebut, sistem akan mencocokkan profil Anda dengan sembilan jurusan yang tersedia di Polije. Silakan akses menu Analisis Rekomendasi pada halaman dashboard."
            ];
        }
        
        if (
            strpos($messageLower, 'apa itu') !== false ||
            strpos($messageLower, 'jurusan tersebut') !== false ||
            strpos($messageLower, 'jelaskan jurusan') !== false ||
            strpos($messageLower, 'maksud jurusan') !== false
        ) {
            if ($hasRecommendation) {
                $parts = [];
                $parts[] = "Jurusan \"{$jurusan}\" adalah bidang yang berfokus pada kompetensi terapan sesuai kebutuhan dunia kerja.";
                if (!empty($majorDesc)) {
                    $parts[] = "Gambaran jurusan: {$majorDesc}";
                }
                if (!empty($majorProspek)) {
                    $parts[] = "Prospek kerja utama: {$majorProspek}";
                }
                $parts[] = "Jika Anda berkenan, saya dapat lanjutkan dengan mata kuliah inti, kemampuan yang perlu dipersiapkan, dan alasan kesesuaiannya dengan profil Anda.";

                return [
                    'success' => true,
                    'message' => implode(' ', $parts),
                ];
            }

            return [
                'success' => true,
                'message' => "Saya dapat menjelaskan jurusan secara rinci. Agar lebih tepat sasaran, sebutkan nama jurusan yang ingin Anda ketahui, atau jalankan Analisis Rekomendasi terlebih dahulu agar saya menjelaskan jurusan yang paling sesuai dengan profil Anda.",
            ];
        }

        if (strpos($messageLower, 'prospek') !== false || strpos($messageLower, 'karir') !== false || strpos($messageLower, 'kerja') !== false) {
            if ($hasRecommendation) {
                return [
                    'success' => true,
                    'message' => "Jurusan \"{$jurusan}\" memiliki prospek karier yang baik. " . (!empty($majorProspek) ? "Contoh prospek kerja: {$majorProspek}. " : "Lulusan dapat bekerja pada bidang yang relevan dengan kompetensi jurusan. ") . "Jika Anda berkenan, saya dapat jelaskan jalur karier dari level awal sampai pengembangan jangka panjang."
                ];
            }
            return [
                'success' => true,
                'message' => "Setiap jurusan memiliki prospek karier yang berbeda. Sebagai contoh, lulusan Teknologi Informasi dapat berkarier sebagai programmer atau developer, lulusan Kesehatan dapat menjadi tenaga medis profesional, dan lulusan Bisnis dapat menempuh karier di bidang manajerial atau kewirausahaan. Jurusan mana yang ingin Anda ketahui lebih lanjut? Saya akan memberikan informasi yang lebih terperinci."
            ];
        }
        
        if (strpos($messageLower, 'bingung') !== false || strpos($messageLower, 'galau') !== false || strpos($messageLower, 'tidak tahu') !== false || strpos($messageLower, 'gak tau') !== false) {
            return [
                'success' => true,
                'message' => "Perasaan bingung dalam memilih jurusan adalah hal yang wajar dan dialami oleh banyak siswa. Untuk membantu memperjelas arah pilihan Anda, cobalah menjawab beberapa pertanyaan berikut: (1) Mata pelajaran apa yang paling Anda kuasai atau minati? (2) Kegiatan apa yang membuat Anda bersemangat? (3) Apa cita-cita atau tujuan karier Anda? Dari jawaban tersebut, kita dapat mulai mengidentifikasi jurusan yang sesuai. Selain itu, Anda juga dapat memanfaatkan fitur Analisis Rekomendasi di halaman dashboard untuk mendapatkan rekomendasi secara otomatis."
            ];
        }

        if (strpos($messageLower, 'skill') !== false || strpos($messageLower, 'kemampuan') !== false) {
            if ($hasRecommendation) {
                return [
                    'success' => true,
                    'message' => "Untuk berhasil di jurusan \"{$jurusan}\", Anda perlu mengembangkan berbagai kompetensi, baik teknis maupun non-teknis. Kompetensi teknis akan bergantung pada spesifikasi bidang jurusan yang dipilih, sedangkan kompetensi umum seperti kemampuan komunikasi, kerja sama tim, dan pemecahan masalah sangat dibutuhkan di semua bidang studi. Apakah Anda ingin mengetahui kompetensi spesifik yang perlu dipersiapkan?"
                ];
            }
            return [
                'success' => true,
                'message' => "Setiap jurusan memerlukan kompetensi yang berbeda. Sebagai contoh, Teknologi Informasi membutuhkan kemampuan logika dan pemrograman, Kesehatan membutuhkan ketelitian dan empati, sedangkan Bisnis memerlukan kemampuan komunikasi dan manajerial. Secara umum, semua jurusan membutuhkan kemampuan belajar mandiri dan kerja sama tim. Jurusan mana yang ingin Anda ketahui kompetensinya secara lebih mendalam?"
            ];
        }

        if (strpos($messageLower, 'ipa') !== false || strpos($messageLower, 'ips') !== false) {
            return [
                'success' => true,
                'message' => "Perlu dipahami bahwa kelompok IPA dan IPS bukan merupakan batasan mutlak dalam memilih jurusan kuliah. Banyak jurusan di POLIJE dapat dimasuki oleh siswa dari kedua kelompok tersebut. Faktor yang lebih menentukan adalah minat, kemampuan, dan kompetensi yang Anda miliki. Siswa IPA dapat memilih bidang bisnis, dan sebaliknya siswa IPS dapat menempuh bidang teknologi informasi. Silakan manfaatkan fitur Analisis Rekomendasi untuk melihat jurusan yang paling sesuai berdasarkan profil lengkap Anda."
            ];
        }
        
        // Default response: tetap menanggapi isi pertanyaan agar nyambung
        if ($hasRecommendation) {
            $lead = !empty($lastAiMessage)
                ? "Menindaklanjuti percakapan sebelumnya, "
                : "";

            return [
                'success' => true,
                'message' => $lead . "berdasarkan hasil analisis Anda saat ini, jurusan dengan kecocokan tertinggi adalah \"{$jurusan}\" ({$score}%). " . (!empty($majorDesc) ? "Ringkasan jurusan: {$majorDesc}. " : "") . "Silakan lanjutkan dengan pertanyaan yang lebih spesifik, misalnya perbandingan dengan jurusan lain, kompetensi yang harus dipersiapkan, atau prospek kariernya."
            ];
        }

        return [
            'success' => true,
            'message' => "Saya adalah konselor BK virtual SMA Bima Ambulu, siap membantu Anda dalam proses pemilihan jurusan kuliah. Anda dapat bertanya seputar kesesuaian jurusan, prospek karier, kompetensi yang dibutuhkan, maupun panduan dalam menentukan pilihan studi. Untuk mendapatkan rekomendasi yang dipersonalisasi, silakan gunakan fitur Analisis Rekomendasi di halaman dashboard."
        ];
    }

    protected function buildSystemPrompt($context)
    {
        $prompt = "Kamu adalah Konselor Bimbingan Konseling (BK) di SMA Bima Ambulu. ";
        $prompt .= "Kamu adalah konselor profesional yang memberikan bimbingan secara personal kepada siswa. ";
        $prompt .= "Kamu MENGARAHKAN siswa, memberikan ANALISIS LOGIS, dan MEYAKINKAN mereka dengan alasan yang masuk akal. ";
        $prompt .= "Kamu juga bisa menjawab pertanyaan umum di luar topik jurusan (seperti pengetahuan umum, tokoh, dll) secara singkat, lalu arahkan kembali ke topik konseling. ";
        $prompt .= "Gunakan bahasa Indonesia yang FORMAL, AKADEMIK, dan SOPAN — seperti seorang konselor profesional berbicara dengan siswa. ";
        $prompt .= "DILARANG menggunakan bahasa gaul, slang, atau terlalu santai. Gunakan kalimat yang baku dan terstruktur. ";

        // PENTING: Konteks POLIJE
        $prompt .= "\n\nKONTEKS PENTING POLIJE (Politeknik Negeri Jember):";
        $prompt .= "\n- Di POLIJE, unit akademik disebut JURUSAN, BUKAN 'program studi' atau 'prodi'. Selalu gunakan istilah 'jurusan'.";
        $prompt .= "\n- Contoh benar: 'Jurusan Teknologi Informasi', 'Jurusan Kesehatan', 'Jurusan Manajemen Agribisnis'.";
        $prompt .= "\n- Contoh SALAH (JANGAN digunakan): 'program studi Teknologi Informasi', 'prodi TI'.";
        $prompt .= "\n- Saat menjelaskan jurusan POLIJE, PRIORITASKAN data dari DAFTAR JURUSAN di bawah (deskripsi, prospek kerja, kata kunci).";
        $prompt .= "\n- JANGAN gunakan informasi umum dari internet yang bisa berbeda dengan konteks POLIJE.";
        $prompt .= "\n- Jika siswa bertanya tentang suatu jurusan, jawab berdasarkan data POLIJE yang tersedia, bukan pengetahuan umum.";

        // Tambahkan informasi waktu saat ini
        $hour = (int) now()->format('H');
        if ($hour >= 3 && $hour < 11) {
            $sapaan = 'Selamat pagi';
            $waktu = 'pagi';
        } elseif ($hour >= 11 && $hour < 15) {
            $sapaan = 'Selamat siang';
            $waktu = 'siang';
        } elseif ($hour >= 15 && $hour < 18) {
            $sapaan = 'Selamat sore';
            $waktu = 'sore';
        } else {
            $sapaan = 'Selamat malam';
            $waktu = 'malam';
        }
        $prompt .= "\n\nWAKTU SAAT INI: " . now()->format('H:i') . " WIB (" . $waktu . "). ";
        $prompt .= "Jika perlu menyapa, gunakan sapaan yang sesuai waktu: '{$sapaan}'. JANGAN gunakan sapaan waktu yang tidak sesuai (misalnya jangan ucapkan 'Selamat pagi' jika saat ini malam hari).";
        
        // Tambahkan konteks rekomendasi jika ada
        if (!empty($context['recommendation'])) {
            $prompt .= "\n\nDATA REKOMENDASI SISWA (dari sistem analisis Naive Bayes): ";
            $prompt .= "Jurusan paling cocok: {$context['recommendation']}. ";
            if (!empty($context['score'])) {
                $prompt .= "Skor kesesuaian: {$context['score']}%. ";
            }

            // Tambahkan top 3 rekomendasi
            if (!empty($context['top3'])) {
                $prompt .= "\nPeringkat 3 besar rekomendasi: ";
                foreach ($context['top3'] as $i => $t) {
                    $num = $i + 1;
                    $skorVal = $t['skor'] ?? 0;
                    $pct = number_format(($skorVal > 1 ? $skorVal : $skorVal * 100), 1);
                    $prompt .= "\n  {$num}. {$t['jurusan']} ({$pct}%)";
                }
            }

            $prompt .= "\nGunakan data rekomendasi ini untuk menjelaskan MENGAPA jurusan tersebut direkomendasikan. Hubungkan dengan profil siswa di bawah.";
        }

        // Tambahkan profil siswa jika ada
        if (!empty($context['profile'])) {
            $prompt .= "\nDATA PROFIL SISWA: ";
            if (!empty($context['profile']['nama'])) {
                $prompt .= "Nama: {$context['profile']['nama']}. ";
            }
            if (!empty($context['profile']['kelompok'])) {
                $prompt .= "Kelompok asal: {$context['profile']['kelompok']}. ";
            }
            if (!empty($context['profile']['nilai'])) {
                $prompt .= "Kategori nilai akademik: {$context['profile']['nilai']}. ";
            }
            if (!empty($context['profile']['rata_rata'])) {
                $prompt .= "Rata-rata nilai: {$context['profile']['rata_rata']}. ";
            }
            if (!empty($context['profile']['minat'])) {
                $prompt .= "Minat: {$context['profile']['minat']}. ";
            }
            if (!empty($context['profile']['pref'])) {
                $prompt .= "Preferensi rumpun studi: {$context['profile']['pref']}. ";
            }
            if (!empty($context['profile']['cita_cita'])) {
                $prompt .= "Cita-cita: {$context['profile']['cita_cita']}. ";
            }
            if (!empty($context['profile']['prestasi'])) {
                $prompt .= "Prestasi: {$context['profile']['prestasi']}. ";
            }
        }

        $jurusanList = PolijeMajor::all();
        if ($jurusanList->isNotEmpty()) {
            $prompt .= "\n\nDAFTAR JURUSAN POLIJE ({$jurusanList->count()} jurusan) — INI ADALAH SUMBER DATA UTAMA, gunakan informasi ini saat menjelaskan jurusan:";
            foreach ($jurusanList as $j) {
                $prompt .= "\n- JURUSAN {$j->nama_jurusan}";
                if (!empty($j->deskripsi)) {
                    $prompt .= ": {$j->deskripsi}";
                }
                if (!empty($j->prospek_kerja)) {
                    $prompt .= " | Prospek kerja: {$j->prospek_kerja}.";
                }
                if (!empty($j->keywords) && is_array($j->keywords)) {
                    $prompt .= " | Kata kunci: " . implode(', ', array_slice($j->keywords, 0, 10)) . ".";
                }
                if (!empty($j->preferensi_studi) && is_array($j->preferensi_studi)) {
                    $prompt .= " | Rumpun: " . implode(', ', $j->preferensi_studi) . ".";
                }
            }
        }
        
        $prompt .= "\n\nCara kamu merespons:";
        $prompt .= "\n0. WAJIB jawab inti pertanyaan pengguna terlebih dahulu secara langsung dalam 1-2 kalimat pertama. Jangan memutar atau memberi jawaban generik yang tidak menanggapi pertanyaan.";
        $prompt .= "\n1. INGAT seluruh percakapan sebelumnya. Jangan tanya ulang hal yang sudah dijawab siswa.";
        $prompt .= "\n2. Kalau siswa sudah bilang minat/kemampuan/kesukaan, LANGSUNG analisis dan arahkan ke jurusan yang cocok dengan ALASAN LOGIS (misal: 'kamu suka logika → TI cocok karena...')";
        $prompt .= "\n3. Berikan REKOMENDASI TEGAS, bukan cuma daftar pilihan. Contoh: 'Menurut Bapak, kamu paling cocok ke Teknologi Informasi. Alasannya: ...'";
        $prompt .= "\n4. Dukung rekomendasi dengan fakta: prospek karir, gaji, mata kuliah, skill.";
        $prompt .= "\n5. Kalau siswa ragu, YAKINKAN dengan argumen kuat — jangan cuma bilang 'terserah kamu'.";
        $prompt .= "\n6. Jawab RINGKAS (2-3 paragraf). Jangan terlalu panjang kecuali diminta detail.";
        $prompt .= "\n7. Boleh menjawab pertanyaan di luar topik jurusan secara singkat, lalu kembalikan ke konseling.";
        $prompt .= "\n8. JANGAN awali setiap respons dengan 'Halo' atau salam — langsung ke inti jawaban (kecuali percakapan baru dimulai).";
        $prompt .= "\n8a. Jika pengguna bertanya 'apa itu jurusan X' atau 'jelaskan jurusan tersebut', jelaskan definisi jurusan, fokus pembelajaran, dan prospek kerjanya secara ringkas dan nyambung dengan konteks pengguna.";
        $prompt .= "\n8b. Jika pengguna menilai jawaban tidak nyambung, lakukan klarifikasi singkat lalu jawab ulang secara spesifik sesuai pertanyaan terbaru.";

        // Tambahkan referensi Q&A serupa dari riwayat
        if (!empty($context['similar_qa'])) {
            $prompt .= "\n\nREFERENSI JAWABAN SEBELUMNYA (pertanyaan serupa yang pernah dijawab — gunakan sebagai referensi untuk konsistensi, tapi sesuaikan dengan konteks siswa saat ini):";
            foreach ($context['similar_qa'] as $i => $qa) {
                $num = $i + 1;
                $prompt .= "\n{$num}. Pertanyaan: \"{$qa['prompt']}\"";
                $prompt .= "\n   Jawaban sebelumnya: \"{$qa['response']}\"";
            }
            $prompt .= "\nGunakan referensi di atas untuk menjaga konsistensi jawaban, namun tetap sesuaikan dengan profil dan konteks percakapan siswa saat ini.";
        }        $prompt .= "\n9. DILARANG KERAS menggunakan format markdown seperti **, *, #, ##, atau simbol formatting lainnya. Tulis teks biasa (plain text) saja tanpa formatting markdown.";
        $prompt .= "\n10. Gunakan bahasa Indonesia baku dan akademik. Hindari bahasa gaul seperti 'kek', 'banget', 'ngobrol', 'ngomongin', 'gampangnya'. Gunakan padanan formal seperti 'sangat', 'berbincang', 'membahas', 'secara sederhana'.";
        $prompt .= "\n11. Jika pertanyaan bersifat umum di luar jurusan (misalnya pengetahuan umum), jawab singkat namun benar, lalu tawarkan kaitan dengan rencana studi/karier pengguna.";

        if (($context['intent'] ?? '') === 'compare_majors') {
            $prompt .= "\n12. Pengguna sedang meminta PERBANDINGAN JURUSAN. Gunakan format terstruktur dengan urutan tetap: (1) Fokus pembelajaran, (2) Kompetensi yang perlu dipersiapkan, (3) Prospek kerja, (4) Tantangan belajar, (5) Rekomendasi paling sesuai untuk profil pengguna beserta alasan.";
        }

        return $prompt;
    }

    protected function isFollowUpMessage(string $messageLower): bool
    {
        $followUpHints = [
            'jelaskan semua',
            'lanjut',
            'lanjutkan',
            'yang tadi',
            'yang sebelumnya',
            'maksudnya',
            'lebih detail',
            'perjelas',
            'itu gimana',
            'jurusan itu',
            'jurusan tersebut',
            'semua jurusan',
            'satu satu',
            'satu-satu',
        ];

        foreach ($followUpHints as $hint) {
            if (strpos($messageLower, $hint) !== false) {
                return true;
            }
        }

        return false;
    }

    protected function getLastAssistantMessage(array $chatHistory): ?string
    {
        for ($i = count($chatHistory) - 1; $i >= 0; $i--) {
            $item = $chatHistory[$i] ?? null;
            if (!$item || !isset($item['role'], $item['text'])) {
                continue;
            }

            if ($item['role'] === 'ai' && trim((string) $item['text']) !== '') {
                return (string) $item['text'];
            }
        }

        return null;
    }

    protected function buildAllMajorsResponse(bool $hasRecommendation, ?string $jurusan, float $score): string
    {
        $majors = PolijeMajor::orderBy('nama_jurusan')->get();

        if ($majors->isEmpty()) {
            if ($hasRecommendation) {
                return "Menindaklanjuti pertanyaan Anda, saat ini rekomendasi utama Anda tetap pada jurusan \"{$jurusan}\" dengan skor {$score}%. Jika Anda berkenan, saya dapat jelaskan detail jurusan ini terlebih dahulu.";
            }

            return "Data jurusan saat ini belum tersedia. Silakan coba kembali beberapa saat lagi, atau ajukan satu jurusan yang ingin dibahas agar saya jelaskan secara umum.";
        }

        $lines = [];
        $lines[] = "Berikut ringkasan seluruh jurusan di Politeknik Negeri Jember agar pembahasan kita tetap nyambung dengan pertanyaan Anda:";

        foreach ($majors as $index => $m) {
            $desc = trim((string) ($m->deskripsi ?? ''));
            if ($desc === '') {
                $desc = 'Berorientasi pada kompetensi terapan yang relevan dengan kebutuhan dunia kerja.';
            }
            $num = $index + 1;
            $lines[] = "{$num}. {$m->nama_jurusan}: {$desc}";
        }

        if ($hasRecommendation) {
            $lines[] = "Berdasarkan profil Anda, jurusan yang paling direkomendasikan tetap \"{$jurusan}\" ({$score}%). Jika Anda ingin, saya lanjutkan dengan perbandingan jurusan rekomendasi Anda terhadap 2 alternatif teratas.";
        } else {
            $lines[] = "Jika Anda berkenan, saya dapat bantu menyaring 2-3 jurusan paling relevan berdasarkan minat dan nilai Anda.";
        }

        return implode(' ', $lines);
    }

    protected function buildStructuredComparisonResponse(string $message, array $context = []): ?string
    {
        $majors = PolijeMajor::orderBy('nama_jurusan')->get();
        if ($majors->count() < 2) {
            return null;
        }

        $selected = $this->resolveMajorsForComparison($message, $context, $majors);
        if (count($selected) < 2) {
            return null;
        }

        $a = $selected[0];
        $b = $selected[1];

        $aDesc = trim((string) ($a->deskripsi ?? ''));
        $bDesc = trim((string) ($b->deskripsi ?? ''));
        $aProspek = trim((string) ($a->prospek_kerja ?? ''));
        $bProspek = trim((string) ($b->prospek_kerja ?? ''));

        if ($aDesc === '') {
            $aDesc = 'Berfokus pada kompetensi terapan sesuai kebutuhan industri.';
        }
        if ($bDesc === '') {
            $bDesc = 'Berfokus pada kompetensi terapan sesuai kebutuhan industri.';
        }
        if ($aProspek === '') {
            $aProspek = 'Lulusan berpeluang masuk pada bidang kerja yang relevan dengan kompetensi jurusan.';
        }
        if ($bProspek === '') {
            $bProspek = 'Lulusan berpeluang masuk pada bidang kerja yang relevan dengan kompetensi jurusan.';
        }

        $recommended = $context['recommendation'] ?? null;
        $winner = $a->nama_jurusan;
        if (!empty($recommended)) {
            if (strcasecmp($recommended, $b->nama_jurusan) === 0) {
                $winner = $b->nama_jurusan;
            } elseif (strcasecmp($recommended, $a->nama_jurusan) !== 0) {
                $winner = $a->nama_jurusan;
            }
        }

        $score = isset($context['score']) ? (float) $context['score'] : null;
        $scoreText = $score !== null && $score > 0 ? " dengan skor kesesuaian {$score}%" : "";

        $lines = [];
        $lines[] = "Perbandingan terstruktur antara Jurusan {$a->nama_jurusan} dan Jurusan {$b->nama_jurusan}:";
        $lines[] = "1. Fokus pembelajaran";
        $lines[] = "- {$a->nama_jurusan}: {$aDesc}";
        $lines[] = "- {$b->nama_jurusan}: {$bDesc}";
        $lines[] = "2. Kompetensi yang perlu dipersiapkan";
        $lines[] = "- {$a->nama_jurusan}: Penguasaan konsep inti jurusan, kemampuan analitis, komunikasi profesional, dan disiplin praktik.";
        $lines[] = "- {$b->nama_jurusan}: Penguasaan konsep inti jurusan, kemampuan analitis, komunikasi profesional, dan disiplin praktik.";
        $lines[] = "3. Prospek kerja";
        $lines[] = "- {$a->nama_jurusan}: {$aProspek}";
        $lines[] = "- {$b->nama_jurusan}: {$bProspek}";
        $lines[] = "4. Tantangan belajar";
        $lines[] = "- {$a->nama_jurusan}: Menuntut konsistensi belajar, adaptasi pada praktik lapangan, dan ketekunan menyelesaikan tugas proyek.";
        $lines[] = "- {$b->nama_jurusan}: Menuntut konsistensi belajar, adaptasi pada praktik lapangan, dan ketekunan menyelesaikan tugas proyek.";
        $lines[] = "5. Rekomendasi untuk profil Anda";
        $lines[] = "- Berdasarkan konteks analisis Anda, jurusan yang lebih diprioritaskan adalah {$winner}{$scoreText}. Jika Anda berkenan, saya dapat lanjutkan dengan langkah persiapan 6 bulan pertama agar transisi belajar Anda lebih terarah.";

        return implode("\n", $lines);
    }

    protected function resolveMajorsForComparison(string $message, array $context, $majors): array
    {
        $messageLower = mb_strtolower($message);
        $mentioned = [];

        foreach ($majors as $major) {
            $name = mb_strtolower((string) $major->nama_jurusan);
            if ($name !== '' && str_contains($messageLower, $name)) {
                $mentioned[$major->nama_jurusan] = $major;
            }
        }

        if (count($mentioned) >= 2) {
            return array_slice(array_values($mentioned), 0, 2);
        }

        $picked = array_values($mentioned);
        $recommendationName = $context['recommendation'] ?? null;

        if (!empty($recommendationName)) {
            $recMajor = $majors->first(function ($m) use ($recommendationName) {
                return strcasecmp((string) $m->nama_jurusan, (string) $recommendationName) === 0;
            });

            if ($recMajor && !$this->containsMajor($picked, $recMajor->nama_jurusan)) {
                $picked[] = $recMajor;
            }
        }

        if (!empty($context['top3']) && is_array($context['top3'])) {
            foreach ($context['top3'] as $candidate) {
                $name = $candidate['jurusan'] ?? null;
                if (empty($name)) {
                    continue;
                }

                $candidateMajor = $majors->first(function ($m) use ($name) {
                    return strcasecmp((string) $m->nama_jurusan, (string) $name) === 0;
                });

                if ($candidateMajor && !$this->containsMajor($picked, $candidateMajor->nama_jurusan)) {
                    $picked[] = $candidateMajor;
                }

                if (count($picked) >= 2) {
                    break;
                }
            }
        }

        if (count($picked) < 2) {
            foreach ($majors as $major) {
                if (!$this->containsMajor($picked, $major->nama_jurusan)) {
                    $picked[] = $major;
                }

                if (count($picked) >= 2) {
                    break;
                }
            }
        }

        return array_slice($picked, 0, 2);
    }

    protected function containsMajor(array $picked, string $name): bool
    {
        foreach ($picked as $item) {
            if (strcasecmp((string) $item->nama_jurusan, $name) === 0) {
                return true;
            }
        }

        return false;
    }

}
