@extends('layouts.guest')

@section('title', 'Detail Sarana & Prasarana')

@section('content')
<div class="bg-gray-50 min-h-screen py-10">
  <div class="max-w-5xl mx-auto px-6">
    <!-- Tombol kembali -->
    <div class="mb-6">
      <a href="{{ route('public.user.halamansarpras') }}" class="text-[#179ACE] hover:text-[#0F6A8F] font-medium">
        ← Kembali ke Daftar Sarpras
      </a>
    </div>

    <div class="bg-white shadow-md rounded-xl overflow-hidden">
      <div class="grid md:grid-cols-2">
        <!-- Gambar -->
        <div class="h-80 md:h-auto">
          @if($sarpras->gambar)
            <img src="{{ asset('storage/' . str_replace('public/', '', $sarpras->gambar)) }}"
                 alt="{{ $sarpras->nama_ruangan ?? $sarpras->nama_proyektor }}"
                 class="w-full h-full object-cover">
          @else
            <img src="https://via.placeholder.com/600x400?text=Tidak+Ada+Gambar"
                 alt="Tidak Ada Gambar"
                 class="w-full h-full object-cover">
          @endif
        </div>

        <!-- Detail -->
        <div class="p-8">
          <h2 class="text-2xl font-bold text-gray-800 mb-2">
            {{ $sarpras->nama_ruangan ?? $sarpras->nama_proyektor ?? 'Nama Sarpras Tidak Diketahui' }}
          </h2>

          <p class="text-gray-500 mb-6 italic">
            {{ $type === 'ruangan' ? 'Ruangan' : 'Proyektor' }}
          </p>

          <table class="w-full text-sm text-gray-600 mb-4">
            <tbody>
              @if($type === 'ruangan')
                <tr>
                  <td class="py-2 font-medium w-32">Lokasi</td>
                  <td>{{ $sarpras->lokasi->nama_lokasi ?? '-' }}</td>
                </tr>
                <tr>
                  <td class="py-2 font-medium">Kapasitas</td>
                  <td>{{ $sarpras->kapasitas ?? '-' }} orang</td>
                </tr>
              @elseif($type === 'proyektor')
                <tr>
                  <td class="py-2 font-medium w-32">Merk</td>
                  <td>{{ $sarpras->merk ?? '-' }}</td>
                </tr>
                <tr>
                  <td class="py-2 font-medium">Kode</td>
                  <td>{{ $sarpras->kode_proyektor ?? '-' }}</td>
                </tr>
              @endif

              <tr>
                <td class="py-2 font-medium">Status</td>
                <td>
                  <span class="px-2 py-1 rounded-full text-xs font-semibold
                    {{ ($sarpras->status->nama_status ?? '') === 'Tersedia'
                        ? 'bg-green-100 text-green-700'
                        : 'bg-yellow-100 text-yellow-700' }}">
                    {{ $sarpras->status->nama_status ?? 'Tidak Diketahui' }}
                  </span>
                </td>
              </tr>
            </tbody>
          </table>

          @if($sarpras->deskripsi ?? false)
            <div class="mb-6">
              <h3 class="font-semibold text-gray-800 mb-2">Deskripsi</h3>
              <p class="text-gray-600 leading-relaxed">{{ $sarpras->deskripsi }}</p>
            </div>
          @endif

          <a href="{{ route('public.peminjaman.create') }}"
             class="inline-block bg-[#179ACE] hover:bg-[#0F6A8F] text-white font-semibold px-5 py-2 rounded-lg transition">
            Ajukan Peminjaman
          </a>
        </div>
      </div>
    </div>
  </div>
</div>
@endsection
