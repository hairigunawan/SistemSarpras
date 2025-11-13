@extends('layouts.guest')

@section('title', 'Detail Sarana & Prasarana')

@section('content')
<div class="bg-gray-50 min-h-screen py-10">
  <div class="max-w-6xl mx-auto px-6 lg:px-8">

    <!-- Tombol kembali -->
    <div class="mb-6">
      <a href="{{ route('public.sarana_perasarana.halamansarpras') }}"
         class="inline-flex items-center text-[#179ACE] hover:text-[#0F6A8F] font-medium transition">
        <i class="fa-solid fa-arrow-left mr-2"></i> Kembali ke Daftar Sarpras
      </a>
    </div>

    <!-- Card utama -->
    <div class="bg-white border border-gray-200 rounded-2xl overflow-hidden">
      <div class="grid md:grid-cols-2">

        <!-- Bagian Gambar -->
        <div class="relative h-72 md:h-auto">
          @if($sarpras->gambar)
            <img src="{{ asset('storage/' . str_replace('public/', '', $sarpras->gambar)) }}"
                 alt="{{ $sarpras->nama_ruangan ?? $sarpras->nama_proyektor }}"
                 class="w-full h-full object-cover">
          @else
            <img src="https://via.placeholder.com/600x400?text=Tidak+Ada+Gambar"
                 alt="Tidak Ada Gambar"
                 class="w-full h-full object-cover">
          @endif
          <span class="absolute top-3 right-3 bg-white/80 backdrop-blur-md text-gray-800 text-xs font-semibold px-3 py-1 rounded-full shadow">
            {{ $type === 'ruangan' ? 'Ruangan' : 'Proyektor' }}
          </span>
        </div>

        <!-- Bagian Detail -->
        <div class="p-8">
          <!-- Nama -->
          <div class="flex justify-between">
            <h2 class="text-3xl font-bold text-gray-800 mb-3">
                {{ $sarpras->nama_ruangan ?? $sarpras->nama_proyektor ?? 'Nama Sarpras Tidak Diketahui' }}
            </h2>
            <!-- Tombol Peminjaman -->
            <div class="mb-6">
                <a href="{{ route('public.peminjaman.create', ['sarpras_type' => $type, 'sarpras_id' => $sarpras->id_ruangan ?? $sarpras->id_proyektor]) }}"
                class="text-sm px-3 py-1.5 bg-[#179ACE] text-white rounded hover:bg-[#137aa3]"></i> Ajukan Peminjaman
                </a>
            </div>
          </div>

          <!-- Status -->
          <div class="mb-6">
            @if($mainPeminjaman)
              <span class="inline-flex items-center px-3 py-1.5 rounded-full text-xs font-semibold
                {{ $mainPeminjaman->status_peminjaman === 'Menunggu'
                    ? 'bg-green-100 text-green-700'
                    : 'bg-yellow-100 text-yellow-700' }}">
                <i class="fa-solid fa-circle text-xs"></i>
                {{ $mainPeminjaman->status_peminjaman }}
              </span>
            @else
              <!-- Tampilkan status dari sumber daya -->
              <span class="inline-flex items-center px-3 py-1.5 rounded-full text-xs font-semibold
                {{ $resourceStatus === 'Tersedia'
                    ? 'bg-blue-100 text-blue-700'
                    : ($resourceStatus === 'Dipakai'
                        ? 'bg-yellow-100 text-yellow-700'
                        : ($resourceStatus === 'Diperbaiki'
                            ? 'bg-orange-100 text-orange-700'
                            : 'bg-red-100 text-red-700')) }}">
                <i class="fa-solid fa-circle text-xs"></i>
                {{ $resourceStatus }}
              </span>
            @endif
          </div>

          <!-- Tabel Informasi -->
          <div class="overflow-hidden rounded-lg border border-gray-200 mb-6">
            <table class="w-full text-sm text-gray-700">
              <tbody class="divide-y divide-gray-100">
                @if($type === 'ruangan')
                  <tr>
                    <td class="py-3 px-4 font-medium bg-gray-50 w-1/3">Lokasi</td>
                    <td class="py-3 px-4">{{ $sarpras->lokasi->nama_lokasi ?? '-' }}</td>
                  </tr>
                  <tr>
                    <td class="py-3 px-4 font-medium bg-gray-50">Kapasitas</td>
                    <td class="py-3 px-4">{{ $sarpras->kapasitas ?? '-' }} orang</td>
                  </tr>
                @elseif($type === 'proyektor')
                  <tr>
                    <td class="py-3 px-4 font-medium bg-gray-50 w-1/3">Merk</td>
                    <td class="py-3 px-4">{{ $sarpras->merk ?? '-' }}</td>
                  </tr>
                  <tr>
                    <td class="py-3 px-4 font-medium bg-gray-50">Kode</td>
                    <td class="py-3 px-4">{{ $sarpras->kode_proyektor ?? '-' }}</td>
                  </tr>
                @endif
              </tbody>
            </table>
          </div>

          <!-- Deskripsi -->
          @if($sarpras->deskripsi ?? false)
            <div class="mb-6">
              <h3 class="text-lg font-semibold text-gray-800 mb-2">Deskripsi</h3>
              <p class="text-gray-600 leading-relaxed">{{ $sarpras->deskripsi }}</p>
            </div>
          @endif

          <!-- Bagian Feedback -->
          <div class="border-t pt-5">
            <h4 class="font-semibold text-gray-800 mb-2">Feedback</h4>

            @if(Auth::check())
              <p class="text-sm text-gray-600 mb-6">
              </p>
              <a href="{{ route('public.feedback.index', ['id_sarpras' => $sarpras->id_ruangan ?? $sarpras->id_proyektor, 'type' => $type]) }}"
                 class="text-sm px-3 py-1.5 bg-[#656564] text-white rounded hover:bg-[#404040]"></i> Berikan Feedback
              </a>
            @else
              <p class="text-sm text-gray-600 mb-3">
                Silakan login untuk dapat memberikan feedback.
              </p>
              <a href="{{ route('login') }}"
                 class="inline-flex items-center justify-center bg-gray-600 hover:bg-gray-700 text-white font-semibold px-5 py-2.5 rounded-lg transition duration-200 shadow-sm">
                <i class="fa-solid fa-right-to-bracket mr-2"></i> Login untuk Berikan Feedback
              </a>
            @endif

            <!-- Menampilkan daftar feedback -->
            @if($feedbacks && $feedbacks->count() > 0)
              <div class="mt-6 space-y-4">
                <h5 class="text-md font-medium text-gray-700">Feedback Peminjam</h5>
                @foreach($feedbacks as $feedback)
                  <div class="bg-gray-50 rounded-lg p-4 border border-gray-200">
                    <div class="flex justify-between items-start mb-2">
                      <div class="flex items-center">
                        <div class="w-8 h-8 bg-[#179ACE] rounded-full flex items-center justify-center text-white text-sm font-semibold mr-3">
                          {{ strtoupper(substr($feedback->user->nama, 0, 1)) }}
                        </div>
                        <div>
                          <p class="font-medium text-gray-800">{{ $feedback->user->nama }}</p>
                          <p class="text-xs text-gray-500">{{ $feedback->created_at->format('d M Y, H:i') }}</p>
                        </div>
                      </div>
                    </div>
                    <p class="text-gray-700 leading-relaxed">{{ $feedback->isi_feedback }}</p>
                  </div>
                @endforeach
              </div>
            @else
              <div class="mt-4 p-4 bg-gray-50 rounded-lg border border-gray-200">
                <p class="text-sm text-gray-600 text-center">Belum ada feedback untuk sumber daya ini.</p>
              </div>
            @endif
          </div>
        </div>
      </div>
    </div>
  </div>
</div>
@endsection
