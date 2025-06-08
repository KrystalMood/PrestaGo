<?php

namespace App\Services;

class WPService
{
    public function calculateWeights(array $criteria): array
    {
        $totalWeight = array_sum($criteria);
        $normalizedWeights = [];
        
        foreach ($criteria as $key => $weight) {
            $normalizedWeights[$key] = $weight / $totalWeight;
        }
        
        return $normalizedWeights;
    }
    
    public function calculateWPScore(array $alternatives, array $weights, array $criteriaTypes = []): array
    {
        $scores = [];
        
        if (empty($alternatives)) {
            \Log::warning('calculateWPScore called with empty alternatives array');
            return [0.01];
        }
        
        foreach ($alternatives as $altKey => $alternative) {
            $score = 1;
            $calculationDetails = [];
            
            \Log::info("Calculating WP score for alternative: {$altKey}");
            
            foreach ($alternative as $critKey => $value) {
                if (!isset($weights[$critKey])) {
                    \Log::warning("Weight not found for criterion '{$critKey}'", [
                        'available_weights' => array_keys($weights)
                    ]);
                    continue;
                }
                
                $weight = $weights[$critKey];
                
                if (isset($criteriaTypes[$critKey]) && $criteriaTypes[$critKey] === 'cost') {
                    $weight = -$weight;
                }
                
                if ($value <= 0) {
                    \Log::warning("Value for criterion '{$critKey}' is zero or negative: {$value}, using minimum value");
                    $value = 0.01;
                }
                
                $partialScore = pow($value, $weight);
                
                if (!is_finite($partialScore)) {
                    \Log::warning("Non-finite partial score for criterion '{$critKey}': using fallback", [
                        'value' => $value,
                        'weight' => $weight,
                        'partial_score' => $partialScore
                    ]);
                    $partialScore = 1;
                }
                
                $score *= $partialScore;
                $calculationDetails[$critKey] = [
                    'value' => $value,
                    'weight' => $weight,
                    'partial' => $partialScore
                ];
                
                \Log::info("Criterion: {$critKey}, Value: {$value}, Weight: {$weight}, Partial score: {$partialScore}");
            }
            
            if (!is_finite($score) || $score <= 0) {
                \Log::warning("Final score is non-finite or non-positive: {$score}, using minimum value", [
                    'calculation_details' => $calculationDetails
                ]);
                $score = 0.01;
            }
            
            \Log::info("Final WP score for alternative {$altKey}: {$score}");
            $scores[$altKey] = $score;
        }
        
        if (empty($scores)) {
            \Log::warning('No scores calculated in WP method');
            return [0.01];
        }
        
        $finalScores = array_values($scores);
        
        \Log::info("WP calculation completed with " . count($finalScores) . " scores", [
            'scores' => $finalScores
        ]);
        
        return $finalScores;
    }
    
    public function normalizeScores(array $scores): array
    {
        if (empty($scores)) {
            return [];
        }
        
        $maxScore = max($scores);
        $normalizedScores = [];
        
        if ($maxScore > 0) {
            foreach ($scores as $key => $score) {
                $normalizedScores[$key] = ($score / $maxScore) * 100;
            }
        } else {
            foreach ($scores as $key => $score) {
                $normalizedScores[$key] = 0;
            }
        }
        
        return $normalizedScores;
    }
} 