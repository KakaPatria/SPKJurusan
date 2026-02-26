<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use App\Models\PolijeMajor;

class GeminiService
{
    protected $apiKey;
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
    }

    public function chat($message, $context = [], $chatHistory = [])
    {
        try {
            if (empty($this->apiKey)) {
                return [
                    'success' => false,
                    'message' => 'API Key tidak tersedia. Silakan konfigurasi GEMINI_API_KEY di .env'
                ];
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

            // Try each model until one works
            foreach ($this->models as $model) {
                $url = $this->baseUrl . $model . ':generateContent?key=' . $this->apiKey;

                Log::info('Trying Gemini model', ['model' => $model]);

                $response = Http::timeout(30)
                    ->withHeaders(['Content-Type' => 'application/json'])
                    ->post($url, $payload);

                if ($response->successful()) {
                    $data = $response->json();
                    
                    if (isset($data['candidates'][0]['content']['parts'][0]['text'])) {
                        Log::info('Gemini API success', ['model' => $model]);
                        return [
                            'success' => true,
                            'message' => $data['candidates'][0]['content']['parts'][0]['text']
                        ];
                    }
                }

                // If 429 (rate limit) or 404 (model not found), try next model
                $status = $response->status();
                Log::warning("Gemini model {$model} failed", ['status' => $status]);
                
                if ($status === 429) {
                    // Wait briefly before trying next model
                    sleep(1);
                }
            }

            // All models failed
            Log::error('All Gemini models failed, using fallback');
            return $this->getFallbackResponse($message, $context);

        } catch (\Exception $e) {
            Log::error('Gemini Service Exception', [
                'error' => $e->getMessage(),
                'file' => $e->getFile(),
                'line' => $e->getLine()
            ]);
            
            return $this->getFallbackResponse($message, $context);
        }
    }

    protected function getFallbackResponse($message, $context = [])
    {
        $jurusan = $context['recommendation'] ?? null;
        $score = isset($context['score']) ? floatval($context['score']) : 0;
        $hasRecommendation = !empty($jurusan);

        // Keyword-based responses
        $messageLower = strtolower($message);

        if (strpos($messageLower, 'halo') !== false || strpos($messageLower, 'hai') !== false || strpos($messageLower, 'hallo') !== false || strpos($messageLower, 'hi') !== false) {
            $greeting = "Selamat datang. Saya adalah konselor BK virtual SMA Bima Ambulu yang siap membantu Anda dalam pemilihan jurusan kuliah. ";
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
                    'message' => "Jurusan \"{$jurusan}\" direkomendasikan berdasarkan analisis komprehensif terhadap profil akademik, minat, serta preferensi studi Anda. Skor kesesuaian sebesar {$score}% menunjukkan tingkat kecocokan yang signifikan antara profil Anda dengan karakteristik jurusan tersebut. Sistem menghitung skor ini berdasarkan lima faktor utama, yaitu: nilai akademik, minat dan bakat, preferensi studi lanjutan, prestasi, dan cita-cita."
                ];
            }
            return [
                'success' => true,
                'message' => "Untuk dapat menjawab pertanyaan tersebut secara akurat, disarankan agar Anda terlebih dahulu melakukan analisis rekomendasi. Melalui proses tersebut, sistem akan mencocokkan profil Anda dengan sembilan jurusan yang tersedia di Polije. Silakan akses menu Analisis Rekomendasi pada halaman dashboard."
            ];
        }
        
        if (strpos($messageLower, 'prospek') !== false || strpos($messageLower, 'karir') !== false || strpos($messageLower, 'kerja') !== false) {
            if ($hasRecommendation) {
                return [
                    'success' => true,
                    'message' => "Jurusan \"{$jurusan}\" memiliki prospek karier yang menjanjikan. Lulusan dari jurusan ini dapat bekerja di berbagai sektor industri yang relevan dengan bidang keahliannya. Setiap program studi di perguruan tinggi dirancang untuk membekali lulusannya dengan kompetensi praktis yang dibutuhkan oleh dunia kerja. Apakah Anda ingin mengetahui lebih detail mengenai posisi pekerjaan spesifik yang dapat ditempuh?"
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
                'message' => "Perlu dipahami bahwa kelompok IPA dan IPS bukan merupakan batasan mutlak dalam memilih jurusan kuliah. Banyak program studi yang dapat dimasuki oleh siswa dari kedua kelompok tersebut. Faktor yang lebih menentukan adalah minat, kemampuan, dan kompetensi yang Anda miliki. Siswa IPA dapat memilih bidang bisnis, dan sebaliknya siswa IPS dapat menempuh bidang teknologi informasi. Silakan manfaatkan fitur Analisis Rekomendasi untuk melihat jurusan yang paling sesuai berdasarkan profil lengkap Anda."
            ];
        }
        
        // Default response
        if ($hasRecommendation) {
            return [
                'success' => true,
                'message' => "Saya adalah konselor BK virtual SMA Bima Ambulu. Berdasarkan hasil analisis, jurusan \"{$jurusan}\" memiliki kesesuaian tertinggi dengan profil Anda, yaitu sebesar {$score}%. Anda dapat berkonsultasi mengenai prospek karier, kompetensi yang dibutuhkan, perbandingan antar jurusan, atau hal lain terkait persiapan pendidikan tinggi. Silakan sampaikan pertanyaan Anda."
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
        
        // Tambahkan konteks rekomendasi jika ada
        if (!empty($context['recommendation'])) {
            $prompt .= "\n\nDATA REKOMENDASI SISWA (dari sistem analisis): ";
            $prompt .= "Jurusan paling cocok: {$context['recommendation']}. ";
            if (!empty($context['score'])) {
                $prompt .= "Skor kesesuaian: {$context['score']}%. ";
            }
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
                $prompt .= "Nilai akademik: {$context['profile']['nilai']}. ";
            }
            if (!empty($context['profile']['minat'])) {
                $prompt .= "Minat: {$context['profile']['minat']}. ";
            }
            if (!empty($context['profile']['pref'])) {
                $prompt .= "Preferensi pembelajaran: {$context['profile']['pref']}. ";
            }
        }

        $jurusanList = PolijeMajor::all();
        if ($jurusanList->isNotEmpty()) {
            $prompt .= "\n\nDAFTAR JURUSAN POLIJE ({$jurusanList->count()} jurusan):";
            foreach ($jurusanList as $j) {
                $prompt .= "\n- {$j->nama_jurusan}";
                if (!empty($j->deskripsi)) {
                    $prompt .= ": {$j->deskripsi}";
                }
                if (!empty($j->prospek_kerja)) {
                    $prompt .= " Prospek kerja: {$j->prospek_kerja}.";
                }
                if (!empty($j->keywords) && is_array($j->keywords)) {
                    $prompt .= " Kata kunci: " . implode(', ', array_slice($j->keywords, 0, 10)) . ".";
                }
            }
        }
        
        $prompt .= "\n\nCara kamu merespons:";
        $prompt .= "\n1. INGAT seluruh percakapan sebelumnya. Jangan tanya ulang hal yang sudah dijawab siswa.";
        $prompt .= "\n2. Kalau siswa sudah bilang minat/kemampuan/kesukaan, LANGSUNG analisis dan arahkan ke jurusan yang cocok dengan ALASAN LOGIS (misal: 'kamu suka logika → TI cocok karena...')";
        $prompt .= "\n3. Berikan REKOMENDASI TEGAS, bukan cuma daftar pilihan. Contoh: 'Menurut Bapak, kamu paling cocok ke Teknologi Informasi. Alasannya: ...'";
        $prompt .= "\n4. Dukung rekomendasi dengan fakta: prospek karir, gaji, mata kuliah, skill.";
        $prompt .= "\n5. Kalau siswa ragu, YAKINKAN dengan argumen kuat — jangan cuma bilang 'terserah kamu'.";
        $prompt .= "\n6. Jawab RINGKAS (2-3 paragraf). Jangan terlalu panjang kecuali diminta detail.";
        $prompt .= "\n7. Boleh menjawab pertanyaan di luar topik jurusan secara singkat, lalu kembalikan ke konseling.";
        $prompt .= "\n8. JANGAN awali setiap respons dengan 'Halo' atau salam — langsung ke inti jawaban (kecuali percakapan baru dimulai).";
        $prompt .= "\n9. DILARANG KERAS menggunakan format markdown seperti **, *, #, ##, atau simbol formatting lainnya. Tulis teks biasa (plain text) saja tanpa formatting markdown.";
        $prompt .= "\n10. Gunakan bahasa Indonesia baku dan akademik. Hindari bahasa gaul seperti 'kek', 'banget', 'ngobrol', 'ngomongin', 'gampangnya'. Gunakan padanan formal seperti 'sangat', 'berbincang', 'membahas', 'secara sederhana'.";

        return $prompt;
    }
}
