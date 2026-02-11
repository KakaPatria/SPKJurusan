<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard Siswa - Sistem Pemilihan Jurusan</title>
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
        .card-hover {
            transition: all 0.3s ease;
        }
        .card-hover:hover {
            transform: translateY(-5px);
            box-shadow: 0 10px 30px rgba(107, 44, 44, 0.2);
        }
    </style>
</head>
<body class="bg-cream">
    <!-- Header -->
    <header class="gradient-maroon text-white shadow-lg sticky top-0 z-50">
        <div class="container mx-auto px-4 sm:px-6 py-4 sm:py-6">
            <div class="flex flex-col sm:flex-row sm:justify-between sm:items-center gap-3 sm:gap-4">
                <div>
                    <h1 class="text-xl sm:text-2xl md:text-3xl font-bold">Dashboard Siswa</h1>
                    <p class="text-xs sm:text-sm text-yellow-300 font-semibold mt-1">Sistem Pemilihan Jurusan</p>
                </div>
                <div class="flex flex-col sm:flex-row items-start sm:items-center gap-2 sm:gap-4">
                    <span class="text-xs sm:text-sm md:text-base text-yellow-200">Selamat datang, <strong>{{ Auth::user()->name }}</strong>!</span>
                    <div class="flex gap-2 w-full sm:w-auto">
                        <a href="{{ route('profile.edit') }}" class="flex-1 sm:flex-none block sm:inline-block text-center bg-white font-bold py-2 px-4 rounded-lg hover:bg-gray-100 transition text-xs sm:text-sm" style="color: #6B2C2C;">
                            👤 Profil
                        </a>
                        <form method="POST" action="{{ route('logout') }}" class="flex-1 sm:flex-none">
                            @csrf
                            <button type="submit" class="block sm:inline-block w-full sm:w-auto bg-yellow-400 text-maroon font-bold py-2 px-4 rounded-lg hover:bg-yellow-300 transition text-xs sm:text-sm text-center">
                                Logout
                            </button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </header>

    <!-- Main Content -->
    <div class="container mx-auto px-4 sm:px-6 py-6 sm:py-12">
        <!-- Info Box -->
        <div class="bg-white border-2 border-maroon rounded-lg p-4 sm:p-6 mb-6 sm:mb-8 shadow-md">
            <h2 class="text-lg sm:text-xl md:text-2xl font-bold text-maroon mb-2 sm:mb-3">Selamat Datang</h2>
            <p class="text-xs sm:text-sm md:text-base text-gray-700 mb-2 sm:mb-3">
                Sistem ini membantu Anda menemukan jurusan yang sesuai dengan profil akademik, minat, dan preferensi pembelajaran Anda.
            </p>
            <p class="text-xs sm:text-sm md:text-base text-gray-700">
                Sistem menganalisis 5 faktor: nilai akademik, minat, preferensi pembelajaran, prestasi, dan cita-cita. 
                Berdasarkan analisis tersebut, sistem memberikan rekomendasi dari 9 pilihan jurusan yang tersedia.
            </p>
        </div>

        <!-- Main Actions Grid -->
        <div class="grid grid-cols-1 md:grid-cols-2 gap-4 sm:gap-8 mb-6 sm:mb-12">
            <!-- Rekomendasi Card -->
            <div class="card-hover bg-white rounded-lg shadow-lg p-5 sm:p-8 border-l-4 border-blue-500 flex flex-col h-full">
                <div class="flex items-start gap-3 sm:gap-4 mb-3 sm:mb-4 flex-grow">
                    <div class="text-3xl sm:text-4xl flex-shrink-0">📊</div>
                    <div>
                        <h3 class="text-lg sm:text-lg md:text-2xl font-bold text-maroon mb-1 sm:mb-2">Analisis Rekomendasi</h3>
                        <p class="text-xs sm:text-sm md:text-base text-gray-700">
                            Isi formulir singkat tentang profil Anda. Sistem akan menganalisis dan memberikan rekomendasi dari 9 jurusan yang tersedia.
                        </p>
                    </div>
                </div>
                <div class="mt-4 sm:mt-6">
                    <a href="{{ url('/rekomendasi') }}" class="block w-full text-center bg-blue-600 hover:bg-blue-700 text-white font-bold py-2 sm:py-3 px-4 sm:px-6 rounded-lg transition duration-200 text-sm sm:text-base">
                        Mulai Analisis Baru
                    </a>
                </div>
                <div class="mt-3 sm:mt-4 p-2 sm:p-3 bg-blue-50 rounded-lg">
                    <p class="text-xs sm:text-sm text-blue-800">
                        <strong>Durasi:</strong> 3-5 menit | <strong>Metode:</strong> AI Analysis
                    </p>
                </div>
            </div>

            <!-- Chatbot Card -->
            <div class="card-hover bg-white rounded-lg shadow-lg p-5 sm:p-8 border-l-4 border-green-500 flex flex-col h-full">
                <div class="flex items-start gap-3 sm:gap-4 mb-3 sm:mb-4 flex-grow">
                    <div class="text-3xl sm:text-4xl flex-shrink-0">💬</div>
                    <div>
                        <h3 class="text-lg sm:text-lg md:text-2xl font-bold text-maroon mb-1 sm:mb-2">Chat dengan AI</h3>
                        <p class="text-xs sm:text-sm md:text-base text-gray-700">
                            Tanya jawab tentang jurusan, kurikulum, prospek karir, dan pertanyaan seputar pemilihan jurusan.
                        </p>
                    </div>
                </div>
                <div class="mt-4 sm:mt-6">
                    <a href="{{ url('/chatbot') }}" class="block w-full text-center bg-green-600 hover:bg-green-700 text-white font-bold py-2 sm:py-3 px-4 sm:px-6 rounded-lg transition duration-200 text-sm sm:text-base">
                        Mulai Chat
                    </a>
                </div>
                <div class="mt-3 sm:mt-4 p-2 sm:p-3 bg-green-50 rounded-lg">
                    <p class="text-xs sm:text-sm text-green-800">
                        <strong>Fitur:</strong> Tanya jawab dengan AI
                    </p>
                </div>
            </div>
        </div>

        <!-- History Grid -->
        <div class="grid grid-cols-1 md:grid-cols-2 gap-4 sm:gap-8 mb-8 sm:mb-16">
            <!-- History Rekomendasi Card -->
            <div class="card-hover bg-white rounded-lg shadow-lg p-5 sm:p-8 border-l-4 border-purple-500 flex flex-col h-full">
                <div class="flex items-start gap-3 sm:gap-4 mb-3 sm:mb-4 flex-grow">
                    <div class="text-3xl sm:text-4xl flex-shrink-0">📋</div>
                    <div>
                        <h3 class="text-lg sm:text-lg md:text-2xl font-bold text-maroon mb-1 sm:mb-2">History Rekomendasi</h3>
                        <p class="text-xs sm:text-sm md:text-base text-gray-700">
                            Lihat semua hasil analisis rekomendasi yang telah Anda lakukan sebelumnya.
                        </p>
                    </div>
                </div>
                <div class="mt-4 sm:mt-6">
                    <a href="{{ url('/history/rekomendasi') }}" class="block w-full text-center bg-purple-600 hover:bg-purple-700 text-white font-bold py-2 sm:py-3 px-4 sm:px-6 rounded-lg transition duration-200 text-sm sm:text-base">
                        Lihat History
                    </a>
                </div>
                <div class="mt-3 sm:mt-4 p-2 sm:p-3 bg-purple-50 rounded-lg">
                    <p class="text-xs sm:text-sm text-purple-800">
                        <strong>Total:</strong> {{ $recommendationCount ?? 0 }} analisis
                    </p>
                </div>
            </div>

            <!-- History Chat Card -->
            <div class="card-hover bg-white rounded-lg shadow-lg p-5 sm:p-8 flex flex-col h-full" style="border-left: 4px solid #EA580C;">
                <div class="flex items-start gap-3 sm:gap-4 mb-3 sm:mb-4 flex-grow">
                    <div class="text-3xl sm:text-4xl flex-shrink-0">💾</div>
                    <div>
                        <h3 class="text-lg sm:text-lg md:text-2xl font-bold text-maroon mb-1 sm:mb-2">History Chat</h3>
                        <p class="text-xs sm:text-sm md:text-base text-gray-700">
                            Lihat riwayat semua percakapan Anda dengan AI chatbot.
                        </p>
                    </div>
                </div>
                <div class="mt-4 sm:mt-6">
                    <a href="{{ url('/history/chat') }}" class="block w-full text-center text-white font-bold py-2 sm:py-3 px-4 sm:px-6 rounded-lg transition duration-200 text-sm sm:text-base" style="background-color: #EA580C;">
                        Lihat History
                    </a>
                </div>
                <div class="mt-3 sm:mt-4 p-2 sm:p-3 rounded-lg" style="background-color: #FFF7ED;">
                    <p class="text-xs sm:text-sm" style="color: #9A3412;">
                        <strong>Total:</strong> {{ $chatCount ?? 0 }} chat
                    </p>
                </div>
            </div>
        </div>

        <!-- Info Section -->
        <div class="bg-white rounded-lg shadow-lg p-5 sm:p-8 mb-6 sm:mb-8">
            <h3 class="text-lg sm:text-xl md:text-2xl font-bold text-maroon mb-4 sm:mb-6">9 Jurusan Tersedia</h3>
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-3 sm:gap-6">
                <div class="p-3 sm:p-4 bg-gradient-to-br from-yellow-50 to-orange-50 rounded-lg border border-maroon">
                    <div class="text-2xl sm:text-3xl mb-1 sm:mb-2">🌾</div>
                    <h4 class="font-bold text-sm sm:text-base text-maroon">Produksi Pertanian</h4>
                    <p class="text-xs sm:text-sm text-gray-700 mt-1">Teknik budidaya tanaman modern</p>
                </div>
                <div class="p-3 sm:p-4 bg-gradient-to-br from-green-50 to-teal-50 rounded-lg border border-maroon">
                    <div class="text-2xl sm:text-3xl mb-1 sm:mb-2">🔬</div>
                    <h4 class="font-bold text-sm sm:text-base text-maroon">Teknologi Pertanian</h4>
                    <p class="text-xs sm:text-sm text-gray-700 mt-1">Inovasi teknologi pertanian</p>
                </div>
                <div class="p-3 sm:p-4 bg-gradient-to-br from-blue-50 to-purple-50 rounded-lg border border-maroon">
                    <div class="text-2xl sm:text-3xl mb-1 sm:mb-2">🐄</div>
                    <h4 class="font-bold text-sm sm:text-base text-maroon">Peternakan</h4>
                    <p class="text-xs sm:text-sm text-gray-700 mt-1">Manajemen peternakan</p>
                </div>
                <div class="p-3 sm:p-4 bg-gradient-to-br from-red-50 to-pink-50 rounded-lg border border-maroon">
                    <div class="text-2xl sm:text-3xl mb-1 sm:mb-2">💼</div>
                    <h4 class="font-bold text-sm sm:text-base text-maroon">Manajemen Agribisnis</h4>
                    <p class="text-xs sm:text-sm text-gray-700 mt-1">Bisnis pertanian</p>
                </div>
                <div class="p-3 sm:p-4 bg-gradient-to-br from-indigo-50 to-blue-50 rounded-lg border border-maroon">
                    <div class="text-2xl sm:text-3xl mb-1 sm:mb-2">💻</div>
                    <h4 class="font-bold text-sm sm:text-base text-maroon">Teknologi Informasi</h4>
                    <p class="text-xs sm:text-sm text-gray-700 mt-1">Sistem digital</p>
                </div>
                <div class="p-3 sm:p-4 bg-gradient-to-br from-amber-50 to-yellow-50 rounded-lg border border-maroon">
                    <div class="text-2xl sm:text-3xl mb-1 sm:mb-2">⚙️</div>
                    <h4 class="font-bold text-sm sm:text-base text-maroon">Teknik</h4>
                    <p class="text-xs sm:text-sm text-gray-700 mt-1">Mesin & sistem teknik</p>
                </div>
                <div class="p-3 sm:p-4 bg-gradient-to-br from-rose-50 to-red-50 rounded-lg border border-maroon">
                    <div class="text-2xl sm:text-3xl mb-1 sm:mb-2">⚕️</div>
                    <h4 class="font-bold text-sm sm:text-base text-maroon">Kesehatan</h4>
                    <p class="text-xs sm:text-sm text-gray-700 mt-1">Profesi kesehatan</p>
                </div>
                <div class="p-3 sm:p-4 bg-gradient-to-br from-sky-50 to-cyan-50 rounded-lg border border-maroon">
                    <div class="text-2xl sm:text-3xl mb-1 sm:mb-2">🗣️</div>
                    <h4 class="font-bold text-sm sm:text-base text-maroon">Bahasa & Komunikasi</h4>
                    <p class="text-xs sm:text-sm text-gray-700 mt-1">Komunasikan & wisata</p>
                </div>
                <div class="p-3 sm:p-4 bg-gradient-to-br from-lime-50 to-green-50 rounded-lg border border-maroon">
                    <div class="text-2xl sm:text-3xl mb-1 sm:mb-2">📊</div>
                    <h4 class="font-bold text-sm sm:text-base text-maroon">Bisnis</h4>
                    <p class="text-xs sm:text-sm text-gray-700 mt-1">Manajemen bisnis</p>
                </div>
            </div>
        </div>

        <!-- Features Section -->
        <div class="bg-white rounded-lg shadow-lg p-5 sm:p-8">
            <h3 class="text-lg sm:text-xl md:text-2xl font-bold text-maroon mb-4 sm:mb-6">Cara Kerja Sistem</h3>
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4 sm:gap-6">
                <div class="flex gap-3 sm:gap-4">
                    <div class="text-2xl sm:text-3xl font-bold text-maroon flex-shrink-0">1</div>
                    <div>
                        <h4 class="font-bold text-sm sm:text-base text-maroon mb-1">Isi Data Diri</h4>
                        <p class="text-xs sm:text-sm text-gray-700">Masukkan nilai akademik, minat, preferensi, prestasi, dan cita-cita</p>
                    </div>
                </div>
                <div class="flex gap-3 sm:gap-4">
                    <div class="text-2xl sm:text-3xl font-bold text-maroon flex-shrink-0">2</div>
                    <div>
                        <h4 class="font-bold text-sm sm:text-base text-maroon mb-1">Analisis Sistem</h4>
                        <p class="text-xs sm:text-sm text-gray-700">Sistem menganalisis data menggunakan algoritma canggih</p>
                    </div>
                </div>
                <div class="flex gap-3 sm:gap-4">
                    <div class="text-2xl sm:text-3xl font-bold text-maroon flex-shrink-0">3</div>
                    <div>
                        <h4 class="font-bold text-sm sm:text-base text-maroon mb-1">Hasil Rekomendasi</h4>
                        <p class="text-xs sm:text-sm text-gray-700">Dapatkan rekomendasi 9 jurusan dengan analisis detail</p>
                    </div>
                </div>
                <div class="flex gap-3 sm:gap-4">
                    <div class="text-2xl sm:text-3xl font-bold text-maroon flex-shrink-0">4</div>
                    <div>
                        <h4 class="font-bold text-sm sm:text-base text-maroon mb-1">Konsultasi Lanjut</h4>
                        <p class="text-xs sm:text-sm text-gray-700">Gunakan chatbot untuk tanya jawab lebih lanjut</p>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Footer -->
    <footer class="gradient-maroon text-white mt-8 sm:mt-12 py-4 sm:py-6">
        <div class="container mx-auto px-4 sm:px-6 text-center">
            <p class="text-xs sm:text-sm text-yellow-200">Sistem Pemilihan Jurusan © 2026 | SMA Bima Ambulu</p>
        </div>
    </footer>
</body>
</html>