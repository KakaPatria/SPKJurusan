<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Profil Saya - Sistem Pemilihan Jurusan</title>
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
    <style>
        .gradient-maroon {
            background: linear-gradient(135deg, #5B7B89 0%, #7B9BA5 100%);
        }
        .text-maroon {
            color: #5B7B89;
        }
        .border-maroon {
            border-color: #5B7B89;
        }
        .bg-cream {
            background-color: #F8FAFC;
        }
        .btn-maroon {
            background: linear-gradient(135deg, #5B7B89 0%, #7B9BA5 100%);
            color: #fff;
            transition: all 0.3s ease;
        }
        .btn-maroon:hover {
            opacity: 0.9;
            transform: translateY(-1px);
            box-shadow: 0 4px 12px rgba(91, 123, 137, 0.3);
        }
        .input-focus:focus {
            border-color: #5B7B89;
            box-shadow: 0 0 0 3px rgba(91, 123, 137, 0.15);
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
    <div class="container mx-auto px-4 sm:px-6 py-6 sm:py-12 max-w-3xl">

        {{-- Success Message --}}
        @if (session('status') === 'profile-updated')
            <div class="bg-green-50 border border-green-300 text-green-800 rounded-lg p-4 mb-6 text-sm">
                ✅ Profil berhasil diperbarui!
            </div>
        @endif

        {{-- ========== INFORMASI PROFIL ========== --}}
        <div class="bg-white rounded-lg shadow-lg p-5 sm:p-8 mb-6 border-l-4 border-blue-500">
            <h2 class="text-lg sm:text-xl font-bold text-maroon mb-1">Informasi Profil</h2>
            <p class="text-xs sm:text-sm text-gray-500 mb-6">Perbarui data diri dan foto profil Anda.</p>

            <form method="POST" action="{{ route('profile.update') }}" enctype="multipart/form-data">
                @csrf
                @method('patch')

                {{-- Foto Profil --}}
                <div class="mb-6">
                    <label class="block text-sm font-semibold text-maroon mb-2">Foto Profil</label>
                    <div class="flex items-center gap-4">
                        @if($user->foto)
                            <img src="{{ asset($user->foto) }}" alt="Foto Profil" class="h-20 w-20 rounded-full object-cover border-2 border-maroon shadow" id="foto-preview">
                        @else
                            <div class="h-20 w-20 rounded-full bg-gray-200 flex items-center justify-center text-3xl border-2 border-maroon" id="foto-placeholder">
                                👤
                            </div>
                            <img src="#" alt="Foto Profil" class="h-20 w-20 rounded-full object-cover border-2 border-maroon shadow hidden" id="foto-preview">
                        @endif
                        <div>
                            <input type="file" name="foto" id="foto" accept="image/*" class="text-sm text-gray-600 file:mr-3 file:py-2 file:px-4 file:rounded-lg file:border-0 file:text-sm file:font-semibold file:bg-yellow-100 file:text-yellow-800 hover:file:bg-yellow-200" onchange="previewFoto(this)">
                            <p class="text-xs text-gray-400 mt-1">Format: JPG, PNG, GIF. Maks 2MB.</p>
                        </div>
                    </div>
                    @error('foto')
                        <p class="text-red-600 text-xs mt-1">{{ $message }}</p>
                    @enderror
                </div>

                {{-- Nama --}}
                <div class="mb-4">
                    <label for="name" class="block text-sm font-semibold text-maroon mb-1">Nama Lengkap</label>
                    <input type="text" id="name" name="name" value="{{ old('name', $user->name) }}" required
                        class="input-focus w-full border border-gray-300 rounded-lg px-4 py-2 text-sm focus:ring-0">
                    @error('name')
                        <p class="text-red-600 text-xs mt-1">{{ $message }}</p>
                    @enderror
                </div>

                {{-- Email --}}
                <div class="mb-4">
                    <label for="email" class="block text-sm font-semibold text-maroon mb-1">Email</label>
                    <input type="email" id="email" name="email" value="{{ old('email', $user->email) }}" required
                        class="input-focus w-full border border-gray-300 rounded-lg px-4 py-2 text-sm focus:ring-0">
                    @error('email')
                        <p class="text-red-600 text-xs mt-1">{{ $message }}</p>
                    @enderror
                </div>

                {{-- NIS --}}
                <div class="mb-4">
                    <label for="nis" class="block text-sm font-semibold text-maroon mb-1">NIS (Nomor Induk Siswa)</label>
                    <input type="text" id="nis" name="nis" value="{{ old('nis', $user->nis) }}" maxlength="20"
                        class="input-focus w-full border border-gray-300 rounded-lg px-4 py-2 text-sm focus:ring-0" placeholder="Contoh: 123456">
                    @error('nis')
                        <p class="text-red-600 text-xs mt-1">{{ $message }}</p>
                    @enderror
                </div>

                {{-- Kelompok Asal --}}
                <div class="mb-6">
                    <label for="kelompok_asal" class="block text-sm font-semibold text-maroon mb-1">Kelompok Asal</label>
                    <select id="kelompok_asal" name="kelompok_asal"
                        class="input-focus w-full border border-gray-300 rounded-lg px-4 py-2 text-sm focus:ring-0 bg-white">
                        <option value="">-- Pilih Kelompok --</option>
                        <option value="IPA" {{ old('kelompok_asal', $user->kelompok_asal) === 'IPA' ? 'selected' : '' }}>IPA</option>
                        <option value="IPS" {{ old('kelompok_asal', $user->kelompok_asal) === 'IPS' ? 'selected' : '' }}>IPS</option>
                    </select>
                    @error('kelompok_asal')
                        <p class="text-red-600 text-xs mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <button type="submit" class="btn-maroon font-bold py-2 px-6 rounded-lg text-sm">
                    Simpan Perubahan
                </button>
            </form>
        </div>

        {{-- ========== UBAH PASSWORD ========== --}}
        <div class="bg-white rounded-lg shadow-lg p-5 sm:p-8 mb-6 border-l-4 border-green-500">
            <h2 class="text-lg sm:text-xl font-bold text-maroon mb-1">Ubah Password</h2>
            <p class="text-xs sm:text-sm text-gray-500 mb-6">Pastikan akun Anda menggunakan password yang kuat dan aman.</p>

            @if (session('status') === 'password-updated')
                <div class="bg-green-50 border border-green-300 text-green-800 rounded-lg p-4 mb-4 text-sm">
                    ✅ Password berhasil diubah!
                </div>
            @endif

            <form method="POST" action="{{ route('password.update') }}">
                @csrf
                @method('put')

                <div class="mb-4">
                    <label for="current_password" class="block text-sm font-semibold text-maroon mb-1">Password Saat Ini</label>
                    <div style="position: relative; display: flex; align-items: center;">
                        <input type="password" id="current_password" name="current_password"
                            class="input-focus w-full border border-gray-300 rounded-lg px-4 py-2 text-sm focus:ring-0" style="padding-right: 45px;">
                        <button type="button" style="position: absolute; right: 12px; background: none; border: none; cursor: pointer; color: #5B7B89; font-size: 18px;" onclick="togglePasswordVisibility('current_password', this)">👁️</button>
                    </div>
                    @error('current_password', 'updatePassword')
                        <p class="text-red-600 text-xs mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <div class="mb-4">
                    <label for="password" class="block text-sm font-semibold text-maroon mb-1">Password Baru</label>
                    <div style="position: relative; display: flex; align-items: center;">
                        <input type="password" id="password" name="password"
                            class="input-focus w-full border border-gray-300 rounded-lg px-4 py-2 text-sm focus:ring-0" style="padding-right: 45px;">
                        <button type="button" style="position: absolute; right: 12px; background: none; border: none; cursor: pointer; color: #5B7B89; font-size: 18px;" onclick="togglePasswordVisibility('password', this)">👁️</button>
                    </div>
                    @error('password', 'updatePassword')
                        <p class="text-red-600 text-xs mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <div class="mb-6">
                    <label for="password_confirmation" class="block text-sm font-semibold text-maroon mb-1">Konfirmasi Password Baru</label>
                    <div style="position: relative; display: flex; align-items: center;">
                        <input type="password" id="password_confirmation" name="password_confirmation"
                            class="input-focus w-full border border-gray-300 rounded-lg px-4 py-2 text-sm focus:ring-0" style="padding-right: 45px;">
                        <button type="button" style="position: absolute; right: 12px; background: none; border: none; cursor: pointer; color: #5B7B89; font-size: 18px;" onclick="togglePasswordVisibility('password_confirmation', this)">👁️</button>
                    </div>
                    @error('password_confirmation', 'updatePassword')
                        <p class="text-red-600 text-xs mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <button type="submit" class="bg-green-600 hover:bg-green-700 text-white font-bold py-2 px-6 rounded-lg text-sm transition">
                    Ubah Password
                </button>
            </form>
        </div>

        {{-- ========== HAPUS AKUN ========== --}}
        <div class="bg-white rounded-lg shadow-lg p-5 sm:p-8 mb-6 border-l-4 border-red-500">
            <h2 class="text-lg sm:text-xl font-bold text-red-700 mb-1">Hapus Akun</h2>
            <p class="text-xs sm:text-sm text-gray-500 mb-4">
                Setelah akun dihapus, semua data dan riwayat Anda akan dihapus secara permanen. Pastikan Anda sudah menyimpan data yang diperlukan.
            </p>

            <button type="button" onclick="document.getElementById('delete-section').classList.toggle('hidden')" class="bg-red-600 hover:bg-red-700 text-white font-bold py-2 px-6 rounded-lg text-sm transition">
                Hapus Akun Saya
            </button>

            {{-- Konfirmasi Hapus --}}
            <div id="delete-section" class="hidden mt-6 p-4 bg-red-50 border border-red-200 rounded-lg">
                <p class="text-sm text-red-800 mb-4 font-semibold">Apakah Anda yakin? Masukkan password untuk konfirmasi:</p>
                <form method="POST" action="{{ route('profile.destroy') }}">
                    @csrf
                    @method('delete')

                    <div class="mb-4">
                        <input type="password" name="password" placeholder="Masukkan password Anda"
                            class="input-focus w-full border border-red-300 rounded-lg px-4 py-2 text-sm focus:ring-0">
                        @error('password', 'userDeletion')
                            <p class="text-red-600 text-xs mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <div class="flex gap-3">
                        <button type="button" onclick="document.getElementById('delete-section').classList.add('hidden')" class="bg-gray-300 hover:bg-gray-400 text-gray-800 font-bold py-2 px-4 rounded-lg text-sm transition">
                            Batal
                        </button>
                        <button type="submit" class="bg-red-700 hover:bg-red-800 text-white font-bold py-2 px-4 rounded-lg text-sm transition">
                            Ya, Hapus Akun
                        </button>
                    </div>
                </form>
            </div>
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
                    var preview = document.getElementById('foto-preview');
                    var placeholder = document.getElementById('foto-placeholder');
                    preview.src = e.target.result;
                    preview.classList.remove('hidden');
                    if (placeholder) placeholder.classList.add('hidden');
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
