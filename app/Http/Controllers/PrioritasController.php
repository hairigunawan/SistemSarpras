<?php

namespace App\Http\Controllers;

use App\Models\Peminjaman;
use Illuminate\Http\Request;

class PrioritasController extends Controller
{
    public function indexRuangan()
    {
        // Ambil semua data peminjaman yang jenis = 'ruangan'
        $peminjamans = Peminjaman::whereHas('sarpras', function ($query){
            $query->where('jenis_sarpras', 'ruangan');
            })->get();

        return view('admin.prioritas.ruangan', compact('peminjamans'));
    }

    public function indexProyektor()
    {
        // Ambil semua data peminjaman yang jenis = 'proyektor'
        $peminjamans = Peminjaman::whereHas('sarpras', function ($query){
            $query->where('jenis_sarpras', 'proyektor');
            })->get();

        return view('admin.prioritas.proyektor', compact('peminjamans'));
    }

    // ====================================================
    // 🔹 Fungsi hitung khusus untuk tiap tipe (ruangan / proyektor)
    // ====================================================
    public function hitungRuangan(Request $request)
    {
        return $this->hitung(new Request(['tipe' => 'ruangan']));
    }

    public function hitungProyektor(Request $request)
    {
        return $this->hitung(new Request(['tipe' => 'proyektor']));
    }

    // ==============================
    // 🔹 Hitung Prioritas (AHP + SAW)
    // ==============================
    public function hitung(Request $request)
    {
        $tipe = $request->input('tipe', 'ruangan');

        // Ambil data sesuai tipe
        $data = Peminjaman::whereHas('sarpras', function ($query) use ($tipe){
            $query->where('jenis_sarpras', ucfirst($tipe));
            })->get();

        if ($data->isEmpty()) {
            return redirect()->back()->with('error', 'Tidak ada data untuk tipe: ' . $tipe);
        }

        // ====== Matriks AHP (4 kriteria) ======
        $A = [
            [1,     2,     4,     3],
            [1/2,   1,     3,     2],
            [1/4,   1/3,   1,     1/2],
            [1/3,   1/2,   2,     1],
        ];

        $ahp = $this->ahpWeights($A);
        $bobotAHP = $ahp['weights'];

        $hasil = $this->hitungSAW($data, $bobotAHP);

        return view('admin.prioritas.hasil', [
            'hasil' => $hasil,
            'bobotAHP' => $bobotAHP,
            'consistency' => [
                'lambda_max' => $ahp['lambda_max'],
                'CI' => $ahp['CI'],
                'CR' => $ahp['CR'],
            ],
            'tipe' => $tipe,
        ]);
    }

    // Fungsi Perhitungan AHP
    private function ahpWeights(array $A)
    {
        $n = count($A);
        $colSum = array_fill(0, $n, 0.0);
        for ($j = 0; $j < $n; $j++) {
            for ($i = 0; $i < $n; $i++) {
                $colSum[$j] += $A[$i][$j];
            }
        }

        $norm = [];
        for ($i = 0; $i < $n; $i++) {
            for ($j = 0; $j < $n; $j++) {
                $norm[$i][$j] = $A[$i][$j] / ($colSum[$j] ?: 1);
            }
        }

        $w = [];
        for ($i = 0; $i < $n; $i++) {
            $w[$i] = array_sum($norm[$i]) / $n;
        }

        $sumW = array_sum($w);
        if ($sumW > 0) {
            $w = array_map(fn($val) => $val / $sumW, $w);
        }

        // Hitung konsistensi
        $Aw = [];
        for ($i = 0; $i < $n; $i++) {
            $Aw[$i] = 0;
            for ($j = 0; $j < $n; $j++) {
                $Aw[$i] += $A[$i][$j] * $w[$j];
            }
        }

        $lambda = array_sum(array_map(fn($a, $b) => $a / $b, $Aw, $w)) / $n;
        $CI = ($lambda - $n) / ($n - 1);
        $RI = [1 => 0, 2 => 0, 3 => 0.58, 4 => 0.90, 5 => 1.12][$n] ?? 1.49;
        $CR = $RI == 0 ? 0 : $CI / $RI;

        return [
            'weights' => $w,
            'lambda_max' => round($lambda, 6),
            'CI' => round($CI, 6),
            'CR' => round($CR, 6),
        ];
    }

    // Fungsi Hitung SAW
    private function hitungSAW($dataCollection, array $bobot)
    {
        $maxJenis = $dataCollection->max('jenis_kegiatan');
        $maxPeserta = $dataCollection->max('jumlah_peserta');
        $minWaktu = $dataCollection->min('waktu_pengajuan');
        $minDurasi = $dataCollection->min('durasi_peminjaman');

        $results = $dataCollection->map(function ($item) use ($bobot, $maxJenis, $maxPeserta, $minWaktu, $minDurasi) {
            $r1 = ($maxJenis == 0) ? 0 : ($item->jenis_kegiatan / $maxJenis);
            $r2 = ($maxPeserta == 0) ? 0 : ($item->jumlah_peserta / $maxPeserta);
            $r3 = ($item->waktu_pengajuan == 0) ? 0 : ($minWaktu / $item->waktu_pengajuan);
            $r4 = ($item->durasi_peminjaman == 0) ? 0 : ($minDurasi / $item->durasi_peminjaman);

            $nilai = ($r1 * $bobot[0]) + ($r2 * $bobot[1]) + ($r3 * $bobot[2]) + ($r4 * $bobot[3]);
            $item->nilai_saw = round($nilai, 6);
            return $item;
        });

        $sorted = $results->sortByDesc('nilai_saw')->values();
        foreach ($sorted as $i => $row) {
            $row->peringkat = $i + 1;
        }

        return $sorted;
    }
}