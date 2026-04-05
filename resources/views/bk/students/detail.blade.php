@extends('bk.layouts.app')

@section('title', 'Detail Siswa - ' . $student->name)

@section('content')
    <div class="flex justify-between items-center mb-6">
        <div>
            <h2 class="text-2xl font-bold text-bk">👤 Detail Siswa</h2>
            <p class="text-sm text-gray-500 mt-1">{{ $student->name }}</p>
        </div>
        <a href="{{ route('bk.students') }}" class="bg-gray-400 text-white font-bold py-2 px-4 rounded-lg hover:bg-gray-500 transition text-sm">
            ← Kembali
        </a>
    </div>

    <!-- Profile Card -->
    <div class="bg-white rounded-lg shadow-lg p-6 mb-6 border-l-4 border-teal-500">
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            <div>
                <p class="text-gray-600 text-sm font-semibold">Nama</p>
                <p class="text-xl font-bold text-bk mt-1">{{ $student->name }}</p>
            </div>
            <div>
                <p class="text-gray-600 text-sm font-semibold">Email</p>
                <p class="text-gray-800 mt-1">{{ $student->email }}</p>
            </div>
            <div>
                <p class="text-gray-600 text-sm font-semibold">NIS</p>
                <p class="text-gray-800 font-semibold mt-1">{{ $student->nis ?? '-' }}</p>
            </div>
            <div>
                <p class="text-gray-600 text-sm font-semibold">Kelompok Asal</p>
                @if($student->kelompok_asal)
                    <p class="mt-1">
                        <span class="px-3 py-1 rounded text-sm font-bold" style="{{ $student->kelompok_asal == 'IPA' ? 'background-color: #E0F2FE; color: #0369A1;' : 'background-color: #FEF3C7; color: #92400E;' }}">
                            {{ $student->kelompok_asal }}
                        </span>
                    </p>
                @else
                    <p class="text-gray-500 mt-1">-</p>
                @endif
            </div>
            <div>
                <p class="text-gray-600 text-sm font-semibold">Foto Profil</p>
                @if($student->foto)
                    <img src="{{ Storage::url($student->foto) }}" alt="{{ $student->name }}" class="w-24 h-24 rounded-lg object-cover mt-2 border-2 border-teal-500">
                @else
                    <p class="text-gray-500 mt-1">-</p>
                @endif
            </div>
            <div>
                <p class="text-gray-600 text-sm font-semibold">Terdaftar</p>
                <p class="text-gray-800 mt-1">{{ $student->created_at->format('d M Y H:i') }}</p>
            </div>
        </div>
    </div>

    <!-- Rekomendasi -->
    <div class="bg-white rounded-lg shadow p-6 mb-6 border-l-4 border-green-400">
        <h3 class="text-lg font-bold text-bk mb-4">🎯 Riwayat Rekomendasi ({{ count($recommendations) }})</h3>

        @if($recommendations->isNotEmpty())
            <div class="space-y-4">
                @foreach($recommendations as $rec)
                    <div class="border border-gray-200 rounded-lg p-4 hover:bg-gray-50 transition">
                        <div class="flex justify-between items-start mb-3">
                            <p class="text-sm text-gray-500">{{ $rec->created_at->format('d M Y H:i') }}</p>
                            <span class="px-2 py-1 rounded text-xs font-bold bg-teal-100 text-teal-800">Rekomendasi #{{ $loop->index + 1 }}</span>
                        </div>

                        @if($rec->hasil_rekomendasi && is_array($rec->hasil_rekomendasi))
                            <div class="mt-3">
                                <p class="text-xs font-semibold text-gray-600 mb-2">Top 3 Rekomendasi:</p>
                                <div class="space-y-2">
                                    @foreach(array_slice($rec->hasil_rekomendasi, 0, 3) as $idx => $hasil)
                                        <div class="flex items-center justify-between">
                                            <span class="text-sm text-gray-700">{{ $idx + 1 }}. {{ $hasil['jurusan'] ?? 'N/A' }}</span>
                                            @php $skorVal = $hasil['skor'] ?? 0; @endphp
                                            <span class="px-2 py-1 rounded text-xs font-bold" style="background-color: {{ $idx === 0 ? '#CCFBF1' : '#F3F4F6' }}; color: {{ $idx === 0 ? '#0f766e' : '#6B7280' }};">
                                                {{ round(($skorVal > 1 ? $skorVal : $skorVal * 100), 1) }}%
                                            </span>
                                        </div>
                                    @endforeach
                                </div>
                            </div>
                        @endif

                        <div class="mt-3 p-3 bg-gray-50 rounded text-xs text-gray-600">
                            <p><strong>Minat:</strong> {{ $rec->minat ?? '-' }} | <strong>Cita-cita:</strong> {{ $rec->cita_cita ?? '-' }}</p>
                        </div>
                    </div>
                @endforeach
            </div>
        @else
            <p class="text-gray-500 text-sm">Siswa belum melakukan rekomendasi</p>
        @endif
    </div>

    <!-- Chat History -->
    <div class="bg-white rounded-lg shadow p-6 border-l-4 border-blue-400">
        <div class="flex justify-between items-center mb-4">
            <h3 class="text-lg font-bold text-bk">💬 Chat History ({{ count($chatHistories) }})</h3>
            @if(count($chatHistories) > 0)
                <a href="{{ route('bk.student.chat', $student->id) }}" class="bg-teal-500 text-white font-semibold py-2 px-3 rounded-lg hover:bg-teal-600 transition text-xs">
                    Lihat Semua →
                </a>
            @endif
        </div>

        @if($chatHistories->isNotEmpty())
            <div class="space-y-3 max-h-96 overflow-y-auto">
                @foreach($chatHistories as $chat)
                    <div class="border-b pb-3 last:border-b-0">
                        <div class="flex justify-between items-start mb-2">
                            <p class="text-xs font-semibold text-gray-600">{{ $chat->created_at->format('d M Y H:i') }}</p>
                        </div>
                        <div class="bg-blue-50 border-l-4 border-blue-400 p-3 rounded mb-2">
                            <p class="text-xs font-semibold text-gray-700 mb-1">👤 Pertanyaan Siswa:</p>
                            <p class="text-sm text-gray-800">{{ \Illuminate\Support\Str::limit($chat->prompt, 150) }}</p>
                        </div>
                        <div class="bg-green-50 border-l-4 border-green-400 p-3 rounded">
                            <p class="text-xs font-semibold text-gray-700 mb-1">🤖 Jawaban AI:</p>
                            <p class="text-sm text-gray-800">{{ \Illuminate\Support\Str::limit($chat->response, 150) }}</p>
                        </div>
                    </div>
                @endforeach
            </div>
        @else
            <p class="text-gray-500 text-sm">Siswa belum melakukan chat dengan AI</p>
        @endif
    </div>
@endsection
