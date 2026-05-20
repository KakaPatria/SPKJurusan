<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Reset Password - Sistem Pemilihan Jurusan</title>
    <style>
        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background-color: #f3f4f6;
            margin: 0;
            padding: 20px;
            line-height: 1.6;
            color: #374151;
        }
        .container {
            max-width: 600px;
            margin: 0 auto;
            background-color: white;
            border-radius: 12px;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
            overflow: hidden;
        }
        .header {
            background: linear-gradient(135deg, #6B7280 0%, #8B95A5 100%);
            padding: 40px 20px;
            text-align: center;
            color: white;
        }
        .header h1 {
            margin: 0;
            font-size: 32px;
            font-weight: 700;
            letter-spacing: 2px;
        }
        .header p {
            margin: 8px 0 0 0;
            font-size: 16px;
            font-weight: 600;
            letter-spacing: 0.5px;
            color: #C9A961;
        }
        .content {
            padding: 40px;
        }
        .content h2 {
            color: #111827;
            font-size: 24px;
            margin: 0 0 16px 0;
            font-weight: 700;
        }
        .greeting {
            color: #4B5563;
            font-size: 16px;
            margin-bottom: 20px;
            font-weight: 500;
        }
        .message {
            background-color: #f0f4f8;
            border-left: 4px solid #6B7280;
            padding: 16px;
            margin: 24px 0;
            border-radius: 6px;
            color: #374151;
            font-size: 15px;
            line-height: 1.8;
        }
        .cta-section {
            text-align: center;
            margin: 32px 0;
        }
        .cta-button {
            display: inline-block;
            background: linear-gradient(135deg, #6B7280 0%, #8B95A5 100%);
            color: white;
            padding: 16px 48px;
            border-radius: 8px;
            text-decoration: none;
            font-weight: 700;
            font-size: 16px;
            letter-spacing: 1px;
            transition: all 0.3s ease;
            box-shadow: 0 4px 15px rgba(107, 114, 128, 0.3);
        }
        .cta-button:hover {
            transform: translateY(-2px);
            box-shadow: 0 6px 20px rgba(107, 114, 128, 0.4);
        }
        .steps {
            background-color: #f9fafb;
            border: 2px solid #e5e7eb;
            border-radius: 8px;
            padding: 20px;
            margin: 24px 0;
        }
        .steps h3 {
            color: #111827;
            font-size: 16px;
            font-weight: 700;
            margin: 0 0 12px 0;
        }
        .step {
            display: flex;
            align-items: flex-start;
            margin: 12px 0;
            color: #374151;
            font-size: 15px;
        }
        .step-number {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            width: 28px;
            height: 28px;
            background: linear-gradient(135deg, #6B7280 0%, #8B95A5 100%);
            color: white;
            border-radius: 50%;
            font-weight: 700;
            font-size: 14px;
            margin-right: 12px;
            flex-shrink: 0;
        }
        .step-text {
            flex: 1;
        }
        .warning {
            background-color: #fef3c7;
            border-left: 4px solid #f59e0b;
            padding: 14px;
            margin: 24px 0;
            border-radius: 6px;
            color: #78350f;
            font-size: 14px;
            line-height: 1.6;
            font-weight: 500;
        }
        .warning strong {
            color: #b45309;
        }
        .footer {
            background-color: #f9fafb;
            border-top: 1px solid #e5e7eb;
            padding: 24px 40px;
            color: #6B7280;
            font-size: 13px;
            line-height: 1.6;
        }
        .footer p {
            margin: 8px 0;
        }
        .footer strong {
            color: #374151;
        }
        .contact-info {
            background-color: #f0f4f8;
            border-radius: 6px;
            padding: 14px;
            margin-top: 12px;
            font-size: 13px;
            color: #374151;
        }
        @media only screen and (max-width: 600px) {
            body {
                padding: 10px;
            }
            .content {
                padding: 24px;
            }
            .header {
                padding: 30px 20px;
            }
            .header h1 {
                font-size: 24px;
            }
            .cta-button {
                padding: 14px 32px;
                font-size: 15px;
            }
        }
    </style>
</head>
<body>
    <div class="container">
        <!-- Header -->
        <div class="header">
            <h1>🔐 POLIJE</h1>
            <p>Sistem Pemilihan Jurusan</p>
        </div>

        <!-- Main Content -->
        <div class="content">
            <h2>Halo, {{ $user->name }}! 👋</h2>
            
            <p class="greeting">
                Kami menerima permintaan untuk mengatur ulang password akunmu. Jika kamu yang melakukan ini, ikuti langkah-langkah di bawah untuk membuat password baru.
            </p>

            <div class="message">
                <strong>⏰ Penting:</strong> Link reset password ini hanya berlaku selama <strong>{{ $expiresIn }} menit</strong>. Jika sudah kadaluarsa, silakan minta link baru di halaman "Lupa Password".
            </div>

            <!-- CTA Button -->
            <div class="cta-section">
                <a href="{{ $resetUrl }}" class="cta-button">🔑 RESET PASSWORD</a>
            </div>

            <!-- Code alternative -->
            @if(!empty($code))
            <div style="margin-top:18px;">
                <p style="font-size:14px;color:#374151;margin-bottom:8px;">Atau, jika Anda ingin melakukan reset tanpa membuka tautan, salin <strong>kode token 6 digit</strong> di bawah ini dan masukkan pada form "Gunakan token" di halaman Lupa Password:</p>
                <div style="background:#f3f4f6;border:1px dashed #e5e7eb;padding:14px;border-radius:6px;font-family:monospace;color:#111827;font-weight:700;word-break:break-all;font-size:20px;letter-spacing:4px;text-align:center;">{{ $code }}</div>
            </div>
            @endif

            <!-- Steps -->
            <div class="steps">
                <h3>📋 Langkah-Langkah:</h3>
                <div class="step">
                    <div class="step-number">1</div>
                    <div class="step-text">Klik tombol <strong>"RESET PASSWORD"</strong> di atas atau copy-paste link ke browser</div>
                </div>
                <div class="step">
                    <div class="step-number">2</div>
                    <div class="step-text">Isi form dengan password baru yang kuat (minimal 8 karakter)</div>
                </div>
                <div class="step">
                    <div class="step-number">3</div>
                    <div class="step-text">Konfirmasi password baru dengan memasukkan ulang</div>
                </div>
                <div class="step">
                    <div class="step-number">4</div>
                    <div class="step-text">Klik tombol <strong>"Simpan Password"</strong></div>
                </div>
                <div class="step">
                    <div class="step-number">5</div>
                    <div class="step-text">Login dengan password baru kamu di Sistem Pemilihan Jurusan</div>
                </div>
            </div>

            <!-- Warning -->
            <div class="warning">
                <strong>⚠️ Keamanan:</strong> Jika kamu tidak meminta reset password ini atau tidak mengenali aktivitas ini, abaikan email ini dan segera hubungi admin kami.
            </div>
        </div>

        <!-- Footer -->
        <div class="footer">
            <p><strong>Informasi Penting:</strong></p>
            <p>
                • Jangan membagikan link ini kepada siapapun<br>
                • Link ini hanya untuk reset password, bukan untuk aktivitas lain<br>
                • Pastikan kamu menggunakan password yang kuat dan unik<br>
                • Jika ada masalah, hubungi tim dukungan kami
            </p>
            <div class="contact-info">
                <strong>💬 Butuh Bantuan?</strong><br>
                Jika link tidak berfungsi atau ada pertanyaan, hubungi BK (Bimbingan Konseling) atau admin Sistem Pemilihan Jurusan.
            </div>
            <p style="margin-top: 16px; border-top: 1px solid #e5e7eb; padding-top: 12px;">
                Terima kasih,<br>
                <strong>Tim Sistem Pemilihan Jurusan Polije</strong>
            </p>
        </div>
    </div>
</body>
</html>
