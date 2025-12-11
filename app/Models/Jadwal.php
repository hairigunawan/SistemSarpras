<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Facades\Excel;
use App\Imports\JadwalImport;

class Jadwal extends Model
{
    use HasFactory;

    protected $primaryKey = 'id_jadwal';

    protected $fillable = [
        'kode_mk',
        'nama_kelas',
        'kelas_mahasiswa',
        'hari',
        'jam_mulai',
        'jam_selesai',
        'ruangan',
        'daya_tampung',
        'sebaran_mahasiswa'
    ];

    protected $casts = [
        'jam_mulai' => 'datetime:H:i',
        'jam_selesai' => 'datetime:H:i',
    ];

    public function JadwalUpdate(Request $request, $id)
    {
        $validatedData = $this->validateRequest($request);

        $j = Jadwal::findOrFail($id);
        $j->update($validatedData);

        return redirect()->route('admin.jadwal.index')
            ->with('success', 'Jadwal berhasil diperbarui');
    }

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

    public function getJamMulaiAttribute($value)
    {
        return $value ? Carbon::parse($value)->format('H:i') : null;
    }

    public function getJamSelesaiAttribute($value)
    {
        return $value ? Carbon::parse($value)->format('H:i') : null;
    }

    public function updateJadwal(array $data)
    {
        return $this->update($data);
    }

    public static function JadwalDelete($id){

        $j = Jadwal::findOrFail($id);

        // Panggil method di Model
        $j->delete();

        return redirect()->route('admin.jadwal.index')
            ->with('success', 'Jadwal berhasil dihapus');
    }

    public function submit(Request $request){
        $validatedData = $this->validateRequest($request);

        self::create($validatedData);

        return redirect()->route('admin.jadwal.index')
            ->with('success', 'Jadwal berhasil ditambahkan');
    }


    public static function Import(Request $request){
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
}
