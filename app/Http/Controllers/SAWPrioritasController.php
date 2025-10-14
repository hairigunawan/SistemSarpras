<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class SAWPrioritasController extends Controller
{
    public function hitungPrioritas(Request $request)
    {
        // Contoh data peminjam (bisa diganti dari database)
        $data = [
            [
                'nama' => 'Peminjam A',
                'jenis_kegiatan' => 5, // Seminar TA/PKL
                'jumlah_peserta' => 3,
                'waktu_pengajuan' => 2,
                'durasi_peminjaman' => 1,
            ],
            [
                'nama' => 'Peminjam B',
                'jenis_kegiatan' => 4, // Bimbingan
                'jumlah_peserta' => 1,
                'waktu_pengajuan' => 5,
                'durasi_peminjaman' => 4,
            ],
            [
                'nama' => 'Peminjam C',
                'jenis_kegiatan' => 2, // Teori
                'jumlah_peserta' => 4,
                'waktu_pengajuan' => 3,
                'durasi_peminjaman' => 5,
            ],
        ];

        // Bobot kriteria
        $bobot = [
            'jenis_kegiatan' => 0.075,
            'jumlah_peserta' => 0.393,
            'waktu_pengajuan' => 0.163,
            'durasi_peminjaman' => 0.369,
        ];

        // Arah kriteria (benefit/cost)
        $tipe = [
            'jenis_kegiatan' => 'benefit',
            'jumlah_peserta' => 'cost',
            'waktu_pengajuan' => 'cost',
            'durasi_peminjaman' => 'cost',
        ];

        // Normalisasi
        $nilai_normalisasi = [];
        foreach ($data as $k => $alt) {
            foreach ($alt as $key => $value) {
                if (isset($bobot[$key])) {
                    // Ambil semua nilai kriteria
                    $nilai_kriteria = array_column($data, $key);

                    if ($tipe[$key] === 'benefit') {
                        // Benefit → dibagi dengan nilai max
                        $normal = $value / max($nilai_kriteria);
                    } else {
                        // Cost → nilai min / nilai
                        $normal = min($nilai_kriteria) / $value;
                    }

                    $nilai_normalisasi[$k][$key] = $normal;
                }
            }
        }

        // Hitung skor akhir
        $hasil = [];
        foreach ($nilai_normalisasi as $i => $n) {
            $skor = 0;
            foreach ($bobot as $key => $w) {
                $skor += $n[$key] * $w;
            }
            $hasil[] = [
                'nama' => $data[$i]['nama'],
                'skor' => round($skor, 4),
            ];
        }

        // Urutkan berdasarkan skor tertinggi
        usort($hasil, fn($a, $b) => $b['skor'] <=> $a['skor']);

        // Batasi sesuai jumlah proyektor/ruangan (contoh: 8 proyektor)
        $prioritasProyektor = array_slice($hasil, 0, 8);

        return response()->json([
            'ranking_semua' => $hasil,
            'prioritas_proyektor' => $prioritasProyektor,
        ]);
    }
}
