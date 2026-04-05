@extends('bk.layouts.app')

@section('title', 'Riwayat Konsultasi Chatbot')

@section('content')
    <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4 mb-6">
        <div>
            <h2 class="text-2xl font-bold text-bk">💬 Riwayat Konsultasi Chatbot</h2>
            <p class="text-sm text-gray-500 mt-1">Seluruh riwayat konsultasi siswa dengan AI Konselor BK</p>
        </div>
    </div>

    <!-- Stats Cards -->
    <div class="grid grid-cols-1 sm:grid-cols-3 gap-4 mb-6">
        <div class="bg-white rounded-lg shadow p-4 border-t-4 border-teal-500 stat-card">
            <p class="text-gray-600 text-sm font-semibold">Total Percakapan</p>
            <p class="text-2xl font-bold text-bk mt-1">{{ $chatHistories->total() }}</p>
        </div>
        <div class="bg-white rounded-lg shadow p-4 border-t-4 border-blue-400 stat-card">
            <p class="text-gray-600 text-sm font-semibold">Siswa Unik</p>
            <p class="text-2xl font-bold text-blue-600 mt-1">{{ $uniqueStudents }}</p>
        </div>
        <div class="bg-white rounded-lg shadow p-4 border-t-4 border-green-400 stat-card">
            <p class="text-gray-600 text-sm font-semibold">Hari Ini</p>
            <p class="text-2xl font-bold text-green-600 mt-1">{{ $todayCount }}</p>
        </div>
    </div>

    <!-- Filter -->
    <div class="bg-white rounded-lg shadow p-4 mb-6 border-l-4 border-teal-500">
        <form method="GET" class="flex gap-3 flex-col sm:flex-row">
            <input type="text" name="search" placeholder="Cari nama siswa atau isi percakapan..." class="flex-1 px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-teal-500" value="{{ request('search') }}">
            <button type="submit" class="gradient-bk text-white font-bold px-6 py-2 rounded-lg hover:opacity-90 transition">
                🔍 Cari
            </button>
            @if(request('search'))
                <a href="{{ route('bk.riwayat-chatbot') }}" class="bg-gray-400 text-white font-bold px-4 py-2 rounded-lg hover:bg-gray-500 transition text-center">
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
                    <th class="px-4 py-3 text-left">Pertanyaan</th>
                    <th class="px-4 py-3 text-left">Jawaban AI</th>
                    <th class="px-4 py-3 text-center">Tanggal</th>
                    <th class="px-4 py-3 text-center">Aksi</th>
                </tr>
            </thead>
            <tbody class="divide-y">
                @forelse($chatHistories as $idx => $chat)
                    <tr class="hover:bg-gray-50 transition">
                        <td class="px-4 py-3 text-gray-600">{{ $chatHistories->firstItem() + $idx }}</td>
                        <td class="px-4 py-3 font-semibold text-gray-800">{{ $chat->user->name ?? 'Deleted User' }}</td>
                        <td class="px-4 py-3 text-gray-600 text-xs max-w-xs">
                            <div class="bg-blue-50 border-l-2 border-blue-400 p-2 rounded">
                                {{ \Illuminate\Support\Str::limit($chat->prompt, 80) }}
                            </div>
                        </td>
                        <td class="px-4 py-3 text-gray-600 text-xs max-w-xs">
                            <div class="bg-green-50 border-l-2 border-green-400 p-2 rounded">
                                {{ \Illuminate\Support\Str::limit($chat->response, 80) }}
                            </div>
                        </td>
                        <td class="px-4 py-3 text-center text-gray-500 text-xs">{{ $chat->created_at->format('d M Y H:i') }}</td>
                        <td class="px-4 py-3 text-center">
                            <a href="{{ route('bk.student.detail', $chat->user_id) }}" class="text-teal-600 hover:text-teal-800 font-semibold text-xs">👁 Detail Siswa</a>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="6" class="px-4 py-8 text-center text-gray-500">
                            Belum ada data konsultasi chatbot
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <!-- Pagination -->
    <div class="mt-6">
        {{ $chatHistories->withQueryString()->links() }}
    </div>
@endsection
