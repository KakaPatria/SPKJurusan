<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'Admin Panel') - SPK Jurusan Polije</title>
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
    <style>
        .gradient-maroon { background: linear-gradient(135deg, #5B7B89 0%, #7B9BA5 100%); }
        .text-maroon { color: #5B7B89; }
        .border-maroon { border-color: #5B7B89; }
        .bg-cream { background-color: #F8FAFC; }
        .bg-maroon { background-color: #5B7B89; }
        .hover\:bg-maroon:hover { background-color: #7B9BA5; }
        .stat-card { transition: all 0.3s ease; }
        .stat-card:hover { transform: translateY(-5px); box-shadow: 0 5px 15px rgba(91, 123, 137, 0.1); }

        /* Sidebar */
        .sidebar-dark {
            background: linear-gradient(180deg, #1e293b 0%, #0f172a 100%);
        }
        .sidebar-link {
            transition: all 0.25s cubic-bezier(.4,0,.2,1);
            border-left: 3px solid transparent;
            color: #94a3b8;
        }
        .sidebar-link:hover {
            background: rgba(91, 123, 137, 0.15);
            color: #e2e8f0;
            border-left-color: rgba(91, 123, 137, 0.5);
        }
        .sidebar-link.active {
            background: linear-gradient(90deg, rgba(91,123,137,0.25) 0%, rgba(91,123,137,0.05) 100%);
            color: #7dd3fc !important;
            border-left-color: #7dd3fc;
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
            background: rgba(91, 123, 137, 0.3);
            transform: scale(1.05);
        }
        .sidebar-link.active .sidebar-icon {
            background: rgba(125, 211, 252, 0.15);
        }
        .sidebar-section-label {
            font-size: 10px;
            letter-spacing: 0.1em;
            text-transform: uppercase;
            font-weight: 700;
            color: #475569;
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
            background: linear-gradient(135deg, #5B7B89 0%, #7B9BA5 100%);
            border-radius: 10px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 20px;
            box-shadow: 0 4px 12px rgba(91, 123, 137, 0.3);
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
    <header class="gradient-maroon text-white shadow-lg sticky top-0 z-50">
        <div class="container mx-auto px-4 sm:px-6 py-4">
            <div class="flex justify-between items-center">
                <div class="flex items-center gap-3">
                    <!-- Mobile menu toggle -->
                    <button id="mobileMenuBtn" class="md:hidden text-white focus:outline-none">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"/>
                        </svg>
                    </button>
                    <div>
                        <h1 class="text-lg sm:text-xl md:text-2xl font-bold">🔧 Admin Panel</h1>
                        <p class="text-xs text-gray-200 font-semibold">Sistem Pemilihan Jurusan Politeknik Negeri Jember</p>
                    </div>
                </div>
                <div class="relative">
                    <button id="profileDropdownBtn" class="bg-gray-100 font-bold py-2 px-4 rounded-lg hover:bg-gray-200 transition text-xs sm:text-sm flex items-center gap-2" style="color: #5B7B89;">
                        👤 {{ Auth::user()->name }}
                        <svg id="dropdownArrow" class="w-4 h-4 transition-transform duration-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/>
                        </svg>
                    </button>
                    <div id="profileDropdown" class="absolute right-0 mt-2 w-48 bg-white text-gray-800 rounded-lg shadow-lg hidden z-50">
                        <a href="{{ route('admin.profil') }}" class="block px-4 py-3 hover:bg-gray-50 text-xs sm:text-sm font-semibold border-b">
                            👤 Profil Admin
                        </a>
                        <form method="POST" action="{{ route('logout') }}">
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
                            <p class="text-xs text-slate-400">Admin Panel</p>
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
                            <p class="text-xs font-medium text-slate-300 truncate">{{ Auth::user()->name }}</p>
                            <p class="text-xs text-slate-500">{{ Auth::user()->role === 'admin' ? 'Administrator' : (Auth::user()->role === 'bk' ? 'Guru BK' : ucfirst(Auth::user()->role)) }}</p>
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
                    <button id="closeMobileMenu" class="text-slate-400 hover:text-white transition text-xl">✕</button>
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
        // Profile dropdown
        const profileDropdownBtn = document.getElementById('profileDropdownBtn');
        const profileDropdown = document.getElementById('profileDropdown');
        const dropdownArrow = document.getElementById('dropdownArrow');

        profileDropdownBtn.addEventListener('click', function(e) {
            e.preventDefault();
            profileDropdown.classList.toggle('hidden');
            dropdownArrow.style.transform = profileDropdown.classList.contains('hidden') ? 'rotate(0deg)' : 'rotate(180deg)';
        });

        document.addEventListener('click', function(e) {
            if (!profileDropdownBtn.contains(e.target) && !profileDropdown.contains(e.target)) {
                profileDropdown.classList.add('hidden');
                dropdownArrow.style.transform = 'rotate(0deg)';
            }
        });

        // Mobile sidebar
        const mobileMenuBtn = document.getElementById('mobileMenuBtn');
        const mobileSidebar = document.getElementById('mobileSidebar');
        const mobileOverlay = document.getElementById('mobileOverlay');
        const closeMobileMenu = document.getElementById('closeMobileMenu');

        mobileMenuBtn.addEventListener('click', () => mobileSidebar.classList.remove('hidden'));
        mobileOverlay.addEventListener('click', () => mobileSidebar.classList.add('hidden'));
        closeMobileMenu.addEventListener('click', () => mobileSidebar.classList.add('hidden'));
    </script>
    @yield('scripts')
</body>
</html>
