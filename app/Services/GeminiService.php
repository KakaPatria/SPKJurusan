<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

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
            $greeting = "Halo! 👋 Saya konselor BK virtual SMA Bima Ambulu. Saya siap membantu kamu soal pemilihan jurusan kuliah. ";
            if ($hasRecommendation) {
                $greeting .= "Saya lihat kamu sudah dapat rekomendasi jurusan \"{$jurusan}\" dengan skor {$score}%. Mau bahas lebih lanjut tentang jurusan itu, atau ada pertanyaan lain?";
            } else {
                $greeting .= "Kamu bisa tanya apa saja tentang jurusan kuliah, prospek karir, atau tips memilih jurusan yang tepat. Yuk, mulai!";
            }
            return ['success' => true, 'message' => $greeting];
        }
        
        if (strpos($messageLower, 'kenapa') !== false || strpos($messageLower, 'mengapa') !== false) {
            if ($hasRecommendation) {
                return [
                    'success' => true,
                    'message' => "Jurusan \"{$jurusan}\" direkomendasikan berdasarkan analisis profil akademik, minat, dan preferensi belajar kamu. Skor kesesuaian {$score}% menunjukkan tingkat kecocokan yang baik antara profil kamu dengan jurusan tersebut. Sistem menghitung ini dari 5 faktor: nilai akademik, minat, preferensi pembelajaran, prestasi, dan cita-cita."
                ];
            }
            return [
                'success' => true,
                'message' => "Untuk menjawab pertanyaan \"mengapa\", sebaiknya kamu lakukan analisis rekomendasi dulu ya. Dari situ, sistem akan mencocokkan profil kamu dengan 9 jurusan yang tersedia. Kamu bisa klik menu 'Analisis Rekomendasi' di dashboard."
            ];
        }
        
        if (strpos($messageLower, 'prospek') !== false || strpos($messageLower, 'karir') !== false || strpos($messageLower, 'kerja') !== false) {
            if ($hasRecommendation) {
                return [
                    'success' => true,
                    'message' => "Jurusan \"{$jurusan}\" memiliki prospek karir yang baik. Lulusan dari jurusan ini bisa bekerja di berbagai sektor industri yang relevan. Setiap jurusan di perguruan tinggi menyiapkan lulusannya dengan keahlian praktis yang dibutuhkan dunia kerja. Mau tau lebih detail tentang posisi kerja spesifik?"
                ];
            }
            return [
                'success' => true,
                'message' => "Setiap jurusan punya prospek karir yang berbeda-beda. Misalnya, Teknologi Informasi bisa jadi programmer/developer, Kesehatan bisa jadi tenaga medis, Bisnis bisa jadi manajer/entrepreneur. Jurusan mana yang kamu tertarik? Saya bisa jelaskan lebih detail."
            ];
        }
        
        if (strpos($messageLower, 'bingung') !== false || strpos($messageLower, 'galau') !== false || strpos($messageLower, 'tidak tahu') !== false || strpos($messageLower, 'gak tau') !== false) {
            return [
                'success' => true,
                'message' => "Wajar kok kalau masih bingung! 😊 Coba jawab pertanyaan ini: 1) Mata pelajaran apa yang paling kamu suka? 2) Kegiatan apa yang bikin kamu semangat? 3) Cita-cita kamu apa? Dari situ kita bisa mulai mencari jurusan yang cocok. Atau kamu juga bisa coba fitur 'Analisis Rekomendasi' di dashboard untuk mendapat rekomendasi otomatis."
            ];
        }

        if (strpos($messageLower, 'skill') !== false || strpos($messageLower, 'kemampuan') !== false) {
            if ($hasRecommendation) {
                return [
                    'success' => true,
                    'message' => "Untuk sukses di jurusan \"{$jurusan}\", kamu perlu mengembangkan berbagai skill teknis dan non-teknis. Skill teknis tergantung bidang jurusannya, sedangkan skill umum seperti komunikasi, kerja tim, dan problem solving selalu dibutuhkan di semua jurusan. Mau tau skill spesifik yang perlu disiapkan?"
                ];
            }
            return [
                'success' => true,
                'message' => "Setiap jurusan butuh skill yang berbeda. Misalnya: TI butuh logika & coding, Kesehatan butuh ketelitian & empati, Bisnis butuh komunikasi & manajemen. Yang pasti, semua jurusan butuh kemampuan belajar mandiri dan kerja tim. Jurusan mana yang ingin kamu ketahui skill-nya?"
            ];
        }

        if (strpos($messageLower, 'ipa') !== false || strpos($messageLower, 'ips') !== false) {
            return [
                'success' => true,
                'message' => "Kelompok IPA dan IPS bukan batasan mutlak untuk memilih jurusan kuliah ya. Banyak jurusan yang bisa dimasuki oleh keduanya. Yang penting adalah minat dan kemampuan kamu. Anak IPA bisa masuk bisnis, anak IPS bisa masuk TI. Lakukan analisis rekomendasi untuk melihat jurusan mana yang paling cocok berdasarkan profil lengkap kamu."
            ];
        }
        
        // Default response
        if ($hasRecommendation) {
            return [
                'success' => true,
                'message' => "Saya konselor BK virtual SMA Bima Ambulu. Berdasarkan analisis, jurusan \"{$jurusan}\" cocok untuk kamu dengan skor {$score}%. Kamu bisa tanya tentang: prospek karir, skill yang dibutuhkan, perbandingan jurusan, atau apapun tentang persiapan kuliah. Saya siap membantu! 😊"
            ];
        }

        return [
            'success' => true,
            'message' => "Saya konselor BK virtual SMA Bima Ambulu, siap membantu kamu memilih jurusan kuliah! 😊 Kamu bisa bertanya tentang: jurusan apa yang cocok, prospek karir, skill yang dibutuhkan, atau tips memilih jurusan. Untuk rekomendasi personal, coba fitur 'Analisis Rekomendasi' di dashboard ya."
        ];
    }

    protected function buildSystemPrompt($context)
    {
        $prompt = "Kamu adalah Pak/Bu Konselor BK (Bimbingan Konseling) di SMA Bima Ambulu. ";
        $prompt .= "Kamu adalah guru BK yang HIDUP — bukan robot. ";
        $prompt .= "Kamu MENGARAHKAN siswa, memberikan ANALISIS LOGIS, dan MEYAKINKAN mereka dengan alasan yang masuk akal. ";
        $prompt .= "Kamu juga bisa menjawab pertanyaan umum di luar topik jurusan (seperti pengetahuan umum, tokoh, dll) secara singkat, lalu arahkan kembali ke topik konseling. ";
        $prompt .= "Gunakan bahasa Indonesia santai, hangat, tapi tetap berbobot — seperti guru BK favorit yang ngobrol dengan muridnya. ";
        
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

        $jurusan = config('polije.criteria', []);
        if (!empty($jurusan)) {
            $namaJurusan = array_keys($jurusan);
            $prompt .= "\n\n9 Jurusan tersedia: " . implode(', ', $namaJurusan) . ". ";
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

        return $prompt;
    }
}
