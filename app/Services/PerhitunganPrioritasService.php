<?php

namespace App\Services;

use App\Models\Kriteria;
use App\Models\Bobot;
use App\Models\Peminjaman;

/**
 * Service untuk menghitung prioritas peminjaman menggunakan pendekatan hibrida:
 * - AHP (Analytic Hierarchy Process) untuk menentukan bobot kriteria
 * - SAW (Simple Additive Weighting) sebagai metode akhir untuk perankingan
 */
class PerhitunganPrioritasService
{
    /**
     * Menghitung bobot kriteria menggunakan metode AHP
     *
     * Catatan: Nama bobot dalam tabel 'bobots' harus sesuai dengan nama kriteria
     * agar perhitungan AHP dapat berjalan dengan benar
     */
    public function hitungBobotAHP($kriteria)
    {
        $origKeys = array_keys($kriteria);
        $n = count($origKeys);

        if ($n === 0) {
            return ['pairwiseMatrix' => [], 'normalizedMatrix' => [], 'bobotAkhir' => [], 'cr' => 0, 'keys' => []];
        }

        if ($n === 1) {
            return [
                'pairwiseMatrix' => [[1]],
                'normalizedMatrix' => [[1]],
                'bobotAkhir' => [1],
                'cr' => 0,
                'keys' => $origKeys,
            ];
        }

        // Ambil bobot dari tabel bobots untuk digunakan sebagai perbandingan kriteria
        // Nama bobot harus sesuai dengan nama kriteria agar dapat dihubungkan
        $bobotKriteria = Bobot::whereIn('nama', $origKeys)->get()->keyBy('nama');

        // Buat matriks perbandingan berdasarkan bobot kriteria
        $matrix = array_fill(0, $n, array_fill(0, $n, 1.0));
        for ($i = 0; $i < $n; $i++) {
            for ($j = 0; $j < $n; $j++) {
                if ($i === $j) {
                    $matrix[$i][$j] = 1.0;
                } else {
                    $nilai_i = $bobotKriteria->get($origKeys[$i])->nilai ?? 0.5; // Default bobot
                    $nilai_j = $bobotKriteria->get($origKeys[$j])->nilai ?? 0.5;

                    // Jika nilai_j adalah 0, kita perlu handling
                    if ($nilai_j == 0) {
                        $matrix[$i][$j] = 999; // Atau nilai besar lainnya
                    } else {
                        $matrix[$i][$j] = $nilai_i / $nilai_j;
                    }
                }
            }
        }

        // Normalisasi Kolom
        $sumKolom = array_fill(0, $n, 0.0);
        for ($j = 0; $j < $n; $j++) {
            for ($i = 0; $i < $n; $i++) $sumKolom[$j] += $matrix[$i][$j];
        }

        $normal = array_fill(0, $n, array_fill(0, $n, 0.0));
        for ($i = 0; $i < $n; $i++) {
            for ($j = 0; $j < $n; $j++) {
                $normal[$i][$j] = $matrix[$i][$j] / max($sumKolom[$j], 1e-9);
            }
        }

        // Bobot (Rata-rata Baris)
        $bobot = [];
        for ($i = 0; $i < $n; $i++) {
            $bobot[$i] = array_sum($normal[$i]) / $n;
        }

        // Hitung CR
        $lambdaMax = 0.0;
        for ($i = 0; $i < $n; $i++) {
            $temp = 0.0;
            for ($j = 0; $j < $n; $j++) $temp += $matrix[$i][$j] * $bobot[$j];
            $lambdaMax += ($temp / max($bobot[$i], 1e-9));
        }
        $lambdaMax /= $n;

        $ci = ($lambdaMax - $n) / max($n - 1, 1);
        $riList = [0, 0, 0.58, 0.9, 1.12, 1.24, 1.32, 1.41, 1.45];
        $ri = $riList[$n] ?? 1.49;
        $cr = ($ri > 0) ? $ci / $ri : 0;

        return [
            'pairwiseMatrix' => $matrix,
            'normalizedMatrix' => $normal,
            'bobotAkhir' => $bobot,
            'cr' => round($cr, 3),
            'keys' => $origKeys,
        ];
    }

    /**
     * Menghitung perankingan menggunakan metode SAW
     */
    public function hitungSAW($data, $kriteria)
    {
        $alternatif = [];

        foreach ($data as $p) {
            $alt = [
                'id' => $p->id,
                'nama' => $p->nama_peminjam ?? 'Tidak Diketahui',
            ];

            foreach ($kriteria as $key => $v) {
                $alt[$key] = $this->nilaiSkala($p, $key);
            }
            $alternatif[] = $alt;
        }

        // Cari Max/Min untuk normalisasi SAW
        $maxMin = [];
        foreach ($kriteria as $key => $val) {
            $colValues = array_column($alternatif, $key);
            if (empty($colValues)) {
                $maxMin[$key] = ['max' => 1, 'min' => 1];
            } else {
                $maxMin[$key] = [
                    'max' => max($colValues),
                    'min' => min($colValues),
                ];
            }
        }

        $hasil = [];
        foreach ($alternatif as $alt) {
            $total = 0;
            foreach ($kriteria as $key => $val) {
                $nilaiAlt = $alt[$key];
                $maxVal = $maxMin[$key]['max'];
                $minVal = $maxMin[$key]['min'];

                // Normalisasi SAW
                if ($val['tipe'] == 'cost') {
                    $r = ($minVal / max($nilaiAlt, 1e-9));
                } else {
                    $r = ($nilaiAlt / max($maxVal, 1e-9));
                }

                $total += ($val['bobot'] ?? 0) * $r;
            }

            $hasil[] = [
                'id' => $alt['id'],
                'nama' => $alt['nama'],
                'nilai' => round($total, 4),
            ];
        }

        // Ranking
        usort($hasil, fn($a, $b) => $b['nilai'] <=> $a['nilai']);
        foreach ($hasil as $i => &$h) $h['ranking'] = $i + 1;

        return [$hasil, $alternatif];
    }

    /**
     * Mendapatkan nilai skala berdasarkan kriteria
     */
    private function nilaiSkala($p, $key)
    {
        // 1. Logika untuk JENIS KEGIATAN
        if ($key === 'jenis_kegiatan') {
            $jenis = strtolower($p->jenis_kegiatan ?? $p->keperluan ?? '');
            if (str_contains($jenis, 'pkl') || str_contains($jenis, 'skripsi') || str_contains($jenis, 'ta')) return 5;
            if (str_contains($jenis, 'seminar')) return 5;
            if (str_contains($jenis, 'bimbingan')) return 4;
            if (str_contains($jenis, 'kuliah') || str_contains($jenis, 'praktikum')) return 3;
            if (str_contains($jenis, 'rapat')) return 2;
            return 1;
        }

        // 2. Logika untuk JUMLAH PESERTA
        if ($key === 'jumlah_peserta') {
            $j = intval($p->jumlah_peserta ?? 0);
            if ($j > 100) return 5;
            if ($j > 50) return 4;
            if ($j > 25) return 3;
            if ($j > 10) return 2;
            return 1;
        }

        // 3. Logika untuk DURASI
        if ($key === 'durasi') {
            $durasiJam = 0;
            if ($p->jam_mulai && $p->jam_selesai) {
                $durasiJam = (strtotime($p->jam_selesai) - strtotime($p->jam_mulai)) / 3600;
            }
            // Asumsi Benefit: Semakin lama semakin prioritas?
            // Atau Cost: Semakin lama semakin buruk? (Default logic disini benefit untuk efisiensi ruang)
            if ($durasiJam > 5) return 5;
            if ($durasiJam > 3) return 4;
            if ($durasiJam > 1.5) return 3;
            if ($durasiJam > 0) return 2;
            return 1;
        }

        // 4. Logika untuk PENGAJUAN (Selisih Hari)
        if ($key === 'pengajuan') {
            $tglPinjam = strtotime($p->tanggal_pinjam);
            $tglBuat = strtotime($p->created_at);
            $selisih = max(0, ($tglPinjam - $tglBuat) / (3600 * 24));

            // Semakin jauh hari booking, semakin prioritas (disiplin)
            if ($selisih >= 7) return 5;
            if ($selisih >= 5) return 4;
            if ($selisih >= 3) return 3;
            if ($selisih >= 1) return 2;
            return 1;
        }

        // Default value jika ada kriteria baru yg belum di-coding logikanya
        return 3;
    }

    /**
     * Menormalisasi nama kriteria menjadi key
     */
    public function normalizeKriteriaKey($raw)
    {
        $s = strtolower(trim($raw));
        // Membersihkan karakter aneh
        $s = preg_replace('/[^a-z0-9\s]/', '', $s);

        if (str_contains($s, 'kegiatan') || str_contains($s, 'acara')) return 'jenis_kegiatan';
        if (str_contains($s, 'peserta') || str_contains($s, 'orang')) return 'jumlah_peserta';
        if (str_contains($s, 'durasi') || str_contains($s, 'waktu') || str_contains($s, 'lama')) return 'durasi';
        if (str_contains($s, 'ajuan') || str_contains($s, 'booking')) return 'pengajuan';

        return str_replace(' ', '_', $s);
    }

    /**
     * Menghitung nilai AHP untuk ditampilkan
     */
    public function hitungAHPValues($data, $kriteria)
    {
        $alternatif = [];

        foreach ($data as $p) {
            $alt = [
                'id' => $p->id,
                'nama' => $p->nama_peminjam ?? 'Tidak Diketahui',
            ];

            foreach ($kriteria as $key => $v) {
                $alt[$key] = $this->nilaiSkala($p, $key);
            }
            $alternatif[] = $alt;
        }

        // Perhitungan AHP untuk setiap alternatif
        $hasil = [];
        foreach ($alternatif as $alt) {
            $total = 0;
            foreach ($kriteria as $key => $val) {
                $nilaiAlt = $alt[$key];
                $bobot = $val['bobot'] ?? 0;
                $total += $nilaiAlt * $bobot;
            }

            $hasil[] = [
                'id' => $alt['id'],
                'nama' => $alt['nama'],
                'nilai' => round($total, 4),
            ];
        }

        // Ranking
        usort($hasil, fn($a, $b) => $b['nilai'] <=> $a['nilai']);
        foreach ($hasil as $i => &$h) $h['ranking'] = $i + 1;

        return $hasil;
    }
}