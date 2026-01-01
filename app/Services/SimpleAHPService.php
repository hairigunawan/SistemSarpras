<?php

namespace App\Services;

use App\Models\Kriteria;

class SimpleAHPService
{
    /**
     * Calculate AHP weights using pairwise comparison matrix
     * 
     * @param \Illuminate\Support\Collection $kriterias
     * @param \Illuminate\Support\Collection|null $comparisons Data dari tabel kriteria_comparisons
     */
    public function calculateAHP($kriterias, $comparisons = null)
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

        $kriteriaArray = $kriterias->keyBy('id')->toArray();
        $ids = array_keys($kriteriaArray);
        $n = count($ids);
        
        // Initialize pairwise matrix with 1s
        $pairwiseMatrix = array_fill(0, $n, array_fill(0, $n, 1));

        // Fill matrix from comparisons if available
        if ($comparisons && $comparisons->isNotEmpty()) {
            // Map ID to Index 0..n-1
            $idToIndex = array_flip($ids);

            foreach ($comparisons as $comp) {
                if (isset($idToIndex[$comp->kriteria_id_1]) && isset($idToIndex[$comp->kriteria_id_2])) {
                    $i = $idToIndex[$comp->kriteria_id_1];
                    $j = $idToIndex[$comp->kriteria_id_2];
                    $val = (float) $comp->nilai;

                    $pairwiseMatrix[$i][$j] = $val;
                    $pairwiseMatrix[$j][$i] = ($val != 0) ? 1 / $val : 1;
                }
            }
        } 
        
        // Calculate normalized matrix
        $normalizedMatrix = $this->normalizeMatrix($pairwiseMatrix);

        // Calculate final weights (bobot akhir / Eigen vector)
        $bobotAkhir = $this->calculateWeights($normalizedMatrix);

        // Calculate consistency ratio
        $cr = $this->calculateConsistencyRatio($pairwiseMatrix, $bobotAkhir);

        // Create bobot array indexed by kriteria id
        $bobot = [];
        foreach ($ids as $index => $id) {
            $bobot[$id] = $bobotAkhir[$index] ?? 0;
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
                    $normalizedMatrix[$i][$j] = $matrix[$i][$j] / $columnSums[$j];
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
            $weights[] = $rowSum / $n;
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
            $lambdaMax += $sum / ($weights[$i] * $n); // Approximation
        }
        
        // More accurate Lambda Max calculation: Sum of (Column Sum * Priority Vector)
        // But let's stick to the standard Ax = lambda x approximation or consistency vector approach
        // Re-calculating proper lambdaMax:
        $lambdaMax = 0;
        // 1. Calculate weighted sum vector
        $weightedSumVector = [];
        for ($i=0; $i<$n; $i++) {
            $sum = 0;
            for ($j=0; $j<$n; $j++) {
                $sum += $matrix[$i][$j] * $weights[$j];
            }
            $weightedSumVector[$i] = $sum;
        }
        
        // 2. Average of (Weighted Sum / Weight)
        $sumRatios = 0;
        for ($i=0; $i<$n; $i++) {
            if ($weights[$i] > 0) {
                $sumRatios += $weightedSumVector[$i] / $weights[$i];
            }
        }
        $lambdaMax = $sumRatios / $n;


        // Calculate consistency index
        $ci = ($lambdaMax - $n) / ($n - 1);

        // Random index values (Saaty)
        $riValues = [
            1 => 0.00, 2 => 0.00, 3 => 0.58, 4 => 0.90, 5 => 1.12,
            6 => 1.24, 7 => 1.32, 8 => 1.41, 9 => 1.45, 10 => 1.49
        ];

        $ri = $riValues[$n] ?? 1.49;

        // Calculate consistency ratio
        $cr = ($ri > 0) ? $ci / $ri : 0;

        return $cr;
    }
}
