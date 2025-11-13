@extends('layouts.app')

@section('content')
<div class="container mx-auto py-10 px-4" 
     x-data="{ showAHP: false, showSAW: false, showRank: false }">

    <h2 class="text-2xl font-bold mb-8">Prioritas Peminjaman Proyektor</h2>

    <!-- === TABEL PEMINJAMAN === -->
    <div class="bg-white shadow-sm rounded-lg border border-gray-200 overflow-hidden mb-6">
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-blue-50">
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-medium text-blue-900 uppercase">No</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-blue-900 uppercase">Nama Peminjam</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-blue-900 uppercase">Proyektor</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-blue-900 uppercase">Keperluan</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-blue-900 uppercase">Tanggal Peminjaman</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-blue-900 uppercase">Tanggal Pengembalian</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-blue-900 uppercase">Jam</th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    @php $no = 1; @endphp
                    @foreach ($peminjaman as $p)
                    <tr>
                        <td class="px-6 py-4 text-sm text-gray-900">{{ $no++ }}</td>
                        <td class="px-6 py-4 text-sm text-gray-900">{{ $p->nama_peminjam ?? '-' }}</td>
                        <td class="px-6 py-4 text-sm text-gray-900 text-center">{{ $p->proyektor->nama_proyektor ?? '-' }}</td>
                        <td class="px-6 py-4 max-w-xs text-sm text-gray-900">{{ $p->keperluan ?? $p->jenis_kegiatan ?? '-' }}</td>
                        <td class="px-6 py-4 text-sm text-gray-900 text-center">{{ \Carbon\Carbon::parse($p->tanggal_pinjam)->format('d M Y') }}</td>
                        <td class="px-6 py-4 text-sm text-gray-900 text-center">{{ \Carbon\Carbon::parse($p->tanggal_kembali)->format('d M Y') }}</td>
                        <td class="px-6 py-4 text-sm text-gray-900 text-center">{{ $p->jam_mulai }} - {{ $p->jam_selesai }}</td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>

    <!-- Tombol Hitung AHP -->
    <div class="text-right mb-4">
        <button 
            @click="showAHP = !showAHP"
            class="bg-blue-600 hover:bg-blue-700 text-white font-semibold px-6 py-2 rounded-lg transition">
            Hitung Bobot AHP
        </button>
    </div>

    <!-- === BAGIAN AHP === -->
    <div x-show="showAHP" x-transition>
        <div class="bg-white shadow-md rounded-lg border border-gray-200 mb-6 p-6">
            <div class="flex items-center justify-between mb-4">
                <h3 class="text-xl font-bold text-blue-800">Perhitungan AHP (Analytic Hierarchy Process)</h3>

                {{-- Tombol Tambah Kriteria --}}
                <a href="{{ route('admin.kriteria.tambah_kruang') }}" 
                   class="bg-gray-600 hover:bg-gray-700 text-white font-semibold px-4 py-2 rounded-lg">
                    + Tambah Kriteria
                </a>
            </div>

            {{-- Matriks Perbandingan --}}
            <h4 class="font-semibold mb-2 text-gray-800">1️⃣ Matriks Perbandingan Berpasangan</h4>
            <div class="overflow-x-auto mb-4">
                <table class="min-w-full border text-sm">
                    <thead class="bg-blue-50">
                        <tr>
                            <th class="border px-3 py-2">Kriteria</th>
                            @foreach ($kriteria as $key => $value)
                                <th class="border px-3 py-2 text-center">{{ ucfirst(str_replace('_', ' ', $key)) }}</th>
                            @endforeach
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($pairwiseMatrix as $i => $row)
                        <tr>
                            <td class="border px-3 py-2 font-semibold">{{ ucfirst(str_replace('_', ' ', array_keys($kriteria)[$i])) }}</td>
                            @foreach ($row as $val)
                                <td class="border px-3 py-2 text-center">{{ number_format($val, 3) }}</td>
                            @endforeach
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

            {{-- Normalisasi --}}
            <h4 class="font-semibold mb-2 text-gray-800">2️⃣ Normalisasi Matriks</h4>
            <div class="overflow-x-auto mb-4">
                <table class="min-w-full border text-sm">
                    <thead class="bg-blue-50">
                        <tr>
                            <th class="border px-3 py-2">Kriteria</th>
                            @foreach ($kriteria as $key => $value)
                                <th class="border px-3 py-2 text-center">{{ ucfirst(str_replace('_', ' ', $key)) }}</th>
                            @endforeach
                            <th class="border px-3 py-2 text-center">Rata-rata (Bobot)</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($normalizedMatrix as $i => $row)
                        <tr>
                            <td class="border px-3 py-2 font-semibold">{{ ucfirst(str_replace('_', ' ', array_keys($kriteria)[$i])) }}</td>
                            @foreach ($row as $val)
                                <td class="border px-3 py-2 text-center">{{ number_format($val, 3) }}</td>
                            @endforeach
                            <td class="border px-3 py-2 text-center font-semibold">{{ number_format($bobotAkhir[$i], 3) }}</td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

            {{-- Bobot Akhir --}}
            <h4 class="font-semibold mb-2 text-gray-800">3️⃣ Bobot Akhir Tiap Kriteria</h4>
            <table class="min-w-full border text-sm mb-4">
                <thead class="bg-green-50">
                    <tr>
                        <th class="border px-4 py-2">Kriteria</th>
                        <th class="border px-4 py-2">Bobot Akhir</th>
                        <th class="border px-4 py-2 text-center">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($kriteria as $index => $value)
                    <tr>
                        <td class="border px-4 py-2">{{ ucfirst(str_replace('_', ' ', $index)) }}</td>
                        <td class="border px-4 py-2 text-center">{{ number_format($value['bobot'], 3) }}</td>
                        <td class="border px-4 py-2 text-center">
                            <form action="{{ route('prioritas.hapusKriteria', $index) }}" method="POST" onsubmit="return confirm('Yakin ingin menghapus kriteria ini?')">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="text-red-600 hover:underline">Hapus</button>
                            </form>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>

            {{-- Rasio Konsistensi --}}
            <p class="text-sm text-gray-700 mt-2">
                <strong>Rasio Konsistensi (CR):</strong> {{ number_format($cr, 3) }} 
                @if($cr <= 0.1)
                    ✅ <span class="text-blue-600 font-semibold">Konsisten</span>
                @else
                    ⚠️ <span class="text-red-600 font-semibold">Tidak Konsisten</span>
                @endif
            </p>

            <!-- Tombol Hitung SAW -->
            <div class="text-right mt-6">
                <button 
                    @click="showSAW = !showSAW"
                    class="bg-blue-600 hover:bg-blue-700 text-white font-semibold px-6 py-2 rounded-lg transition">
                    Hitung SAW
                </button>
            </div>
        </div>

        <!-- === BAGIAN SAW === -->
        <div x-show="showSAW" x-transition>
            <div class="bg-white shadow-md rounded-lg border border-gray-200 p-6">
                <h3 class="text-xl font-bold mb-4 text-blue-900">Perhitungan SAW (Simple Additive Weighting)</h3>

                <p class="text-sm text-gray-600 mb-4">
                    Skala penilaian: <strong>Benefit</strong> = 1–5 (semakin tinggi semakin baik), 
                    <strong>Cost</strong> = 5–1 (semakin rendah semakin baik).
                </p>

                <table class="min-w-full border text-sm mb-4">
                    <thead class="bg-blue-50">
                        <tr>
                            <th class="border px-4 py-2">Alternatif</th>
                            @foreach ($kriteria as $key => $value)
                                <th class="border px-4 py-2">{{ ucfirst(str_replace('_', ' ', $key)) }}
                                    <br><span class="text-xs text-gray-500">({{ ucfirst($value['tipe']) }})</span>
                                </th>
                            @endforeach
                            <th class="border px-4 py-2">Total Nilai</th>
                            <th class="border px-4 py-2">Prioritas</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($hasil as $index => $h)
                        <tr>
                            <td class="border px-4 py-2">{{ $h['nama'] }}</td>
                            @foreach ($kriteria as $key => $value)
                                <td class="border px-4 py-2 text-center">{{ $alternatif[$index][$key] ?? '-' }}</td>
                            @endforeach
                            <td class="border px-4 py-2 text-center">{{ $h['nilai'] }}</td>
                            <td class="border px-4 py-2 text-center">{{ $h['ranking'] }}</td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>

                <!-- Tombol Lihat Rank -->
                <div class="text-right mt-4">
                    <button 
                        @click="showRank = !showRank"
                        class="bg-blue-600 hover:bg-blue-700 text-white font-semibold px-6 py-2 rounded-lg transition">
                        Lihat Rank
                    </button>
                </div>

                <!-- Tabel Rank -->
                <div x-show="showRank" x-transition>
                    <div class="bg-white shadow-md rounded-lg border border-gray-200 p-6">
                        <h3 class="text-xl font-bold mb-4 text-blue-800">Ranking Prioritas SAW</h3>

                        <table class="min-w-full border text-sm mb-4">
                            <thead class="bg-blue-50">
                                <tr>
                                    <th class="border px-4 py-2 text-center">Nama Peminjam</th>
                                    <th class="border px-4 py-2 text-center">Total Nilai</th>
                                    <th class="border px-4 py-2 text-center">Prioritas</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($hasil as $h)
                                <tr>
                                    <td class="border px-4 py-2 text-center">{{ $h['nama'] }}</td>
                                    <td class="border px-4 py-2 text-center">{{ number_format($h['nilai'], 4) }}</td>
                                    <td class="border px-4 py-2 text-center font-semibold">{{ $h['ranking'] }}</td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>

                        <p class="text-gray-700 text-sm">
                            <strong>Kesimpulan:</strong> 
                            Peminjaman atas nama 
                            <strong>{{ $hasil[0]['nama'] ?? '-' }}</strong> 
                            memiliki prioritas tertinggi berdasarkan hasil SAW.
                        </p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
