@extends('admin.layouts.app')

@section('title', 'Tambah Jurusan')

@section('content')
    <div class="flex justify-between items-center mb-6">
        <div>
            <h2 class="text-2xl font-bold text-maroon">➕ Tambah Jurusan Baru</h2>
            <p class="text-sm text-gray-500 mt-1">Tambahkan jurusan baru ke dalam sistem rekomendasi</p>
        </div>
        <a href="{{ route('admin.jurusan') }}" class="bg-gray-400 text-white font-bold py-2 px-4 rounded-lg hover:bg-gray-500 transition text-sm">
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

    <form action="{{ route('admin.jurusan.store') }}" method="POST" class="space-y-6" id="jurusanForm">
        @csrf

        <div class="bg-white rounded-lg shadow p-6">
            <h3 class="text-lg font-bold text-maroon mb-4">📋 Informasi Jurusan</h3>
            <div class="space-y-4">
                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-2">Nama Jurusan <span class="text-red-500">*</span> <span class="text-gray-400 text-xs">(minimal 3 karakter)</span></label>
                    <input type="text" name="nama_jurusan" value="{{ old('nama_jurusan') }}" class="w-full px-4 py-2 border rounded-lg focus:outline-none focus:ring-2 transition @error('nama_jurusan') border-red-500 focus:ring-red-400 @else border-gray-300 focus:ring-maroon @enderror" placeholder="Contoh: Teknologi Informasi" required minlength="3" maxlength="100" oninput="validateJurusanForm()">
                    <div class="flex justify-between items-center mt-1">
                        <span id="namaError" class="text-red-500 text-xs hidden">⚠️ Nama minimal 3 karakter</span>
                        <span id="namaValid" class="text-green-500 text-xs hidden">✓ Nama valid</span>
                    </div>
                    @error('nama_jurusan') <span class="text-red-500 text-xs block mt-1">{{ $message }}</span> @enderror
                </div>

                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-2">Deskripsi <span class="text-gray-400 text-xs">(disarankan detail untuk kebutuhan chatbot)</span></label>
                    <textarea name="deskripsi" rows="3" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-maroon" placeholder="Jelaskan detail tentang jurusan ini" oninput="updateCharCount()">{{ old('deskripsi') }}</textarea>
                    <div class="flex justify-between items-center mt-1">
                        <span class="text-gray-400 text-xs">Karakter:</span>
                        <span id="charCount" class="text-gray-600 text-xs font-medium">0</span>
                    </div>
                </div>

                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-2">Keywords (Kata Kunci Minat & Cita-cita)</label>
                    <textarea name="keywords" rows="3" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-maroon" placeholder="Pisahkan dengan koma, contoh: programmer, developer, coding, software, web" maxlength="500" oninput="updateCharCount('keywordsCount')">{{ old('keywords') }}</textarea>
                    <div class="flex justify-between items-center mt-1">
                        <p class="text-xs text-gray-500">Keywords digunakan untuk mencocokkan minat dan cita-cita siswa dengan jurusan ini. Pisahkan dengan koma.</p>
                        <span id="keywordsCount" class="text-gray-600 text-xs font-medium">0/500</span>
                    </div>
                </div>

                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-2">Preferensi Studi</label>
                    <textarea name="preferensi_studi" rows="2" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-maroon" placeholder="Pisahkan dengan koma, contoh: Praktik Langsung, DuDi, Project Based, Blended Learning" maxlength="500" oninput="updateCharCount('preferensiCount')">{{ old('preferensi_studi') }}</textarea>
                    <div class="flex justify-between items-center mt-1">
                        <p class="text-xs text-gray-500">Pilihan preferensi studi: Praktik Langsung, DuDi, Project Based, Blended Learning</p>
                        <span id="preferensiCount" class="text-gray-600 text-xs font-medium">0/500</span>
                    </div>
                </div>

                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-2">Prospek Kerja</label>
                    <textarea name="prospek_kerja" rows="2" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-maroon" placeholder="Contoh: Software Developer, Web Developer, Data Analyst" maxlength="500" oninput="updateCharCount('prospekCount')">{{ old('prospek_kerja') }}</textarea>
                    <div class="flex justify-between items-center mt-1">
                        <span class="text-xs text-gray-400">Prospek kerja yang terbuka</span>
                        <span id="prospekCount" class="text-gray-600 text-xs font-medium">0/500</span>
                    </div>
                </div>
            </div>
        </div>

        <!-- Bobot Mata Pelajaran -->
        <div class="bg-white rounded-lg shadow p-6">
            <h3 class="text-lg font-bold text-maroon mb-4">⚖️ Bobot Mata Pelajaran</h3>
            <p class="text-xs text-gray-500 mb-4">Tentukan bobot setiap mata pelajaran untuk jurusan ini (0.00 - 1.00). Mata pelajaran yang lebih relevan diberi bobot lebih tinggi. Jumlah total tidak harus 1.0.</p>
            
            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                <div>
                    <h4 class="text-sm font-bold text-gray-700 mb-3 border-b pb-2">📐 IPA</h4>
                    <div class="space-y-3">
                        <div>
                            <label class="block text-xs font-semibold text-gray-600 mb-1">Matematika</label>
                            <input type="number" name="bobot_mapel[ipa][mtk]" value="{{ old('bobot_mapel.ipa.mtk', '0.25') }}" step="0.05" min="0" max="1" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:border-maroon text-sm">
                        </div>
                        <div>
                            <label class="block text-xs font-semibold text-gray-600 mb-1">Fisika</label>
                            <input type="number" name="bobot_mapel[ipa][fisika]" value="{{ old('bobot_mapel.ipa.fisika', '0.25') }}" step="0.05" min="0" max="1" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:border-maroon text-sm">
                        </div>
                        <div>
                            <label class="block text-xs font-semibold text-gray-600 mb-1">Kimia</label>
                            <input type="number" name="bobot_mapel[ipa][kimia]" value="{{ old('bobot_mapel.ipa.kimia', '0.25') }}" step="0.05" min="0" max="1" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:border-maroon text-sm">
                        </div>
                        <div>
                            <label class="block text-xs font-semibold text-gray-600 mb-1">Biologi</label>
                            <input type="number" name="bobot_mapel[ipa][biologi]" value="{{ old('bobot_mapel.ipa.biologi', '0.25') }}" step="0.05" min="0" max="1" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:border-maroon text-sm">
                        </div>
                    </div>
                </div>
                <div>
                    <h4 class="text-sm font-bold text-gray-700 mb-3 border-b pb-2">📊 IPS</h4>
                    <div class="space-y-3">
                        <div>
                            <label class="block text-xs font-semibold text-gray-600 mb-1">Ekonomi</label>
                            <input type="number" name="bobot_mapel[ips][ekonomi]" value="{{ old('bobot_mapel.ips.ekonomi', '0.25') }}" step="0.05" min="0" max="1" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:border-maroon text-sm">
                        </div>
                        <div>
                            <label class="block text-xs font-semibold text-gray-600 mb-1">Geografi</label>
                            <input type="number" name="bobot_mapel[ips][geografi]" value="{{ old('bobot_mapel.ips.geografi', '0.25') }}" step="0.05" min="0" max="1" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:border-maroon text-sm">
                        </div>
                        <div>
                            <label class="block text-xs font-semibold text-gray-600 mb-1">Sosiologi</label>
                            <input type="number" name="bobot_mapel[ips][sosiologi]" value="{{ old('bobot_mapel.ips.sosiologi', '0.25') }}" step="0.05" min="0" max="1" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:border-maroon text-sm">
                        </div>
                        <div>
                            <label class="block text-xs font-semibold text-gray-600 mb-1">Sejarah</label>
                            <input type="number" name="bobot_mapel[ips][sejarah]" value="{{ old('bobot_mapel.ips.sejarah', '0.25') }}" step="0.05" min="0" max="1" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:border-maroon text-sm">
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="flex gap-4">
            <button type="submit" class="flex-1 gradient-maroon text-white font-bold py-3 px-4 rounded-lg hover:opacity-90 transition disabled:opacity-50 disabled:cursor-not-allowed" id="submitButton">
                💾 Simpan Jurusan
            </button>
            <a href="{{ route('admin.jurusan') }}" class="flex-1 bg-gray-400 text-white font-bold py-3 px-4 rounded-lg hover:bg-gray-500 transition text-center">
                Batal
            </a>
        </div>
    </form>

    @section('scripts')
        <script>
            function updateCharCount(elementId = 'charCount') {
                const textarea = event.target;
                const count = textarea.value.length;
                const maxLength = parseInt(textarea.maxLength);
                document.getElementById(elementId).textContent = maxLength > 0 ? `${count}/${maxLength}` : `${count}`;
                validateJurusanForm();
            }

            function validateJurusanForm() {
                const nama = document.querySelector('input[name="nama_jurusan"]');
                const submitBtn = document.getElementById('submitButton');
                
                if (nama.value.trim().length >= 3) {
                    nama.classList.remove('border-red-500', 'focus:ring-red-400');
                    nama.classList.add('border-gray-300', 'focus:ring-maroon');
                    document.getElementById('namaError').classList.add('hidden');
                    document.getElementById('namaValid').classList.remove('hidden');
                } else if (nama.value.trim().length > 0) {
                    nama.classList.remove('border-gray-300', 'focus:ring-maroon');
                    nama.classList.add('border-red-500', 'focus:ring-red-400');
                    document.getElementById('namaError').classList.remove('hidden');
                    document.getElementById('namaValid').classList.add('hidden');
                } else {
                    document.getElementById('namaError').classList.add('hidden');
                    document.getElementById('namaValid').classList.add('hidden');
                }

                const isValid = nama.value.trim().length >= 3;
                submitBtn.disabled = !isValid;
            }

            document.addEventListener('DOMContentLoaded', function() {
                // Update char counts on load
                document.querySelectorAll('textarea[maxlength]').forEach(textarea => {
                    const id = textarea.name === 'deskripsi' ? 'charCount' : 
                               textarea.name === 'keywords' ? 'keywordsCount' :
                               textarea.name === 'preferensi_studi' ? 'preferensiCount' :
                               'prospekCount';
                    const count = textarea.value.length;
                    const maxLength = parseInt(textarea.maxLength);
                    if (document.getElementById(id)) {
                        document.getElementById(id).textContent = maxLength > 0 ? `${count}/${maxLength}` : `${count}`;
                    }
                });

                const desc = document.querySelector('textarea[name="deskripsi"]');
                if (desc && document.getElementById('charCount')) {
                    document.getElementById('charCount').textContent = `${desc.value.length}`;
                }
                validateJurusanForm();
            });
        </script>
    @endsection
@endsection
