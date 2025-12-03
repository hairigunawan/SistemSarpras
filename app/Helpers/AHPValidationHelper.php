<?php

namespace App\Helpers;

class AHPValidationHelper
{
    /**
     * Check if consistency ratio is acceptable
     */
    public static function isConsistent($consistencyRatio)
    {
        return $consistencyRatio < 0.1;
    }

    /**
     * Get interpretation of consistency ratio
     */
    public static function getConsistencyInterpretation($consistencyRatio)
    {
        if ($consistencyRatio < 0.05) {
            return [
                'status' => 'excellent',
                'message' => 'Konsistensi sangat baik',
                'color' => 'green'
            ];
        } elseif ($consistencyRatio < 0.1) {
            return [
                'status' => 'good',
                'message' => 'Konsistensi dapat diterima',
                'color' => 'green'
            ];
        } elseif ($consistencyRatio < 0.15) {
            return [
                'status' => 'acceptable',
                'message' => 'Konsistensi mendekati batas, pertimbangkan untuk meninjau perbandingan',
                'color' => 'yellow'
            ];
        } else {
            return [
                'status' => 'poor',
                'message' => 'Konsistensi tidak dapat diterima, perlu menyesuaikan perbandingan',
                'color' => 'red'
            ];
        }
    }

    /**
     * Suggest improvements for inconsistent matrix
     */
    public static function getSuggestions($comparisonMatrix, $consistencyRatio)
    {
        $suggestions = [];
        
        if ($consistencyRatio >= 0.1) {
            $suggestions[] = "Consistency Ratio melebihi batas yang dianjurkan (0.1), pertimbangkan untuk meninjau kembali perbandingan berpasangan.";
        }
        
        // Additional logic for suggesting specific comparisons that might need revision
        // This is a simplified version; in practice, you might use algorithms to identify
        // the most inconsistent comparisons
        if (count($comparisonMatrix) > 0) {
            $suggestions[] = "Fokus pada perbandingan antar kriteria yang memiliki perbedaan penilaian ekstrem (misalnya perbandingan dengan nilai tinggi dan rendah secara bersamaan).";
        }
        
        return $suggestions;
    }
}