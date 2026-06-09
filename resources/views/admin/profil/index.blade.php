@extends('admin.layouts.app')

@section('title', 'Profil Admin')

@section('content')
    <div class="mb-6">
        <h2 class="text-xl sm:text-2xl font-bold text-maroon leading-tight">⚙️ Profil Admin</h2>
        <p class="text-sm text-gray-500 mt-1">Kelola informasi akun administrator</p>
    </div>

    <!-- Success Alert -->
    @if(session('success'))
        <div class="mb-6 p-4 bg-green-50 border border-green-200 rounded-lg flex items-center gap-3">
            <span class="text-green-600 text-xl">✅</span>
            <span class="text-green-700 font-medium">{{ session('success') }}</span>
        </div>
    @endif

    <!-- Error Summary Alert -->
    @if($errors->any())
        <div class="mb-6 p-4 bg-red-50 border border-red-200 rounded-lg">
            <div class="flex items-center gap-2 mb-2">
                <span class="text-red-600 text-xl">⚠️</span>
                <span class="text-red-700 font-bold">Terjadi Kesalahan</span>
            </div>
            <ul class="text-red-600 text-sm space-y-1 ml-8">
                @foreach($errors->all() as $error)
                    <li>• {{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <div class="grid grid-cols-1 lg:grid-cols-2 gap-4 sm:gap-6">
        <!-- Update Profile -->
        <div class="bg-white rounded-lg shadow p-4 sm:p-6">
            <h3 class="text-lg font-bold text-maroon mb-4">👤 Informasi Akun</h3>
            <form action="{{ route('admin.profil.update') }}" method="POST" class="space-y-4" id="profileForm">
                @csrf
                @method('PUT')
                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-2">Nama * <span class="text-gray-400 text-xs">(minimal 3 karakter)</span></label>
                    <input type="text" name="name" required value="{{ old('name', $admin->name) }}" minlength="3" maxlength="100" class="w-full px-4 py-2 border rounded-lg focus:outline-none focus:ring-2 transition @error('name') border-red-500 focus:ring-red-400 @else border-gray-300 focus:ring-maroon @enderror" placeholder="Nama lengkap" oninput="validateName(this)">
                    <div class="flex justify-between items-center mt-1">
                        <span id="nameError" class="text-red-500 text-xs hidden">⚠️ Nama harus minimal 3 karakter</span>
                        <span id="nameValid" class="text-green-500 text-xs hidden">✓ Nama valid</span>
                    </div>
                    @error('name') <span class="text-red-500 text-xs block mt-1">{{ $message }}</span> @enderror
                </div>
                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-2">Email * <span class="text-gray-400 text-xs">(format email valid)</span></label>
                    <input type="email" name="email" required value="{{ old('email', $admin->email) }}" class="w-full px-4 py-2 border rounded-lg focus:outline-none focus:ring-2 transition @error('email') border-red-500 focus:ring-red-400 @else border-gray-300 focus:ring-maroon @enderror" placeholder="email@example.com" oninput="validateEmail(this)">
                    <div class="flex justify-between items-center mt-1">
                        <span id="emailError" class="text-red-500 text-xs hidden">⚠️ Format email tidak valid</span>
                        <span id="emailValid" class="text-green-500 text-xs hidden">✓ Email valid</span>
                    </div>
                    @error('email') <span class="text-red-500 text-xs block mt-1">{{ $message }}</span> @enderror
                </div>
                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-2">Role</label>
                    <input type="text" value="Administrator" readonly class="w-full px-4 py-2 border border-gray-200 rounded-lg bg-gray-100 text-gray-500 cursor-not-allowed">
                </div>
                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-2">Terdaftar Sejak</label>
                    <input type="text" value="{{ $admin->created_at->format('d M Y H:i') }}" readonly class="w-full px-4 py-2 border border-gray-200 rounded-lg bg-gray-100 text-gray-500 cursor-not-allowed">
                </div>
                <button type="submit" class="w-full gradient-maroon text-white font-bold py-3 px-4 rounded-lg hover:opacity-90 transition" id="profileSubmit">
                    💾 Update Profil
                </button>
            </form>
        </div>

        <!-- Change Password -->
        <div class="bg-white rounded-lg shadow p-4 sm:p-6">
            <h3 class="text-lg font-bold text-maroon mb-4">🔒 Ubah Password</h3>
            <form action="{{ route('admin.profil.password') }}" method="POST" class="space-y-4" id="passwordForm">
                @csrf
                @method('PUT')
                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-2">Password Lama * <span class="text-gray-400 text-xs">(harus benar)</span></label>
                    <div style="position: relative; display: flex; align-items: center;">
                        <input type="password" id="currentPass" name="current_password" required class="w-full px-4 py-2 border rounded-lg focus:outline-none focus:ring-2 transition @error('current_password') border-red-500 focus:ring-red-400 @else border-gray-300 focus:ring-blue-400 @enderror" placeholder="Masukkan password lama" style="padding-right: 45px;" oninput="validatePasswordForm()">
                        <button type="button" style="position: absolute; right: 12px; background: none; border: none; cursor: pointer; color: #5B7B89; font-size: 18px;" onclick="togglePasswordVisibility('currentPass', this)">👁️</button>
                    </div>
                    @error('current_password') <span class="text-red-500 text-xs block mt-1">{{ $message }}</span> @enderror
                </div>
                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-2">Password Baru * <span class="text-gray-400 text-xs">(minimal 8 karakter)</span></label>
                    <div style="position: relative; display: flex; align-items: center;">
                        <input type="password" id="newPass" name="password" required minlength="8" maxlength="255" class="w-full px-4 py-2 border rounded-lg focus:outline-none focus:ring-2 transition @error('password') border-red-500 focus:ring-red-400 @else border-gray-300 focus:ring-blue-400 @enderror" placeholder="Minimal 8 karakter" style="padding-right: 45px;" oninput="validatePasswordForm()">
                        <button type="button" style="position: absolute; right: 12px; background: none; border: none; cursor: pointer; color: #5B7B89; font-size: 18px;" onclick="togglePasswordVisibility('newPass', this)">👁️</button>
                    </div>
                    <div id="passwordStrength" class="text-xs mt-1 font-medium hidden">
                        <span id="passwordStrengthText"></span>
                    </div>
                    @error('password') <span class="text-red-500 text-xs block mt-1">{{ $message }}</span> @enderror
                </div>
                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-2">Konfirmasi Password Baru * <span class="text-gray-400 text-xs">(harus sama)</span></label>
                    <div style="position: relative; display: flex; align-items: center;">
                        <input type="password" id="newPassConfirm" name="password_confirmation" required class="w-full px-4 py-2 border rounded-lg focus:outline-none focus:ring-2 transition border-gray-300 focus:ring-blue-400" placeholder="Ulangi password baru" style="padding-right: 45px;" oninput="validatePasswordForm()">
                        <button type="button" style="position: absolute; right: 12px; background: none; border: none; cursor: pointer; color: #5B7B89; font-size: 18px;" onclick="togglePasswordVisibility('newPassConfirm', this)">👁️</button>
                    </div>
                    <div id="confirmError" class="text-red-500 text-xs hidden mt-1">⚠️ Password konfirmasi tidak cocok</div>
                    <div id="confirmValid" class="text-green-500 text-xs hidden mt-1">✓ Password cocok</div>
                </div>
                <button type="submit" class="w-full bg-blue-600 text-white font-bold py-3 px-4 rounded-lg hover:bg-blue-700 transition disabled:opacity-50 disabled:cursor-not-allowed" id="passwordSubmit">
                    🔑 Ubah Password
                </button>
            </form>
        </div>
    </div>
@endsection

@section('scripts')
    <script>
        // Toggle password visibility
        function togglePasswordVisibility(inputId, buttonElement) {
            const input = document.getElementById(inputId);
            const isPassword = input.type === 'password';
            input.type = isPassword ? 'text' : 'password';
        }

        // Validate name
        function validateName(input) {
            const nameError = document.getElementById('nameError');
            const nameValid = document.getElementById('nameValid');
            const value = input.value.trim();
            
            if (value.length === 0) {
                nameError.classList.add('hidden');
                nameValid.classList.add('hidden');
            } else if (value.length < 3) {
                input.classList.remove('focus:ring-maroon');
                input.classList.add('border-red-500', 'focus:ring-red-400');
                nameError.classList.remove('hidden');
                nameValid.classList.add('hidden');
            } else {
                input.classList.remove('border-red-500', 'focus:ring-red-400');
                input.classList.add('border-gray-300', 'focus:ring-maroon');
                nameError.classList.add('hidden');
                nameValid.classList.remove('hidden');
            }
        }

        // Validate email
        function validateEmail(input) {
            const emailError = document.getElementById('emailError');
            const emailValid = document.getElementById('emailValid');
            const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
            const value = input.value.trim();
            
            if (value.length === 0) {
                emailError.classList.add('hidden');
                emailValid.classList.add('hidden');
            } else if (!emailRegex.test(value)) {
                input.classList.remove('focus:ring-maroon');
                input.classList.add('border-red-500', 'focus:ring-red-400');
                emailError.classList.remove('hidden');
                emailValid.classList.add('hidden');
            } else {
                input.classList.remove('border-red-500', 'focus:ring-red-400');
                input.classList.add('border-gray-300', 'focus:ring-maroon');
                emailError.classList.add('hidden');
                emailValid.classList.remove('hidden');
            }
        }

        // Check password strength
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

        // Validate password form
        function validatePasswordForm() {
            const currentPass = document.getElementById('currentPass').value;
            const newPass = document.getElementById('newPass').value;
            const confirmPass = document.getElementById('newPassConfirm').value;
            
            const passwordStrength = document.getElementById('passwordStrength');
            const passwordStrengthText = document.getElementById('passwordStrengthText');
            const confirmError = document.getElementById('confirmError');
            const confirmValid = document.getElementById('confirmValid');
            const passwordSubmit = document.getElementById('passwordSubmit');
            
            // Show password strength if new password is entered
            if (newPass.length > 0) {
                const strength = getPasswordStrength(newPass);
                passwordStrengthText.textContent = `Kekuatan: ${strength.level}`;
                passwordStrengthText.className = `${strength.color}`;
                passwordStrength.classList.remove('hidden');
            } else {
                passwordStrength.classList.add('hidden');
            }
            
            // Check password confirmation
            if (confirmPass.length > 0) {
                if (newPass !== confirmPass) {
                    confirmError.classList.remove('hidden');
                    confirmValid.classList.add('hidden');
                } else if (newPass.length >= 8) {
                    confirmError.classList.add('hidden');
                    confirmValid.classList.remove('hidden');
                } else {
                    confirmError.classList.add('hidden');
                    confirmValid.classList.add('hidden');
                }
            } else {
                confirmError.classList.add('hidden');
                confirmValid.classList.add('hidden');
            }
            
            // Enable/disable submit button
            const isValid = currentPass.length > 0 && newPass.length >= 8 && newPass === confirmPass;
            passwordSubmit.disabled = !isValid;
        }

        // Validate profile form on submit
        document.getElementById('profileForm')?.addEventListener('submit', function(e) {
            const name = document.querySelector('input[name="name"]').value.trim();
            const email = document.querySelector('input[name="email"]').value.trim();
            const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
            
            let errors = [];
            
            if (name.length < 3) {
                errors.push('Nama harus minimal 3 karakter');
            }
            if (!emailRegex.test(email)) {
                errors.push('Format email tidak valid');
            }
            
            if (errors.length > 0) {
                e.preventDefault();
                alert('Perbaiki kesalahan berikut:\n• ' + errors.join('\n• '));
            }
        });

        // Validate password form on submit
        document.getElementById('passwordForm')?.addEventListener('submit', function(e) {
            const currentPass = document.getElementById('currentPass').value;
            const newPass = document.getElementById('newPass').value;
            const confirmPass = document.getElementById('newPassConfirm').value;
            
            let errors = [];
            
            if (currentPass.length === 0) {
                errors.push('Password lama harus diisi');
            }
            if (newPass.length < 8) {
                errors.push('Password baru harus minimal 8 karakter');
            }
            if (newPass !== confirmPass) {
                errors.push('Password konfirmasi tidak cocok dengan password baru');
            }
            
            if (errors.length > 0) {
                e.preventDefault();
                alert('Perbaiki kesalahan berikut:\n• ' + errors.join('\n• '));
            }
        });
    </script>
@endsection
