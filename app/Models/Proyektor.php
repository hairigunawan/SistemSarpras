<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;
use Masterminds\HTML5\Exception;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class Proyektor extends Model
{
    use HasFactory;

    protected $table = 'proyektors';
    protected $primaryKey = 'id_proyektor';

    protected $fillable = [
        'nama_proyektor',
        'merk',
        'kode_proyektor',
        'gambar',
        'id_status'
    ];

    public function status()
    {
        return $this->belongsTo(Status::class, 'id_status', 'id_status');
    }

    public function peminjamans()
    {
        return $this->hasMany(Peminjaman::class, 'id_proyektor', 'id_proyektor');
    }

    public function feedback()
    {
        return $this->hasMany(Feedback::class, 'id_proyektor', 'id_proyektor');
    }

    public static function TampilkanProyektor(Request $request)
    {
        $s = Status::all();
        $defaultStatus = Status::where('nama_status', 'Tersedia')->value('id_status');

        return view('admin.sarpras.proyektor.tambah_proyektor', compact('s', 'defaultStatus'));
    }

    public static function Submit(Request $request)
    {
        $messages = [
            'nama_proyektor.required' => 'Nama proyektor wajib diisi.',
            'nama_proyektor.max'      => 'Nama proyektor maksimal 255 karakter.',
            'merk.required'           => 'Merk proyektor wajib diisi.',
            'merk.max'                => 'Merk proyektor maksimal 255 karakter.',
            'kode_proyektor.unique'   => 'Kode proyektor sudah digunakan.',
            'id_status.required'      => 'Status proyektor wajib dipilih.',
            'id_status.exists'        => 'Status yang dipilih tidak valid.',
            'gambar.required'         => 'Gambar proyektor wajib diunggah.',
            'gambar.image'            => 'File harus berupa gambar.',
            'gambar.mimes'            => 'Format gambar harus jpg, jpeg, png, atau webp.',
            'gambar.max'              => 'Ukuran gambar maksimal 2MB.',
        ];

        $validated = $request->validate([
            'nama_proyektor' => 'required|string|max:255',
            'merk' => 'required|string|max:255',
            'kode_proyektor' => 'nullable|string|max:255|unique:proyektors,kode_proyektor',
            'id_status' => 'required|exists:statuses,id_status',
            'gambar' => 'required|image|mimes:jpg,jpeg,png,webp|max:2048',
        ], $messages);

        try {
            Proyektor::SubmitFile($validated, $request->file('gambar'));

            return redirect()->route('admin.sarpras.index')
                ->with('success', 'Proyektor berhasil ditambahkan!');
        } catch (Exception $e) {
            Log::error('Gagal menambah Proyektor: ' . $e->getMessage());
            return back()->with('error', 'Gagal menambah data: ' . $e->getMessage())->withInput();
        }
    }

    public function scopeFilter($query, array $filters)
    {
        $query->with(['status']);

        if (isset($filters['nama_status']) && $filters['nama_status']) {
            $query->whereHas('status', function ($q) use ($filters) {
                $q->where('nama_status', $filters['nama_status']);
            });
        }

        if (isset($filters['search']) && $filters['search']) {
            $query->where('nama_proyektor', 'like', '%' . $filters['search'] . '%');
        }

        return $query;
    }

    public static function LihatProyektor($id)
    {
        $p = Proyektor::findOrFail($id);
        $s = Status::all();

        return view('admin.sarpras.proyektor.lihat_proyektor', compact('p', 's'));
    }

    public static function EditProyektor($id)
    {
        $data = Proyektor::edit(request(), $id);
        return view('admin.sarpras.proyektor.edit_proyektor', $data);
    }

    public static function proyektorUpdate(Request $request, $id)
    {

        $p = Proyektor::findOrFail($id);

        $validated = $request->validate([
            'nama_proyektor' => 'required|string|max:255',
            'merk' => 'required|string|max:255',
            'kode_proyektor' => 'nullable|string|max:255|unique:proyektors,kode_proyektor,' . $p->id_proyektor . ',id_proyektor',
            'id_status' => 'required|exists:statuses,id_status',
            'gambar' => 'nullable|image|mimes:jpg,jpeg,png,webp|max:2048',
        ]);

        try {
            // Panggil method di Model
            $p->updateProyektor($validated, $request->file('gambar'));

            return redirect()->route('admin.sarpras.index')
                ->with('success', 'Data proyektor berhasil diperbarui!');
        } catch (Exception $e) {
            Log::error('Gagal update Proyektor: ' . $e->getMessage());
            return back()->with('error', 'Gagal memperbarui data: ' . $e->getMessage())->withInput();
        }
    }

    public static function SubmitFile(array $data, $imageFile = null)
    {
        // Path default jika tidak ada gambar (opsional, sesuaikan dengan kebutuhan)
        $path = null;

        if ($imageFile) {
            $path = self::uploadImage($imageFile);
        }

        $data['gambar'] = $path;

        return self::create($data);
    }

    public function updateProyektor(array $data, $imageFile = null)
    {
        if ($imageFile) {
            // Hapus gambar lama jika ada
            $this->removeImage();
            // Upload yang baru
            $data['gambar'] = self::uploadImage($imageFile);
        }

        return $this->update($data);
    }

    public static function hapusProyektor($id)
    {
        try {
            $proyektor = self::findOrFail($id);

            // Cek 1: Apakah sedang dipinjam?
            if ($proyektor->status && $proyektor->status->nama_status === 'Dipinjam') {
                throw new Exception('Ruangan sedang dipinjam dan tidak dapat dihapus.');
            }

            // Cek 2: Apakah ada riwayat peminjaman?
            if ($proyektor->peminjamans()->exists()) {
                throw new Exception('Ruangan memiliki riwayat peminjaman dan tidak dapat dihapus.');
            }

            // Jika lolos, hapus gambar dan record
            $proyektor->removeImage();
            $proyektor->delete();

            return redirect()
                ->route('admin.sarpras.index')
                ->with('success', 'Proyektor berhasil dihapus.');
        } catch (ModelNotFoundException $e) {
            return back()->with('error', 'Proyektor tidak ditemukan.');
        } catch (\Exception $e) {
            return back()->with('error', $e->getMessage());
        }
    }

    public static function edit(Request $request, $id){
        $p = Proyektor::findOrFail($id);
        $s = Status::all();

        return compact('p', 's');
    }


    private static function uploadImage($file)
    {
        $supabase = new \App\Services\SupabaseService();
        $fileName = uniqid() . '.' . $file->getClientOriginalExtension();
        return $supabase->upload($file, 'proyektor/' . $fileName);
    }

    private function removeImage()
    {
        if ($this->gambar) {
            $supabase = new \App\Services\SupabaseService();
            $supabase->delete($this->gambar);
        }
    }
}
