@extends('layouts.app')
@section('title', 'Akun - Buat Akun')

@push('styles')
<style>
    [x-cloak] { display: none !important; }
</style>
@endpush

@section('content')
<div class="min-h-screen bg-gray-50 py-8 px-4 sm:px-6 lg:px-8">
    <div class="max-w-7xl mx-auto">

        <!-- Header Section -->
        <div class="mb-6 flex items-center justify-between">
            <div class="flex items-center space-x-4">
                <a href="{{ route('admin.akun.index') }}" class="p-2 rounded-lg hover:bg-gray-200 transition-colors">
                    <i class="fas fa-arrow-left text-gray-600"></i>
                </a>
                <div>
                    <h1 class="text-2xl font-bold text-gray-900">Buat Akun Baru</h1>
                    <p class="text-sm text-gray-500">Isi informasi lengkap untuk membuat pengguna baru.</p>
                </div>
            </div>

            <a href="{{ route('admin.akun.index') }}"
               class="inline-flex items-center gap-2 rounded-lg bg-white px-4 py-2 text-sm font-medium text-gray-700 shadow-sm ring-1 ring-gray-200 hover:bg-gray-50 active:bg-gray-100">
                <svg class="h-5 w-5 text-gray-500" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M19 12H5m0 0l6-6m-6 6l6 6"/>
                </svg>
                Kembali
            </a>
        </div>

        <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
            <div class="p-8">
                <form method="POST" action="{{ route('admin.akun.store.new') }}">
                    @csrf

                    <div class="space-y-6">
                        <div>
                            <label for="nama" class="block text-sm font-medium text-gray-700 mb-1">Nama Lengkap</label>
                            <div class="relative rounded-md shadow-sm">
                                <input type="text" name="nama" id="nama"
                                    value="{{ old('nama') }}"
                                    class="pl-3 block w-full rounded-md border-gray-300 focus:ring-[#0d7198] focus:border-blue-500 sm:text-sm py-2.5 border
                                    @error('nama') border-red-300 text-red-900 placeholder-red-300 focus:ring-red-500 focus:border-red-500 @enderror"
                                    placeholder="Masukkan nama lengkap">
                            </div>
                            @error('nama')
                                <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <div>
                            <label for="email" class="block text-sm font-medium text-gray-700 mb-1">Alamat Email</label>
                            <div class="relative rounded-md shadow-sm">
                                <input type="email" name="email" id="email"
                                    value="{{ old('email') }}"
                                    class="pl-3 block w-full rounded-md border-gray-300 focus:ring-[#0d7198] focus:border-blue-500 sm:text-sm py-2.5 border
                                    @error('email') border-red-300 text-red-900 placeholder-red-300 focus:ring-red-500 focus:border-red-500 @enderror"
                                    placeholder="contoh@email.com">
                            </div>
                            @error('email')
                                <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <div>
                            <label for="role_id" class="block text-sm font-medium text-gray-700 mb-1">Role Pengguna</label>
                            <div class="relative">
                                <select name="role_id" id="role_id"
                                    class="pl-3 block w-full rounded-md border-gray-300 focus:ring-[#0d7198] focus:border-blue-500 sm:text-sm py-2.5 border bg-white">
                                    <option value="" disabled selected>-- Pilih Role --</option>
                                    @foreach($roles ?? [] as $r)
                                        <option value="{{ $r->id_role }}" {{ old('role_id') == $r->id_role ? 'selected' : '' }}>
                                            {{ $r->nama_role }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                            @error('role_id')
                                <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <div>
                            <label for="nomor_telepon" class="block text-sm font-medium text-gray-700 mb-1">Nomor Telepon (WhatsApp)</label>
                            <div class="relative rounded-md shadow-sm">
                                <input type="text" name="nomor_telepon" id="nomor_telepon"
                                    value="{{ old('nomor_telepon') }}"
                                    class="pl-3 block w-full rounded-md border-gray-300 focus:ring-[#0d7198] focus:border-blue-500 sm:text-sm py-2.5 border
                                    @error('nomor_telepon') border-red-300 text-red-900 placeholder-red-300 focus:ring-red-500 focus:border-red-500 @enderror"
                                    placeholder="Contoh: 081234567890">
                            </div>
                            @error('nomor_telepon')
                                <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <div class="grid grid-cols-1 gap-6 sm:grid-cols-2">

                            <div x-data="{ showPassword: false }">
                                <label for="password" class="block text-sm font-medium text-gray-700 mb-1">Password</label>
                                <div class="relative rounded-md shadow-sm">
                                    <input :type="showPassword ? 'text' : 'password'"
                                        name="password"
                                        id="password"
                                        class="pl-3 pr-10 block w-full rounded-md border-gray-300 focus:ring-[#0d7198] focus:border-blue-500 sm:text-sm py-2.5 border
                                        @error('password') border-red-300 focus:ring-red-500 focus:border-red-500 @enderror">

                                    <div class="absolute inset-y-0 right-0 pr-3 flex items-center">
                                        <button type="button" @click="showPassword = !showPassword" class="text-gray-400 hover:text-gray-600 focus:outline-none">
                                            <svg x-show="!showPassword" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                                            </svg>
                                            <svg x-show="showPassword" x-cloak class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13.875 18.825A10.05 10.05 0 0112 19c-4.478 0-8.268-2.943-9.543-7a9.97 9.97 0 011.563-3.029m5.858.908a3 3 0 114.243 4.243M9.878 9.878l4.242 4.242M9.88 9.88l-3.29-3.29m7.532 7.532l3.29 3.29M3 3l3.59 3.59m0 0A9.953 9.953 0 0112 5c4.478 0 8.268 2.943 9.543 7a10.025 10.025 0 01-4.132 5.411m0 0L21 21" />
                                            </svg>
                                        </button>
                                    </div>
                                </div>
                                @error('password')
                                    <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>

                            <div x-data="{ showConfirm: false }">
                                <label for="password_confirmation" class="block text-sm font-medium text-gray-700 mb-1">Konfirmasi Password</label>
                                <div class="relative rounded-md shadow-sm">
                                    <input :type="showConfirm ? 'text' : 'password'"
                                        name="password_confirmation"
                                        id="password_confirmation"
                                        class="pl-3 pr-10 block w-full rounded-md border-gray-300 focus:ring-[#0d7198] focus:border-blue-500 sm:text-sm py-2.5 border">

                                    <div class="absolute inset-y-0 right-0 pr-3 flex items-center">
                                        <button type="button" @click="showConfirm = !showConfirm" class="text-gray-400 hover:text-gray-600 focus:outline-none">
                                            <svg x-show="!showConfirm" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                                            </svg>
                                            <svg x-show="showConfirm" x-cloak class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13.875 18.825A10.05 10.05 0 0112 19c-4.478 0-8.268-2.943-9.543-7a9.97 9.97 0 011.563-3.029m5.858.908a3 3 0 114.243 4.243M9.878 9.878l4.242 4.242M9.88 9.88l-3.29-3.29m7.532 7.532l3.29 3.29M3 3l3.59 3.59m0 0A9.953 9.953 0 0112 5c4.478 0 8.268 2.943 9.543 7a10.025 10.025 0 01-4.132 5.411m0 0L21 21" />
                                            </svg>
                                        </button>
                                    </div>
                                </div>
                            </div>

                        </div>
                    </div>

                    <div class="mt-8 pt-5 border-t border-gray-200 flex items-center justify-end space-x-3">
                        <a href="{{ route('admin.akun.index') }}"
                           class="inline-flex justify-center px-4 py-2 text-sm font-medium text-gray-700 bg-white border border-gray-300 rounded-md shadow-sm hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-[#0d7198]">
                            Batal
                        </a>
                        <button type="submit"
                            class="inline-flex justify-center px-6 py-2 text-sm font-medium text-white bg-[#1180ab] border border-transparent rounded-md shadow-sm hover:bg-[#0d7198] focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-[#0d7198] transition-colors duration-200">
                            Simpan Akun
                        </button>
                    </div>

                </form>
            </div>
        </div>
    </div>
</div>
@endsection
