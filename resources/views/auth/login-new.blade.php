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
        }
        .container-wrapper {
            display: flex;
            flex-direction: column;
            min-height: 100vh;
            width: 100%;
            background-color: #5B7B89;
        }
        .left-section {
            width: 100%;
            background-color: #5B7B89;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 20px;
            min-height: 200px;
            order: -1;
        }
        .image-placeholder {
            text-align: center;
        }
        .image-area {
            width: 180px;
            height: 240px;
            background-color: rgba(255, 255, 255, 0.1);
            border: 2px dashed #FCD34D;
            border-radius: 12px;
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            margin-bottom: 20px;
            color: #FCD34D;
            font-size: 14px;
            text-align: center;
            padding: 15px;
            line-height: 1.4;
            overflow: hidden;
        }
        .image-area img {
            width: 100%;
            height: 100%;
            object-fit: cover;
            display: block;
        }
        .image-area span {
            font-size: 40px;
            display: block;
            margin-bottom: 8px;
        }
        .brand-info h1 {
            font-size: 32px;
            font-weight: 900;
            color: #FFFFFF;
            margin-bottom: 6px;
            letter-spacing: 2px;
            text-shadow: 0 2px 10px rgba(0, 0, 0, 0.3);
        }100%;
            background-color: #FFFFFF;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 20px;
            flex: 1g: 0.5px;
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
        .form-title {32px;
            font-weight: 800;
            color: #111827;
            text-align: center;
            margin-bottom: 8px;
            letter-spacing: -0.5px;
        }
        .form-subtitle {
            text-align: center;
            color: #6B7280;
            font-size: 14px;
            margin-bottom: 1
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
        /* Tablet and Desktop */
        @media (min-width: 768px) {
            .container-wrapper {
                flex-direction: row;
            }
            .left-section {
                width: 50%;
                min-height: auto;
                padding: 40px;
                order: 0;
            }
            .image-area {
                width: 240px;
                height: 300px;
                font-size: 16px;
                padding: 0;
                margin-bottom: 30px;
            }
            .image-area span {
                font-size: 48px;
                margin-bottom: 10px;
            }
            .brand-info h1 {
                font-size: 50px;
                margin-bottom: 8px;
                letter-spacing: 3px;
            }
            .brand-info p {
                font-size: 22px;
                letter-spacing: 1px;
            }
            .right-section {
                width: 50%;
                padding: 40px;
            }
            .form-title {
                font-size: 48px;
                margin-bottom: 12px;
                letter-spacing: -1px;
            }
            .form-subtitle {
                font-size: 16px;
                margin-bottom: 28px;
            }
            .form-group {
                margin-bottom: 20px;
            }
            .form-group label {
                font-size: 15px;
                margin-bottom: 8px;
            }
            .form-group input,
            .form-group select {
                padding: 13px 15px;
                font-size: 15px;
            }
            .form-links {
                margin: 18px 0 24px 0;
                font-size: 15px;
            }
            .btn-submit {
                padding: 15px 20px;
                font-size: 16px;
                letter-spacing: 1.5px;
            }
            .error-alert {
                padding: 14px 16px;
                font-size: 14px;
                margin-bottom: 24px;
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
                    <img src="{{ asset('images/SMA%20BIMA.jpg') }}" alt="SMA BIMA" />
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
                <h2 class="form-title">Selamat Datang</h2>
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
