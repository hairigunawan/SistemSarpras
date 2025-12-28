@extends('layouts.guest')

@section('title', 'Sarana & Prasarana')

@section('content')
<div class="bg-gray-50 min-h-screen font-sans text-gray-900">

    <div class="text-center py-8 bg-white border-b border-gray-100 mb-6">
        <h1 class="text-2xl font-bold text-gray-800">Sarana & Prasarana</h1>
        <p class="text-sm text-gray-500 mt-1">Daftar fasilitas yang tersedia</p>
    </div>

    <div class="max-w-7xl mx-auto px-4 sm:px-6 pb-12">

        <div class="mb-6 flex flex-col sm:flex-row justify-start items-start sm:items-center gap-3">
            <form action="{{ route('public.sarana_perasarana.halamansarpras') }}" method="GET" class="relative w-full sm:w-auto" x-data="{ open: false }">

                <input type="hidden" name="jenis_sarpras" x-ref="inputJenis" value="{{ $jenisSarprasFilter }}">

                @if($lokasiRuanganFilter !== 'all')
                    <input type="hidden" name="lokasi_ruangan" value="{{ $lokasiRuanganFilter }}">
                @endif

                @php
                    $labelJenis = 'Semua Jenis';
                    if($jenisSarprasFilter == 'ruangan') $labelJenis = 'Ruangan';
                    if($jenisSarprasFilter == 'proyektor') $labelJenis = 'Proyektor';
                @endphp

                <button type="button" @click="open = !open" @click.outside="open = false"
                    class="flex items-center justify-between w-full sm:w-40 pl-3 pr-3 py-1.5 text-sm bg-white border border-gray-300 rounded-lg shadow-sm hover:border-blue-400 ">
                    <span class="block truncate">{{ $labelJenis }}</span>
                    <svg class="w-4 h-4 text-gray-400 ml-2" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                        <path fill-rule="evenodd" d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z" clip-rule="evenodd" />
                    </svg>
                </button>

                <ul x-show="open"
                    x-transition:enter="transition ease-out duration-100"
                    x-transition:enter-start="transform opacity-0 scale-95"
                    x-transition:enter-end="transform opacity-100 scale-100"
                    x-transition:leave="transition ease-in duration-75"
                    x-transition:leave-start="transform opacity-100 scale-100"
                    x-transition:leave-end="transform opacity-0 scale-95"
                    class="absolute z-10 mt-1 w-full sm:w-40 bg-white shadow-lg max-h-60 rounded-md py-1 text-base overflow-auto focus:outline-none sm:text-sm"
                    style="display: none;">

                    <li class="text-gray-900 cursor-pointer select-none relative py-2 pl-3 pr-9 hover:bg-blue-50 transition-colors"
                        @click="$refs.inputJenis.value = 'all'; $el.closest('form').submit()">
                        <span class="block truncate {{ $jenisSarprasFilter == 'all' ? 'font-semibold text-gray-600' : 'font-normal' }}">Semua Jenis</span>
                    </li>

                    <li class="text-gray-900 cursor-pointer select-none relative py-2 pl-3 pr-9 hover:bg-blue-50 transition-colors"
                        @click="$refs.inputJenis.value = 'ruangan'; $el.closest('form').submit()">
                        <span class="block truncate {{ $jenisSarprasFilter == 'ruangan' ? 'font-semibold text-gray-600' : 'font-normal' }}">Ruangan</span>
                    </li>

                    <li class="text-gray-900 cursor-pointer select-none relative py-2 pl-3 pr-9 hover:bg-blue-50 transition-colors"
                        @click="$refs.inputJenis.value = 'proyektor'; $el.closest('form').submit()">
                        <span class="block truncate {{ $jenisSarprasFilter == 'proyektor' ? 'font-semibold text-gray-600' : 'font-normal' }}">Proyektor</span>
                    </li>
                </ul>
            </form>


            @if($jenisSarprasFilter === 'ruangan')
            <form action="{{ route('public.sarana_perasarana.halamansarpras') }}" method="GET" class="relative w-full sm:w-auto" x-data="{ open: false }">

                <input type="hidden" name="jenis_sarpras" value="ruangan">
                <input type="hidden" name="lokasi_ruangan" x-ref="inputLokasi" value="{{ $lokasiRuanganFilter }}">

                @php
                    $labelLokasi = 'Semua Lokasi';
                    if($lokasiRuanganFilter != 'all') {
                        $foundLokasi = $lokasis->firstWhere('id_lokasi', $lokasiRuanganFilter);
                        if($foundLokasi) $labelLokasi = $foundLokasi->nama_lokasi;
                    }
                @endphp

                <button type="button" @click="open = !open" @click.outside="open = false"
                    class="flex items-center justify-between w-full sm:w-48 pl-3 pr-3 py-1.5 text-sm bg-white border border-gray-300 rounded-lg shadow-sm hover:border-blue-400 ">
                    <span class="block truncate">{{ $labelLokasi }}</span>
                    <svg class="w-4 h-4 text-gray-400 ml-2 flex-shrink-0" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                        <path fill-rule="evenodd" d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z" clip-rule="evenodd" />
                    </svg>
                </button>

                <ul x-show="open"
                    x-transition:enter="transition ease-out duration-100"
                    x-transition:enter-start="transform opacity-0 scale-95"
                    x-transition:enter-end="transform opacity-100 scale-100"
                    x-transition:leave="transition ease-in duration-75"
                    x-transition:leave-start="transform opacity-100 scale-100"
                    x-transition:leave-end="transform opacity-0 scale-95"
                    class="absolute z-50 mt-1 min-w-full w-auto bg-white shadow-lg max-h-60 rounded-md py-1 text-base overflow-auto focus:outline-none sm:text-sm whitespace-nowrap"
                    style="display: none;">

                    <li class="text-gray-900 cursor-pointer select-none relative py-2 pl-3 pr-9 hover:bg-blue-50 transition-colors"
                        @click="$refs.inputLokasi.value = 'all'; $el.closest('form').submit()">
                        <span class="block {{ $lokasiRuanganFilter == 'all' ? 'font-semibold text-gray-600' : 'font-normal' }}">Semua Lokasi</span>
                    </li>

                    @foreach($lokasis as $lokasi)
                        <li class="text-gray-900 cursor-pointer select-none relative py-2 pl-3 pr-9 hover:bg-blue-50 transition-colors"
                            @click="$refs.inputLokasi.value = '{{ $lokasi->id_lokasi }}'; $el.closest('form').submit()">

                            <span class="block {{ $lokasiRuanganFilter == $lokasi->id_lokasi ? 'font-semibold text-gray-600' : 'font-normal' }}">
                                {{ $lokasi->nama_lokasi }}
                            </span>

                            @if($lokasiRuanganFilter == $lokasi->id_lokasi)
                            <span class="absolute inset-y-0 right-0 flex items-center pr-4 text-gray-600">
                                <svg class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor">
                                    <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd" />
                                </svg>
                            </span>
                            @endif
                        </li>
                    @endforeach
                </ul>
            </form>
            @endif
        </div>

        @if($r->isEmpty() && $p->isEmpty())
            <div class="text-center py-16 bg-white rounded-xl border border-dashed border-gray-300">
                <p class="text-gray-500 text-sm">Data tidak ditemukan.</p>
            </div>
        @else
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-5 gap-6">

                @foreach ($r as $item)
                    <div class="group bg-white rounded-xl border border-gray-200 hover:border-blue-300 hover:shadow-md transition-all duration-200 overflow-hidden flex flex-col h-full">
                        <div class="h-40 w-full bg-gray-100 relative overflow-hidden">
                            @if($item->gambar)
                                <img src="{{ asset('storage/' . str_replace('public/', '', $item->gambar)) }}"
                                     alt="{{ $item->nama_ruangan }}"
                                     class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-300">
                            @else
                                <div class="w-full h-full flex items-center justify-center text-gray-300">
                                    <svg class="w-10 h-10" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"></path></svg>
                                </div>
                            @endif
                            <div class="absolute top-2 right-2 bg-white/90 backdrop-blur px-2 py-0.5 rounded text-[10px] font-bold text-gray-600 uppercase tracking-wider shadow-sm">
                                Ruangan
                            </div>
                        </div>

                        <div class="p-4 flex flex-col flex-grow">
                            <div class="mb-3">
                                <h2 class="text-base font-bold text-gray-800 truncate" title="{{ $item->nama_ruangan }}">
                                    {{ Str::limit($item->nama_ruangan, 17) }}
                                </h2>
                                <p class="text-xs text-gray-500 flex items-center mt-1 truncate">
                                    <svg class="w-3 h-3 mr-1 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"></path></svg>
                                    {{ $item->lokasi->nama_lokasi ?? '-' }}
                                </p>
                            </div>

                            <div class="flex justify-between items-center mb-4 pt-3 border-t border-gray-50">
                                <span class="px-2 py-0.5 rounded text-[10px] font-semibold border
                                    {{ $item->status->nama_status == 'Tersedia' ? 'bg-green-50 text-green-700 border-green-100' :
                                       ($item->status->nama_status == 'Dipakai' ? 'bg-yellow-50 text-yellow-700 border-yellow-100' :
                                       ($item->status->nama_status == 'Diperbaiki' ? 'bg-orange-50 text-orange-700 border-orange-100' : 'bg-red-50 text-red-700 border-red-100')) }}">
                                    {{ $item->status->nama_status }}
                                </span>
                                <span class="text-xs text-gray-500 font-medium">
                                    {{ $item->kapasitas }} Orang
                                </span>
                            </div>

                            <div class="mt-auto">
                                <a href="{{ route('public.sarana_perasarana.detail_sarpras', ['type' => 'ruangan', 'id' => $item->id_ruangan]) }}"
                                   class="block w-full text-center bg-white border border-gray-200 hover:border-[#179ACE] hover:text-[#179ACE] text-gray-600 text-sm font-medium py-2 rounded-lg transition-colors duration-200">
                                    Detail
                                </a>
                            </div>
                        </div>
                    </div>
                @endforeach

                @foreach ($p as $item)
                    <div class="group bg-white rounded-xl border border-gray-200 hover:border-blue-300 hover:shadow-md transition-all duration-200 overflow-hidden flex flex-col h-full">
                        <div class="h-40 w-full bg-gray-100 relative overflow-hidden">
                            @if($item->gambar)
                                <img src="{{ asset('storage/' . str_replace('public/', '', $item->gambar)) }}"
                                     alt="{{ $item->nama_proyektor }}""
                                     class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-300">
                            @else
                                <div class="w-full h-full flex items-center justify-center text-gray-300">
                                    <svg class="w-10 h-10" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"></path></svg>
                                </div>
                            @endif
                            <div class="absolute top-2 right-2 bg-white/90 backdrop-blur px-2 py-0.5 rounded text-[10px] font-bold text-gray-600 uppercase tracking-wider shadow-sm">
                                Proyektor
                            </div>
                        </div>

                        <div class="p-4 flex flex-col flex-grow">
                            <div class="mb-3">
                                <h2 class="text-base font-bold text-gray-800 truncate" title="{{ $item->nama_proyektor }}">
                                    {{ Str::limit($item->nama_proyektor, 17) }}
                                </h2>
                                <p class="text-xs text-gray-500 mt-1 truncate">
                                    Merk: <span class="font-medium text-gray-700">{{ $item->merk }}</span>
                                </p>
                            </div>

                            <div class="flex justify-between items-center mb-4 pt-3 border-t border-gray-50">
                                <span class="px-2 py-0.5 rounded text-[10px] font-semibold border
                                    {{ $item->status->nama_status == 'Tersedia' ? 'bg-green-50 text-green-700 border-green-100' :
                                       ($item->status->nama_status == 'Dipakai' ? 'bg-yellow-50 text-yellow-700 border-yellow-100' :
                                       ($item->status->nama_status == 'Diperbaiki' ? 'bg-orange-50 text-orange-700 border-orange-100' : 'bg-red-50 text-red-700 border-red-100')) }}">
                                    {{ $item->status->nama_status }}
                                </span>
                                    <span class="text-[10px] bg-gray-100 px-1.5 py-0.5 rounded text-gray-600">{{ $item->kode_proyektor }}
                                </span>
                            </div>

                            <div class="mt-auto">
                                <a href="{{ route('public.sarana_perasarana.detail_sarpras', ['type' => 'proyektor', 'id' => $item->id_proyektor]) }}"
                                   class="block w-full text-center bg-white border border-gray-200 hover:border-[#179ACE] hover:text-[#179ACE] text-gray-600 text-sm font-medium py-2 rounded-lg transition-colors duration-200">
                                    Detail
                                </a>
                            </div>
                        </div>
                    </div>
                @endforeach

            </div>
        @endif
    </div>
</div>
@endsection
