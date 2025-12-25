<?php

namespace App\Services;

use App\Models\Peminjaman;
use App\Models\Kriteria;
use Carbon\Carbon;

class SimpleSAWService
{
    public function calculateSAW($peminjamans, $kriterias, $bobotAHP)
    {
        if ($peminjamans->isEmpty() || $kriterias->isEmpty()) {
            return [
                'hasil' => [],
                'alternatif' => []
            ];
        }

        // 1. Gather Raw Values
        $alternatif = [];
        $rawValues = []; // Store raw values for max/min calculation

        foreach ($peminjamans as $peminjaman) {
            $row = [
                'id' => $peminjaman->id, // Ensure we use the correct ID key (id or id_peminjaman)
                'nama' => $peminjaman->nama_peminjam ?? $peminjaman->user->name ?? 'User',
            ];

            foreach ($kriterias as $kriteria) {
                $val = $this->getCriteriaValue($peminjaman, $kriteria);
                $row[$kriteria->id] = $val;
                $rawValues[$kriteria->id][] = $val;
            }
            $alternatif[] = $row;
        }

        // 2. Find Max/Min for each criterion
        $maxMin = [];
        foreach ($kriterias as $kriteria) {
            $values = $rawValues[$kriteria->id] ?? [0];
            $maxMin[$kriteria->id] = [
                'max' => max($values) ?: 1, // Avoid div by zero
                'min' => min($values) ?: 0.1, // Avoid div by zero
            ];
        }

        // 3. Normalize and Calculate Score
        $hasil = [];
        foreach ($alternatif as $alt) {
            $totalScore = 0;
            $normalizedRow = $alt; // To store normalized values if needed for display

            foreach ($kriterias as $kriteria) {
                $kid = $kriteria->id;
                $val = $alt[$kid];
                $max = $maxMin[$kid]['max'];
                $min = $maxMin[$kid]['min'];
                $bobot = $bobotAHP[$kid] ?? 0;
                $tipe = $kriteria->tipe;

                // SAW Normalization
                $normalized = 0;
                if ($tipe === 'cost') {
                    // Min / Value (Low is good)
                    // Avoid division by zero if value is 0 (unlikely for duration/date but possible)
                    $normalized = ($val == 0) ? 1 : ($min / $val);
                } else {
                    // Benefit: Value / Max (High is good)
                    $normalized = $val / $max;
                }

                $totalScore += $normalized * $bobot;
                $normalizedRow[$kid] = $normalized; // Update if we want to return normalized matrix
            }

            $hasil[] = [
                'id' => $alt['id'],
                'nama' => $alt['nama'],
                'nilai' => round($totalScore, 4),
                'ranking' => 0
            ];
        }

        // 4. Ranking
        usort($hasil, function ($a, $b) {
            return $b['nilai'] <=> $a['nilai'];
        });

        $rank = 1;
        foreach ($hasil as &$h) {
            $h['ranking'] = $rank++;
        }

        return [
            'hasil' => $hasil,
            'alternatif' => $alternatif // Returning raw values for display
        ];
    }

    /**
     * Get raw criteria value for a peminjaman
     */
    private function getCriteriaValue($peminjaman, $kriteria)
    {
        $name = strtolower($kriteria->nama_kriteria);

        if (str_contains($name, 'tanggal') || str_contains($name, 'date')) {
            // Days from today. 
            // If event is today: 0. Tomorrow: 1.
            // If handling urgency: This should likely be a COST criteria (Lower is better).
            // If handling "Booked in advance": BENEFIT (Higher is better).
            // We return raw days.
            $tanggalPinjam = Carbon::parse($peminjaman->tanggal_pinjam);
            $today = Carbon::today();
            return max(0, $today->diffInDays($tanggalPinjam, false));
        }

        if (str_contains($name, 'peserta') || str_contains($name, 'jumlah')) {
            return (int) ($peminjaman->jumlah_peserta ?? 0);
        }

        if (str_contains($name, 'durasi') || str_contains($name, 'waktu')) {
            // Duration in hours
            $start = Carbon::parse($peminjaman->tanggal_pinjam . ' ' . $peminjaman->jam_mulai);
            $end = Carbon::parse($peminjaman->tanggal_pinjam . ' ' . $peminjaman->jam_selesai);
            return max(0, $end->diffInMinutes($start) / 60);
        }

        if (str_contains($name, 'proyektor') || str_contains($name, 'alat')) {
            return $peminjaman->id_proyektor ? 1 : 0;
        }

        // Default fallback
        return 0;
    }
}
