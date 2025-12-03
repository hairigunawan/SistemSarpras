<?php

namespace App\Services;

use App\Models\Criteria;
use App\Models\AhpMatrix;
use App\Models\CalculationResult;
use App\Helpers\AHPValidationHelper;
use Illuminate\Support\Str;

class AHPService
{
    private $criteria;
    private $comparisonMatrix;
    private $eigenVector;
    private $consistencyRatio;
    private $isConsistent;

    public function __construct()
    {
        $this->criteria = collect();
        $this->comparisonMatrix = [];
        $this->eigenVector = [];
        $this->consistencyRatio = 0;
        $this->isConsistent = false;
    }

    /**
     * Calculate AHP weights based on pairwise comparison matrices
     */
    public function calculate($criteria = null, $comparisonSession = null)
    {
        if ($criteria === null) {
            $this->criteria = Criteria::orderBy('order', 'asc')->get();
        } else {
            $this->criteria = collect($criteria);
        }

        if ($this->criteria->count() <= 1) {
            throw new \Exception('Minimum 2 criteria required for AHP calculation');
        }

        // Load comparison matrices
        $this->loadComparisonMatrix($comparisonSession);

        // Calculate comparison matrix if not loaded
        if (empty($this->comparisonMatrix)) {
            throw new \Exception('No comparison data found for AHP calculation');
        }

        // Calculate weights using eigenvalue method
        $this->calculateWeights();

        // Calculate consistency
        $this->calculateConsistency();

        return [
            'weights' => $this->eigenVector,
            'consistency_ratio' => $this->consistencyRatio,
            'is_consistent' => $this->isConsistent,
            'criteria' => $this->criteria,
            'matrix' => $this->comparisonMatrix
        ];
    }

    /**
     * Load comparison matrix from database
     */
    private function loadComparisonMatrix($session = null)
    {
        $session = $session ?: 'default_' . time();
        
        // Get all stored comparison matrices for the session
        $matrices = AhpMatrix::where('comparison_session', $session)->get();
        
        // Initialize comparison matrix
        $n = $this->criteria->count();
        $criteriaIds = $this->criteria->pluck('id')->toArray();
        
        // Create a zero matrix
        $this->comparisonMatrix = array_fill(0, $n, array_fill(0, $n, 0));
        
        // Fill the matrix with the stored values
        foreach ($this->criteria as $i => $criterion1) {
            foreach ($this->criteria as $j => $criterion2) {
                if ($i == $j) {
                    // Diagonal elements are always 1
                    $this->comparisonMatrix[$i][$j] = 1.0;
                } else {
                    // Look for the comparison in the database
                    $comparison = $matrices->first(function ($matrix) use ($criterion1, $criterion2) {
                        return ($matrix->criteria_id_1 == $criterion1->id && $matrix->criteria_id_2 == $criterion2->id);
                    });

                    if ($comparison) {
                        $this->comparisonMatrix[$i][$j] = floatval($comparison->value);
                    } else {
                        // If no comparison exists, assume equal importance (value = 1)
                        $this->comparisonMatrix[$i][$j] = 1.0;
                    }
                }
            }
        }
    }

    /**
     * Calculate weights using the eigenvalue method
     */
    private function calculateWeights()
    {
        $n = count($this->comparisonMatrix);
        
        // Normalize the matrix by column
        $normalizedMatrix = [];
        for ($j = 0; $j < $n; $j++) {  // For each column
            $columnSum = 0;
            for ($i = 0; $i < $n; $i++) {  // Sum of column j
                $columnSum += $this->comparisonMatrix[$i][$j];
            }
            
            for ($i = 0; $i < $n; $i++) {  // Normalize each element in column j
                $normalizedMatrix[$i][$j] = $this->comparisonMatrix[$i][$j] / $columnSum;
            }
        }
        
        // Calculate the eigen vector (weights) by averaging each row
        $this->eigenVector = [];
        for ($i = 0; $i < $n; $i++) {
            $rowAvg = array_sum($normalizedMatrix[$i]) / $n;
            $this->eigenVector[$i] = $rowAvg;
        }
        
        // Update the database with calculated weights
        foreach ($this->criteria as $index => $criterion) {
            $criterion->weight = $this->eigenVector[$index];
            $criterion->save();
        }
    }

    /**
     * Calculate consistency ratio
     */
    private function calculateConsistency()
    {
        $n = count($this->comparisonMatrix);
        
        if ($n <= 1) {
            $this->consistencyRatio = 0;
            $this->isConsistent = true;
            return;
        }
        
        // Calculate lambda max
        $lambdaMax = 0;
        for ($i = 0; $i < $n; $i++) {
            $sum = 0;
            for ($j = 0; $j < $n; $j++) {
                $sum += $this->comparisonMatrix[$i][$j] * $this->eigenVector[$j];
            }
            $lambdaMax += $sum / ($this->eigenVector[$i] * $n);
        }
        $lambdaMax /= $n;
        
        // Calculate consistency index
        $ci = ($lambdaMax - $n) / ($n - 1);
        
        // Random index based on matrix size
        $riValues = [
            1 => 0.00, 2 => 0.00, 3 => 0.58, 4 => 0.90, 5 => 1.12, 6 => 1.24, 
            7 => 1.32, 8 => 1.41, 9 => 1.45, 10 => 1.49, 11 => 1.51, 12 => 1.48, 
            13 => 1.56, 14 => 1.57, 15 => 1.59
        ];
        
        $ri = $riValues[$n] ?? 1.59; // Default to 1.59 if n is not in the table
        
        // Calculate consistency ratio
        $this->consistencyRatio = $ri == 0 ? 0 : $ci / $ri;
        
        // Check if the matrix is consistent (CR < 0.1)
        $this->isConsistent = AHPValidationHelper::isConsistent($this->consistencyRatio);

        // Log a warning if the consistency ratio is too high
        if (!$this->isConsistent) {
            \Log::warning('AHP Consistency Ratio is too high: ' . $this->consistencyRatio . '. Recommended to adjust comparisons.');
        }
    }

    /**
     * Save the calculation results to the database
     */
    public function saveCalculationResults($sessionId, $detailedResults = [])
    {
        $validationDetails = AHPValidationHelper::getConsistencyInterpretation($this->consistencyRatio);
        $suggestions = $this->isConsistent ? [] : AHPValidationHelper::getSuggestions($this->comparisonMatrix, $this->consistencyRatio);

        return CalculationResult::create([
            'session_id' => $sessionId,
            'method' => 'AHP',
            'criteria_weights' => array_combine(
                $this->criteria->pluck('id')->toArray(),
                $this->eigenVector
            ),
            'consistency_ratio' => $this->consistencyRatio,
            'is_consistent' => $this->isConsistent,
            'detailed_results' => array_merge($detailedResults, [
                'validation_details' => $validationDetails,
                'suggestions' => $suggestions
            ])
        ]);
    }
}