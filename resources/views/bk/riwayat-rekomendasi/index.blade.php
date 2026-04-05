@extends('bk.layouts.app')

@section('title', 'Riwayat Rekomendasi Siswa')

@section('content')
    <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4 mb-6">
        <div>
            <h2 class="text-2xl font-bold text-bk">🎯 Riwayat Rekomendasi Siswa</h2>
            <p class="text-sm text-gray-500 mt-1">Seluruh hasil rekomendasi jurusan yang pernah dilakukan siswa</p>
        </div>
    </div>

    <!-- Stats Cards -->
    <div class="grid grid-cols-1 sm:grid-cols-3 gap-4 mb-6">
        <div class="bg-white rounded-lg shadow p-4 border-t-4 border-teal-500 stat-card">
            <p class="text-gray-600 text-sm font-semibold">Total Rekomendasi</p>
            <p class="text-2xl font-bold text-bk mt-1">{{ $recommendations->total() }}</p>
        </div>
        <div class="bg-white rounded-lg shadow p-4 border-t-4 border-blue-400 stat-card">
            <p class="text-gray-600 text-sm font-semibold">Siswa Unik</p>
            <p class="text-2xl font-bold text-blue-600 mt-1">{{ $uniqueStudents }}</p>
        </div>
        <div class="bg-white rounded-lg shadow p-4 border-t-4 border-green-400 stat-card">
            <p class="text-gray-600 text-sm font-semibold">Jurusan Terpopuler</p>
            <p class="text-lg font-bold text-green-600 mt-1">{{ $topMajor ?? '-' }}</p>
        </div>
    </div>

    <!-- Filter -->
    <div class="bg-white rounded-lg shadow p-4 mb-6 border-l-4 border-teal-500">
        <form method="GET" class="flex gap-3 flex-col sm:flex-row">
            <input type="text" name="search" placeholder="Cari nama siswa..." class="flex-1 px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-teal-500" value="{{ request('search') }}">
            <button type="submit" class="gradient-bk text-white font-bold px-6 py-2 rounded-lg hover:opacity-90 transition">
                🔍 Cari
            </button>
            @if(request('search'))
                <a href="{{ route('bk.riwayat-rekomendasi') }}" class="bg-gray-400 text-white font-bold px-4 py-2 rounded-lg hover:bg-gray-500 transition text-center">
                    Reset
                </a>
            @endif
        </form>
    </div>

    <!-- Table -->
    <div class="bg-white rounded-lg shadow overflow-x-auto">
        <table class="w-full text-sm">
            <thead class="gradient-bk text-white">
                <tr>
                    <th class="px-4 py-3 text-left">No</th>
                    <th class="px-4 py-3 text-left">Nama Siswa</th>
                    <th class="px-4 py-3 text-left">Kelompok</th>
                    <th class="px-4 py-3 text-left">Minat</th>
                    <th class="px-4 py-3 text-left">Top 3 Rekomendasi</th>
                    <th class="px-4 py-3 text-center">Tanggal</th>
                    <th class="px-4 py-3 text-center">Aksi</th>
                </tr>
            </thead>
            <tbody class="divide-y">
                @forelse($recommendations as $idx => $rec)
                    <tr class="hover:bg-gray-50 transition">
                        <td class="px-4 py-3 text-gray-600">{{ $recommendations->firstItem() + $idx }}</td>
                        <td class="px-4 py-3 font-semibold text-gray-800">{{ $rec->user->name ?? 'Deleted User' }}</td>
                        <td class="px-4 py-3">
                            @if($rec->user && $rec->user->kelompok_asal)
                                <span class="px-2 py-1 rounded text-xs font-bold" style="{{ $rec->user->kelompok_asal == 'IPA' ? 'background-color: #E0F2FE; color: #0369A1;' : 'background-color: #FEF3C7; color: #92400E;' }}">
                                    {{ $rec->user->kelompok_asal }}
                                </span>
                            @else
                                <span class="text-gray-400">-</span>
                            @endif
                        </td>
                        <td class="px-4 py-3 text-gray-600 text-xs">{{ \Illuminate\Support\Str::limit($rec->minat, 30) }}</td>
                        <td class="px-4 py-3">
                            @if($rec->hasil_rekomendasi && is_array($rec->hasil_rekomendasi))
                                <div class="space-y-1">
                                    @foreach(array_slice($rec->hasil_rekomendasi, 0, 3) as $i => $hasil)
                                        <div class="flex items-center gap-2">
                                            <span class="w-5 h-5 flex items-center justify-center rounded-full text-xs font-bold {{ $i === 0 ? 'bg-yellow-400 text-yellow-900' : 'bg-gray-200 text-gray-600' }}">{{ $i + 1 }}</span>
                                            <span class="text-xs text-gray-700">{{ $hasil['jurusan'] ?? 'N/A' }}</span>
                                            @php $skorVal = $hasil['skor'] ?? 0; @endphp
                                            <span class="text-xs font-semibold {{ $i === 0 ? 'text-teal-600' : 'text-gray-400' }}">{{ round(($skorVal > 1 ? $skorVal : $skorVal * 100), 1) }}%</span>
                                        </div>
                                    @endforeach
                                </div>
                            @else
                                <span class="text-gray-400 text-xs">-</span>
                            @endif
                        </td>
                        <td class="px-4 py-3 text-center text-gray-500 text-xs">{{ $rec->created_at->format('d M Y H:i') }}</td>
                        <td class="px-4 py-3 text-center">
                            <a href="{{ route('bk.student.detail', $rec->user_id) }}" class="text-teal-600 hover:text-teal-800 font-semibold text-xs">👁 Detail Siswa</a>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="7" class="px-4 py-8 text-center text-gray-500">
                            Belum ada data rekomendasi
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <!-- Pagination -->
    <div class="mt-6">
        {{ $recommendations->withQueryString()->links() }}
    </div>
@endsection
