<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Input Data - Sistem Pemilihan Jurusan</title>
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
        .focus-maroon:focus {
            border-color: #6B2C2C;
            box-shadow: 0 0 0 3px rgba(107, 44, 44, 0.1);
        }
    </style>
</head>
<body class="bg-cream">
    <!-- Header -->
    <header class="gradient-maroon text-white shadow-lg sticky top-0 z-50">
        <div class="container mx-auto px-4 sm:px-6 py-4 sm:py-6 flex flex-col sm:flex-row justify-between items-start sm:items-center gap-3 sm:gap-4">
            <div>
                <h1 class="text-xl sm:text-2xl md:text-3xl font-bold">Input Data Profil</h1>
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
            <h2 class="text-lg sm:text-xl font-bold text-maroon mb-2 sm:mb-3">Petunjuk Pengisian</h2>
            <p class="text-xs sm:text-sm md:text-base text-gray-700 mb-2">
                Silakan isi data berikut dengan jujur agar sistem dapat memberikan rekomendasi yang akurat.
            </p>
            <p class="text-xs sm:text-sm text-gray-600">
                Sistem akan menganalisis data Anda dan menampilkan ranking <strong>9 jurusan</strong> yang tersedia di Politeknik Negeri Jember.
            </p>
        </div>

        <!-- Form Card -->
        <div class="bg-white rounded-lg shadow-lg p-5 sm:p-8 border-l-4 border-maroon">
            <div class="flex flex-col sm:flex-row items-start sm:items-center gap-3 sm:gap-4 mb-4 sm:mb-6">
                <div class="w-12 h-12 sm:w-14 sm:h-14 rounded-lg bg-yellow-100 flex items-center justify-center text-2xl flex-shrink-0">📝</div>
                <div>
                    <h2 class="text-lg sm:text-xl md:text-2xl font-bold text-maroon">Formulir Data Profil</h2>
                    <p class="text-xs sm:text-sm text-gray-600">Jawab pertanyaan berikut untuk mendapatkan rekomendasi jurusan.</p>
                </div>
            </div>

            <form action="{{ route('rekomendasi.proses') }}" method="POST" class="space-y-4 sm:space-y-6">
                @csrf

                <div class="grid grid-cols-1 md:grid-cols-2 gap-4 sm:gap-6">
                    <!-- Kolom Nilai -->
                    <div class="p-4 sm:p-5 rounded-lg border-2 border-gray-200 bg-gray-50">
                        <h3 class="font-bold text-maroon text-base sm:text-lg mb-1 sm:mb-2">1. Nilai Rapor (0-100)</h3>
                        <p class="text-xs text-gray-600 mb-3 sm:mb-4">Masukkan nilai rata-rata mata pelajaran Anda.</p>

                        <div class="space-y-3 sm:space-y-4">
                            <div>
                                <label for="mtk" class="block text-xs sm:text-sm font-semibold text-gray-700 mb-1">Matematika</label>
                                <input id="mtk" type="number" name="mtk" min="0" max="100" placeholder="Contoh: 85" 
                                    class="block w-full px-3 sm:px-4 py-2 border border-gray-300 rounded-lg focus-maroon focus:outline-none text-sm">
                            </div>

                            @if(isset($student) && $student->kelompok_asal == 'IPA')
                                <div class="grid grid-cols-2 gap-2 sm:gap-3">
                                    <div>
                                        <label for="fisika" class="block text-xs sm:text-sm font-semibold text-gray-700 mb-1">Fisika</label>
                                        <input id="fisika" type="number" name="fisika" min="0" max="100" placeholder="78" 
                                            class="block w-full px-3 sm:px-4 py-2 border border-gray-300 rounded-lg focus-maroon focus:outline-none text-sm">
                                    </div>
                                    <div>
                                        <label for="kimia" class="block text-xs sm:text-sm font-semibold text-gray-700 mb-1">Kimia</label>
                                        <input id="kimia" type="number" name="kimia" min="0" max="100" placeholder="72" 
                                            class="block w-full px-3 sm:px-4 py-2 border border-gray-300 rounded-lg focus-maroon focus:outline-none text-sm">
                                    </div>
                                    <div class="col-span-2">
                                        <label for="biologi" class="block text-xs sm:text-sm font-semibold text-gray-700 mb-1">Biologi</label>
                                        <input id="biologi" type="number" name="biologi" min="0" max="100" placeholder="80" 
                                            class="block w-full px-3 sm:px-4 py-2 border border-gray-300 rounded-lg focus-maroon focus:outline-none text-sm">
                                    </div>
                                </div>
                            @else
                                <div class="grid grid-cols-2 gap-2 sm:gap-3">
                                    <div>
                                        <label for="ekonomi" class="block text-xs sm:text-sm font-semibold text-gray-700 mb-1">Ekonomi</label>
                                        <input id="ekonomi" type="number" name="ekonomi" min="0" max="100" placeholder="82" 
                                            class="block w-full px-3 sm:px-4 py-2 border border-gray-300 rounded-lg focus-maroon focus:outline-none text-sm">
                                    </div>
                                    <div>
                                        <label for="geografi" class="block text-xs sm:text-sm font-semibold text-gray-700 mb-1">Geografi</label>
                                        <input id="geografi" type="number" name="geografi" min="0" max="100" placeholder="76" 
                                            class="block w-full px-3 sm:px-4 py-2 border border-gray-300 rounded-lg focus-maroon focus:outline-none text-sm">
                                    </div>
                                    <div>
                                        <label for="sosiologi" class="block text-xs sm:text-sm font-semibold text-gray-700 mb-1">Sosiologi</label>
                                        <input id="sosiologi" type="number" name="sosiologi" min="0" max="100" placeholder="74" 
                                            class="block w-full px-3 sm:px-4 py-2 border border-gray-300 rounded-lg focus-maroon focus:outline-none text-sm">
                                    </div>
                                    <div>
                                        <label for="sejarah" class="block text-xs sm:text-sm font-semibold text-gray-700 mb-1">Sejarah</label>
                                        <input id="sejarah" type="number" name="sejarah" min="0" max="100" placeholder="70" 
                                            class="block w-full px-3 sm:px-4 py-2 border border-gray-300 rounded-lg focus-maroon focus:outline-none text-sm">
                                    </div>
                                </div>
                            @endif
                        </div>
                    </div>

                    <!-- Kolom Minat -->
                    <div class="p-4 sm:p-5 rounded-lg border-2 border-gray-200 bg-gray-50">
                        <h3 class="font-bold text-maroon text-base sm:text-lg mb-1 sm:mb-2">2. Minat & Preferensi</h3>
                        <p class="text-xs text-gray-600 mb-3 sm:mb-4">Deskripsikan minat dan preferensi belajar Anda.</p>

                        <div class="space-y-3 sm:space-y-4">
                            <div>
                                <label for="minat" class="block text-xs sm:text-sm font-semibold text-gray-700 mb-1">Bidang yang Anda Sukai</label>
                                <input id="minat" type="text" name="minat" placeholder="Contoh: ngoding, bercocok tanam" 
                                    class="block w-full px-3 sm:px-4 py-2 border border-gray-300 rounded-lg focus-maroon focus:outline-none text-sm">
                            </div>

                            <div>
                                <label for="cita_cita" class="block text-xs sm:text-sm font-semibold text-gray-700 mb-1">Cita-cita / Profesi</label>
                                <input id="cita_cita" type="text" name="cita_cita" placeholder="Contoh: Teknisi, Dokter, Pengusaha" 
                                    class="block w-full px-3 sm:px-4 py-2 border border-gray-300 rounded-lg focus-maroon focus:outline-none text-sm">
                            </div>

                            <div>
                                <label for="pref_studi" class="block text-xs sm:text-sm font-semibold text-gray-700 mb-1">Preferensi Pembelajaran</label>
                                <select name="pref_studi" class="block w-full px-3 sm:px-4 py-2 border border-gray-300 rounded-lg focus-maroon focus:outline-none text-sm">
                                    <option value="Praktik Langsung">Praktik Langsung (Workshop / Lab)</option>
                                    <option value="DuDi">Kerja Sama dengan Industri (DuDi)</option>
                                    <option value="Project Based">Project-Based Learning</option>
                                    <option value="Blended">Teori + Praktik Seimbang</option>
                                </select>
                            </div>

                            <div>
                                <label for="prestasi" class="block text-xs sm:text-sm font-semibold text-gray-700 mb-1">Prestasi (opsional)</label>
                                <input id="prestasi" type="text" name="prestasi" placeholder="Contoh: Juara lomba, sertifikat" 
                                    class="block w-full px-3 sm:px-4 py-2 border border-gray-300 rounded-lg focus-maroon focus:outline-none text-sm">
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Submit Button -->
                <div class="mt-4 sm:mt-6 p-3 sm:p-4 rounded-lg bg-gray-50 border border-gray-200 text-center">
                    <p class="text-xs sm:text-sm text-gray-600 mb-3 sm:mb-4">
                        Setelah menekan tombol, sistem akan menganalisis data Anda dan menampilkan ranking 9 jurusan.
                    </p>
                    <button type="submit" class="w-full gradient-maroon text-white font-bold py-2 sm:py-3 px-4 sm:px-6 rounded-lg hover:opacity-90 transition duration-200 text-sm sm:text-base">
                        Lihat Rekomendasi Jurusan
                    </button>
                </div>
            </form>
        </div>

        <!-- Info Metode -->
        <div class="mt-6 sm:mt-8 p-3 sm:p-4 bg-white rounded-lg border border-gray-200 shadow-sm">
            <p class="text-xs sm:text-sm text-gray-600">
                <strong>Metode:</strong> Sistem menggunakan Weighted Naive Bayes dengan 5 kriteria: Nilai (40%), Minat (35%), Preferensi (15%), Cita-cita (5%), Prestasi (5%).
            </p>
        </div>
    </div>
</body>
</html>