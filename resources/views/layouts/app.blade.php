<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>{{ config('app.name', 'Laravel') }}</title>

        <!-- Fonts -->
        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />

        <!-- Scripts -->
        @vite(['resources/css/app.css', 'resources/js/app.js'])
    </head>
    <body class="font-sans antialiased">
        <div class="min-h-screen bg-gray-100">
            @include('layouts.navigation')

            <!-- Page Heading -->
            @if (isset($header))
                <header class="bg-white shadow">
                    <div class="max-w-7xl mx-auto py-6 px-4 sm:px-6 lg:px-8">
                        {{ $header }}
                    </div>
                </header>
            @endif

            <!-- Page Content -->
            <main>
                {{ $slot }}
            </main>
        </div>
        <!-- SweetAlert2 for nicer confirmation modals -->
        <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
        <script>
            document.addEventListener('DOMContentLoaded', function () {
                document.querySelectorAll('form.confirm-logout').forEach(function(form) {
                    form.addEventListener('submit', function (e) {
                        e.preventDefault();
                        Swal.fire({
                            title: 'Konfirmasi Logout',
                            text: 'Yakin ingin logout dari akun ini?',
                            icon: 'question',
                            showCancelButton: true,
                            confirmButtonText: 'Ya, Logout',
                            cancelButtonText: 'Batal',
                            reverseButtons: true
                        }).then(function(result) {
                            if (result.isConfirmed) {
                                form.submit();
                            }
                        });
                    });
                });
                // Generic SweetAlert2 confirmation for destructive actions
                document.querySelectorAll('form.swal-confirm').forEach(function(form) {
                    form.addEventListener('submit', function(e) {
                        e.preventDefault();
                        const msg = form.getAttribute('data-confirm-message') || 'Yakin melanjutkan aksi ini?';
                        Swal.fire({
                            title: 'Konfirmasi',
                            text: msg,
                            icon: 'warning',
                            showCancelButton: true,
                            confirmButtonText: 'Ya',
                            cancelButtonText: 'Batal',
                            reverseButtons: true
                        }).then(function(result) {
                            if (result.isConfirmed) {
                                form.submit();
                            }
                        });
                    });
                });
            });
        </script>
    </body>
</html>
