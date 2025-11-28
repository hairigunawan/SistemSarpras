<?php

namespace App\Http\Controllers;

use App\Models\Ruangan;
use App\Models\Status;
use App\Models\Lokasi;
use App\Models\Proyektor;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\Log;
use Masterminds\HTML5\Exception;
use Illuminate\Database\Eloquent\ModelNotFoundException;

class RuanganController extends Controller
{
    public function index(Request $request)
    {
        // Menggunakan dari Model untuk query yang lebih bersih
        $r = Ruangan::filter($request->all())
            ->latest()
            ->paginate(9);

        $s = Status::all();
        $p = Proyektor::with('status')->latest()->paginate(9);

        return view('admin.sarpras.index', compact('r', 's', 'p'));
    }

    public function tambah_ruangan()
    {
        // Ambil daftar lokasi untuk dropdown (id → nama lokasi)
        $l = Lokasi::pluck('nama_lokasi', 'id_lokasi')->sort();

        // Ambil semua status
        $s = Status::all();

        // Cari atau buat status default "Tersedia"
        $defaultStatus = Status::firstOrCreate(['nama_status' => 'Tersedia']);

        return view('admin.sarpras.ruangan.tambah_ruangan', [
            'l' => $l,
            's' => $s,
            'defaultStatusId' => $defaultStatus->id_status,
        ]);
    }



    public function store_ruangan(Request $request)
    {
        // Validasi input
        $validated = $request->validate([
            'nama_ruangan' => 'required|string|max:255',
            'kapasitas'    => 'required|integer|min:1',
            'id_status'    => 'required|exists:statuses,id_status',
            'kode_ruangan' => [
                'nullable',
                'string',
                'max:50',
                Rule::unique('ruangans', 'kode_ruangan'),
            ],
            'lokasi_id'    => 'required|exists:lokasis,id_lokasi',
            'gambar'       => 'nullable|image|mimes:jpeg,png,jpg,webp|max:2048',
        ]);

        try {
            // Panggil Model yang menangani logika penyimpanan
            Ruangan::Submit($validated, $request->file('gambar'));

            return redirect()
                ->route('admin.sarpras.index')
                ->with('success', 'Ruangan berhasil ditambahkan.');
        } catch (\Exception $e) {

            // Tangkap error dari proses model
            Log::error('Gagal menambah ruangan: ' . $e->getMessage());

            return redirect()
                ->back()
                ->with('error', 'Gagal menambahkan ruangan: ' . $e->getMessage())
                ->withInput();
        }
    }

    public function store(Request $request)
    {
        return $this->store_ruangan($request);
    }

    public function lihat_ruangan($id)
    {
        $r = Ruangan::findOrFail($id);

        return view('admin.sarpras.ruangan.lihat_ruangan', compact('r'));
    }

    public function edit_ruangan($id)
    {
        // Ambil satu data ruangan berdasarkan ID
        $r = Ruangan::findOrFail($id);

        // Ambil seluruh data status
        $s = Status::all();

        // Ambil daftar lokasi
        $l = Lokasi::pluck('nama_lokasi', 'id_lokasi');

        // Kirim data ke view
        return view('admin.sarpras.ruangan.edit_ruangan', compact('r', 's', 'l'));
    }


    public function update_ruangan(Request $request, $id)
    {
        try {
            // Cari ruangan, kalau tidak ada akan throw ModelNotFoundException
            $r = Ruangan::findOrFail($id);

            // Validasi input
            $validated = $request->validate([
                'nama_ruangan'   => 'required|string|max:255',
                'kapasitas'      => 'required|integer|min:1',
                'id_status'      => 'required|exists:statuses,id_status',
                'kode_ruangan'   => [
                    'nullable',
                    'string',
                    'max:50',
                    Rule::unique('ruangans', 'kode_ruangan')
                        ->ignore($r->id_ruangan, 'id_ruangan'),
                ],
                'lokasi_id'      => 'required|exists:lokasis,id_lokasi',
                'gambar'         => 'nullable|image|mimes:jpeg,png,jpg,webp|max:2048',
            ]);

            // Jalankan fungsi update dari model
            $r->updateRuangan($validated, $request->file('gambar'));

            return redirect()
                ->route('sarpras.ruangan.lihat_ruangan', $r->id_ruangan)
                ->with('success', 'Ruangan berhasil diperbarui.');
        } catch (ModelNotFoundException $e) {

            // Jika ID ruangan tidak ditemukan
            return redirect()
                ->back()
                ->with('error', 'Data ruangan tidak ditemukan.');
        } catch (\Exception $e) {

            // Error umum atau error custom dari Model
            Log::error('Gagal memperbarui Ruangan: ' . $e->getMessage());

            return redirect()
                ->back()
                ->with('error', 'Gagal memperbarui Ruangan: ' . $e->getMessage())
                ->withInput();
        }
    }

    public function destroy($id)
    {
        try {
            // Cari data ruangan, jika tidak ada otomatis throw ModelNotFoundException
            $ruangan = Ruangan::findOrFail($id);

            // Jalankan logika penghapusan dari Model
            $ruangan->hapusRuangan();

            return redirect()
                ->route('admin.sarpras.index')
                ->with('success', 'Ruangan berhasil dihapus.');
        } catch (ModelNotFoundException $e) {
            // Jika ID tidak ditemukan
            return back()->with('error', 'Ruangan tidak ditemukan.');
        } catch (\Exception $e) {
            // Menangkap error lainnya, termasuk error custom dari model
            return back()->with('error', $e->getMessage());
        }
    }
}
