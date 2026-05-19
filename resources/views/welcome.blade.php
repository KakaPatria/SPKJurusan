<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>SPK Jurusan Polije - Sistem Pendukung Keputusan Pemilihan Jurusan</title>
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
    <style>
        .gradient-maroon {
            background: linear-gradient(135deg, #9333ea 0%, #6366f1 100%);
        }
        .text-maroon {
            color: #9333ea;
        }
        .border-maroon {
            border-color: #9333ea;
        }
        .bg-cream {
            background-color: #F8FAFC;
        }
        .gradient-button {
            background: linear-gradient(135deg, #9333ea 0%, #6366f1 100%);
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
                background: linear-gradient(135deg, #9333ea 0%, #6366f1 100%);
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
        <div class="w-full px-4 sm:px-6 py-4 flex justify-between items-center relative">
            <div>
                <h1 class="text-xl sm:text-2xl md:text-3xl font-bold">SPK Jurusan Polije</h1>
                <p class="text-xs sm:text-sm text-purple-100 font-semibold mt-1">Temukan Jurusan Terbaik Untukmu</p>
            </div>
            <button class="mobile-menu-toggle text-white text-2xl" id="menuToggle">☰</button>
            <div class="mobile-menu md:flex md:space-x-4 md:items-center" id="mobileMenu">
                @if (Route::has('login'))
                    <a href="{{ route('login') }}" class="bg-white text-purple-700 font-bold py-2 px-6 rounded-lg hover:bg-purple-50 transition text-sm sm:text-base text-center block md:inline-block shadow-md">Login</a>
                    <a href="{{ route('register') }}" class="border-2 border-white text-white font-bold py-2 px-6 rounded-lg hover:bg-white hover:text-purple-700 transition text-sm sm:text-base text-center block md:inline-block">Daftar</a>
                @endif
            </div>
        </div>
    </header>

    <section class="py-8 sm:py-12 md:py-16 gradient-maroon text-white">
        <div class="w-full px-4 sm:px-6">
            <div class="grid grid-cols-1 md:grid-cols-2 gap-8 md:gap-12 items-center">
                <div>
                    <h2 class="text-3xl sm:text-4xl md:text-5xl font-bold mb-4 sm:mb-6">Tentukan Pilihan Jurusan dengan Bijak</h2>
                    <p class="text-sm sm:text-base md:text-lg mb-3 sm:mb-4 text-purple-100 leading-relaxed">
                        Selamat datang di Sistem Pemilihan Jurusan Politeknik Negeri Jember! Memilih jurusan adalah keputusan penting yang menentukan masa depan karirmu. Kami membantu kamu menemukan jurusan yang paling sesuai dengan kemampuan akademis, minat, dan impian karir.
                    </p>
                    <p class="text-sm sm:text-base md:text-lg mb-6 sm:mb-8 text-purple-100 leading-relaxed">
                        Sistem kami menganalisis 5 aspek utama dari profilmu untuk memberikan rekomendasi jurusan yang akurat, terpercaya, dan personal hanya untuk kamu.
                    </p>
                    <div class="flex flex-col sm:flex-row gap-3 sm:gap-4">
                        <a href="{{ route('login') }}" class="inline-block bg-white text-purple-700 font-bold py-3 sm:py-4 px-8 sm:px-10 rounded-lg hover:shadow-lg hover:bg-purple-50 transition text-sm sm:text-base text-center shadow-md">Mulai Analisis</a>
                    </div>
                </div>
                <div class="flex justify-center">
                    <div class="bg-white rounded-xl p-8 sm:p-10 shadow-2xl w-full max-w-sm text-center border-t-4 border-purple-600">
                        <div class="text-6xl sm:text-7xl mb-4">🚀</div>
                        <h3 class="text-2xl sm:text-3xl font-bold mb-3 sm:mb-4 text-purple-700">9 Pilihan Jurusan</h3>
                        <p class="text-sm text-gray-600 mb-6 leading-relaxed">Dari berbagai bidang: Pertanian, Teknologi, Kesehatan, Bisnis, dan Humaniora. Setiap dengan peluang karir yang menjanjikan.</p>
                        <div class="bg-purple-100 rounded-lg p-4 border-l-4 border-purple-600">
                            <p class="text-sm font-semibold text-purple-900">💡 Dapatkan ranking jurusan yang unik sesuai profilmu</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- MENGAPA SISTEM INI PENTING -->
    <section class="py-8 sm:py-12 md:py-16 bg-white">
        <div class="w-full px-4 sm:px-6">
            <h2 class="text-2xl sm:text-3xl md:text-4xl font-bold text-center text-purple-700 mb-3 sm:mb-4">Mengapa Menggunakan Sistem Ini?</h2>
            <p class="text-center text-gray-600 mb-8 sm:mb-12 max-w-2xl mx-auto">Sistem kami dirancang khusus untuk membantu siswa membuat keputusan yang tepat dan percaya diri dalam memilih jurusan.</p>
            <div class="grid grid-cols-1 md:grid-cols-3 gap-6 sm:gap-8">
                <div class="p-8 rounded-xl bg-gradient-to-br from-blue-50 to-blue-100 border-2 border-blue-300 shadow-md hover:shadow-lg transition">
                    <div class="text-5xl mb-4">🤖</div>
                    <h3 class="text-lg font-bold text-blue-900 mb-3">AI yang Terpercaya</h3>
                    <p class="text-sm text-blue-800 leading-relaxed">Menggunakan Naive Bayes algorithm untuk analisis data yang akurat dan terukur</p>
                </div>
                <div class="p-8 rounded-xl bg-gradient-to-br from-green-50 to-green-100 border-2 border-green-300 shadow-md hover:shadow-lg transition">
                    <div class="text-5xl mb-4">🎯</div>
                    <h3 class="text-lg font-bold text-green-900 mb-3">Pendekatan Holistik</h3>
                    <p class="text-sm text-green-800 leading-relaxed">Menganalisis 5 aspek: nilai akademik, minat, preferensi, cita-cita, dan prestasi</p>
                </div>
                <div class="p-8 rounded-xl bg-gradient-to-br from-purple-50 to-purple-100 border-2 border-purple-300 shadow-md hover:shadow-lg transition">
                    <div class="text-5xl mb-4">✨</div>
                    <h3 class="text-lg font-bold text-purple-900 mb-3">Hasil Akurat</h3>
                    <p class="text-sm text-purple-800 leading-relaxed">Dapatkan ranking jurusan dengan skor kecocokan yang detail dan penjelasan mendalam</p>
                </div>
            </div>
        </div>
    </section>


    <!-- 5 FAKTOR KEPUTUSAN -->
    <section class="py-8 sm:py-12 md:py-16 bg-gray-50">
        <div class="w-full px-4 sm:px-6">
            <h2 class="text-2xl sm:text-3xl md:text-4xl font-bold text-center text-purple-700 mb-3 sm:mb-4">5 Aspek yang Dianalisis Sistem</h2>
            <p class="text-center text-gray-700 mb-8 sm:mb-12 max-w-3xl mx-auto">Sistem kami menganalisis 5 aspek penting dari profilmu untuk memberikan rekomendasi yang akurat dan personal.</p>
            <div class="max-w-4xl mx-auto space-y-4 sm:space-y-6">
                <div class="bg-white p-6 sm:p-8 rounded-lg border-l-4 border-purple-600 shadow-md hover:shadow-lg transition">
                    <div class="flex flex-col sm:flex-row items-start gap-4 sm:gap-6">
                        <div class="text-3xl font-bold text-white bg-purple-600 rounded-full w-14 h-14 sm:w-16 sm:h-16 flex items-center justify-center flex-shrink-0">1</div>
                        <div>
                            <h3 class="text-xl sm:text-2xl font-bold text-purple-700 mb-2">📚 Nilai Akademik (15.6%)</h3>
                            <p class="text-sm text-gray-700 leading-relaxed">Nilai Anda pada mata pelajaran utama (IPA atau IPS) menunjukkan kemampuan dasar dan potensi untuk sukses di jurusan tertentu</p>
                        </div>
                    </div>
                </div>
                <div class="bg-white p-6 sm:p-8 rounded-lg border-l-4 border-blue-600 shadow-md hover:shadow-lg transition">
                    <div class="flex flex-col sm:flex-row items-start gap-4 sm:gap-6">
                        <div class="text-3xl font-bold text-white bg-blue-600 rounded-full w-14 h-14 sm:w-16 sm:h-16 flex items-center justify-center flex-shrink-0">2</div>
                        <div>
                            <h3 class="text-xl sm:text-2xl font-bold text-blue-700 mb-2">💡 Minat & Passion (45.6%)</h3>
                            <p class="text-sm text-gray-700 leading-relaxed">Minat Anda terhadap bidang atau kegiatan tertentu sangat menentukan motivasi dan kesuksesan Anda dalam perkuliahan</p>
                        </div>
                    </div>
                </div>
                <div class="bg-white p-6 sm:p-8 rounded-lg border-l-4 border-green-600 shadow-md hover:shadow-lg transition">
                    <div class="flex flex-col sm:flex-row items-start gap-4 sm:gap-6">
                        <div class="text-3xl font-bold text-white bg-green-600 rounded-full w-14 h-14 sm:w-16 sm:h-16 flex items-center justify-center flex-shrink-0">3</div>
                        <div>
                            <h3 class="text-xl sm:text-2xl font-bold text-green-700 mb-2">🎯 Preferensi Studi Lanjutan (25.6%)</h3>
                            <p class="text-sm text-gray-700 leading-relaxed">Pilihan rumpun bidang studi yang sesuai dengan kecenderungan akademis dan prospek karir jangka panjang Anda</p>
                        </div>
                    </div>
                </div>
                <div class="bg-white p-6 sm:p-8 rounded-lg border-l-4 border-amber-600 shadow-md hover:shadow-lg transition">
                    <div class="flex flex-col sm:flex-row items-start gap-4 sm:gap-6">
                        <div class="text-3xl font-bold text-white bg-amber-600 rounded-full w-14 h-14 sm:w-16 sm:h-16 flex items-center justify-center flex-shrink-0">4</div>
                        <div>
                            <h3 class="text-xl sm:text-2xl font-bold text-amber-700 mb-2">🚀 Cita-Cita & Tujuan Karir (9%)</h3>
                            <p class="text-sm text-gray-700 leading-relaxed">Target karir dan impian profesional Anda membantu kami mencocokkan jurusan dengan jalur karir yang Anda inginkan</p>
                        </div>
                    </div>
                </div>
                <div class="bg-white p-6 sm:p-8 rounded-lg border-l-4 border-red-600 shadow-md hover:shadow-lg transition">
                    <div class="flex flex-col sm:flex-row items-start gap-4 sm:gap-6">
                        <div class="text-3xl font-bold text-white bg-red-600 rounded-full w-14 h-14 sm:w-16 sm:h-16 flex items-center justify-center flex-shrink-0">5</div>
                        <div>
                            <h3 class="text-xl sm:text-2xl font-bold text-red-700 mb-2">🏆 Prestasi & Kegiatan (4%)</h3>
                            <p class="text-sm text-gray-700 leading-relaxed">Pencapaian dalam kompetisi akademik maupun kegiatan non-akademik menunjukkan potensi, dedikasi, dan kepemimpinan</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- UNGGULAN FITUR -->
    <section class="py-8 sm:py-12 md:py-16 bg-white">
        <div class="w-full px-4 sm:px-6">
            <h2 class="text-2xl sm:text-3xl md:text-4xl font-bold text-center text-purple-700 mb-3 sm:mb-4">Fitur Unggulan Sistem</h2>
            <p class="text-center text-gray-600 mb-8 sm:mb-12 max-w-3xl mx-auto">Dilengkapi dengan berbagai fitur canggih untuk memberikan pengalaman terbaik dalam menemukan jurusan impianmu.</p>
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6 sm:gap-8">
                <div class="bg-gradient-to-br from-purple-50 to-purple-100 p-8 rounded-xl border-2 border-purple-300 shadow-md hover:shadow-lg transition">
                    <div class="text-5xl mb-4">🤖</div>
                    <h3 class="text-lg font-bold text-purple-900 mb-3">AI Berbasis Data</h3>
                    <p class="text-sm text-purple-800">Algoritma Naive Bayes terpercaya untuk analisis akurat dan rekomendasi yang dapat dipertanggungjawabkan</p>
                </div>
                <div class="bg-gradient-to-br from-blue-50 to-blue-100 p-8 rounded-xl border-2 border-blue-300 shadow-md hover:shadow-lg transition">
                    <div class="text-5xl mb-4">📊</div>
                    <h3 class="text-lg font-bold text-blue-900 mb-3">Analisis Komprehensif</h3>
                    <p class="text-sm text-blue-800">5 aspek penting: akademik, minat, preferensi, cita-cita, dan prestasi untuk gambaran lengkap</p>
                </div>
                <div class="bg-gradient-to-br from-green-50 to-green-100 p-8 rounded-xl border-2 border-green-300 shadow-md hover:shadow-lg transition">
                    <div class="text-5xl mb-4">✨</div>
                    <h3 class="text-lg font-bold text-green-900 mb-3">Rekomendasi Personal</h3>
                    <p class="text-sm text-green-800">Ranking jurusan unik khusus untukmu berdasarkan profil individual dan data real-time</p>
                </div>
                <div class="bg-gradient-to-br from-amber-50 to-amber-100 p-8 rounded-xl border-2 border-amber-300 shadow-md hover:shadow-lg transition">
                    <div class="text-5xl mb-4">📝</div>
                    <h3 class="text-lg font-bold text-amber-900 mb-3">Riwayat Lengkap</h3>
                    <p class="text-sm text-amber-800">Lacak semua analisis sebelumnya dan lihat perkembangan scoring dari waktu ke waktu</p>
                </div>
                <div class="bg-gradient-to-br from-red-50 to-red-100 p-8 rounded-xl border-2 border-red-300 shadow-md hover:shadow-lg transition">
                    <div class="text-5xl mb-4">💬</div>
                    <h3 class="text-lg font-bold text-red-900 mb-3">Chatbot Konsultasi</h3>
                    <p class="text-sm text-red-800">Konsultasi dengan AI chatbot 24/7 untuk pertanyaan seputar jurusan dan karir</p>
                </div>
                <div class="bg-gradient-to-br from-indigo-50 to-indigo-100 p-8 rounded-xl border-2 border-indigo-300 shadow-md hover:shadow-lg transition">
                    <div class="text-5xl mb-4">👨‍🏫</div>
                    <h3 class="text-lg font-bold text-indigo-900 mb-3">Monitoring Guru BK</h3>
                    <p class="text-sm text-indigo-800">Guru BK dapat memantau progress siswa dan memberikan bimbingan tambahan real-time</p>
                </div>
            </div>
        </div>
    </section>

    <!-- 9 JURUSAN TERSEDIA -->
    <section class="py-8 sm:py-12 md:py-16 bg-gray-50">
        <div class="w-full px-4 sm:px-6">
            <h2 class="text-2xl sm:text-3xl md:text-4xl font-bold text-center text-purple-700 mb-3 sm:mb-4">9 Pilihan Jurusan Polije</h2>
            <p class="text-center text-gray-600 mb-8 sm:mb-12 max-w-3xl mx-auto">Tersedia berbagai pilihan jurusan dari bidang pertanian, teknologi, kesehatan, bisnis, hingga humaniora. Temukan jurusan yang paling cocok untukmu.</p>
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-4 sm:gap-6">
                <div class="bg-white border-t-4 border-purple-600 rounded-lg shadow-md hover:shadow-lg hover:-translate-y-1 transition p-6">
                    <div class="text-4xl mb-3">🌾</div>
                    <h3 class="text-lg font-bold text-purple-700 mb-2">Produksi Pertanian</h3>
                    <p class="text-sm text-gray-700">Cocok untuk yang suka bercocok tanam dan ingin meningkatkan hasil dengan metode modern.</p>
                </div>
                <div class="bg-white border-t-4 border-blue-600 rounded-lg shadow-md hover:shadow-lg hover:-translate-y-1 transition p-6">
                    <div class="text-4xl mb-3">🔬</div>
                    <h3 class="text-lg font-bold text-blue-700 mb-2">Teknologi Pertanian</h3>
                    <p class="text-sm text-gray-700">Belajar teknologi terkini untuk inovasi pertanian yang berkelanjutan dan efisien.</p>
                </div>
                <div class="bg-white border-t-4 border-green-600 rounded-lg shadow-md hover:shadow-lg hover:-translate-y-1 transition p-6">
                    <div class="text-4xl mb-3">🐄</div>
                    <h3 class="text-lg font-bold text-green-700 mb-2">Peternakan</h3>
                    <p class="text-sm text-gray-700">Mengelola dan mengembangkan usaha peternakan dengan standar industri profesional.</p>
                </div>
                <div class="bg-white border-t-4 border-amber-600 rounded-lg shadow-md hover:shadow-lg hover:-translate-y-1 transition p-6">
                    <div class="text-4xl mb-3">💼</div>
                    <h3 class="text-lg font-bold text-amber-700 mb-2">Manajemen Agribisnis</h3>
                    <p class="text-sm text-gray-700">Ideal untuk entrepreneur dan manajer bisnis yang tertarik di bidang pertanian modern.</p>
                </div>
                <div class="bg-white border-t-4 border-red-600 rounded-lg shadow-md hover:shadow-lg hover:-translate-y-1 transition p-6">
                    <div class="text-4xl mb-3">💻</div>
                    <h3 class="text-lg font-bold text-red-700 mb-2">Teknologi Informasi</h3>
                    <p class="text-sm text-gray-700">Buat yang suka coding, bikin aplikasi, dan ingin terjun ke dunia teknologi digital.</p>
                </div>
                <div class="bg-white border-t-4 border-orange-600 rounded-lg shadow-md hover:shadow-lg hover:-translate-y-1 transition p-6">
                    <div class="text-4xl mb-3">⚙️</div>
                    <h3 class="text-lg font-bold text-orange-700 mb-2">Teknik</h3>
                    <p class="text-sm text-gray-700">Cocok untuk yang suka mesin, desain teknik, dan sistem manufaktur modern.</p>
                </div>
                <div class="bg-white border-t-4 border-pink-600 rounded-lg shadow-md hover:shadow-lg hover:-translate-y-1 transition p-6">
                    <div class="text-4xl mb-3">⚕️</div>
                    <h3 class="text-lg font-bold text-pink-700 mb-2">Kesehatan</h3>
                    <p class="text-sm text-gray-700">Untuk yang ingin berkarir di bidang kesehatan dengan keterampilan praktis tinggi.</p>
                </div>
                <div class="bg-white border-t-4 border-cyan-600 rounded-lg shadow-md hover:shadow-lg hover:-translate-y-1 transition p-6">
                    <div class="text-4xl mb-3">🗣️</div>
                    <h3 class="text-lg font-bold text-cyan-700 mb-2">Bahasa & Pariwisata</h3>
                    <p class="text-sm text-gray-700">Buat yang suka berkomunikasi, hospitality, dan dunia pariwisata internasional.</p>
                </div>
                <div class="bg-white border-t-4 border-indigo-600 rounded-lg shadow-md hover:shadow-lg hover:-translate-y-1 transition p-6">
                    <div class="text-4xl mb-3">📊</div>
                    <h3 class="text-lg font-bold text-indigo-700 mb-2">Bisnis</h3>
                    <p class="text-sm text-gray-700">Jalan menuju karir sebagai pemimpin bisnis dan profesional manajemen terkemuka.</p>
                </div>
            </div>
        </div>
    </section>

    <!-- HOW IT WORKS -->
    <section id="how-it-works" class="py-8 sm:py-12 md:py-16 bg-white">
        <div class="w-full px-4 sm:px-6">
            <h2 class="text-2xl sm:text-3xl md:text-4xl font-bold text-center text-purple-700 mb-3 sm:mb-4">Empat Langkah Mudah</h2>
            <p class="text-center text-gray-600 mb-8 sm:mb-12 max-w-3xl mx-auto">Proses yang sederhana namun powerful untuk menemukan rekomendasi jurusan yang paling sesuai dengan profil dan impian Anda.</p>
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6 sm:gap-8">
                <div class="text-center">
                    <div class="bg-gradient-to-br from-purple-600 to-indigo-600 text-white rounded-full w-16 h-16 sm:w-20 sm:h-20 mx-auto mb-4 sm:mb-6 flex items-center justify-center text-2xl sm:text-3xl font-bold shadow-lg">1</div>
                    <h3 class="text-lg font-bold text-purple-700 mb-3">Buat Akun</h3>
                    <p class="text-sm text-gray-600 leading-relaxed">Daftar dengan email, NIS, dan data diri lengkap Anda</p>
                </div>
                <div class="text-center">
                    <div class="bg-gradient-to-br from-blue-600 to-purple-600 text-white rounded-full w-16 h-16 sm:w-20 sm:h-20 mx-auto mb-4 sm:mb-6 flex items-center justify-center text-2xl sm:text-3xl font-bold shadow-lg">2</div>
                    <h3 class="text-lg font-bold text-blue-700 mb-3">Isi Kuis</h3>
                    <p class="text-sm text-gray-600 leading-relaxed">Jawab 5 aspek penting tentang akademik, minat, dan aspirasi</p>
                </div>
                <div class="text-center">
                    <div class="bg-gradient-to-br from-green-600 to-emerald-600 text-white rounded-full w-16 h-16 sm:w-20 sm:h-20 mx-auto mb-4 sm:mb-6 flex items-center justify-center text-2xl sm:text-3xl font-bold shadow-lg">3</div>
                    <h3 class="text-lg font-bold text-green-700 mb-3">Analisis AI</h3>
                    <p class="text-sm text-gray-600 leading-relaxed">Sistem memproses data dengan algoritma Naive Bayes canggih</p>
                </div>
                <div class="text-center">
                    <div class="bg-gradient-to-br from-amber-600 to-orange-600 text-white rounded-full w-16 h-16 sm:w-20 sm:h-20 mx-auto mb-4 sm:mb-6 flex items-center justify-center text-2xl sm:text-3xl font-bold shadow-lg">4</div>
                    <h3 class="text-lg font-bold text-amber-700 mb-3">Lihat Hasil</h3>
                    <p class="text-sm text-gray-600 leading-relaxed">Dapatkan ranking 9 jurusan dengan skor dan analisis detail</p>
                </div>
            </div>
        </div>
    </section>

    <!-- CALL TO ACTION -->
    <section class="py-8 sm:py-12 md:py-16 gradient-maroon text-white">
        <div class="w-full px-4 sm:px-6 text-center">
            <h2 class="text-2xl sm:text-3xl md:text-4xl font-bold mb-4 sm:mb-6">Mulai Temukan Jurusan Impianmu</h2>
            <p class="text-sm sm:text-base md:text-lg mb-8 sm:mb-10 text-purple-100 max-w-2xl mx-auto leading-relaxed">Jangan biarkan pilihan jurusan menjadi keputusan buru-buru. Gunakan sistem kami yang telah terbukti akurat untuk menemukan jurusan Politeknik Negeri Jember yang paling sesuai dengan potensi dan impianmu.</p>
            <div class="flex flex-col sm:flex-row gap-4 sm:gap-6 justify-center">
                <a href="{{ route('register') }}" class="inline-block bg-white text-purple-700 font-bold py-3 sm:py-4 px-8 sm:px-12 rounded-lg hover:shadow-lg hover:bg-purple-50 transition text-base shadow-lg">Daftar Sekarang</a>
                <a href="{{ route('login') }}" class="inline-block border-2 border-white text-white font-bold py-3 sm:py-4 px-8 sm:px-12 rounded-lg hover:bg-white hover:text-purple-700 transition text-base">Login</a>
            </div>
        </div>
    </section>

    <!-- FOOTER -->
    <footer class="gradient-maroon text-white py-8 sm:py-12">
        <div class="w-full px-4 sm:px-6">
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6 sm:gap-8 max-w-5xl mx-auto mb-8">
                <div>
                    <h3 class="text-lg font-bold text-white mb-3 sm:mb-4">Tentang Sistem</h3>
                    <p class="text-sm text-purple-100 leading-relaxed">SPK Jurusan adalah sistem pendukung keputusan berbasis AI yang membantu siswa menemukan jurusan yang paling sesuai dengan profil akademis, minat, dan aspirasi karir mereka.</p>
                </div>
                <div>
                    <h3 class="text-lg font-bold text-white mb-3 sm:mb-4">Kontak</h3>
                    <ul class="text-sm text-purple-100 space-y-2">
                        <li><strong>Email:</strong> <a href="mailto:info@spkjurusan.com" class="hover:text-white">info@spkjurusan.com</a></li>
                        <li><strong>Telepon:</strong> (0331) 123456</li>
                        <li><strong>Lokasi:</strong> <span class="block text-xs">Politeknik Negeri Jember<br/>Jawa Timur, Indonesia</span></li>
                    </ul>
                </div>
                <div>
                    <h3 class="text-lg font-bold text-white mb-3 sm:mb-4">Menu</h3>
                    <ul class="text-sm text-purple-100 space-y-2">
                        <li><a href="{{ route('login') }}" class="hover:text-white transition">Login</a></li>
                        <li><a href="{{ route('register') }}" class="hover:text-white transition">Daftar</a></li>
                        <li><a href="#how-it-works" class="hover:text-white transition">Cara Pakai</a></li>
                    </ul>
                </div>
            </div>
            <div class="border-t border-purple-400 pt-6 text-center text-sm text-purple-100">
                <p>&copy; 2026 SPK Jurusan Polije - Semua Hak Dilindungi</p>
                <p class="mt-2 text-xs text-purple-200">Membantu Siswa Menemukan Jurusan yang Tepat untuk Masa Depan Cemerlang</p>
            </div>
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
