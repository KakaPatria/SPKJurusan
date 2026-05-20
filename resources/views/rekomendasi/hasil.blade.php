<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Hasil Rekomendasi - Sistem Pemilihan Jurusan</title>
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
    <style>
        :root {
            --primary: #0f766e;
            --primary-light: #0d9488;
            --accent: #f59e0b;
            --success: #10b981;
            --error: #ef4444;
            --bg-soft: #f0f9ff;
            --bg-card: #ffffff;
            --text-main: #1f2937;
            --text-secondary: #6b7280;
            --border-light: #e5e7eb;
            --shadow-md: 0 4px 6px rgba(0, 0, 0, 0.07);
            --shadow-lg: 0 10px 15px rgba(0, 0, 0, 0.08);
        }

        body {
            background-color: var(--bg-soft);
            color: var(--text-main);
            font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, 'Helvetica Neue', Arial, sans-serif;
        }

        .header-main {
            background: linear-gradient(135deg, var(--primary) 0%, var(--primary-light) 100%);
            color: white;
            position: sticky;
            top: 0;
            z-index: 50;
            box-shadow: var(--shadow-md);
        }

        .btn-primary {
            background-color: var(--primary);
            color: white;
            transition: all 0.3s ease;
        }

        .btn-primary:hover {
            background-color: var(--primary-light);
            box-shadow: var(--shadow-lg);
        }

        .card {
            background: var(--bg-card);
            border-radius: 12px;
            box-shadow: var(--shadow-md);
            border: 1px solid var(--border-light);
        }

        .card-header {
            display: flex;
            align-items: center;
            gap: 1rem;
            margin-bottom: 1.5rem;
        }

        .badge-primary {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            min-width: 2.25rem;
            min-height: 2.25rem;
            background-color: var(--accent);
            color: white;
            border-radius: 9999px;
            font-weight: 600;
            font-size: 0.875rem;
        }

        .text-primary {
            color: var(--primary);
        }

        .profile-box {
            background: linear-gradient(135deg, rgba(15, 118, 110, 0.05) 0%, rgba(13, 148, 136, 0.05) 100%);
            border: 1px solid rgba(15, 118, 110, 0.2);
            border-radius: 12px;
            padding: 1rem;
        }

        .result-item {
            background: var(--bg-card);
            border: 1px solid var(--border-light);
            border-radius: 12px;
            padding: 1.25rem;
            transition: all 0.3s ease;
        }

        .result-item:hover {
            box-shadow: var(--shadow-lg);
            border-color: var(--primary);
        }

        .result-item.top-1 {
            border-left: 4px solid var(--accent);
            background: linear-gradient(to right, rgba(245, 158, 11, 0.05), white);
        }

        .progress-bar {
            background-color: #e5e7eb;
            border-radius: 9999px;
            height: 0.5rem;
            overflow: hidden;
        }

        .progress-fill {
            background: linear-gradient(90deg, var(--primary) 0%, var(--primary-light) 100%);
            height: 100%;
            border-radius: 9999px;
            transition: width 0.6s ease;
        }
    </style>
</head>
<body>
    <!-- Header -->
    <header class="header-main py-4 md:py-6">
        <div class="container mx-auto px-4 md:px-6">
            <div class="flex flex-col md:flex-row justify-between items-start md:items-center gap-4">
                <div>
                    <h1 class="text-2xl md:text-3xl font-bold">Hasil Analisis Rekomendasi</h1>
                    <p class="text-sm md:text-base text-teal-100 mt-1">Jurusan terbaik berdasarkan profil Anda</p>
                </div>
                <div class="flex gap-3">
                    <a href="{{ route('rekomendasi.index') }}" class="btn-primary px-4 py-2 rounded-lg font-medium text-sm hover:shadow-lg transition">
                        🔄 Analisis Ulang
                    </a>
                    <a href="{{ url('/dashboard') }}" class="px-4 py-2 rounded-lg font-medium text-sm bg-white text-primary hover:bg-gray-50 transition">
                        ← Dashboard
                    </a>
                </div>
            </div>
        </div>
    </header>

    <!-- Main Content -->
    <main class="container mx-auto px-4 md:px-6 py-8 md:py-12">
        <!-- Data Profil Summary -->
        <div class="card p-6 md:p-8 mb-8">
            <h2 class="text-2xl font-bold text-primary mb-6">Data Profil Anda</h2>
            
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
                <!-- Nilai Akademik -->
                <div class="profile-box">
                    <p style="font-size: 0.75rem; color: var(--text-secondary); text-transform: uppercase; font-weight: 600; margin-bottom: 0.5rem;">Nilai Akademik</p>
                    <p style="font-size: 1.5rem; font-weight: 700; color: var(--primary); margin-bottom: 0.25rem;">{{ $katNilai }}</p>
                    <p style="font-size: 0.875rem; color: var(--text-secondary);">Rata-rata: {{ number_format($average, 1) }}%</p>
                </div>

                <!-- Minat -->
                <div class="profile-box">
                    <p style="font-size: 0.75rem; color: var(--text-secondary); text-transform: uppercase; font-weight: 600; margin-bottom: 0.5rem;">Bidang Minat</p>
                    <p style="font-size: 1.125rem; font-weight: 700; color: var(--primary);">{{ $minatMapped ?? '-' }}</p>
                    <p style="font-size: 0.75rem; color: var(--text-secondary); margin-top: 0.375rem;">Anda inputkan: <strong>{{ ucfirst($minatRaw ?? '-') }}</strong></p>
                </div>

                <!-- Cita-cita -->
                <div class="profile-box">
                    <p style="font-size: 0.75rem; color: var(--text-secondary); text-transform: uppercase; font-weight: 600; margin-bottom: 0.5rem;">Cita-cita</p>
                    <p style="font-size: 1.125rem; font-weight: 700; color: var(--primary);">{{ $citaMapped ?? '-' }}</p>
                    <p style="font-size: 0.75rem; color: var(--text-secondary); margin-top: 0.375rem;">Anda inputkan: <strong>{{ ucfirst($citaRaw ?? '-') }}</strong></p>
                </div>

                <!-- Preferensi Studi -->
                <div class="profile-box">
                    <p style="font-size: 0.75rem; color: var(--text-secondary); text-transform: uppercase; font-weight: 600; margin-bottom: 0.5rem;">Preferensi Studi</p>
                    <p style="font-size: 1.125rem; font-weight: 700; color: var(--primary);">{{ $prefStudi ?? '-' }}</p>
                </div>

                <!-- Prestasi -->
                <div class="profile-box">
                    <p style="font-size: 0.75rem; color: var(--text-secondary); text-transform: uppercase; font-weight: 600; margin-bottom: 0.5rem;">Prestasi</p>
                    <p style="font-size: 1.125rem; font-weight: 700; color: var(--primary);">
                        @if(!($isPrestasiFilled ?? true))
                            Tidak Ada
                        @elseif($prestasiScore >= 0.8)
                            Tinggi ⭐
                        @elseif($prestasiScore >= 0.6)
                            Sedang 👍
                        @elseif($prestasiScore > 0)
                            Cukup
                        @else
                            Belum Ada
                        @endif
                    </p>
                </div>
            </div>
        </div>

        <!-- Top Recommendation -->
        @if(count($hasilAkhir) > 0)
        <div class="mb-8">
            <div class="result-item top-1 border-4">
                <div class="flex items-start justify-between mb-4">
                    <div>
                        <span style="display: inline-block; background: var(--accent); color: white; padding: 0.25rem 0.75rem; border-radius: 9999px; font-size: 0.75rem; font-weight: 600; margin-bottom: 0.75rem;">
                            🏆 REKOMENDASI UTAMA
                        </span>
                        <h3 style="font-size: 2rem; font-weight: 700; color: var(--primary);">{{ $hasilAkhir[0]['jurusan'] ?? '-' }}</h3>
                    </div>
                    <div style="text-align: right;">
                        <p style="font-size: 2.5rem; font-weight: 700; color: var(--accent);">{{ number_format(($hasilAkhir[0]['skor'] ?? 0) * 100, 1) }}%</p>
                        <p style="font-size: 0.875rem; color: var(--text-secondary);">Skor Kesesuaian</p>
                    </div>
                </div>

                <div class="progress-bar mb-4">
                    <div class="progress-fill" style="width: {{ number_format(($hasilAkhir[0]['skor'] ?? 0) * 100, 1) }}%"></div>
                </div>

                <p style="font-size: 0.875rem; color: var(--text-secondary); margin-bottom: 1rem;">
                    Berdasarkan analisis Weighted Naive Bayes, jurusan ini adalah pilihan terbaik untuk profil akademik dan minat Anda.
                </p>
            </div>
        </div>
        @endif

        <!-- All Rankings -->
        <div class="card p-6 md:p-8">
            <h2 class="text-2xl font-bold text-primary mb-6">Peringkat Semua Jurusan</h2>

            <div class="space-y-4">
                @foreach($hasilAkhir as $index => $res)
                    <div class="result-item {{ $index == 0 ? 'top-1' : '' }}">
                        <div class="flex items-start justify-between mb-3">
                            <div class="flex items-center gap-4">
                                <div class="badge-primary">{{ $index + 1 }}</div>
                                <div>
                                    <p style="font-weight: 600; color: var(--text-main); margin-bottom: 0.125rem;">{{ $res['jurusan'] ?? '-' }}</p>
                                    <p style="font-size: 0.875rem; color: var(--text-secondary);">
                                        @if($index == 0)
                                            Pilihan terbaik untuk Anda
                                        @elseif($index < 3)
                                            Rekomendasi alternatif yang baik
                                        @else
                                            Pilihan lainnya
                                        @endif
                                    </p>
                                </div>
                            </div>
                            <div style="text-align: right;">
                                <p style="font-size: 1.5rem; font-weight: 700; color: var(--primary);">
                                    {{ number_format(($res['skor'] ?? 0) * 100, 1) }}%
                                </p>
                            </div>
                        </div>

                        <div class="progress-bar">
                            <div class="progress-fill" style="width: {{ number_format(($res['skor'] ?? 0) * 100, 1) }}%"></div>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>

        <!-- Method Explanation -->
        <div class="card p-6 md:p-8 mt-8 bg-gradient-to-r from-cyan-50 to-blue-50">
            <h3 class="text-lg font-bold text-primary mb-4 flex items-center gap-2">
                <span>🔬</span> Metode Analisis
            </h3>
            <p style="font-size: 0.875rem; color: var(--text-secondary); line-height: 1.6;">
                Sistem menggunakan <strong>Weighted Naive Bayes</strong> dengan 5 kriteria yang dibobotkan berdasarkan data historis siswa:
                <br><strong>Minat (45.6%)</strong> • Preferensi Studi (25.6%) • Nilai Akademik (15.6%) • Cita-cita (9%) • Prestasi (4%)
                <br>Jika prestasi tidak diisi, bobot distribusi ulang ke 4 kriteria lainnya untuk hasil yang akurat.
            </p>
        </div>

        <!-- Action Buttons -->
        <div class="flex flex-col sm:flex-row gap-4 mt-8">
            <a href="{{ route('rekomendasi.index') }}" class="btn-primary px-6 py-3 rounded-lg font-semibold text-center hover:shadow-lg transition">
                🔄 Analisis Ulang
            </a>
            <a href="{{ route('chatbot.index', ['new' => 1]) }}" class="px-6 py-3 rounded-lg font-semibold text-center bg-blue-500 text-white hover:bg-blue-600 transition hover:shadow-lg">
                💬 Tanya Chatbot
            </a>
            <a href="{{ url('/dashboard') }}" class="px-6 py-3 rounded-lg font-semibold text-center bg-white text-primary border-2" style="border-color: var(--primary); transition: all 0.3s ease;" onmouseover="this.style.backgroundColor='var(--bg-soft)'" onmouseout="this.style.backgroundColor='white'">
                ← Ke Dashboard
            </a>
        </div>
    </main>

    <script>
        // Smooth animations for progress bars
        window.addEventListener('load', () => {
            document.querySelectorAll('.progress-fill').forEach(el => {
                el.style.width = '0%';
                setTimeout(() => {
                    el.style.width = el.parentElement.style.width;
                }, 100);
            });
        });
    </script>
</body>
</html>