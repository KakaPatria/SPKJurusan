@extends('layouts.app')

@section('content')
<div class="container mx-auto px-4 py-8">
    <div class="max-w-2xl mx-auto">
        <!-- Header -->
        <div class="mb-8">
            <h1 class="text-3xl font-bold text-gray-900">Import Data Alumni</h1>
            <p class="text-gray-600 mt-2">Unggah file Excel berisi data alumni untuk diimport ke database</p>
        </div>

        <!-- Success Message -->
        @if (session('success'))
            <div class="mb-6 p-4 bg-green-50 border border-green-200 rounded-lg">
                <div class="flex items-start">
                    <div class="flex-shrink-0">
                        <svg class="h-5 w-5 text-green-400" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd" />
                        </svg>
                    </div>
                    <div class="ml-3">
                        <h3 class="text-sm font-medium text-green-800">{{ session('success') }}</h3>
                        @if (session('errors') && count(session('errors')) > 0)
                            <div class="mt-2 text-sm text-green-700">
                                <p class="font-medium">Detail Error (menampilkan max 10):</p>
                                <ul class="list-disc ml-5 mt-1">
                                    @foreach (session('errors') as $error)
                                        <li>{{ $error }}</li>
                                    @endforeach
                                </ul>
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        @endif

        <!-- Error Message -->
        @if ($errors->any())
            <div class="mb-6 p-4 bg-red-50 border border-red-200 rounded-lg">
                <div class="flex items-start">
                    <div class="flex-shrink-0">
                        <svg class="h-5 w-5 text-red-400" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd" />
                        </svg>
                    </div>
                    <div class="ml-3">
                        <h3 class="text-sm font-medium text-red-800">Terjadi Error</h3>
                        <ul class="list-disc ml-5 mt-2 text-sm text-red-700">
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                </div>
            </div>
        @endif

        <!-- Upload Form -->
        <div class="bg-white rounded-lg shadow-md p-6">
            <form action="{{ route('alumni.import') }}" method="POST" enctype="multipart/form-data" class="space-y-6">
                @csrf

                <!-- File Input -->
                <div>
                    <label for="file" class="block text-sm font-medium text-gray-700 mb-2">
                        Pilih File Excel
                    </label>
                    <div class="relative border-2 border-dashed border-gray-300 rounded-lg p-6 text-center hover:border-blue-500 hover:bg-blue-50 transition cursor-pointer">
                        <input type="file" id="file" name="file" accept=".xlsx,.xls,.csv" class="absolute inset-0 w-full h-full opacity-0 cursor-pointer" required>
                        <div class="pointer-events-none">
                            <svg class="mx-auto h-12 w-12 text-gray-400" stroke="currentColor" fill="none" viewBox="0 0 48 48">
                                <path d="M28 8H12a4 4 0 00-4 4v20a4 4 0 004 4h24a4 4 0 004-4V20m-8-12l-4-4m0 0l-4 4m4-4v12" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" />
                            </svg>
                            <p class="mt-2 text-sm font-medium text-gray-700">
                                <span class="text-blue-600">Klik untuk upload</span> atau drag & drop
                            </p>
                            <p class="text-xs text-gray-500 mt-1">
                                File format: .xlsx, .xls, .csv (max 10MB)
                            </p>
                        </div>
                    </div>
                    <p id="fileName" class="mt-2 text-sm text-gray-600"></p>
                </div>

                <!-- Info Box -->
                <div class="bg-blue-50 border border-blue-200 rounded-lg p-4">
                    <h4 class="font-semibold text-blue-900 mb-2">📝 Kolom yang Didukung:</h4>
                    <ul class="text-sm text-blue-800 space-y-1">
                        <li>✓ <strong>Wajib:</strong> Nama Alumni, Kelompok Asal</li>
                        <li>✓ <strong>Optional:</strong> NIS, Matematika, Fisika, Kimia, Biologi, Ekonomi, Geografi, Sosiologi, Sejarah, Minat, Cita-Cita, Preferensi Studi, Prestasi, Jurusan Masuk, Tahun Lulus, Catatan</li>
                    </ul>
                </div>

                <!-- Submit Button -->
                <div class="flex gap-4">
                    <button type="submit" class="flex-1 bg-blue-600 hover:bg-blue-700 text-white font-medium py-2 px-4 rounded-lg transition">
                        📤 Upload & Import
                    </button>
                    <a href="{{ route('alumni.index') }}" class="flex-1 bg-gray-200 hover:bg-gray-300 text-gray-800 font-medium py-2 px-4 rounded-lg transition text-center">
                        Kembali
                    </a>
                </div>
            </form>
        </div>

        <!-- Template Download -->
        <div class="mt-8 bg-gray-50 rounded-lg p-6">
            <h3 class="text-lg font-semibold text-gray-900 mb-4">📋 Template Excel</h3>
            <p class="text-gray-600 mb-4">Gunakan template berikut sebagai panduan struktur file Excel:</p>
            
            <div class="bg-white rounded overflow-x-auto border border-gray-200">
                <table class="w-full text-sm">
                    <thead class="bg-gray-100">
                        <tr>
                            <th class="px-4 py-2 text-left">Nama Alumni</th>
                            <th class="px-4 py-2 text-left">NIS</th>
                            <th class="px-4 py-2 text-left">Kelompok</th>
                            <th class="px-4 py-2 text-left">MTK</th>
                            <th class="px-4 py-2 text-left">Fisika</th>
                            <th class="px-4 py-2 text-left">Jurusan</th>
                            <th class="px-4 py-2 text-left">Tahun</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr class="border-t">
                            <td class="px-4 py-2">Budi Santoso</td>
                            <td class="px-4 py-2">001</td>
                            <td class="px-4 py-2">IPA</td>
                            <td class="px-4 py-2">85</td>
                            <td class="px-4 py-2">82</td>
                            <td class="px-4 py-2">TIF</td>
                            <td class="px-4 py-2">2024</td>
                        </tr>
                        <tr class="border-t bg-gray-50">
                            <td class="px-4 py-2">Siti Nurhaliza</td>
                            <td class="px-4 py-2">002</td>
                            <td class="px-4 py-2">IPS</td>
                            <td class="px-4 py-2">75</td>
                            <td class="px-4 py-2">65</td>
                            <td class="px-4 py-2">AK</td>
                            <td class="px-4 py-2">2024</td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<!-- File input change handler -->
<script>
document.getElementById('file').addEventListener('change', function(e) {
    const fileName = e.target.files[0]?.name || '';
    document.getElementById('fileName').textContent = fileName ? `✓ File dipilih: ${fileName}` : '';
});
</script>
@endsection
