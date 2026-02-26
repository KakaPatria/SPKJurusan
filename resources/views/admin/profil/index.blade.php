@extends('admin.layouts.app')

@section('title', 'Profil Admin')

@section('content')
    <div class="mb-6">
        <h2 class="text-2xl font-bold text-maroon">⚙️ Profil Admin</h2>
        <p class="text-sm text-gray-500 mt-1">Kelola informasi akun administrator</p>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
        <!-- Update Profile -->
        <div class="bg-white rounded-lg shadow p-6">
            <h3 class="text-lg font-bold text-maroon mb-4">👤 Informasi Akun</h3>
            <form action="{{ route('admin.profil.update') }}" method="POST" class="space-y-4">
                @csrf
                @method('PUT')
                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-2">Nama *</label>
                    <input type="text" name="name" required value="{{ old('name', $admin->name) }}" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-maroon">
                    @error('name') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                </div>
                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-2">Email *</label>
                    <input type="email" name="email" required value="{{ old('email', $admin->email) }}" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-maroon">
                    @error('email') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                </div>
                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-2">Role</label>
                    <input type="text" value="Administrator" readonly class="w-full px-4 py-2 border border-gray-200 rounded-lg bg-gray-100 text-gray-500 cursor-not-allowed">
                </div>
                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-2">Terdaftar Sejak</label>
                    <input type="text" value="{{ $admin->created_at->format('d M Y H:i') }}" readonly class="w-full px-4 py-2 border border-gray-200 rounded-lg bg-gray-100 text-gray-500 cursor-not-allowed">
                </div>
                <button type="submit" class="w-full gradient-maroon text-white font-bold py-3 px-4 rounded-lg hover:opacity-90 transition">
                    💾 Update Profil
                </button>
            </form>
        </div>

        <!-- Change Password -->
        <div class="bg-white rounded-lg shadow p-6">
            <h3 class="text-lg font-bold text-maroon mb-4">🔒 Ubah Password</h3>
            <form action="{{ route('admin.profil.password') }}" method="POST" class="space-y-4">
                @csrf
                @method('PUT')
                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-2">Password Lama *</label>
                    <div style="position: relative; display: flex; align-items: center;">
                        <input type="password" id="currentPass" name="current_password" required class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-400" placeholder="Masukkan password lama" style="padding-right: 45px;">
                        <button type="button" style="position: absolute; right: 12px; background: none; border: none; cursor: pointer; color: #5B7B89; font-size: 18px;" onclick="togglePasswordVisibility('currentPass', this)">👁️</button>
                    </div>
                    @error('current_password') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                </div>
                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-2">Password Baru *</label>
                    <div style="position: relative; display: flex; align-items: center;">
                        <input type="password" id="newPass" name="password" required class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-400" placeholder="Minimal 8 karakter" style="padding-right: 45px;">
                        <button type="button" style="position: absolute; right: 12px; background: none; border: none; cursor: pointer; color: #5B7B89; font-size: 18px;" onclick="togglePasswordVisibility('newPass', this)">👁️</button>
                    </div>
                    @error('password') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                </div>
                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-2">Konfirmasi Password Baru *</label>
                    <div style="position: relative; display: flex; align-items: center;">
                        <input type="password" id="newPassConfirm" name="password_confirmation" required class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-400" placeholder="Ulangi password baru" style="padding-right: 45px;">
                        <button type="button" style="position: absolute; right: 12px; background: none; border: none; cursor: pointer; color: #5B7B89; font-size: 18px;" onclick="togglePasswordVisibility('newPassConfirm', this)">👁️</button>
                    </div>
                </div>
                <button type="submit" class="w-full bg-blue-600 text-white font-bold py-3 px-4 rounded-lg hover:bg-blue-700 transition">
                    🔑 Ubah Password
                </button>
            </form>
        </div>
    </div>
@endsection

@section('scripts')
    <script>
        function togglePasswordVisibility(inputId, buttonElement) {
            const input = document.getElementById(inputId);
            const isPassword = input.type === 'password';
            input.type = isPassword ? 'text' : 'password';
        }
    </script>
@endsection
