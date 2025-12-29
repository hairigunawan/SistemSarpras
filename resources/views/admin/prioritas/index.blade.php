@extends('layouts.app')

@section('content')
<div class="min-h-screen bg-gray-100 py-10 px-4 sm:px-6 lg:px-8">
    <div class="max-w-4xl mx-auto">

        {{-- Section Title & Badge --}}
        <div class="flex items-center justify-between mb-6">
            <h2 class="text-xl font-bold text-gray-800">Ruangan</h2>
            <div class="text-xs font-medium bg-white px-3 py-1 rounded-md text-gray-500 shadow-sm border border-gray-200">
                Total: {{ count($ruangan) }}
            </div>
        </div>

        {{-- LIST STYLE: RUANGAN --}}
        <div class="space-y-3 mb-12">
            {{-- Header Row (Fake Table Header) --}}
            <div class="grid grid-cols-12 gap-4 px-4 py-2 text-xs font-semibold text-gray-400 uppercase tracking-wider">
                <div class="col-span-1">#</div>
                <div class="col-span-5">Peminjam & Jenis</div>
                <div class="col-span-3 text-right">Nilai AHP</div>
                <div class="col-span-3 text-right">Nilai SAW</div>
            </div>

            @forelse($ruangan as $index => $r)
            <div class="group relative bg-white rounded-lg p-4 shadow-sm border border-gray-200 hover:shadow-md hover:border-blue-300 transition-all duration-200">
                {{-- Aksen warna di kiri --}}
                <div class="absolute left-0 top-0 bottom-0 w-1 bg-[#1180ab] rounded-l-lg opacity-0 group-hover:opacity-100 transition-opacity"></div>

                <div class="grid grid-cols-12 gap-4 items-center">
                    {{-- No --}}
                    <div class="col-span-1 text-gray-400 font-mono text-sm">{{ $index + 1 }}</div>

                    {{-- Nama & Jenis --}}
                    <div class="col-span-5">
                        <div class="font-semibold text-gray-900">{{ $r->nama_peminjam }}</div>
                        <div class="text-xs text-[#1180ab] bg-blue-50 inline-block px-2 py-0.5 rounded mt-1">
                            {{ $r->jenis }}
                        </div>
                    </div>

                    {{-- Nilai AHP --}}
                    <div class="col-span-3 text-right">
                        <span class="text-sm text-gray-500 font-mono">{{ number_format($r->nilai_ahp, 4) }}</span>
                    </div>

                    {{-- Nilai SAW (Highlight) --}}
                    <div class="col-span-3 text-right">
                        <span class="text-lg font-bold text-gray-800 font-mono">{{ number_format($r->nilai_saw, 4) }}</span>
                    </div>
                </div>
            </div>
            @empty
                <div class="text-center py-8 text-gray-400 bg-white rounded-lg border border-dashed border-gray-300">
                    Tidak ada data ruangan
                </div>
            @endforelse
        </div>

        <hr class="border-gray-200 my-8">

        {{-- Section Title & Badge --}}
        <div class="flex items-center justify-between mb-6">
            <h2 class="text-xl font-bold text-gray-800">Proyektor</h2>
            <div class="text-xs font-medium bg-white px-3 py-1 rounded-md text-gray-500 shadow-sm border border-gray-200">
                Total: {{ count($proyektor) }}
            </div>
        </div>

        {{-- LIST STYLE: PROYEKTOR --}}
        <div class="space-y-3">
             <div class="grid grid-cols-12 gap-4 px-4 py-2 text-xs font-semibold text-gray-400 uppercase tracking-wider">
                <div class="col-span-1">#</div>
                <div class="col-span-5">Peminjam & Jenis</div>
                <div class="col-span-3 text-right">Nilai AHP</div>
                <div class="col-span-3 text-right">Nilai SAW</div>
            </div>

            @forelse($proyektor as $index => $p)
            <div class="group relative bg-white rounded-lg p-4 shadow-sm border border-gray-200 hover:shadow-md hover:border-emerald-300 transition-all duration-200">
                <div class="absolute left-0 top-0 bottom-0 w-1 bg-emerald-500 rounded-l-lg opacity-0 group-hover:opacity-100 transition-opacity"></div>

                <div class="grid grid-cols-12 gap-4 items-center">
                    <div class="col-span-1 text-gray-400 font-mono text-sm">{{ $index + 1 }}</div>
                    <div class="col-span-5">
                        <div class="font-semibold text-gray-900">{{ $p->nama_peminjam }}</div>
                        <div class="text-xs text-emerald-600 bg-emerald-50 inline-block px-2 py-0.5 rounded mt-1">
                            {{ $p->jenis }}
                        </div>
                    </div>
                    <div class="col-span-3 text-right">
                        <span class="text-sm text-gray-500 font-mono">{{ number_format($p->nilai_ahp, 4) }}</span>
                    </div>
                    <div class="col-span-3 text-right">
                        <span class="text-lg font-bold text-gray-800 font-mono">{{ number_format($p->nilai_saw, 4) }}</span>
                    </div>
                </div>
            </div>
            @empty
                <div class="text-center py-8 text-gray-400 bg-white rounded-lg border border-dashed border-gray-300">
                    Tidak ada data proyektor
                </div>
            @endforelse
        </div>

    </div>
</div>
@endsection
