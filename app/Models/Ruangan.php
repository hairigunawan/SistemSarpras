<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;
use Masterminds\HTML5\Exception;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;

class Ruangan extends Model
{
    use HasFactory;

    protected $table = 'ruangans';
    protected $primaryKey = 'id_ruangan';

    protected $fillable = [
        'nama_ruangan',
        'kapasitas',
        'lokasi_id',
        'id_status',
        'kode_ruangan',
        'gambar',
    ];


    public function lokasi()
    {
        return $this->belongsTo(Lokasi::class, 'lokasi_id', 'id_lokasi');
    }

    public function status()
    {
        return $this->belongsTo(Status::class, 'id_status', 'id_status');
    }

    public function peminjamans()
    {
        return $this->hasMany(Peminjaman::class, 'id_ruangan', 'id_ruangan');
    }

    public function feedback()
    {
        return $this->hasMany(Feedback::class, 'id_ruangan', 'id_ruangan');
    }

    public static function TampilkanRuangan(Request $request)
    {
        $r = Ruangan::filter($request->all())
            ->latest()
            ->paginate(9);

        $s = Status::all();
        $p = Proyektor::with('status')->latest()->paginate(9);

        return view('admin.sarpras.index', compact('r', 's', 'p'));
    }

    public function scopeFilter($query, array $filters)
    {
        $query->with(['status', 'lokasi']);

        if (isset($filters['nama_status']) && $filters['nama_status']) {
            $query->whereHas('status', function ($q) use ($filters) {
                $q->where('nama_status', $filters['nama_status']);
            });
        }

        if (isset($filters['search']) && $filters['search']) {
            $query->where('nama_ruangan', 'ilike', '%' . $filters['search'] . '%');
        }

        return $query;
    }

    public static function TambahRuangan(Request $request)
    {
        $s = Status::all();
        $l = Lokasi::all();
        $defaultLokasi = Lokasi::where('nama_lokasi', 'Gedung Teknik Informatika')->first();
        $defaultStatus = Status::where('nama_status', 'Tersedia')->first();

        if (!$defaultLokasi) {
            $defaultLokasi = Lokasi::create(['nama_lokasi' => 'Gedung Teknik Informatika']);
        }
        $defaultLokasiId = $defaultLokasi->id_lokasi;

        if (!$defaultStatus) {
            $defaultStatus = Status::create(['nama_status' => 'Tersedia']);
        }
        $defaultStatusId = $defaultStatus->id_status;

        return view(
            'admin.sarpras.ruangan.tambah_ruangan',
            compact(
                's',
                'l',
                'defaultStatusId',
                'defaultLokasiId'
            )
        );
    }

    public static function LihatRuangan($id){
        $r = Ruangan::findOrFail($id);

        return view('admin.sarpras.ruangan.lihat_ruangan', compact('r'));
    }

    public static function Submit(Request $request, $imageFile = null)
    {
        try {
            $messages = [
                'nama_ruangan.required' => 'Nama ruangan wajib diisi.',
                'nama_ruangan.max'      => 'Nama ruangan maksimal 255 karakter.',
                'kapasitas.required'    => 'Kapasitas ruangan wajib diisi.',
                'kapasitas.integer'     => 'Kapasitas harus berupa angka.',
                'kapasitas.min'         => 'Kapasitas minimal 1 orang.',
                'id_status.required'    => 'Status ruangan wajib dipilih.',
                'id_status.exists'      => 'Status yang dipilih tidak valid.',
                'kode_ruangan.unique'   => 'Kode ruangan sudah digunakan.',
                'lokasi_id.required'    => 'Lokasi ruangan wajib dipilih.',
                'lokasi_id.exists'      => 'Lokasi yang dipilih tidak valid.',
                'gambar.required'       => 'Gambar ruangan wajib diunggah.',
                'gambar.image'          => 'File harus berupa gambar.',
                'gambar.mimes'          => 'Format gambar harus jpeg, png, jpg, atau webp.',
                'gambar.max'            => 'Ukuran gambar maksimal 2MB.',
            ];

            $validator = Validator::make($request->all(), [
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
                'gambar'       => 'required|image|mimes:jpeg,png,jpg,webp|max:2048',
            ], $messages);

            if ($validator->fails()) {
                return redirect()
                    ->back()
                    ->withErrors($validator)
                    ->withInput();
            }

            $validated = $validator->validated();

            Ruangan::SubmitFile($validated, $request->file('gambar'));

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

    public static function SubmitFile(array $data, $imageFile = null)
    {
        $path = null;

        if ($imageFile) {
            $path = self::uploadImage($imageFile);
        }

        $data['gambar'] = $path;

        return self::create($data);
    }

    public static function EditRuangan($id)
    {
        $r = Ruangan::findOrFail($id);
        $s = Status::all();
        $l = Lokasi::pluck('nama_lokasi', 'id_lokasi');

        // Kirim data ke view
        return view('admin.sarpras.ruangan.edit_ruangan', compact('r', 's', 'l'));
    }

    public function updateRuangan(array $data, $imageFile = null)
    {
        if ($imageFile) {
            // Hapus gambar lama jika bukan default
            $this->removeImage();
            // Upload yang baru
            $data['gambar'] = self::uploadImage($imageFile);
        }

        return $this->update($data);
    }

    public static function updateRuanganFromRequest(Request $request, $id)
    {
        try {
            // Cari ruangan, kalau tidak ada akan throw ModelNotFoundException
            $r = self::findOrFail($id);

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

    public static function HapusRuangan($id)
    {
        try {
            $ruangan = self::findOrFail($id);

            // Cek 1: Apakah sedang dipinjam?
            if ($ruangan->status && $ruangan->status->nama_status === 'Dipinjam') {
                throw new Exception('Ruangan sedang dipinjam dan tidak dapat dihapus.');
            }

            // Cek 2: Apakah ada riwayat peminjaman?
            if ($ruangan->peminjamans()->exists()) {
                throw new Exception('Ruangan memiliki riwayat peminjaman dan tidak dapat dihapus.');
            }

            // Jika lolos, hapus gambar dan record
            $ruangan->removeImage();
            $ruangan->delete();

            return redirect()
                ->route('admin.sarpras.index')
                ->with('success', 'Ruangan berhasil dihapus.');
        } catch (ModelNotFoundException $e) {
            return back()->with('error', 'Ruangan tidak ditemukan.');
        } catch (\Exception $e) {
            return back()->with('error', $e->getMessage());
        }
    }

    private static function uploadImage($file)
    {
        return $file->store('ruangan', 's3');
    }

    private function removeImage()
    {
        if ($this->gambar && Storage::disk('s3')->exists($this->gambar)) {
            Storage::disk('s3')->delete($this->gambar);
        }
    }
}
