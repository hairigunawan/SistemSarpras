@extends('layouts.guest')

@section('content')
<div class="min-h-screen bg-slate-50 py-8 font-sans">
    <div class="max-w-2xl mx-auto px-4 sm:px-6 lg:px-8">
        
        <div class="bg-white rounded-2xl shadow-lg border border-slate-200 overflow-hidden">
            
            <div class="bg-slate-900 h-32 w-full relative">
                <div class="absolute inset-0 bg-gradient-to-br from-blue-600 to-indigo-700 opacity-90"></div>
                <div class="absolute inset-0 opacity-10" style="background-image: radial-gradient(#fff 1px, transparent 1px); background-size: 20px 20px;"></div>
            </div>
            
            <div class="px-6 pb-8 relative">
                <div class="relative -mt-16 mb-6 flex justify-between items-end">
                    <div class="h-24 w-24 rounded-full ring-4 ring-white bg-white flex items-center justify-center shadow-lg overflow-hidden">
                        @if(Auth::user()->avatar)
                            <img src="{{ str_starts_with(Auth::user()->avatar, 'http') ? Auth::user()->avatar : asset(Auth::user()->avatar) }}"
                            onerror="this.onerror=null; this.src='https://ui-avatars.com/api/?name={{ urlencode(Auth::user()->nama) }}&background=random';"
                            alt="Profile"
                            class="h-full w-full rounded-full object-cover border border-gray-200 shadow-sm">
                        @else
                            <div class="h-full w-full rounded-full bg-[#179ACE] text-white flex items-center justify-center font-medium text-4xl shadow-sm">
                            {{ strtoupper(substr(Auth::user()->nama, 0, 1)) }}
                            </div>
                        @endif
                    </div>
                </div>

                <div class="mb-8 border-b border-slate-100 pb-4">
                    <h1 class="text-2xl font-bold text-slate-900">Edit Profil</h1>
                    <p class="text-sm text-slate-500 mt-1">Perbarui informasi kontak dan data pribadi Anda.</p>
                </div>

                @if(session('success'))
                    <div x-data="{ show: true }" x-show="show" class="mb-6 p-4 bg-green-50 border border-green-200 rounded-lg flex justify-between items-center">
                        <div class="flex items-center">
                            <svg class="w-5 h-5 text-green-500 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                            <span class="text-green-700 text-sm font-medium">{{ session('success') }}</span>
                        </div>
                        <button @click="show = false" class="text-green-500 hover:text-green-700">&times;</button>
                    </div>
                @endif

                @if(session('error'))
                    <div x-data="{ show: true }" x-show="show" class="mb-6 p-4 bg-red-50 border border-red-200 rounded-lg flex justify-between items-center">
                        <div class="flex items-center">
                            <svg class="w-5 h-5 text-red-500 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                            <span class="text-red-700 text-sm font-medium">{{ session('error') }}</span>
                        </div>
                        <button @click="show = false" class="text-red-500 hover:text-red-700">&times;</button>
                    </div>
                @endif

                <form method="POST" action="{{ route('public.profile.update') }}" class="space-y-6" x-data="{ submitting: false }" @submit="submitting = true">
                    @csrf
                    
                    <div>
                        <label class="block text-sm font-medium text-slate-700 mb-2">Nama Lengkap</label>
                        <div class="relative">
                            <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                <svg class="h-5 w-5 text-slate-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                                </svg>
                            </div>
                            <input type="text" value="{{ $user->nama }}" 
                                class="w-full pl-10 pr-10 py-2.5 bg-slate-100 border border-slate-200 rounded-lg text-slate-500 cursor-not-allowed focus:ring-0 focus:border-slate-200" 
                                readonly>
                            <div class="absolute inset-y-0 right-0 pr-3 flex items-center pointer-events-none">
                                <svg class="h-4 w-4 text-slate-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z" />
                                </svg>
                            </div>
                        </div>
                        <p class="mt-1.5 text-xs text-slate-400 italic">Nama tidak dapat diubah. Hubungi admin untuk perubahan.</p>
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-slate-700 mb-2">Alamat Email</label>
                        <div class="relative">
                            <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                <svg class="h-5 w-5 text-slate-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z" />
                                </svg>
                            </div>
                            <input type="email" value="{{ $user->email }}" 
                                class="w-full pl-10 pr-10 py-2.5 bg-slate-100 border border-slate-200 rounded-lg text-slate-500 cursor-not-allowed focus:ring-0 focus:border-slate-200" 
                                readonly>
                            <div class="absolute inset-y-0 right-0 pr-3 flex items-center pointer-events-none">
                                <svg class="h-4 w-4 text-slate-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z" />
                                </svg>
                            </div>
                        </div>
                    </div>

                    <div>
                        <label for="nomor_telepon" class="block text-sm font-medium text-slate-700 mb-2">
                            Nomor Telepon <span class="text-red-500">*</span>
                        </label>
                        <div class="relative">
                            <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                <svg class="h-5 w-5 text-slate-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z" />
                                </svg>
                            </div>
                            <input type="tel" 
                                   id="nomor_telepon" 
                                   name="nomor_telepon" 
                                   value="{{ old('nomor_telepon', $user->nomor_telepon) }}" 
                                   placeholder="08xxxxxxxxxx"
                                   class="w-full pl-10 py-2.5 border rounded-lg focus:ring-1 focus:ring-blue-500 focus:border-blue-500 transition-colors @error('nomor_telepon') border-red-300 focus:ring-red-200 @else border-slate-300 @enderror"
                                   required
                                   oninput="this.value = this.value.replace(/[^0-9]/g, ''); if(this.value.length > 1 && !this.value.startsWith('08')) this.value='08';">
                        </div>
                        
                        @error('nomor_telepon')
                            <p class="mt-1 text-sm text-red-600 flex items-center">
                                <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"></path></svg>
                                {{ $message }}
                            </p>
                        @enderror
                        <p class="mt-1 text-xs text-slate-500">Format: Diawali 08, min 10 digit, max 13 digit.</p>
                    </div>

                    <div class="flex flex-col-reverse sm:flex-row gap-3 pt-6 border-t border-slate-100 mt-6">
                        <a href="{{ route('public.profile.index') }}" 
                           class="w-full sm:w-auto px-6 py-2.5 bg-white border border-slate-300 rounded-lg text-slate-700 font-medium hover:bg-slate-50 focus:outline-none focus:ring-1 focus:ring-slate-500 transition-all text-center">
                            Batal
                        </a>
                        <button type="submit" 
                                class="w-full sm:w-auto flex-1 px-6 py-2.5 bg-blue-600 border border-transparent rounded-lg text-white font-medium hover:bg-blue-700 focus:outline-none focus:ring-1 focus:ring-blue-500 transition-all flex justify-center items-center"
                                :disabled="submitting"
                                :class="{ 'opacity-75 cursor-wait': submitting }">
                            
                            <svg x-show="submitting" class="animate-spin -ml-1 mr-3 h-5 w-5 text-white" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                                <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                                <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                            </svg>
                            
                            <span x-text="submitting ? 'Menyimpan...' : 'Simpan Perubahan'"></span>
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection