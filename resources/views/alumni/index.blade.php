<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Data Alumni - Sistem Pemilihan Jurusan</title>
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
    </style>
</head>
<body class="bg-cream">
    <!-- Header -->
    <header class="gradient-maroon text-white shadow-lg sticky top-0 z-50">
        <div class="container mx-auto px-4 sm:px-6 py-4 sm:py-6">
            <div class="flex flex-col sm:flex-row sm:justify-between sm:items-center gap-3 sm:gap-4">
                <div>
                    <h1 class="text-xl sm:text-2xl md:text-3xl font-bold">Data Alumni</h1>
                    <p class="text-xs sm:text-sm text-yellow-300 font-semibold mt-1">Validasi & Analisis Bobot Algoritma</p>
                </div>
                <a href="{{ route('alumni.create') }}" class="bg-yellow-400 text-maroon font-bold py-2 px-4 rounded-lg hover:bg-yellow-300 transition text-xs sm:text-sm text-center">
                    ➕ Input Alumni Baru
                </a>
            </div>
        </div>
    </header>

    <!-- Main Content -->
    <div class="container mx-auto px-4 sm:px-6 py-6 sm:py-12">
        <!-- Success Alert -->
        @if(session('success'))
            <div class="mb-6 p-4 bg-green-100 border-l-4 border-green-500 text-green-700 rounded">
                {{ session('success') }}
            </div>
        @endif

        <!-- Summary Stats -->
        @if($summary)
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4 mb-8">
                <div class="bg-white rounded-lg shadow p-6 border-t-4 border-maroon">
                    <p class="text-gray-600 text-sm font-semibold">Total Alumni</p>
                    <p class="text-3xl font-bold text-maroon mt-2">{{ $summary['total'] }}</p>
                </div>

                @if($summary['prediction_accuracy'])
                    <div class="bg-white rounded-lg shadow p-6 border-t-4 border-yellow-400">
                        <p class="text-gray-600 text-sm font-semibold">Top-1 Accuracy</p>
                        <p class="text-3xl font-bold mt-2" style="color: #EA580C;">{{ $summary['prediction_accuracy']['top_1'] }}%</p>
                    </div>
                    <div class="bg-white rounded-lg shadow p-6 border-t-4 border-blue-400">
                        <p class="text-gray-600 text-sm font-semibold">Top-3 Accuracy</p>
                        <p class="text-3xl font-bold text-blue-600 mt-2">{{ $summary['prediction_accuracy']['top_3'] }}%</p>
                    </div>
                    <div class="bg-white rounded-lg shadow p-6 border-t-4 border-green-400">
                        <p class="text-gray-600 text-sm font-semibold">Top-5 Accuracy</p>
                        <p class="text-3xl font-bold text-green-600 mt-2">{{ $summary['prediction_accuracy']['top_5'] }}%</p>
                    </div>
                @endif
            </div>

            <!-- Distribution by Major -->
            @if($summary['by_major']->isNotEmpty())
                <div class="bg-white rounded-lg shadow p-6 mb-8 border-l-4 border-maroon">
                    <h3 class="text-lg font-bold text-maroon mb-4">Distribusi Alumni per Jurusan</h3>
                    <div class="space-y-2">
                        @foreach($summary['by_major'] as $major)
                            <div class="flex items-center gap-3">
                                <span class="text-sm font-semibold text-gray-700 w-48">{{ $major->major_masuk }}</span>
                                <div class="flex-1 h-6 bg-gray-200 rounded">
                                    <div class="h-full bg-gradient-to-r from-maroon to-yellow-400 rounded flex items-center justify-center text-white text-xs font-bold" style="width: {{ ($major->count / $summary['total']) * 100 }}%">
                                        {{ $major->count }}
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>
            @endif
        @endif

        <!-- Alumni Table -->
        <div class="bg-white rounded-lg shadow overflow-x-auto">
            <table class="w-full text-sm">
                <thead class="gradient-maroon text-white">
                    <tr>
                        <th class="px-4 py-3 text-left">Nama</th>
                        <th class="px-4 py-3 text-left">NIS</th>
                        <th class="px-4 py-3 text-center">Kelompok</th>
                        <th class="px-4 py-3 text-center">Nilai Rata</th>
                        <th class="px-4 py-3 text-left">Major</th>
                        <th class="px-4 py-3 text-center">Ranking</th>
                        <th class="px-4 py-3 text-center">Success</th>
                        <th class="px-4 py-3 text-center">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y">
                    @forelse($alumni as $a)
                        <tr class="hover:bg-gray-50 transition">
                            <td class="px-4 py-3 font-semibold text-gray-800">{{ $a->nama_alumni }}</td>
                            <td class="px-4 py-3 text-gray-600">{{ $a->nis ?? '-' }}</td>
                            <td class="px-4 py-3 text-center">
                                <span class="px-2 py-1 rounded text-xs font-bold" style="{{ $a->kelompok_asal == 'IPA' ? 'background-color: #E0F2FE; color: #0369A1;' : 'background-color: #FEF3C7; color: #92400E;' }}">
                                    {{ $a->kelompok_asal }}
                                </span>
                            </td>
                            <td class="px-4 py-3 text-center font-bold text-maroon">{{ $a->nilai_rata_rata ? number_format($a->nilai_rata_rata, 2) : '-' }}</td>
                            <td class="px-4 py-3 text-gray-700">{{ $a->major_masuk }}</td>
                            <td class="px-4 py-3 text-center">
                                @if($a->ranking_saat_rekomendasi)
                                    <span class="px-2 py-1 rounded text-xs font-bold bg-blue-100 text-blue-800">#{{ $a->ranking_saat_rekomendasi }}</span>
                                @else
                                    <span class="text-gray-400 text-xs">-</span>
                                @endif
                            </td>
                            <td class="px-4 py-3 text-center text-xs">
                                @if($a->success_status)
                                    @switch($a->success_status)
                                        @case('sangat_sukses')
                                            <span class="px-2 py-1 rounded bg-green-100 text-green-800 font-bold">✓ Sangat</span>
                                            @break
                                        @case('sukses')
                                            <span class="px-2 py-1 rounded bg-green-50 text-green-700">✓ Sukses</span>
                                            @break
                                        @case('cukup')
                                            <span class="px-2 py-1 rounded bg-yellow-100 text-yellow-800">• Cukup</span>
                                            @break
                                        @case('kurang_sukses')
                                            <span class="px-2 py-1 rounded bg-red-100 text-red-800">✗ Kurang</span>
                                            @break
                                    @endswitch
                                @else
                                    <span class="text-gray-400 text-xs">-</span>
                                @endif
                            </td>
                            <td class="px-4 py-3 text-center gap-2 flex justify-center">
                                <a href="{{ route('alumni.show', $a->id) }}" class="text-blue-600 hover:text-blue-800 font-semibold text-xs">👁 Lihat</a>
                                <a href="{{ route('alumni.edit', $a->id) }}" class="text-yellow-600 hover:text-yellow-800 font-semibold text-xs">✏ Edit</a>
                                <form action="{{ route('alumni.destroy', $a->id) }}" method="POST" class="inline" onsubmit="return confirm('Yakin hapus?')">
                                    @csrf @method('DELETE')
                                    <button type="submit" class="text-red-600 hover:text-red-800 font-semibold text-xs">🗑 Hapus</button>
                                </form>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="8" class="px-4 py-6 text-center text-gray-500">
                                Belum ada data alumni. <a href="{{ route('alumni.create') }}" class="text-maroon font-bold">Tambah sekarang</a>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <!-- Pagination -->
        <div class="mt-6">
            {{ $alumni->links() }}
        </div>

        <!-- Info Box -->
        <div class="mt-8 p-4 bg-blue-50 border-l-4 border-blue-400 rounded">
            <p class="text-sm text-blue-800">
                <strong>📊 Data Alumni digunakan untuk:</strong><br>
                1. Validasi akurasi algoritma Naive Bayes<br>
                2. Analisis faktor-faktor mana yang paling berpengaruh terhadap kesuksesan<br>
                3. Re-weighting: menyesuaikan bobot jika data menunjukkan faktor lain lebih penting
            </p>
        </div>
    </div>
</body>
</html>
