<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'Admin Panel') - SPK Jurusan Kuliah</title>
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
        .sidebar-link { transition: all 0.2s ease; }
        .sidebar-link:hover { background: rgba(255,255,255,0.1); }
        .sidebar-link.active { background: #F0F4F8; color: #5B7B89 !important; }
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
                        <p class="text-xs text-gray-200 font-semibold">Sistem Pemilihan Jurusan Kuliah</p>
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
        <aside class="hidden md:block w-64 gradient-maroon text-white shadow-lg flex-shrink-0">
            <nav class="p-4 space-y-1 sticky top-20">
                <p class="text-xs text-gray-300 font-bold uppercase tracking-wider mb-4 px-4">Menu Utama</p>

                <a href="{{ route('admin.dashboard') }}" class="sidebar-link block px-4 py-3 rounded-lg font-semibold text-sm {{ request()->routeIs('admin.dashboard') ? 'active' : '' }}">
                    📊 Dashboard
                </a>
                <a href="{{ route('admin.students') }}" class="sidebar-link block px-4 py-3 rounded-lg font-semibold text-sm {{ request()->routeIs('admin.students*') ? 'active' : '' }}">
                    👥 Manajemen Data Siswa
                </a>
                <a href="{{ route('admin.jurusan') }}" class="sidebar-link block px-4 py-3 rounded-lg font-semibold text-sm {{ request()->routeIs('admin.jurusan*') ? 'active' : '' }}">
                    🎓 Manajemen Jurusan
                </a>
                <a href="{{ route('admin.guru-bk') }}" class="sidebar-link block px-4 py-3 rounded-lg font-semibold text-sm {{ request()->routeIs('admin.guru-bk*') ? 'active' : '' }}">
                    👨‍🏫 Manajemen Akun Guru BK
                </a>

                <p class="text-xs text-gray-300 font-bold uppercase tracking-wider mt-6 mb-3 px-4">Riwayat</p>

                <a href="{{ route('admin.riwayat-rekomendasi') }}" class="sidebar-link block px-4 py-3 rounded-lg font-semibold text-sm {{ request()->routeIs('admin.riwayat-rekomendasi*') ? 'active' : '' }}">
                    🎯 Riwayat Rekomendasi
                </a>
                <a href="{{ route('admin.riwayat-chatbot') }}" class="sidebar-link block px-4 py-3 rounded-lg font-semibold text-sm {{ request()->routeIs('admin.riwayat-chatbot*') ? 'active' : '' }}">
                    💬 Riwayat Konsultasi Chatbot
                </a>
            </nav>
        </aside>

        <!-- Mobile Sidebar Overlay -->
        <div id="mobileSidebar" class="fixed inset-0 z-40 hidden">
            <div class="absolute inset-0 bg-black bg-opacity-50" id="mobileOverlay"></div>
            <aside class="relative w-64 h-full gradient-maroon text-white shadow-lg overflow-y-auto">
                <div class="p-4 border-b border-white border-opacity-20 flex justify-between items-center">
                    <span class="font-bold">Menu Admin</span>
                    <button id="closeMobileMenu" class="text-white">✕</button>
                </div>
                <nav class="p-4 space-y-1">
                    <a href="{{ route('admin.dashboard') }}" class="sidebar-link block px-4 py-3 rounded-lg font-semibold text-sm {{ request()->routeIs('admin.dashboard') ? 'active' : '' }}">
                        📊 Dashboard
                    </a>
                    <a href="{{ route('admin.students') }}" class="sidebar-link block px-4 py-3 rounded-lg font-semibold text-sm {{ request()->routeIs('admin.students*') ? 'active' : '' }}">
                        👥 Manajemen Data Siswa
                    </a>
                    <a href="{{ route('admin.jurusan') }}" class="sidebar-link block px-4 py-3 rounded-lg font-semibold text-sm {{ request()->routeIs('admin.jurusan*') ? 'active' : '' }}">
                        🎓 Manajemen Jurusan
                    </a>
                    <a href="{{ route('admin.guru-bk') }}" class="sidebar-link block px-4 py-3 rounded-lg font-semibold text-sm {{ request()->routeIs('admin.guru-bk*') ? 'active' : '' }}">
                        👨‍🏫 Manajemen Akun Guru BK
                    </a>
                    <hr class="border-white border-opacity-20 my-3">
                    <a href="{{ route('admin.riwayat-rekomendasi') }}" class="sidebar-link block px-4 py-3 rounded-lg font-semibold text-sm {{ request()->routeIs('admin.riwayat-rekomendasi*') ? 'active' : '' }}">
                        🎯 Riwayat Rekomendasi
                    </a>
                    <a href="{{ route('admin.riwayat-chatbot') }}" class="sidebar-link block px-4 py-3 rounded-lg font-semibold text-sm {{ request()->routeIs('admin.riwayat-chatbot*') ? 'active' : '' }}">
                        💬 Riwayat Konsultasi Chatbot
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
