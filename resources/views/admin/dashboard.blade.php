@extends('admin.layouts.app')

@section('title', 'Dashboard')

@section('content')
            <!-- Logout All Users Button (hidden for safety during sidang) -->
            {{-- <div class="mb-6">
                <button onclick="confirmLogoutAllUsers()" class="px-4 py-2 bg-red-600 text-white rounded-lg hover:bg-red-700 transition flex items-center gap-2 font-semibold">
                    🚪 Logout Semua User
                </button>
            </div> --}}

            <!-- Statistics Cards -->
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4 mb-8">
                <div class="stat-card bg-white rounded-lg shadow p-6 border-t-4 border-maroon">
                    <p class="text-gray-600 text-sm font-semibold">👥 Total Siswa</p>
                    <p class="text-3xl font-bold text-maroon mt-2">{{ $totalSiswa }}</p>
                </div>
                <div class="stat-card bg-white rounded-lg shadow p-6 border-t-4 border-yellow-400">
                    <p class="text-gray-600 text-sm font-semibold">🎯 Total Rekomendasi</p>
                    <p class="text-3xl font-bold mt-2" style="color: #EA580C;">{{ $totalRekomendasi }}</p>
                </div>
                <div class="stat-card bg-white rounded-lg shadow p-6 border-t-4 border-blue-400">
                    <p class="text-gray-600 text-sm font-semibold">💬 Chat History</p>
                    <p class="text-3xl font-bold text-blue-600 mt-2">{{ $totalChatHistory }}</p>
                </div>
                <div class="stat-card bg-white rounded-lg shadow p-6 border-t-4 border-green-400">
                    <p class="text-gray-600 text-sm font-semibold">🎓 Jurusan Tersedia</p>
                    <p class="text-3xl font-bold text-green-600 mt-2">{{ $totalJurusan }}</p>
                </div>
            </div>

            <!-- Charts Section -->
            <div class="grid grid-cols-1 lg:grid-cols-2 gap-6 mb-6">
                <!-- Rekomendasi Distribution Chart -->
                <div class="bg-white rounded-lg shadow p-6 border-l-4 border-purple-400">
                    <h3 class="text-lg font-bold text-maroon mb-4">📊 Distribusi Rekomendasi per Jurusan</h3>
                    <div style="position: relative; height: 300px;">
                        <canvas id="chartRecommendations"></canvas>
                    </div>
                </div>

                <!-- Kelompok Distribution Chart -->
                <div class="bg-white rounded-lg shadow p-6 border-l-4 border-indigo-400">
                    <h3 class="text-lg font-bold text-maroon mb-4">📈 Distribusi Siswa per Kelompok</h3>
                    <div style="position: relative; height: 300px;">
                        <canvas id="chartKelompok"></canvas>
                    </div>
                </div>
            </div>

<!-- Rekomendasi & Top Majors -->
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6 mb-6">
        <!-- Rekomendasi per Kelompok -->
        <div class="bg-white rounded-lg shadow p-6 border-l-4 border-maroon">
            <h3 class="text-lg font-bold text-maroon mb-4">📊 Rekomendasi per Kelompok</h3>
            <div style="position: relative; height: 250px;">
                <canvas id="chartRekomendasiKelompok"></canvas>
                    </div>
                </div>

                <!-- Top Recommended Majors Chart -->
                <div class="bg-white rounded-lg shadow p-6 border-l-4 border-green-400">
                    <h3 class="text-lg font-bold text-maroon mb-4">🎯 Top Recommended Majors</h3>
                    <div style="position: relative; height: 250px;">
                        <canvas id="chartTopMajors"></canvas>
                    </div>
                </div>
            </div>

            <!-- Recent Students -->
            <div class="bg-white rounded-lg shadow p-6 mb-6 border-l-4 border-yellow-400">
                <h3 class="text-lg font-bold text-maroon mb-4">👥 Siswa Terbaru</h3>
                @if($recentStudents->isNotEmpty())
                    <div class="overflow-x-auto">
                        <table class="w-full text-sm">
                            <thead class="border-b-2 border-purple-500">
                                <tr>
                                    <th class="text-left px-4 py-2 font-bold text-maroon">Nama</th>
                                    <th class="text-center px-4 py-2 font-bold text-maroon">NIS</th>
                                    <th class="text-center px-4 py-2 font-bold text-maroon">Kelompok</th>
                                    <th class="text-center px-4 py-2 font-bold text-maroon">Aksi</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y">
                                @foreach($recentStudents as $student)
                                    <tr class="hover:bg-gray-50">
                                        <td class="px-4 py-2 font-semibold text-gray-800">{{ $student->name }}</td>
                                        <td class="px-4 py-2 text-center text-gray-600">{{ $student->nis ?? '-' }}</td>
                                        <td class="px-4 py-2 text-center">
                                            <span class="px-2 py-1 rounded text-xs font-bold" style="{{ $student->kelompok_asal == 'IPA' ? 'background-color: #E0F2FE; color: #0369A1;' : 'background-color: #FEF3C7; color: #92400E;' }}">
                                                {{ $student->kelompok_asal ?? '-' }}
                                            </span>
                                        </td>
                                        <td class="px-4 py-2 text-center">
                                            <a href="{{ route('admin.student.detail', $student->id) }}" class="text-purple-600 hover:text-purple-800 font-semibold text-xs">👁 Lihat</a>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                @else
                    <p class="text-gray-500 text-sm">Belum ada siswa terdaftar</p>
                @endif
            </div>

            <!-- Recent Recommendations -->
            <div class="bg-white rounded-lg shadow p-6 border-l-4 border-blue-400">
                <h3 class="text-lg font-bold text-maroon mb-4">🎯 Rekomendasi Terbaru</h3>
                @if($recentRecommendations->isNotEmpty())
                    <div class="overflow-x-auto">
                        <table class="w-full text-sm">
                            <thead class="border-b-2 border-maroon">
                                <tr>
                                    <th class="text-left px-4 py-2 font-bold text-maroon">Siswa</th>
                                    <th class="text-left px-4 py-2 font-bold text-maroon">Top Rekomendasi</th>
                                    <th class="text-center px-4 py-2 font-bold text-maroon">Skor</th>
                                    <th class="text-center px-4 py-2 font-bold text-maroon">Tanggal</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y">
                                @foreach($recentRecommendations as $rec)
                                    @php
                                        $topJurusan = $rec->hasil_rekomendasi[0]['jurusan'] ?? '-';
                                        $skorRaw = $rec->hasil_rekomendasi[0]['skor'] ?? 0;
                                        $topSkor = round(($skorRaw > 1 ? $skorRaw : $skorRaw * 100), 1);
                                    @endphp
                                    <tr class="hover:bg-gray-50">
                                        <td class="px-4 py-2 font-semibold text-gray-800">{{ $rec->user->name ?? 'Pengguna Dihapus' }}</td>
                                        <td class="px-4 py-2 text-gray-700">{{ $topJurusan }}</td>
                                        <td class="px-4 py-2 text-center">
                                            <span class="px-2 py-1 rounded bg-green-100 text-green-800 font-bold">{{ $topSkor }}%</span>
                                        </td>
                                        <td class="px-4 py-2 text-center text-gray-600">{{ $rec->created_at->format('d M Y') }}</td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                @else
                    <p class="text-gray-500 text-sm">Belum ada rekomendasi</p>
                @endif
            </div>
@endsection

@section('scripts')
    <script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.0/dist/chart.umd.js"></script>
    <script>
        // Chart 1: Rekomendasi Distribution
        const chartRecommendationsCtx = document.getElementById('chartRecommendations').getContext('2d');
        const chartRecommendations = new Chart(chartRecommendationsCtx, {
            type: 'doughnut',
            data: {
                labels: @json($chartMajorNames),
                datasets: [{
                    data: @json($chartMajorCounts),
                    backgroundColor: [
                        '#FF6384', '#36A2EB', '#FFCE56', '#4BC0C0', '#9966FF',
                        '#FF9F40', '#FF6384', '#C9CBCF', '#4BC0C0'
                    ],
                    borderColor: '#fff',
                    borderWidth: 2
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: {
                        position: 'bottom',
                        labels: {
                            font: { size: 11 },
                            padding: 10
                        }
                    }
                }
            }
        });

        // Chart 2: Kelompok Distribution
        const chartKelompokCtx = document.getElementById('chartKelompok').getContext('2d');
        const chartKelompok = new Chart(chartKelompokCtx, {
            type: 'bar',
            data: {
                labels: @json($chartKelompokNames),
                datasets: [{
                    label: 'Jumlah Siswa',
                    data: @json($chartKelompokCounts),
                    backgroundColor: ['#0369A1', '#D97706'],
                    borderColor: ['#0369A1', '#D97706'],
                    borderWidth: 1
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: {
                        display: true,
                        labels: {
                            font: { size: 11 }
                        }
                    }
                },
                scales: {
                    y: {
                        beginAtZero: true,
                        ticks: {
                            font: { size: 10 }
                        }
                    }
                }
            }
        });

        // Chart 3: Rekomendasi per Kelompok Bar Chart
        const chartRekomendasiKelompokCtx = document.getElementById('chartRekomendasiKelompok').getContext('2d');
        const chartRekomendasiKelompok = new Chart(chartRekomendasiKelompokCtx, {
            type: 'bar',
            data: {
                labels: @json($rekomendasiPerKelompok->pluck('kelompok_asal')->toArray()),
                datasets: [{
                    label: 'Jumlah Rekomendasi',
                    data: @json($rekomendasiPerKelompok->pluck('count')->toArray()),
                    backgroundColor: ['#0369A1', '#D97706'],
                    borderColor: ['#0369A1', '#D97706'],
                    borderWidth: 1
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: {
                        display: true,
                        labels: {
                            font: { size: 11 }
                        }
                    }
                },
                scales: {
                    y: {
                        beginAtZero: true,
                        ticks: {
                            font: { size: 10 }
                        }
                    }
                }
            }
        });

        // Chart 4: Top Majors Horizontal Bar Chart
        const chartTopMajorsCtx = document.getElementById('chartTopMajors').getContext('2d');
        const chartTopMajors = new Chart(chartTopMajorsCtx, {
            type: 'bar',
            data: {
                labels: @json($topMajorsChart),
                datasets: [{
                    label: 'Jumlah Rekomendasi',
                    data: @json($topMajorsCounts),
                    backgroundColor: '#36A2EB',
                    borderColor: '#36A2EB',
                    borderWidth: 1
                }]
            },
            options: {
                indexAxis: 'y',
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: {
                        display: true,
                        labels: {
                            font: { size: 11 }
                        }
                    }
                },
                scales: {
                    x: {
                        beginAtZero: true,
                        ticks: {
                            font: { size: 10 }
                        }
                    }
                }
            }
        });
    </script>

    <!-- Logout All Users Confirmation Script -->
    <script>
        function confirmLogoutAllUsers() {
            // First confirmation
            if (!confirm('⚠️  WARNING! Ini akan logout SEMUA user (Siswa, BK, Admin)!\\n\\nYakin ingin melanjutkan?')) {
                return;
            }

            // Second confirmation
            if (!confirm('🔔 Konfirmasi sekali lagi!\\n\\nIni tidak bisa dibatalkan. Lanjutkan?')) {
                return;
            }

            // Submit form via POST
            const form = document.createElement('form');
            form.method = 'POST';
            form.action = '{{ route("admin.logout-all-users") }}';
            
            const csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');
            const csrfInput = document.createElement('input');
            csrfInput.type = 'hidden';
            csrfInput.name = '_token';
            csrfInput.value = csrfToken;
            
            form.appendChild(csrfInput);
            document.body.appendChild(form);
            form.submit();
        }
    </script>
@endsection
