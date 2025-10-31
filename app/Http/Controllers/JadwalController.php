<?php

namespace App\Http\Controllers;

use App\Models\Jadwal;
use App\Model\Ruangan;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Facades\Excel;
use App\Imports\JadwalImport;

class JadwalController extends Controller
{
    // Menampilkan daftar jadwal
    public function index()
    {
        $jadwals = Jadwal::all();
        return view('admin.jadwal.index', compact('jadwals'));
    }

    // Form tambah jadwal
    public function create()
    {
        return view('admin.jadwal.create');
    }

    // Simpan data baru
    public function store(Request $request)
    {
        $request->validate([
            'kode_mk' => 'required',
            'nama_kelas' => 'required',
            'kelas_mahasiswa' => 'required',
            'sebaran_mahasiswa' => 'required|integer',
            'hari' => 'required',
            'jam_mulai' => 'required',
            'jam_selesai' => 'required',
            'ruangan' => 'required',
            'daya_tampung' => 'required|integer',
        ]);

        Jadwal::create($request->all());

        return redirect()->route('admin.jadwal.index')->with('success', 'Jadwal berhasil ditambahkan');
    }

    // Form edit jadwal
    public function edit($id)
    {
        $jadwal = Jadwal::findOrFail($id);
        return view('admin.jadwal.edit', compact('jadwal'));
    }

    // Update data jadwal
    public function update(Request $request, Jadwal $jadwal)
    {
        $request->validate([
            'kode_mk' => 'required',
            'nama_kelas' => 'required',
            'kelas_mahasiswa' => 'required',
            'sebaran_mahasiswa' => 'required|integer',
            'hari' => 'required',
            'jam_mulai' => 'required',
            'jam_selesai' => 'required',
            'ruangan' => 'required',
            'daya_tampung' => 'required|integer',
        ]);

        $jadwal->update($request->all());

        return redirect()->route('jadwal.index')->with('success', 'Jadwal berhasil diperbarui');
    }


    // Hapus data
    public function destroy($id)
    {
        $jadwal = Jadwal::findOrFail($id);
        $jadwal->delete();

        return redirect()->route('jadwal.index')->with('success', 'Jadwal berhasil dihapus');
    }
}
