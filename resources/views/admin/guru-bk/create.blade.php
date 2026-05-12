@extends('admin.layouts.app')

@section('title', 'Tambah Guru BK')

@section('content')
    <div class="flex justify-between items-center mb-6">
        <div>
            <h2 class="text-2xl font-bold text-maroon">➕ Tambah Akun Guru BK</h2>
            <p class="text-sm text-gray-500 mt-1">Buat akun baru untuk guru BK</p>
        </div>
        <a href="{{ route('admin.guru-bk') }}" class="bg-gray-400 text-white font-bold py-2 px-4 rounded-lg hover:bg-gray-500 transition text-sm">
            ← Kembali
        </a>
    </div>

    <!-- Error Summary Alert -->
    @if($errors->any())
        <div class="mb-6 p-4 bg-red-50 border border-red-200 rounded-lg">
            <div class="flex items-center gap-2 mb-2">
                <span class="text-red-600 text-xl">⚠️</span>
                <span class="text-red-700 font-bold">Perbaiki kesalahan berikut:</span>
            </div>
            <ul class="text-red-600 text-sm space-y-1 ml-8">
                @foreach($errors->all() as $error)
                    <li>• {{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <div class="bg-white rounded-lg shadow p-6 max-w-2xl">
        <form action="{{ route('admin.guru-bk.store') }}" method="POST" class="space-y-4" id="guruBKForm">
            @csrf
            <div>
                <label class="block text-sm font-semibold text-gray-700 mb-2">Nama Lengkap * <span class="text-gray-400 text-xs">(minimal 3 karakter)</span></label>
                <input type="text" name="name" required minlength="3" maxlength="255" value="{{ old('name') }}" class="w-full px-4 py-2 border rounded-lg focus:outline-none focus:ring-2 transition @error('name') border-red-500 focus:ring-red-400 @else border-gray-300 focus:ring-maroon @enderror" placeholder="Nama guru BK" oninput="validateGuruBKForm()">
                <div class="flex justify-between items-center mt-1">
                    <span id="nameError" class="text-red-500 text-xs hidden">⚠️ Nama minimal 3 karakter</span>
                    <span id="nameValid" class="text-green-500 text-xs hidden">✓ Nama valid</span>
                </div>
                @error('name') <span class="text-red-500 text-xs block mt-1">{{ $message }}</span> @enderror
            </div>
            <div>
                <label class="block text-sm font-semibold text-gray-700 mb-2">Email * <span class="text-gray-400 text-xs">(format email valid)</span></label>
                <input type="email" name="email" required value="{{ old('email') }}" class="w-full px-4 py-2 border rounded-lg focus:outline-none focus:ring-2 transition @error('email') border-red-500 focus:ring-red-400 @else border-gray-300 focus:ring-maroon @enderror" placeholder="email@sekolah.id" oninput="validateGuruBKForm()">
                <div class="flex justify-between items-center mt-1">
                    <span id="emailError" class="text-red-500 text-xs hidden">⚠️ Format email tidak valid</span>
                    <span id="emailValid" class="text-green-500 text-xs hidden">✓ Email valid</span>
                </div>
                @error('email') <span class="text-red-500 text-xs block mt-1">{{ $message }}</span> @enderror
            </div>
            <div>
                <label class="block text-sm font-semibold text-gray-700 mb-2">Password * <span class="text-gray-400 text-xs">(minimal 8 karakter)</span></label>
                <div style="position: relative; display: flex; align-items: center;">
                    <input type="password" id="guruBKPassword" name="password" required minlength="8" maxlength="255" class="w-full px-4 py-2 border rounded-lg focus:outline-none focus:ring-2 transition @error('password') border-red-500 focus:ring-red-400 @else border-gray-300 focus:ring-blue-400 @enderror" placeholder="Minimal 8 karakter" style="padding-right: 45px;" oninput="validateGuruBKForm()">
                    <button type="button" style="position: absolute; right: 12px; background: none; border: none; cursor: pointer; color: #5B7B89; font-size: 18px;" onclick="togglePasswordVisibility('guruBKPassword', this)">👁️</button>
                </div>
                <div id="passwordStrength" class="text-xs mt-1 font-medium hidden">
                    <span id="passwordStrengthText"></span>
                </div>
                @error('password') <span class="text-red-500 text-xs block mt-1">{{ $message }}</span> @enderror
            </div>
            <div>
                <label class="block text-sm font-semibold text-gray-700 mb-2">Konfirmasi Password * <span class="text-gray-400 text-xs">(harus sama)</span></label>
                <div style="position: relative; display: flex; align-items: center;">
                    <input type="password" id="guruBKPasswordConfirm" name="password_confirmation" required class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-400" placeholder="Ulangi password" style="padding-right: 45px;" oninput="validateGuruBKForm()">
                    <button type="button" style="position: absolute; right: 12px; background: none; border: none; cursor: pointer; color: #5B7B89; font-size: 18px;" onclick="togglePasswordVisibility('guruBKPasswordConfirm', this)">👁️</button>
                </div>
                <div id="confirmError" class="text-red-500 text-xs hidden mt-1">⚠️ Password konfirmasi tidak cocok</div>
                <div id="confirmValid" class="text-green-500 text-xs hidden mt-1">✓ Password cocok</div>
            </div>

            <div class="flex gap-4 pt-4">
                <button type="submit" class="flex-1 gradient-maroon text-white font-bold py-3 px-4 rounded-lg hover:opacity-90 transition disabled:opacity-50 disabled:cursor-not-allowed" id="submitButton">
                    💾 Simpan
                </button>
                <a href="{{ route('admin.guru-bk') }}" class="flex-1 bg-gray-400 text-white font-bold py-3 px-4 rounded-lg hover:bg-gray-500 transition text-center">
                    Batal
                </a>
            </div>
        </form>
    </div>

    @section('scripts')
        <script>
            function togglePasswordVisibility(inputId, buttonElement) {
                const input = document.getElementById(inputId);
                const isPassword = input.type === 'password';
                input.type = isPassword ? 'text' : 'password';
            }

            function getPasswordStrength(password) {
                let strength = 0;
                if (password.length >= 8) strength++;
                if (password.length >= 12) strength++;
                if (/[a-z]/.test(password) && /[A-Z]/.test(password)) strength++;
                if (/\d/.test(password)) strength++;
                if (/[^a-zA-Z\d]/.test(password)) strength++;
                
                if (strength <= 1) return { level: 'Lemah', color: 'text-red-500' };
                if (strength <= 2) return { level: 'Sedang', color: 'text-yellow-500' };
                if (strength <= 3) return { level: 'Baik', color: 'text-blue-500' };
                return { level: 'Kuat', color: 'text-green-500' };
            }

            function validateGuruBKForm() {
                const name = document.querySelector('input[name="name"]');
                const email = document.querySelector('input[name="email"]');
                const password = document.getElementById('guruBKPassword');
                const passwordConfirm = document.getElementById('guruBKPasswordConfirm');
                const submitButton = document.getElementById('submitButton');
                const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;

                // Validate name
                if (name.value.trim().length >= 3) {
                    name.classList.remove('border-red-500', 'focus:ring-red-400');
                    name.classList.add('border-gray-300', 'focus:ring-maroon');
                    document.getElementById('nameError').classList.add('hidden');
                    document.getElementById('nameValid').classList.remove('hidden');
                } else if (name.value.trim().length > 0) {
                    name.classList.remove('border-gray-300', 'focus:ring-maroon');
                    name.classList.add('border-red-500', 'focus:ring-red-400');
                    document.getElementById('nameError').classList.remove('hidden');
                    document.getElementById('nameValid').classList.add('hidden');
                } else {
                    document.getElementById('nameError').classList.add('hidden');
                    document.getElementById('nameValid').classList.add('hidden');
                }

                // Validate email
                if (!emailRegex.test(email.value)) {
                    email.classList.remove('border-gray-300', 'focus:ring-maroon');
                    email.classList.add('border-red-500', 'focus:ring-red-400');
                    document.getElementById('emailError').classList.remove('hidden');
                    document.getElementById('emailValid').classList.add('hidden');
                } else {
                    email.classList.remove('border-red-500', 'focus:ring-red-400');
                    email.classList.add('border-gray-300', 'focus:ring-maroon');
                    document.getElementById('emailError').classList.add('hidden');
                    document.getElementById('emailValid').classList.remove('hidden');
                }

                // Show password strength
                if (password.value.length > 0) {
                    const strength = getPasswordStrength(password.value);
                    document.getElementById('passwordStrengthText').textContent = `Kekuatan: ${strength.level}`;
                    document.getElementById('passwordStrengthText').className = strength.color;
                    document.getElementById('passwordStrength').classList.remove('hidden');
                } else {
                    document.getElementById('passwordStrength').classList.add('hidden');
                }

                // Validate password confirmation
                if (passwordConfirm.value.length > 0) {
                    if (password.value !== passwordConfirm.value) {
                        document.getElementById('confirmError').classList.remove('hidden');
                        document.getElementById('confirmValid').classList.add('hidden');
                    } else if (password.value.length >= 8) {
                        document.getElementById('confirmError').classList.add('hidden');
                        document.getElementById('confirmValid').classList.remove('hidden');
                    }
                } else {
                    document.getElementById('confirmError').classList.add('hidden');
                    document.getElementById('confirmValid').classList.add('hidden');
                }

                // Enable/disable submit
                const isValid = name.value.trim().length >= 3 &&
                               emailRegex.test(email.value) &&
                               password.value.length >= 8 &&
                               password.value === passwordConfirm.value;
                submitButton.disabled = !isValid;
            }

            document.addEventListener('DOMContentLoaded', function() {
                validateGuruBKForm();
            });
        </script>
    @endsection
@endsection
