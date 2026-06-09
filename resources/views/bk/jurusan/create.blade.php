@extends('bk.layouts.app')

@section('title', 'Tambah Jurusan')

@section('content')
    <div class="flex justify-between items-center mb-6">
        <div>
            <h2 class="text-2xl font-bold text-bk">➕ Tambah Jurusan Baru</h2>
            <p class="text-sm text-gray-500 mt-1">Tambahkan jurusan baru ke dalam sistem rekomendasi</p>
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

    <form action="{{ route('bk.jurusan.store') }}" method="POST" class="space-y-6" id="jurusanForm">
        @csrf

        <div class="bg-white rounded-lg shadow p-6">
            <h3 class="text-lg font-bold text-bk mb-4">📋 Informasi Jurusan</h3>
            <div class="space-y-4">
                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-2">Nama Jurusan <span class="text-red-500">*</span> <span class="text-gray-400 text-xs">(minimal 3 karakter)</span></label>
                    <input type="text" name="nama_jurusan" value="{{ old('nama_jurusan') }}" minlength="3" maxlength="100" class="w-full px-4 py-2 border rounded-lg focus:outline-none focus:ring-2 transition @error('nama_jurusan') border-red-500 focus:ring-red-400 @else border-gray-300 focus:ring-teal-400 @enderror" placeholder="Contoh: Teknologi Informasi" required oninput="validateJurusanForm()">
                    <div class="flex justify-between items-center mt-1">
                        <span id="namaError" class="text-red-500 text-xs hidden">⚠️ Nama harus minimal 3 karakter</span>
                        <span id="namaValid" class="text-green-500 text-xs hidden">✓ Nama valid</span>
                    </div>
                    @error('nama_jurusan') <span class="text-red-500 text-xs block mt-1">{{ $message }}</span> @enderror
                </div>

                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-2">Deskripsi <span class="text-gray-400 text-xs">(disarankan detail untuk kebutuhan chatbot)</span></label>
                    <textarea name="deskripsi" rows="3" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-teal-400 transition" placeholder="Jelaskan detail tentang jurusan ini" oninput="updateCharCount('deskripsi'); validateJurusanForm()">{{ old('deskripsi') }}</textarea>
                    <div class="flex justify-between items-center mt-1">
                        <p class="text-xs text-gray-500">Jelaskan tentang program, fasilitas, dan prospek</p>
                        <span id="descCount" class="text-gray-400 text-xs">0</span>
                    </div>
                </div>



                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-2">Preferensi Studi <span class="text-gray-400 text-xs">(pisahkan dengan koma)</span></label>
                    <textarea name="preferensi_studi" rows="2" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-teal-400 transition" placeholder="Contoh: Praktik Langsung, DuDi, Project Based, Blended Learning" oninput="validateJurusanForm()">{{ old('preferensi_studi') }}</textarea>
                    <p class="text-xs text-gray-500 mt-1">Pilihan preferensi studi: Praktik Langsung, DuDi, Project Based, Blended Learning</p>
                </div>

                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-2">Prospek Kerja <span class="text-gray-400 text-xs">(pisahkan dengan koma)</span></label>
                    <textarea name="prospek_kerja" rows="2" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-teal-400 transition" placeholder="Contoh: Software Developer, Web Developer, Data Analyst" oninput="validateJurusanForm()">{{ old('prospek_kerja') }}</textarea>
                </div>
            </div>
        </div>

        <!-- Bobot Mata Pelajaran dihapus: gunakan bobot ROC global -->
        <div class="bg-white rounded-lg shadow p-6">
            <h3 class="text-lg font-bold text-bk mb-4">⚖️ Bobot Penilaian (ROC)</h3>
            <p class="text-sm text-gray-600 mb-3">Mulai sekarang, sistem menggunakan bobot ROC tetap untuk semua jurusan. Pengaturan bobot per-mata-pelajaran tidak lagi tersedia di form ini.</p>
            <ul class="list-disc pl-5 text-sm text-gray-700 space-y-1">
                <li><strong>Minat</strong>: 45.6% (0.456)</li>
                <li><strong>Preferensi</strong>: 25.6% (0.256)</li>
                <li><strong>Nilai</strong>: 15.6% (0.156)</li>
                <li><strong>Cita-cita</strong>: 9.0% (0.090)</li>
                <li><strong>Prestasi</strong>: 4.0% (0.040)</li>
            </ul>
            <p class="text-xs text-gray-500 mt-3">Catatan: Jika Anda tetap menyimpan nilai bobot di database, sistem akan mengabaikannya dan menggunakan bobot ROC global dalam perhitungan rekomendasi.</p>
        </div>

        <div class="flex gap-4">
            <button type="submit" class="flex-1 gradient-bk text-white font-bold py-3 px-4 rounded-lg hover:opacity-90 transition disabled:opacity-50 disabled:cursor-not-allowed" id="submitButton">
                💾 Simpan Jurusan
            </button>
            <a href="{{ route('bk.jurusan') }}" class="flex-1 bg-gray-400 text-white font-bold py-3 px-4 rounded-lg hover:bg-gray-500 transition text-center">
                Batal
            </a>
        </div>
    </form>
@endsection

@section('scripts')
<script>
    function updateCharCount(textareaName) {
        const textarea = document.querySelector(`textarea[name="${textareaName}"]`);
        const countSpan = document.getElementById('descCount');
        const maxLength = parseInt(textarea.maxLength);
        countSpan.textContent = maxLength > 0 ? `${textarea.value.length}/${maxLength}` : `${textarea.value.length}`;
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

    document.addEventListener('DOMContentLoaded', function() {
        validateJurusanForm();
        updateCharCount('deskripsi');
    });
</script>
@endsection
