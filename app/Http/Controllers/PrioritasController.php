<?php

namespace App\Http\Controllers;

use App\Models\Peminjaman;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;

class PrioritasController extends Controller
{
    // === 1️⃣ PRIORITAS PEMINJAMAN RUANGAN ===
    public function ruangan()
    {
        // Pastikan relasi 'ruangan' ada di model Peminjaman
        $peminjaman = Peminjaman::with('ruangan')->whereNotNull('id_ruangan')->get();

        // Ambil kriteria dari database
        $dbKriteria = DB::table('kriteria')->get();

        $kriteria = [];
        foreach ($dbKriteria as $k) {
            $key = $this->normalizeKriteriaKey($k->nama_kriteria);
            $kriteria[$key] = [
                'id' => $k->id, // PENTING: Simpan ID asli untuk link hapus/edit
                'tipe' => strtolower(trim($k->tipe)),
                'nama_asli' => $k->nama_kriteria // Simpan nama asli untuk display
            ];
        }

        if (count($kriteria) == 0) {
            return view('admin.prioritas.ruangan', [
                'peminjaman' => $peminjaman,
                'pairwiseMatrix' => [],
                'normalizedMatrix' => [],
                'bobotAkhir' => [],
                'cr' => 0,
                'kriteria' => [],
                'hasil' => [],
                'alternatif' => [],
            ]);
        }

        $ahp = $this->hitungBobotAHP($peminjaman, $kriteria);
        $bobot = $ahp['bobotAkhir'];
        $orderedKeys = $ahp['keys'] ?? array_keys($kriteria);

        // Susun ulang kriteria untuk View
        $kriteriaOrdered = [];
        foreach ($orderedKeys as $idx => $key) {
            if (isset($kriteria[$key])) {
                $kriteriaOrdered[$key] = $kriteria[$key];
                $kriteriaOrdered[$key]['bobot'] = round($bobot[$idx] ?? 0, 3);
            }
        }

        // Hitung SAW
        [$hasil, $alternatif] = $this->hitungSAW($peminjaman, $kriteriaOrdered);

        return view('admin.prioritas.ruangan', [
            'peminjaman' => $peminjaman,
            'pairwiseMatrix' => $ahp['pairwiseMatrix'],
            'normalizedMatrix' => $ahp['normalizedMatrix'],
            'bobotAkhir' => $ahp['bobotAkhir'],
            'cr' => $ahp['cr'],
            'kriteria' => $kriteriaOrdered,
            'hasil' => $hasil,
            'alternatif' => $alternatif,
        ]);
    }

    // === 2️⃣ PRIORITAS PEMINJAMAN PROYEKTOR ===
    public function proyektor()
    {
        // Pastikan relasi 'proyektor' ada di model Peminjaman
        $peminjaman = Peminjaman::with('proyektor')->whereNotNull('id_proyektor')->get();

        $dbKriteria = DB::table('kriteria')->get();

        $kriteria = [];
        foreach ($dbKriteria as $k) {
            $key = $this->normalizeKriteriaKey($k->nama_kriteria);
            $kriteria[$key] = [
                'id' => $k->id, // PENTING: Simpan ID asli
                'tipe' => strtolower(trim($k->tipe)),
                'nama_asli' => $k->nama_kriteria
            ];
        }

        if (count($kriteria) == 0) {
            return view('admin.prioritas.proyektor', [
                'peminjaman' => $peminjaman,
                'pairwiseMatrix' => [],
                'normalizedMatrix' => [],
                'bobotAkhir' => [],
                'cr' => 0,
                'kriteria' => [],
                'hasil' => [],
                'alternatif' => [],
            ]);
        }

        $ahp = $this->hitungBobotAHP($peminjaman, $kriteria);
        $bobot = $ahp['bobotAkhir'];
        $orderedKeys = $ahp['keys'] ?? array_keys($kriteria);

        $kriteriaOrdered = [];
        foreach ($orderedKeys as $idx => $key) {
            if (isset($kriteria[$key])) {
                $kriteriaOrdered[$key] = $kriteria[$key];
                $kriteriaOrdered[$key]['bobot'] = round($bobot[$idx] ?? 0, 3);
            }
        }

        [$hasil, $alternatif] = $this->hitungSAW($peminjaman, $kriteriaOrdered);

        return view('admin.prioritas.proyektor', [
            'peminjaman' => $peminjaman,
            'pairwiseMatrix' => $ahp['pairwiseMatrix'],
            'normalizedMatrix' => $ahp['normalizedMatrix'],
            'bobotAkhir' => $ahp['bobotAkhir'],
            'cr' => $ahp['cr'],
            'kriteria' => $kriteriaOrdered,
            'hasil' => $hasil,
            'alternatif' => $alternatif,
        ]);
    }

    // === HITUNG AHP ===
    private function hitungBobotAHP($data, $kriteria)
    {
        $origKeys = array_keys($kriteria);
        $n = count($origKeys);

        if ($n === 0) return ['pairwiseMatrix' => [], 'normalizedMatrix' => [], 'bobotAkhir' => [], 'cr' => 0, 'keys' => []];
        if ($n === 1) {
            return [
                'pairwiseMatrix' => [[1]],
                'normalizedMatrix' => [[1]],
                'bobotAkhir' => [1],
                'cr' => 0,
                'keys' => $origKeys,
            ];
        }

        // Prioritas Hardcoded (semakin atas semakin penting)
        $priorityOrder = [
            'jenis_kegiatan',
            'jumlah_peserta',
            'pengajuan',
            'durasi'
        ];

        // Susun urutan key berdasarkan priorityOrder + sisa key lainnya
        $orderedKeys = [];
        foreach ($priorityOrder as $p) {
            if (in_array($p, $origKeys)) $orderedKeys[] = $p;
        }
        foreach ($origKeys as $k) {
            if (!in_array($k, $orderedKeys)) $orderedKeys[] = $k;
        }

        $keys = $orderedKeys;
        $n = count($keys);

        // Map key ke rank integer untuk perbandingan
        $priorityRank = [];
        foreach ($keys as $k) {
            $pos = array_search($k, $priorityOrder);
            if ($pos === false) $pos = 99; // Kriteria baru/unknown dianggap prioritas rendah
            $priorityRank[$k] = $pos;
        }

        // Buat matriks
        $matrix = array_fill(0, $n, array_fill(0, $n, 1.0));
        for ($i = 0; $i < $n; $i++) {
            for ($j = 0; $j < $n; $j++) {
                if ($i === $j) continue;

                $rankI = $priorityRank[$keys[$i]];
                $rankJ = $priorityRank[$keys[$j]];

                $diff = abs($rankI - $rankJ);
                $ratio = ($diff == 0) ? 1 : (1 + $diff);

                // Rank lebih kecil = Lebih penting
                if ($rankI < $rankJ) {
                    $matrix[$i][$j] = $ratio;
                } else {
                    $matrix[$i][$j] = 1 / $ratio;
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
            'keys' => $keys,
        ];
    }

    // === HITUNG SAW ===
    private function hitungSAW($data, $kriteria)
    {
        $alternatif = [];

        foreach ($data as $p) {
            $alt = [
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
                'nama' => $alt['nama'],
                'nilai' => round($total, 4),
            ];
        }

        // Ranking
        usort($hasil, fn($a, $b) => $b['nilai'] <=> $a['nilai']);
        foreach ($hasil as $i => &$h) $h['ranking'] = $i + 1;

        return [$hasil, $alternatif];
    }

    // === NILAI SKALA (Logika Bisnis) ===
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

    private function normalizeKriteriaKey($raw)
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
}
