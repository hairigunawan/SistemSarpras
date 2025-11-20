@extends('layouts.app')

@section('title', 'Dashboard')

@section('content')
<div class="space-y-6">
    <!-- Kartu Statistik Utama -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4 md:gap-6">
        <!-- Total Akun -->
        <div class="bg-gradient-to-br from-blue-600 to-blue-700 p-4 rounded-xl shadow-lg transform transition-all duration-300 hover:scale-100 hover:shadow-xl">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-blue-100 text-sm font-medium mb-1">Total Akun</p>
                    <p class="text-3xl text-white font-bold">{{ $jumlah_akun ?? 0 }}</p>
                </div>
                <div class="bg-white bg-opacity-20 p-3 rounded-lg backdrop-blur-sm">
                    <svg class="w-4 h-4 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"></path>
                    </svg>
                </div>
            </div>
        </div>

        <!-- Total Sarpras -->
        <div class="bg-gradient-to-br from-blue-600 to-blue-700 p-4 rounded-xl shadow-lg transform transition-all duration-300 hover:scale-100 hover:shadow-xl">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-purple-100 text-sm font-medium mb-1">Total Sarpras</p>
                    <p class="text-3xl text-white font-bold">{{ $jumlah_sarpras ?? 0 }}</p>
                </div>
                <div class="bg-white bg-opacity-20 p-3 rounded-lg backdrop-blur-sm">
                    <svg class="w-4 h-4 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"></path>
                    </svg>
                </div>
            </div>
        </div>

        <!-- Peminjaman Menunggu -->
        <a href="{{ route('admin.peminjaman.index', ['status' => 'Menunggu']) }}" class="block group">
            <div class="bg-gradient-to-br from-blue-600 to-blue-700 p-4 rounded-xl shadow-lg transform transition-all duration-300 hover:scale-100 hover:shadow-xl">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-yellow-100 text-sm font-medium mb-1">Peminjaman Menunggu</p>
                        <p class="text-3xl text-white font-bold">{{ $peminjaman_menunggu ?? 0 }}</p>
                    </div>
                    <div class="bg-white bg-opacity-20 p-3 rounded-lg backdrop-blur-sm group-hover:bg-opacity-30 transition-all">
                        <svg class="w-4 h-4 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                        </svg>
                    </div>
                </div>
            </div>
        </a>

        <!-- Peminjaman Disetujui -->
        <a href="{{ route('admin.peminjaman.index', ['status' => 'disetujui']) }}" class="block group">
            <div class="bg-gradient-to-br from-blue-600 to-blue-700 p-4 rounded-xl shadow-lg transform transition-all duration-300 hover:scale-100 hover:shadow-xl">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-green-100 text-sm font-medium mb-1">Peminjaman Disetujui</p>
                        <p class="text-3xl text-white font-bold">{{ $peminjaman_disetujui ?? 0 }}</p>
                    </div>
                    <div class="bg-white bg-opacity-20 p-3 rounded-lg backdrop-blur-sm group-hover:bg-opacity-30 transition-all">
                        <svg class="w-4 h-4 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                        </svg>
                    </div>
                </div>
            </div>
        </a>
    </div>

    <!-- Kartu Aksi Cepat -->
    <div class="bg-gradient-to-tl from-blue-600 to-blue-700 p-6 rounded-xl shadow-lg">
        <h3 class="text-lg font-medium text-white mb-6 flex items-center">
            <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"></path>
            </svg>
            Aksi Cepat
        </h3>
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4">
            <!-- Tambah Ruangan -->
            <a href="{{ route('sarpras.ruangan.tambah_ruangan') }}" class="group">
                <div class="bg-white p-4 rounded-xl hover:shadow-lg transition-all duration-300 border border-gray-100 group-hover:border-blue-200 group-hover:transform group-hover:scale-100">
                    <div class="flex flex-col items-center text-center">
                        <div class="bg-blue-100 p-4 rounded-full group-hover:bg-blue-200 transition-colors mb-4">
                            <svg class="w-6 h-6 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"></path>
                            </svg>
                        </div>
                        <h3 class="text-gray-800 font-medium">Tambah Ruangan</h3>
                    </div>
                </div>
            </a>

            <!-- Tambah Proyektor -->
            <a href="{{ route('sarpras.proyektor.tambah_proyektor') }}" class="group">
                <div class="bg-white p-4 rounded-xl hover:shadow-lg transition-all duration-300 border border-gray-100 group-hover:border-purple-200 group-hover:transform group-hover:scale-100">
                    <div class="flex flex-col items-center text-center">
                        <div class="bg-blue-100 p-4 rounded-full group-hover:bg-blue-200 transition-colors mb-4">
                            <svg class="w-6 h-6 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9.75 17L9 20l-1 1h8l-1-1-.75-3M3 13h18M5 17h14a2 2 0 002-2V5a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"></path>
                            </svg>
                        </div>
                        <h3 class="text-gray-800 font-medium">Tambah Proyektor</h3>
                    </div>
                </div>
            </a>

            <!-- Laporan PDF -->
            <a href="{{ route('laporan.pdf') }}" class="group">
                <div class="bg-white p-4 rounded-xl hover:shadow-lg transition-all duration-300 border border-gray-100 group-hover:border-red-200 group-hover:transform group-hover:scale-100">
                    <div class="flex flex-col items-center text-center">
                        <div class="bg-red-100 p-4 rounded-full group-hover:bg-red-200 transition-colors mb-4">
                            <svg class="w-6 h-6 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 21h10a2 2 0 002-2V9.414a1 1 0 00-.293-.707l-5.414-5.414A1 1 0 0012.586 3H7a2 2 0 00-2 2v14a2 2 0 002 2z"></path>
                            </svg>
                        </div>
                        <h3 class="text-gray-800 font-medium">Laporan PDF</h3>
                    </div>
                </div>
            </a>

            <!-- Laporan Excel -->
            <a href="{{ route('laporan.excel') }}" class="group">
                <div class="bg-white p-4 rounded-xl hover:shadow-lg transition-all duration-300 border border-gray-100 group-hover:border-green-200 group-hover:transform group-hover:scale-100">
                    <div class="flex flex-col items-center text-center">
                        <div class="bg-green-100 p-4 rounded-full group-hover:bg-green-200 transition-colors mb-4">
                            <svg class="w-6 h-6 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 17v1a1 1 0 001 1h4a1 1 0 001-1v-1m3-2V8a2 2 0 00-2-2H8a2 2 0 00-2 2v6m9-5h-6a2 2 0 100 4h6a2 2 0 100-4z"></path>
                            </svg>
                        </div>
                        <h3 class="text-gray-800 font-medium">Laporan Excel</h3>

                    </div>
                </div>
            </a>
        </div>
    </div>

    <!-- Grafik Statistik Peminjaman dengan Opsi Periode -->
    <div class="bg-white p-6 border border-gray-200 rounded-xl shadow-sm">
        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4 mb-6">
            <div>
                <h3 class="text-xl font-semibold text-gray-800 mb-2">Statistik Peminjaman</h3>
                <p class="text-sm text-gray-500">{{ $periodeLabel ?? 'Data Statistik' }}</p>
            </div>

            <!-- Periode Toggle Buttons -->
            <div class="flex gap-2 flex-wrap">
                <a href="{{ route('admin.dashboard', ['periode' => 'minggu']) }}"
                   class="px-4 py-2 rounded-lg text-sm font-medium transition-all duration-300 {{ $periode === 'minggu' ? 'bg-blue-500 text-white shadow-lg transform scale-100' : 'bg-gray-100 text-gray-700 hover:bg-gray-200' }}">
                    Minggu Ini
                </a>
                <a href="{{ route('admin.dashboard', ['periode' => 'bulan']) }}"
                   class="px-4 py-2 rounded-lg text-sm font-medium transition-all duration-300 {{ $periode === 'bulan' ? 'bg-blue-500 text-white shadow-lg transform scale-100' : 'bg-gray-100 text-gray-700 hover:bg-gray-200' }}">
                    Bulan Ini
                </a>
                <a href="{{ route('admin.dashboard', ['periode' => 'semester']) }}"
                   class="px-4 py-2 rounded-lg text-sm font-medium transition-all duration-300 {{ $periode === 'semester' ? 'bg-blue-500 text-white shadow-lg transform scale-100' : 'bg-gray-100 text-gray-700 hover:bg-gray-200' }}">
                    Semester Ini
                </a>
            </div>
        </div>

        <!-- Chart Container -->
        <div class="relative h-96 bg-gray-50 rounded-lg p-4">
            <canvas id="peminjamantChart"></canvas>
        </div>
    </div>

    <!-- Row untuk Top Sarpras dan Top Peminjam -->
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
        <!-- Top Sarpras Terpopuler -->
        <div class="bg-white p-6 border border-gray-200 rounded-xl shadow-sm">
            <div class="mb-6">
                <h3 class="text-lg font-semibold text-gray-800 mb-2">Sarpras Terpopuler</h3>
                <p class="text-sm text-gray-500">Top 3 berdasarkan periode: <span class="font-medium text-gray-700">
                    @if($periode === 'minggu')
                        <p class="text-sm text-gray-500">{{ $periodeLabel ?? 'Data Statistik' }}</p>
                    @elseif($periode === 'bulan')
                        <p class="text-sm text-gray-500">{{ $periodeLabel ?? 'Data Statistik' }}</p>
                    @else
                        <p class="text-sm text-gray-500">{{ $periodeLabel ?? 'Data Statistik' }}</p>
                    @endif
                </span></p>
            </div>

            <!-- Ruangan -->
            <div class="mb-6">
                <h4 class="font-medium text-gray-700 mb-4 flex items-center">
                    Ruangan
                </h4>
                <div class="space-y-3">
                    @forelse($topSarpras['ruangan'] ?? [] as $index => $ruangan)
                        <div class="flex items-center justify-between p-3 bg-blue-50 rounded-lg hover:bg-blue-100 transition-colors">
                            <div class="flex items-center gap-3">
                                <span class="flex items-center justify-center w-8 h-8 bg-blue-500 text-white rounded-full text-sm font-bold shadow-md">
                                    {{ $index + 1 }}
                                </span>
                                <div>
                                    <span class="text-gray-700 font-medium">{{ $ruangan['nama'] }}</span>
                                    <div class="w-full bg-blue-200 rounded-full h-2 mt-1">
                                        <div class="bg-blue-500 h-2 rounded-full" style="width: {{ $ruangan['jumlah'] * 20 }}%"></div>
                                    </div>
                                </div>
                            </div>
                            <span class="bg-blue-100 text-blue-800 px-3 py-1 rounded-full text-sm font-semibold">
                                {{ $ruangan['jumlah'] }} x
                            </span>
                        </div>
                    @empty
                        <div class="text-center py-8 bg-gray-50 rounded-lg">
                            <svg class="w-12 h-12 text-gray-400 mx-auto mb-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 13V6a2 2 0 00-2-2H6a2 2 0 00-2 2v7m16 0v5a2 2 0 01-2 2H6a2 2 0 01-2-2v-5m16 0h-2.586a1 1 0 00-.707.293l-2.414 2.414a1 1 0 01-.707.293h-3.172a1 1 0 01-.707-.293l-2.414-2.414A1 1 0 006.586 13H4"></path>
                            </svg>
                            <p class="text-gray-500 text-sm italic">Belum ada data peminjaman</p>
                        </div>
                    @endforelse
                </div>
            </div>

            <!-- Proyektor -->
            <div>
                <h4 class="font-medium text-gray-700 mb-4 flex items-center">
                    Proyektor
                </h4>
                <div class="space-y-3">
                    @forelse($topSarpras['proyektor'] ?? [] as $index => $proyektor)
                        <div class="flex items-center justify-between p-3 bg-blue-50 rounded-lg hover:bg-blue-100 transition-colors">
                            <div class="flex items-center gap-3">
                                <span class="flex items-center justify-center w-8 h-8 bg-blue-500 text-white rounded-full text-sm font-bold shadow-md">
                                    {{ $index + 1 }}
                                </span>
                                <div>
                                    <span class="text-gray-700 font-medium">{{ $proyektor['nama'] }}</span>
                                    <div class="w-full bg-blue-200 rounded-full h-2 mt-1">
                                        <div class="bg-blue-500 h-2 rounded-full" style="width: {{ $proyektor['jumlah'] * 20 }}%"></div>
                                    </div>
                                </div>
                            </div>
                            <span class="bg-blue-100 text-purple-800 px-3 py-1 rounded-full text-sm font-semibold">
                                {{ $proyektor['jumlah'] }} x
                            </span>
                        </div>
                    @empty
                        <div class="text-center py-8 bg-gray-50 rounded-lg">
                            <svg class="w-12 h-12 text-gray-400 mx-auto mb-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 13V6a2 2 0 00-2-2H6a2 2 0 00-2 2v7m16 0v5a2 2 0 01-2 2H6a2 2 0 01-2-2v-5m16 0h-2.586a1 1 0 00-.707.293l-2.414 2.414a1 1 0 01-.707.293h-3.172a1 1 0 01-.707-.293l-2.414-2.414A1 1 0 006.586 13H4"></path>
                            </svg>
                            <p class="text-gray-500 text-sm italic">Belum ada data peminjaman</p>
                        </div>
                    @endforelse
                </div>
            </div>
        </div>

        <!-- Top Peminjam -->
        <div class="bg-white p-6 border border-gray-200 rounded-xl shadow-sm">
            <div class="mb-6">
                <h3 class="text-lg font-semibold text-gray-800 mb-2">Top Peminjam</h3>
                <p class="text-sm text-gray-500">3 peminjam terbanyak berdasarkan periode: <span class="font-medium text-gray-700">
                    @if($periode === 'minggu')
                        Minggu Ini
                    @elseif($periode === 'bulan')
                        Bulan Ini
                    @else
                        Semester Ini
                    @endif
                </span></p>
            </div>

            <div class="space-y-3">
                @forelse($topPeminjam ?? [] as $index => $peminjam)
                    <div class="flex items-center justify-between p-4 bg-gray-50 rounded-lg hover:bg-gray-100 transition-all duration-300 hover:shadow-md">
                        <div class="flex items-center gap-4">
                            <div class="relative">
                                <span class="flex items-center justify-center w-10 h-10 bg-gradient-to-br from-blue-400 to-blue-600 text-white rounded-full text-sm font-bold shadow-md">
                                    {{ $index + 1 }}
                                </span>
                                @if($index === 0)
                                    <div class="absolute -top-1 -right-1 bg-yellow-400 text-yellow-900 rounded-full p-1">
                                        <svg class="w-3 h-3" fill="currentColor" viewBox="0 0 20 20">
                                            <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"></path>
                                        </svg>
                                    </div>
                                @endif
                            </div>
                            <div>
                                <p class="font-medium text-gray-800">{{ $peminjam['nama'] }}</p>
                                <div class="flex items-center mt-1">
                                    <div class="w-24 bg-gray-200 rounded-full h-2 mr-2">
                                        <div class="bg-blue-500 h-2 rounded-full" style="width: {{ $peminjam['jumlah'] * 20 }}%"></div>
                                    </div>
                                    <span class="text-xs text-gray-500">{{ $peminjam['jumlah'] }} peminjaman</span>
                                </div>
                            </div>
                        </div>
                        <span class="bg-blue-100 text-blue-800 px-4 py-2 rounded-full font-semibold text-sm shadow-sm">
                            {{ $peminjam['jumlah'] }} x
                        </span>
                    </div>
                @empty
                    <div class="text-center py-8 bg-gray-50 rounded-lg">
                        <svg class="w-12 h-12 text-gray-400 mx-auto mb-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"></path>
                        </svg>
                        <p class="text-gray-500 text-sm italic">Belum ada data peminjaman</p>
                    </div>
                @endforelse
            </div>
        </div>
    </div>
</div>

<!-- Include Chart.js Library -->
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

<script>
    // Data untuk chart peminjaman
    const chartData = @json($chartData ?? []);

    // Extract data untuk Chart.js
    const labels = chartData.map(item => item.label);
    const ruanganData = chartData.map(item => item.ruangan);
    const proyektorData = chartData.map(item => item.proyektor);

    // Destroy existing chart if it exists
    if (window.peminjamantChartInstance) {
        window.peminjamantChartInstance.destroy();
    }

    // Create Chart
    const ctx = document.getElementById('peminjamantChart').getContext('2d');
    window.peminjamantChartInstance = new Chart(ctx, {
        type: 'bar',
        data: {
            labels: labels,
            datasets: [
                {
                    label: 'Ruangan',
                    data: ruanganData,
                    backgroundColor: '#3B82F6',
                    borderColor: '#1D4ED8',
                    borderWidth: 2,
                    borderRadius: 5,
                    tension: 0.1,
                    hoverBackgroundColor: '#1D4ED8',
                },
                {
                    label: 'Proyektor',
                    data: proyektorData,
                    backgroundColor: '#10B981',
                    borderColor: '#059669',
                    borderWidth: 2,
                    borderRadius: 5,
                    tension: 0.1,
                    hoverBackgroundColor: '#059669',
                }
            ]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            plugins: {
                legend: {
                    display: true,
                    position: 'top',
                    labels: {
                        usePointStyle: true,
                        padding: 15,
                        font: {
                            size: 12,
                            weight: 'bold'
                        }
                    }
                },
                tooltip: {
                    backgroundColor: 'rgba(0, 0, 0, 0.8)',
                    padding: 12,
                    titleFont: {
                        size: 14,
                        weight: 'bold'
                    },
                    bodyFont: {
                        size: 13
                    },
                    borderColor: '#ddd',
                    borderWidth: 1,
                    displayColors: true,
                    callbacks: {
                        afterLabel: function(context) {
                            return 'Peminjaman: ' + context.parsed.y + ' kali';
                        }
                    }
                }
            },
            scales: {
                y: {
                    beginAtZero: true,
                    ticks: {
                        stepSize: 1,
                        font: {
                            size: 12
                        },
                        callback: function(value) {
                            return value + ' x';
                        }
                    },
                    grid: {
                        color: 'rgba(0, 0, 0, 0.05)',
                        drawBorder: false
                    }
                },
                x: {
                    grid: {
                        display: false,
                        drawBorder: false
                    },
                    ticks: {
                        font: {
                            size: 12
                        }
                    }
                }
            }
        }
    });
</script>

@endsection
