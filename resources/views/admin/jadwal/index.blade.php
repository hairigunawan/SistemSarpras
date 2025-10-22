@extends('layouts.app')

@section('content')
<div class="p-6">
    <div class="flex justify-between items-center mb-6">
        <h2 class="text-xl font-semibold text-gray-800 flex items-center gap-2">
            📅 Daftar Jadwal
        </h2>

        <div class="flex gap-3">
            {{-- Tombol Import --}}
            <form action="{{ route('jadwal.import.store') }}" method="POST" enctype="multipart/form-data" class="flex items-center gap-2">
                @csrf
                <input type="file" name="file" accept=".xls,.xlsx" required class="text-sm">
                <button type="submit" class="px-4 py-2 bg-blue-500 text-white rounded-lg shadow hover:bg-blue-600 transition">
                    📂 Import Jadwal
                </button>
            </form>

            {{-- Tombol Tambah --}}
            <a href="{{ route('admin.jadwal.create') }}" 
               class="px-4 py-2 bg-green-600 text-white rounded-lg shadow hover:bg-green-700 transition">
               + Tambah Jadwal
            </a>
        </div>
    </div>

    {{-- Notifikasi --}}
    @if(session('success'))
        <div class="mb-4 p-3 bg-green-100 border border-green-400 text-green-700 rounded">
            {{ session('success') }}
        </div>
    @endif

    {{-- Tabel Jadwal --}}
    <div class="overflow-x-auto bg-white rounded-lg shadow">
        <table class="w-full text-sm text-left text-gray-700">
            <thead class="bg-gray-100 text-gray-700 uppercase text-xs">
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
                    <tr class="border-b hover:bg-gray-50">
                        <td class="px-4 py-2">{{ $jadwal->kode_mk }}</td>
                        <td class="px-4 py-2">{{ $jadwal->nama_kelas }}</td>
                        <td class="px-4 py-2">{{ $jadwal->kelas_mahasiswa }}</td>
                        <td class="px-4 py-2">{{ $jadwal->hari }}</td>
                        <td class="px-4 py-2">{{ $jadwal->jam_mulai }} - {{ $jadwal->jam_selesai }}</td>
                        <td class="px-4 py-2">{{ $jadwal->ruangan }}</td>
                        <td class="px-4 py-2">{{ $jadwal->daya_tampung }}</td>
                        <td class="px-4 py-2 flex justify-center gap-2">
                            <a href="{{ route('jadwal.edit', $jadwal->id_jadwal) }}" 
                               class="px-3 py-1 bg-yellow-400 text-white rounded hover:bg-yellow-500">Edit</a>
                            <form action="{{ route('jadwal.destroy', $jadwal->id_jadwal) }}" method="POST" onsubmit="return confirm('Yakin hapus?')">
                                @csrf
                                @method('DELETE')
                                <button class="px-3 py-1 bg-red-500 text-white rounded hover:bg-red-600">Hapus</button>
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
