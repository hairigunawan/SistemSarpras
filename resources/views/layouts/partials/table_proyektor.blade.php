<div class="bg-gray-50 min-h-screen p-2">
    <div class="bg-white p-6">

        <!-- Grid Ruangan -->
        @if($proyektors->isEmpty())
            <div class="text-center py-16 text-gray-500">
                <p class="text-lg font-semibold">Tidak ada proyektor</p>
                <p class="text-sm text-gray-400 mt-1">Silakan tambahkan sarpras baru untuk ditampilkan</p>
            </div>
        @else
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-6">
                @foreach($proyektors as $proyektor)
                    <div class="bg-white border border-gray-200 rounded-xl overflow-hidden shadow-sm hover:shadow-lg transform hover:-translate-y-1 transition-all duration-200">
                        <!-- Gambar -->
                        <div class="aspect-[4/3] bg-gray-100 overflow-hidden">
                            @if($proyektor->gambar)
                                <img src="{{ asset('storage/' . $proyektor->gambar) }}"
                                     alt="{{ $proyektor->nama_proyektor }}"
                                     class="w-full h-full object-cover">
                            @else
                                <div class="flex items-center justify-center h-full text-gray-400">
                                    <svg class="w-10 h-10" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                              d="M3 7a2 2 0 012-2h14a2 2 0 012 2v10a2 2 0 01-2 2H5a2 2 0 01-2-2V7z"/>
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                              d="M16 11a4 4 0 11-8 0 4 4 0 018 0z"/>
                                    </svg>
                                </div>
                            @endif
                        </div>

                        <!-- Info Ruangan -->
                        <div class="p-4">
                            <div class="flex justify-between items-center mb-2">
                                <h3 class="text-base font-semibold text-gray-800 truncate">
                                    {{ $proyektor->nama_proyektor }}
                                </h3>
                                <span class="text-xs px-2 py-1 rounded-full font-medium
                                    @if($proyektor->status && $proyektor->status->nama_status == 'Tersedia')
                                        bg-green-100 text-green-700
                                    @elseif($proyektor->status && $proyektor->status->nama_status == 'Dipinjam')
                                        bg-yellow-100 text-yellow-700
                                    @else
                                        bg-gray-100 text-gray-600
                                    @endif">
                                    {{ $proyektor->status->nama_status ?? 'Tidak Aktif' }}
                                </span>
                            </div>
                            <p class="text-gray-500 text-sm mb-4">
                                {{ $proyektor->kode_proyektor }}
                            </p>

                            <a href="{{ route('admin.sarpras.proyektor.lihat_proyektor', ['proyektor' => $proyektor->id_proyektor]) }}"
                               class="block text-center w-full bg-blue-500 hover:bg-blue-600 text-white text-sm font-medium py-2 rounded-lg transition-all duration-150">
                                Lihat Detail
                            </a>
                        </div>
                    </div>
                @endforeach
            </div>

            <!-- Pagination -->
            @if($proyektors->hasPages())
                <div class="mt-8 flex justify-center">
                    {{ $proyektors->links() }}
                </div>
            @endif
        @endif
    </div>
</div>
