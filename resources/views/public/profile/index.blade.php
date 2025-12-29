@extends('layouts.guest')

@section('content')
<div class="min-h-screen bg-slate-50 py-8 font-sans">
    <div class="max-w-6xl mx-auto px-4 sm:px-6 lg:px-8">
        
        <div class="bg-white rounded-2xl shadow-sm border border-slate-200 overflow-hidden mb-8">
            <div class="bg-slate-900 h-32 w-full relative">
                <div class="absolute inset-0 bg-gradient-to-r from-[#1180ab] to-indigo-600 opacity-90"></div>
            </div>
            <div class="px-6 pb-6 relative">
                <div class="relative -mt-12 mb-4 flex items-end">
                    <div class="h-24 w-24 rounded-full ring-4 ring-white bg-white flex items-center justify-center shadow-md overflow-hidden">
                        @if(Auth::user()->avatar)
                            <img src="{{ str_starts_with(Auth::user()->avatar, 'http') ? Auth::user()->avatar : asset(Auth::user()->avatar) }}"
                            onerror="this.onerror=null; this.src='https://ui-avatars.com/api/?name={{ urlencode(Auth::user()->nama) }}&background=random';"
                            alt="Profile"
                            class="h-24 w-24 rounded-full object-cover border border-gray-200 shadow-sm">
                        @else
                            <div class="h-24 w-24 rounded-full bg-[#179ACE] text-white flex items-center justify-center font-medium text-4xl shadow-sm">
                            {{ strtoupper(substr(Auth::user()->nama, 0, 1)) }}
                            </div>
                        @endif
                    </div>
                </div>

                <div class="flex flex-col md:flex-row md:justify-between md:items-start gap-4">
                    <div>
                        <h1 class="text-2xl font-bold text-slate-900">{{ $user->name }}</h1>
                        <p class="text-sm text-slate-500">Member sejak {{ $user->created_at->format('d M Y') }}</p>
                    </div>
                    
                    <div class="flex flex-col sm:flex-row gap-4 text-sm text-slate-600">
                        <div class="flex items-center gap-2 bg-slate-50 px-3 py-2 rounded-lg border border-slate-100">
                            <svg class="w-4 h-4 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"></path></svg>
                            {{ $user->email }}
                        </div>
                        <div class="flex items-center gap-2 bg-slate-50 px-3 py-2 rounded-lg border border-slate-100">
                            <svg class="w-4 h-4 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z"></path></svg>
                            {{ $user->nomor_telepon ?? '-' }}
                        </div>
                        <a href="{{ route('public.profile.edit') }}" class="flex items-center gap-2 bg-blue-50 hover:bg-blue-100 px-3 py-2 rounded-lg border border-blue-200 text-[#1180ab] transition-colors">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path></svg>
                            Edit
                        </a>
                    </div>
                </div>
            </div>
        </div>

        <div class="space-y-6">
            <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
                <h2 class="text-xl font-bold text-slate-800">Riwayat Peminjaman</h2>
                
                <div class="flex p-1 bg-white rounded-xl shadow-sm border border-slate-200 overflow-x-auto no-scrollbar">
                    @php
                        $filters = [
                            '' => 'Semua', 
                            'Menunggu' => 'Pending', 
                            'Disetujui' => 'Disetujui', 
                            'Ditolak' => 'Ditolak'
                        ];
                    @endphp
                    @foreach($filters as $key => $label)
                    <a href="{{ route('public.profile.index', ['status' => $key]) }}"
                       class="px-4 py-1.5 text-sm font-medium rounded-lg whitespace-nowrap transition-all duration-200 {{ request()->status == $key ? 'bg-slate-900 text-white shadow-sm' : 'text-slate-600 hover:bg-slate-50 hover:text-slate-900' }}">
                        {{ $label }}
                    </a>
                    @endforeach
                </div>
            </div>

            @if($peminjaman->count() > 0)
                
                <div class="hidden md:block bg-white rounded-xl shadow-sm border border-slate-200 overflow-hidden">
                    <table class="min-w-full divide-y divide-slate-100">
                        <thead class="bg-slate-50">
                            <tr>
                                <th scope="col" class="px-6 py-4 text-left text-xs font-semibold text-slate-500 uppercase tracking-wider">Aset</th>
                                <th scope="col" class="px-6 py-4 text-left text-xs font-semibold text-slate-500 uppercase tracking-wider">Waktu</th>
                                <th scope="col" class="px-6 py-4 text-left text-xs font-semibold text-slate-500 uppercase tracking-wider">Keperluan</th>
                                <th scope="col" class="px-6 py-4 text-center text-xs font-semibold text-slate-500 uppercase tracking-wider">Status</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-slate-100 bg-white">
                            @foreach($peminjaman as $item)
                            <tr class="hover:bg-slate-50/80 transition-colors">
                                <td class="px-6 py-4">
                                    <div class="flex items-center">
                                        <div class="ml-4">
                                            <div class="font-medium text-slate-900">{{ $item->ruangan->nama_ruangan ?? $item->proyektor->nama_proyektor ?? '-' }}</div>
                                            <div class="text-xs text-slate-500">{{ $item->proyektor->merk ?? $item->ruangan->lokasi->nama_lokasi ?? '-' }}</div>
                                        </div>
                                    </div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="text-sm font-medium text-slate-700">
                                        {{ \Carbon\Carbon::parse($item->tanggal_pinjam)->translatedFormat('d M Y') }}
                                    </div>
                                    <div class="text-xs text-slate-500">
                                        {{ \Carbon\Carbon::parse($item->jam_mulai)->format('H:i') }} - {{ \Carbon\Carbon::parse($item->jam_selesai)->format('H:i') }}
                                    </div>
                                </td>
                                <td class="px-6 py-4">
                                    <p class="text-sm text-slate-600 max-w-[200px] truncate" title="{{ $item->jenis_kegiatan }}">
                                        {{ $item->jenis_kegiatan }}
                                    </p>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-center">
                                    <span>{{ $item->status_peminjaman }}</span>
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>

                <div class="md:hidden space-y-4">
                    @foreach($peminjaman as $item)
                    <div class="bg-white rounded-xl p-5 shadow-sm border border-slate-200">
                        <div class="flex justify-between items-start mb-4">
                            <div class="flex items-center gap-3">
                                <div>
                                    <h3 class="font-semibold text-slate-900">{{ $item->ruangan->nama_ruangan ?? $item->proyektor->nama_proyektor ?? '-' }}</h3>
                                    <p class="text-xs text-slate-500">{{ $item->proyektor->merk ?? $item->ruangan->lokasi->nama_lokasi ?? '-' }}</p>
                                </div>
                            </div>
                            <span>{{ $item->status_peminjaman }}</span>
                        </div>
                        
                        <div class="grid grid-cols-2 gap-4 text-sm border-t border-slate-100 pt-4">
                            <div>
                                <p class="text-xs text-slate-400 mb-1">Tanggal</p>
                                <p class="font-medium text-slate-700">{{ \Carbon\Carbon::parse($item->tanggal_pinjam)->translatedFormat('d M Y') }}</p>
                            </div>
                            <div>
                                <p class="text-xs text-slate-400 mb-1">Waktu</p>
                                <p class="font-medium text-slate-700">{{ \Carbon\Carbon::parse($item->jam_mulai)->format('H:i') }} - {{ \Carbon\Carbon::parse($item->jam_selesai)->format('H:i') }}</p>
                            </div>
                            <div class="col-span-2">
                                <p class="text-xs text-slate-400 mb-1">Keperluan</p>
                                <p class="text-slate-700">{{ $item->jenis_kegiatan }}</p>
                            </div>
                        </div>
                    </div>
                    @endforeach
                </div>

            @else
                @if (request()->status == 'Menunggu')
                    <div class="bg-white rounded-xl shadow-sm border border-slate-200 p-12 text-center">
                        <p class="text-slate-500 mt-2 max-w-sm mx-auto">Tidak ada peminjaman yang Pending. Silakan ajukan peminjaman aset baru.</p>
                    </div>
                @elseif (request()->status == 'Disetujui')
                    <div class="bg-white rounded-xl shadow-sm border border-slate-200 p-12 text-center">
                        <p class="text-slate-500 mt-2 max-w-sm mx-auto">Tidak ada peminjaman yang Disetujui. Silakan ajukan peminjaman aset baru.</p>
                    </div>
                @elseif (request()->status == 'Ditolak')
                    <div class="bg-white rounded-xl shadow-sm border border-slate-200 p-12 text-center">
                        <p class="text-slate-500 mt-2 max-w-sm mx-auto">Tidak ada peminjaman yang Ditolak. Silakan ajukan peminjaman aset baru.</p>
                    </div>
                @else
                    <div class="bg-white rounded-xl shadow-sm border border-slate-200 p-12 text-center">
                        <p class="text-slate-500 mt-2 max-w-sm mx-auto">Anda belum melakukan peminjaman. Silakan ajukan peminjaman aset baru.</p>
                    </div>
                @endif
            @endif
        </div>
    </div>
</div>
@endsection
