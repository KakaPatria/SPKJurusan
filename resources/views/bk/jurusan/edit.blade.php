@extends('bk.layouts.app')

@section('title', 'Edit Jurusan')

@section('content')
    <div class="flex justify-between items-center mb-6">
        <div>
            <h2 class="text-2xl font-bold text-bk">✏️ Edit Jurusan: {{ $jurusan->nama_jurusan }}</h2>
            <p class="text-sm text-gray-500 mt-1">Ubah informasi jurusan</p>
        </div>
        <a href="{{ route('bk.jurusan') }}" class="bg-gray-400 text-white font-bold py-2 px-4 rounded-lg hover:bg-gray-500 transition text-sm">
            ← Kembali
        </a>
    </div>

    <!-- Error Summary Alert -->
    @if($errors->any())
        <div class="mb-6 p-4 bg-red-50 border border-red-200 rounded-lg">
            <div class="flex items-center gap-2 mb-2">
                <span class="text-red-600 text-xl">⚠️</span>
                <span class="text-red-700 font-bold">Perbaiki kesalahan berikut:</span>
            </div>
            <ul class="text-red-600 text-sm space-y-1 ml-8">
                @foreach($errors->all() as $error)
                    <li>• {{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <form action="{{ route('bk.jurusan.update', $jurusan->id) }}" method="POST" class="space-y-6" id="jurusanForm">
        @csrf
        @method('PUT')

        <div class="bg-white rounded-lg shadow p-6">
            <h3 class="text-lg font-bold text-bk mb-4">📋 Informasi Jurusan</h3>
            <div class="space-y-4">
                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-2">Nama Jurusan <span class="text-red-500">*</span> <span class="text-gray-400 text-xs">(minimal 3 karakter)</span></label>
                    <input type="text" name="nama_jurusan" value="{{ old('nama_jurusan', $jurusan->nama_jurusan) }}" minlength="3" maxlength="100" class="w-full px-4 py-2 border rounded-lg focus:outline-none focus:ring-2 transition @error('nama_jurusan') border-red-500 focus:ring-red-400 @else border-gray-300 focus:ring-teal-400 @enderror" required oninput="validateJurusanForm()">
                    <div class="flex justify-between items-center mt-1">
                        <span id="namaError" class="text-red-500 text-xs hidden">⚠️ Nama harus minimal 3 karakter</span>
                        <span id="namaValid" class="text-green-500 text-xs hidden">✓ Nama valid</span>
                    </div>
                    @error('nama_jurusan') <span class="text-red-500 text-xs block mt-1">{{ $message }}</span> @enderror
                </div>

                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-2">Deskripsi <span class="text-gray-400 text-xs">(maks 500 karakter)</span></label>
                    <textarea name="deskripsi" rows="3" maxlength="500" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-teal-400 transition" placeholder="Jelaskan singkat tentang jurusan ini" oninput="updateCharCount('deskripsi', 500); validateJurusanForm()">{{ old('deskripsi', $jurusan->deskripsi) }}</textarea>
                    <div class="flex justify-between items-center mt-1">
                        <p class="text-xs text-gray-500">Jelaskan tentang program, fasilitas, dan prospek</p>
                        <span id="descCount" class="text-gray-400 text-xs">0/500</span>
                    </div>
                </div>

                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-2">Keywords (Kata Kunci) <span class="text-gray-400 text-xs">(pisahkan dengan koma)</span></label>
                    <textarea name="keywords" rows="2" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-teal-400 transition" placeholder="Contoh: programmer, developer, coding, software" oninput="validateJurusanForm()">{{ old('keywords', implode(', ', $jurusan->keywords ?? [])) }}</textarea>
                    <p class="text-xs text-gray-500 mt-1">Gunakan keywords untuk mencocokkan minat & cita-cita siswa dengan jurusan</p>
                    @if(!empty($jurusan->keywords))
                        <div class="flex flex-wrap gap-1 mt-2">
                            @foreach($jurusan->keywords as $kw)
                                <span class="inline-block px-2 py-0.5 rounded bg-blue-100 text-blue-700 text-xs">{{ $kw }}</span>
                            @endforeach
                        </div>
                    @endif
                </div>

                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-2">Preferensi Studi <span class="text-gray-400 text-xs">(pisahkan dengan koma)</span></label>
                    <textarea name="preferensi_studi" rows="2" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-teal-400 transition" placeholder="Contoh: Sains & Teknologi, Bisnis & Manajemen">{{ old('preferensi_studi', implode(', ', $jurusan->preferensi_studi ?? [])) }}</textarea>
                    <p class="text-xs text-gray-500 mt-1">Pilihan: Sains & Teknologi | Pertanian & Lingkungan | Kesehatan & Ilmu Hayat | Bisnis & Manajemen | Sosial & Humaniora</p>
                </div>

                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-2">Prospek Kerja <span class="text-gray-400 text-xs">(pisahkan dengan koma)</span></label>
                    <textarea name="prospek_kerja" rows="2" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-teal-400 transition" placeholder="Contoh: Software Developer, Web Developer, Data Analyst">{{ old('prospek_kerja', $jurusan->prospek_kerja) }}</textarea>
                </div>
            </div>
        </div>

        <!-- Bobot Mata Pelajaran -->
        @php
            $bobot = $jurusan->bobot_mapel ?? [];
        @endphp
        <div class="bg-white rounded-lg shadow p-6">
            <h3 class="text-lg font-bold text-bk mb-4">⚖️ Bobot Mata Pelajaran</h3>
            <p class="text-xs text-gray-500 mb-4">Tentukan bobot setiap mata pelajaran untuk jurusan ini (0.00 - 1.00). Mata pelajaran yang lebih relevan diberi bobot lebih tinggi. Jumlah total tidak harus 1.0.</p>
            
            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                <div>
                    <h4 class="text-sm font-bold text-gray-700 mb-3 border-b pb-2">📐 IPA</h4>
                    <div class="space-y-3">
                        <div>
                            <label class="block text-xs font-semibold text-gray-600 mb-1">Matematika</label>
                            <input type="number" name="bobot_mtk" value="{{ old('bobot_mtk', $bobot['mtk'] ?? '0.25') }}" step="0.05" min="0" max="1" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-teal-400 text-sm" oninput="validateBobot()">
                        </div>
                        <div>
                            <label class="block text-xs font-semibold text-gray-600 mb-1">Fisika</label>
                            <input type="number" name="bobot_fisika" value="{{ old('bobot_fisika', $bobot['fisika'] ?? '0.25') }}" step="0.05" min="0" max="1" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-teal-400 text-sm" oninput="validateBobot()">
                        </div>
                        <div>
                            <label class="block text-xs font-semibold text-gray-600 mb-1">Kimia</label>
                            <input type="number" name="bobot_kimia" value="{{ old('bobot_kimia', $bobot['kimia'] ?? '0.25') }}" step="0.05" min="0" max="1" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-teal-400 text-sm" oninput="validateBobot()">
                        </div>
                        <div>
                            <label class="block text-xs font-semibold text-gray-600 mb-1">Biologi</label>
                            <input type="number" name="bobot_biologi" value="{{ old('bobot_biologi', $bobot['biologi'] ?? '0.25') }}" step="0.05" min="0" max="1" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-teal-400 text-sm" oninput="validateBobot()">
                        </div>
                    </div>
                </div>
                <div>
                    <h4 class="text-sm font-bold text-gray-700 mb-3 border-b pb-2">📊 IPS</h4>
                    <div class="space-y-3">
                        <div>
                            <label class="block text-xs font-semibold text-gray-600 mb-1">Ekonomi</label>
                            <input type="number" name="bobot_ekonomi" value="{{ old('bobot_ekonomi', $bobot['ekonomi'] ?? '0.25') }}" step="0.05" min="0" max="1" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-teal-400 text-sm" oninput="validateBobot()">
                        </div>
                        <div>
                            <label class="block text-xs font-semibold text-gray-600 mb-1">Geografi</label>
                            <input type="number" name="bobot_geografi" value="{{ old('bobot_geografi', $bobot['geografi'] ?? '0.25') }}" step="0.05" min="0" max="1" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-teal-400 text-sm" oninput="validateBobot()">
                        </div>
                        <div>
                            <label class="block text-xs font-semibold text-gray-600 mb-1">Sosiologi</label>
                            <input type="number" name="bobot_sosiologi" value="{{ old('bobot_sosiologi', $bobot['sosiologi'] ?? '0.25') }}" step="0.05" min="0" max="1" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-teal-400 text-sm" oninput="validateBobot()">
                        </div>
                        <div>
                            <label class="block text-xs font-semibold text-gray-600 mb-1">Sejarah</label>
                            <input type="number" name="bobot_sejarah" value="{{ old('bobot_sejarah', $bobot['sejarah'] ?? '0.25') }}" step="0.05" min="0" max="1" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-teal-400 text-sm" oninput="validateBobot()">
                        </div>
                    </div>
                </div>
            </div>
            <div id="bobotInfo" class="mt-4 p-3 bg-blue-50 border border-blue-200 rounded-lg hidden">
                <p class="text-blue-700 text-xs"><span id="bobotTotal">0</span> / 2.00 (Total bobot untuk penilaian)</p>
            </div>
        </div>

        <div class="flex gap-4">
            <button type="submit" class="flex-1 gradient-bk text-white font-bold py-3 px-4 rounded-lg hover:opacity-90 transition disabled:opacity-50 disabled:cursor-not-allowed" id="submitButton">
                💾 Simpan Perubahan
            </button>
            <a href="{{ route('bk.jurusan') }}" class="flex-1 bg-gray-400 text-white font-bold py-3 px-4 rounded-lg hover:bg-gray-500 transition text-center">
                Batal
            </a>
        </div>
    </form>
@endsection

@section('scripts')
<script>
    function updateCharCount(textareaName, max) {
        const textarea = document.querySelector(`textarea[name="${textareaName}"]`);
        const countSpan = document.getElementById('descCount');
        countSpan.textContent = `${textarea.value.length}/${max}`;
    }

    function validateJurusanForm() {
        const namaJurusan = document.querySelector('input[name="nama_jurusan"]');
        const namaError = document.getElementById('namaError');
        const namaValid = document.getElementById('namaValid');
        const submitButton = document.getElementById('submitButton');

        let isNamaValid = false;
        if (namaJurusan.value.trim().length >= 3) {
            namaJurusan.classList.remove('border-red-500', 'focus:ring-red-400');
            namaJurusan.classList.add('border-gray-300', 'focus:ring-teal-400');
            namaError.classList.add('hidden');
            namaValid.classList.remove('hidden');
            isNamaValid = true;
        } else if (namaJurusan.value.trim().length > 0) {
            namaJurusan.classList.remove('border-gray-300', 'focus:ring-teal-400');
            namaJurusan.classList.add('border-red-500', 'focus:ring-red-400');
            namaError.classList.remove('hidden');
            namaValid.classList.add('hidden');
        } else {
            namaError.classList.add('hidden');
            namaValid.classList.add('hidden');
        }

        submitButton.disabled = !isNamaValid;
    }

    function validateBobot() {
        const bobotInputs = document.querySelectorAll('input[type="number"][name^="bobot_"]');
        const bobotInfo = document.getElementById('bobotInfo');
        const bobotTotal = document.getElementById('bobotTotal');
        let total = 0;

        bobotInputs.forEach(input => {
            let value = parseFloat(input.value);
            if (isNaN(value) || value < 0) {
                value = 0;
                input.value = '0.00';
            }
            if (value > 1) {
                value = 1;
                input.value = '1.00';
            }
            total += value;
        });

        bobotTotal.textContent = total.toFixed(2);
        bobotInfo.classList.remove('hidden');
    }

    document.addEventListener('DOMContentLoaded', function() {
        validateJurusanForm();
        validateBobot();
        updateCharCount('deskripsi', 500);
    });
</script>
@endsection
