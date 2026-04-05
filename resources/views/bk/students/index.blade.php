@extends('bk.layouts.app')

@section('title', 'Data Siswa')

@section('content')
    <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4 mb-6">
        <div>
            <h2 class="text-2xl font-bold text-bk">👥 Data Siswa</h2>
            <p class="text-sm text-gray-500 mt-1">Total: {{ $students->total() }} Siswa</p>
        </div>
    </div>

    <!-- Search & Filter -->
    <div class="bg-white rounded-lg shadow p-4 mb-6 border-l-4 border-teal-500">
        <form method="GET" class="flex gap-3 flex-col sm:flex-row">
            <input type="text" name="search" placeholder="Cari nama atau NIS..." class="flex-1 px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-teal-400" value="{{ request('search') }}">
            <select name="kelompok" class="px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-teal-400">
                <option value="">-- Semua Kelompok --</option>
                <option value="IPA" {{ request('kelompok') == 'IPA' ? 'selected' : '' }}>IPA</option>
                <option value="IPS" {{ request('kelompok') == 'IPS' ? 'selected' : '' }}>IPS</option>
            </select>
            <button type="submit" class="gradient-bk text-white font-bold px-6 py-2 rounded-lg hover:opacity-90 transition">
                🔍 Cari
            </button>
            @if(request('search') || request('kelompok'))
                <a href="{{ route('bk.students') }}" class="bg-gray-400 text-white font-bold px-4 py-2 rounded-lg hover:bg-gray-500 transition text-center">
                    Reset
                </a>
            @endif
        </form>
    </div>

    <!-- Students Table -->
    <div class="bg-white rounded-lg shadow overflow-x-auto">
        <table class="w-full text-sm">
            <thead class="gradient-bk text-white">
                <tr>
                    <th class="px-4 py-3 text-left">Nama</th>
                    <th class="px-4 py-3 text-left">Email</th>
                    <th class="px-4 py-3 text-center">NIS</th>
                    <th class="px-4 py-3 text-center">Kelompok</th>
                    <th class="px-4 py-3 text-center">Rekomendasi</th>
                    <th class="px-4 py-3 text-center">Chat</th>
                    <th class="px-4 py-3 text-center">Aksi</th>
                </tr>
            </thead>
            <tbody class="divide-y">
                @forelse($students as $student)
                    <tr class="hover:bg-gray-50 transition">
                        <td class="px-4 py-3 font-semibold text-gray-800">{{ $student->name }}</td>
                        <td class="px-4 py-3 text-gray-600 text-xs">{{ $student->email }}</td>
                        <td class="px-4 py-3 text-center text-gray-600">{{ $student->nis ?? '-' }}</td>
                        <td class="px-4 py-3 text-center">
                            @if($student->kelompok_asal)
                                <span class="px-2 py-1 rounded text-xs font-bold" style="{{ $student->kelompok_asal == 'IPA' ? 'background-color: #E0F2FE; color: #0369A1;' : 'background-color: #FEF3C7; color: #92400E;' }}">
                                    {{ $student->kelompok_asal }}
                                </span>
                            @else
                                <span class="text-gray-400">-</span>
                            @endif
                        </td>
                        <td class="px-4 py-3 text-center">
                            <span class="px-3 py-1 rounded text-xs font-bold" style="{{ $student->recommendations_count > 0 ? 'background-color: #DBEAFE; color: #1e40af;' : 'background-color: #F3F4F6; color: #9CA3AF;' }}">
                                {{ $student->recommendations_count }}
                            </span>
                        </td>
                        <td class="px-4 py-3 text-center">
                            <span class="px-3 py-1 rounded text-xs font-bold" style="{{ $student->chat_histories_count > 0 ? 'background-color: #D1FAE5; color: #065F46;' : 'background-color: #F3F4F6; color: #9CA3AF;' }}">
                                {{ $student->chat_histories_count }}
                            </span>
                        </td>
                        <td class="px-4 py-3 text-center">
                            <a href="{{ route('bk.student.detail', $student->id) }}" class="text-teal-600 hover:text-teal-800 font-semibold text-xs">👁 Lihat</a>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="7" class="px-4 py-6 text-center text-gray-500">Tidak ada siswa yang ditemukan</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <div class="mt-6">
        {{ $students->withQueryString()->links() }}
    </div>
@endsection
