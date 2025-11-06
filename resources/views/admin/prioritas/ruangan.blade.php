@extends('layouts.app')

@section('content')
<div class="container mx-auto py-10 px-4" x-data="{ showHitung: false }">
    <h2 class="text-2xl font-bold mb-8">Prioritas Peminjaman Ruangan</h2>

    <div class="bg-white shadow-sm rounded-lg border border-gray-200 overflow-hidden mb-6">
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-blue-50">
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-medium text-blue-900 uppercase tracking-wider">No</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-blue-900 uppercase tracking-wider">Nama Peminjam</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-blue-900 uppercase tracking-wider">Sarana/Prasarana</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-blue-900 uppercase tracking-wider">Keperluan</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-blue-900 uppercase tracking-wider">Tanggal Peminjaman</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-blue-900 uppercase tracking-wider">Tanggal Pengembalian</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-blue-900 uppercase tracking-wider">Jam</th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    @php $no = 1; @endphp
                    @foreach ($peminjaman as $p)
                    <tr>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">{{ $no++ }}</td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">{{ $p->nama_peminjam ?? '-' }}</td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900 text-center">{{ $p->ruangan->nama_ruangan ?? '-' }}</td>
                        <td class="px-6 py-4 max-w-xs text-sm text-gray-900" style="white-space: normal; word-wrap: break-word;">
                            {{ $p->keperluan ?? $p->jenis_kegiatan ?? '-' }}
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900 text-center">
                            {{ \Carbon\Carbon::parse($p->tanggal_pinjam ?? '-')->format('d M Y') }}
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900 text-center">
                            {{ \Carbon\Carbon::parse($p->tanggal_kembali ?? '-')->format('d M Y') }}
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900 text-center">
                            {{ $p->jam_mulai ?? '-' }} - {{ $p->jam_selesai ?? '-' }}
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>

    <!-- Tombol Hitung -->
    <div class="text-right mb-4">
        <button 
            @click="showHitung = !showHitung" 
            class="bg-blue-600 hover:bg-blue-700 text-white font-semibold px-6 py-2 rounded-lg transition">
            Hitung
        </button>
    </div>

    <!-- Bagian Perhitungan AHP & SAW -->
    <div x-show="showHitung" x-transition>
        <!-- === AHP === -->
        <div class="bg-white shadow-md rounded-lg border border-gray-200 mb-6 p-6">
            <h3 class="text-xl font-bold mb-4 text-blue-800">Perhitungan AHP (Analytic Hierarchy Process)</h3>
            
            <table class="min-w-full border text-sm mb-4">
                <thead class="bg-blue-50">
                    <tr>
                        <th class="border px-4 py-2">Kriteria</th>
                        <th class="border px-4 py-2">Bobot Normalisasi</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($kriteria as $key => $value)
                    <tr>
                        <td class="border px-4 py-2">{{ ucfirst(str_replace('_', ' ', $key)) }}</td>
                        <td class="border px-4 py-2 text-center">{{ $value['bobot'] }}</td>
                    </tr>
                    @endforeach
                </tbody>
            </table>

            <p class="text-gray-700 text-sm">
                <strong>Hasil:</strong> Bobot akhir tiap kriteria diperoleh dari rata-rata normalisasi matriks perbandingan berpasangan.
            </p>
        </div>

        <!-- === SAW === -->
        <div class="bg-white shadow-md rounded-lg border border-gray-200 p-6">
            <h3 class="text-xl font-bold mb-4 text-blue-900">Perhitungan SAW (Simple Additive Weighting)</h3>

            <p class="text-sm text-gray-600 mb-4">
                Skala penilaian: <strong>Benefit</strong> = 1–5 (semakin tinggi semakin baik), 
                <strong>Cost</strong> = 5–1 (semakin rendah semakin baik).
            </p>

            <table class="min-w-full border text-sm mb-4">
                <thead class="bg-green-50">
                    <tr>
                        <th class="border px-4 py-2">Alternatif</th>
                        @foreach ($kriteria as $key => $value)
                            <th class="border px-4 py-2">{{ ucfirst(str_replace('_', ' ', $key)) }}<br><span class="text-xs text-gray-500">({{ ucfirst($value['tipe']) }})</span></th>
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

            <p class="text-gray-700 text-sm">
                <strong>Kesimpulan:</strong> Peminjaman atas nama <strong>{{ $hasil[0]['nama'] ?? '-' }}</strong> memiliki prioritas tertinggi berdasarkan hasil SAW.
            </p>
        </div>
    </div>
</div>
@endsection
