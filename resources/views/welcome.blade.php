<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>SPK Jurusan Polije - Sistem Pendukung Keputusan Pemilihan Jurusan</title>
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
        .mobile-menu-toggle {
            display: none;
        }
        @media (max-width: 767px) {
            .mobile-menu-toggle {
                display: block;
            }
            .mobile-menu {
                display: none !important;
                flex-direction: column;
                position: absolute;
                top: 100%;
                left: 0;
                right: 0;
                background: linear-gradient(135deg, #5B7B89 0%, #7B9BA5 100%);
                padding: 1rem 1.5rem;
                gap: 0.5rem;
                box-shadow: 0 4px 12px rgba(0,0,0,0.15);
                z-index: 50;
            }
            .mobile-menu.active {
                display: flex !important;
            }
        }
    </style>
</head>
<body class="bg-cream">
    <header class="gradient-maroon text-white shadow-lg sticky top-0 z-50">
        <div class="container mx-auto px-4 sm:px-6 py-4 flex justify-between items-center relative">
            <div>
                <h1 class="text-xl sm:text-2xl md:text-3xl font-bold">Sistem Pemilihan Jurusan</h1>
                <p class="text-xs sm:text-sm text-gray-200 font-semibold mt-1">Pilih Jurusan yang Tepat.</p>
            </div>
            <button class="mobile-menu-toggle text-white text-2xl" id="menuToggle">☰</button>
            <div class="mobile-menu md:flex md:space-x-4 md:items-center" id="mobileMenu">
                @if (Route::has('login'))
                    <a href="{{ route('login') }}" class="bg-yellow-400 text-maroon font-bold py-2 px-6 rounded-lg hover:bg-yellow-300 transition text-sm sm:text-base text-center block md:inline-block">Login</a>
                    <a href="{{ route('register') }}" class="border-2 border-yellow-400 text-yellow-300 font-bold py-2 px-6 rounded-lg hover:bg-yellow-400 hover:text-maroon transition text-sm sm:text-base text-center block md:inline-block">Daftar</a>
                @endif
            </div>
        </div>
    </header>

    <section class="py-8 sm:py-12 md:py-16 gradient-maroon text-white">
        <div class="container mx-auto px-4 sm:px-6">
            <div class="grid grid-cols-1 md:grid-cols-2 gap-8 md:gap-12 items-center">
                <div>
                    <h2 class="text-3xl sm:text-4xl md:text-5xl font-bold mb-4 sm:mb-6">Tentukan Pilihan Jurusan dengan Bijak</h2>
                    <p class="text-sm sm:text-base md:text-lg mb-3 sm:mb-4 text-yellow-100">
                        Selamat datang, siswa SMA Bima Ambulu. Memilih jurusan di Politeknik Negeri Jember adalah keputusan penting yang mempengaruhi masa depan. Sistem ini membantu Anda menentukan pilihan jurusan yang sesuai dengan kemampuan dan minat.
                    </p>
                    <p class="text-sm sm:text-base md:text-lg mb-6 sm:mb-8 text-yellow-100">
                        Sistem menganalisis 5 aspek utama untuk memberikan rekomendasi jurusan Politeknik Negeri Jember (Polije) yang akurat dan personal.
                    </p>
                    <div class="flex flex-col sm:flex-row gap-3 sm:gap-4">
                        <a href="{{ route('login') }}" class="inline-block bg-yellow-400 text-maroon font-bold py-2 sm:py-3 px-6 sm:px-8 rounded-lg hover:bg-yellow-300 transition text-sm sm:text-base text-center">Mulai Analisis</a>
                    </div>
                </div>
                <div class="flex justify-center">
                    <div class="bg-white rounded-lg p-6 sm:p-8 shadow-2xl w-full max-w-sm text-center text-maroon">
                        <div class="text-5xl sm:text-6xl mb-4">✨</div>
                        <h3 class="text-xl sm:text-2xl font-bold mb-3 sm:mb-4">9 Kategori Jurusan</h3>
                        <p class="text-xs sm:text-sm text-gray-600 mb-4 sm:mb-6">Pilihan jurusan Politeknik Negeri Jember dari berbagai bidang: pertanian, teknologi, kesehatan, hingga bisnis</p>
                        <div class="bg-yellow-100 rounded-lg p-4">
                            <p class="text-xs sm:text-sm font-semibold">Temukan Ranking Jurusan yang Paling Cocok buat Kamu</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- MENGAPA SISTEM INI PENTING -->
    <section class="py-8 sm:py-12 md:py-16 bg-white">
        <div class="container mx-auto px-4 sm:px-6">
            <h2 class="text-2xl sm:text-3xl md:text-4xl font-bold text-center text-maroon mb-8 sm:mb-12">Mengapa Perlu Menggunakan Sistem Ini?</h2>
            <div class="grid grid-cols-1 md:grid-cols-3 gap-6 sm:gap-8">
                <div class="p-6 sm:p-8 bg-cream rounded-xl border-2 border-maroon shadow-md">
                    <div class="text-4xl sm:text-5xl mb-3 sm:mb-4">🤖</div>
                    <h3 class="text-lg sm:text-xl font-bold text-maroon mb-2 sm:mb-3">Analisis Berbasis Data</h3>
                            <p class="text-xs sm:text-sm md:text-base text-gray-700">Menggunakan pendekatan berbasis data untuk menganalisis profil akademik dan non-akademik secara detail</p>
                </div>
                <div class="p-6 sm:p-8 bg-cream rounded-xl border-2 border-maroon shadow-md">
                    <div class="text-4xl sm:text-5xl mb-3 sm:mb-4">🎯</div>
                    <h3 class="text-lg sm:text-xl font-bold text-maroon mb-2 sm:mb-3">Pendekatan Menyeluruh</h3>
                    <p class="text-xs sm:text-sm md:text-base text-gray-700">Tidak hanya berdasarkan nilai, tetapi juga minat, preferensi studi lanjutan, cita-cita karir, dan prestasi</p>
                </div>
                <div class="p-6 sm:p-8 bg-cream rounded-xl border-2 border-maroon shadow-md">
                    <div class="text-4xl sm:text-5xl mb-3 sm:mb-4">💡</div>
                    <h3 class="text-lg sm:text-xl font-bold text-maroon mb-2 sm:mb-3">Rekomendasi Akurat</h3>
                    <p class="text-xs sm:text-sm md:text-base text-gray-700">Membantu Anda memilih jurusan dengan lebih yakin berdasarkan data yang objektif</p>
                </div>
            </div>
        </div>
    </section>


    <!-- 5 FAKTOR KEPUTUSAN -->
    <section class="py-8 sm:py-12 md:py-16 bg-cream">
        <div class="container mx-auto px-4 sm:px-6">
            <h2 class="text-2xl sm:text-3xl md:text-4xl font-bold text-center text-maroon mb-3 sm:mb-4">5 Aspek yang Dianalisis Sistem</h2>
            <p class="text-center text-xs sm:text-sm md:text-base text-gray-700 mb-8 sm:mb-12 max-w-3xl mx-auto">Sistem ini menganalisis 5 aspek penting dalam profil Anda untuk memberikan rekomendasi jurusan yang tepat dan personal.</p>
            <div class="max-w-4xl mx-auto space-y-4 sm:space-y-6">
                <div class="bg-white p-5 sm:p-8 rounded-lg border-l-4 border-maroon shadow-md hover:shadow-lg transition">
                    <div class="flex flex-col sm:flex-row items-start gap-4 sm:gap-6">
                        <div class="text-2xl sm:text-3xl font-bold text-yellow-400 bg-maroon rounded-full w-12 h-12 sm:w-16 sm:h-16 flex items-center justify-center flex-shrink-0">1</div>
                        <div>
                            <h3 class="text-lg sm:text-xl md:text-2xl font-bold text-maroon mb-1 sm:mb-2">📚 Nilai Akademik (Bobot 40%)</h3>
                            <p class="text-xs sm:text-sm md:text-base text-gray-700">Nilai Anda pada mata pelajaran utama (IPA atau IPS) menunjukkan kemampuan dasar untuk sukses di jurusan tertentu</p>
                        </div>
                    </div>
                </div>
                <div class="bg-white p-5 sm:p-8 rounded-lg border-l-4 border-maroon shadow-md hover:shadow-lg transition">
                    <div class="flex flex-col sm:flex-row items-start gap-4 sm:gap-6">
                        <div class="text-2xl sm:text-3xl font-bold text-yellow-400 bg-maroon rounded-full w-12 h-12 sm:w-16 sm:h-16 flex items-center justify-center flex-shrink-0">2</div>
                        <div>
                            <h3 class="text-lg sm:text-xl md:text-2xl font-bold text-maroon mb-1 sm:mb-2">❤️ Minat & Passion (Bobot 35%)</h3>
                            <p class="text-xs sm:text-sm md:text-base text-gray-700">Minat Anda terhadap bidang atau kegiatan tertentu sangat menentukan keberhasilan dalam perkuliahan</p>
                        </div>
                    </div>
                </div>
                <div class="bg-white p-5 sm:p-8 rounded-lg border-l-4 border-maroon shadow-md hover:shadow-lg transition">
                    <div class="flex flex-col sm:flex-row items-start gap-4 sm:gap-6">
                        <div class="text-2xl sm:text-3xl font-bold text-yellow-400 bg-maroon rounded-full w-12 h-12 sm:w-16 sm:h-16 flex items-center justify-center flex-shrink-0">3</div>
                        <div>
                            <h3 class="text-lg sm:text-xl md:text-2xl font-bold text-maroon mb-1 sm:mb-2">🎓 Preferensi Studi Lanjutan (Bobot 15%)</h3>
                            <p class="text-xs sm:text-sm md:text-base text-gray-700">Pilihan rumpun bidang studi (Sains & Teknologi, Pertanian, Kesehatan, Bisnis, atau Sosial & Humaniora) yang sesuai dengan kecenderungan dan prospek karir Anda</p>
                        </div>
                    </div>
                </div>
                <div class="bg-white p-5 sm:p-8 rounded-lg border-l-4 border-maroon shadow-md hover:shadow-lg transition">
                    <div class="flex flex-col sm:flex-row items-start gap-4 sm:gap-6">
                        <div class="text-2xl sm:text-3xl font-bold text-yellow-400 bg-maroon rounded-full w-12 h-12 sm:w-16 sm:h-16 flex items-center justify-center flex-shrink-0">4</div>
                        <div>
                            <h3 class="text-lg sm:text-xl md:text-2xl font-bold text-maroon mb-1 sm:mb-2">🚀 Impian dan Tujuan Karir (Bobot 5%)</h3>
                            <p class="text-xs sm:text-sm md:text-base text-gray-700">Cita-cita dan target karir Anda membantu mencocokkan pilihan jurusan dengan jalur profesional yang diinginkan</p>
                        </div>
                    </div>
                </div>
                <div class="bg-white p-5 sm:p-8 rounded-lg border-l-4 border-maroon shadow-md hover:shadow-lg transition">
                    <div class="flex flex-col sm:flex-row items-start gap-4 sm:gap-6">
                        <div class="text-2xl sm:text-3xl font-bold text-yellow-400 bg-maroon rounded-full w-12 h-12 sm:w-16 sm:h-16 flex items-center justify-center flex-shrink-0">5</div>
                        <div>
                            <h3 class="text-lg sm:text-xl md:text-2xl font-bold text-maroon mb-1 sm:mb-2">🏆 Prestasi dan Kegiatan (Bobot 5%)</h3>
                            <p class="text-xs sm:text-sm md:text-base text-gray-700">Pencapaian dalam kompetisi akademik maupun kegiatan non-akademik menunjukkan potensi dan dedikasi</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- UNGGULAN FITUR -->
    <section class="py-8 sm:py-12 md:py-16 bg-white">
        <div class="container mx-auto px-4 sm:px-6">
            <h2 class="text-2xl sm:text-3xl md:text-4xl font-bold text-center text-maroon mb-3 sm:mb-4">Unggulan Fitur Sistem</h2>
            <p class="text-center text-xs sm:text-sm md:text-base text-gray-700 mb-8 sm:mb-12 max-w-3xl mx-auto">Sistem ini dilengkapi dengan fitur-fitur canggih untuk memberikan pengalaman terbaik dalam memilih jurusan.</p>
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6 sm:gap-8">
                <div class="bg-cream p-6 sm:p-8 rounded-lg border-2 border-maroon shadow-md hover:shadow-lg transition">
                    <div class="text-4xl sm:text-5xl mb-4">🤖</div>
                    <h3 class="text-lg sm:text-xl font-bold text-maroon mb-2 sm:mb-3">Algoritma Naive Bayes</h3>
                    <p class="text-xs sm:text-sm md:text-base text-gray-700">Menggunakan algoritma machine learning terkini untuk memberikan rekomendasi yang akurat dan terpercaya</p>
                </div>
                <div class="bg-cream p-6 sm:p-8 rounded-lg border-2 border-maroon shadow-md hover:shadow-lg transition">
                    <div class="text-4xl sm:text-5xl mb-4">📊</div>
                    <h3 class="text-lg sm:text-xl font-bold text-maroon mb-2 sm:mb-3">Analisis Multi-Faktor</h3>
                    <p class="text-xs sm:text-sm md:text-base text-gray-700">Menganalisis 5 aspek penting: nilai akademik, minat, preferensi studi, cita-cita, dan prestasi</p>
                </div>
                <div class="bg-cream p-6 sm:p-8 rounded-lg border-2 border-maroon shadow-md hover:shadow-lg transition">
                    <div class="text-4xl sm:text-5xl mb-4">✨</div>
                    <h3 class="text-lg sm:text-xl font-bold text-maroon mb-2 sm:mb-3">Rekomendasi Personal</h3>
                    <p class="text-xs sm:text-sm md:text-base text-gray-700">Setiap siswa mendapat rekomendasi ranking jurusan yang unik sesuai dengan profil individual mereka</p>
                </div>
                <div class="bg-cream p-6 sm:p-8 rounded-lg border-2 border-maroon shadow-md hover:shadow-lg transition">
                    <div class="text-4xl sm:text-5xl mb-4">📝</div>
                    <h3 class="text-lg sm:text-xl font-bold text-maroon mb-2 sm:mb-3">Riwayat Rekomendasi</h3>
                    <p class="text-xs sm:text-sm md:text-base text-gray-700">Lacak semua rekomendasi yang telah dilakukan sebelumnya dan lihat perubahan scoring dari waktu ke waktu</p>
                </div>
                <div class="bg-cream p-6 sm:p-8 rounded-lg border-2 border-maroon shadow-md hover:shadow-lg transition">
                    <div class="text-4xl sm:text-5xl mb-4">💬</div>
                    <h3 class="text-lg sm:text-xl font-bold text-maroon mb-2 sm:mb-3">Chatbot AI</h3>
                    <p class="text-xs sm:text-sm md:text-base text-gray-700">Dapatkan konsultasi dan bimbingan dari chatbot berbasis AI yang siap membantu 24/7</p>
                </div>
                <div class="bg-cream p-6 sm:p-8 rounded-lg border-2 border-maroon shadow-md hover:shadow-lg transition">
                    <div class="text-4xl sm:text-5xl mb-4">👁️</div>
                    <h3 class="text-lg sm:text-xl font-bold text-maroon mb-2 sm:mb-3">Monitoring Guru BK</h3>
                    <p class="text-xs sm:text-sm md:text-base text-gray-700">Guru BK dapat memantau progress siswa dan memberikan bimbingan tambahan secara real-time</p>
                </div>
            </div>
        </div>
    </section>

    <!-- 9 JURUSAN TERSEDIA -->
    <section class="py-8 sm:py-12 md:py-16 bg-cream">
        <div class="container mx-auto px-4 sm:px-6">
            <h2 class="text-2xl sm:text-3xl md:text-4xl font-bold text-center text-maroon mb-3 sm:mb-4">9 Kategori Jurusan yang Bisa Dipilih</h2>
            <p class="text-center text-xs sm:text-sm md:text-base text-gray-700 mb-8 sm:mb-12 max-w-3xl mx-auto">Tersedia banyak pilihan jurusan dari berbagai bidang. Sistem ini akan menganalisis profil Anda dan merekomendasikan jurusan yang paling sesuai berdasarkan nilai, minat, dan potensi.</p>
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-4 sm:gap-6">
                <div class="bg-white border-2 border-maroon rounded-lg shadow-lg hover:shadow-xl transition p-5 sm:p-6">
                    <div class="text-3xl sm:text-4xl mb-3">🌾</div>
                    <h3 class="text-base sm:text-lg md:text-xl font-bold text-maroon mb-2">Produksi Pertanian</h3>
                    <p class="text-xs sm:text-sm text-gray-700">Cocok buat yang suka bercocok tanam dan ingin meningkatkan hasil pertanian dengan metode modern.</p>
                </div>
                <div class="bg-white border-2 border-maroon rounded-lg shadow-lg hover:shadow-xl transition p-5 sm:p-6">
                    <div class="text-3xl sm:text-4xl mb-3">🔬</div>
                    <h3 class="text-base sm:text-lg md:text-xl font-bold text-maroon mb-2">Teknologi Pertanian</h3>
                    <p class="text-xs sm:text-sm text-gray-700">Belajar teknologi terkini untuk inovasi pertanian yang lebih berkelanjutan.</p>
                </div>
                <div class="bg-white border-2 border-maroon rounded-lg shadow-lg hover:shadow-xl transition p-5 sm:p-6">
                    <div class="text-3xl sm:text-4xl mb-3">🐄</div>
                    <h3 class="text-base sm:text-lg md:text-xl font-bold text-maroon mb-2">Peternakan</h3>
                    <p class="text-xs sm:text-sm text-gray-700">Mengelola dan mengembangkan usaha peternakan secara profesional.</p>
                </div>
                <div class="bg-white border-2 border-maroon rounded-lg shadow-lg hover:shadow-xl transition p-5 sm:p-6">
                    <div class="text-3xl sm:text-4xl mb-3">💼</div>
                    <h3 class="text-base sm:text-lg md:text-xl font-bold text-maroon mb-2">Manajemen Agribisnis</h3>
                    <p class="text-xs sm:text-sm text-gray-700">Ideal buat yang ingin jadi entrepreneur atau manajer bisnis di bidang pertanian.</p>
                </div>
                <div class="bg-white border-2 border-maroon rounded-lg shadow-lg hover:shadow-xl transition p-5 sm:p-6">
                    <div class="text-3xl sm:text-4xl mb-3">💻</div>
                    <h3 class="text-base sm:text-lg md:text-xl font-bold text-maroon mb-2">Teknologi Informasi</h3>
                    <p class="text-xs sm:text-sm text-gray-700">Buat yang suka coding, bikin aplikasi, dan terjun ke dunia digital.</p>
                </div>
                <div class="bg-white border-2 border-maroon rounded-lg shadow-lg hover:shadow-xl transition p-5 sm:p-6">
                    <div class="text-3xl sm:text-4xl mb-3">⚙️</div>
                    <h3 class="text-base sm:text-lg md:text-xl font-bold text-maroon mb-2">Teknik</h3>
                    <p class="text-xs sm:text-sm text-gray-700">Cocok untuk yang suka mesin, desain teknik, dan sistem manufaktur.</p>
                </div>
                <div class="bg-white border-2 border-maroon rounded-lg shadow-lg hover:shadow-xl transition p-5 sm:p-6">
                    <div class="text-3xl sm:text-4xl mb-3">⚕️</div>
                    <h3 class="text-base sm:text-lg md:text-xl font-bold text-maroon mb-2">Kesehatan</h3>
                    <p class="text-xs sm:text-sm text-gray-700">Untuk yang ingin berkarir di bidang kesehatan dengan keterampilan praktis tinggi.</p>
                </div>
                <div class="bg-white border-2 border-maroon rounded-lg shadow-lg hover:shadow-xl transition p-5 sm:p-6">
                    <div class="text-3xl sm:text-4xl mb-3">🗣️</div>
                    <h3 class="text-base sm:text-lg md:text-xl font-bold text-maroon mb-2">Bahasa, Komunikasi, dan Pariwisata</h3>
                    <p class="text-xs sm:text-sm text-gray-700">Buat yang suka berkomunikasi, hospitality, dan dunia pariwisata.</p>
                </div>
                <div class="bg-white border-2 border-maroon rounded-lg shadow-lg hover:shadow-xl transition p-5 sm:p-6">
                    <div class="text-3xl sm:text-4xl mb-3">📊</div>
                    <h3 class="text-base sm:text-lg md:text-xl font-bold text-maroon mb-2">Bisnis</h3>
                    <p class="text-xs sm:text-sm text-gray-700">Jalan menuju karir sebagai pemimpin bisnis atau profesional manajemen.</p>
                </div>
            </div>
        </div>
    </section>

    <!-- HOW IT WORKS -->
    <section id="how-it-works" class="py-8 sm:py-12 md:py-16 bg-cream">
        <div class="container mx-auto px-4 sm:px-6">
            <h2 class="text-2xl sm:text-3xl md:text-4xl font-bold text-center text-maroon mb-3 sm:mb-4">Cara Menggunakan Sistem</h2>
            <p class="text-center text-xs sm:text-sm md:text-base text-gray-700 mb-8 sm:mb-12 max-w-3xl mx-auto">Empat langkah mudah untuk menemukan rekomendasi jurusan yang paling sesuai dengan profil dan impian Anda.</p>
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4 sm:gap-6">
                <div class="text-center">
                    <div class="bg-maroon text-white rounded-full w-16 h-16 sm:w-20 sm:h-20 mx-auto mb-3 sm:mb-4 flex items-center justify-center text-2xl sm:text-3xl font-bold">1</div>
                    <h3 class="text-base sm:text-lg md:text-xl font-bold text-maroon mb-2 sm:mb-3">Daftar Akun</h3>
                    <p class="text-xs sm:text-sm text-gray-700">Buat akun menggunakan email, NIS, dan data diri</p>
                </div>
                <div class="text-center">
                    <div class="bg-maroon text-white rounded-full w-16 h-16 sm:w-20 sm:h-20 mx-auto mb-3 sm:mb-4 flex items-center justify-center text-2xl sm:text-3xl font-bold">2</div>
                    <h3 class="text-base sm:text-lg md:text-xl font-bold text-maroon mb-2 sm:mb-3">Isi Data</h3>
                    <p class="text-xs sm:text-sm text-gray-700">Lengkapi 5 aspek penting pada profil Anda</p>
                </div>
                <div class="text-center">
                    <div class="bg-maroon text-white rounded-full w-16 h-16 sm:w-20 sm:h-20 mx-auto mb-3 sm:mb-4 flex items-center justify-center text-2xl sm:text-3xl font-bold">3</div>
                    <h3 class="text-base sm:text-lg md:text-xl font-bold text-maroon mb-2 sm:mb-3">Proses AI</h3>
                    <p class="text-xs sm:text-sm text-gray-700">Sistem memproses data Anda dengan algoritma yang terstruktur</p>
                </div>
                <div class="text-center">
                    <div class="bg-maroon text-white rounded-full w-16 h-16 sm:w-20 sm:h-20 mx-auto mb-3 sm:mb-4 flex items-center justify-center text-2xl sm:text-3xl font-bold">4</div>
                    <h3 class="text-base sm:text-lg md:text-xl font-bold text-maroon mb-2 sm:mb-3">Lihat Hasil</h3>
                    <p class="text-xs sm:text-sm text-gray-700">Dapatkan ranking jurusan dengan skor kecocokan detail</p>
                </div>
            </div>
        </div>
    </section>

    <!-- CALL TO ACTION -->
    <section class="py-8 sm:py-12 md:py-16 gradient-maroon text-white">
        <div class="container mx-auto px-4 sm:px-6 text-center">
            <h2 class="text-2xl sm:text-3xl md:text-4xl font-bold mb-4 sm:mb-6">Temukan Jurusan Polije yang Paling Cocok</h2>
            <p class="text-sm sm:text-base md:text-lg mb-6 sm:mb-8 text-yellow-100 max-w-2xl mx-auto">Gunakan sistem ini untuk memperoleh rekomendasi jurusan Politeknik Negeri Jember yang akurat berdasarkan nilai, minat, dan cita-cita Anda.</p>
            <div class="flex flex-col sm:flex-row gap-3 sm:gap-4 justify-center">
                <a href="{{ route('register') }}" class="inline-block bg-yellow-400 text-maroon font-bold py-2 sm:py-3 px-6 sm:px-10 rounded-lg hover:bg-yellow-300 transition text-sm sm:text-base text-center">Daftar Sekarang</a>
                <a href="{{ route('login') }}" class="inline-block border-2 border-yellow-400 text-yellow-300 font-bold py-2 sm:py-3 px-6 sm:px-10 rounded-lg hover:bg-yellow-400 hover:text-maroon transition text-sm sm:text-base text-center">Login</a>
            </div>
        </div>
    </section>

    <!-- FOOTER -->
    <footer class="gradient-maroon text-white py-8 sm:py-12">
        <div class="container mx-auto px-4 sm:px-6 grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6 sm:gap-8">
            <div>
                <h3 class="text-base sm:text-lg font-bold text-yellow-400 mb-3 sm:mb-4">Untuk Siswa SMA Bima Ambulu</h3>
                <p class="text-xs sm:text-sm text-yellow-100">Sistem ini dikembangkan khusus untuk membantu siswa SMA Bima Ambulu menemukan jurusan Politeknik Negeri Jember yang paling sesuai dengan minat dan kemampuan.</p>
            </div>
            <div>
                <h3 class="text-base sm:text-lg font-bold text-yellow-400 mb-3 sm:mb-4">Kontak SMA Bima Ambulu</h3>
                <ul class="text-xs sm:text-sm text-yellow-100 space-y-1 sm:space-y-2">
                    <li><strong>Email:</strong> <a href="mailto:smabima@gmail.com" class="hover:text-yellow-300">smabima@gmail.com</a></li>
                    <li><strong>Telepon:</strong> (0331) 123456</li>
                    <li><strong>Lokasi:</strong> <span class="block text-xs">Jl. Pendidikan No. 1<br/>Ambulu, Jember, Jawa Timur</span></li>
                </ul>
            </div>
            <div>
                <h3 class="text-base sm:text-lg font-bold text-yellow-400 mb-3 sm:mb-4">Menu</h3>
                <ul class="text-xs sm:text-sm text-yellow-100 space-y-1 sm:space-y-2">
                    <li><a href="{{ route('login') }}" class="hover:text-yellow-300">Login</a></li>
                    <li><a href="{{ route('register') }}" class="hover:text-yellow-300">Daftar Sekarang</a></li>
                    <li><a href="#how-it-works" class="hover:text-yellow-300">Cara Pakai</a></li>
                </ul>
            </div>
        </div>
        <div class="border-t border-yellow-300 mt-6 sm:mt-8 pt-4 sm:pt-6 text-center text-xs sm:text-sm text-yellow-100">
            <p>&copy; 2026 Sistem Pemilihan Jurusan</p>
            <p class="mt-1 sm:mt-2">Membantu Siswa SMA Bima Ambulu Memilih Jurusan Politeknik Negeri Jember yang Tepat</p>
        </div>
    </footer>

    <script>
        // Mobile menu toggle
        const menuToggle = document.getElementById('menuToggle');
        const mobileMenu = document.getElementById('mobileMenu');
        
        if (menuToggle) {
            menuToggle.addEventListener('click', function(e) {
                e.stopPropagation();
                mobileMenu.classList.toggle('active');
                menuToggle.textContent = mobileMenu.classList.contains('active') ? '✕' : '☰';
            });
        }

        // Tutup menu saat klik di luar
        document.addEventListener('click', function(e) {
            if (mobileMenu && !mobileMenu.contains(e.target) && e.target !== menuToggle) {
                mobileMenu.classList.remove('active');
                menuToggle.textContent = '☰';
            }
        });
    </script>
</body>
</html>
