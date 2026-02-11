<?php

namespace App\Http\Controllers;

use App\Services\GeminiService;
use App\Models\ChatHistory;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ChatbotController extends Controller
{
    protected $geminiService;

    public function __construct(GeminiService $geminiService)
    {
        $this->middleware('auth');
        $this->geminiService = $geminiService;
    }

    public function index()
    {
        $user = Auth::user();
        $recentRecommendation = session('recomendation_data', null);

        // Jika session kosong, ambil rekomendasi terakhir dari database
        if (!$recentRecommendation) {
            $lastRec = \App\Models\Recommendation::where('user_id', $user->id)
                ->latest()
                ->first();

            if ($lastRec) {
                $hasil = json_decode($lastRec->hasil_rekomendasi, true);
                $topJurusan = $hasil[0] ?? null;
                $recentRecommendation = [
                    'jurusan' => $topJurusan['jurusan'] ?? null,
                    'skor' => $topJurusan['skor'] ?? null,
                    'nilai' => $lastRec->nilai_akademik,
                    'minat' => $lastRec->minat,
                    'pref_studi' => $lastRec->preferensi_studi,
                ];
            }
        }

        return view('chatbot.index', [
            'recommendation' => $recentRecommendation
        ]);
    }

    public function send(Request $request)
    {
        $request->validate([
            'message' => 'required|string|max:1000',
            'chatHistory' => 'nullable|array|max:20',
            'chatHistory.*.role' => 'required|string|in:user,ai',
            'chatHistory.*.text' => 'required|string|max:2000',
        ]);

        $message = $request->input('message');
        $chatHistory = $request->input('chatHistory', []);
        $user = Auth::user();
        $recentRecommendation = session('recomendation_data', []);

        // Jika session kosong, ambil rekomendasi terakhir dari database
        if (empty($recentRecommendation)) {
            $lastRec = \App\Models\Recommendation::where('user_id', $user->id)
                ->latest()
                ->first();

            if ($lastRec) {
                $hasil = json_decode($lastRec->hasil_rekomendasi, true);
                $topJurusan = $hasil[0] ?? null;
                $recentRecommendation = [
                    'jurusan' => $topJurusan['jurusan'] ?? null,
                    'skor' => $topJurusan['skor'] ?? null,
                    'nilai' => $lastRec->nilai_akademik,
                    'minat' => $lastRec->minat,
                    'pref_studi' => $lastRec->preferensi_studi,
                ];
            }
        }

        // Siapkan context untuk Gemini
        $context = [
            'recommendation' => $recentRecommendation['jurusan'] ?? null,
            'score' => isset($recentRecommendation['skor']) ? number_format($recentRecommendation['skor'] * 100, 1) : null,
            'profile' => [
                'nama' => $user->name,
                'kelompok' => $user->kelompok_asal,
                'nilai' => $recentRecommendation['nilai'] ?? null,
                'minat' => $recentRecommendation['minat'] ?? null,
                'pref' => $recentRecommendation['pref_studi'] ?? null,
            ]
        ];

        // Panggil Gemini API dengan conversation history
        $response = $this->geminiService->chat($message, $context, $chatHistory);

        // Simpan chat ke database
        if ($user && isset($response['message'])) {
            ChatHistory::create([
                'user_id' => $user->id,
                'prompt' => $message,
                'response' => $response['message'],
            ]);
        }

        return response()->json($response);
    }

    /**
     * Tampilkan history chat
     */
    public function historyChat()
    {
        $user = Auth::user();
        $chatHistories = ChatHistory::where('user_id', $user->id)
            ->orderBy('created_at', 'desc')
            ->get();

        return view('history.chat', compact('chatHistories'));
    }
}
