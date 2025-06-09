<?php

namespace App\Services;

class WPService
{
    public function calculateWeights(array $criteriaWeights): array
    {
        $totalWeight = array_sum($criteriaWeights);
        
        if ($totalWeight <= 0) {
            throw new \Exception("Total weight of criteria must be positive.");
        }
        
        $normalizedWeights = [];
        foreach ($criteriaWeights as $criterion => $weight) {
            $normalizedWeights[$criterion] = $weight / $totalWeight;
        }
        
        return $normalizedWeights;
    }
    
    public function calculateVectorS(array $alternatives, array $normalizedWeights): array
    {
        $vectorS_scores = [];
        
        foreach ($alternatives as $altKey => $criteriaValues) {
            $s_score = 1;
            foreach ($normalizedWeights as $criterion => $weight) {
                $value = $criteriaValues[$criterion] ?? 0;
                // Add a small epsilon to avoid log(0) issues if a value is 0
                $s_score *= pow($value + 1e-9, $weight);
            }
            $vectorS_scores[$altKey] = $s_score;
        }
        
        return $vectorS_scores;
    }

    public function calculateVectorV(array $vectorS_scores): array
    {
        $total_S = array_sum($vectorS_scores);
        
        if ($total_S == 0) {
            return array_fill_keys(array_keys($vectorS_scores), 0);
        }
        
        $vectorV_scores = [];
        foreach ($vectorS_scores as $altKey => $s_score) {
            $vectorV_scores[$altKey] = $s_score / $total_S;
        }
        
        return $vectorV_scores;
    }

    public function calculateWPScore(array $alternatives, array $normalizedWeights): array
    {
        return $this->calculateVectorS($alternatives, $normalizedWeights);
    }
    
    public function calculateFinalScore(array $factorScores, array $criteriaWeights): float
    {
        $normalizedWeights = $this->calculateWeights($criteriaWeights);
        $finalScore = 0;

        foreach ($normalizedWeights as $criterion => $weight) {
            $score = ($factorScores[$criterion] ?? 0) / 100;
            $finalScore += $score * $weight;
        }
        
        return $finalScore * 100;
    }
} 