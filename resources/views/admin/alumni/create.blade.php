@extends('admin.layouts.app')

@section('title', 'Tambah Alumni')

@section('content')
    <div class="mb-6">
        <h2 class="text-2xl font-bold text-maroon">➕ Tambah Alumni</h2>
        <p class="text-sm text-gray-500 mt-1">Input data alumni SMA Bima Ambulu</p>
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

    <form action="{{ route('admin.alumni.store') }}" method="POST" class="max-w-2xl" id="alumniForm">
        @csrf

        <!-- Data Dasar -->
        <div class="bg-white rounded-lg shadow p-6 mb-6 border-l-4 border-maroon">
            <h3 class="text-lg font-bold text-maroon mb-4">📋 Data Dasar</h3>
            
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-4">
                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-2">Nama Alumni * <span class="text-gray-400 text-xs">(minimal 3 karakter)</span></label>
                    <input type="text" name="nama_alumni" required minlength="3" maxlength="255" class="w-full px-4 py-2 border rounded-lg focus:outline-none focus:ring-2 transition @error('nama_alumni') border-red-500 focus:ring-red-400 @else border-gray-300 focus:ring-maroon @enderror text-sm" 
                        value="{{ old('nama_alumni') }}" placeholder="Nama lengkap" oninput="validateAlumniForm()">
                    <div class="flex justify-between items-center mt-1">
                        <span id="namaAlumniError" class="text-red-500 text-xs hidden">⚠️ Nama minimal 3 karakter</span>
                        <span id="namaAlumniValid" class="text-green-500 text-xs hidden">✓ Nama valid</span>
                    </div>
                    @error('nama_alumni') <span class="text-red-500 text-xs block mt-1">{{ $message }}</span> @enderror
                </div>
                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-2">NIS <span class="text-gray-400 text-xs">(max 20 karakter)</span></label>
                    <input type="text" name="nis" maxlength="20" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-maroon text-sm" 
                        value="{{ old('nis') }}" placeholder="NIS SMA (opsional)">
                </div>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-2">Kelompok Asal *</label>
                    <select name="kelompok_asal" required class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-maroon text-sm" oninput="validateAlumniForm()">
                        <option value="">-- Pilih --</option>
                        <option value="IPA" {{ old('kelompok_asal') == 'IPA' ? 'selected' : '' }}>IPA</option>
                        <option value="IPS" {{ old('kelompok_asal') == 'IPS' ? 'selected' : '' }}>IPS</option>
                    </select>
                    @error('kelompok_asal') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                </div>
                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-2">Minat / Bidang Studi <span class="text-gray-400 text-xs">(min 2 karakter)</span></label>
                    <input type="text" name="minat" minlength="2" maxlength="255" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-maroon text-sm" 
                        value="{{ old('minat') }}" placeholder="Minat siswa (opsional)">
                </div>
            </div>
        </div>

        <!-- Nilai Entry -->
        <div class="bg-white rounded-lg shadow p-6 mb-6 border-l-4 border-yellow-400">
            <h3 class="text-lg font-bold text-maroon mb-4">📊 Nilai Saat Entry (Rapor SMA)</h3>
            <p class="text-xs text-gray-500 mb-4">Input nilai 0-100 sesuai kelompok asal.</p>

            <div id="ipaScores" class="hidden">
                <p class="text-xs font-semibold text-blue-700 mb-3">Mapel IPA: Matematika, Fisika, Kimia, Biologi</p>
                <div class="grid grid-cols-2 md:grid-cols-3 gap-3">
                    <div>
                        <label class="block text-xs font-semibold text-gray-700 mb-1">Matematika</label>
                        <input type="number" name="mtk" step="0.01" min="0" max="100" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-maroon text-sm" 
                            value="{{ old('mtk') }}" placeholder="0-100" oninput="validateScore(this)">
                        @error('mtk') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                    </div>
                    <div>
                        <label class="block text-xs font-semibold text-gray-700 mb-1">Fisika</label>
                        <input type="number" name="fisika" step="0.01" min="0" max="100" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-maroon text-sm" 
                            value="{{ old('fisika') }}" placeholder="0-100" oninput="validateScore(this)">
                        @error('fisika') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                    </div>
                    <div>
                        <label class="block text-xs font-semibold text-gray-700 mb-1">Kimia</label>
                        <input type="number" name="kimia" step="0.01" min="0" max="100" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-maroon text-sm" 
                            value="{{ old('kimia') }}" placeholder="0-100" oninput="validateScore(this)">
                        @error('kimia') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                    </div>
                    <div>
                        <label class="block text-xs font-semibold text-gray-700 mb-1">Biologi</label>
                        <input type="number" name="biologi" step="0.01" min="0" max="100" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-maroon text-sm" 
                            value="{{ old('biologi') }}" placeholder="0-100" oninput="validateScore(this)">
                        @error('biologi') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                    </div>
                </div>
            </div>

            <div id="ipsScores" class="hidden">
                <p class="text-xs font-semibold text-amber-700 mb-3">Mapel IPS: Ekonomi, Geografi, Sosiologi, Sejarah</p>
                <div class="grid grid-cols-2 md:grid-cols-3 gap-3">
                    <div>
                        <label class="block text-xs font-semibold text-gray-700 mb-1">Ekonomi</label>
                        <input type="number" name="ekonomi" step="0.01" min="0" max="100" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-maroon text-sm" 
                            value="{{ old('ekonomi') }}" placeholder="0-100" oninput="validateScore(this)">
                        @error('ekonomi') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                    </div>
                    <div>
                        <label class="block text-xs font-semibold text-gray-700 mb-1">Geografi</label>
                        <input type="number" name="geografi" step="0.01" min="0" max="100" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-maroon text-sm" 
                            value="{{ old('geografi') }}" placeholder="0-100" oninput="validateScore(this)">
                        @error('geografi') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                    </div>
                    <div>
                        <label class="block text-xs font-semibold text-gray-700 mb-1">Sosiologi</label>
                        <input type="number" name="sosiologi" step="0.01" min="0" max="100" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-maroon text-sm" 
                            value="{{ old('sosiologi') }}" placeholder="0-100" oninput="validateScore(this)">
                        @error('sosiologi') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                    </div>
                    <div>
                        <label class="block text-xs font-semibold text-gray-700 mb-1">Sejarah</label>
                        <input type="number" name="sejarah" step="0.01" min="0" max="100" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-maroon text-sm" 
                            value="{{ old('sejarah') }}" placeholder="0-100" oninput="validateScore(this)">
                        @error('sejarah') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                    </div>
                </div>
            </div>
        </div>

        <!-- Hasil -->
        <div class="bg-white rounded-lg shadow p-6 mb-6 border-l-4 border-green-400">
            <h3 class="text-lg font-bold text-maroon mb-4">🎯 Hasil / Outcome</h3>
            
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-4">
                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-2">Jurusan Masuk ke Polije * <span class="text-gray-400 text-xs">(min 3 karakter)</span></label>
                    <input type="text" name="major_masuk" required minlength="3" maxlength="255" class="w-full px-4 py-2 border rounded-lg focus:outline-none focus:ring-2 transition @error('major_masuk') border-red-500 focus:ring-red-400 @else border-gray-300 focus:ring-maroon @enderror text-sm" 
                        value="{{ old('major_masuk') }}" placeholder="Jurusan Polije" oninput="validateAlumniForm()">
                    <div class="flex justify-between items-center mt-1">
                        <span id="majorError" class="text-red-500 text-xs hidden">⚠️ Jurusan minimal 3 karakter</span>
                        <span id="majorValid" class="text-green-500 text-xs hidden">✓ Jurusan valid</span>
                    </div>
                    @error('major_masuk') <span class="text-red-500 text-xs block mt-1">{{ $message }}</span> @enderror
                </div>
                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-2">Tahun Lulus SMA Bima Ambulu <span class="text-gray-400 text-xs">(2020-sekarang)</span></label>
                    <input type="number" name="tahun_lulus_polije" min="2020" max="{{ date('Y') }}" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-maroon text-sm" 
                        value="{{ old('tahun_lulus_polije') }}" placeholder="Tahun lulus">
                    @error('tahun_lulus_polije') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                </div>
            </div>

            <div>
                <label class="block text-sm font-semibold text-gray-700 mb-2">Catatan <span class="text-gray-400 text-xs">(max 500 karakter)</span></label>
                <textarea name="catatan" rows="3" maxlength="500" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-maroon text-sm" 
                    placeholder="Catatan tambahan (opsional)" oninput="updateCharCount('catatan', 500)">{{ old('catatan') }}</textarea>
                <span id="catatanCount" class="text-gray-400 text-xs">0/500</span>
            </div>
        </div>

        <!-- Buttons -->
        <div class="flex gap-4 justify-end">
            <a href="{{ route('admin.alumni.index') }}" class="px-6 py-2 rounded-lg font-bold bg-gray-300 text-gray-700 hover:bg-gray-400 transition">
                Batal
            </a>
            <button type="submit" class="px-6 py-2 rounded-lg font-bold gradient-maroon text-white hover:opacity-90 transition disabled:opacity-50 disabled:cursor-not-allowed" id="submitButton">
                💾 Simpan
            </button>
        </div>
    </form>
@endsection

@section('scripts')
<script>
    function updateCharCount(fieldName, max) {
        const field = document.querySelector(`textarea[name="${fieldName}"]`);
        const countSpan = document.getElementById(`${fieldName}Count`);
        countSpan.textContent = `${field.value.length}/${max}`;
    }

    function validateScore(input) {
        let value = parseFloat(input.value);
        if (value > 100) {
            input.value = '100';
        } else if (value < 0 && input.value !== '') {
            input.value = '0';
        }
    }

    function validateAlumniForm() {
        const namaAlumni = document.querySelector('input[name="nama_alumni"]');
        const major = document.querySelector('input[name="major_masuk"]');
        const kelompok = document.querySelector('select[name="kelompok_asal"]');
        const submitButton = document.getElementById('submitButton');

        // Validate nama alumni
        if (namaAlumni.value.trim().length >= 3) {
            namaAlumni.classList.remove('border-red-500', 'focus:ring-red-400');
            namaAlumni.classList.add('border-gray-300', 'focus:ring-maroon');
            document.getElementById('namaAlumniError').classList.add('hidden');
            document.getElementById('namaAlumniValid').classList.remove('hidden');
        } else if (namaAlumni.value.trim().length > 0) {
            namaAlumni.classList.remove('border-gray-300', 'focus:ring-maroon');
            namaAlumni.classList.add('border-red-500', 'focus:ring-red-400');
            document.getElementById('namaAlumniError').classList.remove('hidden');
            document.getElementById('namaAlumniValid').classList.add('hidden');
        }

        // Validate major
        if (major.value.trim().length >= 3) {
            major.classList.remove('border-red-500', 'focus:ring-red-400');
            major.classList.add('border-gray-300', 'focus:ring-maroon');
            document.getElementById('majorError').classList.add('hidden');
            document.getElementById('majorValid').classList.remove('hidden');
        } else if (major.value.trim().length > 0) {
            major.classList.remove('border-gray-300', 'focus:ring-maroon');
            major.classList.add('border-red-500', 'focus:ring-red-400');
            document.getElementById('majorError').classList.remove('hidden');
            document.getElementById('majorValid').classList.add('hidden');
        }

        // Enable/disable submit
        const isValid = namaAlumni.value.trim().length >= 3 && 
                       major.value.trim().length >= 3 && 
                       kelompok.value !== '';
        submitButton.disabled = !isValid;
    }

    function toggleScoreByKelompok() {
        const kelompok = document.querySelector('select[name="kelompok_asal"]').value;
        const ipaContainer = document.getElementById('ipaScores');
        const ipsContainer = document.getElementById('ipsScores');

        ipaContainer.classList.add('hidden');
        ipsContainer.classList.add('hidden');

        ipaContainer.querySelectorAll('input').forEach(input => input.disabled = true);
        ipsContainer.querySelectorAll('input').forEach(input => input.disabled = true);

        if (kelompok === 'IPA') {
            ipaContainer.classList.remove('hidden');
            ipaContainer.querySelectorAll('input').forEach(input => input.disabled = false);
        } else if (kelompok === 'IPS') {
            ipsContainer.classList.remove('hidden');
            ipsContainer.querySelectorAll('input').forEach(input => input.disabled = false);
        }
    }

    document.addEventListener('DOMContentLoaded', function() {
        validateAlumniForm();
        updateCharCount('catatan', 500);

        const kelompokSelect = document.querySelector('select[name="kelompok_asal"]');
        kelompokSelect.addEventListener('change', toggleScoreByKelompok);
        toggleScoreByKelompok();
    });
</script>
@endsection
