<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>History Rekomendasi - Sistem Pemilihan Jurusan</title>
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
    </style>
</head>
<body class="bg-cream">
    <!-- Header -->
    <header class="gradient-maroon text-white shadow-lg sticky top-0 z-50">
        <div class="container mx-auto px-4 sm:px-6 py-4 sm:py-6 flex flex-col sm:flex-row justify-between items-start sm:items-center gap-3 sm:gap-4">
            <div>
                <h1 class="text-xl sm:text-2xl md:text-3xl font-bold">History Rekomendasi</h1>
                <p class="text-xs sm:text-sm text-yellow-300 font-semibold mt-1">Semua Analisis Anda</p>
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
        @if($recommendations && $recommendations->count() > 0)
            <div class="space-y-4 sm:space-y-6">
                @foreach($recommendations as $rec)
                    <div class="bg-white rounded-lg shadow-lg p-5 sm:p-6 border-l-4 border-maroon hover:shadow-xl transition">
                        <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-3 sm:gap-4 mb-4 sm:mb-6">
                            <div>
                                <h3 class="text-lg sm:text-xl font-bold text-maroon mb-2">
                                    Analisis - {{ \Carbon\Carbon::parse($rec->created_at)->format('d M Y H:i') }}
                                </h3>
                                <p class="text-xs sm:text-sm text-gray-600">{{ $rec->created_at->diffForHumans() }}</p>
                            </div>
                            <button onclick="toggleDetail({{ $loop->index }})" class="w-full sm:w-auto bg-maroon text-white font-bold py-2 px-4 rounded-lg hover:opacity-90 transition text-xs sm:text-sm">
                                Lihat Detail
                            </button>
                        </div>

                        <div id="detail-{{ $loop->index }}" class="hidden">
                            <div class="grid grid-cols-2 md:grid-cols-4 gap-2 sm:gap-4 mb-4 sm:mb-6">
                                <div class="bg-gray-50 p-3 sm:p-4 rounded-lg">
                                    <p class="text-xs sm:text-sm text-gray-600 font-semibold">Minat</p>
                                    <p class="text-sm sm:text-base font-bold text-maroon mt-1">{{ $rec->minat ?? '-' }}</p>
                                </div>
                                <div class="bg-gray-50 p-3 sm:p-4 rounded-lg">
                                    <p class="text-xs sm:text-sm text-gray-600 font-semibold">Pref. Belajar</p>
                                    <p class="text-sm sm:text-base font-bold text-maroon mt-1">{{ $rec->preferensi_studi ?? '-' }}</p>
                                </div>
                                <div class="bg-gray-50 p-3 sm:p-4 rounded-lg">
                                    <p class="text-xs sm:text-sm text-gray-600 font-semibold">Cita-Cita</p>
                                    <p class="text-sm sm:text-base font-bold text-maroon mt-1">{{ Str::limit($rec->cita_cita ?? '-', 15) }}</p>
                                </div>
                                <div class="bg-gray-50 p-3 sm:p-4 rounded-lg">
                                    <p class="text-xs sm:text-sm text-gray-600 font-semibold">Prestasi</p>
                                    <p class="text-sm sm:text-base font-bold text-maroon mt-1">{{ Str::limit($rec->prestasi ?? '-', 15) }}</p>
                                </div>
                            </div>

                            <div class="bg-yellow-50 p-4 sm:p-6 rounded-lg">
                                <h4 class="font-bold text-maroon mb-3 sm:mb-4">Top 3 Rekomendasi Jurusan</h4>
                                <div class="space-y-2 sm:space-y-3">
                                    @if($rec->hasil_rekomendasi)
                                        @foreach(array_slice($rec->hasil_rekomendasi, 0, 3) as $index => $hasil)
                                            <div class="flex flex-col sm:flex-row items-start sm:items-center justify-between gap-2 sm:gap-4 p-3 sm:p-4 bg-white rounded-lg border border-yellow-300">
                                                <div class="flex items-center gap-3 sm:gap-4">
                                                    <span class="text-lg sm:text-xl font-bold text-yellow-500">{{ $index + 1 }}</span>
                                                    <span class="text-sm sm:text-base font-bold text-maroon">{{ $hasil['jurusan'] ?? '-' }}</span>
                                                </div>
                                                <span class="text-xs sm:text-sm bg-maroon text-white px-3 py-1 rounded-full font-bold">
                                                    @php $skorVal = $hasil['skor'] ?? 0; @endphp
                                                    {{ number_format(($skorVal > 1 ? $skorVal : $skorVal * 100), 1) }}%
                                                </span>
                                            </div>
                                        @endforeach
                                    @endif
                                </div>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
        @else
            <div class="bg-white rounded-lg shadow-lg p-8 sm:p-12 text-center">
                <div class="text-5xl sm:text-6xl mb-4">📊</div>
                <h3 class="text-xl sm:text-2xl font-bold text-maroon mb-2">Belum Ada History</h3>
                <p class="text-xs sm:text-sm md:text-base text-gray-700 mb-6">Anda belum melakukan analisis rekomendasi. Mulai sekarang!</p>
                <a href="{{ url('/rekomendasi') }}" class="inline-block bg-maroon text-white font-bold py-2 sm:py-3 px-6 sm:px-8 rounded-lg hover:opacity-90 transition text-sm sm:text-base">
                    Mulai Analisis
                </a>
            </div>
        @endif
    </div>

    <script>
        function toggleDetail(index) {
            const detail = document.getElementById('detail-' + index);
            detail.classList.toggle('hidden');
        }
    </script>
</body>
</html>
