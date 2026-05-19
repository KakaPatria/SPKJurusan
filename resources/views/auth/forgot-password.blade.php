<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Lupa Password - SPK Jurusan Polije</title>
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
    </style>
</head>
<body class="bg-cream">
    <div class="min-h-screen flex items-center justify-center px-4 py-8">
        <div class="w-full max-w-md">
            <!-- Header -->
            <div class="text-center mb-8">
                <div class="inline-flex items-center justify-center w-16 h-16 rounded-full bg-purple-100 mb-4">
                    <span class="text-3xl">🔐</span>
                </div>
                <h1 class="text-3xl sm:text-4xl font-bold text-gray-900 mb-2">Lupa Password?</h1>
                <p class="text-gray-600 text-sm sm:text-base">Tenang, kami bantu kamu atur ulang password dengan mudah.</p>
            </div>

            <!-- Info Box -->
            <div class="bg-purple-50 border-l-4 border-purple-600 rounded-lg p-4 mb-6">
                <p class="text-sm text-purple-900">
                    Masukkan email kamu yang terdaftar, dan kami akan mengirimkan tautan untuk mengatur ulang password ke inbox kamu.
                </p>
            </div>

            <!-- Session Status / Success Message -->
            @if (session('status'))
                <div class="mb-6 bg-gradient-to-r from-green-50 to-emerald-50 border-l-4 border-green-500 rounded-lg p-5 shadow-lg border-r border-green-300 animate-pulse-soft">
                    <div class="flex items-start gap-3">
                        <span class="text-2xl flex-shrink-0 mt-0.5">✅</span>
                        <div>
                            <h4 class="text-green-900 font-bold text-base">Berhasil!</h4>
                            <p class="text-green-800 text-sm mt-1 leading-relaxed">{{ session('status') }}</p>
                            <p class="text-green-700 text-xs mt-2 font-semibold">📧 Cek email Anda untuk tautan reset password (cek juga folder spam/promotions)</p>
                        </div>
                    </div>
                </div>
            @endif

            <!-- Error Messages -->
            @if ($errors->any())
                <div class="mb-6 bg-gradient-to-r from-red-50 to-pink-50 border-l-4 border-red-500 rounded-lg p-5 shadow-lg border-r border-red-300">
                    <div class="flex items-start gap-3">
                        <span class="text-2xl flex-shrink-0 mt-0.5">❌</span>
                        <div>
                            <h4 class="text-red-900 font-bold text-base">Gagal!</h4>
                            <ul class="text-red-800 text-sm mt-2 space-y-1.5">
                                @foreach ($errors->all() as $error)
                                    <li class="flex items-start gap-2">
                                        <span class="text-red-500 mt-0.5">•</span>
                                        <span>{{ $error }}</span>
                                    </li>
                                @endforeach
                            </ul>
                        </div>
                    </div>
                </div>
            @endif

            <!-- Form -->
            <form method="POST" action="{{ route('password.email') }}" class="bg-white rounded-xl shadow-lg p-8 border-t-4 border-purple-600" onsubmit="handleForgotSubmit(event)">
                @csrf

                <!-- Email Address -->
                <div class="mb-6">
                    <label for="email" class="block text-sm font-semibold text-gray-700 mb-2">📧 Email</label>
                    <input 
                        id="email" 
                        type="email" 
                        name="email" 
                        value="{{ old('email') ?? request()->query('email', '') }}" 
                        required 
                        autofocus
                        placeholder="masukkan email kamu"
                        class="w-full px-4 py-3 border-2 border-gray-300 rounded-lg focus:outline-none focus:border-purple-600 focus:ring-2 focus:ring-purple-200 transition text-sm @error('email') border-red-500 @enderror"
                    />
                    @error('email')
                        <span class="text-red-500 text-sm mt-1 block">⚠️ {{ $message }}</span>
                    @enderror
                </div>

                <!-- Submit Button -->
                <button 
                    id="submitBtn"
                    type="submit" 
                    class="w-full bg-gradient-to-r from-purple-600 to-indigo-600 text-white font-bold py-3 px-4 rounded-lg hover:shadow-lg active:scale-95 transition text-base mb-4 disabled:opacity-50 disabled:cursor-not-allowed"
                >
                    🔗 Kirim Tautan Reset Password
                </button>

                <!-- Back to Login -->
                <div class="text-center">
                    <p class="text-gray-600 text-sm">
                        Ingat passwordnya? 
                        <a href="{{ route('login') }}" class="text-purple-600 hover:text-purple-700 font-semibold">Kembali ke Login</a>
                    </p>
                </div>
            </form>

            <!-- Footer Info -->
            <div class="text-center mt-6 text-xs text-gray-500">
                <p>💡 Jika email tidak masuk, cek folder spam kamu.</p>
            </div>
        </div>
    </div>

    <script>
        function handleForgotSubmit(event) {
            const email = document.getElementById('email').value;
            const submitBtn = document.getElementById('submitBtn');

            // Validasi email
            const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
            if (!emailRegex.test(email)) {
                event.preventDefault();
                alert('❌ Email tidak valid! Gunakan format yang benar (contoh: nama@gmail.com)');
                return false;
            }

            // Disable button dan tampilkan loading state
            submitBtn.disabled = true;
            submitBtn.textContent = '⏳ Sedang Mengirim...';
            submitBtn.style.opacity = '0.7';

            return true;
        }
    </script>
</body>
</html>
