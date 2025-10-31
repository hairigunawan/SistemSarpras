<?php

namespace App\Http\Controllers;

use App\Models\Peminjaman;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;

class PrioritasController extends Controller
{
    /**
     * Menampilkan hasil perhitungan prioritas peminjaman ruangan.
     */
    public function indexRuangan()
    {
        $peminjamans = Peminjaman::with(['ruangan', 'user'])
            ->whereHas('sarpras', fn($q) => $q->where('tipe', 'Ruangan'))
            ->get();

        if ($peminjamans->isEmpty()) {
            return back()->with('warning', 'Tidak ada data peminjaman ruangan untuk dihitung.');
        }

        // Contoh matriks AHP antar kriteria
        $A = [
            [1,   3,   5],
            [1 / 3, 1,   2],
            [1 / 5, 1 / 2, 1],
        ];

        // Hitung bobot dengan AHP
        $ahp = $this->hitungAHP($A);

        // Hitung hasil SAW
        $hasil = $this->hitungSAW($peminjamans, $ahp['weights']);

        // Simpan hasil ke database
        foreach ($hasil as $index => $item) {
            $peminjaman = Peminjaman::find($item['id']);
            if ($peminjaman) {
                $peminjaman->update([
                    'nilai_prioritas' => $item['nilai'],
                    'peringkat' => $index + 1,
                ]);
            }
        }

        return view('admin.prioritas.ruangan', [
            'hasil' => $hasil,
            'weights' => $ahp['weights'],
            'cr' => $ahp['cr'],
        ]);
    }
    public function indexProyektor()
    {
        $peminjamans = Peminjaman::with(['proyektor', 'user'])
            ->whereHas('proyektor', fn($q) => $q->where('tipe', 'proyektor'))
            ->get();

        if ($peminjamans->isEmpty()) {
            return back()->with('warning', 'Tidak ada data peminjaman ruangan untuk dihitung.');
        }

        // Contoh matriks AHP antar kriteria
        $A = [
            [1,   3,   5],
            [1 / 3, 1,   2],
            [1 / 5, 1 / 2, 1],
        ];

        // Hitung bobot dengan AHP
        $ahp = $this->hitungAHP($A);

        // Hitung hasil SAW
        $hasil = $this->hitungSAW($peminjamans, $ahp['weights']);

        // Simpan hasil ke database
        foreach ($hasil as $index => $item) {
            $peminjaman = Peminjaman::find($item['id']);
            if ($peminjaman) {
                $peminjaman->update([
                    'nilai_prioritas' => $item['nilai'],
                    'peringkat' => $index + 1,
                ]);
            }
        }

        return view('admin.prioritas.ruangan', [
            'hasil' => $hasil,
            'weights' => $ahp['weights'],
            'cr' => $ahp['cr'],
        ]);
    }

    /**
     * Fungsi umum untuk menghitung bobot AHP.
     */
    private function hitungAHP(array $matrix)
    {
        $n = count($matrix);
        $col_sum = array_fill(0, $n, 0);

        // Hitung total kolom
        for ($i = 0; $i < $n; $i++) {
            for ($j = 0; $j < $n; $j++) {
                $col_sum[$j] += $matrix[$i][$j];
            }
        }

        // Normalisasi dan hitung bobot
        $norm = [];
        for ($i = 0; $i < $n; $i++) {
            for ($j = 0; $j < $n; $j++) {
                $norm[$i][$j] = ($col_sum[$j] == 0) ? 0 : $matrix[$i][$j] / $col_sum[$j];
            }
        }

        $weights = [];
        for ($i = 0; $i < $n; $i++) {
            $weights[$i] = array_sum($norm[$i]) / $n;
        }

        // Hitung Consistency Ratio (CR)
        $λmax = 0;
        for ($i = 0; $i < $n; $i++) {
            $row_sum = 0;
            for ($j = 0; $j < $n; $j++) {
                $row_sum += $matrix[$i][$j] * $weights[$j];
            }
            $λmax += $row_sum / ($weights[$i] ?: 1);
        }
        $λmax /= $n;

        $CI = ($n > 1) ? (($λmax - $n) / ($n - 1)) : 0;
        $RI = [0, 0, 0.58, 0.90, 1.12, 1.24]; // Random Index
        $CR = ($n <= 5) ? ($CI / $RI[$n]) : 0;

        if ($CR > 0.1) {
            Session::flash('warning', '⚠️ Rasio Konsistensi (CR) = ' . round($CR, 3) . ' > 0.1. Bobot kriteria mungkin tidak konsisten.');
        }

        return [
            'weights' => $weights,
            'cr' => round($CR, 4),
        ];
    }

    /**
     * Fungsi umum untuk menghitung prioritas SAW.
     */
    private function hitungSAW($data, $bobot)
    {
        // Ubah data ke array nilai numerik (misal: lama peminjaman, urgensi, dll)
        $alternatif = [];
        foreach ($data as $d) {
            $alternatif[] = [
                'id' => $d->id,
                'nama_peminjam' => $d->user->name ?? 'Tidak diketahui',
                'ruangan' => $d->ruangan->nama_ruangan ?? '-',
                'kriteria' => [

                    $lama_peminjaman = ($d->tanggal_kembali - $d->tanggal_pinjam) - ($d->jam_selesai - $d->jam_mulai),
                    $lama_peminjaman ?? 1,
                    $d->kepentingan ?? 1,
                    $d->frekuensi ?? 1,
                ],
            ];
        }

        // Normalisasi (benefit criteria)
        $transpose = [];
        foreach ($alternatif as $alt) {
            foreach ($alt['kriteria'] as $i => $val) {
                $transpose[$i][] = $val;
            }
        }

        $normalized = [];
        foreach ($alternatif as $alt) {
            $nilai = [];
            foreach ($alt['kriteria'] as $i => $val) {
                $max = max($transpose[$i]);
                $nilai[$i] = ($max == 0) ? 0 : $val / $max;
            }
            $normalized[] = $nilai;
        }

        // Hitung nilai akhir
        $hasil = [];
        foreach ($alternatif as $i => $alt) {
            $nilai_akhir = 0;
            foreach ($bobot as $j => $w) {
                $nilai_akhir += $normalized[$i][$j] * $w;
            }

            $hasil[] = [
                'id' => $alt['id'],
                'nama_peminjam' => $alt['nama_peminjam'],
                'ruangan' => $alt['ruangan'],
                'nilai' => round($nilai_akhir, 4),
            ];
        }

        // Urutkan descending
        $hasil = collect($hasil)->sortByDesc('nilai')->values()->all();

        // Tambahkan peringkat
        foreach ($hasil as $index => &$item) {
            $item['peringkat'] = $index + 1;
        }

        return $hasil;
    }
}
