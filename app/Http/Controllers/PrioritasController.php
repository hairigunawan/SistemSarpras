<?php

namespace App\Http\Controllers;

use App\Models\Peminjaman;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class PrioritasController extends Controller
{
    // === 1️⃣ PRIORITAS PEMINJAMAN RUANGAN ===
    public function ruangan()
    {
        $peminjaman = Peminjaman::whereNotNull('id_ruangan')->get();

        // Ambil kriteria dari database dan normalisasi nama_kriteria
        $dbKriteria = DB::table('kriteria')->get();

        $kriteria = [];
        foreach ($dbKriteria as $k) {
            $key = $this->normalizeKriteriaKey($k->nama_kriteria);
            $kriteria[$key] = [
                'tipe' => strtolower(trim($k->tipe))
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

        // Susun ulang kriteria sesuai orderedKeys agar tampilannya sinkron
        $kriteriaOrdered = [];
        foreach ($orderedKeys as $key) {
            // jika kriteria dari DB tidak punya tipe (safety), beri default benefit
            $kriteriaOrdered[$key] = $kriteria[$key] ?? ['tipe' => 'benefit'];
        }

        // sinkronisasi bobot dengan kriteria (index-safe) mengikuti orderedKeys
        foreach ($orderedKeys as $idx => $key) {
            $kriteriaOrdered[$key]['bobot'] = round($bobot[$idx] ?? 0, 3);
        }

        // hitung SAW menggunakan kriteria terurut
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
        $peminjaman = Peminjaman::whereNotNull('id_proyektor')->get();

        $dbKriteria = DB::table('kriteria')->get();

        $kriteria = [];
        foreach ($dbKriteria as $k) {
            $key = $this->normalizeKriteriaKey($k->nama_kriteria);
            $kriteria[$key] = [
                'tipe' => strtolower(trim($k->tipe))
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
        foreach ($orderedKeys as $key) {
            $kriteriaOrdered[$key] = $kriteria[$key] ?? ['tipe' => 'benefit'];
        }

        foreach ($orderedKeys as $idx => $key) {
            $kriteriaOrdered[$key]['bobot'] = round($bobot[$idx] ?? 0, 3);
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

    // === 3️⃣ TAMBAH, SIMPAN, DAN HAPUS KRITERIA ===
    public function tambahKriteria()
    {
        return view('kriteria.tambah_kruang');
    }

    public function storeKriteria(Request $request)
    {
        $validated = $request->validate([
            'nama_kriteria' => 'required|string|max:100',
            'tipe' => 'required|in:benefit,cost',
        ]);

        DB::table('kriteria')->insert([
            'nama_kriteria' => $validated['nama_kriteria'],
            'tipe' => $validated['tipe'],
        ]);

        return redirect()->route('admin.prioritas.ruangan')
            ->with('success', 'Kriteria baru berhasil ditambahkan.');
    }

    public function deleteKriteria($nama)
    {
        DB::table('kriteria')->where('nama_kriteria', $nama)->delete();
        return back()->with('success', 'Kriteria berhasil dihapus.');
    }

    // === 4️⃣ HITUNG BOBOT AHP (OTOMATIS & SESUAI EXCEL) ===
    private function hitungBobotAHP($data, $kriteria)
    {
        // original keys (from provided kriteria array)
        $origKeys = array_keys($kriteria);
        $n = count($origKeys);

        if ($n === 1) {
            return [
                'pairwiseMatrix' => [[1]],
                'normalizedMatrix' => [[1]],
                'bobotAkhir' => [1],
                'cr' => 0,
                'keys' => $origKeys,
            ];
        }

        // === priority order (Excel reference). Semakin awal = semakin penting.
        $priorityOrder = [
            'jenis_kegiatan',
            'jumlah_peserta',
            'pengajuan',
            'durasi'
        ];

        // Build orderedKeys: take those in priorityOrder first (in that order), then append any other keys
        $orderedKeys = [];
        foreach ($priorityOrder as $p) {
            if (in_array($p, $origKeys)) $orderedKeys[] = $p;
        }
        foreach ($origKeys as $k) {
            if (!in_array($k, $orderedKeys)) $orderedKeys[] = $k;
        }

        $keys = $orderedKeys;
        $n = count($keys);

        // build priorityRank using canonical priorityOrder positions (lower = more important)
        $priorityRank = [];
        foreach ($keys as $k) {
            $pos = array_search($k, $priorityOrder);
            if ($pos === false) $pos = count($priorityOrder); // new keys get lowest priority
            $priorityRank[$k] = $pos;
        }

        // === build pairwise matrix (rows = keys order, cols = keys order)
        $matrix = array_fill(0, $n, array_fill(0, $n, 1.0));
        for ($i = 0; $i < $n; $i++) {
            for ($j = 0; $j < $n; $j++) {
                if ($i === $j) {
                    $matrix[$i][$j] = 1.0;
                    continue;
                }
                // diff in rank
                $diff = abs($priorityRank[$keys[$i]] - $priorityRank[$keys[$j]]);
                $ratio = 1 + $diff; // difference 0 ->1, diff1->2, diff2->3, etc

                // If row i is higher priority (smaller rank number) than col j => row vs col = ratio (big)
                if ($priorityRank[$keys[$i]] < $priorityRank[$keys[$j]]) {
                    $matrix[$i][$j] = $ratio;
                } else {
                    $matrix[$i][$j] = 1 / $ratio;
                }
            }
        }

        // === normalize columns ===
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

        // === bobot = rata-rata baris ===
        $bobot = [];
        for ($i = 0; $i < $n; $i++) {
            $bobot[$i] = array_sum($normal[$i]) / $n;
        }

        // normalize bobot
        $total = array_sum($bobot);
        if ($total <= 0) $total = 1;
        foreach ($bobot as &$b) $b = $b / $total;

        // consistency check
        $lambdaMax = 0.0;
        for ($i = 0; $i < $n; $i++) {
            $temp = 0.0;
            for ($j = 0; $j < $n; $j++) $temp += $matrix[$i][$j] * $bobot[$j];
            $lambdaMax += ($temp / max($bobot[$i], 1e-9));
        }
        $lambdaMax /= $n;

        $ci = ($lambdaMax - $n) / max($n - 1, 1);
        $riList = [0, 0, 0.58, 0.9, 1.12, 1.24, 1.32, 1.41, 1.45];
        $ri = $riList[$n] ?? end($riList);
        $cr = ($ri > 0) ? $ci / $ri : 0;

        return [
            'pairwiseMatrix' => $matrix,
            'normalizedMatrix' => $normal,
            'bobotAkhir' => $bobot,
            'cr' => round($cr, 3),
            'keys' => $keys, // ordered keys used in matrix
        ];
    }

    // === 5️⃣ HITUNG SAW ===
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

        $maxMin = [];
        foreach ($kriteria as $key => $val) {
            $nilai = array_column($alternatif, $key);
            if (empty($nilai)) {
                $max = 1;
                $min = 1;
            } else {
                $max = max($nilai);
                $min = min($nilai);
            }
            $maxMin[$key] = [
                'max' => $max,
                'min' => $min,
            ];
        }

        $hasil = [];
        foreach ($alternatif as $alt) {
            $total = 0;
            foreach ($kriteria as $key => $val) {

                $nilaiAlt = max($alt[$key] ?? 0, 0.0001);
                $maxVal   = max($maxMin[$key]['max'] ?? 0, 0.0001);
                $minVal   = max($maxMin[$key]['min'] ?? 0, 0.0001);

                $r = ($val['tipe'] === 'benefit')
                    ? ($nilaiAlt / $maxVal)
                    : ($minVal / $nilaiAlt);

                $total += ($val['bobot'] ?? 0) * $r;
            }

            $hasil[] = [
                'nama' => $alt['nama'],
                'nilai' => round($total, 4),
            ];
        }

        usort($hasil, fn($a, $b) => $b['nilai'] <=> $a['nilai']);
        foreach ($hasil as $i => &$h) $h['ranking'] = $i + 1;

        return [$hasil, $alternatif];
    }

    // === 6️⃣ KONVERSI NILAI SKALA ===
    private function nilaiSkala($p, $key)
    {
        switch ($key) {
            case 'jenis_kegiatan':
                $jenis = strtolower($p->jenis_kegiatan ?? '');
                if (str_contains($jenis, 'seminar pkl')) return 5;
                if (str_contains($jenis, 'seminar tugas akhir')) return 5;
                if (str_contains($jenis, 'bimbingan')) return 4;
                if (str_contains($jenis, 'praktikum')) return 3;
                if (str_contains($jenis, 'materi')) return 3;
                return 1;

            case 'jumlah_peserta':
                $j = $p->jumlah_peserta ?? 0;
                if ($j > 100) return 5;
                if ($j > 50) return 4;
                if ($j > 25) return 3;
                if ($j > 10) return 2;
                return 1;

            case 'durasi':
                $durasiJam = 0;
                if ($p->jam_mulai && $p->jam_selesai) {
                    $durasiJam = max(1, (strtotime($p->jam_selesai) - strtotime($p->jam_mulai)) / 3600);
                }
                if ($durasiJam <= 2) return 5;
                if ($durasiJam <= 4) return 4;
                if ($durasiJam <= 6) return 3;
                if ($durasiJam <= 8) return 2;
                return 1;

            case 'pengajuan':
                $tanggalPinjam = strtotime($p->tanggal_pinjam);
                $tanggalPengajuan = strtotime($p->created_at);
                $selisihHari = max(1, ($tanggalPinjam - $tanggalPengajuan) / (3600 * 24));

                if ($selisihHari >= 10) return 5;
                if ($selisihHari >= 7) return 4;
                if ($selisihHari >= 4) return 3;
                if ($selisihHari >= 2) return 2;
                return 1;

            default:
                return 3;
        }
    }

    /**
     * Normalisasi / mapping nama_kriteria dari DB ke key canonical that is recognized in nilaiSkala() and priorityOrder.
     */
    private function normalizeKriteriaKey($raw)
    {
        $s = strtolower(trim($raw));
        $s_single = preg_replace('/\s+/', ' ', $s);

        if (strpos($s_single, 'kegiatan') !== false) return 'jenis_kegiatan';
        if (strpos($s_single, 'jumlah peserta') !== false || strpos($s_single, 'jumlah') !== false || strpos($s_single, 'peserta') !== false) return 'jumlah_peserta';
        if (strpos($s_single, 'durasi') !== false) return 'durasi';
        if (strpos($s_single, 'pengajuan') !== false || strpos($s_single, 'ajuan') !== false) return 'pengajuan';

        return str_replace(' ', '_', $s_single);
    }
}
