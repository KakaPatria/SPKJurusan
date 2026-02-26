@extends('admin.layouts.app')

@section('title', 'Edit Jurusan')

@section('content')
    <div class="flex justify-between items-center mb-6">
        <div>
            <h2 class="text-2xl font-bold text-maroon">✏️ Edit Jurusan: {{ $jurusan->nama_jurusan }}</h2>
            <p class="text-sm text-gray-500 mt-1">Ubah informasi jurusan</p>
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

    <form action="{{ route('admin.jurusan.update', $jurusan->id) }}" method="POST" class="space-y-6">
        @csrf
        @method('PUT')

        <div class="bg-white rounded-lg shadow p-6">
            <h3 class="text-lg font-bold text-maroon mb-4">📋 Informasi Jurusan</h3>
            <div class="space-y-4">
                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-2">Nama Jurusan <span class="text-red-500">*</span></label>
                    <input type="text" name="nama_jurusan" value="{{ old('nama_jurusan', $jurusan->nama_jurusan) }}" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:border-maroon" required>
                </div>

                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-2">Deskripsi</label>
                    <textarea name="deskripsi" rows="3" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:border-maroon" placeholder="Jelaskan singkat tentang jurusan ini">{{ old('deskripsi', $jurusan->deskripsi) }}</textarea>
                </div>

                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-2">Keywords (Kata Kunci Minat & Cita-cita)</label>
                    <textarea name="keywords" rows="3" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:border-maroon" placeholder="Pisahkan dengan koma">{{ old('keywords', implode(', ', $jurusan->keywords ?? [])) }}</textarea>
                    <p class="text-xs text-gray-500 mt-1">Keywords digunakan untuk mencocokkan minat dan cita-cita siswa dengan jurusan ini. Pisahkan dengan koma.</p>
                    @if(!empty($jurusan->keywords))
                        <div class="flex flex-wrap gap-1 mt-2">
                            @foreach($jurusan->keywords as $kw)
                                <span class="inline-block px-2 py-0.5 rounded bg-blue-100 text-blue-700 text-xs">{{ $kw }}</span>
                            @endforeach
                        </div>
                    @endif
                </div>

                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-2">Preferensi Studi</label>
                    <textarea name="preferensi_studi" rows="2" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:border-maroon" placeholder="Pisahkan dengan koma">{{ old('preferensi_studi', implode(', ', $jurusan->preferensi_studi ?? [])) }}</textarea>
                    <p class="text-xs text-gray-500 mt-1">Rumpun bidang studi yang cocok: Sains & Teknologi, Pertanian & Lingkungan, Kesehatan & Ilmu Hayat, Bisnis & Manajemen, Sosial & Humaniora</p>
                </div>

                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-2">Prospek Kerja</label>
                    <textarea name="prospek_kerja" rows="2" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:border-maroon" placeholder="Contoh: Software Developer, Web Developer, Data Analyst">{{ old('prospek_kerja', $jurusan->prospek_kerja) }}</textarea>
                </div>
            </div>
        </div>

        <!-- Bobot Mata Pelajaran -->
        @php
            $bobot = $jurusan->bobot_mapel ?? [];
        @endphp
        <div class="bg-white rounded-lg shadow p-6">
            <h3 class="text-lg font-bold text-maroon mb-4">⚖️ Bobot Mata Pelajaran</h3>
            <p class="text-xs text-gray-500 mb-4">Tentukan bobot setiap mata pelajaran untuk jurusan ini (0.00 - 1.00). Mata pelajaran yang lebih relevan diberi bobot lebih tinggi. Jumlah total tidak harus 1.0.</p>
            
            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                <div>
                    <h4 class="text-sm font-bold text-gray-700 mb-3 border-b pb-2">📐 IPA</h4>
                    <div class="space-y-3">
                        <div>
                            <label class="block text-xs font-semibold text-gray-600 mb-1">Matematika</label>
                            <input type="number" name="bobot_mtk" value="{{ old('bobot_mtk', $bobot['mtk'] ?? '0.25') }}" step="0.05" min="0" max="1" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:border-maroon text-sm">
                        </div>
                        <div>
                            <label class="block text-xs font-semibold text-gray-600 mb-1">Fisika</label>
                            <input type="number" name="bobot_fisika" value="{{ old('bobot_fisika', $bobot['fisika'] ?? '0.25') }}" step="0.05" min="0" max="1" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:border-maroon text-sm">
                        </div>
                        <div>
                            <label class="block text-xs font-semibold text-gray-600 mb-1">Kimia</label>
                            <input type="number" name="bobot_kimia" value="{{ old('bobot_kimia', $bobot['kimia'] ?? '0.25') }}" step="0.05" min="0" max="1" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:border-maroon text-sm">
                        </div>
                        <div>
                            <label class="block text-xs font-semibold text-gray-600 mb-1">Biologi</label>
                            <input type="number" name="bobot_biologi" value="{{ old('bobot_biologi', $bobot['biologi'] ?? '0.25') }}" step="0.05" min="0" max="1" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:border-maroon text-sm">
                        </div>
                    </div>
                </div>
                <div>
                    <h4 class="text-sm font-bold text-gray-700 mb-3 border-b pb-2">📊 IPS</h4>
                    <div class="space-y-3">
                        <div>
                            <label class="block text-xs font-semibold text-gray-600 mb-1">Ekonomi</label>
                            <input type="number" name="bobot_ekonomi" value="{{ old('bobot_ekonomi', $bobot['ekonomi'] ?? '0.25') }}" step="0.05" min="0" max="1" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:border-maroon text-sm">
                        </div>
                        <div>
                            <label class="block text-xs font-semibold text-gray-600 mb-1">Geografi</label>
                            <input type="number" name="bobot_geografi" value="{{ old('bobot_geografi', $bobot['geografi'] ?? '0.25') }}" step="0.05" min="0" max="1" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:border-maroon text-sm">
                        </div>
                        <div>
                            <label class="block text-xs font-semibold text-gray-600 mb-1">Sosiologi</label>
                            <input type="number" name="bobot_sosiologi" value="{{ old('bobot_sosiologi', $bobot['sosiologi'] ?? '0.25') }}" step="0.05" min="0" max="1" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:border-maroon text-sm">
                        </div>
                        <div>
                            <label class="block text-xs font-semibold text-gray-600 mb-1">Sejarah</label>
                            <input type="number" name="bobot_sejarah" value="{{ old('bobot_sejarah', $bobot['sejarah'] ?? '0.25') }}" step="0.05" min="0" max="1" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:border-maroon text-sm">
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="flex gap-4">
            <button type="submit" class="flex-1 gradient-maroon text-white font-bold py-3 px-4 rounded-lg hover:opacity-90 transition">
                💾 Simpan Perubahan
            </button>
            <a href="{{ route('admin.jurusan') }}" class="flex-1 bg-gray-400 text-white font-bold py-3 px-4 rounded-lg hover:bg-gray-500 transition text-center">
                Batal
            </a>
        </div>
    </form>
@endsection
