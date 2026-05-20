@extends('admin.layouts.app')

@section('title', 'Data Alumni')

@section('content')
    <!-- Page Header -->
    <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center mb-6 gap-3">
        <div>
            <h2 class="text-2xl font-bold text-maroon">🎓 Data Alumni</h2>
            <p class="text-sm text-gray-500 mt-1">Kelola data alumni SMA Bima Ambulu yang masuk ke Polije</p>
        </div>
        <a href="{{ route('admin.alumni.create') }}" class="gradient-maroon text-white font-bold py-2 px-4 rounded-lg hover:opacity-90 transition text-sm">
            + Tambah Alumni
        </a>
    </div>

    @if(session('success'))
        <div class="bg-green-50 border-l-4 border-green-400 p-4 rounded-lg mb-6">
            <p class="text-green-800 text-sm font-semibold">✅ {{ session('success') }}</p>
        </div>
    @endif

    <!-- Alumni Table -->
    <div class="bg-white rounded-lg shadow overflow-hidden">
        <table class="w-full">
            <thead class="gradient-maroon text-white">
                <tr>
                    <th class="px-6 py-3 text-left text-sm font-semibold">Nama Alumni</th>
                    <th class="px-6 py-3 text-left text-sm font-semibold">NIS</th>
                    <th class="px-6 py-3 text-center text-sm font-semibold">Kelompok</th>
                    <th class="px-6 py-3 text-left text-sm font-semibold">Jurusan Masuk</th>
                    <th class="px-6 py-3 text-center text-sm font-semibold">Tahun Lulus</th>
                    <th class="px-6 py-3 text-center text-sm font-semibold">Aksi</th>
                </tr>
            </thead>
            <tbody class="divide-y">
                @forelse($alumni as $a)
                    <tr class="hover:bg-gray-50 transition">
                        <td class="px-6 py-4 font-semibold text-gray-800">{{ $a->nama_alumni }}</td>
                        <td class="px-6 py-4 text-gray-600">{{ $a->nis ?? '-' }}</td>
                        <td class="px-6 py-4 text-center">
                            <span class="px-3 py-1 rounded text-xs font-bold" 
                                style="{{ $a->kelompok_asal == 'IPA' ? 'background-color: #E0F2FE; color: #0369A1;' : 'background-color: #FEF3C7; color: #92400E;' }}">
                                {{ $a->kelompok_asal }}
                            </span>
                        </td>
                        <td class="px-6 py-4 text-gray-800">{{ $a->major_masuk }}</td>
                        <td class="px-6 py-4 text-center">
                            @if($a->tahun_lulus_polije)
                                <span class="px-2 py-1 rounded bg-blue-100 text-blue-800 text-sm font-semibold">{{ $a->tahun_lulus_polije }}</span>
                            @else
                                <span class="text-gray-400 text-sm">-</span>
                            @endif
                        </td>
                        <td class="px-6 py-4 text-center gap-2 flex justify-center">
                            <a href="{{ route('admin.alumni.show', $a->id) }}" class="text-blue-600 hover:text-blue-800 font-semibold text-sm">👁 Lihat</a>
                            <a href="{{ route('admin.alumni.edit', $a->id) }}" class="text-yellow-600 hover:text-yellow-800 font-semibold text-sm">✏ Edit</a>
                            <form action="{{ route('admin.alumni.destroy', $a->id) }}" method="POST" class="inline swal-confirm" data-confirm-message="Yakin hapus?">
                                @csrf @method('DELETE')
                                <button type="submit" class="text-red-600 hover:text-red-800 font-semibold text-sm">🗑 Hapus</button>
                            </form>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="6" class="px-6 py-8 text-center text-gray-500">
                            Belum ada data alumni. <a href="{{ route('admin.alumni.create') }}" class="text-maroon font-bold hover:underline">Tambah sekarang</a>
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <!-- Pagination -->
    @if($alumni->hasPages())
        <div class="mt-6">
            {{ $alumni->links() }}
        </div>
    @endif

    <!-- Info Box -->
    <div class="mt-8 p-4 bg-blue-50 border-l-4 border-blue-400 rounded">
        <p class="text-sm text-blue-800">
            <strong>📊 Catatan:</strong><br>
            Data alumni digunakan untuk tracking alumni SMA Bima Ambulu yang melanjutkan ke Polije, monitoring career development, dan referensi untuk siswa baru dalam memilih jurusan.
        </p>
    </div>
@endsection
