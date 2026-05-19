<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>History Rekomendasi - Sistem Pemilihan Jurusan</title>
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
    <style>
        .gradient-maroon {
            background: linear-gradient(135deg, #6B7280 0%, #8B95A5 100%);
        }
        .text-maroon {
            color: #6B7280;
        }
        .border-maroon {
            border-color: #6B7280;
        }
        .bg-cream {
            background-color: #F8FAFC;
        }
    </style>
</head>
<body class="bg-cream">
    <!-- Header -->
    <header class="gradient-maroon text-white shadow-lg sticky top-0 z-50">
        <div class="container mx-auto px-4 sm:px-6 py-4 sm:py-6 flex flex-col sm:flex-row justify-between items-start sm:items-center gap-3 sm:gap-4">
            <div>
                <h1 class="text-2xl md:text-3xl font-bold">📊 History Rekomendasi</h1>
                <p class="text-sm text-yellow-200 font-semibold mt-1">Semua Analisis Anda</p>
            </div>
            <a href="{{ url('/dashboard') }}" class="bg-yellow-400 text-maroon font-bold py-2 px-4 rounded-lg hover:bg-yellow-300 transition text-sm">
                ← Kembali Dashboard
            </a>
        </div>
    </header>

    <!-- Main Content -->
    <div class="container mx-auto px-4 sm:px-6 py-8 sm:py-12">
        @if($recommendations && $recommendations->count() > 0)
            <div class="space-y-6">
                @foreach($recommendations as $rec)
                    <div class="bg-white rounded-lg shadow-lg overflow-hidden border-l-4 border-maroon hover:shadow-xl transition">
                        <!-- Header Card -->
                        <div class="bg-gradient-to-r from-yellow-400 to-yellow-300 p-6 text-gray-800 cursor-pointer hover:bg-gradient-to-r hover:from-yellow-300 hover:to-yellow-200 transition" onclick="toggleDetail({{ $loop->index }})">
                            <div class="flex justify-between items-start gap-4">
                                <div class="flex-1">
                                    <h3 class="text-2xl font-bold mb-2">📅 {{ \Carbon\Carbon::parse($rec->created_at)->format('d M Y H:i') }}</h3>
                                    <p class="text-sm text-gray-700 font-semibold">{{ $rec->created_at->diffForHumans() }}</p>
                                </div>
                                <div class="flex-shrink-0">
                                    <span class="inline-block px-4 py-2 rounded-full bg-white text-maroon font-bold text-sm">
                                        Lihat Detail ↓
                                    </span>
                                </div>
                            </div>
                        </div>

                        <!-- Detail Card (Hidden by default) -->
                        <div id="detail-{{ $loop->index }}" class="hidden border-t-4 border-yellow-200 p-6">
                            <!-- Input Data Summary -->
                            <div class="mb-8">
                                <h4 class="text-lg font-bold text-maroon mb-4">📝 Data Input</h4>
                                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                    <div class="bg-blue-50 p-4 rounded-lg border-l-4 border-blue-500">
                                        <p class="text-xs text-blue-700 font-semibold mb-1">💭 Minat</p>
                                        <p class="text-sm font-bold text-gray-800">{{ $rec->minat ?? '-' }}</p>
                                    </div>
                                    <div class="bg-purple-50 p-4 rounded-lg border-l-4 border-purple-500">
                                        <p class="text-xs text-purple-700 font-semibold mb-1">🎓 Preferensi Studi</p>
                                        <p class="text-sm font-bold text-gray-800">{{ $rec->preferensi_studi ?? '-' }}</p>
                                    </div>
                                    <div class="bg-green-50 p-4 rounded-lg border-l-4 border-green-500">
                                        <p class="text-xs text-green-700 font-semibold mb-1">🎯 Cita-Cita</p>
                                        <p class="text-sm font-bold text-gray-800">{{ $rec->cita_cita ?? '-' }}</p>
                                    </div>
                                    <div class="bg-red-50 p-4 rounded-lg border-l-4 border-red-500">
                                        <p class="text-xs text-red-700 font-semibold mb-1">🏆 Prestasi</p>
                                        <p class="text-sm font-bold text-gray-800">{{ $rec->prestasi ?? '-' }}</p>
                                    </div>
                                </div>
                            </div>

                            <!-- Nilai Akademik -->
                            <div class="mb-8">
                                <h4 class="text-lg font-bold text-maroon mb-4">📚 Nilai Akademik</h4>
                                <div class="grid grid-cols-2 md:grid-cols-4 gap-3">
                                    @php
                                        $subjects = [
                                            ['name' => 'Matematika', 'key' => 'mtk', 'icon' => '🔢'],
                                            ['name' => 'Fisika', 'key' => 'fisika', 'icon' => '⚡'],
                                            ['name' => 'Kimia', 'key' => 'kimia', 'icon' => '🧪'],
                                            ['name' => 'Biologi', 'key' => 'biologi', 'icon' => '🔬'],
                                            ['name' => 'Ekonomi', 'key' => 'ekonomi', 'icon' => '💰'],
                                            ['name' => 'Geografi', 'key' => 'geografi', 'icon' => '🌍'],
                                            ['name' => 'Sosiologi', 'key' => 'sosiologi', 'icon' => '👥'],
                                            ['name' => 'Sejarah', 'key' => 'sejarah', 'icon' => '📜'],
                                        ];
                                    @endphp
                                    @foreach($subjects as $subject)
                                        @if($rec->{$subject['key']} !== null)
                                            <div class="bg-gray-50 p-3 rounded-lg border border-gray-200 hover:border-maroon transition">
                                                <p class="text-xs text-gray-600 font-semibold mb-1">{{ $subject['icon'] }} {{ $subject['name'] }}</p>
                                                <p class="text-lg font-bold text-maroon">{{ $rec->{$subject['key']} }}</p>
                                            </div>
                                        @endif
                                    @endforeach
                                </div>
                            </div>

                            <!-- Top 3 Rekomendasi Jurusan -->
                            <div>
                                <h4 class="text-lg font-bold text-maroon mb-4">🎯 Top 3 Rekomendasi Jurusan</h4>
                                @if($rec->hasil_rekomendasi && is_array($rec->hasil_rekomendasi))
                                    <div class="space-y-3">
                                        @foreach(array_slice($rec->hasil_rekomendasi, 0, 3) as $index => $hasil)
                                            <div class="flex items-center justify-between p-4 bg-gradient-to-r {{ $index === 0 ? 'from-yellow-50 to-yellow-100' : ($index === 1 ? 'from-gray-50 to-gray-100' : 'from-orange-50 to-orange-100') }} rounded-lg border-l-4 {{ $index === 0 ? 'border-yellow-500' : ($index === 1 ? 'border-gray-500' : 'border-orange-500') }}">
                                                <div class="flex items-center gap-4 flex-1">
                                                    <span class="inline-flex items-center justify-center w-10 h-10 rounded-full {{ $index === 0 ? 'bg-yellow-500 text-white' : ($index === 1 ? 'bg-gray-500 text-white' : 'bg-orange-500 text-white') }} font-bold text-lg">
                                                        {{ $index + 1 }}
                                                    </span>
                                                    <div>
                                                        <p class="text-sm font-bold text-gray-600">Rekomendasi {{ $index + 1 }}</p>
                                                        <p class="text-lg font-bold text-gray-800">{{ $hasil['jurusan'] ?? '-' }}</p>
                                                    </div>
                                                </div>
                                                @php $skorVal = $hasil['skor'] ?? 0; @endphp
                                                <span class="px-4 py-2 rounded-full font-bold text-white {{ $index === 0 ? 'bg-yellow-500' : ($index === 1 ? 'bg-gray-500' : 'bg-orange-500') }}">
                                                    {{ number_format(($skorVal > 1 ? $skorVal : $skorVal * 100), 1) }}%
                                                </span>
                                            </div>
                                        @endforeach
                                    </div>
                                @endif
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
        @else
            <div class="bg-white rounded-lg shadow-lg p-12 text-center">
                <div class="text-6xl mb-4">📊</div>
                <h3 class="text-2xl font-bold text-maroon mb-2">Belum Ada History</h3>
                <p class="text-gray-700 mb-6">Anda belum melakukan analisis rekomendasi. Mulai sekarang untuk melihat history!</p>
                <a href="{{ url('/rekomendasi') }}" class="inline-block bg-maroon text-white font-bold py-3 px-8 rounded-lg hover:opacity-90 transition">
                    🚀 Mulai Analisis
                </a>
            </div>
        @endif
    </div>

    <script>
        function toggleDetail(index) {
            const detail = document.getElementById('detail-' + index);
            if (detail) {
                detail.classList.toggle('hidden');
            }
        }
    </script>
</body>
</html>
