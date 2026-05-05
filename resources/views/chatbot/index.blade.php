<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Konsultasi BK Virtual - Sistem Pemilihan Jurusan</title>
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
        .chat-container {
            height: 400px;
            overflow-y: auto;
        }
        @media (min-width: 768px) {
            .chat-container {
                height: 500px;
            }
        }
        @media (min-width: 1024px) {
            .chat-container {
                height: 600px;
            }
        }
        .message {
            margin-bottom: 12px;
            animation: slideIn 0.3s ease;
        }
        .message.user {
            margin-left: 30px;
        }
        .message.ai {
            margin-right: 30px;
        }
        @media (min-width: 640px) {
            .message.user {
                margin-left: 50px;
            }
            .message.ai {
                margin-right: 50px;
            }
        }
        .user-msg {
            background-color: #5B7B89;
            color: white;
            padding: 10px 12px;
            border-radius: 12px 12px 0 12px;
            word-wrap: break-word;
            font-size: 14px;
        }
        .ai-msg {
            background-color: #f3f4f6;
            color: #1f2937;
            padding: 10px 12px;
            border-radius: 12px 12px 12px 0;
            word-wrap: break-word;
            font-size: 14px;
        }
        @media (min-width: 640px) {
            .user-msg,
            .ai-msg {
                padding: 12px 16px;
                font-size: 15px;
            }
        }
        .input-error {
            border-color: #ef4444 !important;
            background-color: #fef2f2 !important;
        }
        .error-message {
            color: #dc2626;
            font-size: 13px;
            margin-top: 6px;
            display: flex;
            align-items: center;
            gap: 5px;
            animation: slideIn 0.3s ease-out;
        }
        .error-message::before {
            content: "⚠️";
        }
        @keyframes slideIn {
            from {
                opacity: 0;
                transform: translateY(-5px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }
    </style>
</head>
<body class="bg-cream">
    <!-- Header -->
    <header class="gradient-maroon text-white shadow-lg sticky top-0 z-50">
        <div class="container mx-auto px-4 sm:px-6 py-4 sm:py-6 flex flex-col sm:flex-row justify-between items-start sm:items-center gap-3 sm:gap-4">
            <div>
                <h1 class="text-xl sm:text-2xl md:text-3xl font-bold">Konsultasi BK Virtual</h1>
                <p class="text-xs sm:text-sm text-yellow-300 font-semibold mt-1">Konseling Pemilihan Jurusan Politeknik Negeri Jember</p>
            </div>
            <div class="flex items-center gap-2 sm:gap-4 w-full sm:w-auto">
                <a href="{{ route('chatbot.index') }}" class="block sm:inline-block flex-1 sm:flex-none text-center bg-white text-maroon font-bold py-2 px-3 sm:px-4 rounded-lg hover:bg-gray-100 transition text-xs sm:text-sm">
                    Sesi Baru
                </a>
                <a href="{{ url('/dashboard') }}" class="block sm:inline-block flex-1 sm:flex-none text-center bg-yellow-400 text-maroon font-bold py-2 px-3 sm:px-4 rounded-lg hover:bg-yellow-300 transition text-xs sm:text-sm">
                    Kembali
                </a>
            </div>
        </div>
    </header>

    <!-- Main Content -->
    <div class="container mx-auto px-4 sm:px-6 py-4 sm:py-8">
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-4 sm:gap-8">
            <!-- Info Panel -->
            <div class="lg:col-span-1 order-2 lg:order-1">
                <div class="bg-white rounded-lg shadow-lg p-4 sm:p-6 border-l-4 border-maroon">
                    <h3 class="text-base sm:text-lg font-bold text-maroon mb-3 sm:mb-4">👨‍🏫 Konselor BK Virtual</h3>
                    
                    @if($recommendation && !empty($recommendation['jurusan']))
                        <div class="space-y-3 sm:space-y-4">
                            <div class="bg-green-50 border border-green-200 rounded-lg p-3">
                                <p class="text-xs text-green-600 font-semibold">Rekomendasi Terakhir</p>
                                <p class="text-base sm:text-lg font-bold text-maroon">{{ $recommendation['jurusan'] }}</p>
                                <p class="text-xs sm:text-sm text-gray-600">Skor: {{ isset($recommendation['skor']) ? number_format(($recommendation['skor'] > 1 ? $recommendation['skor'] : $recommendation['skor'] * 100), 1) : '-' }}%</p>
                            </div>

                            @if(!empty($recommendation['top3']) && count($recommendation['top3']) > 1)
                                <div class="mt-2 text-xs text-gray-500">
                                    <p class="font-semibold mb-1">Alternatif lain:</p>
                                    @foreach(array_slice($recommendation['top3'], 1) as $alt)
                                        <p>• {{ $alt['jurusan'] }} ({{ number_format(($alt['skor'] > 1 ? $alt['skor'] : $alt['skor'] * 100), 1) }}%)</p>
                                    @endforeach
                                </div>
                            @endif
                        </div>
                    @else
                        <div class="bg-yellow-50 border border-yellow-200 rounded-lg p-3">
                            <p class="text-xs sm:text-sm text-yellow-800">💡 Belum ada rekomendasi. Tapi tenang, kamu tetap bisa konsultasi!</p>
                        </div>
                    @endif

                    <hr class="my-3 sm:my-4">

                    <div>
                        <p class="text-xs text-gray-600 font-semibold mb-2 sm:mb-3">Contoh Pertanyaan:</p>
                        <ul class="space-y-1 sm:space-y-2 text-xs">
                            <li class="text-gray-700">• Jurusan apa yang cocok buat aku?</li>
                            <li class="text-gray-700">• Prospek kerja jurusan TI?</li>
                            <li class="text-gray-700">• Aku bingung pilih jurusan</li>
                            <li class="text-gray-700">• Skill apa yang dibutuhkan?</li>
                            <li class="text-gray-700">• Bedanya IPA dan IPS?</li>
                            <li class="text-gray-700">• Tips sukses kuliah di Polije?</li>
                        </ul>
                    </div>
                </div>
            </div>

            <!-- Chat Area -->
            <div class="lg:col-span-2 order-1 lg:order-2">
                <div class="bg-white rounded-lg shadow-lg p-4 sm:p-6 border border-gray-200 flex flex-col" style="height: 550px;">
                    <!-- Chat Messages -->
                    <div class="chat-container flex-1 mb-3 sm:mb-4" id="chatContainer">
                        <div class="message ai">
                            <div class="ai-msg">
                                <p id="initialGreeting"></p>
                            </div>
                        </div>
                    </div>

                    <!-- Input Area -->
                    <div class="border-t border-gray-200 pt-3 sm:pt-4">
                        <form id="chatForm" class="space-y-2">
                            @csrf
                            <div class="flex gap-2">
                                <div class="flex-1 relative">
                                    <input 
                                        type="text" 
                                        id="messageInput" 
                                        name="message" 
                                        placeholder="Ketik pertanyaan..." 
                                        class="w-full px-3 sm:px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:border-maroon text-sm transition-colors"
                                        autocomplete="off"
                                    >
                                    <div id="errorMessage" class="error-message hidden"></div>
                                </div>
                                <button 
                                    type="submit" 
                                    class="gradient-maroon text-white font-bold py-2 px-4 sm:px-6 rounded-lg hover:opacity-90 transition text-sm sm:text-base whitespace-nowrap disabled:opacity-50 disabled:cursor-not-allowed"
                                    id="sendBtn"
                                >
                                    Kirim
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>

        <!-- History Chat Section -->
        @php
            $chatHistories = $chatHistories ?? [];
        @endphp
        
        @if(count($chatHistories) > 0)
            <div class="mt-8 sm:mt-12">
                <div class="bg-white rounded-lg shadow-lg p-5 sm:p-8 border-l-4 border-orange-500">
                    <div class="flex items-center gap-3 mb-6">
                        <div class="w-12 h-12 sm:w-14 sm:h-14 rounded-lg bg-orange-100 flex items-center justify-center text-2xl flex-shrink-0">💬</div>
                        <div>
                            <h2 class="text-lg sm:text-xl md:text-2xl font-bold text-maroon">Riwayat Chat</h2>
                            <p class="text-xs sm:text-sm text-gray-600">Percakapan Anda dengan AI sebelumnya</p>
                        </div>
                    </div>

                    <div class="space-y-3 sm:space-y-4 max-h-96 overflow-y-auto">
                        @foreach($chatHistories as $chat)
                            <a href="{{ route('chatbot.index', ['session' => $chat->id_sesi]) }}" class="block border border-gray-200 rounded-lg p-4 hover:shadow-md hover:border-maroon transition">
                                <p class="text-xs sm:text-sm text-gray-500 mb-2">{{ $chat->created_at->format('d M Y - H:i') }}</p>
                                <p class="text-xs sm:text-sm text-gray-700 line-clamp-2 leading-relaxed">
                                    <span class="font-semibold">Anda:</span> {{ $chat->prompt ?? 'Tidak ada pesan' }}
                                </p>
                            </a>
                        @endforeach
                    </div>
                </div>
            </div>
        @endif
    </div>

    <script>
        // Session ID for this chat
        const sessionId = @json($sessionId);
        const previousMessages = @json($previousMessages ?? []);
        const recommendationId = @json($recommendationId ?? null);

        // Langsung update URL agar refresh tetap di sesi ini
        if (!window.location.search.includes('session=')) {
            window.history.replaceState({}, '', '{{ route("chatbot.index") }}?session=' + sessionId);
        }

        // Time-aware greeting
        (function() {
            const hour = new Date().getHours();
            let sapaan;
            if (hour >= 3 && hour < 11) {
                sapaan = 'Selamat pagi';
            } else if (hour >= 11 && hour < 15) {
                sapaan = 'Selamat siang';
            } else if (hour >= 15 && hour < 18) {
                sapaan = 'Selamat sore';
            } else {
                sapaan = 'Selamat malam';
            }

            if (previousMessages.length > 0) {
                // Melanjutkan sesi lama — tampilkan info lanjutan
                document.getElementById('initialGreeting').textContent = sapaan + '. Anda melanjutkan sesi konsultasi sebelumnya. Silakan lanjutkan pertanyaan Anda.';
            } else {
                document.getElementById('initialGreeting').textContent = sapaan + '. Saya adalah konselor BK virtual SMA Bima Ambulu. Saya siap membantu Anda dalam pemilihan jurusan Politeknik Negeri Jember, informasi prospek karier, maupun konsultasi lanjutan studi. Silakan sampaikan pertanyaan Anda.';
            }
        })();

        const chatForm = document.getElementById('chatForm');
        const messageInput = document.getElementById('messageInput');
        const chatContainer = document.getElementById('chatContainer');
        const sendBtn = document.getElementById('sendBtn');
        const errorMessage = document.getElementById('errorMessage');

        // Track conversation history for multi-turn context
        let conversationHistory = [];

        // Validasi input saat user mengetik
        messageInput.addEventListener('input', function() {
            const message = this.value.trim();
            
            if (message === '') {
                // Show error state
                this.classList.add('input-error');
                errorMessage.textContent = 'Pesan tidak boleh kosong';
                errorMessage.classList.remove('hidden');
                sendBtn.disabled = true;
            } else {
                // Clear error state
                this.classList.remove('input-error');
                errorMessage.classList.add('hidden');
                sendBtn.disabled = false;
            }
        });

        // Validasi saat blur
        messageInput.addEventListener('blur', function() {
            if (this.value.trim() === '') {
                this.classList.add('input-error');
                errorMessage.textContent = 'Pesan tidak boleh kosong';
                errorMessage.classList.remove('hidden');
            }
        });

        // Clear error saat focus
        messageInput.addEventListener('focus', function() {
            if (this.value.trim() === '') {
                this.classList.add('input-error');
                errorMessage.textContent = 'Ketik sesuatu untuk melanjutkan';
                errorMessage.classList.remove('hidden');
            }
        });

        // Load previous messages if resuming session
        if (previousMessages.length > 0) {
            previousMessages.forEach(function(msg) {
                addMessage(msg.text, msg.role === 'user' ? 'user' : 'ai');
                conversationHistory.push({ role: msg.role, text: msg.text });
            });
        }

        // Initialize button state
        sendBtn.disabled = true;

        chatForm.addEventListener('submit', async (e) => {
            e.preventDefault();
            
            const message = messageInput.value.trim();
            
            // Validasi pesan tidak kosong
            if (!message) {
                messageInput.classList.add('input-error');
                errorMessage.textContent = 'Pesan tidak boleh kosong, ketik sesuatu';
                errorMessage.classList.remove('hidden');
                messageInput.focus();
                return;
            }

            // Clear error sebelum mengirim
            messageInput.classList.remove('input-error');
            errorMessage.classList.add('hidden');

            // Add user message to UI
            addMessage(message, 'user');
            messageInput.value = '';
            sendBtn.disabled = true;
            sendBtn.textContent = 'Mengirim...';

            // Add to history
            conversationHistory.push({ role: 'user', text: message });

            try {
                // Truncate history texts to avoid validation failure
                const trimmedHistory = conversationHistory.slice(0, -1).map(h => ({
                    role: h.role,
                    text: h.text.substring(0, 1500)
                }));

                const response = await fetch('{{ route("chatbot.send") }}', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'Accept': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('input[name="_token"]').value
                    },
                    body: JSON.stringify({ 
                        message: message,
                        sessionId: sessionId,
                        recommendationId: recommendationId,
                        chatHistory: trimmedHistory
                    })
                });

                if (!response.ok) {
                    const errorData = await response.json().catch(() => null);
                    const errorMsg = errorData?.message || 'Terjadi kesalahan pada server (kode: ' + response.status + '). Silakan coba lagi.';
                    addMessage(errorMsg, 'ai');
                    conversationHistory.pop(); // Remove failed user message from history
                    return;
                }

                const data = await response.json();

                if (data.success) {
                    // Strip markdown formatting (**, *, #, etc.)
                    let cleanMessage = data.message
                        .replace(/\*\*(.*?)\*\*/g, '$1')
                        .replace(/\*(.*?)\*/g, '$1')
                        .replace(/^#{1,6}\s+/gm, '')
                        .replace(/`(.*?)`/g, '$1');
                    addMessage(cleanMessage, 'ai');
                    // Add AI response to history
                    conversationHistory.push({ role: 'ai', text: cleanMessage });
                } else {
                    addMessage(data.message || 'Maaf, terjadi kesalahan.', 'ai');
                }

                // Keep history manageable (max 20 turns)
                if (conversationHistory.length > 20) {
                    conversationHistory = conversationHistory.slice(-16);
                }
            } catch (error) {
                addMessage('Terjadi kesalahan koneksi. Silakan coba lagi.', 'ai');
            } finally {
                sendBtn.textContent = 'Kirim';
                // Re-enable button only if input has text
                const currentMessage = messageInput.value.trim();
                sendBtn.disabled = currentMessage === '';
            }
        });

        function addMessage(text, sender) {
            const messageDiv = document.createElement('div');
            messageDiv.className = `message ${sender}`;
            
            const msgContent = document.createElement('div');
            msgContent.className = `${sender}-msg`;
            msgContent.textContent = text;
            
            messageDiv.appendChild(msgContent);
            chatContainer.appendChild(messageDiv);

            // Scroll to bottom
            chatContainer.scrollTop = chatContainer.scrollHeight;
        }

        // Focus on input
        messageInput.focus();
    </script>
</body>
</html>
