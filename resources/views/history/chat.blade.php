<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>History Chat - Sistem Pemilihan Jurusan</title>
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
    <style>
        .gradient-maroon {
            background: linear-gradient(135deg, #5B7B89 0%, #7B9BA5 100%);
        }
        .text-maroon {
            color: #5B7B89;
        }
        .border-maroon {
            border-color: #5B7B89;
        }
        .bg-cream {
            background-color: #F8FAFC;
        }
        .user-message {
            background-color: #5B7B89;
            color: white;
            border-radius: 12px 12px 0 12px;
        }
        .ai-message {
            background-color: #f3f4f6;
            color: #1f2937;
            border-radius: 12px 12px 12px 0;
        }
        .session-card {
            transition: all 0.2s ease;
        }
        .session-card:hover {
            box-shadow: 0 4px 12px rgba(0,0,0,0.1);
        }
        .chat-detail {
            display: none;
        }
        .chat-detail.active {
            display: block;
        }
    </style>
</head>
<body class="bg-cream">
    <!-- Header -->
    <header class="gradient-maroon text-white shadow-lg sticky top-0 z-50">
        <div class="container mx-auto px-4 sm:px-6 py-4 sm:py-6 flex flex-col sm:flex-row justify-between items-start sm:items-center gap-3 sm:gap-4">
            <div>
                <h1 class="text-xl sm:text-2xl md:text-3xl font-bold">Riwayat Konsultasi</h1>
                <p class="text-xs sm:text-sm text-yellow-300 font-semibold mt-1">Arsip Sesi Konsultasi AI</p>
            </div>
            <div class="flex items-center gap-2 sm:gap-4 w-full sm:w-auto">
                <a href="{{ route('chatbot.index') }}" class="block sm:inline-block flex-1 sm:flex-none text-center bg-white text-maroon font-bold py-2 px-3 sm:px-4 rounded-lg hover:bg-gray-100 transition text-xs sm:text-sm">
                    Konsultasi Baru
                </a>
                <a href="{{ url('/dashboard') }}" class="block sm:inline-block flex-1 sm:flex-none text-center bg-yellow-400 text-maroon font-bold py-2 px-3 sm:px-4 rounded-lg hover:bg-yellow-300 transition text-xs sm:text-sm">
                    Kembali Dashboard
                </a>
            </div>
        </div>
    </header>

    <!-- Main Content -->
    <div class="container mx-auto px-4 sm:px-6 py-6 sm:py-12">
        @if($sessions && $sessions->count() > 0)
            <div class="space-y-4 sm:space-y-6">
                @foreach($sessions as $sessionKey => $session)
                    <div class="session-card bg-white rounded-lg shadow-lg overflow-hidden border-l-4 border-maroon">
                        <!-- Session Header (clickable) -->
                        <div class="p-4 sm:p-6 cursor-pointer flex flex-col sm:flex-row justify-between items-start sm:items-center gap-3"
                             onclick="toggleSession('session-{{ $loop->index }}')">
                            <div class="flex-1 min-w-0">
                                <div class="flex items-center gap-2 mb-1">
                                    <span class="text-lg">💬</span>
                                    <h3 class="text-sm sm:text-base font-bold text-maroon truncate">
                                        {{ $session['first_message'] }}
                                    </h3>
                                </div>
                                <div class="flex flex-wrap items-center gap-2 sm:gap-4 text-xs text-gray-500">
                                    <span>{{ $session['started_at']->format('d M Y, H:i') }}</span>
                                    <span class="bg-gray-100 px-2 py-0.5 rounded-full">{{ $session['message_count'] }} pesan</span>
                                    @if($session['started_at']->format('Y-m-d') !== $session['last_at']->format('Y-m-d'))
                                        <span>s/d {{ $session['last_at']->format('d M Y, H:i') }}</span>
                                    @endif
                                </div>
                                @if(!empty($session['recommendation']))
                                    <div class="mt-2 flex items-center gap-2">
                                        <span class="inline-flex items-center gap-1 bg-blue-50 border border-blue-200 text-blue-700 text-xs px-2 py-0.5 rounded-full">
                                            📊 {{ $session['recommendation']['jurusan'] }} 
                                            ({{ number_format(($session['recommendation']['skor'] > 1 ? $session['recommendation']['skor'] : $session['recommendation']['skor'] * 100), 1) }}%)
                                        </span>
                                        <span class="text-xs text-gray-400">
                                            Rekomendasi {{ $session['recommendation']['tanggal']->format('d M Y') }}
                                        </span>
                                    </div>
                                @endif
                            </div>
                            <div class="flex items-center gap-2 w-full sm:w-auto">
                                <a href="{{ route('chatbot.index', ['session' => $session['session_id']]) }}" 
                                   class="flex-1 sm:flex-none text-center gradient-maroon text-white font-bold py-2 px-3 sm:px-4 rounded-lg hover:opacity-90 transition text-xs sm:text-sm"
                                   onclick="event.stopPropagation()">
                                    Lanjutkan
                                </a>
                                <button class="flex-1 sm:flex-none text-center bg-gray-200 text-gray-700 font-bold py-2 px-3 sm:px-4 rounded-lg hover:bg-gray-300 transition text-xs sm:text-sm"
                                        id="toggle-btn-{{ $loop->index }}">
                                    Lihat Detail
                                </button>
                            </div>
                        </div>

                        <!-- Session Chat Detail (toggleable) -->
                        <div class="chat-detail border-t border-gray-200 p-4 sm:p-6 bg-gray-50" id="session-{{ $loop->index }}">
                            <div class="space-y-3 sm:space-y-4 max-h-96 overflow-y-auto">
                                @foreach($session['chats'] as $chat)
                                    <!-- User Message -->
                                    <div class="flex justify-end">
                                        <div class="user-message max-w-xs sm:max-w-md lg:max-w-lg xl:max-w-xl p-3 sm:p-4">
                                            <p class="text-xs sm:text-sm">{{ $chat->prompt }}</p>
                                            <p class="text-xs mt-2 opacity-75">{{ $chat->created_at->format('H:i') }}</p>
                                        </div>
                                    </div>

                                    <!-- AI Response -->
                                    <div class="flex justify-start">
                                        <div class="ai-message max-w-xs sm:max-w-md lg:max-w-lg xl:max-w-xl p-3 sm:p-4">
                                            <p class="text-xs sm:text-sm">{{ preg_replace(['/\*\*(.*?)\*\*/s', '/\*(.*?)\*/s', '/^#{1,6}\s+/m', '/`(.*?)`/s'], ['$1', '$1', '', '$1'], $chat->response) }}</p>
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
        @else
            <div class="bg-white rounded-lg shadow-lg p-8 sm:p-12 text-center">
                <div class="text-5xl sm:text-6xl mb-4">💬</div>
                <h3 class="text-xl sm:text-2xl font-bold text-maroon mb-2">Belum Ada Riwayat Konsultasi</h3>
                <p class="text-xs sm:text-sm md:text-base text-gray-700 mb-6">Anda belum melakukan konsultasi dengan AI. Mulai konsultasi sekarang!</p>
                <a href="{{ route('chatbot.index') }}" class="inline-block gradient-maroon text-white font-bold py-2 sm:py-3 px-6 sm:px-8 rounded-lg hover:opacity-90 transition text-sm sm:text-base">
                    Mulai Konsultasi
                </a>
            </div>
        @endif
    </div>

    <!-- Footer -->
    <footer class="gradient-maroon text-white mt-8 sm:mt-12 py-4 sm:py-6">
        <div class="container mx-auto px-4 sm:px-6 text-center">
            <p class="text-xs sm:text-sm text-yellow-200">Sistem Pemilihan Jurusan &copy; 2026 | SMA Bima Ambulu</p>
        </div>
    </footer>

    <script>
        function toggleSession(sessionId) {
            const detail = document.getElementById(sessionId);
            const index = sessionId.replace('session-', '');
            const btn = document.getElementById('toggle-btn-' + index);
            
            if (detail.classList.contains('active')) {
                detail.classList.remove('active');
                if (btn) btn.textContent = 'Lihat Detail';
            } else {
                detail.classList.add('active');
                if (btn) btn.textContent = 'Sembunyikan';
            }
        }
    </script>
</body>
</html>
