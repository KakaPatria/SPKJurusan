@extends('admin.layouts.app')

@section('title', 'Edit Alumni')

@section('content')
    <div class="mb-6">
        <h2 class="text-2xl font-bold text-maroon">✏ Edit Alumni</h2>
        <p class="text-sm text-gray-500 mt-1">{{ $alumni->nama_alumni }}</p>
    </div>

    @if($errors->any())
        <div class="bg-red-50 border-l-4 border-red-400 p-4 rounded-lg mb-6">
            <p class="text-red-800 text-sm font-bold mb-2">❌ Validasi gagal:</p>
            <ul class="list-disc pl-5 text-sm text-red-700">
                @foreach($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <form action="{{ route('admin.alumni.update', $alumni->id) }}" method="POST" class="max-w-2xl">
        @csrf @method('PUT')

        <!-- Data Dasar -->
        <div class="bg-white rounded-lg shadow p-6 mb-6 border-l-4 border-maroon">
            <h3 class="text-lg font-bold text-maroon mb-4">📋 Data Dasar</h3>
            
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-4">
                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-2">Nama Alumni *</label>
                    <input type="text" name="nama_alumni" required class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:border-maroon text-sm" 
                        value="{{ old('nama_alumni', $alumni->nama_alumni) }}" placeholder="Nama lengkap">
                </div>
                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-2">NIS</label>
                    <input type="text" name="nis" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:border-maroon text-sm" 
                        value="{{ old('nis', $alumni->nis) }}" placeholder="NIS SMA">
                </div>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-2">Kelompok Asal *</label>
                    <select name="kelompok_asal" required class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:border-maroon text-sm">
                        <option value="">-- Pilih --</option>
                        <option value="IPA" {{ old('kelompok_asal', $alumni->kelompok_asal) == 'IPA' ? 'selected' : '' }}>IPA</option>
                        <option value="IPS" {{ old('kelompok_asal', $alumni->kelompok_asal) == 'IPS' ? 'selected' : '' }}>IPS</option>
                    </select>
                </div>
                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-2">Minat / Bidang Studi</label>
                    <input type="text" name="minat" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:border-maroon text-sm" 
                        value="{{ old('minat', $alumni->minat) }}" placeholder="Minat siswa">
                </div>
            </div>
        </div>

        <!-- Nilai Entry -->
        <div class="bg-white rounded-lg shadow p-6 mb-6 border-l-4 border-yellow-400">
            <h3 class="text-lg font-bold text-maroon mb-4">📊 Nilai Saat Entry (Rapor SMA)</h3>
            
            <div class="grid grid-cols-2 md:grid-cols-3 gap-3">
                <div>
                    <label class="block text-xs font-semibold text-gray-700 mb-1">Matematika</label>
                    <input type="number" name="mtk" step="0.01" min="0" max="100" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:border-maroon text-sm" 
                        value="{{ old('mtk', $alumni->mtk) }}" placeholder="0-100">
                </div>
                <div>
                    <label class="block text-xs font-semibold text-gray-700 mb-1">Fisika</label>
                    <input type="number" name="fisika" step="0.01" min="0" max="100" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:border-maroon text-sm" 
                        value="{{ old('fisika', $alumni->fisika) }}" placeholder="0-100">
                </div>
                <div>
                    <label class="block text-xs font-semibold text-gray-700 mb-1">Kimia</label>
                    <input type="number" name="kimia" step="0.01" min="0" max="100" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:border-maroon text-sm" 
                        value="{{ old('kimia', $alumni->kimia) }}" placeholder="0-100">
                </div>
                <div>
                    <label class="block text-xs font-semibold text-gray-700 mb-1">Biologi</label>
                    <input type="number" name="biologi" step="0.01" min="0" max="100" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:border-maroon text-sm" 
                        value="{{ old('biologi', $alumni->biologi) }}" placeholder="0-100">
                </div>
                <div>
                    <label class="block text-xs font-semibold text-gray-700 mb-1">Ekonomi</label>
                    <input type="number" name="ekonomi" step="0.01" min="0" max="100" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:border-maroon text-sm" 
                        value="{{ old('ekonomi', $alumni->ekonomi) }}" placeholder="0-100">
                </div>
                <div>
                    <label class="block text-xs font-semibold text-gray-700 mb-1">Geografi</label>
                    <input type="number" name="geografi" step="0.01" min="0" max="100" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:border-maroon text-sm" 
                        value="{{ old('geografi', $alumni->geografi) }}" placeholder="0-100">
                </div>
            </div>
        </div>

        <!-- Hasil -->
        <div class="bg-white rounded-lg shadow p-6 mb-6 border-l-4 border-green-400">
            <h3 class="text-lg font-bold text-maroon mb-4">🎯 Hasil / Outcome</h3>
            
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-4">
                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-2">Jurusan Masuk ke Polije *</label>
                    <input type="text" name="major_masuk" required class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:border-maroon text-sm" 
                        value="{{ old('major_masuk', $alumni->major_masuk) }}" placeholder="Jurusan Polije">
                </div>
                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-2">Tahun Lulus Polije</label>
                    <input type="number" name="tahun_lulus_polije" min="2020" max="{{ date('Y') }}" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:border-maroon text-sm" 
                        value="{{ old('tahun_lulus_polije', $alumni->tahun_lulus_polije) }}" placeholder="Tahun lulus">
                </div>
            </div>

            <div>
                <label class="block text-sm font-semibold text-gray-700 mb-2">Catatan</label>
                <textarea name="catatan" rows="3" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:border-maroon text-sm" 
                    placeholder="Catatan tambahan (opsional)">{{ old('catatan', $alumni->catatan) }}</textarea>
            </div>
        </div>

        <!-- Buttons -->
        <div class="flex gap-4 justify-end">
            <a href="{{ route('admin.alumni.index') }}" class="px-6 py-2 rounded-lg font-bold bg-gray-300 text-gray-700 hover:bg-gray-400 transition">
                Batal
            </a>
            <button type="submit" class="px-6 py-2 rounded-lg font-bold gradient-maroon text-white hover:opacity-90 transition">
                💾 Update
            </button>
        </div>
    </form>
@endsection
