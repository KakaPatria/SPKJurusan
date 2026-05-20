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
                <!-- Show token input panel immediately after successful send -->
                @php
                    $prefillCode = '';
                    $emailForCode = old('email') ?? request()->query('email') ?? request()->input('email');
                    if (!$emailForCode) {
                        // try to retrieve email from old input stored in session
                        $old = session()->get('_old_input', []);
                        if (!empty($old['email'])) $emailForCode = $old['email'];
                    }
                    if ($emailForCode) {
                        try {
                            $expiryMinutes = config('auth.passwords.users.expire', 60);
                            $cutoff = \Carbon\Carbon::now()->subMinutes($expiryMinutes);
                            $row = \Illuminate\Support\Facades\DB::table('password_reset_codes')
                                ->where('email', $emailForCode)
                                ->where('created_at', '>=', $cutoff)
                                ->first();
                            if ($row) $prefillCode = $row->code;
                        } catch (\Throwable $e) {
                            $prefillCode = '';
                        }
                    }
                @endphp

                <div id="postSendTokenPanel" class="mb-6 bg-white rounded-xl shadow-lg p-6 border-t-4 border-gray-200">
                    <h4 class="text-sm font-bold text-gray-700 mb-2">Sudah menerima kode 6-digit?</h4>
                    <p class="text-xs text-gray-500 mb-3">Jika sudah, masukkan kode di bawah agar Anda bisa langsung mengganti password di halaman ini.</p>

                    <form method="POST" action="{{ route('password.reset.with_code') }}" onsubmit="return handleTokenSubmit(event)">
                        @csrf
                        <div class="grid grid-cols-1 gap-3">
                            <div>
                                <label class="block text-xs font-semibold text-gray-700 mb-1">Email</label>
                                <input type="email" name="email" id="post_token_email" value="{{ old('email') ?? request()->query('email', '') }}" required class="w-full px-3 py-2 border rounded-md text-sm focus:ring-2 focus:ring-purple-200 focus:border-purple-500" />
                            </div>
                            <div>
                                <label class="block text-xs font-semibold text-gray-700 mb-1">Kode 6-digit</label>
                                <input type="text" name="token" id="post_token_input" inputmode="numeric" pattern="\d{6}" placeholder="123456" value="{{ $prefillCode }}" required class="w-full px-3 py-2 border rounded-md text-sm focus:ring-2 focus:ring-purple-200 focus:border-purple-500" />
                            </div>

                            <div id="passwordFields" class="hidden">
                                <div>
                                    <label class="block text-xs font-semibold text-gray-700 mb-1">Password Baru</label>
                                    <input type="password" name="password" id="post_token_password" class="w-full px-3 py-2 border rounded-md text-sm focus:ring-2 focus:ring-purple-200 focus:border-purple-500" />
                                </div>
                                <div>
                                    <label class="block text-xs font-semibold text-gray-700 mb-1">Konfirmasi Password</label>
                                    <input type="password" name="password_confirmation" id="post_token_password_confirmation" class="w-full px-3 py-2 border rounded-md text-sm focus:ring-2 focus:ring-purple-200 focus:border-purple-500" />
                                </div>
                            </div>
                        </div>

                        <div class="mt-4 flex gap-2">
                            <button type="button" id="verifyCodeBtn" onclick="verifyCode()" class="inline-flex items-center gap-2 bg-gray-200 text-gray-800 text-sm px-4 py-2 rounded-md font-semibold">🔎 Verifikasi Kode</button>
                            <button type="submit" id="postTokenSubmitBtn" disabled class="inline-flex items-center gap-2 bg-gradient-to-r from-purple-600 to-indigo-600 text-white text-sm px-4 py-2 rounded-md font-semibold opacity-60 cursor-not-allowed">🔒 Reset dengan Kode</button>
                        </div>
                    </form>
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

            <!-- (Token panel removed) -->
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

            // Save email locally so we can prefill the post-send token panel
            try { localStorage.setItem('password_reset_email', email); } catch(e){}

            // Disable button dan tampilkan loading state
            submitBtn.disabled = true;
            submitBtn.textContent = '⏳ Sedang Mengirim...';
            submitBtn.style.opacity = '0.7';

            return true;
        }
    </script>
    <script>
        function handleTokenSubmit(event) {
            const email = document.getElementById('post_token_email').value.trim();
            const token = document.getElementById('post_token_input').value.trim();
            const pass = document.getElementById('post_token_password').value;
            const pass2 = document.getElementById('post_token_password_confirmation').value;
            const submitBtn = document.getElementById('postTokenSubmitBtn');

            if (!email || !token || !pass) {
                alert('Isi semua field: email, kode, dan password.');
                event.preventDefault();
                return false;
            }

            if (!/^\d{6}$/.test(token)) {
                alert('Kode harus 6 digit angka.');
                event.preventDefault();
                return false;
            }

            if (pass.length < 8) {
                alert('Password minimal 8 karakter.');
                event.preventDefault();
                return false;
            }

            if (pass !== pass2) {
                alert('Konfirmasi password tidak cocok.');
                event.preventDefault();
                return false;
            }

            submitBtn.disabled = true;
            submitBtn.textContent = '⏳ Memproses...';
            return true;
        }

        // autofocus token input when panel is present
        document.addEventListener('DOMContentLoaded', function () {
            try {
                const panel = document.getElementById('postSendTokenPanel');
                // if there's a saved email from before submit, prefill and lock the email field
                const savedEmail = (function(){ try{ return localStorage.getItem('password_reset_email') }catch(e){return null} })();
                const emailInput = document.getElementById('post_token_email');
                if (savedEmail && emailInput) {
                    emailInput.value = savedEmail;
                    emailInput.readOnly = true;
                    emailInput.classList.add('bg-gray-100');
                }
                if (panel) {
                    const t = document.getElementById('post_token_input');
                    if (t) t.focus();
                }
            } catch (e) {}
        });
        
        async function verifyCode() {
            const email = document.getElementById('post_token_email').value.trim();
            const token = document.getElementById('post_token_input').value.trim();
            const verifyBtn = document.getElementById('verifyCodeBtn');
            const submitBtn = document.getElementById('postTokenSubmitBtn');
            const passwordFields = document.getElementById('passwordFields');

            if (!email || !/^\d{6}$/.test(token)) {
                alert('Masukkan email dan kode 6 digit yang valid terlebih dahulu.');
                return;
            }

            verifyBtn.disabled = true;
            verifyBtn.textContent = '⏳ Memeriksa...';

            try {
                const resp = await fetch('{{ route('password.verify.code') }}', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('input[name="_token"]').value,
                        'Accept': 'application/json'
                    },
                    body: JSON.stringify({email: email, token: token})
                });

                const data = await resp.json();
                if (resp.ok && data.ok) {
                    // reveal password fields and enable submit
                    passwordFields.classList.remove('hidden');
                    submitBtn.disabled = false;
                    submitBtn.classList.remove('opacity-60','cursor-not-allowed');
                    verifyBtn.textContent = '✔️ Kode Valid';
                    verifyBtn.classList.add('bg-green-100');
                    // focus password
                    setTimeout(()=>{ document.getElementById('post_token_password').focus(); }, 50);
                    // clear stored email after successful verification
                    try { localStorage.removeItem('password_reset_email'); } catch(e){}
                } else {
                    verifyBtn.disabled = false;
                    verifyBtn.textContent = '🔎 Verifikasi Kode';
                    alert(data.message || (data.errors || ['Token tidak valid'])[0]);
                }
            } catch (err) {
                verifyBtn.disabled = false;
                verifyBtn.textContent = '🔎 Verifikasi Kode';
                alert('Terjadi kesalahan saat memverifikasi kode.');
            }
        }
    </script>
    <script>
        function toggleTokenForm(e) {
            e.preventDefault();
            const panel = document.getElementById('tokenForm');
            panel.classList.toggle('hidden');
            const btn = document.getElementById('toggleTokenForm');
            btn.textContent = panel.classList.contains('hidden') ? 'Gunakan token' : 'Sembunyikan';
        }

        function hideTokenForm() {
            const panel = document.getElementById('tokenForm');
            panel.classList.add('hidden');
            const btn = document.getElementById('toggleTokenForm');
            btn.textContent = 'Gunakan token';
        }

        function handleTokenSubmit(event) {
            const email = document.getElementById('token_email').value.trim();
            const token = document.getElementById('token_input').value.trim();
            const pass = document.getElementById('token_password').value;
            const pass2 = document.getElementById('token_password_confirmation').value;
            const submitBtn = document.getElementById('tokenSubmitBtn');

            if (!email || !token || !pass) {
                alert('Isi semua field token, email, dan password.');
                event.preventDefault();
                return false;
            }

            if (pass.length < 8) {
                alert('Password minimal 8 karakter.');
                event.preventDefault();
                return false;
            }

            if (pass !== pass2) {
                alert('Konfirmasi password tidak cocok.');
                event.preventDefault();
                return false;
            }

            submitBtn.disabled = true;
            submitBtn.textContent = '⏳ Memproses...';
            return true;
        }
    </script>
    
</body>
</html>
