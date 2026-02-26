<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - Sistem Pemilihan Jurusan</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }
        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background-color: #f5f5f5;
        }
        .container-wrapper {
            display: flex;
            height: 100vh;
            width: 100%;
            background-color: #5B7B89;
        }
        .left-section {
            width: 50%;
            background-color: #5B7B89;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 40px;
        }
        .image-placeholder {
            text-align: center;
        }
        .image-area {
            width: 240px;
            height: 300px;
            background-color: rgba(255, 255, 255, 0.1);
            border: 3px dashed #FCD34D;
            border-radius: 12px;
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            margin-bottom: 30px;
            color: #FCD34D;
            font-size: 16px;
            text-align: center;
            padding: 20px;
        }
        .image-area span {
            font-size: 48px;
            display: block;
            margin-bottom: 10px;
        }
        .brand-info h1 {
            font-size: 50px;
            font-weight: 900;
            color: #FFFFFF;
            margin-bottom: 8px;
            letter-spacing: 3px;
            text-shadow: 0 2px 10px rgba(0, 0, 0, 0.3);
        }
        .brand-info p {
            font-size: 22px;
            color: #FCD34D;
            font-weight: 700;
            letter-spacing: 1px;
        }
        .right-section {
            width: 50%;
            background-color: #FFFFFF;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 40px;
            overflow-y: auto;
        }
        .form-wrapper {
            width: 100%;
            max-width: 420px;
        }
        .form-title {
            font-size: 48px;
            font-weight: 800;
            color: #111827;
            text-align: center;
            margin-bottom: 12px;
            letter-spacing: -1px;
        }
        .form-subtitle {
            text-align: center;
            color: #6B7280;
            font-size: 16px;
            margin-bottom: 28px;
            font-weight: 500;
        }
        .form-group {
            margin-bottom: 22px;
        }
        .form-group label {
            display: block;
            font-weight: 600;
            color: #374151;
            margin-bottom: 9px;
            font-size: 15px;
        }
        .form-group input {
            width: 100%;
            padding: 14px 16px;
            background-color: #E5E7EB;
            border: 2px solid transparent;
            border-radius: 8px;
            font-size: 15px;
            color: #111827;
            transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
            font-weight: 500;
        }
        .form-group input::placeholder {
            color: #9CA3AF;
            font-weight: 400;
        }
        .form-group input:focus {
            outline: none;
            background-color: #FFFFFF;
            border-color: #3B82F6;
            box-shadow: 0 0 0 4px rgba(59, 130, 246, 0.15);
        }
        .form-links {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin: 18px 0 28px 0;
            font-size: 15px;
        }
        .form-links a {
            color: #4B5563;
            text-decoration: none;
            font-weight: 600;
            transition: color 0.3s ease;
        }
        .form-links a:hover {
            color: #2563EB;
        }
        .btn-submit {
            width: 100%;
            padding: 15px 20px;
            background: linear-gradient(135deg, #000000 0%, #1F2937 100%);
            color: white;
            border: none;
            border-radius: 8px;
            font-weight: 700;
            font-size: 16px;
            cursor: pointer;
            transition: all 0.3s ease;
            text-transform: uppercase;
            letter-spacing: 1.5px;
            box-shadow: 0 4px 15px rgba(0, 0, 0, 0.3);
        }
        .btn-submit:hover {
            transform: translateY(-2px);
            box-shadow: 0 8px 25px rgba(0, 0, 0, 0.4);
            background: linear-gradient(135deg, #1F2937 0%, #374151 100%);
        }
        .btn-submit:active {
            transform: translateY(0);
        }
        .error-alert {
            background-color: #FEE2E2;
            border: 2px solid #FECACA;
            color: #DC2626;
            padding: 14px 16px;
            border-radius: 8px;
            margin-bottom: 24px;
            font-size: 14px;
            font-weight: 500;
        }
        .error-alert p {
            margin: 4px 0;
            line-height: 1.6;
        }
        @media (max-width: 1024px) {
            .left-section {
                display: none;
            }
            .right-section {
                width: 100%;
            }
        }
    </style>
</head>
<body>
    <div class="container-wrapper">
        <!-- LEFT SECTION - FOR USER IMAGE -->
        <div class="left-section">
            <div class="image-placeholder">
                <div class="image-area">
                    <span>🖼️</span>
                    Ganti dengan Gambar Anda
                </div>
                <div class="brand-info">
                    <h1>POLIJE</h1>
                    <p>Sistem Pemilihan Jurusan</p>
                </div>
            </div>
        </div>

        <!-- RIGHT SECTION - LOGIN FORM -->
        <div class="right-section">
            <div class="form-wrapper">
                <h2 class="form-title">WELCOME!</h2>
                <p class="form-subtitle">Masuk untuk melanjutkan</p>

                @if ($errors->any())
                    <div class="error-alert">
                        @foreach ($errors->all() as $error)
                            <p>• {{ $error }}</p>
                        @endforeach
                    </div>
                @endif

                <form method="POST" action="{{ route('login') }}">
                    @csrf

                    <div class="form-group">
                        <label for="email">Email</label>
                        <input 
                            id="email" 
                            type="email" 
                            name="email" 
                            value="{{ old('email') }}" 
                            required 
                            autofocus
                            placeholder="Masukkan email Anda" />
                    </div>

                    <div class="form-group" style="position: relative;">
                        <label for="password">Password</label>
                        <div style="position: relative; display: flex; align-items: center;">
                            <input 
                                id="password" 
                                type="password"
                                name="password"
                                required
                                placeholder="Masukkan password Anda"
                                style="width: 100%; padding-right: 45px;" />
                            <button type="button" style="position: absolute; right: 12px; background: none; border: none; cursor: pointer; color: #5B7B89; font-size: 18px;" onclick="togglePasswordVisibility('password', this)">👁️</button>
                        </div>
                    </div>

                    <div class="form-links">
                        @if (Route::has('password.request'))
                            <a href="{{ route('password.request') }}">Lupa password?</a>
                        @else
                            <span></span>
                        @endif
                        <a href="{{ route('register') }}">Atau daftar</a>
                    </div>

                    <button type="submit" class="btn-submit">LOGIN</button>
                </form>
            </div>
        </div>
    </div>

    <script>
        function togglePasswordVisibility(inputId, buttonElement) {
            const input = document.getElementById(inputId);
            const isPassword = input.type === 'password';
            input.type = isPassword ? 'text' : 'password';
        }
    </script>
</body>
</html>
