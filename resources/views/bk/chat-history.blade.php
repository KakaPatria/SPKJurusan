@extends('bk.layouts.app')

@section('title', 'Chat History - ' . ($user->name ?? 'Unknown'))

@section('content')
    <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4 mb-6">
        <div>
            <h2 class="text-2xl font-bold text-bk">💬 Chat History</h2>
            <p class="text-sm text-gray-500 mt-1">{{ $user->name ?? 'Unknown' }}</p>
        </div>
        <a href="{{ route('bk.student.detail', $user->id) }}" class="bg-gray-400 text-white font-bold py-2 px-4 rounded-lg hover:bg-gray-500 transition text-sm">
            ← Profil Siswa
        </a>
    </div>

    <!-- Stats -->
    <div class="grid grid-cols-1 md:grid-cols-3 gap-4 mb-8">
        <div class="bg-white rounded-lg shadow p-4 border-t-4 border-teal-500">
            <p class="text-gray-600 text-sm font-semibold">Total Chat</p>
            <p class="text-2xl font-bold text-bk mt-1">{{ $chatHistories->count() }}</p>
        </div>
        <div class="bg-white rounded-lg shadow p-4 border-t-4 border-blue-400">
            <p class="text-gray-600 text-sm font-semibold">Pertanyaan</p>
            <p class="text-2xl font-bold text-blue-600 mt-1">{{ $chatHistories->count() }}</p>
        </div>
        <div class="bg-white rounded-lg shadow p-4 border-t-4 border-green-400">
            <p class="text-gray-600 text-sm font-semibold">Jawaban</p>
            <p class="text-2xl font-bold text-green-600 mt-1">{{ $chatHistories->count() }}</p>
        </div>
    </div>

    <!-- Chat Messages -->
    <div class="bg-white rounded-lg shadow p-6 space-y-4 max-w-4xl">
        @forelse($chatHistories->sortBy('created_at') as $chat)
            <div class="mb-6">
                <p class="text-xs text-gray-500 text-center mb-3">
                    {{ $chat->created_at->format('d/m/Y H:i') }}
                </p>

                <!-- Question -->
                <div class="flex justify-start mb-2">
                    <div class="bg-blue-100 text-blue-900 rounded-lg p-3 mb-2 max-w-md break-words">
                        <p class="text-sm font-semibold mb-1">Siswa 📤</p>
                        <p class="text-sm leading-relaxed">{{ $chat->prompt }}</p>
                    </div>
                </div>

                <!-- Answer -->
                <div class="flex justify-end mb-2">
                    <div class="bg-green-100 text-green-900 rounded-lg p-3 mb-2 max-w-2xl break-words">
                        <p class="text-sm font-semibold mb-1 text-right">Konselor BK 📥</p>
                        <p class="text-sm leading-relaxed whitespace-pre-wrap">{{ $chat->response }}</p>
                    </div>
                </div>
            </div>

            @if(!$loop->last)
                <hr class="my-4 border-gray-200">
            @endif
        @empty
            <div class="text-center py-12">
                <p class="text-gray-500 text-sm">📭 Belum ada chat history untuk siswa ini</p>
            </div>
        @endforelse
    </div>

    <!-- Info Box -->
    <div class="mt-8 p-4 bg-teal-50 border-l-4 border-teal-400 rounded max-w-4xl">
        <p class="text-sm text-teal-800">
            <strong>ℹ️ Info:</strong> Chat history menunjukkan interaksi siswa dengan Konselor BK AI
        </p>
    </div>
@endsection
