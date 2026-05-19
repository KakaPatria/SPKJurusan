<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Profil Saya - Sistem Pemilihan Jurusan</title>
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
    <style>
        .gradient-maroon {
            background: linear-gradient(135deg, #6B7280 0%, #8B95A5 100%);
        }
        .text-maroon {
            color: #6B7280;
        }
        .border-maroon {
            border-color: #6B7280;
        }
        .bg-cream {
            background-color: #F8FAFC;
        }
        .btn-maroon {
            background: linear-gradient(135deg, #6B7280 0%, #8B95A5 100%);
            color: #fff;
            transition: all 0.3s ease;
        }
        .btn-maroon:hover {
            opacity: 0.9;
            transform: translateY(-1px);
            box-shadow: 0 4px 12px rgba(107, 114, 128, 0.3);
        }
        .input-focus:focus {
            border-color: #6B7280;
            box-shadow: 0 0 0 3px rgba(107, 114, 128, 0.15);
            outline: none;
        }
    </style>
</head>
<body class="bg-cream">
    <!-- Header -->
    <header class="gradient-maroon text-white shadow-lg sticky top-0 z-50">
        <div class="container mx-auto px-4 sm:px-6 py-4 sm:py-6">
            <div class="flex flex-col sm:flex-row sm:justify-between sm:items-center gap-3 sm:gap-4">
                <div>
                    <h1 class="text-xl sm:text-2xl md:text-3xl font-bold">Profil Saya</h1>
                    <p class="text-xs sm:text-sm text-yellow-300 font-semibold mt-1">Sistem Pemilihan Jurusan</p>
                </div>
                <div class="flex flex-col sm:flex-row items-start sm:items-center gap-2 sm:gap-4">
                    <span class="text-xs sm:text-sm md:text-base text-yellow-200">{{ Auth::user()->name }}</span>
                    <a href="{{ route('dashboard') }}" class="block sm:inline-block w-full sm:w-auto bg-yellow-400 text-center font-bold py-2 px-4 rounded-lg hover:bg-yellow-300 transition text-xs sm:text-sm" style="color: #5B7B89;">
                        ← Kembali ke Dashboard
                    </a>
                </div>
            </div>
        </div>
    </header>

    <!-- Main Content -->
    <div class="container mx-auto px-4 sm:px-6 py-4 sm:py-8 max-w-7xl">

        {{-- Success Message --}}
        @if (session('status') === 'profile-updated')
            <div class="bg-green-50 border border-green-300 text-green-800 rounded-lg p-3 mb-4 text-sm">
                ✅ Profil berhasil diperbarui!
            </div>
        @endif

        {{-- ========== PROFILE HEADER CARD - HORIZONTAL ========== --}}
        <div class="bg-gradient-to-r from-yellow-400 to-yellow-300 rounded-xl shadow-xl p-6 mb-6 text-gray-800">
            <div class="flex gap-6 items-center">
                <!-- Avatar Section -->
                <div class="flex-shrink-0">
                    @if($user->foto)
                        <img src="{{ asset($user->foto) }}" alt="Foto Profil" class="w-32 h-32 rounded-2xl object-cover border-4 border-white shadow-lg" id="foto-preview-header">
                    @else
                        <div class="w-32 h-32 rounded-2xl bg-white bg-opacity-30 flex items-center justify-center text-5xl font-bold text-gray-700" id="foto-placeholder-header">
                            {{ strtoupper(substr($user->name, 0, 1)) }}
                        </div>
                        <img src="#" alt="Foto Profil" class="w-32 h-32 rounded-2xl object-cover border-4 border-white shadow-lg hidden" id="foto-preview-header">
                    @endif
                </div>
                
                <!-- Info Section -->
                <div class="flex-1">
                    <h2 class="text-3xl font-bold mb-4">{{ $user->name }}</h2>
                    <div class="grid grid-cols-4 gap-4 text-sm">
                        <div>
                            <p class="text-gray-700 text-xs font-semibold opacity-75">NIS</p>
                            <p class="text-lg font-bold text-gray-800">{{ $user->nis ?? '-' }}</p>
                        </div>
                        <div>
                            <p class="text-gray-700 text-xs font-semibold opacity-75">Email</p>
                            <p class="text-sm font-semibold text-gray-800 break-words">{{ $user->email }}</p>
                        </div>
                        <div>
                            <p class="text-gray-700 text-xs font-semibold opacity-75">Kelompok</p>
                            @if($user->kelompok_asal)
                                <span class="inline-block px-3 py-1 rounded-full text-xs font-bold text-white" style="background-color: {{ $user->kelompok_asal == 'IPA' ? '#0369A1' : '#B45309' }};">
                                    {{ $user->kelompok_asal }}
                                </span>
                            @else
                                <p class="text-sm font-bold">-</p>
                            @endif
                        </div>
                        <div>
                            <p class="text-gray-700 text-xs font-semibold opacity-75">Terdaftar</p>
                            <p class="text-sm font-semibold text-gray-800">{{ $user->created_at->format('d M Y') }}</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        {{-- ========== FORMS CONTAINER - 2 KOLOM ========== --}}
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-6 mb-6">

            {{-- ========== INFORMASI PROFIL ========== --}}
            <div class="bg-white rounded-lg shadow-lg p-5 border-l-4 border-blue-500">
                <h2 class="text-lg font-bold text-maroon mb-1">📝 Edit Profil</h2>
                <p class="text-xs text-gray-500 mb-4">Perbarui data diri Anda.</p>

                <form method="POST" action="{{ route('profile.update') }}" enctype="multipart/form-data">
                    @csrf
                    @method('patch')

                    {{-- Foto Profil Upload --}}
                    <div class="mb-4">
                        <label class="block text-xs font-semibold text-maroon mb-2">🖼️ Foto Profil</label>
                        <input type="file" name="foto" id="foto" accept="image/*" class="text-xs text-gray-600 file:mr-2 file:py-1 file:px-2 file:rounded file:border-0 file:text-xs file:font-semibold file:bg-yellow-100 file:text-yellow-800 hover:file:bg-yellow-200 w-full" onchange="previewFoto(this)">
                        <p class="text-xs text-gray-400 mt-1">JPG, PNG, GIF. Maks 2MB.</p>
                        @error('foto')
                            <p class="text-red-600 text-xs mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <div class="grid grid-cols-2 gap-3">
                    {{-- Nama --}}
                    <div class="col-span-2">
                        <label for="name" class="block text-xs font-semibold text-maroon mb-1">Nama Lengkap</label>
                        <input type="text" id="name" name="name" value="{{ old('name', $user->name) }}" required
                            class="input-focus w-full border border-gray-300 rounded px-2 py-1 text-xs focus:ring-0">
                        @error('name')
                            <p class="text-red-600 text-xs mt-0.5">{{ $message }}</p>
                        @enderror
                    </div>

                    {{-- Email --}}
                    <div class="col-span-2">
                        <label for="email" class="block text-xs font-semibold text-maroon mb-1">Email</label>
                        <input type="email" id="email" name="email" value="{{ old('email', $user->email) }}" required
                            class="input-focus w-full border border-gray-300 rounded px-2 py-1 text-xs focus:ring-0">
                        @error('email')
                            <p class="text-red-600 text-xs mt-0.5">{{ $message }}</p>
                        @enderror
                    </div>

                    {{-- NIS --}}
                    <div>
                        <label for="nis" class="block text-xs font-semibold text-maroon mb-1">NIS</label>
                        <input type="text" id="nis" name="nis" value="{{ old('nis', $user->nis) }}" maxlength="20"
                            class="input-focus w-full border border-gray-300 rounded px-2 py-1 text-xs focus:ring-0" placeholder="12345678">
                        @error('nis')
                            <p class="text-red-600 text-xs mt-0.5">{{ $message }}</p>
                        @enderror
                    </div>

                    {{-- Kelompok Asal --}}
                    <div>
                        <label for="kelompok_asal" class="block text-xs font-semibold text-maroon mb-1">Kelompok</label>
                        <select id="kelompok_asal" name="kelompok_asal"
                            class="input-focus w-full border border-gray-300 rounded px-2 py-1 text-xs focus:ring-0 bg-white">
                            <option value="">Pilih</option>
                            <option value="IPA" {{ old('kelompok_asal', $user->kelompok_asal) === 'IPA' ? 'selected' : '' }}>IPA</option>
                            <option value="IPS" {{ old('kelompok_asal', $user->kelompok_asal) === 'IPS' ? 'selected' : '' }}>IPS</option>
                        </select>
                        @error('kelompok_asal')
                            <p class="text-red-600 text-xs mt-0.5">{{ $message }}</p>
                        @enderror
                    </div>
                </div>

                <button type="submit" class="btn-maroon font-bold py-2 px-6 rounded text-xs mt-4 w-full">
                    💾 Simpan
                </button>
                </form>
            </div>

            {{-- ========== UBAH PASSWORD ========== --}}
            <div class="bg-white rounded-lg shadow-lg p-5 border-l-4 border-green-500">
                <h2 class="text-lg font-bold text-maroon mb-1">🔐 Ubah Password</h2>
                <p class="text-xs text-gray-500 mb-4">Gunakan password yang kuat dan aman.</p>

                @if (session('status') === 'password-updated')
                    <div class="bg-green-50 border border-green-300 text-green-800 rounded-lg p-3 mb-3 text-xs">
                        ✅ Password berhasil diubah!
                    </div>
                @endif

                <form method="POST" action="{{ route('password.update') }}">
                    @csrf
                    @method('put')

                    <div class="grid grid-cols-1 gap-3">
                    <div>
                        <label for="current_password" class="block text-xs font-semibold text-maroon mb-1">Password Saat Ini</label>
                        <div style="position: relative; display: flex; align-items: center;">
                            <input type="password" id="current_password" name="current_password"
                                class="input-focus w-full border border-gray-300 rounded px-2 py-1 text-xs focus:ring-0" style="padding-right: 30px;">
                            <button type="button" style="position: absolute; right: 6px; background: none; border: none; cursor: pointer; color: #5B7B89; font-size: 14px;" onclick="togglePasswordVisibility('current_password', this)">👁️</button>
                        </div>
                        @error('current_password', 'updatePassword')
                            <p class="text-red-600 text-xs mt-0.5">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label for="password" class="block text-xs font-semibold text-maroon mb-1">Password Baru</label>
                        <div style="position: relative; display: flex; align-items: center;">
                            <input type="password" id="password" name="password"
                                class="input-focus w-full border border-gray-300 rounded px-2 py-1 text-xs focus:ring-0" style="padding-right: 30px;">
                            <button type="button" style="position: absolute; right: 6px; background: none; border: none; cursor: pointer; color: #5B7B89; font-size: 14px;" onclick="togglePasswordVisibility('password', this)">👁️</button>
                        </div>
                        @error('password', 'updatePassword')
                            <p class="text-red-600 text-xs mt-0.5">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label for="password_confirmation" class="block text-xs font-semibold text-maroon mb-1">Konfirmasi Password</label>
                        <div style="position: relative; display: flex; align-items: center;">
                            <input type="password" id="password_confirmation" name="password_confirmation"
                                class="input-focus w-full border border-gray-300 rounded px-2 py-1 text-xs focus:ring-0" style="padding-right: 30px;">
                            <button type="button" style="position: absolute; right: 6px; background: none; border: none; cursor: pointer; color: #5B7B89; font-size: 14px;" onclick="togglePasswordVisibility('password_confirmation', this)">👁️</button>
                        </div>
                        @error('password_confirmation', 'updatePassword')
                            <p class="text-red-600 text-xs mt-0.5">{{ $message }}</p>
                        @enderror
                    </div>
                    </div>

                    <button type="submit" class="bg-green-600 hover:bg-green-700 text-white font-bold py-2 px-6 rounded text-xs transition mt-4 w-full">
                        ✓ Ubah Password
                    </button>
                </form>
        </div>
    </div>

    <!-- Footer -->
    <footer class="gradient-maroon text-white mt-8 sm:mt-12 py-4 sm:py-6">
        <div class="container mx-auto px-4 sm:px-6 text-center">
            <p class="text-xs sm:text-sm text-yellow-200">Sistem Pemilihan Jurusan © 2026 | SMA Bima Ambulu</p>
        </div>
    </footer>

    <script>
        function previewFoto(input) {
            if (input.files && input.files[0]) {
                var reader = new FileReader();
                reader.onload = function(e) {
                    var previewHeader = document.getElementById('foto-preview-header');
                    var placeholderHeader = document.getElementById('foto-placeholder-header');
                    previewHeader.src = e.target.result;
                    previewHeader.classList.remove('hidden');
                    if (placeholderHeader) placeholderHeader.classList.add('hidden');
                }
                reader.readAsDataURL(input.files[0]);
            }
        }
        
        function togglePasswordVisibility(inputId, buttonElement) {
            const input = document.getElementById(inputId);
            const isPassword = input.type === 'password';
            input.type = isPassword ? 'text' : 'password';
        }
    </script>
</body>
</html>
