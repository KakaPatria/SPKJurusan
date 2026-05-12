@extends('admin.layouts.app')

@section('title', 'Detail Alumni')

@section('content')
    <div class="mb-6 flex justify-between items-start">
        <div>
            <h2 class="text-2xl font-bold text-maroon">👁 Detail Alumni</h2>
            <p class="text-sm text-gray-500 mt-1">{{ $alumni->nama_alumni }}</p>
        </div>
        <a href="{{ route('admin.alumni.index') }}" class="px-4 py-2 rounded-lg font-bold bg-gray-300 text-gray-700 hover:bg-gray-400 transition">
            ← Kembali
        </a>
    </div>

    <!-- Data Dasar -->
    <div class="bg-white rounded-lg shadow p-6 mb-6 border-l-4 border-maroon">
        <h3 class="text-lg font-bold text-maroon mb-4">📋 Data Dasar</h3>
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            <div>
                <p class="text-xs text-gray-600 font-semibold">Nama Alumni</p>
                <p class="text-lg font-bold text-gray-800">{{ $alumni->nama_alumni }}</p>
            </div>
            <div>
                <p class="text-xs text-gray-600 font-semibold">NIS</p>
                <p class="text-lg font-bold text-gray-800">{{ $alumni->nis ?? '-' }}</p>
            </div>
            <div>
                <p class="text-xs text-gray-600 font-semibold">Kelompok Asal</p>
                <span class="inline-block px-3 py-1 rounded text-sm font-bold" 
                    style="{{ $alumni->kelompok_asal == 'IPA' ? 'background-color: #E0F2FE; color: #0369A1;' : 'background-color: #FEF3C7; color: #92400E;' }}">
                    {{ $alumni->kelompok_asal }}
                </span>
            </div>
            <div>
                <p class="text-xs text-gray-600 font-semibold">Minat</p>
                <p class="text-gray-800">{{ $alumni->minat ?? '-' }}</p>
            </div>
        </div>
    </div>

    <!-- Nilai Entry -->
    <div class="bg-white rounded-lg shadow p-6 mb-6 border-l-4 border-yellow-400">
        <h3 class="text-lg font-bold text-maroon mb-4">📊 Nilai Saat Entry (Rapor SMA)</h3>
        <div class="grid grid-cols-2 md:grid-cols-3 gap-4">
            @if($alumni->kelompok_asal === 'IPA')
                @if($alumni->mtk)
                    <div class="p-3 bg-blue-50 rounded-lg">
                        <p class="text-xs text-gray-600">Matematika</p>
                        <p class="text-xl font-bold text-blue-600">{{ $alumni->mtk }}</p>
                    </div>
                @endif
                @if($alumni->fisika)
                    <div class="p-3 bg-green-50 rounded-lg">
                        <p class="text-xs text-gray-600">Fisika</p>
                        <p class="text-xl font-bold text-green-600">{{ $alumni->fisika }}</p>
                    </div>
                @endif
                @if($alumni->kimia)
                    <div class="p-3 bg-purple-50 rounded-lg">
                        <p class="text-xs text-gray-600">Kimia</p>
                        <p class="text-xl font-bold text-purple-600">{{ $alumni->kimia }}</p>
                    </div>
                @endif
                @if($alumni->biologi)
                    <div class="p-3 bg-red-50 rounded-lg">
                        <p class="text-xs text-gray-600">Biologi</p>
                        <p class="text-xl font-bold text-red-600">{{ $alumni->biologi }}</p>
                    </div>
                @endif
            @else
                @if($alumni->ekonomi)
                    <div class="p-3 bg-orange-50 rounded-lg">
                        <p class="text-xs text-gray-600">Ekonomi</p>
                        <p class="text-xl font-bold text-orange-600">{{ $alumni->ekonomi }}</p>
                    </div>
                @endif
                @if($alumni->geografi)
                    <div class="p-3 bg-indigo-50 rounded-lg">
                        <p class="text-xs text-gray-600">Geografi</p>
                        <p class="text-xl font-bold text-indigo-600">{{ $alumni->geografi }}</p>
                    </div>
                @endif
                @if($alumni->sosiologi)
                    <div class="p-3 bg-teal-50 rounded-lg">
                        <p class="text-xs text-gray-600">Sosiologi</p>
                        <p class="text-xl font-bold text-teal-600">{{ $alumni->sosiologi }}</p>
                    </div>
                @endif
                @if($alumni->sejarah)
                    <div class="p-3 bg-amber-50 rounded-lg">
                        <p class="text-xs text-gray-600">Sejarah</p>
                        <p class="text-xl font-bold text-amber-600">{{ $alumni->sejarah }}</p>
                    </div>
                @endif
            @endif
        </div>
    </div>

    <!-- Hasil -->
    <div class="bg-white rounded-lg shadow p-6 mb-6 border-l-4 border-green-400">
        <h3 class="text-lg font-bold text-maroon mb-4">🎯 Hasil / Outcome</h3>
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            <div>
                <p class="text-xs text-gray-600 font-semibold">Jurusan Masuk Polije</p>
                <p class="text-lg font-bold text-gray-800">{{ $alumni->major_masuk }}</p>
            </div>
            <div>
                <p class="text-xs text-gray-600 font-semibold">Tahun Lulus SMA Bima Ambulu</p>
                <p class="text-lg font-bold text-gray-800">{{ $alumni->tahun_lulus_polije ?? '-' }}</p>
            </div>
            @if($alumni->catatan)
                <div class="md:col-span-2">
                    <p class="text-xs text-gray-600 font-semibold">Catatan</p>
                    <p class="text-gray-800">{{ $alumni->catatan }}</p>
                </div>
            @endif
        </div>
    </div>

    <!-- Action Buttons -->
    <div class="flex gap-4 justify-end">
        <a href="{{ route('admin.alumni.edit', $alumni->id) }}" class="px-6 py-2 rounded-lg font-bold bg-yellow-400 text-maroon hover:bg-yellow-300 transition">
            ✏ Edit
        </a>
        <form action="{{ route('admin.alumni.destroy', $alumni->id) }}" method="POST" class="inline" onsubmit="return confirm('Yakin hapus data alumni ini?')">
            @csrf @method('DELETE')
            <button type="submit" class="px-6 py-2 rounded-lg font-bold bg-red-500 text-white hover:bg-red-600 transition">
                🗑 Hapus
            </button>
        </form>
    </div>
@endsection
