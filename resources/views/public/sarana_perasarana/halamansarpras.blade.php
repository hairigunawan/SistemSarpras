@extends('layouts.guest')

@section('title', 'Sarana & Prasarana')

@section('content')
<div class="bg-gray-50">
  <header class="text-center py-10">
    <h1 class="text-3xl font-bold text-gray-800 mb-2">Sarana & Prasarana</h1>
    <p class="text-gray-500">Daftar fasilitas yang tersedia untuk digunakan</p>
  </header>
  <div class="max-w-7xl mx-auto px-6 pb-12">
    @if(isset($ruangans) && isset($proyektors) && $ruangans->isEmpty() && $proyektors->isEmpty())
      <div class="text-center text-gray-500 py-20">
        <p>Belum ada data sarana dan prasarana yang tersedia.</p>
      </div>
    @else
      <div class="grid sm:grid-cols-2 lg:grid-cols-3 gap-8">
        <!-- Menampilkan data ruangan -->
        @if(isset($ruangans))
          @foreach ($ruangans as $item)
            <div class="bg-white rounded-xl shadow hover:shadow-lg transition overflow-hidden">
              <div class="h-48 w-full">
                @if($item->gambar)
                  <img src="{{ asset('storage/' . str_replace('public/', '', $item->gambar)) }}"
                       alt="{{ $item->nama_ruangan }}"
                       class="w-full h-full object-cover">
                @else
                  <img src="https://via.placeholder.com/400x250?text=Tidak+Ada+Gambar"
                       alt="Tidak Ada Gambar"
                       class="w-full h-full object-cover">
                @endif
              </div>

              <div class="p-5">
                <h2 class="text-lg font-bold text-gray-800">{{ $item->nama_ruangan }}</h2>
                <p class="text-sm text-gray-500 mb-3">{{ $item->lokasi->nama_lokasi ?? '-' }}</p>

                <div class="flex justify-between items-center mb-4">
                  <span class="text-sm font-medium {{ $item->status->nama_status == 'Tersedia' ? 'text-green-600' : 'text-yellow-600' }}">
                    {{ $item->status->nama_status }}
                  </span>
                  <span class="text-sm text-gray-500 italic">Ruangan</span>
                </div>

                <div class="mb-3">
                  <span class="text-sm text-gray-600">Kapasitas: {{ $item->kapasitas }} orang</span>
                </div>

                <a href="{{ route('public.sarana_perasarana.detail_sarpras', ['type' => 'ruangan', 'id' => $item->id_ruangan]) }}"
                   class="block text-center bg-[#179ACE] hover:bg-[#0F6A8F] text-white font-semibold py-2 rounded-lg transition">
                  Lihat Detail
                </a>
              </div>
            </div>
          @endforeach
        @endif

        <!-- Menampilkan data proyektor -->
        @if(isset($proyektors))
          @foreach ($proyektors as $item)
            <div class="bg-white rounded-xl shadow hover:shadow-lg transition overflow-hidden">
              <div class="h-48 w-full">
                @if($item->gambar)
                  <img src="{{ asset('storage/' . str_replace('public/', '', $item->gambar)) }}"
                       alt="{{ $item->nama_proyektor }}"
                       class="w-full h-full object-cover">
                @else
                  <img src="https://via.placeholder.com/400x250?text=Tidak+Ada+Gambar"
                       alt="Tidak Ada Gambar"
                       class="w-full h-full object-cover">
                @endif
              </div>

              <div class="p-5">
                <h2 class="text-lg font-bold text-gray-800">{{ $item->nama_proyektor }}</h2>
                <p class="text-sm text-gray-500 mb-3">Merk: {{ $item->merk }}</p>

                <div class="flex justify-between items-center mb-4">
                  <span class="text-sm font-medium {{ $item->status->nama_status == 'Tersedia' ? 'text-green-600' : 'text-yellow-600' }}">
                    {{ $item->status->nama_status }}
                  </span>
                  <span class="text-sm text-gray-500 italic">Proyektor</span>
                </div>

                @if($item->kode_proyektor)
                  <div class="mb-3">
                    <span class="text-sm text-gray-600">Kode: {{ $item->kode_proyektor }}</span>
                  </div>
                @endif

                <a href="{{ route('public.sarana_perasarana.detail_sarpras', ['type' => 'proyektor', 'id' => $item->id_proyektor]) }}"
                   class="block text-center bg-[#179ACE] hover:bg-[#0F6A8F] text-white font-semibold py-2 rounded-lg transition">
                  Lihat Detail
                </a>
              </div>
            </div>
          @endforeach
        @endif
      </div>
    @endif
  </div>
</div>
@endsection
