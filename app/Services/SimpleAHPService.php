<?php

namespace App\Services;

use App\Models\Kriteria;

class SimpleAHPService
{
    /**
     * Calculate AHP weights using pairwise comparison matrix
     */
    public function calculateAHP($kriterias)
    {
        if ($kriterias->isEmpty()) {
            return [
                'pairwiseMatrix' => [],
                'normalizedMatrix' => [],
                'bobotAkhir' => [],
                'bobot' => [],
                'cr' => 0
            ];
        }

        // Build pairwise comparison matrix (simplified - using bobot from criteria)
        $kriteriaArray = $kriterias->keyBy('id')->toArray();
        $n = count($kriteriaArray);

        // Initialize pairwise matrix
        $pairwiseMatrix = array_fill(0, $n, array_fill(0, $n, 1));

        // Fill pairwise matrix based on bobot values
        $kriteriaList = array_values($kriteriaArray);
        for ($i = 0; $i < $n; $i++) {
            for ($j = 0; $j < $n; $j++) {
                if ($i != $j) {
                    $bobotI = $kriteriaList[$i]['bobot'] ?? 0.25;
                    $bobotJ = $kriteriaList[$j]['bobot'] ?? 0.25;

                    // Avoid division by zero
                    if ($bobotJ > 0) {
                        $pairwiseMatrix[$i][$j] = round($bobotI / $bobotJ, 3);
                    } else {
                        $pairwiseMatrix[$i][$j] = 1;
                    }
                }
            }
        }

        // Calculate normalized matrix
        $normalizedMatrix = $this->normalizeMatrix($pairwiseMatrix);

        // Calculate final weights (bobot akhir)
        $bobotAkhir = $this->calculateWeights($normalizedMatrix);

        // Calculate consistency ratio (simplified)
        $cr = $this->calculateConsistencyRatio($pairwiseMatrix, $bobotAkhir);

        // Create bobot array indexed by kriteria id
        $bobot = [];
        foreach ($kriterias as $index => $kriteria) {
            $bobot[$kriteria->id] = $bobotAkhir[$index] ?? 0;
        }

        return [
            'pairwiseMatrix' => $pairwiseMatrix,
            'normalizedMatrix' => $normalizedMatrix,
            'bobotAkhir' => $bobotAkhir,
            'bobot' => $bobot,
            'cr' => $cr
        ];
    }

    /**
     * Normalize the pairwise comparison matrix
     */
    private function normalizeMatrix($matrix)
    {
        $n = count($matrix);
        $normalizedMatrix = array_fill(0, $n, array_fill(0, $n, 0));

        // Calculate column sums
        $columnSums = array_fill(0, $n, 0);
        for ($j = 0; $j < $n; $j++) {
            for ($i = 0; $i < $n; $i++) {
                $columnSums[$j] += $matrix[$i][$j];
            }
        }

        // Normalize matrix
        for ($i = 0; $i < $n; $i++) {
            for ($j = 0; $j < $n; $j++) {
                if ($columnSums[$j] > 0) {
                    $normalizedMatrix[$i][$j] = round($matrix[$i][$j] / $columnSums[$j], 3);
                } else {
                    $normalizedMatrix[$i][$j] = 0;
                }
            }
        }

        return $normalizedMatrix;
    }

    /**
     * Calculate weights from normalized matrix
     */
    private function calculateWeights($normalizedMatrix)
    {
        $n = count($normalizedMatrix);
        $weights = [];

        for ($i = 0; $i < $n; $i++) {
            $rowSum = array_sum($normalizedMatrix[$i]);
            $weights[] = round($rowSum / $n, 3);
        }

        return $weights;
    }

    /**
     * Calculate consistency ratio (simplified)
     */
    private function calculateConsistencyRatio($matrix, $weights)
    {
        $n = count($matrix);

        if ($n <= 1) {
            return 0;
        }

        // Calculate lambda max
        $lambdaMax = 0;
        for ($i = 0; $i < $n; $i++) {
            $sum = 0;
            for ($j = 0; $j < $n; $j++) {
                $sum += $matrix[$i][$j] * $weights[$j];
            }
            $lambdaMax += $sum / ($weights[$i] * $n);
        }

        // Calculate consistency index
        $ci = ($lambdaMax - $n) / ($n - 1);

        // Random index values
        $riValues = [
            1 => 0.00,
            2 => 0.00,
            3 => 0.58,
            4 => 0.90,
            5 => 1.12,
            6 => 1.24,
            7 => 1.32,
            8 => 1.41,
            9 => 1.45
        ];

        $ri = $riValues[$n] ?? 1.45;

        // Calculate consistency ratio
        $cr = ($ri > 0) ? round($ci / $ri, 3) : 0;

        return $cr;
    }
}
