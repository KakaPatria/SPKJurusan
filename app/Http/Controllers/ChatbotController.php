<?php

namespace App\Http\Controllers;

use App\Services\GeminiService;
use App\Models\ChatHistory;
use App\Models\Recommendation;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;

class ChatbotController extends Controller
{
    protected $geminiService;

    public function __construct(GeminiService $geminiService)
    {
        $this->middleware('auth');
        $this->geminiService = $geminiService;
    }

    /**
     * Tampilkan halaman chatbot.
     * Jika ada ?session=xxx, lanjutkan sesi lama (pakai rekomendasi saat itu).
     * Jika ada ?rec=xxx, buat sesi baru terkait rekomendasi tertentu.
     * Jika tidak ada parameter, buat sesi baru dengan rekomendasi terbaru.
     */
    public function index(Request $request)
    {
        $user = Auth::user();
        $sessionId = $request->query('session');
        $recId = $request->query('rec');
        $previousMessages = [];
        $recommendationId = null;

        if ($sessionId) {
            // Lanjutkan sesi lama — ambil semua chat dari sesi ini
            $chats = ChatHistory::where('user_id', $user->id)
                ->where('id_sesi', $sessionId)
                ->orderBy('created_at', 'asc')
                ->get();

            if ($chats->isEmpty()) {
                // Session tidak valid, buat baru
                $sessionId = Str::uuid()->toString();
            } else {
                // Ambil recommendation_id dari sesi ini
                $recommendationId = $chats->first()->recommendation_id;

                foreach ($chats as $chat) {
                    $previousMessages[] = [
                        'role' => 'user',
                        'text' => $chat->prompt,
                    ];
                    $previousMessages[] = [
                        'role' => 'ai',
                        'text' => $this->stripMarkdown($chat->response),
                    ];
                }
            }
        } else {
            // Sesi baru
            $sessionId = Str::uuid()->toString();
        }

        // Tentukan recommendation_id:
        // 1. Dari sesi lama (sudah diset di atas)
        // 2. Dari parameter ?rec= (klik dari hasil rekomendasi)
        // 3. Dari rekomendasi terbaru user
        if (!$recommendationId && $recId) {
            $rec = Recommendation::where('id', $recId)
                ->where('user_id', $user->id)
                ->first();
            $recommendationId = $rec ? $rec->id : null;
        }

        if (!$recommendationId) {
            $latestRec = Recommendation::where('user_id', $user->id)->latest()->first();
            $recommendationId = $latestRec ? $latestRec->id : null;
        }

        // Ambil konteks rekomendasi berdasarkan ID spesifik
        $recentRecommendation = $this->getRecommendationContext($user, $recommendationId);

        return view('chatbot.index', [
            'recommendation' => $recentRecommendation,
            'sessionId' => $sessionId,
            'previousMessages' => $previousMessages,
            'recommendationId' => $recommendationId,
        ]);
    }

    /**
     * Kirim pesan dan terima respons AI
     */
    public function send(Request $request)
    {
        $request->validate([
            'message' => 'required|string|max:1000',
            'sessionId' => 'required|string|max:36',
            'recommendationId' => 'nullable|integer',
            'chatHistory' => 'nullable|array|max:20',
            'chatHistory.*.role' => 'required|string|in:user,ai',
            'chatHistory.*.text' => 'required|string|max:2000',
        ]);

        $message = $request->input('message');
        $sessionId = $request->input('sessionId');
        $recommendationId = $request->input('recommendationId');
        $chatHistory = $request->input('chatHistory', []);
        $user = Auth::user();

        // Ambil konteks rekomendasi berdasarkan ID spesifik sesi ini
        $recentRecommendation = $this->getRecommendationContext($user, $recommendationId) ?? [];

        // Siapkan context untuk Gemini
        $context = [
            'recommendation' => $recentRecommendation['jurusan'] ?? null,
            'score' => isset($recentRecommendation['skor']) ? number_format(($recentRecommendation['skor'] > 1 ? $recentRecommendation['skor'] : $recentRecommendation['skor'] * 100), 1) : null,
            'top3' => $recentRecommendation['top3'] ?? [],
            'intent' => $this->detectIntent($message),
            'profile' => [
                'nama' => $user->name,
                'kelompok' => $user->kelompok_asal ?? null,
                'nilai' => $recentRecommendation['nilai'] ?? null,
                'rata_rata' => $recentRecommendation['rata_rata'] ?? null,
                'minat' => $recentRecommendation['minat'] ?? null,
                'pref' => $recentRecommendation['pref_studi'] ?? null,
                'cita_cita' => $recentRecommendation['cita_cita'] ?? null,
                'prestasi' => $recentRecommendation['prestasi'] ?? null,
            ]
        ];

        // Cari pertanyaan serupa dari riwayat semua siswa
        $similarQA = $this->findSimilarQuestions($message, $user->id);
        if (!empty($similarQA)) {
            $context['similar_qa'] = $similarQA;
        }

        // Panggil Gemini API dengan conversation history
        $response = $this->geminiService->chat($message, $context, $chatHistory);

        // Normalisasi respons agar error tetap memiliki pesan yang konsisten.
        $isSuccess = (bool) ($response['success'] ?? false);
        $errorCode = (string) ($response['error_code'] ?? 'CHAT_SERVICE_ERROR');
        $responseMessage = trim((string) ($response['message'] ?? ''));

        if ($responseMessage === '') {
            $responseMessage = $isSuccess
                ? 'Respons berhasil diproses.'
                : 'Maaf, layanan chatbot sedang mengalami gangguan. Silakan coba kembali beberapa saat lagi.';
        }

        if (!$isSuccess) {
            $responseMessage = "[ERROR:{$errorCode}] {$responseMessage}";
        }

        $response['message'] = $responseMessage;
        $response['error_code'] = $isSuccess ? null : $errorCode;

        // Simpan chat ke database dengan session_id dan recommendation_id
        if ($user) {
            ChatHistory::create([
                'user_id' => $user->id,
                'id_sesi' => $sessionId,
                'id_rekomendasi' => $recommendationId,
                'pertanyaan' => $message,
                'jawaban' => $responseMessage,
            ]);
        }

        return response()->json($response);
    }

    /**
     * Tampilkan history chat, dikelompokkan per sesi
     */
    public function historyChat()
    {
        $user = Auth::user();
        
        // Ambil semua chat user dengan relasi recommendation
        $chatHistories = ChatHistory::where('user_id', $user->id)
            ->with('recommendation')
            ->orderBy('created_at', 'desc')
            ->get();

        // Kelompokkan per id_sesi
        $sessions = $chatHistories->groupBy('id_sesi')->map(function ($chats, $sessionId) {
            $first = $chats->last();  // oldest in group (karena desc)
            $last = $chats->first();  // newest in group
            $rec = $first->recommendation;
            $recInfo = null;
            if ($rec) {
                $hasil = is_array($rec->hasil_rekomendasi)
                    ? $rec->hasil_rekomendasi
                    : json_decode($rec->hasil_rekomendasi, true);
                $topJurusan = $hasil[0] ?? null;
                $recInfo = [
                    'id' => $rec->id,
                    'jurusan' => $topJurusan['jurusan'] ?? '-',
                    'skor' => $topJurusan['skor'] ?? 0,
                    'tanggal' => $rec->created_at,
                ];
            }
            return [
                'session_id' => $sessionId,
                'chats' => $chats->reverse()->values(),
                'first_message' => Str::limit($first->prompt, 80),
                'message_count' => $chats->count(),
                'started_at' => $first->created_at,
                'last_at' => $last->created_at,
                'recommendation' => $recInfo,
            ];
        });

        return view('history.chat', compact('sessions'));
    }

    /**
     * Ambil konteks rekomendasi berdasarkan ID spesifik.
     * Jika ID tidak ada, coba dari session, lalu dari DB (terbaru).
     */
    private function getRecommendationContext($user, $recommendationId = null)
    {
        // Jika ada recommendation_id spesifik, ambil langsung dari DB
        $lastRec = null;

        if ($recommendationId) {
            $lastRec = Recommendation::where('id', $recommendationId)
                ->where('user_id', $user->id)
                ->first();
        }

        // Fallback: dari session (saat baru selesai rekomendasi)
        if (!$lastRec) {
            $sessionData = session('recomendation_data', null);
            if ($sessionData) {
                return $sessionData;
            }
        }

        // Fallback: rekomendasi terbaru dari DB
        if (!$lastRec) {
            $lastRec = Recommendation::where('user_id', $user->id)
                ->latest()
                ->first();
        }

        if (!$lastRec) {
            return null;
        }

        $hasil = is_array($lastRec->hasil_rekomendasi)
            ? $lastRec->hasil_rekomendasi
            : json_decode($lastRec->hasil_rekomendasi, true);
        $topJurusan = $hasil[0] ?? null;
        $top3 = array_slice($hasil ?? [], 0, 3);

        // Hitung rata-rata dari kolom nilai
        $nilaiCols = ['mtk', 'fisika', 'kimia', 'biologi', 'ekonomi', 'geografi', 'sosiologi', 'sejarah'];
        $validVals = array_filter(array_map(fn($c) => $lastRec->$c, $nilaiCols), fn($v) => $v !== null);
        $rataRata = count($validVals) > 0 ? round(array_sum($validVals) / count($validVals), 1) : null;

        // Kategorisasi
        $katNilai = 'Rendah';
        if ($rataRata >= 85) $katNilai = 'Tinggi';
        elseif ($rataRata >= 70) $katNilai = 'Sedang';

        return [
            'jurusan'    => $topJurusan['jurusan'] ?? null,
            'skor'       => $topJurusan['skor'] ?? null,
            'detail'     => $topJurusan['detail'] ?? [],
            'nilai'      => $katNilai,
            'rata_rata'  => $rataRata,
            'minat'      => $lastRec->minat,
            'pref_studi' => $lastRec->preferensi_studi,
            'cita_cita'  => $lastRec->cita_cita,
            'prestasi'   => $lastRec->prestasi,
            'top3'       => array_map(fn($r) => [
                'jurusan' => $r['jurusan'] ?? '',
                'skor'    => $r['skor'] ?? 0,
            ], $top3),
        ];
    }

    /**
     * Cari pertanyaan serupa dari riwayat chat semua user
     * Menggunakan keyword matching sederhana
     */
    private function findSimilarQuestions(string $message, int $currentUserId): array
    {
        $messageLower = strtolower($message);
        
        // Ekstrak kata kunci penting (min 4 huruf, bukan stopwords)
        $stopwords = ['yang', 'dari', 'untuk', 'dengan', 'dalam', 'pada', 'akan', 'bisa', 'juga', 'saya', 'kamu', 'anda', 'tidak', 'sudah', 'belum', 'mau', 'ingin', 'apa', 'bagaimana', 'kenapa', 'mengapa', 'apakah', 'tolong', 'dong', 'aku', 'ini', 'itu'];
        
        $words = preg_split('/[\s,;.!?\-\/]+/', $messageLower);
        $keywords = array_filter($words, function ($w) use ($stopwords) {
            return strlen($w) >= 4 && !in_array($w, $stopwords);
        });

        if (empty($keywords)) {
            return [];
        }

        // Cari chat history yang mengandung kata kunci serupa
        $query = ChatHistory::select('pertanyaan', 'jawaban', 'created_at')
            ->where('user_id', $currentUserId);

        $query->where(function ($q) use ($keywords) {
            foreach ($keywords as $keyword) {
                $q->orWhere('pertanyaan', 'like', "%{$keyword}%");
            }
        });

        $candidates = $query->orderBy('created_at', 'desc')
            ->limit(20)
            ->get();

        if ($candidates->isEmpty()) {
            return [];
        }

        // Scoring: hitung berapa keyword yang cocok
        $scored = [];
        foreach ($candidates as $chat) {
            $promptLower = strtolower($chat->pertanyaan);
            $matchCount = 0;
            foreach ($keywords as $kw) {
                if (stripos($promptLower, $kw) !== false) {
                    $matchCount++;
                }
            }
            $ratio = $matchCount / count($keywords);
            if ($ratio >= 0.4) { // minimal 40% keyword cocok
                $scored[] = [
                    'prompt' => $chat->pertanyaan,
                    'response' => Str::limit($chat->jawaban, 300),
                    'score' => $ratio,
                ];
            }
        }

        // Sort by score desc, ambil top 3
        usort($scored, fn($a, $b) => $b['score'] <=> $a['score']);
        return array_slice($scored, 0, 3);
    }

    /**
     * Strip markdown formatting
     */
    private function stripMarkdown(string $text): string
    {
        $text = preg_replace('/\*\*(.*?)\*\*/s', '$1', $text);
        $text = preg_replace('/\*(.*?)\*/s', '$1', $text);
        $text = preg_replace('/^#{1,6}\s+/m', '', $text);
        $text = preg_replace('/`(.*?)`/s', '$1', $text);
        return $text;
    }

    private function detectIntent(string $message): string
    {
        $message = strtolower($message);

        if (
            str_contains($message, 'banding') ||
            str_contains($message, 'beda') ||
            str_contains($message, 'vs') ||
            str_contains($message, 'dibanding')
        ) {
            return 'compare_majors';
        }

        if (
            str_contains($message, 'jelaskan semua') ||
            str_contains($message, 'semua jurusan')
        ) {
            return 'explain_all_majors';
        }

        if (
            str_contains($message, 'lanjut') ||
            str_contains($message, 'yang tadi') ||
            str_contains($message, 'yang sebelumnya') ||
            str_contains($message, 'maksudnya')
        ) {
            return 'follow_up';
        }

        if (str_contains($message, 'kenapa') || str_contains($message, 'mengapa')) {
            return 'ask_reason';
        }

        if (
            str_contains($message, 'prospek') ||
            str_contains($message, 'karir') ||
            str_contains($message, 'kerja')
        ) {
            return 'ask_career';
        }

        return 'general';
    }
}
