@extends('layouts.app')

@section('content')
<div class="p-6">
    <div class="flex items-center justify-between mb-6">
        <h2 class="flex items-center gap-2 text-xl font-semibold text-gray-800">
            📅 Daftar Jadwal
        </h2>

        <div class="flex gap-3">
            {{-- Tombol Import --}}
            <form action="{{ route('jadwal.import.store') }}" method="POST" enctype="multipart/form-data" class="flex items-center gap-2">
                @csrf
                <input type="file" name="file" accept=".xls,.xlsx" required class="text-sm">
                <button type="submit" class="px-4 py-2 text-white transition bg-blue-500 rounded-lg shadow hover:bg-blue-600">
                    📂 Import Jadwal
                </button>
            </form>

            {{-- Tombol Tambah --}}
            <a href="{{ route('jadwal.create') }}"
               class="px-4 py-2 text-white transition bg-green-600 rounded-lg shadow hover:bg-green-700">
               + Tambah Jadwal
            </a>
        </div>
    </div>

    {{-- Notifikasi --}}
    @if(session('success'))
        <div class="p-3 mb-4 text-green-700 bg-green-100 border border-green-400 rounded">
            {{ session('success') }}
        </div>
    @endif

    {{-- Tabel Jadwal --}}
    <div class="overflow-x-auto bg-white rounded-lg shadow">
        <table class="w-full text-sm text-left text-gray-700">
            <thead class="text-xs text-gray-700 uppercase bg-gray-100">
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
                        <td class="flex justify-center gap-2 px-4 py-2">
                            <a href="{{ route('jadwal.edit', $jadwal->id_jadwal) }}"
                               class="px-3 py-1 text-white bg-yellow-400 rounded hover:bg-yellow-500">Edit</a>
                            <form action="{{ route('jadwal.destroy', $jadwal->id_jadwal) }}" method="POST" onsubmit="return confirm('Yakin hapus?')">
                                @csrf
                                @method('DELETE')
                                <button class="px-3 py-1 text-white bg-red-500 rounded hover:bg-red-600">Hapus</button>
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
