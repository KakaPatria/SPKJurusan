@extends('admin.layouts.app')

@section('title', 'Manajemen Akun Guru BK')

@section('content')
    <div class="flex justify-between items-center mb-6">
        <div>
            <h2 class="text-2xl font-bold text-maroon">👨‍🏫 Manajemen Akun Guru BK</h2>
            <p class="text-sm text-gray-500 mt-1">Kelola daftar akun guru BK yang memiliki akses sistem</p>
        </div>
        <a href="{{ route('admin.guru-bk.create') }}" class="gradient-maroon text-white font-bold py-2 px-4 rounded-lg hover:opacity-90 transition text-sm">
            + Tambah Guru BK
        </a>
    </div>

    <!-- Guru BK Table -->
    <div class="bg-white rounded-lg shadow overflow-hidden">
        <table class="w-full text-sm">
            <thead class="gradient-maroon text-white">
                <tr>
                    <th class="px-4 py-3 text-left">No</th>
                    <th class="px-4 py-3 text-left">Nama</th>
                    <th class="px-4 py-3 text-left">Email</th>
                    <th class="px-4 py-3 text-center">Terdaftar</th>
                    <th class="px-4 py-3 text-center">Aksi</th>
                </tr>
            </thead>
            <tbody class="divide-y">
                @forelse($guruBK as $index => $guru)
                    <tr class="hover:bg-gray-50 transition">
                        <td class="px-4 py-3 text-gray-600">{{ $guruBK->firstItem() + $index }}</td>
                        <td class="px-4 py-3 font-semibold text-gray-800">{{ $guru->name }}</td>
                        <td class="px-4 py-3 text-gray-600">{{ $guru->email }}</td>
                        <td class="px-4 py-3 text-center text-gray-500 text-xs">{{ $guru->created_at->format('d M Y') }}</td>
                        <td class="px-4 py-3 text-center space-x-1">
                            <a href="{{ route('admin.guru-bk.edit', $guru->id) }}" class="px-2 py-1 bg-blue-100 text-blue-700 rounded text-xs font-semibold hover:bg-blue-200 transition inline-block">
                                Edit
                            </a>
                            <form method="POST" action="{{ route('admin.guru-bk.destroy', $guru->id) }}" style="display:inline;" onsubmit="return confirm('Hapus akun guru BK ini?')">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="px-2 py-1 bg-red-100 text-red-700 rounded text-xs font-semibold hover:bg-red-200 transition">
                                    Hapus
                                </button>
                            </form>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="5" class="px-4 py-6 text-center text-gray-500">
                            📭 Belum ada akun guru BK. Tekan "+ Tambah Guru BK" untuk menambahkan.
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    @if($guruBK->hasPages())
        <div class="mt-4">{{ $guruBK->links() }}</div>
    @endif
@endsection
