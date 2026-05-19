<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Input Data - Sistem Pemilihan Jurusan</title>
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
        .focus-maroon:focus {
            border-color: #6B7280;
            box-shadow: 0 0 0 3px rgba(107, 114, 128, 0.1);
        }
        .input-error {
            border-color: #ef4444 !important;
            background-color: #fef2f2 !important;
        }
        .input-valid {
            border-color: #10b981 !important;
            background-color: #f0fdf4 !important;
        }
        .error-icon::before {
            content: "⚠️ ";
            margin-right: 0.25rem;
        }
        .success-icon::before {
            content: "✅ ";
            margin-right: 0.25rem;
        }
        .input-wrapper {
            position: relative;
        }
        .validation-message {
            font-size: 0.75rem;
            margin-top: 0.25rem;
            display: flex;
            align-items: center;
            animation: slideIn 0.3s ease-out;
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
                <h1 class="text-xl sm:text-2xl md:text-3xl font-bold">Input Data Rekomendasi</h1>
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
    <div class="w-full px-4 sm:px-6 py-6 sm:py-8">
        <!-- Hero Intro -->
        <div class="bg-gradient-to-r from-blue-600 to-purple-600 rounded-xl shadow-lg p-8 sm:p-10 mb-8 text-white">
            <h2 class="text-3xl sm:text-4xl font-bold mb-3 sm:mb-4">Kuis Cerdas Menemukan Jurusanmu 🚀</h2>
            <p class="text-base sm:text-lg text-blue-100 mb-4 sm:mb-6 leading-relaxed">
                Kami akan mengajukan beberapa pertanyaan untuk mengenal lebih dalam tentang profil akademis, minat, gaya belajar, prestasi, dan impian karirmu. Jawab dengan jujur dan sedetail mungkin - informasi ini akan membantu sistem AI kami memberikan rekomendasi yang paling akurat untuk masa depan gemilangmu.
            </p>
            <div class="grid grid-cols-3 gap-4 mt-6">
                <div class="bg-white bg-opacity-20 rounded-lg p-4 backdrop-blur-sm text-center">
                    <p class="text-3xl font-bold">⏱️</p>
                    <p class="text-sm text-blue-100 mt-1">3-5 Menit</p>
                </div>
                <div class="bg-white bg-opacity-20 rounded-lg p-4 backdrop-blur-sm text-center">
                    <p class="text-3xl font-bold">✅</p>
                    <p class="text-sm text-blue-100 mt-1">Mudah & Cepat</p>
                </div>
                <div class="bg-white bg-opacity-20 rounded-lg p-4 backdrop-blur-sm text-center">
                    <p class="text-3xl font-bold">🎯</p>
                    <p class="text-sm text-blue-100 mt-1">Hasil Akurat</p>
                </div>
            </div>
        </div>

        <!-- Tips Card -->
        <div class="bg-amber-50 border-l-4 border-amber-400 rounded-lg p-6 sm:p-8 mb-8 shadow-sm">
            <div class="flex gap-4">
                <div class="text-3xl flex-shrink-0">💡</div>
                <div>
                    <h3 class="font-bold text-amber-900 mb-3 text-lg">Tips Agar Hasil Lebih Akurat</h3>
                    <ul class="text-sm text-amber-800 space-y-2">
                        <li>✓ Jawab semua pertanyaan dengan jujur dan sebenar-benarnya</li>
                        <li>✓ Jangan terburu-buru - pikirkan jawaban dengan matang</li>
                        <li>✓ Nilai yang kamu masukkan sebaiknya merupakan rata-rata atau nilai terbaik dari rapor</li>
                        <li>✓ Pilih opsi yang benar-benar mewakili minat dan preferensi kamu</li>
                    </ul>
                </div>
            </div>
        </div>

        <!-- Form Card -->
        <div class="bg-white rounded-xl shadow-lg p-8 sm:p-10 border-t-4 border-purple-600">

            @if ($errors->any())
                <div class="bg-red-50 border-l-4 border-red-500 p-4 sm:p-5 rounded-lg mb-6 shadow-md animate-pulse">
                    <div class="flex items-start gap-3">
                        <span class="text-2xl flex-shrink-0">❌</span>
                        <div class="flex-1">
                            <h3 class="text-red-700 font-bold text-sm sm:text-base mb-3">Terjadi Kesalahan Validasi</h3>
                            <p class="text-red-600 text-xs sm:text-sm mb-3">Silakan perbaiki kesalahan berikut sebelum melanjutkan:</p>
                            <ul class="list-disc list-inside space-y-2 text-red-600 text-xs sm:text-sm bg-white bg-opacity-50 p-3 rounded">
                                @foreach ($errors->all() as $error)
                                    <li class="ml-2">{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    </div>
                </div>
            @endif

            <form action="{{ route('rekomendasi.proses') }}" method="POST" class="space-y-4 sm:space-y-6">
                @csrf

                {{-- ============================================ --}}
                {{-- KRITERIA 1: NILAI MATA PELAJARAN --}}
                {{-- ============================================ --}}
                <div class="p-6 rounded-lg border-2 border-gray-200 bg-gray-50">
                    <h3 class="font-bold text-lg sm:text-xl text-purple-700 mb-2">1️⃣ Nilai Mata Pelajaran <span class="text-red-500">*</span></h3>
                    <p class="text-sm text-gray-600 mb-4">
                        @if(isset($student) && $student->kelompok_asal == 'IPA')
                            Siswa <strong>IPA</strong> — Masukkan nilai rapor (0-100): <strong>Matematika, Fisika, Kimia, Biologi</strong>.
                        @else
                            Siswa <strong>IPS</strong> — Masukkan nilai rapor (0-100): <strong>Ekonomi, Geografi, Sosiologi, Sejarah</strong>.
                        @endif
                    </p>

                    @if(isset($student) && $student->kelompok_asal == 'IPA')
                        {{-- SISWA IPA: Matematika, Fisika, Kimia, Biologi --}}
                        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4">
                            <div class="input-wrapper">
                                <label for="mtk" class="block text-sm font-semibold text-gray-700 mb-2">Matematika <span class="text-red-500">*</span></label>
                                <input id="mtk" type="number" name="mtk" min="0" max="100" value="{{ old('mtk') }}" placeholder="Nilai: 85" required
                                    class="block w-full px-4 py-2 border border-gray-300 rounded-lg focus-maroon focus:outline-none text-sm transition-colors @error('mtk') input-error @enderror">
                                @error('mtk')
                                    <span class="validation-message text-red-600">⚠️ {{ $message }}</span>
                                @enderror
                            </div>
                            <div class="input-wrapper">
                                <label for="fisika" class="block text-sm font-semibold text-gray-700 mb-2">Fisika <span class="text-red-500">*</span></label>
                                <input id="fisika" type="number" name="fisika" min="0" max="100" value="{{ old('fisika') }}" placeholder="Nilai: 78" required
                                    class="block w-full px-4 py-2 border border-gray-300 rounded-lg focus-maroon focus:outline-none text-sm transition-colors @error('fisika') input-error @enderror">
                                @error('fisika')
                                    <span class="validation-message text-red-600">⚠️ {{ $message }}</span>
                                @enderror
                            </div>
                            <div class="input-wrapper">
                                <label for="kimia" class="block text-sm font-semibold text-gray-700 mb-2">Kimia <span class="text-red-500">*</span></label>
                                <input id="kimia" type="number" name="kimia" min="0" max="100" value="{{ old('kimia') }}" placeholder="Nilai: 72" required
                                    class="block w-full px-4 py-2 border border-gray-300 rounded-lg focus-maroon focus:outline-none text-sm transition-colors @error('kimia') input-error @enderror">
                                @error('kimia')
                                    <span class="validation-message text-red-600">⚠️ {{ $message }}</span>
                                @enderror
                            </div>
                            <div class="input-wrapper">
                                <label for="biologi" class="block text-sm font-semibold text-gray-700 mb-2">Biologi <span class="text-red-500">*</span></label>
                                <input id="biologi" type="number" name="biologi" min="0" max="100" value="{{ old('biologi') }}" placeholder="Nilai: 80" required
                                    class="block w-full px-4 py-2 border border-gray-300 rounded-lg focus-maroon focus:outline-none text-sm transition-colors @error('biologi') input-error @enderror">
                                @error('biologi')
                                    <span class="validation-message text-red-600">⚠️ {{ $message }}</span>
                                @enderror
                            </div>
                        </div>
                    @else
                        {{-- SISWA IPS: Ekonomi, Geografi, Sosiologi, Sejarah --}}
                        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4">
                            <div class="input-wrapper">
                                <label for="ekonomi" class="block text-sm font-semibold text-gray-700 mb-2">Ekonomi <span class="text-red-500">*</span></label>
                                <input id="ekonomi" type="number" name="ekonomi" min="0" max="100" value="{{ old('ekonomi') }}" placeholder="Nilai: 82" required
                                    class="block w-full px-4 py-2 border border-gray-300 rounded-lg focus-maroon focus:outline-none text-sm transition-colors @error('ekonomi') input-error @enderror">
                                @error('ekonomi')
                                    <span class="validation-message text-red-600">⚠️ {{ $message }}</span>
                                @enderror
                            </div>
                            <div class="input-wrapper">
                                <label for="geografi" class="block text-sm font-semibold text-gray-700 mb-2">Geografi <span class="text-red-500">*</span></label>
                                <input id="geografi" type="number" name="geografi" min="0" max="100" value="{{ old('geografi') }}" placeholder="Nilai: 76" required
                                    class="block w-full px-4 py-2 border border-gray-300 rounded-lg focus-maroon focus:outline-none text-sm transition-colors @error('geografi') input-error @enderror">
                                @error('geografi')
                                    <span class="validation-message text-red-600">⚠️ {{ $message }}</span>
                                @enderror
                            </div>
                            <div class="input-wrapper">
                                <label for="sosiologi" class="block text-sm font-semibold text-gray-700 mb-2">Sosiologi <span class="text-red-500">*</span></label>
                                <input id="sosiologi" type="number" name="sosiologi" min="0" max="100" value="{{ old('sosiologi') }}" placeholder="Nilai: 74" required
                                    class="block w-full px-4 py-2 border border-gray-300 rounded-lg focus-maroon focus:outline-none text-sm transition-colors @error('sosiologi') input-error @enderror">
                                @error('sosiologi')
                                    <span class="validation-message text-red-600">⚠️ {{ $message }}</span>
                                @enderror
                            </div>
                            <div class="input-wrapper">
                                <label for="sejarah" class="block text-sm font-semibold text-gray-700 mb-2">Sejarah <span class="text-red-500">*</span></label>
                                <input id="sejarah" type="number" name="sejarah" min="0" max="100" value="{{ old('sejarah') }}" placeholder="Nilai: 70" required
                                    class="block w-full px-4 py-2 border border-gray-300 rounded-lg focus-maroon focus:outline-none text-sm transition-colors @error('sejarah') input-error @enderror">
                                @error('sejarah')
                                    <span class="validation-message text-red-600">⚠️ {{ $message }}</span>
                                @enderror
                            </div>
                        </div>
                    @endif
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    {{-- ============================================ --}}
                    {{-- KRITERIA 2: MINAT SISWA --}}
                    {{-- ============================================ --}}
                    <div class="p-6 rounded-lg border-2 border-gray-200 bg-gray-50">
                        <h3 class="font-bold text-lg sm:text-xl text-blue-700 mb-2">2️⃣ Minat Siswa <span class="text-red-500">*</span></h3>
                        <p class="text-sm text-gray-600 mb-4">Tuliskan bidang atau kegiatan yang Anda minati / sukai.</p>
                        <div class="input-wrapper">
                            <label for="minat" class="block text-sm font-semibold text-gray-700 mb-2">Bidang Minat</label>
                            <input id="minat" type="text" name="minat" value="{{ old('minat') }}" placeholder="Contoh: coding, komputer, bisnis, pertanian" 
                                class="block w-full px-4 py-2 border border-gray-300 rounded-lg focus-maroon focus:outline-none text-sm transition-colors @error('minat') input-error @enderror" required>
                            <p class="text-xs text-gray-500 mt-2">Pisahkan dengan koma jika lebih dari satu minat</p>
                            @error('minat')
                                <span class="validation-message text-red-600">⚠️ {{ $message }}</span>
                            @enderror
                        </div>
                    </div>

                    {{-- ============================================ --}}
                    {{-- KRITERIA 3: PREFERENSI STUDI LANJUTAN --}}
                    {{-- ============================================ --}}
                    <div class="p-6 rounded-lg border-2 border-gray-200 bg-gray-50">
                        <h3 class="font-bold text-lg sm:text-xl text-green-700 mb-2">3️⃣ Preferensi Studi Lanjutan <span class="text-red-500">*</span></h3>
                        <p class="text-sm text-gray-600 mb-4">Pilih rumpun jurusan Politeknik Negeri Jember yang paling sesuai.</p>
                        <div class="input-wrapper">
                            <label for="pref_studi" class="block text-sm font-semibold text-gray-700 mb-2">Arah Rumpun Jurusan Tujuan</label>
                            <select id="pref_studi" name="pref_studi" class="block w-full px-4 py-2 border border-gray-300 rounded-lg focus-maroon focus:outline-none text-sm transition-colors @error('pref_studi') input-error @enderror" required>
                                <option value="">-- Pilih Arah Rumpun Jurusan --</option>
                                <option value="Sains & Teknologi" {{ old('pref_studi') == 'Sains & Teknologi' ? 'selected' : '' }}>Sains & Teknologi (contoh: TI, Teknik)</option>
                                <option value="Pertanian & Lingkungan" {{ old('pref_studi') == 'Pertanian & Lingkungan' ? 'selected' : '' }}>Pertanian & Lingkungan (contoh: Produksi/Teknologi Pertanian)</option>
                                <option value="Kesehatan & Ilmu Hayat" {{ old('pref_studi') == 'Kesehatan & Ilmu Hayat' ? 'selected' : '' }}>Kesehatan & Ilmu Hayat (contoh: rumpun Kesehatan)</option>
                                <option value="Bisnis & Manajemen" {{ old('pref_studi') == 'Bisnis & Manajemen' ? 'selected' : '' }}>Bisnis & Manajemen (contoh: Akuntansi, Manajemen Agribisnis)</option>
                                <option value="Sosial & Humaniora" {{ old('pref_studi') == 'Sosial & Humaniora' ? 'selected' : '' }}>Sosial & Humaniora (contoh: Bahasa, Komunikasi, Pariwisata)</option>
                            </select>
                            <p class="text-xs text-gray-500 mt-2">Pilih rumpun yang paling menggambarkan jurusan Polije yang ingin Anda tuju.</p>
                            @error('pref_studi')
                                <span class="validation-message text-red-600">⚠️ {{ $message }}</span>
                            @enderror
                        </div>
                    </div>

                    {{-- ============================================ --}}
                    {{-- KRITERIA 4: CITA-CITA / PREFERENSI KARIR --}}
                    {{-- ============================================ --}}
                    <div class="p-6 rounded-lg border-2 border-gray-200 bg-gray-50">
                        <h3 class="font-bold text-lg sm:text-xl text-orange-700 mb-2">4️⃣ Cita-cita / Preferensi Karir <span class="text-red-500">*</span></h3>
                        <p class="text-sm text-gray-600 mb-4">Tuliskan profesi atau karir yang Anda impikan.</p>
                        <div class="input-wrapper">
                            <label for="cita_cita" class="block text-sm font-semibold text-gray-700 mb-2">Cita-cita</label>
                            <input id="cita_cita" type="text" name="cita_cita" value="{{ old('cita_cita') }}" placeholder="Contoh: programmer, dokter, pengusaha" 
                                class="block w-full px-4 py-2 border border-gray-300 rounded-lg focus-maroon focus:outline-none text-sm transition-colors @error('cita_cita') input-error @enderror" required>
                            <p class="text-xs text-gray-500 mt-2">Bisa lebih dari satu, pisahkan dengan koma</p>
                            @error('cita_cita')
                                <span class="validation-message text-red-600">⚠️ {{ $message }}</span>
                            @enderror
                        </div>
                    </div>

                    {{-- ============================================ --}}
                    {{-- KRITERIA 5: PRESTASI AKADEMIK / NON-AKADEMIK --}}
                    {{-- ============================================ --}}
                    <div class="p-6 rounded-lg border-2 border-gray-200 bg-gray-50">
                        <h3 class="font-bold text-lg sm:text-xl text-red-700 mb-2">5️⃣ Prestasi (Opsional)</h3>
                        <p class="text-sm text-gray-600 mb-4">Tuliskan prestasi yang pernah diraih (opsional).</p>
                        <div class="input-wrapper">
                            <label for="prestasi" class="block text-sm font-semibold text-gray-700 mb-2">Prestasi</label>
                            <input id="prestasi" type="text" name="prestasi" value="{{ old('prestasi') }}" placeholder="Contoh: Juara 1 olimpiade MTK, sertifikat web design" 
                                class="block w-full px-4 py-2 border border-gray-300 rounded-lg focus-maroon focus:outline-none text-sm transition-colors @error('prestasi') input-error @enderror">
                            <p class="text-xs text-gray-500 mt-2">Kosongkan jika belum ada prestasi</p>
                            @error('prestasi')
                                <span class="validation-message text-red-600">⚠️ {{ $message }}</span>
                            @enderror
                        </div>
                    </div>
                </div>

                <!-- Submit Button -->
                <div class="mt-8 p-6 rounded-lg bg-gradient-to-r from-yellow-50 to-yellow-100 border-l-4 border-yellow-400 shadow-sm">
                    <p class="text-sm text-gray-700 mb-4 leading-relaxed">
                        ⏳ Setelah menekan tombol, sistem akan menganalisis data Anda dengan algoritma Naive Bayes dan menampilkan ranking 9 jurusan Politeknik Negeri Jember yang paling sesuai dengan profil kamu.
                    </p>
                    <button type="submit" class="w-full bg-gradient-to-r from-purple-600 to-indigo-600 text-white font-bold py-3 px-6 rounded-lg hover:shadow-lg active:scale-95 transition duration-200 text-base shadow-lg disabled:opacity-50 disabled:cursor-not-allowed">
                        ✨ Lihat Rekomendasi Jurusan
                    </button>
                    <p class="text-xs sm:text-sm text-gray-600 mt-3 text-center">Pastikan semua data terisi dengan benar sebelum melanjutkan</p>
                </div>
            </form>
        </div>

        <!-- Riwayat Rekomendasi Section -->
        @php
            $recommendations = $recommendations ?? [];
        @endphp
        
        @if(count($recommendations) > 0)
            <div class="mt-8 sm:mt-12">
                <div class="bg-white rounded-lg shadow-lg p-5 sm:p-8 border-l-4 border-purple-500">
                    <div class="flex items-center gap-3 mb-6">
                        <div class="w-12 h-12 sm:w-14 sm:h-14 rounded-lg bg-purple-100 flex items-center justify-center text-2xl flex-shrink-0">📋</div>
                        <div>
                            <h2 class="text-lg sm:text-xl md:text-2xl font-bold text-maroon">Riwayat Rekomendasi</h2>
                            <p class="text-xs sm:text-sm text-gray-600">Analisis yang sudah Anda lakukan sebelumnya</p>
                        </div>
                    </div>

                    <div class="space-y-3 sm:space-y-4">
                        @foreach($recommendations as $rec)
                            <div class="border border-gray-200 rounded-lg p-4 hover:shadow-md hover:border-maroon transition cursor-pointer" onclick="this.classList.toggle('expanded')">
                                <div class="flex justify-between items-start mb-2">
                                    <div class="flex-1">
                                        <p class="text-xs sm:text-sm text-gray-500">{{ $rec->created_at->format('d M Y - H:i') }}</p>
                                        <p class="text-sm sm:text-base font-bold text-maroon mt-1">Rekomendasi Utama: <span class="text-lg">{{ $rec->hasil_rekomendasi[0]['jurusan'] ?? 'N/A' }}</span></p>
                                    </div>
                                    <span class="inline-block px-3 py-1 rounded-full text-xs font-bold bg-purple-100 text-purple-700 flex-shrink-0 ml-2">
                                        {{ number_format(($rec->hasil_rekomendasi[0]['skor'] ?? 0) * 100, 1) }}%
                                    </span>
                                </div>
                                <p class="text-xs text-gray-600 mb-3">Top 3 Rekomendasi:</p>
                                <div class="grid grid-cols-1 md:grid-cols-3 gap-2">
                                    @foreach(array_slice($rec->hasil_rekomendasi, 0, 3) as $idx => $rec_item)
                                        <div class="bg-gradient-to-br from-purple-50 to-purple-100 p-3 rounded text-center">
                                            <p class="text-xs text-gray-600">{{ $idx + 1 }}. {{ $rec_item['jurusan'] }}</p>
                                            <p class="text-sm font-bold text-maroon">{{ number_format($rec_item['skor'] * 100, 1) }}%</p>
                                        </div>
                                    @endforeach
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>
            </div>
        @endif

        <!-- Info Metode -->
        <div class="mt-8 sm:mt-12 p-6 rounded-lg bg-blue-50 border-l-4 border-blue-400 shadow-sm">
            <div class="flex gap-4">
                <div class="text-3xl flex-shrink-0">🤖</div>
                <div>
                    <h3 class="font-bold text-blue-900 mb-2 text-lg">Tentang Sistem Rekomendasi Kami</h3>
                    <p class="text-sm text-blue-800 leading-relaxed">
                        <strong>Metode:</strong> Sistem menggunakan Weighted Naive Bayes dengan 5 kriteria:
                    </p>
                    <ul class="text-sm text-blue-800 mt-3 space-y-1 ml-4">
                        <li>📚 Nilai Akademik (15.6%)</li>
                        <li>💡 Minat & Bakat (45.6%)</li>
                        <li>🎯 Preferensi Studi (25.6%)</li>
                        <li>🚀 Cita-cita (9%)</li>
                        <li>🏆 Prestasi (4%)</li>
                    </ul>
                </div>
            </div>
        </div>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const form = document.querySelector('form');
            const submitBtn = form?.querySelector('button[type="submit"]');

            // Validasi input fields
            const inputs = form?.querySelectorAll('input, select, textarea');
            
            inputs?.forEach(input => {
                // Validasi pada change event
                input.addEventListener('change', function() {
                    validateField(this);
                });
                
                // Validasi pada blur event
                input.addEventListener('blur', function() {
                    validateField(this);
                });

                // Remove error on input
                input.addEventListener('input', function() {
                    if (this.classList.contains('input-error')) {
                        validateField(this);
                    }
                });
            });

            // Validasi saat submit
            form?.addEventListener('submit', function(e) {
                let isValid = true;
                
                inputs?.forEach(input => {
                    if (!validateField(input)) {
                        isValid = false;
                    }
                });

                if (!isValid) {
                    e.preventDefault();
                    // Scroll ke error pertama
                    const firstError = form.querySelector('.input-error');
                    if (firstError) {
                        firstError.scrollIntoView({ behavior: 'smooth', block: 'center' });
                        firstError.focus();
                    }
                }
            });

            function validateField(field) {
                const value = field.value.trim();
                const isRequired = field.hasAttribute('required');
                const name = field.name;
                const type = field.type;
                let errorMsg = '';
                let isValid = true;

                // Remove previous error/valid styling
                field.classList.remove('input-error', 'input-valid');
                const existingMsg = field.parentElement.querySelector('.validation-message');
                if (existingMsg) {
                    existingMsg.remove();
                }

                // Validasi untuk field yang required
                if (isRequired && !value) {
                    errorMsg = '⚠️ ' + field.placeholder?.split('Contoh')[0]?.trim() + ' tidak boleh kosong';
                    isValid = false;
                }

                // Validasi untuk number fields
                if (type === 'number' && value) {
                    const numValue = parseFloat(value);
                    const min = parseFloat(field.min || 0);
                    const max = parseFloat(field.max || 100);

                    if (isNaN(numValue)) {
                        errorMsg = '⚠️ Masukkan angka yang valid (0-100)';
                        isValid = false;
                    } else if (numValue < min || numValue > max) {
                        errorMsg = '⚠️ Nilai harus antara ' + min + ' - ' + max;
                        isValid = false;
                    }
                }

                // Validasi untuk text fields (min length)
                if ((name === 'minat' || name === 'cita_cita') && value && value.length < 3) {
                    errorMsg = '⚠️ Minimal 3 karakter, jelaskan lebih detail';
                    isValid = false;
                }

                // Validasi untuk select (pref_studi)
                if (name === 'pref_studi' && !value) {
                    errorMsg = '⚠️ Pilih salah satu preferensi studi';
                    isValid = false;
                }

                // Apply styling
                if (!isValid && value) {
                    field.classList.add('input-error');
                } else if (isValid && (isRequired || value)) {
                    field.classList.add('input-valid');
                }

                // Show error message
                if (errorMsg) {
                    const msgDiv = document.createElement('div');
                    msgDiv.className = 'validation-message text-red-600';
                    msgDiv.textContent = errorMsg;
                    field.parentElement.appendChild(msgDiv);
                }

                return isValid || !isRequired;
            }

            // Loading state pada submit
            form?.addEventListener('submit', function() {
                submitBtn.disabled = true;
                submitBtn.innerHTML = '<span class="inline-flex items-center">⏳ Menganalisis data...</span>';
            });
        });
    </script>
</body>