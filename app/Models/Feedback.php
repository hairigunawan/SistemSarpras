<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Ruangan;
use App\Models\Proyektor;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class Feedback extends Model
{
    use HasFactory;

    protected $table = 'feedback';
    protected $primaryKey = 'id_feedback';

    protected $fillable = [
        'id_ruangan',
        'id_proyektor',
        'id_peminjaman',
        'isi_feedback',
        'id_akun',
    ];

    public function ruangan()
    {
        return $this->belongsTo(Ruangan::class, 'id_ruangan', 'id_ruangan');
    }

    public function proyektor()
    {
        return $this->belongsTo(Proyektor::class, 'id_proyektor', 'id_proyektor');
    }

    public function peminjaman()
    {
        return $this->belongsTo(Peminjaman::class, 'id_peminjaman', 'id_peminjaman');
    }

    public function user()
    {
        return $this->belongsTo(User::class, 'id_akun', 'id_akun');
    }

    public static function HalamanUtama(Request $request)
    {
        $id_sarpras = $request->input('id_sarpras');
        $sarpras_type = $request->input('type'); // 'ruangan' atau 'proyektor'

        if (!$id_sarpras || !$sarpras_type) {
            abort(400, 'Parameter id_sarpras dan type diperlukan');
        }

        // Validasi apakah pengguna memiliki akses ke feedback ini
        $peminjaman = Peminjaman::where('id_akun', Auth::id())
            ->where(function ($query) use ($id_sarpras, $sarpras_type) {
                if ($sarpras_type === 'ruangan') {
                    $query->where('id_ruangan', $id_sarpras);
                } else {
                    $query->where('id_proyektor', $id_sarpras);
                }
            })
            ->whereIn('status_peminjaman', ['Disetujui', 'Selesai'])
            ->first();

        if (!$peminjaman) {
            $anyPeminjaman = Peminjaman::where('id_akun', Auth::id())
                ->where(function ($query) use ($id_sarpras, $sarpras_type) {
                    if ($sarpras_type === 'ruangan') {
                        $query->where('id_ruangan', $id_sarpras);
                    } else {
                        $query->where('id_proyektor', $id_sarpras);
                    }
                })
                ->first();

            if (!$anyPeminjaman) {
                return redirect()->back()
                    ->with('error', 'Lakukan peminjaman terlebih dahulu pada sarpras ini');
            } else {
                return redirect()->back()
                    ->with('error', 'Anda belum dapat memberikan feedback untuk sumber daya ini. Peminjaman Anda harus disetujui/selesai terlebih dahulu.');
            }
        }

        // Ambil data sumber daya
        if ($sarpras_type === 'ruangan') {
            $ruangan = Ruangan::findOrFail($id_sarpras);
            $proyektor = null;
            $sarpras = $ruangan;
        } else {
            $proyektor = Proyektor::findOrFail($id_sarpras);
            $ruangan = null;
            $sarpras = $proyektor;
        }

        // Ambil semua feedback untuk sarpras ini (tidak dibatasi per peminjaman)
        $feedbacks = Feedback::where(function ($query) use ($id_sarpras, $sarpras_type) {
            if ($sarpras_type === 'ruangan') {
                $query->where('id_ruangan', $id_sarpras);
            } else {
                $query->where('id_proyektor', $id_sarpras);
            }
        })->orderBy('created_at', 'desc')->paginate(10);

        // Tidak ada pembatasan feedback per peminjaman
        $existingFeedback = null;

        return view('public.feedback.index', compact(
            'ruangan',
            'proyektor',
            'feedbacks',
            'id_sarpras',
            'sarpras_type',
            'peminjaman',
            'existingFeedback'
        ));
    }

    public static function submit(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'id_sarpras' => 'required|integer',
            'type' => 'required|in:ruangan,proyektor',
            'id_peminjaman' => 'required|integer',
            'isi_feedback' => 'required|string|min:10|max:1000',
        ]);

        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }

        // Validasi apakah pengguna memiliki akses ke feedback ini
        $peminjaman = Peminjaman::where('id_akun', Auth::id())
            ->where('id_peminjaman', $request->id_peminjaman)
            ->whereIn('status_peminjaman', ['Disetujui', 'Selesai'])
            ->first();

        if (!$peminjaman) {
            abort(403, 'Anda tidak memiliki akses untuk memberikan feedback pada peminjaman ini. Peminjaman harus disetujui/selesai terlebih dahulu.');
        }

        // User dapat memberikan feedback berkali-kali untuk peminjaman yang berbeda

        // Simpan feedback
        $feedback = new Feedback();
        $feedback->id_peminjaman = $request->id_peminjaman;
        $feedback->isi_feedback = $request->isi_feedback;
        $feedback->id_akun = Auth::id();

        if ($request->type === 'ruangan') {
            $feedback->id_ruangan = $request->id_sarpras;
            // Set id_proyektor ke NULL untuk ruangan
            $feedback->id_proyektor = null;
        } else {
            $feedback->id_proyektor = $request->id_sarpras;
            // Set id_ruangan ke NULL untuk proyektor
            $feedback->id_ruangan = null;
        }

        $feedback->save();

        return redirect()->route('public.sarana_perasarana.detail_sarpras', [
            'type' => $request->type,
            'id' => $request->id_sarpras
        ])
            ->with('success', 'Feedback berhasil dikirim');
    }

    public static function deleteFeedback(Feedback $feedback)
    {
        // Validasi apakah pengguna adalah pemilik feedback
        if (Auth::id() !== $feedback->peminjaman->id_akun) {
            abort(403, 'Anda tidak memiliki akses untuk menghapus feedback ini');
        }

        $feedback->delete();

        return redirect()->back()
            ->with('success', 'Feedback berhasil dihapus');
    }
}
