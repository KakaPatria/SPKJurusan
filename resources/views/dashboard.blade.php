<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard Siswa - Sistem Pemilihan Jurusan</title>
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
        .card-hover {
            transition: all 0.3s ease;
        }
        .card-hover:hover {
            transform: translateY(-5px);
            box-shadow: 0 10px 30px rgba(107, 114, 128, 0.15);
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
                    <div class="relative">
                        <button id="profileDropdownBtn" class="bg-gray-100 text-maroon font-bold py-2 px-4 rounded-lg hover:bg-gray-200 transition text-xs sm:text-sm flex items-center gap-2" style="color: #5B7B89;">
                            👤 Profil
                            <svg id="dropdownArrow" class="w-4 h-4 transition-transform duration-300" style="transform: rotate(0deg);" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 14l-7 7m0 0l-7-7m7 7V3"></path>
                            </svg>
                        </button>
                        
                        <div id="profileDropdown" class="absolute right-0 mt-2 w-48 bg-white text-gray-800 rounded-lg shadow-lg hidden z-50">
                            <a href="{{ route('profile.edit') }}" class="block px-4 py-3 hover:bg-gray-50 text-xs sm:text-sm font-semibold border-b border-gray-100 rounded-t-lg">
                                👤 Lihat Profil
                            </a>
                            <form method="POST" action="{{ route('logout') }}" class="confirm-logout">
                                @csrf
                                <button type="submit" class="block w-full text-left px-4 py-3 hover:bg-gray-50 text-xs sm:text-sm font-semibold text-red-600 rounded-b-lg">
                                    🚪 Logout
                                </button>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </header>

    <!-- Main Content -->
    <div class="w-full px-4 sm:px-6 py-6 sm:py-8">
        <!-- Hero Section -->
        <div class="bg-gradient-to-r from-purple-600 to-indigo-600 rounded-xl shadow-xl p-8 sm:p-10 mb-8 text-white">
            <h2 class="text-3xl sm:text-4xl font-bold mb-3">Temukan Jurusan Impianmu 🎯</h2>
            <p class="text-purple-100 text-lg mb-4 max-w-3xl">
                Memilih jurusan adalah salah satu keputusan terpenting dalam hidup. Kami ada di sini untuk membantu kamu menemukan program studi yang benar-benar sesuai dengan potensi, minat, dan impian karirmu. Dengan teknologi AI dan analisis mendalam, kami siap memberikan panduan terbaik.
            </p>
            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4 mt-6">
                <div class="bg-white bg-opacity-20 rounded-lg p-4 backdrop-blur-sm">
                    <p class="text-3xl font-bold">9</p>
                    <p class="text-purple-100 text-sm">Jurusan</p>
                </div>
                <div class="bg-white bg-opacity-20 rounded-lg p-4 backdrop-blur-sm">
                    <p class="text-3xl font-bold">24/7</p>
                    <p class="text-purple-100 text-sm">Konsultasi AI</p>
                </div>
            </div>
        </div>

        <!-- Quick Action Cards -->
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-6 mb-8">
            <!-- Rekomendasi Card -->
            <div class="card-hover bg-gradient-to-br from-blue-50 to-blue-100 rounded-xl shadow-lg p-8 border-2 border-blue-300 hover:border-blue-400 transition">
                <div class="flex items-start gap-4 mb-6">
                    <div class="w-16 h-16 bg-blue-500 rounded-lg flex items-center justify-center text-white text-3xl shadow-md">📊</div>
                    <div>
                        <h3 class="text-2xl font-bold text-blue-900 mb-1">Cari Jurusan Terbaikmu</h3>
                        <p class="text-blue-700">Rekomendasi Khusus</p>
                    </div>
                </div>
                <p class="text-blue-800 mb-6 leading-relaxed">
                    Jawab beberapa pertanyaan tentang nilai akademik, minat, gaya belajar, dan cita-cita kamu. Sistem kami akan menganalisis profil lengkap kamu dan memberikan rekomendasi jurusan yang paling cocok, lengkap dengan penjelasan alasan kesesuaiannya.
                </p>
                <div class="flex items-center justify-between bg-white rounded-lg p-4 mb-6">
                    <span class="text-sm font-semibold text-gray-600">⏱️ Waktu: 3-5 menit</span>
                    <span class="text-sm font-semibold text-blue-600">📈 Akurasi Tinggi</span>
                </div>
                <a href="{{ url('/rekomendasi') }}" class="block w-full text-center bg-gradient-to-r from-blue-600 to-blue-700 hover:from-blue-700 hover:to-blue-800 text-white font-bold py-3 px-6 rounded-lg transition duration-300 shadow-md transform hover:scale-105">
                    Mulai Analisis →
                </a>
            </div>

            <!-- Chatbot Card -->
            <div class="card-hover bg-gradient-to-br from-green-50 to-green-100 rounded-xl shadow-lg p-8 border-2 border-green-300 hover:border-green-400 transition">
                <div class="flex items-start gap-4 mb-6">
                    <div class="w-16 h-16 bg-green-500 rounded-lg flex items-center justify-center text-white text-3xl shadow-md">💬</div>
                    <div>
                        <h3 class="text-2xl font-bold text-green-900 mb-1">Tanya Jawab dengan AI</h3>
                        <p class="text-green-700">Konsultan Karir Virtual</p>
                    </div>
                </div>
                <p class="text-green-800 mb-6 leading-relaxed">
                    Ada pertanyaan tentang jurusan tertentu? Penasaran dengan prospek karir? Atau butuh tips sukses masuk kuliah? Chat dengan konselor AI kami yang siap membantu kapan saja. Dapatkan jawaban detail dan rekomendasi personal untuk setiap pertanyaan kamu.
                </p>
                <div class="flex items-center justify-between bg-white rounded-lg p-4 mb-6">
                    <span class="text-sm font-semibold text-gray-600">⭐ Respons Cepat</span>
                    <span class="text-sm font-semibold text-green-600">🤖 AI Powered</span>
                </div>
                <a href="{{ url('/chatbot?new=1') }}" class="block w-full text-center bg-gradient-to-r from-green-600 to-green-700 hover:from-green-700 hover:to-green-800 text-white font-bold py-3 px-6 rounded-lg transition duration-300 shadow-md transform hover:scale-105">
                    Mulai Konsultasi →
                </a>
            </div>
        </div>

        <!-- Features Grid -->
        <div class="bg-white rounded-xl shadow-lg p-8 mb-8">
            <h3 class="text-2xl font-bold text-gray-900 mb-8">Fitur Lengkap untuk Pendampinganmu</h3>
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6">
                <div class="text-center p-6 rounded-lg bg-gradient-to-br from-purple-50 to-purple-100 hover:shadow-md transition">
                    <div class="text-4xl mb-3">📋</div>
                    <h4 class="font-bold text-gray-900 mb-2">Rekomendasi Personal</h4>
                    <p class="text-sm text-gray-700">Analisis mendalam berdasarkan profil unik kamu</p>
                </div>
                <div class="text-center p-6 rounded-lg bg-gradient-to-br from-blue-50 to-blue-100 hover:shadow-md transition">
                    <div class="text-4xl mb-3">💬</div>
                    <h4 class="font-bold text-gray-900 mb-2">Chat 24/7</h4>
                    <p class="text-sm text-gray-700">Konsultasi kapan saja dengan AI konselor</p>
                </div>
                <div class="text-center p-6 rounded-lg bg-gradient-to-br from-green-50 to-green-100 hover:shadow-md transition">
                    <div class="text-4xl mb-3">📚</div>
                    <h4 class="font-bold text-gray-900 mb-2">Info Jurusan</h4>
                    <p class="text-sm text-gray-700">Detail lengkap tentang setiap program studi</p>
                </div>
                <div class="text-center p-6 rounded-lg bg-gradient-to-br from-orange-50 to-orange-100 hover:shadow-md transition">
                    <div class="text-4xl mb-3">📊</div>
                    <h4 class="font-bold text-gray-900 mb-2">Riwayat</h4>
                    <p class="text-sm text-gray-700">Simpan & bandingkan hasil analisis sebelumnya</p>
                </div>
            </div>
        </div>

        <!-- Process Section -->
        <div class="bg-gradient-to-r from-indigo-600 to-purple-600 rounded-xl p-8 text-white mb-8">
            <h3 class="text-2xl font-bold mb-8">Bagaimana Cara Kerjanya? 🔍</h3>
            <div class="grid grid-cols-1 md:grid-cols-4 gap-6">
                <div class="text-center">
                    <div class="w-12 h-12 rounded-full bg-white text-indigo-600 font-bold text-xl flex items-center justify-center mx-auto mb-4 shadow-lg">1</div>
                    <h4 class="font-bold mb-2">Isi Kuesioner</h4>
                    <p class="text-indigo-100 text-sm">Jawab pertanyaan tentang nilai, minat, dan impian kamu secara jujur</p>
                </div>
                <div class="text-center">
                    <div class="w-12 h-12 rounded-full bg-white text-indigo-600 font-bold text-xl flex items-center justify-center mx-auto mb-4 shadow-lg">2</div>
                    <h4 class="font-bold mb-2">Proses Data</h4>
                    <p class="text-indigo-100 text-sm">AI menganalisis profil kamu dengan algoritma machine learning</p>
                </div>
                <div class="text-center">
                    <div class="w-12 h-12 rounded-full bg-white text-indigo-600 font-bold text-xl flex items-center justify-center mx-auto mb-4 shadow-lg">3</div>
                    <h4 class="font-bold mb-2">Dapatkan Hasil</h4>
                    <p class="text-indigo-100 text-sm">Terima ranking 9 jurusan dengan skor kesesuaian detail</p>
                </div>
                <div class="text-center">
                    <div class="w-12 h-12 rounded-full bg-white text-indigo-600 font-bold text-xl flex items-center justify-center mx-auto mb-4 shadow-lg">4</div>
                    <h4 class="font-bold mb-2">Konsultasi Lanjut</h4>
                    <p class="text-indigo-100 text-sm">Diskusikan hasil & pertanyaan lanjut dengan AI konselor</p>
                </div>
            </div>
        </div>

        <!-- Programs Grid -->
        <div class="bg-white rounded-xl shadow-lg p-8">
            <h3 class="text-2xl font-bold text-gray-900 mb-2">Program Studi yang Tersedia</h3>
            <p class="text-gray-600 mb-8">Jelajahi 9 program studi unggulan kami di Politeknik Negeri Jember</p>
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-4">
                <div class="group p-5 rounded-lg border-2 border-gray-200 hover:border-purple-500 hover:shadow-md transition bg-gradient-to-br from-gray-50 to-white">
                    <div class="text-3xl mb-3">🌾</div>
                    <h4 class="font-bold text-gray-900 group-hover:text-purple-600 transition">Produksi Pertanian</h4>
                    <p class="text-sm text-gray-600 mt-2">Teknik budidaya tanaman modern & berkelanjutan</p>
                </div>
                <div class="group p-5 rounded-lg border-2 border-gray-200 hover:border-purple-500 hover:shadow-md transition bg-gradient-to-br from-gray-50 to-white">
                    <div class="text-3xl mb-3">🔬</div>
                    <h4 class="font-bold text-gray-900 group-hover:text-purple-600 transition">Teknologi Pertanian</h4>
                    <p class="text-sm text-gray-600 mt-2">Inovasi teknologi untuk efisiensi pertanian</p>
                </div>
                <div class="group p-5 rounded-lg border-2 border-gray-200 hover:border-purple-500 hover:shadow-md transition bg-gradient-to-br from-gray-50 to-white">
                    <div class="text-3xl mb-3">🐄</div>
                    <h4 class="font-bold text-gray-900 group-hover:text-purple-600 transition">Peternakan</h4>
                    <p class="text-sm text-gray-600 mt-2">Manajemen & teknologi peternakan terkini</p>
                </div>
                <div class="group p-5 rounded-lg border-2 border-gray-200 hover:border-purple-500 hover:shadow-md transition bg-gradient-to-br from-gray-50 to-white">
                    <div class="text-3xl mb-3">💼</div>
                    <h4 class="font-bold text-gray-900 group-hover:text-purple-600 transition">Manajemen Agribisnis</h4>
                    <p class="text-sm text-gray-600 mt-2">Bisnis & manajemen di sektor pertanian</p>
                </div>
                <div class="group p-5 rounded-lg border-2 border-gray-200 hover:border-purple-500 hover:shadow-md transition bg-gradient-to-br from-gray-50 to-white">
                    <div class="text-3xl mb-3">💻</div>
                    <h4 class="font-bold text-gray-900 group-hover:text-purple-600 transition">Teknologi Informasi</h4>
                    <p class="text-sm text-gray-600 mt-2">Sistem digital & aplikasi web modern</p>
                </div>
                <div class="group p-5 rounded-lg border-2 border-gray-200 hover:border-purple-500 hover:shadow-md transition bg-gradient-to-br from-gray-50 to-white">
                    <div class="text-3xl mb-3">⚙️</div>
                    <h4 class="font-bold text-gray-900 group-hover:text-purple-600 transition">Teknik Mesin</h4>
                    <p class="text-sm text-gray-600 mt-2">Teknik mesin & sistem otomasi industri</p>
                </div>
                <div class="group p-5 rounded-lg border-2 border-gray-200 hover:border-purple-500 hover:shadow-md transition bg-gradient-to-br from-gray-50 to-white">
                    <div class="text-3xl mb-3">⚕️</div>
                    <h4 class="font-bold text-gray-900 group-hover:text-purple-600 transition">Kesehatan</h4>
                    <p class="text-sm text-gray-600 mt-2">Program kesehatan & keselamatan kerja</p>
                </div>
                <div class="group p-5 rounded-lg border-2 border-gray-200 hover:border-purple-500 hover:shadow-md transition bg-gradient-to-br from-gray-50 to-white">
                    <div class="text-3xl mb-3">🗣️</div>
                    <h4 class="font-bold text-gray-900 group-hover:text-purple-600 transition">Komunikasi & Pariwisata</h4>
                    <p class="text-sm text-gray-600 mt-2">Program komunikasi & industri pariwisata</p>
                </div>
                <div class="group p-5 rounded-lg border-2 border-gray-200 hover:border-purple-500 hover:shadow-md transition bg-gradient-to-br from-gray-50 to-white">
                    <div class="text-3xl mb-3">📊</div>
                    <h4 class="font-bold text-gray-900 group-hover:text-purple-600 transition">Akuntansi & Bisnis</h4>
                    <p class="text-sm text-gray-600 mt-2">Akuntansi, keuangan & manajemen bisnis</p>
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

    <script>
        // Dropdown menu toggle with arrow animation
        const profileDropdownBtn = document.getElementById('profileDropdownBtn');
        const profileDropdown = document.getElementById('profileDropdown');
        const dropdownArrow = document.getElementById('dropdownArrow');

        profileDropdownBtn.addEventListener('click', function(e) {
            e.preventDefault();
            const isHidden = profileDropdown.classList.contains('hidden');
            
            if (isHidden) {
                // Show dropdown and rotate arrow
                profileDropdown.classList.remove('hidden');
                dropdownArrow.style.transform = 'rotate(180deg)';
            } else {
                // Hide dropdown and reset arrow
                profileDropdown.classList.add('hidden');
                dropdownArrow.style.transform = 'rotate(0deg)';
            }
        });

        // Close dropdown when clicking outside
        document.addEventListener('click', function(e) {
            if (!profileDropdownBtn.contains(e.target) && !profileDropdown.contains(e.target)) {
                profileDropdown.classList.add('hidden');
                dropdownArrow.style.transform = 'rotate(0deg)';
            }
        });
    </script>
    <!-- SweetAlert2 for nicer logout modal on dashboard -->
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            document.querySelectorAll('form.confirm-logout').forEach(function(form) {
                form.addEventListener('submit', function (e) {
                    e.preventDefault();
                    Swal.fire({
                        title: 'Konfirmasi Logout',
                        text: 'Yakin ingin logout dari akun ini?',
                        icon: 'question',
                        showCancelButton: true,
                        confirmButtonText: 'Ya, Logout',
                        cancelButtonText: 'Batal',
                        reverseButtons: true
                    }).then(function(result) {
                        if (result.isConfirmed) {
                            form.submit();
                        }
                    });
                });
            });
        });
    </script>
</body>
</html>