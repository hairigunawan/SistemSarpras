<?php

namespace App\Services;

use App\Models\Peminjaman;
use App\Models\Kriteria;

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

        // Prepare kriteria data
        $kriteriaArray = $kriterias->keyBy('id')->toArray();

        // Calculate scores for each peminjaman
        $hasil = [];
        $alternatif = [];

        foreach ($peminjamans as $index => $peminjaman) {
            $totalScore = 0;
            $alternatifRow = [];

            // Calculate score for each kriteria
            foreach ($kriterias as $kriteria) {
                $kriteriaId = $kriteria->id;
                $nilai = $this->getCriteriaValue($peminjaman, $kriteria);
                $bobot = $bobotAHP[$kriteriaId] ?? 0;

                $alternatifRow[$kriteriaId] = $nilai;
                $totalScore += $nilai * $bobot;
            }

            $alternatif[] = $alternatifRow;

            // Add to hasil with ranking
            $hasil[] = [
                'id' => $peminjaman->id_peminjaman,
                'nama' => $peminjaman->nama_peminjam,
                'nilai' => round($totalScore, 3),
                'ranking' => 0 // Will be calculated later
            ];
        }

        // Calculate rankings
        $this->calculateRankings($hasil);

        return [
            'hasil' => $hasil,
            'alternatif' => $alternatif
        ];
    }

    /**
     * Get criteria value for a peminjaman
     */
    private function getCriteriaValue($peminjaman, $kriteria)
    {
        $nilai = 0;

        switch ($kriteria->nama_kriteria) {
            case 'Tanggal':
                // More recent dates get higher values
                $tanggalPinjam = \Carbon\Carbon::parse($peminjaman->tanggal_pinjam);
                $today = \Carbon\Carbon::today();
                $daysDiff = $today->diffInDays($tanggalPinjam, false);
                $nilai = max(0, $daysDiff + 7); // Give higher value for closer dates
                break;

            case 'Jumlah Peserta':
                // More participants get higher values
                $nilai = $peminjaman->jumlah_peserta ?? 0;
                break;

            case 'Durasi':
                // Longer duration gets higher values
                $start = \Carbon\Carbon::parse($peminjaman->tanggal_pinjam . ' ' . $peminjaman->jam_mulai);
                $end = \Carbon\Carbon::parse($peminjaman->tanggal_pinjam . ' ' . $peminjaman->jam_selesai);
                $duration = $end->diffInMinutes($start);
                $nilai = $duration / 60; // Convert to hours
                break;

            case 'Proyektor':
                // Give higher value for specific proyektor if needed
                if ($peminjaman->id_proyektor) {
                    $nilai = 1; // Has proyektor
                } else {
                    $nilai = 0; // No proyektor
                }
                break;

            default:
                // Default value based on kriteria tipe
                $nilai = $this->getDefaultCriteriaValue($peminjaman, $kriteria);
                break;
        }

        // Normalize nilai to 0-1 range if needed
        return $this->normalizeValue($nilai, $kriteria->tipe);
    }

    /**
     * Get default criteria value
     */
    private function getDefaultCriteriaValue($peminjaman, $kriteria)
    {
        // Default implementation - can be customized
        return 0.5; // Default middle value
    }

    /**
     * Normalize value based on criteria type (benefit or cost)
     */
    private function normalizeValue($nilai, $tipe)
    {
        // For benefit criteria, higher is better
        // For cost criteria, lower is better

        // Simple normalization - adjust based on your needs
        if ($tipe === 'benefit') {
            return min(1, max(0, $nilai / 10)); // Normalize to 0-1 range
        } else {
            return min(1, max(0, 1 - ($nilai / 10))); // Inverse for cost criteria
        }
    }

    /**
     * Calculate rankings based on scores
     */
    private function calculateRankings(&$hasil)
    {
        // Sort by nilai descending
        usort($hasil, function($a, $b) {
            return $b['nilai'] <=> $a['nilai'];
        });

        // Assign rankings
        $rank = 1;
        foreach ($hasil as &$item) {
            $item['ranking'] = $rank++;
        }
    }
}
