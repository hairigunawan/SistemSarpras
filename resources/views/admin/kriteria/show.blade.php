@extends('layouts.app')

@section('content')
<div class="container mx-auto px-4 py-8">
    <div class="max-w-2xl mx-auto">
        <div class="bg-white rounded-lg shadow-md p-6">
            <div class="flex justify-between items-start mb-6">
                <h1 class="text-2xl font-bold text-gray-800">Detail Kriteria</h1>
                <div class="space-x-2">
                    <a href="{{ route('admin.kriteria.edit', $kriteria) }}"
                       class="px-3 py-1 bg-blue-600 text-white rounded-md hover:bg-blue-700 transition duration-200">
                        <i class="fas fa-edit mr-1"></i>Edit
                    </a>
                    <a href="{{ route('admin.kriteria.index') }}"
                       class="px-3 py-1 border border-gray-300 rounded-md text-gray-700 hover:bg-gray-50 transition duration-200">
                        <i class="fas fa-arrow-left mr-1"></i>Kembali
                    </a>
                </div>
            </div>

            <div class="space-y-6">
                <div class="border-b pb-4">
                    <h2 class="text-lg font-semibold text-gray-700 mb-2">Informasi Kriteria</h2>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div>
                            <label class="block text-sm font-medium text-gray-600 mb-1">Nama Kriteria</label>
                            <p class="text-lg font-medium text-gray-900">{{ $kriteria->nama_kriteria }}</p>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-600 mb-1">Tipe Kriteria</label>
                            <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium
                                {{ $kriteria->tipe === 'benefit' ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800' }}">
                                <i class="fas fa-{{ $kriteria->tipe === 'benefit' ? 'arrow-up' : 'arrow-down' }} mr-1"></i>
                                {{ ucfirst($kriteria->tipe) }}
                            </span>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-600 mb-1">Bobot Kriteria</label>
                            <p class="text-lg font-medium text-gray-900">{{ number_format($kriteria->bobot, 4) }}</p>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-600 mb-1">Dibuat Pada</label>
                            <p class="text-gray-900">{{ $kriteria->created_at->format('d F Y, H:i:s') }}</p>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-600 mb-1">Terakhir Diperbarui</label>
                            <p class="text-gray-900">{{ $kriteria->updated_at->format('d F Y, H:i:s') }}</p>
                        </div>
                    </div>
                </div>

                <div class="bg-gray-50 rounded-lg p-4">
                    <h3 class="text-lg font-semibold text-gray-700 mb-3">Penjelasan Tipe Kriteria</h3>
                    <div class="space-y-3">
                        @if($kriteria->tipe === 'benefit')
                            <div class="flex items-start">
                                <i class="fas fa-arrow-up text-green-500 mt-1 mr-3"></i>
                                <div>
                                    <p class="font-medium text-gray-800">Benefit (Keuntungan)</p>
                                    <p class="text-gray-600 text-sm">
                                        Kriteria benefit adalah kriteria di mana semakin tinggi nilainya,
                                        semakin baik atau diinginkan. Contoh: keuntungan, kepuasan, efisiensi.
                                    </p>
                                </div>
                            </div>
                        @else
                            <div class="flex items-start">
                                <i class="fas fa-arrow-down text-red-500 mt-1 mr-3"></i>
                                <div>
                                    <p class="font-medium text-gray-800">Cost (Biaya)</p>
                                    <p class="text-gray-600 text-sm">
                                        Kriteria cost adalah kriteria di mana semakin rendah nilainya,
                                        semakin baik atau diinginkan. Contoh: biaya, waktu, konsumsi.
                                    </p>
                                </div>
                            </div>
                        @endif
                    </div>
                </div>

                <div class="border-t pt-4">
                    <h3 class="text-lg font-semibold text-gray-700 mb-3">Contoh Penerapan</h3>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div class="bg-blue-50 rounded-lg p-4">
                            <h4 class="font-medium text-blue-800 mb-2">Contoh Kriteria Benefit:</h4>
                            <ul class="text-sm text-blue-700 space-y-1">
                                <li>• Kualitas produk</li>
                                <li>• Kecepatan layanan</li>
                                <li>• Jumlah peserta</li>
                                <li>• Durasi kegiatan</li>
                            </ul>
                        </div>
                        <div class="bg-red-50 rounded-lg p-4">
                            <h4 class="font-medium text-red-800 mb-2">Contoh Kriteria Cost:</h4>
                            <ul class="text-sm text-red-700 space-y-1">
                                <li>• Biaya pengeluaran</li>
                                <li>• Waiting time</li>
                                <li>• Konsumsi bahan</li>
                                <li>• Jarak tempuh</li>
                            </ul>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
