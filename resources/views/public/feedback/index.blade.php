@extends('layouts.guest')
@section('title', 'Halaman Feedback')

@section('content')
<div class="max-w-4xl mx-auto py-10 px-6">

    {{-- Header --}}
    <div class="flex justify-between items-center mb-8">
        <div>
            <h2 class="text-2xl font-bold text-gray-800 flex items-center gap-2">
                <i class="fa-solid fa-comments text-blue-600"></i>
                Feedback Peminjaman
            </h2>
            <p class="text-sm text-gray-500">Berikan masukan untuk meningkatkan kualitas layanan sarana & prasarana.</p>
        </div>
        <a href="{{ route('public.sarana_perasarana.halamansarpras') }}"
           class="inline-flex items-center text-blue-600 hover:text-blue-800 transition font-medium">
            <i class="fa-solid fa-arrow-left mr-2"></i> Kembali
        </a>
    </div>

    {{-- Detail Ruangan --}}
    @if ($ruangan)
        <div class="mb-6 bg-blue-50 border border-blue-100 p-5 rounded-2xl shadow-sm hover:shadow-md transition">
            <h3 class="text-lg font-semibold text-blue-800 mb-3 flex items-center gap-2">
                <i class="fa-solid fa-door-open"></i> Detail Ruangan
            </h3>
            <div class="flex items-center gap-5">
                <img src="{{ asset('storage/' . $ruangan->gambar) }}" alt="{{ $ruangan->nama_ruangan }}"
                     class="w-28 h-28 rounded-xl object-cover shadow-sm">
                <div>
                    <p class="text-lg font-medium text-gray-800">{{ $ruangan->nama_ruangan }}</p>
                    <p class="text-sm text-gray-600">{{ $ruangan->lokasi->nama_lokasi }}</p>
                </div>
            </div>
        </div>
    @endif

    {{-- Detail Proyektor --}}
    @if ($proyektor)
        <div class="mb-6 bg-green-50 border border-green-100 p-5 rounded-2xl shadow-sm hover:shadow-md transition">
            <h3 class="text-lg font-semibold text-green-800 mb-3 flex items-center gap-2">
                <i class="fa-solid fa-video"></i> Detail Proyektor
            </h3>
            <div class="flex items-center gap-5">
                <img src="{{ asset('storage/' . $proyektor->gambar) }}" alt="{{ $proyektor->nama_proyektor }}"
                     class="w-28 h-28 rounded-xl object-cover shadow-sm">
                <div>
                    <p class="text-lg font-medium text-gray-800">{{ $proyektor->nama_proyektor }}</p>
                    <p class="text-sm text-gray-600">Merk: {{ $proyektor->merk }}</p>
                </div>
            </div>
        </div>
    @endif

    {{-- Form Feedback --}}
    @if (!$existingFeedback)
        <div class="bg-white rounded-2xl shadow-md p-8 mb-10 hover:shadow-lg transition">
            <h3 class="text-xl font-semibold text-gray-800 mb-4 flex items-center gap-2">
                <i class="fa-solid fa-pen-to-square text-blue-500"></i> Tambah Feedback
            </h3>

            <form action="{{ route('public.feedback.store') }}" method="POST" class="space-y-5">
                @csrf
                <input type="hidden" name="id_sarpras" value="{{ $id_sarpras }}">
                <input type="hidden" name="type" value="{{ $sarpras_type }}">
                <input type="hidden" name="id_peminjaman" value="{{ $peminjaman->id_peminjaman }}">

                <div>
                    <label for="isi_feedback" class="block text-sm font-medium text-gray-700 mb-2">
                        Isi Feedback <span class="text-red-500">*</span>
                    </label>
                    <textarea id="isi_feedback" name="isi_feedback" rows="5"
                              class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent text-gray-700 resize-none"
                              placeholder="Tulis pengalaman atau saran Anda di sini..."
                              required>{{ old('isi_feedback') }}</textarea>
                    @error('isi_feedback')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                    <p class="mt-1 text-sm text-gray-500">Minimal 10 karakter, maksimal 1000 karakter</p>
                </div>

                <div class="flex justify-end">
                    <button type="submit"
                            class="px-6 py-2.5 bg-blue-600 text-white rounded-xl font-medium hover:bg-blue-700 active:scale-[.98] transition duration-200">
                        Kirim Feedback
                    </button>
                </div>
            </form>
        </div>
    @else
        <div class="mb-8 bg-green-50 border border-green-200 text-green-800 p-4 rounded-xl flex items-center gap-2">
            <i class="fa-solid fa-circle-check text-green-600 text-lg"></i>
            <span>Anda sudah memberikan feedback untuk peminjaman ini.</span>
        </div>
    @endif

    {{-- Daftar Feedback --}}
    @if ($feedbacks->isNotEmpty())
        <div class="mt-8">
            <h4 class="text-lg font-semibold text-gray-800 mb-5 flex items-center gap-2">
                <i class="fa-solid fa-clock-rotate-left text-blue-500"></i> Feedback Sebelumnya
            </h4>

            <div class="space-y-6">
                @foreach ($feedbacks as $feedback)
                    <div class="bg-gray-50 border border-gray-200 rounded-2xl shadow-sm hover:shadow-md transition p-5">
                        <div class="flex items-center gap-3 mb-3">
                            <img src="{{ asset('storage/' . Auth::user()->avatar) }}" alt="{{ Auth::user()->nama }}"
                                 class="h-12 w-12 rounded-full object-cover border border-gray-200">
                            <div>
                                <p class="font-semibold text-gray-800">{{ Auth::user()->nama }}</p>
                                <p class="text-sm text-gray-600">{{ Auth::user()->email }}</p>
                                <p class="text-xs text-gray-500">{{ $feedback->created_at->format('d M Y, H:i') }}</p>
                            </div>
                        </div>

                        <p class="text-gray-700 leading-relaxed border-l-4 border-blue-500 pl-3">
                            {{ $feedback->isi_feedback }}
                        </p>

                        @if (Auth::id() == $feedback->peminjaman->id_akun)
                            <form action="{{ route('public.feedback.destroy', $feedback) }}" method="POST" class="mt-3">
                                @csrf
                                @method('DELETE')
                                <button type="submit"
                                        onclick="return confirm('Apakah Anda yakin ingin menghapus feedback ini?')"
                                        class="text-red-600 hover:text-red-800 text-sm font-medium flex items-center gap-1 transition">
                                    <i class="fa-solid fa-trash"></i> Hapus Feedback
                                </button>
                            </form>
                        @endif
                    </div>
                @endforeach
            </div>
        </div>
    @endif
</div>
@endsection
