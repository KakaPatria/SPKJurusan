<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'Admin Panel') - SPK Jurusan Polije</title>
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
    <style>
        .gradient-maroon { background: linear-gradient(135deg, #7c3aed 0%, #6366f1 100%); }
        .text-maroon { color: #7c3aed; }
        .border-maroon { border-color: #7c3aed; }
        .bg-cream { background-color: #f8fafc; }
        .bg-maroon { background-color: #7c3aed; }
        .hover\:bg-maroon:hover { background-color: #6366f1; }
        .stat-card { transition: all 0.3s ease; }
        .stat-card:hover { transform: translateY(-5px); box-shadow: 0 5px 15px rgba(124, 58, 237, 0.1); }

        /* Sidebar */
        .sidebar-dark {
            background: linear-gradient(180deg, #1e293b 0%, #0f172a 100%);
        }
        .sidebar-link {
            transition: all 0.25s cubic-bezier(.4,0,.2,1);
            border-left: 3px solid transparent;
            /* Higher contrast on dark sidebar */
            color: #cbd5e1;
        }
        .sidebar-link:hover {
            background: rgba(124, 58, 237, 0.12);
            color: #ffffff;
            border-left-color: rgba(124, 58, 237, 0.5);
        }
        .sidebar-link.active {
            background: linear-gradient(90deg, rgba(124, 58, 237, 0.2) 0%, rgba(124, 58, 237, 0.03) 100%);
            color: #c4b5fd !important;
            border-left-color: #c4b5fd;
        }
        .sidebar-link .sidebar-icon {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            width: 34px;
            height: 34px;
            border-radius: 8px;
            background: rgba(255,255,255,0.06);
            font-size: 16px;
            flex-shrink: 0;
            transition: all 0.25s ease;
        }
        .sidebar-link:hover .sidebar-icon {
            background: rgba(124, 58, 237, 0.25);
            transform: scale(1.05);
        }
        .sidebar-link.active .sidebar-icon {
            background: rgba(124, 58, 237, 0.15);
        }
        .sidebar-section-label {
            font-size: 10px;
            letter-spacing: 0.1em;
            text-transform: uppercase;
            font-weight: 700;
            /* Keep section labels readable on dark background */
            color: #94a3b8;
            padding: 0 1rem;
            margin-bottom: 0.5rem;
        }
        .sidebar-brand {
            padding: 1.25rem 1rem;
            border-bottom: 1px solid rgba(255,255,255,0.06);
            margin-bottom: 0.5rem;
        }
        .sidebar-brand-icon {
            width: 40px;
            height: 40px;
            background: linear-gradient(135deg, #7c3aed 0%, #6366f1 100%);
            border-radius: 10px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 20px;
            box-shadow: 0 4px 12px rgba(124, 58, 237, 0.3);
        }
        .sidebar-footer {
            border-top: 1px solid rgba(255,255,255,0.06);
            padding: 1rem;
        }
        @media (max-width: 768px) {
            .sidebar-desktop { display: none; }
            .sidebar-mobile.open { display: block; }
        }
    </style>
    @yield('styles')
</head>
<body class="bg-cream">
    <!-- Header -->
    <header class="gradient-maroon text-white shadow-lg sticky top-0 z-50 overflow-visible">
        <div class="container mx-auto px-4 sm:px-6 py-4">
            <div class="flex flex-col gap-3 sm:flex-row sm:justify-between sm:items-center">
                <div class="flex items-center gap-3 min-w-0">
                    <!-- Mobile menu toggle -->
                    <button id="mobileMenuBtn" class="md:hidden text-white focus:outline-none">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"/>
                        </svg>
                    </button>
                    <div>
                        <h1 class="text-lg sm:text-xl md:text-2xl font-bold leading-tight">🔧 Admin Panel</h1>
                        <p class="text-xs text-gray-200 font-semibold">Sistem Pemilihan Jurusan Politeknik Negeri Jember</p>
                    </div>
                </div>
                <div class="relative w-full sm:w-auto overflow-visible">
                    <button id="profileDropdownBtn" class="bg-gray-100 font-bold py-2 px-4 rounded-lg hover:bg-gray-200 transition text-xs sm:text-sm flex items-center justify-center gap-2 w-full sm:w-auto" style="color: #5B7B89;">
                        👤 {{ Auth::user()->name }}
                        <svg id="dropdownArrow" class="w-4 h-4 transition-transform duration-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/>
                        </svg>
                    </button>
                    <div id="profileDropdown" class="absolute left-0 sm:left-auto sm:right-0 mt-2 w-full sm:w-48 bg-white text-gray-800 rounded-lg shadow-lg hidden z-50" style="min-width: 12rem;">
                        <a href="{{ route('admin.profil') }}" class="block px-4 py-3 hover:bg-gray-50 text-xs sm:text-sm font-semibold border-b">
                            👤 Profil Admin
                        </a>
                        <form method="POST" action="{{ route('logout') }}" class="confirm-logout">
                            @csrf
                            <button type="submit" class="block w-full text-left px-4 py-3 hover:bg-gray-50 text-xs sm:text-sm font-semibold text-red-600 rounded-b-lg">
                                🚪 Logout
                            </button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </header>

    <div class="flex min-h-screen">
        <!-- Sidebar Desktop -->
        <aside class="hidden md:block w-64 sidebar-dark shadow-2xl flex-shrink-0">
            <div class="sticky top-20">
                <!-- Brand -->
                <div class="sidebar-brand">
                    <div class="flex items-center gap-3">
                        <div class="sidebar-brand-icon">🎓</div>
                        <div>
                            <p class="text-white font-bold text-sm leading-tight">SPK Jurusan</p>
                            <p class="text-xs text-white">Admin Panel</p>
                        </div>
                    </div>
                </div>

                <nav class="px-3 py-2 space-y-1">
                    <p class="sidebar-section-label mt-2">Menu Utama</p>

                    <a href="{{ route('admin.dashboard') }}" class="sidebar-link flex items-center gap-3 px-3 py-2.5 rounded-lg text-sm font-medium {{ request()->routeIs('admin.dashboard') ? 'active' : '' }}">
                        <span class="sidebar-icon">📊</span> Dashboard
                    </a>
                    <a href="{{ route('admin.students') }}" class="sidebar-link flex items-center gap-3 px-3 py-2.5 rounded-lg text-sm font-medium {{ request()->routeIs('admin.students*') ? 'active' : '' }}">
                        <span class="sidebar-icon">👥</span> Data Siswa
                    </a>
                    <a href="{{ route('admin.jurusan') }}" class="sidebar-link flex items-center gap-3 px-3 py-2.5 rounded-lg text-sm font-medium {{ request()->routeIs('admin.jurusan*') ? 'active' : '' }}">
                        <span class="sidebar-icon">🏛️</span> Jurusan
                    </a>
                    <a href="{{ route('admin.guru-bk') }}" class="sidebar-link flex items-center gap-3 px-3 py-2.5 rounded-lg text-sm font-medium {{ request()->routeIs('admin.guru-bk*') ? 'active' : '' }}">
                        <span class="sidebar-icon">👨‍🏫</span> Akun Guru BK
                    </a>
                    <a href="{{ route('admin.alumni.index') }}" class="sidebar-link flex items-center gap-3 px-3 py-2.5 rounded-lg text-sm font-medium {{ request()->routeIs('alumni*') ? 'active' : '' }}">
                        <span class="sidebar-icon">🎓</span> Data Alumni
                    </a>

                    <p class="sidebar-section-label mt-5">Riwayat</p>

                    <a href="{{ route('admin.riwayat-rekomendasi') }}" class="sidebar-link flex items-center gap-3 px-3 py-2.5 rounded-lg text-sm font-medium {{ request()->routeIs('admin.riwayat-rekomendasi*') ? 'active' : '' }}">
                        <span class="sidebar-icon">🎯</span> Rekomendasi
                    </a>
                    <a href="{{ route('admin.riwayat-chatbot') }}" class="sidebar-link flex items-center gap-3 px-3 py-2.5 rounded-lg text-sm font-medium {{ request()->routeIs('admin.riwayat-chatbot*') ? 'active' : '' }}">
                        <span class="sidebar-icon">💬</span> Konsultasi Chatbot
                    </a>
                </nav>

                <!-- Footer -->
                <div class="sidebar-footer mt-4">
                    <div class="flex items-center gap-3 px-3 py-2">
                        <div class="w-8 h-8 rounded-full bg-gradient-to-br from-blue-400 to-cyan-300 flex items-center justify-center text-white text-xs font-bold">
                            {{ strtoupper(substr(Auth::user()->name, 0, 1)) }}
                        </div>
                        <div class="flex-1 min-w-0">
                            <p class="text-xs font-medium text-white truncate">{{ Auth::user()->name }}</p>
                            <p class="text-xs text-white">{{ Auth::user()->role === 'admin' ? 'Administrator' : (Auth::user()->role === 'bk' ? 'Guru BK' : ucfirst(Auth::user()->role)) }}</p>
                        </div>
                    </div>
                </div>
            </div>
        </aside>

        <!-- Mobile Sidebar Overlay -->
        <div id="mobileSidebar" class="fixed inset-0 z-40 hidden">
            <div class="absolute inset-0 bg-black bg-opacity-60 backdrop-blur-sm" id="mobileOverlay"></div>
            <aside class="relative w-72 h-full sidebar-dark shadow-2xl overflow-y-auto">
                <div class="p-4 flex justify-between items-center" style="border-bottom: 1px solid rgba(255,255,255,0.06);">
                    <div class="flex items-center gap-3">
                        <div class="sidebar-brand-icon" style="width:36px;height:36px;font-size:18px;">🎓</div>
                        <span class="font-bold text-white text-sm">SPK Jurusan</span>
                    </div>
                    <button id="closeMobileMenu" class="text-gray-400 hover:text-white transition text-xl">✕</button>
                </div>
                <nav class="px-3 py-3 space-y-1">
                    <p class="sidebar-section-label mt-1">Menu Utama</p>
                    <a href="{{ route('admin.dashboard') }}" class="sidebar-link flex items-center gap-3 px-3 py-2.5 rounded-lg text-sm font-medium {{ request()->routeIs('admin.dashboard') ? 'active' : '' }}">
                        <span class="sidebar-icon">📊</span> Dashboard
                    </a>
                    <a href="{{ route('admin.students') }}" class="sidebar-link flex items-center gap-3 px-3 py-2.5 rounded-lg text-sm font-medium {{ request()->routeIs('admin.students*') ? 'active' : '' }}">
                        <span class="sidebar-icon">👥</span> Data Siswa
                    </a>
                    <a href="{{ route('admin.jurusan') }}" class="sidebar-link flex items-center gap-3 px-3 py-2.5 rounded-lg text-sm font-medium {{ request()->routeIs('admin.jurusan*') ? 'active' : '' }}">
                        <span class="sidebar-icon">🏛️</span> Jurusan
                    </a>
                    <a href="{{ route('admin.guru-bk') }}" class="sidebar-link flex items-center gap-3 px-3 py-2.5 rounded-lg text-sm font-medium {{ request()->routeIs('admin.guru-bk*') ? 'active' : '' }}">
                        <span class="sidebar-icon">👨‍🏫</span> Akun Guru BK
                    </a>
                    <a href="{{ route('admin.alumni.index') }}" class="sidebar-link flex items-center gap-3 px-3 py-2.5 rounded-lg text-sm font-medium {{ request()->routeIs('alumni*') ? 'active' : '' }}">
                        <span class="sidebar-icon">🎓</span> Data Alumni
                    </a>

                    <p class="sidebar-section-label mt-5">Riwayat</p>
                    <a href="{{ route('admin.riwayat-rekomendasi') }}" class="sidebar-link flex items-center gap-3 px-3 py-2.5 rounded-lg text-sm font-medium {{ request()->routeIs('admin.riwayat-rekomendasi*') ? 'active' : '' }}">
                        <span class="sidebar-icon">🎯</span> Rekomendasi
                    </a>
                    <a href="{{ route('admin.riwayat-chatbot') }}" class="sidebar-link flex items-center gap-3 px-3 py-2.5 rounded-lg text-sm font-medium {{ request()->routeIs('admin.riwayat-chatbot*') ? 'active' : '' }}">
                        <span class="sidebar-icon">💬</span> Konsultasi Chatbot
                    </a>
                </nav>
            </aside>
        </div>

        <!-- Main Content -->
        <main class="flex-1 p-4 sm:p-6 lg:p-8 overflow-x-hidden">
            <!-- Flash Messages -->
            @if(session('success'))
                <div class="mb-4 p-4 bg-green-100 border-l-4 border-green-500 text-green-700 rounded">
                    <p class="font-semibold">✅ {{ session('success') }}</p>
                </div>
            @endif
            @if(session('error'))
                <div class="mb-4 p-4 bg-red-100 border-l-4 border-red-500 text-red-700 rounded">
                    <p class="font-semibold">❌ {{ session('error') }}</p>
                </div>
            @endif

            @yield('content')
        </main>
    </div>

    <!-- Scripts -->
    <script>
        // Mobile sidebar
        const mobileMenuBtn = document.getElementById('mobileMenuBtn');
        const mobileSidebar = document.getElementById('mobileSidebar');
        const mobileOverlay = document.getElementById('mobileOverlay');
        const closeMobileMenu = document.getElementById('closeMobileMenu');

        // Profile dropdown
        const profileDropdownBtn = document.getElementById('profileDropdownBtn');
        const profileDropdown = document.getElementById('profileDropdown');
        const dropdownArrow = document.getElementById('dropdownArrow');

        if (profileDropdownBtn && profileDropdown && dropdownArrow) {
            profileDropdownBtn.addEventListener('click', function(e) {
                e.preventDefault();
                e.stopPropagation();
                profileDropdown.classList.toggle('hidden');
                dropdownArrow.style.transform = profileDropdown.classList.contains('hidden') ? 'rotate(0deg)' : 'rotate(180deg)';
            });

            document.addEventListener('click', function(e) {
                if (!profileDropdownBtn.contains(e.target) && !profileDropdown.contains(e.target)) {
                    profileDropdown.classList.add('hidden');
                    dropdownArrow.style.transform = 'rotate(0deg)';
                }
            });
        }

        if (mobileMenuBtn && mobileSidebar && mobileOverlay && closeMobileMenu) {
            const openMobileSidebar = () => mobileSidebar.classList.remove('hidden');
            const closeMobileSidebar = () => mobileSidebar.classList.add('hidden');

            mobileMenuBtn.addEventListener('click', (e) => {
                e.preventDefault();
                if (mobileSidebar.classList.contains('hidden')) {
                    openMobileSidebar();
                } else {
                    closeMobileSidebar();
                }
            });
            mobileOverlay.addEventListener('click', closeMobileSidebar);
            closeMobileMenu.addEventListener('click', closeMobileSidebar);
        }
    </script>
    <!-- SweetAlert2 (admin) -->
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
            // Generic SweetAlert2 confirmation for destructive actions in admin
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
        });
    </script>
    @yield('scripts')
</body>
</html>
