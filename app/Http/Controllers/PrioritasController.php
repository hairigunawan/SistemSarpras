<?php

namespace App\Http\Controllers;

use App\Models\Peminjaman;

class PrioritasController extends Controller
{
    // === 1️⃣ Prioritas Peminjaman Ruangan ===
    public function ruangan()
    {
        $peminjaman = Peminjaman::with('ruangan')->whereNotNull('id_ruangan')->get();

        // Bobot dan tipe kriteria
        $kriteria = [
            'jenis_kegiatan' => ['bobot' => 0.075, 'tipe' => 'benefit'],
            'jumlah_peserta' => ['bobot' => 0.393, 'tipe' => 'cost'],
            'durasi'          => ['bobot' => 0.369, 'tipe' => 'cost'],   // contoh: durasi lebih lama dianggap kurang efisien
            'pengajuan'       => ['bobot' => 0.163, 'tipe' => 'cost'],
        ];

        [$hasil, $alternatif] = $this->hitungPrioritas($peminjaman, $kriteria);
        return view('admin.prioritas.ruangan', compact('peminjaman', 'hasil', 'kriteria', 'alternatif'));
    }

    // === 2️⃣ Prioritas Peminjaman Proyektor ===
    public function proyektor()
    {
        $peminjaman = Peminjaman::with('proyektor')->whereNotNull('id_proyektor')->get();

        $kriteria = [
            'jenis_kegiatan' => ['bobot' => 0.075, 'tipe' => 'benefit'],
            'jumlah_peserta' => ['bobot' => 0.393, 'tipe' => 'cost'],
            'durasi'          => ['bobot' => 0.369, 'tipe' => 'cost'],
            'pengajuan'       => ['bobot' => 0.163, 'tipe' => 'cost'],
        ];

        [$hasil, $alternatif] = $this->hitungPrioritas($peminjaman, $kriteria);
        return view('admin.prioritas.proyektor', compact('peminjaman', 'hasil', 'kriteria', 'alternatif'));
    }

    // === 🔢 FUNGSI UTAMA UNTUK MENGHITUNG PRIORITAS (AHP + SAW) ===
    private function hitungPrioritas($data, $kriteria)
    {
        $alternatif = [];

        foreach ($data as $p) {
            // Hitung durasi jam
            $durasiJam = 0;
            if (!empty($p->jam_mulai) && !empty($p->jam_selesai)) {
                $mulai = strtotime($p->jam_mulai);
                $selesai = strtotime($p->jam_selesai);
                $durasiJam = max(1, ($selesai - $mulai) / 3600);
            }

            // Hitung selisih hari antara pengajuan dan tanggal pinjam
            $tanggalPengajuan = strtotime($p->created_at ?? now());
            $tanggalPinjam = strtotime($p->tanggal_pinjam ?? now());
            $pengajuanHari = max(1, ($tanggalPinjam - $tanggalPengajuan) / (3600 * 24));

            // Skala jenis kegiatan (1–5)
            $jenisKegiatanMap = [
                'Praktikum' => 3,
                'Seminar PKL' => 5,
                'Seminar Tugas Akhir' => 5,
                'Bimbingan' => 4,
                'Kelas Materi' => 2,
                'Lainnya' => 1,
            ];

            // Skor setiap alternatif
            $alt = [
                'nama' => $p->nama_peminjam ?? 'Tidak Diketahui',
                'jenis_kegiatan' => $jenisKegiatanMap[$p->jenis_kegiatan] ?? 1,
                'jumlah_peserta' => isset($p->jumlah_peserta)
                    ? min(5, ceil($p->jumlah_peserta / 10))
                    : 3,
                'durasi' => $durasiJam <= 2 ? 5 : ($durasiJam <= 4 ? 3 : 1),
                'pengajuan' => $pengajuanHari <= 2 ? 5 : ($pengajuanHari <= 4 ? 4 : ($pengajuanHari <= 6 ? 3 : ($pengajuanHari <= 8 ? 2 : 1))),
            ];

            $alternatif[] = $alt;
        }

        if (empty($alternatif)) {
            return [[], []];
        }

        // --- Normalisasi SAW ---
        $maxValues = [];
        $minValues = [];

        foreach ($kriteria as $key => $val) {
            $maxValues[$key] = max(array_column($alternatif, $key));
            $minValues[$key] = min(array_column($alternatif, $key));
        }

        $hasil = [];
        foreach ($alternatif as $alt) {
            $total = 0;

            foreach ($kriteria as $key => $val) {
                if ($val['tipe'] === 'benefit') {
                    // Benefit: semakin besar semakin baik (1–5)
                    $r = $alt[$key] / ($maxValues[$key] ?: 1);
                } else {
                    // Cost: semakin kecil semakin baik (dibalik 5–1)
                    $r = ($minValues[$key] ?: 1) / $alt[$key];
                }

                $total += $val['bobot'] * $r;
            }

            $hasil[] = [
                'nama' => $alt['nama'],
                'nilai' => round($total, 4),
            ];
        }

        // Urutkan dari nilai tertinggi
        usort($hasil, fn($a, $b) => $b['nilai'] <=> $a['nilai']);

        foreach ($hasil as $i => &$h) {
            $h['ranking'] = $i + 1;
        }

        return [$hasil, $alternatif];
    }
}
