@extends('admin.layouts.app')

@section('title', 'Manajemen Jurusan')

@section('content')
    <!-- Page Header -->
    <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center mb-6 gap-3">
        <div>
            <h2 class="text-2xl font-bold text-maroon">🎓 Manajemen Jurusan</h2>
            <p class="text-sm text-gray-500 mt-1">Kelola data jurusan Polije (tambah, edit, hapus)</p>
        </div>
        <a href="{{ route('admin.jurusan.create') }}" class="gradient-maroon text-white font-bold py-2 px-4 rounded-lg hover:opacity-90 transition text-sm">
            + Tambah Jurusan
        </a>
    </div>

    @if(session('success'))
        <div class="bg-green-50 border-l-4 border-green-400 p-4 rounded-lg mb-6">
            <p class="text-green-800 text-sm font-semibold">✅ {{ session('success') }}</p>
        </div>
    @endif

    @if(session('error'))
        <div class="bg-red-50 border-l-4 border-red-400 p-4 rounded-lg mb-6">
            <p class="text-red-800 text-sm font-semibold">❌ {{ session('error') }}</p>
        </div>
    @endif

    <!-- Jurusan Table -->
    <div class="bg-white rounded-lg shadow overflow-hidden">
        <table class="w-full text-sm">
            <thead class="gradient-maroon text-white">
                <tr>
                    <th class="px-4 py-3 text-left">No</th>
                    <th class="px-4 py-3 text-left">Nama Jurusan</th>
                    <th class="px-4 py-3 text-left hidden lg:table-cell">Preferensi Studi</th>
                    <th class="px-4 py-3 text-center">Aksi</th>
                </tr>
            </thead>
            <tbody class="divide-y">
                @forelse($jurusanList as $index => $jurusan)
                    <tr class="hover:bg-gray-50 transition">
                        <td class="px-4 py-3 text-gray-600">{{ $index + 1 }}</td>
                        <td class="px-4 py-3">
                            <p class="font-semibold text-maroon">{{ $jurusan->nama_jurusan }}</p>
                            @if($jurusan->deskripsi)
                                <p class="text-xs text-gray-500 mt-1 line-clamp-2">{{ Str::limit($jurusan->deskripsi, 80) }}</p>
                            @endif
                        </td>

                        <td class="px-4 py-3 hidden lg:table-cell">
                            <div class="flex flex-wrap gap-1">
                                @foreach($jurusan->preferensi_studi ?? [] as $ps)
                                    <span class="inline-block px-2 py-0.5 rounded bg-green-100 text-green-700 text-xs">{{ $ps }}</span>
                                @endforeach
                            </div>
                        </td>
                        <td class="px-4 py-3 text-center">
                            <div class="flex items-center justify-center gap-2">
                                <a href="{{ route('admin.jurusan.edit', $jurusan->id) }}" class="px-3 py-1 bg-blue-100 text-blue-700 rounded text-xs font-semibold hover:bg-blue-200 transition">
                                    ✏️ Edit
                                </a>
                                <form action="{{ route('admin.jurusan.destroy', $jurusan->id) }}" method="POST" class="swal-confirm" data-confirm-message="Yakin ingin menghapus jurusan {{ $jurusan->nama_jurusan }}?">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="px-3 py-1 bg-red-100 text-red-700 rounded text-xs font-semibold hover:bg-red-200 transition">
                                        🗑️ Hapus
                                    </button>
                                </form>
                            </div>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="4" class="px-4 py-6 text-center text-gray-500">
                            Belum ada data jurusan. <a href="{{ route('admin.jurusan.create') }}" class="text-maroon font-semibold hover:underline">Tambah jurusan pertama</a>.
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <!-- Variabel Input Info -->
    <div class="mt-8 bg-white rounded-lg shadow p-6">
        <h3 class="text-lg font-bold text-maroon mb-4">📊 Kriteria Penilaian Rekomendasi</h3>
        <p class="text-gray-700 text-sm mb-4">Sistem menggunakan 5 kriteria utama untuk memberikan rekomendasi jurusan yang tepat:</p>
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
            <div class="p-4 bg-blue-50 rounded-lg border-l-4 border-blue-400">
                <p class="font-bold text-blue-800 text-sm">📝 Nilai Akademik (15.6%)</p>
                <p class="text-xs text-blue-700 mt-1">IPA: MTK, Fisika, Kimia, Biologi<br>IPS: Ekonomi, Geografi, Sosiologi, Sejarah</p>
            </div>
            <div class="p-4 bg-green-50 rounded-lg border-l-4 border-green-400">
                <p class="font-bold text-green-800 text-sm">💡 Minat & Bakat (45.6%)</p>
                <p class="text-xs text-green-700 mt-1">Dicocokkan dengan keywords jurusan secara graduated</p>
            </div>
            <div class="p-4 bg-yellow-50 rounded-lg border-l-4 border-yellow-400">
                <p class="font-bold text-yellow-800 text-sm">🎯 Preferensi Studi (25.6%)</p>
                <p class="text-xs text-yellow-700 mt-1">Praktik Langsung, DuDi, Project Based, Blended Learning</p>
            </div>
            <div class="p-4 bg-purple-50 rounded-lg border-l-4 border-purple-400">
                <p class="font-bold text-purple-800 text-sm">🏆 Prestasi (4%)</p>
                <p class="text-xs text-purple-700 mt-1">Prestasi akademik dan non-akademik siswa</p>
            </div>
            <div class="p-4 bg-red-50 rounded-lg border-l-4 border-red-400">
                <p class="font-bold text-red-800 text-sm">💼 Cita-cita (9%)</p>
                <p class="text-xs text-red-700 mt-1">Dicocokkan dengan keywords jurusan secara graduated</p>
            </div>
        </div>
    </div>
@endsection
