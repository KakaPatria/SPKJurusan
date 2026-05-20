<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Input Data - Sistem Pemilihan Jurusan</title>
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
    <style>
        * {
            box-sizing: border-box;
        }

        :root {
            --primary: #0f766e;
            --primary-light: #0d9488;
            --secondary: #d97706;
            --accent: #f59e0b;
            --success: #10b981;
            --error: #ef4444;
            --bg-soft: #f0f9ff;
            --bg-card: #ffffff;
            --text-main: #1f2937;
            --text-secondary: #6b7280;
            --border-light: #e5e7eb;
            --shadow-sm: 0 1px 2px rgba(0, 0, 0, 0.05);
            --shadow-md: 0 4px 6px rgba(0, 0, 0, 0.07);
            --shadow-lg: 0 10px 15px rgba(0, 0, 0, 0.08);
        }

        body {
            background-color: var(--bg-soft);
            color: var(--text-main);
            font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, 'Helvetica Neue', Arial, sans-serif;
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

        .btn-primary:active {
            transform: scale(0.98);
        }

        .text-primary {
            color: var(--primary);
        }

        .border-primary {
            border-color: var(--primary);
        }

        .focus-primary:focus {
            border-color: var(--primary);
            outline: none;
            box-shadow: 0 0 0 3px rgba(15, 118, 110, 0.1);
        }

        .input-error {
            border-color: var(--error) !important;
            background-color: #fff5f5 !important;
        }

        .input-valid {
            border-color: var(--success) !important;
            background-color: #f0fdf4 !important;
        }

        .validation-message {
            font-size: 0.75rem;
            margin-top: 0.375rem;
            display: flex;
            align-items: center;
            animation: slideIn 0.3s ease-out;
        }

        @keyframes slideIn {
            from {
                opacity: 0;
                transform: translateY(-4px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        .card {
            background: var(--bg-card);
            border-radius: 12px;
            box-shadow: var(--shadow-md);
            border: 1px solid var(--border-light);
        }

        .card-header {
            display: flex;
            align-items: flex-start;
            gap: 1rem;
            margin-bottom: 1.5rem;
        }

        .card-icon {
            width: 3rem;
            height: 3rem;
            border-radius: 8px;
            background: linear-gradient(135deg, rgba(15, 118, 110, 0.1) 0%, rgba(13, 148, 136, 0.1) 100%);
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 1.5rem;
            flex-shrink: 0;
        }

        .header-main {
            background: linear-gradient(135deg, var(--primary) 0%, var(--primary-light) 100%);
            color: white;
            position: sticky;
            top: 0;
            z-index: 50;
            box-shadow: var(--shadow-md);
        }

        .section-title {
            font-size: 1.125rem;
            font-weight: 600;
            color: var(--primary);
            margin-bottom: 0.5rem;
            display: flex;
            align-items: center;
            gap: 0.5rem;
        }

        .section-desc {
            font-size: 0.875rem;
            color: var(--text-secondary);
            margin-bottom: 1rem;
        }

        .input-field {
            display: flex;
            flex-direction: column;
        }

        .input-field label {
            font-size: 0.875rem;
            font-weight: 500;
            color: var(--text-main);
            margin-bottom: 0.375rem;
        }

        .input-field input,
        .input-field select,
        .input-field textarea {
            padding: 0.625rem 0.875rem;
            border: 1px solid var(--border-light);
            border-radius: 8px;
            font-size: 0.875rem;
            transition: all 0.2s ease;
            font-family: inherit;
        }

        .input-field input:focus,
        .input-field select:focus,
        .input-field textarea:focus {
            border-color: var(--primary);
            outline: none;
            box-shadow: 0 0 0 3px rgba(15, 118, 110, 0.08);
            background-color: #f8feff;
        }

        .input-hint {
            font-size: 0.75rem;
            color: var(--text-secondary);
            margin-top: 0.375rem;
        }
    </style>
</head>
<body>
    <!-- Header -->
    <header class="header-main py-4 md:py-6">
        <div class="container mx-auto px-4 md:px-6">
            <div class="flex flex-col md:flex-row justify-between items-start md:items-center gap-4">
                <div>
                    <h1 class="text-2xl md:text-3xl font-bold">Form Analisis Jurusan</h1>
                    <p class="text-sm md:text-base text-teal-100 mt-1">Temukan jurusan yang paling sesuai dengan minat dan potensi Anda</p>
                </div>
                <a href="{{ url('/dashboard') }}" class="btn-primary px-4 py-2 rounded-lg font-medium text-sm md:text-base hover:shadow-lg transition">
                    ← Kembali
                </a>
            </div>
        </div>
    </header>

    <!-- Main Content -->
    <main class="container mx-auto px-4 md:px-6 py-8 md:py-12">
        <!-- Info Banner -->
        <div class="card p-5 md:p-6 mb-6 md:mb-8 bg-gradient-to-r from-blue-50 to-cyan-50 border-l-4" style="border-left-color: var(--primary);">
            <div style="display: flex; gap: 1rem;">
                <div style="font-size: 2rem; flex-shrink: 0;">🎯</div>
                <div>
                    <h2 class="text-lg font-semibold mb-2 text-primary">Petunjuk Pengisian</h2>
                    <p class="text-sm mb-2">Isi form berikut dengan data yang akurat. Sistem akan menganalisis profil Anda menggunakan algoritma Weighted Naive Bayes untuk merekomendasikan 9 jurusan terbaik.</p>
                    <p class="text-xs" style="color: var(--text-secondary);">Bobot kriteria: Minat (45.6%) • Preferensi (25.6%) • Nilai (15.6%) • Cita-cita (9%) • Prestasi (4%)</p>
                </div>
            </div>
        </div>

        <!-- Form Card -->

        <div class="card p-6 md:p-8">
            <h2 class="text-2xl font-bold mb-6 text-primary">Data Diri Siswa</h2>

            @if ($errors->any())
                <div class="mb-6 p-4 rounded-lg bg-red-50 border-l-4" style="border-left-color: var(--error);">
                    <div style="display: flex; gap: 1rem;">
                        <div style="font-size: 1.5rem; flex-shrink: 0;">❌</div>
                        <div>
                            <h3 style="color: var(--error); font-weight: 600; margin-bottom: 0.5rem;">Ada Kesalahan Pengisian</h3>
                            <ul style="list-style: none; padding: 0; margin: 0;">
                                @foreach ($errors->all() as $error)
                                    <li style="font-size: 0.875rem; color: var(--error); margin-bottom: 0.375rem;">
                                        • {{ $error }}
                                    </li>
                                @endforeach
                            </ul>
                        </div>
                    </div>
                </div>
            @endif

            <form action="{{ route('rekomendasi.proses') }}" method="POST" class="space-y-8">
                @csrf

                <!-- NILAI AKADEMIK -->
                <section>
                    <h3 class="section-title">
                        <span style="font-size: 1.25rem;">1️⃣</span> Nilai Akademik
                        <span style="color: var(--error);">*</span>
                    </h3>
                    <p class="section-desc">
                        @if(isset($student) && $student->kelompok_asal == 'IPA')
                            Masukkan nilai rapor untuk IPA: <strong>Matematika, Fisika, Kimia, Biologi</strong>
                        @else
                            Masukkan nilai rapor untuk IPS: <strong>Ekonomi, Geografi, Sosiologi, Sejarah</strong>
                        @endif
                    </p>

                    @if(isset($student) && $student->kelompok_asal == 'IPA')
                        <div class="grid grid-cols-2 md:grid-cols-4 gap-4">
                            @foreach(['mtk' => 'Matematika', 'fisika' => 'Fisika', 'kimia' => 'Kimia', 'biologi' => 'Biologi'] as $field => $label)
                                <div class="input-field">
                                    <label>{{ $label }}</label>
                                    <input type="number" name="{{ $field }}" min="0" max="100" value="{{ old($field) }}" placeholder="0-100" required
                                        class="focus-primary @error($field) input-error @enderror">
                                    @error($field)
                                        <span class="validation-message" style="color: var(--error);">⚠️ {{ $message }}</span>
                                    @enderror
                                </div>
                            @endforeach
                        </div>
                    @else
                        <div class="grid grid-cols-2 md:grid-cols-4 gap-4">
                            @foreach(['ekonomi' => 'Ekonomi', 'geografi' => 'Geografi', 'sosiologi' => 'Sosiologi', 'sejarah' => 'Sejarah'] as $field => $label)
                                <div class="input-field">
                                    <label>{{ $label }}</label>
                                    <input type="number" name="{{ $field }}" min="0" max="100" value="{{ old($field) }}" placeholder="0-100" required
                                        class="focus-primary @error($field) input-error @enderror">
                                    @error($field)
                                        <span class="validation-message" style="color: var(--error);">⚠️ {{ $message }}</span>
                                    @enderror
                                </div>
                            @endforeach
                        </div>
                    @endif
                </section>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
                    <!-- MINAT -->
                    <section>
                        <h3 class="section-title">
                            <span style="font-size: 1.25rem;">2️⃣</span> Bidang Minat
                            <span style="color: var(--error);">*</span>
                        </h3>
                        <p class="section-desc">Tuliskan bidang atau kegiatan yang Anda minati. Contoh: coding, pertanian, seni, dll.</p>
                        <div class="input-field">
                            <input type="text" name="minat" value="{{ old('minat') }}" placeholder="Minat Anda..." required
                                class="focus-primary @error('minat') input-error @enderror">
                            <span class="input-hint">Minimal 3 karakter, pisahkan dengan koma jika lebih dari satu</span>
                            @error('minat')
                                <span class="validation-message" style="color: var(--error);">⚠️ {{ $message }}</span>
                            @enderror
                        </div>
                    </section>

                    <!-- PREFERENSI STUDI -->
                    <section>
                        <h3 class="section-title">
                            <span style="font-size: 1.25rem;">3️⃣</span> Preferensi Studi
                            <span style="color: var(--error);">*</span>
                        </h3>
                        <p class="section-desc">Pilih rumpun jurusan yang paling Anda minati untuk studi lanjutan.</p>
                        <div class="input-field">
                            <select name="pref_studi" required class="focus-primary @error('pref_studi') input-error @enderror">
                                <option value="">-- Pilih Preferensi --</option>
                                <option value="Sains & Teknologi" {{ old('pref_studi') == 'Sains & Teknologi' ? 'selected' : '' }}>Sains & Teknologi</option>
                                <option value="Pertanian & Lingkungan" {{ old('pref_studi') == 'Pertanian & Lingkungan' ? 'selected' : '' }}>Pertanian & Lingkungan</option>
                                <option value="Kesehatan & Ilmu Hayat" {{ old('pref_studi') == 'Kesehatan & Ilmu Hayat' ? 'selected' : '' }}>Kesehatan & Ilmu Hayat</option>
                                <option value="Bisnis & Manajemen" {{ old('pref_studi') == 'Bisnis & Manajemen' ? 'selected' : '' }}>Bisnis & Manajemen</option>
                                <option value="Sosial & Humaniora" {{ old('pref_studi') == 'Sosial & Humaniora' ? 'selected' : '' }}>Sosial & Humaniora</option>
                            </select>
                            @error('pref_studi')
                                <span class="validation-message" style="color: var(--error);">⚠️ {{ $message }}</span>
                            @enderror
                        </div>
                    </section>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
                    <!-- CITA-CITA -->
                    <section>
                        <h3 class="section-title">
                            <span style="font-size: 1.25rem;">4️⃣</span> Cita-cita / Karir Impian
                            <span style="color: var(--error);">*</span>
                        </h3>
                        <p class="section-desc">Tuliskan profesi atau karir yang Anda impikan di masa depan.</p>
                        <div class="input-field">
                            <input type="text" name="cita_cita" value="{{ old('cita_cita') }}" placeholder="Contoh: programmer, dokter, pengusaha..." required
                                class="focus-primary @error('cita_cita') input-error @enderror">
                            <span class="input-hint">Minimal 3 karakter, pisahkan dengan koma jika lebih dari satu</span>
                            @error('cita_cita')
                                <span class="validation-message" style="color: var(--error);">⚠️ {{ $message }}</span>
                            @enderror
                        </div>
                    </section>

                    <!-- PRESTASI -->
                    <section>
                        <h3 class="section-title">
                            <span style="font-size: 1.25rem;">5️⃣</span> Prestasi (Opsional)
                        </h3>
                        <p class="section-desc">Tuliskan prestasi akademik atau non-akademik yang pernah Anda raih.</p>
                        <div class="input-field">
                            <input type="text" name="prestasi" value="{{ old('prestasi') }}" placeholder="Contoh: Juara olimpiade, sertifikat programming..."
                                class="focus-primary @error('prestasi') input-error @enderror">
                            <span class="input-hint">Kolom ini bersifat opsional</span>
                            @error('prestasi')
                                <span class="validation-message" style="color: var(--error);">⚠️ {{ $message }}</span>
                            @enderror
                        </div>
                    </section>
                </div>

                <!-- SUBMIT BUTTON -->
                <div class="pt-4">
                    <button type="submit" class="btn-primary w-full py-3 rounded-lg font-semibold text-white text-base md:text-lg shadow-md hover:shadow-lg transition">
                        ✨ Analisis & Lihat Rekomendasi
                    </button>
                    <p style="font-size: 0.875rem; color: var(--text-secondary); margin-top: 1rem; text-align: center;">
                        ⏳ Proses analisis membutuhkan waktu 1-2 detik
                    </p>
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


        <!-- Footer Info -->
        <div class="card p-4 md:p-5 mt-8 bg-gradient-to-r from-teal-50 to-cyan-50">
            <p style="font-size: 0.875rem; color: var(--text-secondary);">
                <strong>📊 Metode Analisis:</strong> Weighted Naive Bayes dengan 5 kriteria yang diseimbangkan berdasarkan data historis siswa Polije.
            </p>
        </div>
    </main>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const form = document.querySelector('form');
            const submitBtn = form?.querySelector('button[type="submit"]');
            const inputs = form?.querySelectorAll('input, select, textarea');
            
            inputs?.forEach(input => {
                input.addEventListener('change', () => validateField(input));
                input.addEventListener('blur', () => validateField(input));
                input.addEventListener('input', function() {
                    if (this.classList.contains('input-error')) {
                        validateField(this);
                    }
                });
            });

            form?.addEventListener('submit', function(e) {
                let isValid = true;
                inputs?.forEach(input => {
                    if (!validateField(input)) {
                        isValid = false;
                    }
                });

                if (!isValid) {
                    e.preventDefault();
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
                const type = field.type;
                let isValid = true;

                field.classList.remove('input-error', 'input-valid');
                const existingMsg = field.parentElement.querySelector('.validation-message');
                if (existingMsg) existingMsg.remove();

                if (isRequired && !value) {
                    isValid = false;
                } else if (type === 'number' && value) {
                    const num = parseFloat(value);
                    if (isNaN(num) || num < 0 || num > 100) {
                        isValid = false;
                    }
                } else if ((field.name === 'minat' || field.name === 'cita_cita') && value && value.length < 3) {
                    isValid = false;
                }

                if (!isValid && (value || isRequired)) {
                    field.classList.add('input-error');
                } else if (isValid && (isRequired || value)) {
                    field.classList.add('input-valid');
                }

                return isValid || !isRequired;
            }

            form?.addEventListener('submit', function() {
                if (submitBtn) {
                    submitBtn.disabled = true;
                    submitBtn.innerHTML = '⏳ Menganalisis...';
                }
            });
        });
    </script>
</body>
</html>