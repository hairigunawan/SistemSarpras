<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use App\Helpers\PeminjamanHelper;
use App\Helpers\WhatsappMessageHelper;
use App\Services\WhatsappService;
use Illuminate\Http\Request;

class Peminjaman extends Model
{
    protected $primaryKey = 'id_peminjaman';
    protected $table = 'peminjamans';

    protected $fillable = [
        'id_akun',
        'id_ruangan',
        'id_proyektor',
        'id_lokasi',
        'id_status',
        'nama_peminjam',
        'email_peminjam',
        'jumlah_peserta',
        'tanggal_pinjam',
        'jam_mulai',
        'jam_selesai',
        'status_peminjaman',
        'jenis_kegiatan',
        'alasan_penolakan',
        'catatan_admin',
    ];

    // --- RELATIONSHIPS ---

    public function user()
    {
        return $this->belongsTo(User::class, 'id_akun', 'id_akun')->withTrashed();
    }

    public function ruangan()
    {
        return $this->belongsTo(Ruangan::class, 'id_ruangan', 'id_ruangan');
    }

    public function proyektor()
    {
        return $this->belongsTo(Proyektor::class, 'id_proyektor', 'id_proyektor');
    }

    public function lokasi()
    {
        return $this->belongsTo(Lokasi::class, 'id_lokasi', 'id_lokasi');
    }


    public function getNamaSarprasAttribute()
    {
        return $this->ruangan->nama_ruangan ?? $this->proyektor->nama_proyektor ?? 'Tidak Diketahui';
    }

    public function scopeFilter($query, $filters)
    {
        if (isset($filters['status']) && $filters['status'] !== 'all') {
            $query->where('status_peminjaman', $filters['status']);
        }

        if (isset($filters['search']) && $filters['search']) {
            $search = $filters['search'];
            $query->where(function ($q) use ($search) {
                $q->whereHas('ruangan', fn($qr) => $qr->where('nama_ruangan', 'ilike', "%{$search}%"))
                    ->orWhereHas('proyektor', fn($qp) => $qp->where('nama_proyektor', 'ilike', "%{$search}%"))
                    ->orWhereHas('user', fn($qu) => $qu->where('nama', 'ilike', "%{$search}%"))
                    ->orWhere('nama_peminjam', 'ilike', "%{$search}%");
            });
        }

        return $query;
    }

    public static function HalamanUtama(Request $request)
    {
        $peminjaman = Peminjaman::with(['user', 'ruangan', 'proyektor'])
            ->filter($request->only(['status', 'search']))
            ->latest()
            ->paginate(10);

        $role = optional(Auth::user()->userRole)->nama_role ?? '';
        $status = $request->get('status', 'all');

        return view('admin.peminjaman.index', compact('peminjaman', 'role', 'status'));
    }

    public function scopeIsConflicting($query, $data)
    {
        $isRuangan = !empty($data['id_ruangan']);

        return $query->where(function ($q) use ($data, $isRuangan) {
            if ($isRuangan) {
                $q->where('id_ruangan', $data['id_ruangan']);
            } elseif (!empty($data['id_proyektor'])) {
                $q->where('id_proyektor', $data['id_proyektor']);
            }
        })
            ->whereIn('status_peminjaman', ['Menunggu', 'Disetujui'])
            ->where(function ($q) use ($data) {
                $start = "{$data['tanggal_pinjam']} {$data['jam_mulai']}";
                $end   = "{$data['tanggal_pinjam']} {$data['jam_selesai']}";

                $q->whereRaw("CONCAT(tanggal_pinjam,' ',jam_selesai) > ?", [$start])
                    ->whereRaw("CONCAT(tanggal_pinjam,' ',jam_mulai) < ?", [$end]);
            });
    }

    public static function submit(Request $request)
    {
        $validated = $request->validate([
            'id_ruangan'       => 'nullable|exists:ruangans,id_ruangan',
            'id_proyektor'     => 'nullable|exists:proyektors,id_proyektor',
            'id_ruangan_proyektor' => 'nullable|exists:ruangans,id_ruangan',
            'id_lokasi'        => 'nullable|exists:lokasis,id_lokasi',
            'tanggal_pinjam'   => 'required|date|after_or_equal:today',
            'jam_mulai'        => 'required|date_format:H:i',
            'jam_selesai'      => 'required|date_format:H:i|after:jam_mulai',
            'jumlah_peserta'   => 'required|integer|min:1',
            'jenis_kegiatan'   => 'required|string|max:500',
        ]);

        if (empty($validated['id_ruangan']) && empty($validated['id_proyektor'])) {
            throw new \Exception('Pilih ruangan atau proyektor.');
        }
        if (!empty($validated['id_proyektor'])) {
            if (empty($validated['id_lokasi'])) {
                throw new \Exception('Lokasi harus dipilih untuk peminjaman proyektor.');
            }
            // Hanya gunakan ruangan proyektor jika ruangan utama tidak dipilih
            if (empty($validated['id_ruangan'])) {
                $validated['id_ruangan'] = $validated['id_ruangan_proyektor'];
            }
        }

        $user = Auth::user();

        $role = optional($user->userRole)->nama_role;

        if ($role !== 'Admin') {
            $hasActiveLoan = self::where('id_akun', $user->id_akun)
                ->where('status_peminjaman', 'Disetujui')
                ->exists();

            if ($hasActiveLoan) {
                throw new \Exception('Anda masih memiliki peminjaman yang belum selesai.');
            }
        }

        $createData = array_merge($validated, [
            'id_akun' => $user->id_akun ?? Auth::id(),
            'nama_peminjam' => $user->nama,
            'email_peminjam' => $user->email,
            'status_peminjaman' => 'Menunggu',
            'id_lokasi' => $validated['id_lokasi'] ?? null,
        ]);

        return self::create($createData);
    }

    public function approve()
    {
        if ($this->status_peminjaman !== 'Menunggu') {
            throw new \Exception('Hanya peminjaman berstatus Menunggu yang bisa disetujui.');
        }

        DB::transaction(function () {
            $this->update(['status_peminjaman' => 'Disetujui']);
            PeminjamanHelper::updateResourceStatus($this, 'Disetujui');
            PeminjamanHelper::autoRejectConflictingPeminjaman($this);
        });

        $msg = WhatsappMessageHelper::approved($this);
        self::sendNotification($this->user->nomor_telepon, $msg);
    }

    public function reject($alasan)
    {
        $this->update([
            'status_peminjaman' => 'Ditolak',
            'alasan_penolakan' => $alasan
        ]);

        $msg = WhatsappMessageHelper::rejected($this, $alasan);
        self::sendNotification($this->user->nomor_telepon, $msg);
    }

    public function complete()
    {
        if ($this->status_peminjaman !== 'Disetujui') {
            throw new \Exception('Hanya peminjaman berstatus Disetujui yang bisa diselesaikan.');
        }

        DB::transaction(function () {
            $this->update(['status_peminjaman' => 'Selesai']);
            PeminjamanHelper::updateResourceStatus($this, 'Selesai');
        });

        $msg = WhatsappMessageHelper::completed($this);
        self::sendNotification($this->user->nomor_telepon, $msg);
    }


    protected static function sendNotification($number, $message)
    {
        try {
            // Validasi nomor telepon
            if (!$number) {
                Log::warning("Nomor telepon kosong, skipping WhatsApp notification.");
                return;
            }

            Log::info("Mengirim notifikasi WhatsApp ke: $number");

            // Inisialisasi service
            $whatsappService = new WhatsappService();
            $response = $whatsappService->sendMessage($number, $message);

            // Log respons dari API untuk debugging
            Log::info("Pesan WhatsApp berhasil dikirim ke $number.", ['response' => $response]);
        } catch (\Exception $e) {
            Log::error("Gagal mengirim notifikasi WhatsApp ke $number: " . $e->getMessage(), [
                'exception' => get_class($e),
                'trace' => $e->getTraceAsString()
            ]);
        }
    }

    public static function Approv($type, $idSarpras)
    {
        $p = Peminjaman::with(['user', 'ruangan', 'proyektor'])
            ->where('status_peminjaman', 'Disetujui')
            ->when($type === 'ruangan', function ($query) use ($idSarpras) {
                return $query->where('id_ruangan', $idSarpras);
            })
            ->when($type === 'proyektor', function ($query) use ($idSarpras) {
                return $query->where('id_proyektor', $idSarpras);
            })
            ->get();

        $approvedDetails = [];
        foreach ($p as $peminjaman) {
            $date = $peminjaman->tanggal_pinjam;
            if (!isset($approvedDetails[$date])) {
                $approvedDetails[$date] = [];
            }

            $sarprasType = null;
            $sarprasId = null;
            if ($peminjaman->id_ruangan) {
                $sarprasType = 'ruangan';
                $sarprasId = $peminjaman->id_ruangan;
            } elseif ($peminjaman->id_proyektor) {
                $sarprasType = 'proyektor';
                $sarprasId = $peminjaman->id_proyektor;
            }

            $approvedDetails[$date][] = [
                'id_peminjaman' => $peminjaman->id_peminjaman,
                'nama_peminjam' => $peminjaman->user->nama ?? 'N/A',
                'jenis_kegiatan' => $peminjaman->jenis_kegiatan,
                'tanggal_pinjam' => $peminjaman->tanggal_pinjam,
                'tanggal_kembali' => $peminjaman->tanggal_pinjam,
                'jam_mulai' => $peminjaman->jam_mulai,
                'jam_selesai' => $peminjaman->jam_selesai,
                'jumlah_peserta' => $peminjaman->jumlah_peserta,
                'sarpras_type' => $sarprasType,
                'id_sarpras' => $sarprasId,
            ];
        }
        return response()->json(['approvedDetails' => $approvedDetails]);
    }

    public static function Riwayat()
    {
        $userId = Auth::id();

        $p = Peminjaman::with(['ruangan', 'proyektor'])
            ->where('id_akun', $userId)
            ->orderBy('created_at', 'desc')
            ->get();


        return view('public.peminjaman.riwayat_peminjaman', compact('p'));
    }
}
