<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Atur Ulang Password - SPK Jurusan Polije</title>
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
    <style>
        .gradient-purple {
            background: linear-gradient(135deg, #9333ea 0%, #6366f1 100%);
        }
        .bg-cream {
            background-color: #F8FAFC;
        }
        @keyframes pulse-soft {
            0%, 100% { opacity: 1; }
            50% { opacity: 0.85; }
        }
        .animate-pulse-soft {
            animation: pulse-soft 3s ease-in-out infinite;
        }
        @keyframes slide-down {
            from {
                opacity: 0;
                transform: translateY(-10px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }
        .animate-slide-down {
            animation: slide-down 0.3s ease-out;
        }
    </style>
</head>
<body class="bg-cream">
    <div class="min-h-screen flex items-center justify-center px-4 py-8">
        <div class="w-full max-w-md">
            <!-- Header -->
            <div class="text-center mb-8">
                <div class="inline-flex items-center justify-center w-16 h-16 rounded-full bg-green-100 mb-4">
                    <span class="text-3xl">🔄</span>
                </div>
                <h1 class="text-3xl sm:text-4xl font-bold text-gray-900 mb-2">Atur Ulang Password</h1>
                <p class="text-gray-600 text-sm sm:text-base">Buat password baru yang kuat untuk akun kamu.</p>
            </div>

            <!-- Info Box -->
            <div class="bg-green-50 border-l-4 border-green-600 rounded-lg p-4 mb-6">
                <p class="text-sm text-green-900">
                    Pilih password yang kuat (minimal 8 karakter) dan pastikan kamu ingat untuk login di lain waktu.
                </p>
            </div>

            <!-- Success Message (if redirected back with message) -->
            @if (session('status'))
                <div class="mb-6 bg-gradient-to-r from-green-50 to-emerald-50 border-l-4 border-green-500 rounded-lg p-5 shadow-lg border-r border-green-300 animate-slide-down">
                    <div class="flex items-start gap-3">
                        <span class="text-2xl flex-shrink-0 mt-0.5">✅</span>
                        <div>
                            <h4 class="text-green-900 font-bold text-base">Berhasil!</h4>
                            <p class="text-green-800 text-sm mt-1 leading-relaxed">{{ session('status') }}</p>
                        </div>
                    </div>
                </div>
            @endif

            <!-- Error Messages -->
            @if ($errors->any())
                <div class="mb-6 bg-gradient-to-r from-red-50 to-pink-50 border-l-4 border-red-500 rounded-lg p-5 shadow-lg border-r border-red-300 animate-slide-down">
                    <div class="flex items-start gap-3">
                        <span class="text-2xl flex-shrink-0 mt-0.5">❌</span>
                        <div>
                            <h4 class="text-red-900 font-bold text-base">Ada Kesalahan!</h4>
                            <ul class="text-red-800 text-sm mt-2 space-y-1.5">
                                @foreach ($errors->all() as $error)
                                    <li class="flex items-start gap-2">
                                        <span class="text-red-500 mt-0.5">•</span>
                                        <span>{{ $error }}</span>
                                    </li>
                                @endforeach
                            </ul>
                            
                            @if($errors->has('token'))
                                <div class="mt-4 pt-3 border-t border-red-200">
                                    <p class="text-red-700 text-xs font-semibold mb-2">⏰ Token telah kadaluarsa?</p>
                                    <p class="text-red-700 text-xs mb-3">Minta tautan reset password yang baru:</p>
                                    <a href="{{ route('password.request') }}" class="inline-block px-4 py-2 bg-red-600 text-white text-xs font-semibold rounded hover:bg-red-700 transition">
                                        🔄 Minta Link Reset Baru
                                    </a>
                                </div>
                            @endif

                            @if(!$errors->has('token'))
                                <p class="text-red-700 text-xs mt-3 font-semibold">💡 Pastikan:</p>
                                <ul class="text-red-700 text-xs mt-1 space-y-0.5 ml-2">
                                    <li>✓ Password minimal 8 karakter</li>
                                    <li>✓ Kedua password sama persis</li>
                                    <li>✓ Tidak menggunakan password lama</li>
                                </ul>
                            @endif
                        </div>
                    </div>
                </div>
            @endif

            <!-- Form -->
            <form method="POST" action="{{ route('password.store') }}" class="bg-white rounded-xl shadow-lg p-8 border-t-4 border-green-600" onsubmit="handleSubmit(event)">
                @csrf

                <!-- Password Reset Token -->
                <input type="hidden" name="token" value="{{ $request->route('token') }}">

                <!-- Email Address (Read-only) -->
                <div class="mb-6">
                    <label for="email" class="block text-sm font-semibold text-gray-700 mb-2">📧 Email</label>
                    <input 
                        id="email" 
                        type="email" 
                        value="{{ $request->email }}" 
                        disabled
                        class="w-full px-4 py-3 border-2 border-gray-300 rounded-lg bg-gray-100 text-gray-600 text-sm cursor-not-allowed"
                    />
                    <input type="hidden" name="email" value="{{ $request->email }}">
                </div>

                <!-- Password -->
                <div class="mb-6">
                    <label for="password" class="block text-sm font-semibold text-gray-700 mb-2">🔐 Password Baru</label>
                    <div class="relative">
                        <input 
                            id="password" 
                            type="password" 
                            name="password" 
                            required 
                            autocomplete="new-password"
                            placeholder="minimal 8 karakter"
                            class="w-full px-4 py-3 border-2 border-gray-300 rounded-lg focus:outline-none focus:border-green-600 focus:ring-2 focus:ring-green-200 transition text-sm pr-12 @error('password') border-red-500 @enderror"
                        />
                        <button 
                            type="button" 
                            onclick="togglePassword('password', this)"
                            class="absolute right-3 top-3 text-gray-500 hover:text-gray-700 text-xl"
                        >
                            👁️
                        </button>
                    </div>
                    @error('password')
                        <span class="text-red-500 text-sm mt-1 block">⚠️ {{ $message }}</span>
                    @enderror
                </div>

                <!-- Confirm Password -->
                <div class="mb-6">
                    <label for="password_confirmation" class="block text-sm font-semibold text-gray-700 mb-2">✅ Konfirmasi Password</label>
                    <div class="relative">
                        <input 
                            id="password_confirmation" 
                            type="password" 
                            name="password_confirmation" 
                            required 
                            autocomplete="new-password"
                            placeholder="ulangi password yang sama"
                            class="w-full px-4 py-3 border-2 border-gray-300 rounded-lg focus:outline-none focus:border-green-600 focus:ring-2 focus:ring-green-200 transition text-sm pr-12 @error('password_confirmation') border-red-500 @enderror"
                        />
                        <button 
                            type="button" 
                            onclick="togglePassword('password_confirmation', this)"
                            class="absolute right-3 top-3 text-gray-500 hover:text-gray-700 text-xl"
                        >
                            👁️
                        </button>
                    </div>
                    @error('password_confirmation')
                        <span class="text-red-500 text-sm mt-1 block">⚠️ {{ $message }}</span>
                    @enderror
                </div>

                <!-- Submit Button -->
                <button 
                    id="submitBtn"
                    type="submit" 
                    class="w-full bg-gradient-to-r from-green-600 to-emerald-600 text-white font-bold py-3 px-4 rounded-lg hover:shadow-lg active:scale-95 transition text-base mb-4 disabled:opacity-50 disabled:cursor-not-allowed"
                >
                    ✨ Simpan Password Baru
                </button>

                <!-- Back to Login -->
                <div class="text-center">
                    <p class="text-gray-600 text-sm">
                        <a href="{{ route('login') }}" class="text-green-600 hover:text-green-700 font-semibold">Kembali ke Login</a>
                    </p>
                </div>
            </form>

            <!-- Security Tips -->
            <div class="mt-6 text-sm text-gray-600">
                <p class="font-semibold mb-2">💡 Tips Password yang Aman:</p>
                <ul class="space-y-1 text-xs">
                    <li>✓ Gunakan kombinasi huruf besar, kecil, angka, dan simbol</li>
                    <li>✓ Hindari kata-kata yang mudah ditebak</li>
                    <li>✓ Jangan gunakan informasi pribadi</li>
                    <li>✓ Minimal 8 karakter, semakin panjang semakin aman</li>
                </ul>
            </div>
        </div>
    </div>

    <script>
        function togglePassword(inputId, button) {
            const input = document.getElementById(inputId);
            if (input.type === 'password') {
                input.type = 'text';
                button.textContent = '🙈';
            } else {
                input.type = 'password';
                button.textContent = '👁️';
            }
        }

        function handleSubmit(event) {
            const password = document.getElementById('password').value;
            const passwordConfirmation = document.getElementById('password_confirmation').value;
            const submitBtn = document.getElementById('submitBtn');

            // Validasi client-side
            if (password !== passwordConfirmation) {
                event.preventDefault();
                alert('❌ Password tidak sama! Pastikan kedua password sama persis.');
                return false;
            }

            if (password.length < 8) {
                event.preventDefault();
                alert('❌ Password minimal 8 karakter!');
                return false;
            }

            // Disable button dan tampilkan loading state
            submitBtn.disabled = true;
            submitBtn.textContent = '⏳ Sedang Menyimpan...';
            submitBtn.style.opacity = '0.7';

            return true;
        }
    </script>
</body>
</html>
