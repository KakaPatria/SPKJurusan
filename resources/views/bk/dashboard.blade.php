@extends('bk.layouts.app')

@section('title', 'Dashboard')

@section('content')
    <div class="mb-6">
        <h2 class="text-2xl font-bold text-bk">📊 Dashboard Guru BK</h2>
        <p class="text-sm text-gray-500 mt-1">Selamat datang, {{ Auth::user()->name }}</p>
    </div>

    <!-- Statistics Cards -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4 mb-8">
        <div class="stat-card bg-white rounded-lg shadow p-6 border-t-4 border-teal-500">
            <p class="text-gray-600 text-sm font-semibold">👥 Total Siswa</p>
            <p class="text-3xl font-bold text-bk mt-2">{{ $totalSiswa }}</p>
        </div>
        <div class="stat-card bg-white rounded-lg shadow p-6 border-t-4 border-yellow-400">
            <p class="text-gray-600 text-sm font-semibold">🎯 Total Rekomendasi</p>
            <p class="text-3xl font-bold mt-2" style="color: #EA580C;">{{ $totalRekomendasi }}</p>
        </div>
        <div class="stat-card bg-white rounded-lg shadow p-6 border-t-4 border-blue-400">
            <p class="text-gray-600 text-sm font-semibold">💬 Chat History</p>
            <p class="text-3xl font-bold text-blue-600 mt-2">{{ $totalChatHistory }}</p>
        </div>
        <div class="stat-card bg-white rounded-lg shadow p-6 border-t-4 border-green-400">
            <p class="text-gray-600 text-sm font-semibold">🎓 Jurusan Tersedia</p>
            <p class="text-3xl font-bold text-green-600 mt-2">{{ $totalJurusan }}</p>
        </div>
    </div>

    <!-- Kelompok Distribution & Top Majors -->
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6 mb-6">
        <div class="bg-white rounded-lg shadow p-6 border-l-4 border-teal-500">
            <h3 class="text-lg font-bold text-bk mb-4">📊 Siswa per Kelompok</h3>
            <div class="space-y-3">
                @foreach($kelompokStats as $stat)
                    <div class="flex items-center gap-3">
                        <span class="text-sm font-semibold text-gray-700 w-20">{{ $stat->kelompok_asal ?? 'Tidak Ada' }}</span>
                        <div class="flex-1 h-6 bg-gray-200 rounded">
                            <div class="h-full rounded flex items-center justify-center text-white text-xs font-bold" 
                                style="width: {{ $totalSiswa > 0 ? ($stat->count / $totalSiswa) * 100 : 0 }}%; background-color: {{ $stat->kelompok_asal == 'IPA' ? '#0369A1' : '#D97706' }};">
                                {{ $stat->count }}
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>

        <div class="bg-white rounded-lg shadow p-6 border-l-4 border-green-400">
            <h3 class="text-lg font-bold text-bk mb-4">🎯 Jurusan Terpopuler</h3>
            @if($topMajors->isNotEmpty())
                <div class="space-y-3">
                    @foreach($topMajors as $major)
                        <div class="flex items-center gap-3">
                            <span class="text-sm font-semibold text-gray-700 flex-1 truncate">{{ $major->major_name }}</span>
                            <span class="px-3 py-1 rounded bg-green-100 text-green-800 font-bold text-sm">{{ $major->count }}</span>
                        </div>
                    @endforeach
                </div>
            @else
                <p class="text-gray-500 text-sm">Belum ada data rekomendasi</p>
            @endif
        </div>
    </div>

    <!-- Recent Students -->
    <div class="bg-white rounded-lg shadow p-6 mb-6 border-l-4 border-yellow-400">
        <h3 class="text-lg font-bold text-bk mb-4">👥 Siswa Terbaru</h3>
        @if($recentStudents->isNotEmpty())
            <div class="overflow-x-auto">
                <table class="w-full text-sm">
                    <thead class="border-b-2 border-teal-500">
                        <tr>
                            <th class="text-left px-4 py-2 font-bold text-bk">Nama</th>
                            <th class="text-center px-4 py-2 font-bold text-bk">NIS</th>
                            <th class="text-center px-4 py-2 font-bold text-bk">Kelompok</th>
                            <th class="text-center px-4 py-2 font-bold text-bk">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y">
                        @foreach($recentStudents as $student)
                            <tr class="hover:bg-gray-50">
                                <td class="px-4 py-2 font-semibold text-gray-800">{{ $student->name }}</td>
                                <td class="px-4 py-2 text-center text-gray-600">{{ $student->nis ?? '-' }}</td>
                                <td class="px-4 py-2 text-center">
                                    <span class="px-2 py-1 rounded text-xs font-bold" style="{{ $student->kelompok_asal == 'IPA' ? 'background-color: #E0F2FE; color: #0369A1;' : 'background-color: #FEF3C7; color: #92400E;' }}">
                                        {{ $student->kelompok_asal ?? '-' }}
                                    </span>
                                </td>
                                <td class="px-4 py-2 text-center">
                                    <a href="{{ route('bk.student.detail', $student->id) }}" class="text-teal-600 hover:text-teal-800 font-semibold text-xs">👁 Lihat</a>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        @else
            <p class="text-gray-500 text-sm">Belum ada siswa terdaftar</p>
        @endif
    </div>

    <!-- Recent Recommendations -->
    <div class="bg-white rounded-lg shadow p-6 border-l-4 border-blue-400">
        <h3 class="text-lg font-bold text-bk mb-4">🎯 Rekomendasi Terbaru</h3>
        @if($recentRecommendations->isNotEmpty())
            <div class="overflow-x-auto">
                <table class="w-full text-sm">
                    <thead class="border-b-2 border-teal-500">
                        <tr>
                            <th class="text-left px-4 py-2 font-bold text-bk">Siswa</th>
                            <th class="text-left px-4 py-2 font-bold text-bk">Top Rekomendasi</th>
                            <th class="text-center px-4 py-2 font-bold text-bk">Skor</th>
                            <th class="text-center px-4 py-2 font-bold text-bk">Tanggal</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y">
                        @foreach($recentRecommendations as $rec)
                            @php
                                $topJurusan = $rec->hasil_rekomendasi[0]['jurusan'] ?? '-';
                                $skorRaw = $rec->hasil_rekomendasi[0]['skor'] ?? 0;
                                $topSkor = round(($skorRaw > 1 ? $skorRaw : $skorRaw * 100), 1);
                            @endphp
                            <tr class="hover:bg-gray-50">
                                <td class="px-4 py-2 font-semibold text-gray-800">{{ $rec->user->name ?? 'Pengguna Dihapus' }}</td>
                                <td class="px-4 py-2 text-gray-700">{{ $topJurusan }}</td>
                                <td class="px-4 py-2 text-center">
                                    <span class="px-2 py-1 rounded bg-green-100 text-green-800 font-bold">{{ $topSkor }}%</span>
                                </td>
                                <td class="px-4 py-2 text-center text-gray-600">{{ $rec->created_at->format('d M Y') }}</td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        @else
            <p class="text-gray-500 text-sm">Belum ada rekomendasi</p>
        @endif
    </div>
@endsection
