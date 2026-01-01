@extends('layouts.app')

@section('content')
<div class="max-w-6xl mx-auto px-4 py-8">
    <div class="mb-6">
        <h1 class="text-2xl font-bold text-gray-800">Perbandingan Kriteria (AHP)</h1>
        <p class="text-sm text-gray-500 mt-1">Masukkan nilai perbandingan antar kriteria untuk menghitung bobot prioritas secara otomatis.</p>
    </div>

    @if(session('success'))
        <div class="mb-4 p-4 bg-green-50 border border-green-200 text-green-700 rounded-lg">
            {{ session('success') }}
        </div>
    @endif
    
    @if(session('warning'))
        <div class="mb-4 p-4 bg-yellow-50 border border-yellow-200 text-yellow-700 rounded-lg">
            {{ session('warning') }}
        </div>
    @endif

    <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6 overflow-x-auto">
        <form action="{{ route('admin.kriteria.simpanPerbandingan') }}" method="POST">
            @csrf
            
            <table class="min-w-full border-collapse border border-gray-200">
                <thead>
                    <tr class="bg-gray-50">
                        <th class="p-3 border border-gray-200 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Kriteria</th>
                        @foreach($kriterias as $k)
                            <th class="p-3 border border-gray-200 text-center text-xs font-medium text-gray-500 uppercase tracking-wider">{{ $k->nama_kriteria }}</th>
                        @endforeach
                    </tr>
                </thead>
                <tbody>
                    @foreach($kriterias as $i => $k1)
                        <tr class="hover:bg-gray-50">
                            <td class="p-3 border border-gray-200 font-medium text-gray-700 bg-gray-50">{{ $k1->nama_kriteria }}</td>
                            
                            @foreach($kriterias as $j => $k2)
                                <td class="p-2 border border-gray-200 text-center">
                                    @if($k1->id == $k2->id)
                                        <input type="number" value="1" readonly class="w-16 p-1 text-center bg-gray-100 border-gray-300 rounded text-gray-500" disabled>
                                        <input type="hidden" name="matrix[{{ $k1->id }}][{{ $k2->id }}]" value="1">
                                    @elseif($i < $j)
                                        <!-- Input User (Upper Triangle) -->
                                        @php
                                            $val = $comparisons->where('kriteria_id_1', $k1->id)->where('kriteria_id_2', $k2->id)->first()->nilai ?? 1;
                                        @endphp
                                        <input type="number" step="any" min="0.1" max="9" 
                                               name="matrix[{{ $k1->id }}][{{ $k2->id }}]" 
                                               value="{{ $val }}" 
                                               class="w-20 p-1 text-center border border-gray-300 rounded focus:ring-blue-500 focus:border-blue-500 matrix-input"
                                               data-row="{{ $k1->id }}" data-col="{{ $k2->id }}">
                                    @else
                                        <!-- Auto Calc (Lower Triangle) -->
                                        @php
                                            // Coba cari nilai kebalikannya, atau 1/val
                                            $val = $comparisons->where('kriteria_id_1', $k2->id)->where('kriteria_id_2', $k1->id)->first()->nilai ?? 1;
                                            $inverse = $val != 0 ? 1/$val : 0; 
                                        @endphp
                                        <input type="number" step="any" readonly 
                                               value="{{ round($inverse, 3) }}" 
                                               class="w-20 p-1 text-center bg-gray-100 border-gray-300 rounded text-gray-500 matrix-output"
                                               data-row="{{ $k1->id }}" data-col="{{ $k2->id }}">
                                    @endif
                                </td>
                            @endforeach
                        </tr>
                    @endforeach
                </tbody>
            </table>

            <div class="mt-6 flex justify-end gap-3">
                <a href="{{ route('admin.kriteria.index') }}" class="px-4 py-2 border border-gray-300 shadow-sm text-sm font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50">
                    Batal
                </a>
                <button type="submit" class="px-4 py-2 border border-transparent shadow-sm text-sm font-medium rounded-md text-white bg-blue-600 hover:bg-blue-700">
                    Simpan & Hitung Bobot
                </button>
            </div>
            
            <div class="mt-4 p-4 bg-blue-50 text-blue-800 text-sm rounded">
                <strong>Panduan Skala Saaty (1-9):</strong><br>
                1: Sama penting<br>
                3: Sedikit lebih penting<br>
                5: Lebih penting<br>
                7: Sangat lebih penting<br>
                9: Mutlak lebih penting<br>
                (Gunakan nilai desimal misal 0.33 untuk kebalikannya, contoh: jika B lebih penting dari A sebesar 3, maka A vs B = 0.33)
            </div>
        </form>
    </div>
</div>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        const inputs = document.querySelectorAll('.matrix-input');
        
        inputs.forEach(input => {
            input.addEventListener('input', function() {
                const val = parseFloat(this.value);
                const rowId = this.getAttribute('data-row');
                const colId = this.getAttribute('data-col');
                
                // Cari elemen kebalikannya (inverse)
                const targetOutput = document.querySelector(`.matrix-output[data-row="${colId}"][data-col="${rowId}"]`);
                
                if (targetOutput && val > 0) {
                    targetOutput.value = (1 / val).toFixed(3);
                }
            });
        });
    });
</script>
@endsection
