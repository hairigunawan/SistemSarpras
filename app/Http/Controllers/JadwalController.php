<?php

namespace App\Http\Controllers;

use App\Models\Jadwal;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Facades\Excel;
use App\Imports\JadwalImport;

class JadwalController extends Controller
{
    public function index()
    {
        $jadwals = Jadwal::all();
        return view('admin.jadwal.index', compact('jadwals'));
    }

    public function create()
    {
        return view('admin.jadwal.create');
    }

    public function store(Request $request)
    {
        // Validasi tetap di controller (atau dipisah ke FormRequest agar lebih OOP)
        $validatedData = $this->validateRequest($request);

        // Panggil method di Model
        Jadwal::storeJadwal($validatedData);

        return redirect()->route('admin.jadwal.index')
            ->with('success', 'Jadwal berhasil ditambahkan');
    }

    public function edit($id)
    {
        $jadwal = Jadwal::findOrFail($id);
        return view('admin.jadwal.edit', compact('jadwal'));
    }

    public function update(Request $request, $id)
    {
        $jadwal = Jadwal::findOrFail($id);

        $validatedData = $this->validateRequest($request);

        // Panggil method di Model (Instance method)
        $jadwal->updateJadwal($validatedData);

        return redirect()->route('admin.jadwal.index')
            ->with('success', 'Jadwal berhasil diperbarui');
    }

    public function destroy($id)
    {
        $jadwal = Jadwal::findOrFail($id);

        // Panggil method di Model
        $jadwal->deleteJadwal();

        return redirect()->route('admin.jadwal.index')
            ->with('success', 'Jadwal berhasil dihapus');
    }

    public function importStore(Request $request)
    {
        $request->validate([
            'file' => 'required|mimes:xls,xlsx|max:2048'
        ]);

        try {
            Excel::import(new JadwalImport, $request->file('file'));
            return redirect()->route('admin.jadwal.index')
                ->with('success', 'Data jadwal berhasil di-import!');
        } catch (\Exception $e) {
            return redirect()->back()
                ->with('error', 'Gagal import data: ' . $e->getMessage());
        }
    }

    /**
     * Private method untuk enkapsulasi validasi agar tidak berulang (DRY Principle)
     */
    private function validateRequest(Request $request)
    {
        return $request->validate([
            'kode_mk'           => 'required',
            'nama_kelas'        => 'required',
            'kelas_mahasiswa'   => 'required',
            'sebaran_mahasiswa' => 'required|integer',
            'hari'              => 'required',
            'jam_mulai'         => 'required|date_format:H:i',
            'jam_selesai'       => 'required|date_format:H:i',
            'ruangan'           => 'required',
            'daya_tampung'      => 'required|integer',
        ]);
    }
}
