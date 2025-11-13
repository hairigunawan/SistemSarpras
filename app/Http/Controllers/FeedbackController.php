<?php

namespace App\Http\Controllers;

use App\Models\Feedback;
use App\Models\Peminjaman;
use App\Models\Ruangan;
use App\Models\Proyektor;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class FeedbackController extends Controller
{
    /**
     * Menampilkan halaman feedback untuk ruangan atau proyektor
     */
    public function index(Request $request)
    {
        $id_sarpras = $request->input('id_sarpras');
        $sarpras_type = $request->input('type'); // 'ruangan' atau 'proyektor'

        if (!$id_sarpras || !$sarpras_type) {
            abort(400, 'Parameter id_sarpras dan type diperlukan');
        }

        // Validasi apakah pengguna memiliki akses ke feedback ini
        $peminjaman = Peminjaman::where('id_akun', Auth::id())
            ->where(function($query) use ($id_sarpras, $sarpras_type) {
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
                ->where(function($query) use ($id_sarpras, $sarpras_type) {
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

        // Ambil feedback yang sudah ada
        $feedbacks = Feedback::where(function($query) use ($id_sarpras, $sarpras_type) {
            if ($sarpras_type === 'ruangan') {
                $query->where('id_ruangan', $id_sarpras);
            } else {
                $query->where('id_proyektor', $id_sarpras);
            }
        })->where('id_peminjaman', $peminjaman->id_peminjaman ?? 0)->get();

        // Cek apakah sudah ada feedback untuk peminjaman ini
        $existingFeedback = Feedback::where('id_peminjaman', $peminjaman->id_peminjaman ?? 0)->first();

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

    /**
     * Menyimpan feedback baru
     */
    public function store(Request $request)
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

        // Cek apakah feedback sudah ada untuk peminjaman ini
        $existingFeedback = Feedback::where('id_peminjaman', $request->id_peminjaman)->first();
        if ($existingFeedback) {
            return redirect()->back()
                ->with('error', 'Anda sudah memberikan feedback untuk peminjaman ini');
        }

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

        return redirect()->route('public.feedback.index', [
                'id_sarpras' => $request->id_sarpras,
                'type' => $request->type
            ])
            ->with('success', 'Feedback berhasil dikirim');
    }

    /**
     * Menghapus feedback
     */
    public function destroy(Feedback $feedback)
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
