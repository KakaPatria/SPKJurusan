<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Input Alumni Baru - Sistem Pemilihan Jurusan</title>
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
        .focus-maroon:focus {
            border-color: #5B7B89;
            box-shadow: 0 0 0 3px rgba(107, 44, 44, 0.1);
        }
    </style>
</head>
<body class="bg-cream">
    <!-- Header -->
    <header class="gradient-maroon text-white shadow-lg sticky top-0 z-50">
        <div class="container mx-auto px-4 sm:px-6 py-4 sm:py-6 flex justify-between items-center">
            <div>
                <h1 class="text-xl sm:text-2xl md:text-3xl font-bold">Input Alumni Baru</h1>
                <p class="text-xs sm:text-sm text-yellow-300 font-semibold mt-1">Tambah data alumni untuk validasi algoritma</p>
            </div>
            <a href="{{ route('alumni.index') }}" class="bg-yellow-400 text-maroon font-bold py-2 px-4 rounded-lg hover:bg-yellow-300 transition text-xs sm:text-sm">
                ← Kembali
            </a>
        </div>
    </header>

    <!-- Main Content -->
    <div class="container mx-auto px-4 sm:px-6 py-6 sm:py-12">
        @if($errors->any())
            <div class="mb-6 p-4 bg-red-100 border-l-4 border-red-500 text-red-700 rounded">
                <p class="font-bold mb-2">❌ Ada kesalahan validasi:</p>
                <ul class="list-disc pl-5 text-sm">
                    @foreach($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <form action="{{ route('alumni.store') }}" method="POST" class="max-w-4xl mx-auto">
            @csrf
            
            <!-- Section 1: Data Dasar Alumni -->
            <div class="bg-white rounded-lg shadow p-6 mb-6 border-l-4 border-maroon">
                <h2 class="text-lg font-bold text-maroon mb-4">📋 Data Dasar Alumni</h2>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div>
                        <label class="block text-sm font-semibold text-gray-700 mb-2">Nama Alumni *</label>
                        <input type="text" name="nama_alumni" required class="w-full px-4 py-2 border border-gray-300 rounded-lg focus-maroon focus:outline-none text-sm" value="{{ old('nama_alumni') }}" placeholder="Nama lengkap">
                    </div>
                    <div>
                        <label class="block text-sm font-semibold text-gray-700 mb-2">NIS</label>
                        <input type="text" name="nis" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus-maroon focus:outline-none text-sm" value="{{ old('nis') }}" placeholder="12345">
                    </div>
                    <div>
                        <label class="block text-sm font-semibold text-gray-700 mb-2">Kelompok Asal *</label>
                        <select name="kelompok_asal" required class="w-full px-4 py-2 border border-gray-300 rounded-lg focus-maroon focus:outline-none text-sm">
                            <option value="">-- Pilih --</option>
                            <option value="IPA" {{ old('kelompok_asal') == 'IPA' ? 'selected' : '' }}>IPA</option>
                            <option value="IPS" {{ old('kelompok_asal') == 'IPS' ? 'selected' : '' }}>IPS</option>
                        </select>
                    </div>
                    <div>
                        <label class="block text-sm font-semibold text-gray-700 mb-2">Tahun Masuk *</label>
                        <input type="number" name="tahun_masuk" required min="2020" max="{{ date('Y') }}" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus-maroon focus:outline-none text-sm" value="{{ old('tahun_masuk') }}" placeholder="2023">
                    </div>
                </div>
            </div>

            <!-- Section 2: Nilai Saat Entry -->
            <div class="bg-white rounded-lg shadow p-6 mb-6 border-l-4 border-yellow-400">
                <h2 class="text-lg font-bold text-maroon mb-4">📊 Nilai Saat Entry (Rapor)</h2>
                <div id="nilaiFields" class="grid grid-cols-2 md:grid-cols-4 gap-3">
                    <div>
                        <label class="block text-xs font-semibold text-gray-700 mb-1">Matematika</label>
                        <input type="number" name="mtk" step="0.01" min="0" max="100" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus-maroon focus:outline-none text-sm" value="{{ old('mtk') }}" placeholder="85">
                    </div>
                    <div id="ipa-fields" style="display: none;" class="contents">
                        <div>
                            <label class="block text-xs font-semibold text-gray-700 mb-1">Fisika</label>
                            <input type="number" name="fisika" step="0.01" min="0" max="100" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus-maroon focus:outline-none text-sm" value="{{ old('fisika') }}" placeholder="78">
                        </div>
                        <div>
                            <label class="block text-xs font-semibold text-gray-700 mb-1">Kimia</label>
                            <input type="number" name="kimia" step="0.01" min="0" max="100" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus-maroon focus:outline-none text-sm" value="{{ old('kimia') }}" placeholder="72">
                        </div>
                        <div>
                            <label class="block text-xs font-semibold text-gray-700 mb-1">Biologi</label>
                            <input type="number" name="biologi" step="0.01" min="0" max="100" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus-maroon focus:outline-none text-sm" value="{{ old('biologi') }}" placeholder="80">
                        </div>
                    </div>
                    <div id="ips-fields" style="display: none;" class="contents">
                        <div>
                            <label class="block text-xs font-semibold text-gray-700 mb-1">Ekonomi</label>
                            <input type="number" name="ekonomi" step="0.01" min="0" max="100" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus-maroon focus:outline-none text-sm" value="{{ old('ekonomi') }}" placeholder="82">
                        </div>
                        <div>
                            <label class="block text-xs font-semibold text-gray-700 mb-1">Geografi</label>
                            <input type="number" name="geografi" step="0.01" min="0" max="100" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus-maroon focus:outline-none text-sm" value="{{ old('geografi') }}" placeholder="76">
                        </div>
                        <div>
                            <label class="block text-xs font-semibold text-gray-700 mb-1">Sosiologi</label>
                            <input type="number" name="sosiologi" step="0.01" min="0" max="100" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus-maroon focus:outline-none text-sm" value="{{ old('sosiologi') }}" placeholder="74">
                        </div>
                        <div>
                            <label class="block text-xs font-semibold text-gray-700 mb-1">Sejarah</label>
                            <input type="number" name="sejarah" step="0.01" min="0" max="100" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus-maroon focus:outline-none text-sm" value="{{ old('sejarah') }}" placeholder="70">
                        </div>
                    </div>
                </div>
            </div>

            <!-- Section 3: Variabel Non-Akademik -->
            <div class="bg-white rounded-lg shadow p-6 mb-6 border-l-4 border-blue-400">
                <h2 class="text-lg font-bold text-maroon mb-4">💡 Variabel Non-Akademik</h2>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div>
                        <label class="block text-sm font-semibold text-gray-700 mb-2">Minat</label>
                        <input type="text" name="minat" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus-maroon focus:outline-none text-sm" value="{{ old('minat') }}" placeholder="Contoh: coding, bercocok tanam">
                    </div>
                    <div>
                        <label class="block text-sm font-semibold text-gray-700 mb-2">Cita-cita / Profesi</label>
                        <input type="text" name="cita_cita" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus-maroon focus:outline-none text-sm" value="{{ old('cita_cita') }}" placeholder="Contoh: Programmer, Dokter">
                    </div>
                    <div>
                        <label class="block text-sm font-semibold text-gray-700 mb-2">Preferensi Studi</label>
                        <select name="preferensi_studi" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus-maroon focus:outline-none text-sm">
                            <option value="">-- Pilih --</option>
                            <option value="Praktik Langsung" {{ old('preferensi_studi') == 'Praktik Langsung' ? 'selected' : '' }}>Praktik Langsung</option>
                            <option value="DuDi" {{ old('preferensi_studi') == 'DuDi' ? 'selected' : '' }}>DuDi (Dunia Usaha & Industri)</option>
                            <option value="Project Based" {{ old('preferensi_studi') == 'Project Based' ? 'selected' : '' }}>Project Based</option>
                            <option value="Blended" {{ old('preferensi_studi') == 'Blended' ? 'selected' : '' }}>Blended (Teori + Praktik)</option>
                        </select>
                    </div>
                    <div>
                        <label class="block text-sm font-semibold text-gray-700 mb-2">Prestasi (opsional)</label>
                        <input type="text" name="prestasi" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus-maroon focus:outline-none text-sm" value="{{ old('prestasi') }}" placeholder="Contoh: Juara lomba, sertifikat">
                    </div>
                </div>
            </div>

            <!-- Section 4: Rekomendasi & Hasil -->
            <div class="bg-white rounded-lg shadow p-6 mb-6 border-l-4 border-green-400">
                <h2 class="text-lg font-bold text-maroon mb-4">🎯 Rekomendasi & Hasil Algoritma</h2>
                <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                    <div>
                        <label class="block text-sm font-semibold text-gray-700 mb-2">Jurusan Masuk *</label>
                        <input type="text" name="major_masuk" required class="w-full px-4 py-2 border border-gray-300 rounded-lg focus-maroon focus:outline-none text-sm" value="{{ old('major_masuk') }}" placeholder="Contoh: Teknologi Informasi">
                    </div>
                    <div>
                        <label class="block text-sm font-semibold text-gray-700 mb-2">Ranking Rekomendasi (saat input)</label>
                        <input type="number" name="ranking_saat_rekomendasi" min="1" max="9" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus-maroon focus:outline-none text-sm" value="{{ old('ranking_saat_rekomendasi') }}" placeholder="1-9">
                    </div>
                    <div>
                        <label class="block text-sm font-semibold text-gray-700 mb-2">Score Prediksi</label>
                        <input type="number" name="predicted_score" step="0.01" min="0" max="1" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus-maroon focus:outline-none text-sm" value="{{ old('predicted_score') }}" placeholder="0.95">
                    </div>
                </div>
            </div>

            <!-- Section 5: Outcome Alumni -->
            <div class="bg-white rounded-lg shadow p-6 mb-6 border-l-4 border-purple-400">
                <h2 class="text-lg font-bold text-maroon mb-4">🎓 Outcome Alumni</h2>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div>
                        <label class="block text-sm font-semibold text-gray-700 mb-2">IPK Lulus</label>
                        <input type="number" name="ipk_lulus" step="0.01" min="0" max="4" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus-maroon focus:outline-none text-sm" value="{{ old('ipk_lulus') }}" placeholder="3.65">
                    </div>
                    <div>
                        <label class="block text-sm font-semibold text-gray-700 mb-2">Tahun Lulus</label>
                        <input type="number" name="tahun_lulus" min="2020" max="{{ date('Y') }}" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus-maroon focus:outline-none text-sm" value="{{ old('tahun_lulus') }}" placeholder="2026">
                    </div>
                    <div class="md:col-span-2">
                        <label class="block text-sm font-semibold text-gray-700 mb-2">Karir Outcome (deskripsi)</label>
                        <textarea name="karir_outcome" rows="3" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus-maroon focus:outline-none text-sm" placeholder="Contoh: Bekerja di PT ABC sebagai Software Developer">{{ old('karir_outcome') }}</textarea>
                    </div>
                    <div>
                        <label class="block text-sm font-semibold text-gray-700 mb-2">Status Kesuksesan</label>
                        <select name="success_status" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus-maroon focus:outline-none text-sm">
                            <option value="">-- Pilih --</option>
                            <option value="sangat_sukses" {{ old('success_status') == 'sangat_sukses' ? 'selected' : '' }}>✓ Sangat Sukses</option>
                            <option value="sukses" {{ old('success_status') == 'sukses' ? 'selected' : '' }}>✓ Sukses</option>
                            <option value="cukup" {{ old('success_status') == 'cukup' ? 'selected' : '' }}>• Cukup</option>
                            <option value="kurang_sukses" {{ old('success_status') == 'kurang_sukses' ? 'selected' : '' }}>✗ Kurang Sukses</option>
                        </select>
                    </div>
                    <div>
                        <label class="block text-sm font-semibold text-gray-700 mb-2">Catatan Tambahan</label>
                        <textarea name="catatan" rows="2" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus-maroon focus:outline-none text-sm" placeholder="Catatan / observasi tambahan">{{ old('catatan') }}</textarea>
                    </div>
                </div>
            </div>

            <!-- Submit Buttons -->
            <div class="flex gap-4 justify-end">
                <a href="{{ route('alumni.index') }}" class="px-6 py-2 rounded-lg font-bold bg-gray-300 text-gray-700 hover:bg-gray-400 transition">
                    Batal
                </a>
                <button type="submit" class="px-6 py-2 rounded-lg font-bold gradient-maroon text-white hover:opacity-90 transition">
                    💾 Simpan Alumni
                </button>
            </div>
        </form>
    </div>

    <script>
        // Tampilkan field nilai berdasarkan kelompok asal
        const kelompokSelect = document.querySelector('select[name="kelompok_asal"]');
        const ipaFields = document.getElementById('ipa-fields');
        const ipsFields = document.getElementById('ips-fields');

        function updateNilaiFields() {
            const kelompok = kelompokSelect.value;
            ipaFields.style.display = kelompok === 'IPA' ? 'contents' : 'none';
            ipsFields.style.display = kelompok === 'IPS' ? 'contents' : 'none';
        }

        kelompokSelect.addEventListener('change', updateNilaiFields);
        updateNilaiFields(); // Call on load
    </script>
</body>
</html>
