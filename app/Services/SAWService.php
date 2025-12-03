<?php

namespace App\Services;

use App\Models\Alternative;
use App\Models\Criteria;
use App\Models\CriteriaValue;
use App\Helpers\AHPValidationHelper;

class SAWService
{
    private $alternatives;
    private $criteria;
    private $criteriaWeights;
    private $normalizedMatrix;
    private $scores;

    public function __construct()
    {
        $this->alternatives = collect();
        $this->criteria = collect();
        $this->criteriaWeights = [];
        $this->normalizedMatrix = [];
        $this->scores = [];
    }

    /**
     * Calculate SAW scores based on alternatives and criteria
     */
    public function calculate($criteriaWeights = null)
    {
        // Load alternatives and criteria
        $this->alternatives = Alternative::where('is_active', true)->get();
        $this->criteria = Criteria::where('is_active', true)->orderBy('order', 'asc')->get();

        if ($this->alternatives->count() == 0 || $this->criteria->count() == 0) {
            throw new \Exception('Minimum 1 alternative and 1 criterion required for SAW calculation');
        }

        // Get criteria weights
        if ($criteriaWeights === null) {
            // Use the weights from the criteria table (typically from AHP)
            $this->criteriaWeights = $this->criteria->pluck('weight', 'id')->toArray();
        } else {
            $this->criteriaWeights = $criteriaWeights;
        }

        // Normalize the decision matrix
        $this->normalizeMatrix();

        // Calculate the preference scores
        $this->calculateScores();

        // Rank the alternatives
        $this->rankAlternatives();

        return [
            'scores' => $this->scores,
            'alternatives' => $this->alternatives,
            'criteria' => $this->criteria,
            'weights' => $this->criteriaWeights,
            'normalized_matrix' => $this->normalizedMatrix
        ];
    }

    /**
     * Normalize the decision matrix
     */
    private function normalizeMatrix()
    {
        $this->normalizedMatrix = [];

        foreach ($this->criteria as $criteria) {
            $column = [];
            $columnValues = [];

            // Get all values for this criterion
            foreach ($this->alternatives as $alternative) {
                $criteriaValue = $this->getCriteriaValue($alternative->id, $criteria->id);
                
                if ($criteriaValue !== null) {
                    $columnValues[] = floatval($criteriaValue->value);
                } else {
                    $columnValues[] = 0; // Default value if not found
                }
            }

            // Find max and min values for normalization
            if ($criteria->type === 'benefit') {
                $maxValue = max($columnValues);
                $minValue = min($columnValues);
                
                foreach ($columnValues as $value) {
                    // For benefit criteria: normalize by dividing by max
                    $normalizedValue = $maxValue == 0 ? 0 : $value / $maxValue;
                    $column[] = $normalizedValue;
                }
            } else { // cost criteria
                $maxValue = max($columnValues);
                $minValue = min($columnValues);
                
                foreach ($columnValues as $value) {
                    // For cost criteria: use min/max normalization
                    $normalizedValue = ($maxValue == 0) ? 0 : $minValue / $value;
                    // Avoid division by zero
                    $column[] = ($value == 0) ? 0 : $normalizedValue;
                }
            }

            $this->normalizedMatrix[$criteria->id] = $column;
        }
    }

    /**
     * Calculate the preference scores using SAW formula: Sum(Wj * Xij)
     */
    private function calculateScores()
    {
        $this->scores = [];

        foreach ($this->alternatives as $index => $alternative) {
            $score = 0;

            foreach ($this->criteria as $criteria) {
                $weight = $this->criteriaWeights[$criteria->id] ?? 0;
                $normalizedValue = $this->normalizedMatrix[$criteria->id][$index] ?? 0;

                $score += $weight * $normalizedValue;
            }

            $this->scores[$alternative->id] = $score;
            
            // Update the alternative with the calculated score
            $alternative->final_score = $score;
            $alternative->save();
        }
    }

    /**
     * Rank alternatives based on their scores (highest to lowest)
     */
    private function rankAlternatives()
    {
        // Sort scores in descending order (highest first)
        arsort($this->scores);

        $rank = 1;
        foreach ($this->scores as $alternativeId => $score) {
            $alternative = $this->alternatives->firstWhere('id', $alternativeId);
            if ($alternative) {
                $alternative->rank = $rank;
                $alternative->save();
                $rank++;
            }
        }
    }

    /**
     * Get criteria value for a specific alternative and criterion
     */
    private function getCriteriaValue($alternativeId, $criteriaId)
    {
        return CriteriaValue::where('alternative_id', $alternativeId)
            ->where('criteria_id', $criteriaId)
            ->first();
    }

    /**
     * Save the calculation results to the database
     */
    public function saveCalculationResults($sessionId, $ahpResults = [])
    {
        $detailedResults = [
            'normalized_matrix' => $this->normalizedMatrix,
            'ahp_results' => $ahpResults
        ];

        // If AHP results are provided, include validation details
        if (!empty($ahpResults) && isset($ahpResults['id'])) {
            $ahpCalculation = \App\Models\CalculationResult::find($ahpResults['id']);
            if ($ahpCalculation && $ahpCalculation->method === 'AHP') {
                $detailedResults['ahp_validation'] = AHPValidationHelper::getConsistencyInterpretation($ahpCalculation->consistency_ratio);
            }
        }

        return \App\Models\CalculationResult::create([
            'session_id' => $sessionId,
            'method' => 'SAW',
            'criteria_weights' => $this->criteriaWeights,
            'alternative_scores' => $this->scores,
            'ranking' => array_keys($this->scores), // Array of alternative IDs in order of ranking
            'detailed_results' => $detailedResults
        ]);
    }
}