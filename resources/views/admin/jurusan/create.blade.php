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

    @if($errors->any())
        <div class="bg-red-50 border-l-4 border-red-400 p-4 rounded-lg mb-6">
            <ul class="text-red-800 text-sm list-disc list-inside">
                @foreach($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <form action="{{ route('admin.jurusan.store') }}" method="POST" class="space-y-6">
        @csrf

        <div class="bg-white rounded-lg shadow p-6">
            <h3 class="text-lg font-bold text-maroon mb-4">📋 Informasi Jurusan</h3>
            <div class="space-y-4">
                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-2">Nama Jurusan <span class="text-red-500">*</span></label>
                    <input type="text" name="nama_jurusan" value="{{ old('nama_jurusan') }}" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:border-maroon" placeholder="Contoh: Teknologi Informasi" required>
                </div>

                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-2">Deskripsi</label>
                    <textarea name="deskripsi" rows="3" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:border-maroon" placeholder="Jelaskan singkat tentang jurusan ini">{{ old('deskripsi') }}</textarea>
                </div>

                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-2">Keywords (Kata Kunci Minat & Cita-cita)</label>
                    <textarea name="keywords" rows="3" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:border-maroon" placeholder="Pisahkan dengan koma, contoh: programmer, developer, coding, software, web">{{ old('keywords') }}</textarea>
                    <p class="text-xs text-gray-500 mt-1">Keywords digunakan untuk mencocokkan minat dan cita-cita siswa dengan jurusan ini. Pisahkan dengan koma.</p>
                </div>

                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-2">Preferensi Studi</label>
                    <textarea name="preferensi_studi" rows="2" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:border-maroon" placeholder="Pisahkan dengan koma, contoh: Sains & Teknologi, Pertanian & Lingkungan">{{ old('preferensi_studi') }}</textarea>
                    <p class="text-xs text-gray-500 mt-1">Rumpun bidang studi yang cocok untuk jurusan ini. Pilihan: Sains & Teknologi, Pertanian & Lingkungan, Kesehatan & Ilmu Hayat, Bisnis & Manajemen, Sosial & Humaniora</p>
                </div>

                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-2">Prospek Kerja</label>
                    <textarea name="prospek_kerja" rows="2" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:border-maroon" placeholder="Contoh: Software Developer, Web Developer, Data Analyst">{{ old('prospek_kerja') }}</textarea>
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
                            <input type="number" name="bobot_mtk" value="{{ old('bobot_mtk', '0.25') }}" step="0.05" min="0" max="1" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:border-maroon text-sm">
                        </div>
                        <div>
                            <label class="block text-xs font-semibold text-gray-600 mb-1">Fisika</label>
                            <input type="number" name="bobot_fisika" value="{{ old('bobot_fisika', '0.25') }}" step="0.05" min="0" max="1" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:border-maroon text-sm">
                        </div>
                        <div>
                            <label class="block text-xs font-semibold text-gray-600 mb-1">Kimia</label>
                            <input type="number" name="bobot_kimia" value="{{ old('bobot_kimia', '0.25') }}" step="0.05" min="0" max="1" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:border-maroon text-sm">
                        </div>
                        <div>
                            <label class="block text-xs font-semibold text-gray-600 mb-1">Biologi</label>
                            <input type="number" name="bobot_biologi" value="{{ old('bobot_biologi', '0.25') }}" step="0.05" min="0" max="1" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:border-maroon text-sm">
                        </div>
                    </div>
                </div>
                <div>
                    <h4 class="text-sm font-bold text-gray-700 mb-3 border-b pb-2">📊 IPS</h4>
                    <div class="space-y-3">
                        <div>
                            <label class="block text-xs font-semibold text-gray-600 mb-1">Ekonomi</label>
                            <input type="number" name="bobot_ekonomi" value="{{ old('bobot_ekonomi', '0.25') }}" step="0.05" min="0" max="1" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:border-maroon text-sm">
                        </div>
                        <div>
                            <label class="block text-xs font-semibold text-gray-600 mb-1">Geografi</label>
                            <input type="number" name="bobot_geografi" value="{{ old('bobot_geografi', '0.25') }}" step="0.05" min="0" max="1" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:border-maroon text-sm">
                        </div>
                        <div>
                            <label class="block text-xs font-semibold text-gray-600 mb-1">Sosiologi</label>
                            <input type="number" name="bobot_sosiologi" value="{{ old('bobot_sosiologi', '0.25') }}" step="0.05" min="0" max="1" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:border-maroon text-sm">
                        </div>
                        <div>
                            <label class="block text-xs font-semibold text-gray-600 mb-1">Sejarah</label>
                            <input type="number" name="bobot_sejarah" value="{{ old('bobot_sejarah', '0.25') }}" step="0.05" min="0" max="1" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:border-maroon text-sm">
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="flex gap-4">
            <button type="submit" class="flex-1 gradient-maroon text-white font-bold py-3 px-4 rounded-lg hover:opacity-90 transition">
                💾 Simpan Jurusan
            </button>
            <a href="{{ route('admin.jurusan') }}" class="flex-1 bg-gray-400 text-white font-bold py-3 px-4 rounded-lg hover:bg-gray-500 transition text-center">
                Batal
            </a>
        </div>
    </form>
@endsection
