@extends('layouts.guest')
@section('title', 'Halaman Feedback')

@section('content')
<div class="max-w-4xl mx-auto py-10 px-6">

    {{-- Header --}}
    <div class="flex justify-between items-center mb-8">
        <div>
            <h2 class="text-2xl font-bold text-gray-800 flex items-center gap-2">
                <i class="fa-solid fa-comments text-[#1180ab]"></i>
                Feedback Peminjaman
            </h2>
            <p class="text-sm text-gray-500">Berikan masukan untuk meningkatkan kualitas layanan sarana & prasarana.</p>
        </div>
        <a href="{{ route('public.sarana_perasarana.detail_sarpras', ['type' => $sarpras_type, 'id' => $id_sarpras]) }}"
           class="inline-flex items-center text-[#1180ab] hover:text-[#0f7299] transition font-medium">
            <i class="fa-solid fa-arrow-left mr-2"></i> Kembali
        </a>
    </div>

    {{-- Form Feedback Baru (Selalu ditampilkan) --}}
    <div class="bg-white rounded-2xl shadow-md p-8 mb-10 hover:shadow-lg transition">
        <h3 class="text-xl font-semibold text-gray-800 mb-4 flex items-center gap-2">
            <i class="fa-solid fa-pen-to-square text-[#1180ab]"></i> Tambah Feedback
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
                              class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:outline-none focus:ring-2 focus:ring-[#0d7198] focus:border-transparent text-gray-700 resize-none"
                              placeholder="Tulis pengalaman atau saran Anda di sini..."
                              required>{{ old('isi_feedback') }}</textarea>
                    @error('isi_feedback')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                    <p class="mt-1 text-sm text-gray-500">Minimal 10 karakter, maksimal 1000 karakter</p>
                </div>

                <div class="flex justify-end">
                    <button type="submit"
                            class="px-6 py-1.5 bg-[#1180ab] text-white rounded-sm font-medium hover:bg-[#0d7198] active:scale-[.98] transition duration-200">
                        Kirim Feedback
                    </button>
                </div>
            </form>
        </div>

    {{-- Daftar Feedback --}}
    @if ($feedbacks->isNotEmpty())
        <div class="mt-8">
            <h4 class="text-lg font-semibold text-gray-800 mb-5 flex items-center gap-2">
                <i class="fa-solid fa-clock-rotate-left text-[#1180ab]"></i> Feedback Sebelumnya
            </h4>

            <div class="space-y-6">
                @foreach ($feedbacks as $feedback)
                    <div class="bg-gray-50 border border-gray-200 rounded-2xl shadow-sm hover:shadow-md transition p-5">
                        <div class="flex items-center gap-3 mb-3">
                            <div class="w-10 h-10 rounded-full bg-gray-200 flex items-center justify-center text-gray-500 font-bold text-sm">
                                @if($feedback->user->avatar)
                                    <img src="{{ str_starts_with($feedback->user->avatar, 'http') ? $feedback->user->avatar : asset($feedback->user->avatar) }}"
                                    onerror="this.onerror=null; this.src='https://ui-avatars.com/api/?name={{ urlencode($feedback->user->nama) }}&background=random';"
                                    alt="Profile"
                                    class="h-full w-full rounded-full object-cover border border-gray-200 shadow-sm">
                                @else
                                    <div class="h-full w-full rounded-full bg-[#179ACE] text-white flex items-center justify-center font-medium text-xl shadow-sm">
                                    {{ strtoupper(substr($feedback->user->nama, 0, 1)) }}
                                    </div>
                                @endif
                            </div>
                            <div>
                                <p class="font-semibold text-gray-800">{{ $feedback->user->nama }}</p>
                                <p class="text-sm text-gray-600">{{ $feedback->user->email }}</p>
                                <p class="text-xs text-gray-500">{{ $feedback->created_at->format('d M Y, H:i') }}</p>
                            </div>
                        </div>

                        <p class="text-gray-700 leading-relaxed border-l-4 border-blue-500 pl-3">
                            {{ $feedback->isi_feedback }}
                        </p>

                        @if (Auth::id() == $feedback->peminjaman->id_akun)
                            <div x-data="{ open: false }" class="mt-3">
                                <button type="button" @click="open = true"
                                        class="text-red-600 hover:text-red-800 text-sm font-medium flex items-center gap-1 transition">
                                    <i class="fa-solid fa-trash"></i> Hapus Feedback
                                </button>

                                <div x-show="open" class="fixed inset-0 z-50 flex items-center justify-center bg-black bg-opacity-50" x-cloak>
                                    <div class="bg-white p-6 rounded-lg shadow-xl max-w-sm w-full">
                                        <h2 class="text-lg font-bold text-gray-900">Konfirmasi Hapus</h2>
                                        <p class="mt-2 text-sm text-gray-600">Apakah Anda yakin ingin menghapus feedback ini?</p>

                                        <div class="mt-6 flex justify-end space-x-3">
                                            <button @click="open = false" class="px-4 py-2 text-sm font-medium text-gray-700 bg-gray-100 rounded-md hover:bg-gray-200">
                                                Batal
                                            </button>

                                            <form action="{{ route('public.feedback.destroy', $feedback) }}" method="POST">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="px-4 py-2 text-sm font-medium text-white bg-red-600 rounded-md hover:bg-red-700">
                                                    Ya, Hapus
                                                </button>
                                            </form>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        @endif
                    </div>
                @endforeach
            </div>

            {{-- Pagination --}}
            <div class="mt-6 flex justify-center">
                {{ $feedbacks->onEachSide(1)->links() }}
            </div>
        </div>
    @endif
</div>
@endsection
