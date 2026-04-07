<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Hasil Rekomendasi - Sistem Pemilihan Jurusan</title>
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
        .bg-maroon-light {
            background-color: rgba(91, 123, 137, 0.1);
        }
    </style>
</head>
<body class="bg-cream">
    <!-- Header -->
    <header class="gradient-maroon text-white shadow-lg sticky top-0 z-50">
        <div class="container mx-auto px-4 sm:px-6 py-4 sm:py-6 flex flex-col sm:flex-row justify-between items-start sm:items-center gap-3 sm:gap-4">
            <div>
                <h1 class="text-xl sm:text-2xl md:text-3xl font-bold">Hasil Rekomendasi</h1>
                <p class="text-xs sm:text-sm text-yellow-300 font-semibold mt-1">Sistem Pemilihan Jurusan</p>
            </div>
            <div class="flex items-center gap-2 sm:gap-4 w-full sm:w-auto">
                <a href="{{ url('/dashboard') }}" class="block sm:inline-block flex-1 sm:flex-none text-center bg-yellow-400 text-maroon font-bold py-2 px-3 sm:px-4 rounded-lg hover:bg-yellow-300 transition text-xs sm:text-sm">
                    Kembali ke Dashboard
                </a>
            </div>
        </div>
    </header>

    <!-- Main Content -->
    <div class="container mx-auto px-4 sm:px-6 py-6 sm:py-12">
        <!-- Info Box -->
        <div class="bg-white border-2 border-maroon rounded-lg p-4 sm:p-6 mb-6 sm:mb-8 shadow-md">
            <h2 class="text-lg sm:text-xl font-bold text-maroon mb-2 sm:mb-3">Hasil Analisis</h2>
            <p class="text-xs sm:text-sm md:text-base text-gray-700">
                Berikut adalah hasil analisis sistem terhadap profil Anda. Jurusan diurutkan berdasarkan skor kesesuaian dari yang tertinggi.
            </p>
        </div>

        <!-- Ringkasan Input -->
        <div class="bg-white rounded-lg shadow-lg p-5 sm:p-6 mb-6 sm:mb-8 border-l-4 border-maroon">
            <h3 class="text-base sm:text-lg font-bold text-maroon mb-3 sm:mb-4">Data Profil Anda</h3>
            <div class="grid grid-cols-2 md:grid-cols-2 lg:grid-cols-4 gap-2 sm:gap-4">
                <div class="bg-maroon-light p-3 sm:p-4 rounded-lg">
                    <p class="text-xs sm:text-sm text-gray-600">Nilai Akademik</p>
                    <p class="text-lg sm:text-xl font-bold text-maroon">{{ $katNilai }}</p>
                    <p class="text-xs text-gray-500">Rata-rata: {{ number_format($average, 1) }}</p>
                </div>
                <div class="bg-yellow-50 p-3 sm:p-4 rounded-lg">
                    <p class="text-xs sm:text-sm text-gray-600">Preferensi Studi</p>
                    <p class="text-sm sm:text-lg font-bold text-maroon">{{ $prefStudi }}</p>
                </div>
                <div class="bg-maroon-light p-3 sm:p-4 rounded-lg">
                    <p class="text-xs sm:text-sm text-gray-600">Prestasi</p>
                    <p class="text-sm sm:text-lg font-bold text-maroon">
                        @if($prestasiScore >= 0.8)
                            Tinggi
                        @elseif($prestasiScore >= 0.6)
                            Sedang
                        @elseif($prestasiScore > 0)
                            Cukup
                        @else
                            Belum Ada
                        @endif
                    </p>
                </div>
                <div class="bg-yellow-50 p-3 sm:p-4 rounded-lg">
                    <p class="text-xs sm:text-sm text-gray-600">Skor Nilai</p>
                    <p class="text-sm sm:text-lg font-bold text-maroon">{{ number_format($average, 1) }}%</p>
                </div>
            </div>
        </div>

        <!-- Tabel Peringkat Jurusan -->
        <div class="bg-white rounded-lg shadow-lg p-5 sm:p-6 mb-6 sm:mb-8">
            <h3 class="text-base sm:text-lg font-bold text-maroon mb-3 sm:mb-4">Peringkat Jurusan</h3>
            
            <div class="overflow-x-auto">
                <table class="w-full text-sm">
                    <thead class="gradient-maroon text-white">
                        <tr>
                            <th class="px-3 sm:px-6 py-2 sm:py-3 text-left text-xs sm:text-sm font-bold">#</th>
                            <th class="px-3 sm:px-6 py-2 sm:py-3 text-left text-xs sm:text-sm font-bold">Jurusan</th>
                            <th class="px-3 sm:px-6 py-2 sm:py-3 text-right text-xs sm:text-sm font-bold">Skor</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-200">
                        @foreach($hasilAkhir as $index => $res)
                            <tr class="hover:bg-gray-50 transition {{ $index == 0 ? 'bg-yellow-50' : '' }}">
                                <td class="px-3 sm:px-6 py-2 sm:py-4 text-center">
                                    <span class="inline-flex items-center justify-center w-6 h-6 sm:w-8 sm:h-8 rounded-full text-xs sm:text-sm font-bold {{ $index == 0 ? 'bg-yellow-400 text-white' : 'bg-gray-200 text-gray-700' }}">
                                        {{ $index + 1 }}
                                    </span>
                                </td>
                                <td class="px-3 sm:px-6 py-2 sm:py-4 text-xs sm:text-sm font-semibold text-gray-900">
                                    {{ $res['jurusan'] ?? '-' }}
                                    @if($index == 0)
                                        <span class="ml-1 sm:ml-2 inline-block px-2 py-0.5 rounded text-xs font-semibold bg-yellow-100 text-yellow-800">
                                            ⭐ Utama
                                        </span>
                                    @endif
                                </td>
                                <td class="px-3 sm:px-6 py-2 sm:py-4 text-right text-xs sm:text-sm font-bold text-maroon">
                                    {{ number_format(($res['skor'] ?? 0) * 100, 1) }}%
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
            
            <!-- Visualisasi Progress Bars -->
            <div class="mt-4 sm:mt-6 space-y-2 sm:space-y-3">
                @foreach($hasilAkhir as $index => $res)
                    <div class="flex flex-col gap-1">
                        <div class="flex justify-between items-center">
                            <span class="text-xs sm:text-sm font-semibold text-gray-700">{{ $res['jurusan'] ?? '-' }}</span>
                            <span class="text-xs sm:text-sm font-bold text-maroon">{{ number_format(($res['skor'] ?? 0) * 100, 1) }}%</span>
                        </div>
                        <div class="w-full bg-gray-200 rounded-full h-2">
                            <div class="gradient-maroon h-2 rounded-full" style="width: {{ number_format(($res['skor'] ?? 0) * 100, 1) }}%"></div>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>

        <!-- Detail Rekomendasi Utama -->
        @if(count($hasilAkhir) > 0)
            <div class="bg-white rounded-lg shadow-lg p-5 sm:p-8 mb-6 sm:mb-8 border-l-4 border-yellow-400">
                @php
                    $topRecommendation = $hasilAkhir[0];
                    $detail = $topRecommendation['detail'] ?? [];
                @endphp
                
                <div class="flex flex-col sm:flex-row items-start gap-3 sm:gap-4 mb-4 sm:mb-6">
                    <div class="w-12 h-12 sm:w-14 sm:h-14 rounded-lg bg-yellow-100 flex items-center justify-center text-xl sm:text-2xl flex-shrink-0">1</div>
                    <div>
                        <h3 class="text-lg sm:text-2xl font-bold text-maroon mb-1 sm:mb-2">{{ $topRecommendation['jurusan'] ?? '-' }}</h3>
                        <p class="text-sm sm:text-lg text-gray-700">
                            Skor Kesesuaian: <span class="font-bold text-maroon">{{ number_format(($topRecommendation['skor'] ?? 0) * 100, 1) }}%</span>
                        </p>
                        @if($topJurusan && $topJurusan->deskripsi)
                            <p class="text-xs sm:text-sm text-gray-600 mt-2">{{ $topJurusan->deskripsi }}</p>
                        @endif
                    </div>
                </div>

                <!-- Analysis Breakdown -->
                <div class="mb-4 sm:mb-6 p-3 sm:p-4 bg-gray-50 rounded-lg">
                    <h4 class="font-bold text-maroon text-sm sm:text-base mb-3 sm:mb-4">Likelihood per Kriteria (Weighted Naive Bayes):</h4>
                    
                    <div class="space-y-2 sm:space-y-3">
                        <div>
                            <div class="flex justify-between items-center mb-1">
                                <p class="text-xs sm:text-sm font-semibold text-gray-700">Nilai Akademik — P(nilai|H) &times; w=0.40</p>
                                <span class="text-xs sm:text-sm font-bold text-maroon">{{ number_format(($detail['nilai'] ?? 0) * 100, 1) }}%</span>
                            </div>
                            <div class="w-full bg-gray-300 rounded-full h-2">
                                <div class="gradient-maroon h-2 rounded-full" style="width: {{ number_format(($detail['nilai'] ?? 0) * 100, 1) }}%"></div>
                            </div>
                        </div>

                        <div>
                            <div class="flex justify-between items-center mb-1">
                                <p class="text-xs sm:text-sm font-semibold text-gray-700">Minat & Bakat — P(minat|H) &times; w=0.35</p>
                                <span class="text-xs sm:text-sm font-bold text-maroon">{{ number_format(($detail['minat'] ?? 0) * 100, 1) }}%</span>
                            </div>
                            <div class="w-full bg-gray-300 rounded-full h-2">
                                <div class="bg-yellow-400 h-2 rounded-full" style="width: {{ number_format(($detail['minat'] ?? 0) * 100, 1) }}%"></div>
                            </div>
                        </div>

                        <div>
                            <div class="flex justify-between items-center mb-1">
                                <p class="text-xs sm:text-sm font-semibold text-gray-700">Preferensi Studi — P(pref|H) &times; w=0.15</p>
                                <span class="text-xs sm:text-sm font-bold text-maroon">{{ number_format(($detail['pref'] ?? 0) * 100, 1) }}%</span>
                            </div>
                            <div class="w-full bg-gray-300 rounded-full h-2">
                                <div class="gradient-maroon h-2 rounded-full" style="width: {{ number_format(($detail['pref'] ?? 0) * 100, 1) }}%"></div>
                            </div>
                        </div>

                        <div>
                            <div class="flex justify-between items-center mb-1">
                                <p class="text-xs sm:text-sm font-semibold text-gray-700">Cita-cita — P(cita|H) &times; w=0.05</p>
                                <span class="text-xs sm:text-sm font-bold text-maroon">{{ number_format(($detail['cita'] ?? 0) * 100, 1) }}%</span>
                            </div>
                            <div class="w-full bg-gray-300 rounded-full h-2">
                                <div class="bg-yellow-400 h-2 rounded-full" style="width: {{ number_format(($detail['cita'] ?? 0) * 100, 1) }}%"></div>
                            </div>
                        </div>

                        <div>
                            <div class="flex justify-between items-center mb-1">
                                <p class="text-xs sm:text-sm font-semibold text-gray-700">Prestasi — P(prestasi|H) &times; w=0.05</p>
                                <span class="text-xs sm:text-sm font-bold text-maroon">{{ number_format(($detail['prestasi'] ?? 0) * 100, 1) }}%</span>
                            </div>
                            <div class="w-full bg-gray-300 rounded-full h-2">
                                <div class="gradient-maroon h-2 rounded-full" style="width: {{ number_format(($detail['prestasi'] ?? 0) * 100, 1) }}%"></div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Explanation Breakdown -->
                <div class="mb-4 sm:mb-6 p-3 sm:p-4 bg-blue-50 rounded-lg border border-blue-200">
                    <h4 class="font-bold text-blue-900 text-sm sm:text-base mb-3 sm:mb-4 flex items-center gap-2">
                        <span class="text-lg">💡</span> Alasan Kenapa Jurusan Ini Cocok:
                    </h4>
                    
                    <div class="space-y-2 sm:space-y-3">
                        <div class="flex gap-2 sm:gap-3">
                            <span class="text-lg flex-shrink-0">📊</span>
                            <p class="text-xs sm:text-sm text-gray-800">
                                <strong>Nilai Akademik:</strong> {{ $topRecommendation['explanation']['nilai'] ?? '-' }}
                            </p>
                        </div>
                        <div class="flex gap-2 sm:gap-3">
                            <span class="text-lg flex-shrink-0">❤️</span>
                            <p class="text-xs sm:text-sm text-gray-800">
                                <strong>Minat & Bakat:</strong> {{ $topRecommendation['explanation']['minat'] ?? '-' }}
                            </p>
                        </div>
                        <div class="flex gap-2 sm:gap-3">
                            <span class="text-lg flex-shrink-0">🎓</span>
                            <p class="text-xs sm:text-sm text-gray-800">
                                <strong>Metode Pembelajaran:</strong> {{ $topRecommendation['explanation']['pref'] ?? '-' }}
                            </p>
                        </div>
                        <div class="flex gap-2 sm:gap-3">
                            <span class="text-lg flex-shrink-0">🎯</span>
                            <p class="text-xs sm:text-sm text-gray-800">
                                <strong>Cita-cita Karir:</strong> {{ $topRecommendation['explanation']['cita'] ?? '-' }}
                            </p>
                        </div>
                        <div class="flex gap-2 sm:gap-3">
                            <span class="text-lg flex-shrink-0">🏆</span>
                            <p class="text-xs sm:text-sm text-gray-800">
                                <strong>Prestasi Akademik:</strong> {{ $topRecommendation['explanation']['prestasi'] ?? '-' }}
                            </p>
                        </div>
                    </div>
                </div>

                <!-- Kesimpulan -->
                <div class="p-3 sm:p-4 bg-yellow-50 rounded-lg border-l-4 border-yellow-400 mb-3 sm:mb-4">
                    <h4 class="font-bold text-maroon mb-2 text-sm sm:text-base">📋 Kesimpulan:</h4>
                    <p class="text-gray-700 text-xs sm:text-sm leading-relaxed">
                        Berdasarkan profil Anda dengan <strong>nilai akademik {{ $katNilai }} (rata-rata {{ number_format($average, 1) }})</strong> 
                        dan <strong>preferensi studi {{ $prefStudi }}</strong>, 
                        sistem menganalisis bahwa <strong>{{ $topRecommendation['jurusan'] ?? '-' }}</strong> 
                        adalah pilihan yang paling sesuai dengan skor {{ number_format(($topRecommendation['skor'] ?? 0) * 100, 1) }}%.
                    </p>
                </div>

                <!-- Prospek Kerja -->
                @if($topJurusan && $topJurusan->prospek_kerja)
                    <div class="mb-3 sm:mb-4">
                        <p class="text-xs sm:text-sm font-bold text-maroon mb-2">Prospek Kerja:</p>
                        <p class="text-xs sm:text-sm text-gray-700">{{ $topJurusan->prospek_kerja }}</p>
                    </div>
                @endif
            </div>
        @endif

        <!-- Rekomendasi Alternatif & Penjelasan Detail -->
        @if(count($hasilAkhir) > 1)
            <div class="bg-white rounded-lg shadow-lg p-5 sm:p-8 mb-6 sm:mb-8">
                <h3 class="text-lg sm:text-xl font-bold text-maroon mb-4 sm:mb-6 flex items-center gap-2">
                    <span class="text-2xl">🔍</span> Rekomendasi Alternatif & Penjelasan Detail
                </h3>
                
                <div class="space-y-3 sm:space-y-4">
                    @foreach($hasilAkhir as $index => $rec)
                        @if($index > 0)
                            <details class="border border-gray-300 rounded-lg overflow-hidden">
                                <summary class="cursor-pointer p-4 hover:bg-gray-50 flex justify-between items-center">
                                    <div class="flex items-center gap-3">
                                        <span class="inline-flex items-center justify-center w-8 h-8 rounded-full bg-gray-200 text-gray-700 font-bold text-sm">
                                            {{ $index + 1 }}
                                        </span>
                                        <div>
                                            <p class="font-semibold text-gray-900 text-sm sm:text-base">{{ $rec['jurusan'] ?? '-' }}</p>
                                            <p class="text-xs sm:text-sm text-gray-600">Skor: {{ number_format(($rec['skor'] ?? 0) * 100, 1) }}%</p>
                                        </div>
                                    </div>
                                    <span class="text-gray-400">▸</span>
                                </summary>
                                
                                <div class="p-4 bg-gray-50 border-t border-gray-300 space-y-4">
                                    <!-- Score Breakdown -->
                                    <div class="bg-white p-3 rounded-lg">
                                        <p class="text-xs sm:text-sm font-bold text-gray-700 mb-3">Scoring per Kriteria:</p>
                                        <div class="space-y-2">
                                            <div class="flex justify-between items-center text-xs">
                                                <span class="text-gray-600">📊 Nilai (40%)</span>
                                                <span class="font-semibold text-maroon">{{ number_format(($rec['detail']['nilai'] ?? 0) * 100, 1) }}%</span>
                                            </div>
                                            <div class="w-full bg-gray-200 rounded h-1.5">
                                                <div class="bg-red-500 rounded h-1.5 transition-all" style="width: {{ number_format(($rec['detail']['nilai'] ?? 0) * 100, 1) }}%"></div>
                                            </div>

                                            <div class="flex justify-between items-center text-xs mt-2">
                                                <span class="text-gray-600">❤️ Minat (35%)</span>
                                                <span class="font-semibold text-maroon">{{ number_format(($rec['detail']['minat'] ?? 0) * 100, 1) }}%</span>
                                            </div>
                                            <div class="w-full bg-gray-200 rounded h-1.5">
                                                <div class="bg-pink-500 rounded h-1.5 transition-all" style="width: {{ number_format(($rec['detail']['minat'] ?? 0) * 100, 1) }}%"></div>
                                            </div>

                                            <div class="flex justify-between items-center text-xs mt-2">
                                                <span class="text-gray-600">🎓 Preferensi (15%)</span>
                                                <span class="font-semibold text-maroon">{{ number_format(($rec['detail']['pref'] ?? 0) * 100, 1) }}%</span>
                                            </div>
                                            <div class="w-full bg-gray-200 rounded h-1.5">
                                                <div class="bg-yellow-500 rounded h-1.5 transition-all" style="width: {{ number_format(($rec['detail']['pref'] ?? 0) * 100, 1) }}%"></div>
                                            </div>

                                            <div class="flex justify-between items-center text-xs mt-2">
                                                <span class="text-gray-600">🎯 Cita-cita (5%)</span>
                                                <span class="font-semibold text-maroon">{{ number_format(($rec['detail']['cita'] ?? 0) * 100, 1) }}%</span>
                                            </div>
                                            <div class="w-full bg-gray-200 rounded h-1.5">
                                                <div class="bg-blue-500 rounded h-1.5 transition-all" style="width: {{ number_format(($rec['detail']['cita'] ?? 0) * 100, 1) }}%"></div>
                                            </div>

                                            <div class="flex justify-between items-center text-xs mt-2">
                                                <span class="text-gray-600">🏆 Prestasi (5%)</span>
                                                <span class="font-semibold text-maroon">{{ number_format(($rec['detail']['prestasi'] ?? 0) * 100, 1) }}%</span>
                                            </div>
                                            <div class="w-full bg-gray-200 rounded h-1.5">
                                                <div class="bg-green-500 rounded h-1.5 transition-all" style="width: {{ number_format(($rec['detail']['prestasi'] ?? 0) * 100, 1) }}%"></div>
                                            </div>
                                        </div>
                                    </div>

                                    <!-- Explanation -->
                                    @if(isset($rec['explanation']) && is_array($rec['explanation']))
                                        <div class="bg-blue-50 p-3 rounded-lg border-l-4 border-blue-400">
                                            <p class="text-xs sm:text-sm font-bold text-blue-900 mb-2">💡 Alasan Cocok:</p>
                                            <ul class="space-y-1.5 text-xs sm:text-sm text-gray-800">
                                                <li class="flex gap-2">
                                                    <span class="flex-shrink-0">📊</span>
                                                    <span>{{ $rec['explanation']['nilai'] ?? '-' }}</span>
                                                </li>
                                                <li class="flex gap-2">
                                                    <span class="flex-shrink-0">❤️</span>
                                                    <span>{{ $rec['explanation']['minat'] ?? '-' }}</span>
                                                </li>
                                                <li class="flex gap-2">
                                                    <span class="flex-shrink-0">🎓</span>
                                                    <span>{{ $rec['explanation']['pref'] ?? '-' }}</span>
                                                </li>
                                                <li class="flex gap-2">
                                                    <span class="flex-shrink-0">🎯</span>
                                                    <span>{{ $rec['explanation']['cita'] ?? '-' }}</span>
                                                </li>
                                                <li class="flex gap-2">
                                                    <span class="flex-shrink-0">🏆</span>
                                                    <span>{{ $rec['explanation']['prestasi'] ?? '-' }}</span>
                                                </li>
                                            </ul>
                                        </div>
                                    @endif
                                </div>
                            </details>
                        @endif
                    @endforeach
                </div>
            </div>
        @endif

        <!-- Chatbot Confirmation Card -->
        <div class="bg-white rounded-lg shadow-lg p-5 sm:p-8 mb-6 sm:mb-8 border-2 border-yellow-400">
            <div class="flex flex-col sm:flex-row items-start gap-3 sm:gap-4">
                <div class="w-12 h-12 sm:w-14 sm:h-14 rounded-lg bg-yellow-100 flex items-center justify-center text-xl sm:text-2xl flex-shrink-0">💬</div>
                <div class="flex-1">
                    <h3 class="text-base sm:text-xl font-bold text-maroon mb-1 sm:mb-2">Konsultasi Lebih Lanjut</h3>
                    <p class="text-xs sm:text-sm md:text-base text-gray-700 mb-3 sm:mb-4">
                        Ingin tahu mengapa jurusan <strong>{{ $hasilAkhir[0]['jurusan'] ?? '' }}</strong> direkomendasikan? 
                        Konsultasikan dengan AI Konselor BK Virtual untuk penjelasan detail berdasarkan profil Anda.
                    </p>
                    <a href="{{ route('chatbot.index', ['rec' => session('last_recommendation_id')]) }}" class="inline-block gradient-maroon text-white font-bold py-2 sm:py-3 px-4 sm:px-6 rounded-lg hover:opacity-90 transition duration-200 text-sm sm:text-base">
                        💬 Tanya AI: "Mengapa jurusan ini cocok untukku?"
                    </a>
                </div>
            </div>
        </div>

        <!-- Tombol Aksi -->
        <div class="flex flex-col sm:flex-row gap-3 sm:gap-4 justify-between">
            <a href="{{ route('rekomendasi.index') }}" class="block sm:inline-flex items-center justify-center px-4 sm:px-6 py-2 sm:py-3 bg-gray-600 hover:bg-gray-700 text-white font-semibold rounded-lg transition duration-200 text-sm sm:text-base text-center">
                Kembali & Ubah Input
            </a>
            <a href="{{ url('/dashboard') }}" class="block sm:inline-flex items-center justify-center px-4 sm:px-6 py-2 sm:py-3 gradient-maroon text-white font-semibold rounded-lg hover:opacity-90 transition duration-200 text-sm sm:text-base text-center">
                Ke Dashboard
            </a>
        </div>

        <!-- Info Metode -->
        <div class="mt-6 sm:mt-8 p-3 sm:p-4 bg-white rounded-lg border border-gray-200 shadow-sm">
            <p class="text-xs sm:text-sm text-gray-600">
                <strong>Metode:</strong> Sistem menggunakan algoritma Weighted Naive Bayes dengan 5 fitur berbobot: Nilai Akademik (w=0.40), Minat (w=0.35), Preferensi Studi (w=0.15), Cita-cita (w=0.05), Prestasi (w=0.05). Rumus: P(H|X) &prop; P(H) &times; &prod; P(Xi|H)<sup>wi</sup>, kemudian dinormalisasi menggunakan softmax.
            </p>
        </div>
    </div>
</body>
</html>