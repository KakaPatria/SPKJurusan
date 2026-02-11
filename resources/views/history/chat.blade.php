<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>History Chat - Sistem Pemilihan Jurusan</title>
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
    <style>
        .gradient-maroon {
            background: linear-gradient(135deg, #6B2C2C 0%, #8B3E3E 100%);
        }
        .text-maroon {
            color: #6B2C2C;
        }
        .border-maroon {
            border-color: #6B2C2C;
        }
        .bg-cream {
            background-color: #FFF9F5;
        }
        .user-message {
            background-color: #6B2C2C;
            color: white;
            border-radius: 12px 12px 0 12px;
        }
        .ai-message {
            background-color: #f3f4f6;
            color: #1f2937;
            border-radius: 12px 12px 12px 0;
        }
    </style>
</head>
<body class="bg-cream">
    <!-- Header -->
    <header class="gradient-maroon text-white shadow-lg sticky top-0 z-50">
        <div class="container mx-auto px-4 sm:px-6 py-4 sm:py-6 flex flex-col sm:flex-row justify-between items-start sm:items-center gap-3 sm:gap-4">
            <div>
                <h1 class="text-xl sm:text-2xl md:text-3xl font-bold">History Chat</h1>
                <p class="text-xs sm:text-sm text-yellow-300 font-semibold mt-1">Riwayat Konsultasi AI</p>
            </div>
            <div class="flex items-center gap-2 sm:gap-4 w-full sm:w-auto">
                <a href="{{ url('/dashboard') }}" class="block sm:inline-block flex-1 sm:flex-none text-center bg-yellow-400 text-maroon font-bold py-2 px-3 sm:px-4 rounded-lg hover:bg-yellow-300 transition text-xs sm:text-sm">
                    Kembali Dashboard
                </a>
            </div>
        </div>
    </header>

    <!-- Main Content -->
    <div class="container mx-auto px-4 sm:px-6 py-6 sm:py-12">
        @if($chatHistories && $chatHistories->count() > 0)
            <div class="space-y-4 sm:space-y-6">
                <!-- Group by date -->
                @php
                    $groupedByDate = $chatHistories->groupBy(function($chat) {
                        return $chat->created_at->format('Y-m-d');
                    });
                @endphp

                @foreach($groupedByDate as $date => $chats)
                    <div>
                        <h3 class="text-base sm:text-lg font-bold text-maroon mb-3 sm:mb-4">
                            {{ \Carbon\Carbon::parse($date)->format('d F Y') }}
                        </h3>
                        <div class="space-y-3 sm:space-y-4">
                            @foreach($chats as $chat)
                                <div class="bg-white rounded-lg shadow-lg p-4 sm:p-6 border-l-4 border-maroon">
                                    <div class="space-y-3 sm:space-y-4">
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
                                                <p class="text-xs sm:text-sm">{{ $chat->response }}</p>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    </div>
                @endforeach
            </div>
        @else
            <div class="bg-white rounded-lg shadow-lg p-8 sm:p-12 text-center">
                <div class="text-5xl sm:text-6xl mb-4">💬</div>
                <h3 class="text-xl sm:text-2xl font-bold text-maroon mb-2">Belum Ada Chat</h3>
                <p class="text-xs sm:text-sm md:text-base text-gray-700 mb-6">Anda belum melakukan chat dengan AI. Mulai konsultasi sekarang!</p>
                <a href="{{ url('/chatbot') }}" class="inline-block bg-maroon text-white font-bold py-2 sm:py-3 px-6 sm:px-8 rounded-lg hover:opacity-90 transition text-sm sm:text-base">
                    Mulai Chat
                </a>
            </div>
        @endif
    </div>

    <!-- Footer -->
    <footer class="gradient-maroon text-white mt-8 sm:mt-12 py-4 sm:py-6">
        <div class="container mx-auto px-4 sm:px-6 text-center">
            <p class="text-xs sm:text-sm text-yellow-200">Sistem Pemilihan Jurusan © 2026 | SMA Bima Ambulu</p>
        </div>
    </footer>
</body>
</html>
