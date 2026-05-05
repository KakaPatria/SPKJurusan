@extends('admin.layouts.app')

@section('title', 'Detail Siswa - ' . $student->name)

@section('content')
    <div class="flex justify-between items-center mb-6">
        <div>
            <h2 class="text-2xl font-bold text-maroon">👤 Detail Siswa</h2>
            <p class="text-sm text-gray-500 mt-1">{{ $student->name }}</p>
        </div>
        <a href="{{ route('admin.students') }}" class="bg-gray-400 text-white font-bold py-2 px-4 rounded-lg hover:bg-gray-500 transition text-sm">
            ← Kembali
        </a>
    </div>

    <!-- Profile Header Card -->
    <div class="bg-white rounded-lg shadow-lg p-6 mb-6 border-l-4 border-maroon">
        <div class="flex flex-col md:flex-row gap-6 items-start">
            <!-- Avatar Section -->
            <div class="flex-shrink-0">
                @if($student->foto)
                    <img src="{{ Storage::url($student->foto) }}" alt="{{ $student->name }}" class="w-24 h-24 md:w-32 md:h-32 rounded-lg object-cover border-4 border-maroon shadow-md">
                @else
                    <div class="w-24 h-24 md:w-32 md:h-32 rounded-lg bg-gradient-to-br from-maroon to-teal-600 flex items-center justify-center text-white text-2xl md:text-3xl font-bold shadow-md">
                        {{ strtoupper(substr($student->name, 0, 1)) }}
                    </div>
                @endif
            </div>
            
            <!-- Info Section -->
            <div class="flex-1">
                <h3 class="text-2xl md:text-3xl font-bold text-maroon mb-4">{{ $student->name }}</h3>
                
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div>
                        <p class="text-xs font-semibold text-gray-500">NIS</p>
                        <p class="text-lg font-bold text-gray-800">{{ $student->nis ?? '-' }}</p>
                    </div>
                    <div>
                        <p class="text-xs font-semibold text-gray-500">Kelompok</p>
                        @if($student->kelompok_asal)
                            <span class="inline-block px-3 py-1 rounded text-sm font-bold mt-1" style="{{ $student->kelompok_asal == 'IPA' ? 'background-color: #E0F2FE; color: #0369A1;' : 'background-color: #FEF3C7; color: #92400E;' }}">
                                {{ $student->kelompok_asal }}
                            </span>
                        @else
                            <p class="text-lg font-bold text-gray-500">-</p>
                        @endif
                    </div>
                    <div>
                        <p class="text-xs font-semibold text-gray-500">Email</p>
                        <p class="text-sm font-semibold text-gray-800 break-all">{{ $student->email }}</p>
                    </div>
                    <div>
                        <p class="text-xs font-semibold text-gray-500">Terdaftar</p>
                        <p class="text-sm font-semibold text-gray-800">{{ $student->created_at->format('d M Y') }}</p>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Main Content Grid -->
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        
        <!-- Left Column: Rekomendasi (2/3 width) -->
        <div class="lg:col-span-2">
            <div class="bg-white rounded-lg shadow p-6 border-l-4 border-green-400">
                <h3 class="text-lg font-bold text-maroon mb-4">🎯 Riwayat Rekomendasi ({{ count($recommendations) }})</h3>

                @if($recommendations->isNotEmpty())
                    <div class="space-y-4">
                        @foreach($recommendations as $rec)
                            <div class="border-2 border-green-100 rounded-lg p-4 hover:shadow-md hover:border-green-400 transition">
                                <div class="flex justify-between items-center mb-3">
                                    <p class="text-xs font-semibold text-gray-500">{{ $rec->created_at->format('d M Y H:i') }}</p>
                                    <span class="px-2 py-1 rounded-full text-xs font-bold bg-green-100 text-green-700">Rekomendasi #{{ $loop->index + 1 }}</span>
                                </div>

                                @if($rec->hasil_rekomendasi && is_array($rec->hasil_rekomendasi))
                                    <div class="space-y-2">
                                        @foreach(array_slice($rec->hasil_rekomendasi, 0, 3) as $idx => $hasil)
                                            <div class="flex items-center justify-between bg-gradient-to-r {{ $idx === 0 ? 'from-green-50 to-transparent' : 'from-gray-50 to-transparent' }} p-3 rounded">
                                                <div class="flex items-center gap-3">
                                                    <span class="inline-flex items-center justify-center w-6 h-6 rounded-full {{ $idx === 0 ? 'bg-green-500 text-white' : 'bg-gray-300 text-white' }} text-xs font-bold">
                                                        {{ $idx + 1 }}
                                                    </span>
                                                    <span class="text-sm font-semibold text-gray-800">{{ $hasil['jurusan'] ?? 'N/A' }}</span>
                                                </div>
                                                @php $skorVal = $hasil['skor'] ?? 0; @endphp
                                                <span class="px-3 py-1 rounded-full text-xs font-bold {{ $idx === 0 ? 'bg-green-200 text-green-800' : 'bg-gray-200 text-gray-800' }}">
                                                    {{ round(($skorVal > 1 ? $skorVal : $skorVal * 100), 1) }}%
                                                </span>
                                            </div>
                                        @endforeach
                                    </div>

                                    @if(isset($rec->minat) || isset($rec->cita_cita))
                                        <div class="mt-3 p-3 bg-blue-50 border-l-4 border-blue-300 rounded">
                                            <p class="text-xs text-gray-700"><strong>📝 Minat:</strong> {{ $rec->minat ?? '-' }} | <strong>🎓 Cita-cita:</strong> {{ $rec->cita_cita ?? '-' }}</p>
                                        </div>
                                    @endif
                                @endif
                            </div>
                        @endforeach
                    </div>
                @else
                    <div class="text-center py-8 text-gray-500">
                        <p class="text-sm">Siswa belum melakukan rekomendasi</p>
                    </div>
                @endif
            </div>
        </div>

        <!-- Right Column: Chat History (1/3 width) -->
        <div class="lg:col-span-1">
            <div class="bg-white rounded-lg shadow p-6 border-l-4 border-blue-400 sticky top-20">
                <div class="flex justify-between items-center mb-4">
                    <h3 class="text-lg font-bold text-maroon">💬 Chat</h3>
                    <span class="text-xs font-bold bg-blue-100 text-blue-700 px-2 py-1 rounded-full">{{ count($chatHistories) }}</span>
                </div>

                @if($chatHistories->isNotEmpty())
                    <div class="space-y-3 max-h-96 overflow-y-auto pr-2">
                        @foreach($chatHistories->take(5) as $chat)
                            <div class="border border-blue-100 rounded-lg p-3 hover:bg-blue-50 transition">
                                <p class="text-xs font-semibold text-gray-500 mb-2">{{ $chat->created_at->format('d M Y') }}</p>
                                <p class="text-xs text-gray-700 mb-2"><strong>Q:</strong> {{ \Illuminate\Support\Str::limit($chat->prompt, 80) }}</p>
                                <p class="text-xs text-gray-600"><strong>A:</strong> {{ \Illuminate\Support\Str::limit($chat->response, 80) }}</p>
                            </div>
                        @endforeach
                    </div>
                    @if(count($chatHistories) > 5)
                        <a href="{{ route('admin.student.chat', $student->id) }}" class="block mt-3 text-center text-xs text-blue-600 font-semibold hover:text-blue-800">
                            Lihat Semua ({{ count($chatHistories) }}) →
                        </a>
                    @endif
                @else
                    <p class="text-gray-500 text-xs text-center py-4">Belum ada chat</p>
                @endif
            </div>
        </div>

    </div>
@endsection
