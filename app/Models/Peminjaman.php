<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use App\Helpers\PeminjamanHelper;
use App\Services\FonnteService;

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
    ];

    // --- RELATIONSHIPS ---

    public function user()
    {
        return $this->belongsTo(User::class, 'id_akun', 'id_akun');
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

    // --- SCOPES (Untuk Read/Filter) ---

    /**
     * Scope untuk filter pencarian dan status (Memindahkan logika query dari Controller)
     */
    public function scopeFilter($query, $filters)
    {
        // Filter Status
        if (isset($filters['status']) && $filters['status'] !== 'all') {
            $query->where('status_peminjaman', $filters['status']);
        }

        // Filter Pencarian
        if (isset($filters['search']) && $filters['search']) {
            $search = $filters['search'];
            $query->where(function ($q) use ($search) {
                $q->whereHas('ruangan', fn($qr) => $qr->where('nama_ruangan', 'like', "%{$search}%"))
                    ->orWhereHas('proyektor', fn($qp) => $qp->where('nama_proyektor', 'like', "%{$search}%"))
                    ->orWhereHas('user', fn($qu) => $qu->where('nama', 'like', "%{$search}%"))
                    ->orWhere('nama_peminjam', 'like', "%{$search}%");
            });
        }

        return $query;
    }

    /**
     * Scope untuk mengecek bentrok jadwal
     */
    public function scopeIsConflicting($query, $data)
    {
        $isRuangan = !empty($data['id_ruangan']);

        return $query->where(function ($q) use ($data, $isRuangan) {
            if ($isRuangan) {
                $q->where('id_ruangan', $data['id_ruangan']);
            } else {
                $q->where('id_proyektor', $data['id_proyektor']);
            }
        })
            ->whereIn('status_peminjaman', ['Menunggu', 'Disetujui'])
            ->where(function ($q) use ($data) {
                $start = "{$data['tanggal_pinjam']} {$data['jam_mulai']}";
                $end   = "{$data['tanggal_pinjam']} {$data['jam_selesai']}";

                // Logika overlap waktu SQL untuk 1 hari peminjaman
                $q->whereRaw("CONCAT(tanggal_pinjam,' ',jam_selesai) > ?", [$start])
                    ->whereRaw("CONCAT(tanggal_pinjam,' ',jam_mulai) < ?", [$end]);
            });
    }

    /**
     * Create Logic: Validasi bisnis dan pembuatan data
     */
    public static function submit(array $data)
    {
        $user = Auth::user();

        // CEK VALIDASI ROLE & STATUS AKTIF
        // Jika bukan Admin, cek apakah ada peminjaman yang sedang berjalan (Disetujui)
        $role = optional($user->userRole)->nama_role;

        if ($role !== 'Admin') {
            $hasActiveLoan = self::where('id_akun', $user->id_akun)
                ->where('status_peminjaman', 'Disetujui') // Hanya cek yang sudah disetujui tapi belum selesai
                ->exists();

            if ($hasActiveLoan) {
                throw new \Exception('Anda masih memiliki peminjaman yang belum selesai.');
            }
        }

        // 1. Normalisasi Data
        $data['id_akun'] = $user->id_akun ?? Auth::id();
        $data['nama_peminjam'] = $user->nama;
        $data['email_peminjam'] = $user->email;
        $data['status_peminjaman'] = 'Menunggu';

        // 2. Cek Bentrok (Menggunakan Scope di atas)
        $isBentrok = self::isConflicting($data)->exists();

        if ($isBentrok) {
            self::sendNotification($user->nomor_telepon, "Peminjaman Gagal\nJadwal bentrok dengan peminjaman lain.");
            throw new \Exception('Jadwal bentrok dengan peminjaman lain.');
        }

        // 3. Simpan
        return self::create($data);
    }

    /**
     * Approve Logic
     */
    public function approve()
    {
        if ($this->status_peminjaman !== 'Menunggu') {
            throw new \Exception('Hanya peminjaman berstatus Menunggu yang bisa disetujui.');
        }

        DB::transaction(function () {
            // 1. Update Status Diri Sendiri
            $this->update(['status_peminjaman' => 'Disetujui']);

            // 2. Update Status Fisik Sarpras (Via Helper)
            PeminjamanHelper::updateResourceStatus($this, 'Disetujui');

            // 3. Auto Reject yang lain (Side Effect)
            PeminjamanHelper::autoRejectConflictingPeminjaman($this);
        });

        // 4. Kirim Notifikasi
        $msg = "Pengajuan Disetujui\n" .
            "Sarpras: {$this->nama_sarpras}\n" .
            "Tanggal: {$this->tanggal_pinjam}\n" .
            "Waktu: {$this->jam_mulai} - {$this->jam_selesai}";

        self::sendNotification($this->user->nomor_telepon, $msg);
    }

    /**
     * Reject Logic
     */
    public function reject($alasan)
    {
        if ($this->status_peminjaman !== 'Menunggu') {
            throw new \Exception('Hanya peminjaman berstatus Menunggu yang bisa ditolak.');
        }

        $this->update([
            'status_peminjaman' => 'Ditolak',
            'alasan_penolakan' => $alasan
        ]);

        $msg = "Pengajuan Ditolak\nAlasan: {$alasan}";
        self::sendNotification($this->user->nomor_telepon, $msg);
    }

    /**
     * Complete Logic
     */
    public function complete()
    {
        if ($this->status_peminjaman !== 'Disetujui') {
            throw new \Exception('Hanya peminjaman berstatus Disetujui yang bisa diselesaikan.');
        }

        DB::transaction(function () {
            $this->update(['status_peminjaman' => 'Selesai']);
            PeminjamanHelper::updateResourceStatus($this, 'Selesai');
        });

        $msg = "Peminjaman Selesai\nTerima kasih telah menggunakan fasilitas.";
        self::sendNotification($this->user->nomor_telepon, $msg);
    }

    // --- HELPER INTERNAL ---

    protected static function sendNotification($number, $message)
    {
        try {
            FonnteService::sendMessage($number, $message);
        } catch (\Exception $e) {
            Log::error("Gagal kirim WA ke $number: " . $e->getMessage());
            // Jangan throw exception agar proses utama tidak gagal hanya karena WA error
        }
    }
}
