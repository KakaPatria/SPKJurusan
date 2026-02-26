<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Detail Alumni - {{ $alumnus->nama_alumni }}</title>
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
    <style>
        .gradient-maroon {
            background: linear-gradient(135deg, #5B7B89 0%, #7B9BA5 100%);
        }
        .text-maroon {
            color: #5B7B89;
        }
        .bg-cream {
            background-color: #F8FAFC;
        }
    </style>
</head>
<body class="bg-cream">
    <!-- Header -->
    <header class="gradient-maroon text-white shadow-lg sticky top-0 z-50">
        <div class="container mx-auto px-4 sm:px-6 py-4 sm:py-6 flex justify-between items-center">
            <div>
                <h1 class="text-xl sm:text-2xl md:text-3xl font-bold">Detail Alumni</h1>
                <p class="text-xs sm:text-sm text-yellow-300 font-semibold mt-1">{{ $alumnus->nama_alumni }}</p>
            </div>
            <a href="{{ route('alumni.index') }}" class="bg-yellow-400 text-maroon font-bold py-2 px-4 rounded-lg hover:bg-yellow-300 transition text-xs sm:text-sm">
                ← Kembali
            </a>
        </div>
    </header>

    <!-- Main Content -->
    <div class="container mx-auto px-4 sm:px-6 py-6 sm:py-12">
        <div class="max-w-4xl mx-auto">
            <!-- Profile Card -->
            <div class="bg-white rounded-lg shadow-lg p-6 mb-6 border-l-4 border-maroon">
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div>
                        <p class="text-gray-600 text-sm">Nama</p>
                        <p class="text-xl font-bold text-maroon">{{ $alumnus->nama_alumni }}</p>
                    </div>
                    <div>
                        <p class="text-gray-600 text-sm">NIS</p>
                        <p class="text-lg font-semibold text-gray-800">{{ $alumnus->nis ?? '-' }}</p>
                    </div>
                    <div>
                        <p class="text-gray-600 text-sm">Kelompok Asal</p>
                        <p class="text-lg font-semibold">
                            <span class="px-3 py-1 rounded text-sm font-bold" style="{{ $alumnus->kelompok_asal == 'IPA' ? 'background-color: #E0F2FE; color: #0369A1;' : 'background-color: #FEF3C7; color: #92400E;' }}">
                                {{ $alumnus->kelompok_asal }}
                            </span>
                        </p>
                    </div>
                    <div>
                        <p class="text-gray-600 text-sm">Tahun Masuk</p>
                        <p class="text-lg font-semibold text-gray-800">{{ $alumnus->tahun_masuk }}</p>
                    </div>
                </div>
            </div>

            <!-- Nilai & Variabel Input -->
            <div class="bg-white rounded-lg shadow p-6 mb-6 border-l-4 border-yellow-400">
                <h3 class="text-lg font-bold text-maroon mb-4">📊 Input Data Saat Entry</h3>
                <div class="grid grid-cols-2 md:grid-cols-3 gap-4">
                    @if($alumnus->mtk)
                        <div>
                            <p class="text-gray-600 text-sm">Matematika</p>
                            <p class="text-lg font-bold text-maroon">{{ $alumnus->mtk }}</p>
                        </div>
                    @endif
                    @if($alumnus->fisika)
                        <div>
                            <p class="text-gray-600 text-sm">Fisika</p>
                            <p class="text-lg font-bold">{{ $alumnus->fisika }}</p>
                        </div>
                    @endif
                    @if($alumnus->kimia)
                        <div>
                            <p class="text-gray-600 text-sm">Kimia</p>
                            <p class="text-lg font-bold">{{ $alumnus->kimia }}</p>
                        </div>
                    @endif
                    @if($alumnus->biologi)
                        <div>
                            <p class="text-gray-600 text-sm">Biologi</p>
                            <p class="text-lg font-bold">{{ $alumnus->biologi }}</p>
                        </div>
                    @endif
                    @if($alumnus->ekonomi)
                        <div>
                            <p class="text-gray-600 text-sm">Ekonomi</p>
                            <p class="text-lg font-bold">{{ $alumnus->ekonomi }}</p>
                        </div>
                    @endif
                    @if($alumnus->geografi)
                        <div>
                            <p class="text-gray-600 text-sm">Geografi</p>
                            <p class="text-lg font-bold">{{ $alumnus->geografi }}</p>
                        </div>
                    @endif
                    @if($alumnus->sosiologi)
                        <div>
                            <p class="text-gray-600 text-sm">Sosiologi</p>
                            <p class="text-lg font-bold">{{ $alumnus->sosiologi }}</p>
                        </div>
                    @endif
                    @if($alumnus->sejarah)
                        <div>
                            <p class="text-gray-600 text-sm">Sejarah</p>
                            <p class="text-lg font-bold">{{ $alumnus->sejarah }}</p>
                        </div>
                    @endif
                </div>
                <div class="mt-4 pt-4 border-t">
                    <p class="text-gray-600 text-sm">Nilai Rata-rata</p>
                    <p class="text-2xl font-bold text-maroon">{{ $alumnus->nilai_rata_rata ? number_format($alumnus->nilai_rata_rata, 2) : '-' }}</p>
                </div>
            </div>

            <!-- Non-Akademik -->
            <div class="bg-white rounded-lg shadow p-6 mb-6 border-l-4 border-blue-400">
                <h3 class="text-lg font-bold text-maroon mb-4">💡 Variabel Non-Akademik</h3>
                <div class="space-y-3">
                    <div>
                        <p class="text-gray-600 text-sm">Minat</p>
                        <p class="text-gray-800 font-semibold">{{ $alumnus->minat ?? '-' }}</p>
                    </div>
                    <div>
                        <p class="text-gray-600 text-sm">Cita-cita</p>
                        <p class="text-gray-800 font-semibold">{{ $alumnus->cita_cita ?? '-' }}</p>
                    </div>
                    <div>
                        <p class="text-gray-600 text-sm">Preferensi Studi</p>
                        <p class="text-gray-800 font-semibold">{{ $alumnus->preferensi_studi ?? '-' }}</p>
                    </div>
                    <div>
                        <p class="text-gray-600 text-sm">Prestasi</p>
                        <p class="text-gray-800 font-semibold">{{ $alumnus->prestasi ?? '-' }}</p>
                    </div>
                </div>
            </div>

            <!-- Hasil & Outcome -->
            <div class="bg-white rounded-lg shadow p-6 mb-6 border-l-4 border-green-400">
                <h3 class="text-lg font-bold text-maroon mb-4">🎯 Hasil Rekomendasi & Outcome</h3>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-4">
                    <div>
                        <p class="text-gray-600 text-sm">Jurusan Masuk</p>
                        <p class="text-lg font-bold text-maroon">{{ $alumnus->major_masuk }}</p>
                    </div>
                    <div>
                        <p class="text-gray-600 text-sm">Ranking Saat Rekomendasi</p>
                        @if($alumnus->ranking_saat_rekomendasi)
                            <p class="text-lg font-bold"><span class="px-3 py-1 rounded bg-blue-100 text-blue-800">{{ $alumnus->ranking_saat_rekomendasi }} / 9</span></p>
                        @else
                            <p class="text-gray-500">-</p>
                        @endif
                    </div>
                </div>
                <div>
                    <p class="text-gray-600 text-sm">Score Prediksi</p>
                    <p class="text-lg font-bold">{{ $alumnus->predicted_score ? round($alumnus->predicted_score * 100) . '%' : '-' }}</p>
                </div>
            </div>

            <!-- Outcome Alumni -->
            <div class="bg-white rounded-lg shadow p-6 mb-6 border-l-4 border-purple-400">
                <h3 class="text-lg font-bold text-maroon mb-4">🎓 Outcome Alumni</h3>
                <div class="space-y-3">
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div>
                            <p class="text-gray-600 text-sm">IPK Lulus</p>
                            <p class="text-lg font-bold text-maroon">{{ $alumnus->ipk_lulus ?? '-' }}</p>
                        </div>
                        <div>
                            <p class="text-gray-600 text-sm">Tahun Lulus</p>
                            <p class="text-lg font-bold">{{ $alumnus->tahun_lulus ?? '-' }}</p>
                        </div>
                    </div>
                    <div>
                        <p class="text-gray-600 text-sm">Status Kesuksesan</p>
                        @if($alumnus->success_status)
                            @switch($alumnus->success_status)
                                @case('sangat_sukses')
                                    <span class="inline-block px-3 py-1 rounded bg-green-100 text-green-800 font-bold">✓ Sangat Sukses</span>
                                    @break
                                @case('sukses')
                                    <span class="inline-block px-3 py-1 rounded bg-green-50 text-green-700">✓ Sukses</span>
                                    @break
                                @case('cukup')
                                    <span class="inline-block px-3 py-1 rounded bg-yellow-100 text-yellow-800">• Cukup</span>
                                    @break
                                @case('kurang_sukses')
                                    <span class="inline-block px-3 py-1 rounded bg-red-100 text-red-800">✗ Kurang Sukses</span>
                                    @break
                            @endswitch
                        @else
                            <p class="text-gray-500">-</p>
                        @endif
                    </div>
                    <div>
                        <p class="text-gray-600 text-sm">Karir Outcome</p>
                        <p class="text-gray-800">{{ $alumnus->karir_outcome ?? '-' }}</p>
                    </div>
                    <div>
                        <p class="text-gray-600 text-sm">Catatan</p>
                        <p class="text-gray-800">{{ $alumnus->catatan ?? '-' }}</p>
                    </div>
                </div>
            </div>

            <!-- Action Buttons -->
            <div class="flex gap-4 justify-end">
                <a href="{{ route('alumni.edit', $alumnus->id) }}" class="px-6 py-2 rounded-lg font-bold bg-yellow-400 text-maroon hover:bg-yellow-300 transition">
                    ✏ Edit
                </a>
                <form action="{{ route('alumni.destroy', $alumnus->id) }}" method="POST" class="inline" onsubmit="return confirm('Yakin hapus alumni ini?')">
                    @csrf @method('DELETE')
                    <button type="submit" class="px-6 py-2 rounded-lg font-bold bg-red-500 text-white hover:bg-red-600 transition">
                        🗑 Hapus
                    </button>
                </form>
            </div>
        </div>
    </div>
</body>
</html>
