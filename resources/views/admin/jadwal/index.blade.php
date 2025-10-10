@extends('layouts.app')

@section('content')
<div class="p-8">

    {{-- Header Judul & Tombol Tambah --}}
    <div class="flex justify-between items-center mb-6">
        <h2 class="text-2xl font-semibold text-blue-700">📅 Daftar Jadwal</h2>
        <a href="{{ route('jadwal.create') }}" 
           class="px-5 py-2 bg-blue-600 text-white rounded-lg shadow hover:bg-blue-700 transition">
           + Tambah Jadwal
        </a>
    </div>

    {{-- Notifikasi Sukses --}}
    @if(session('success'))
        <div class="mb-4 p-4 bg-green-100 border border-green-400 text-green-700 rounded-lg shadow-sm">
            {{ session('success') }}
        </div>
    @endif

    {{-- Tabel Jadwal --}}
    <div class="overflow-x-auto bg-white rounded-2xl shadow-lg border border-gray-200">
        <table class="w-full text-sm text-left text-gray-700">
            <thead class="bg-blue-50 border-b text-blue-700 uppercase text-xs">
                <tr>
                    <th class="px-4 py-3">Kode MK</th>
                    <th class="px-4 py-3">Nama Kelas</th>
                    <th class="px-4 py-3">Kelas</th>
                    <th class="px-4 py-3">Hari</th>
                    <th class="px-4 py-3">Jam</th>
                    <th class="px-4 py-3">Ruangan</th>
                    <th class="px-4 py-3">Daya Tampung</th>
                    <th class="px-4 py-3 text-center">Aksi</th>
                </tr>
            </thead>
            <tbody>
                @forelse($jadwals as $jadwal)
                    <tr class="border-b hover:bg-blue-50 transition">
                        <td class="px-4 py-3">{{ $jadwal->kode_mk }}</td>
                        <td class="px-4 py-3">{{ $jadwal->nama_kelas }}</td>
                        <td class="px-4 py-3">{{ $jadwal->kelas_mahasiswa }}</td>
                        <td class="px-4 py-3">{{ $jadwal->hari }}</td>
                        <td class="px-4 py-3">{{ $jadwal->jam_mulai }} - {{ $jadwal->jam_selesai }}</td>
                        <td class="px-4 py-3">{{ $jadwal->ruangan }}</td>
                        <td class="px-4 py-3 text-center">{{ $jadwal->daya_tampung }}</td>
                        <td class="px-4 py-3 flex justify-center gap-2">
                            <a href="{{ route('jadwal.edit', $jadwal->id_jadwal) }}" 
                               class="px-3 py-1.5 bg-yellow-400 text-white rounded-lg shadow hover:bg-yellow-500 transition">
                               ✏️ Edit
                            </a>
                            <form action="{{ route('jadwal.destroy', $jadwal->id_jadwal) }}" method="POST" onsubmit="return confirm('Yakin hapus?')">
                                @csrf
                                @method('DELETE')
                               <button type="submit" 
                                    class="px-3 py-1.5 bg-red-500 text-white rounded-lg shadow hover:bg-red-600 transition">
                                    🗑️ Hapus
                                </button>
                            </form>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="8" class="px-4 py-3 text-center text-gray-500">Belum ada data jadwal</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

</div>
@endsection
