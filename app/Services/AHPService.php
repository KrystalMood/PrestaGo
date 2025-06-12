<?php

namespace App\Services;

class AHPService
{
    private $randomConsistencyIndex = [
        1 => 0,
        2 => 0,
        3 => 0.58,
        4 => 0.9,
        5 => 1.12,
        6 => 1.24,
        7 => 1.32,
        8 => 1.41,
        9 => 1.45,
        10 => 1.49
    ];

    public function calculateWeights(array $pairwiseMatrix, float $consistencyThreshold = 0.1): array
    {
        $n = count($pairwiseMatrix);
        
        $normalizedMatrix = $this->normalizeMatrix($pairwiseMatrix);
        
        $weights = $this->calculatePriorityVector($normalizedMatrix);
        
        $consistencyInfo = $this->checkConsistency($pairwiseMatrix, $weights);
        
        return [
            'weights' => $weights,
            'consistency_ratio' => $consistencyInfo['consistency_ratio'],
            'is_consistent' => $consistencyInfo['consistency_ratio'] <= $consistencyThreshold,
            'lambda_max' => $consistencyInfo['lambda_max'],
            'consistency_index' => $consistencyInfo['consistency_index'],
            'calculation_details' => [
                'pairwise_matrix' => $pairwiseMatrix,
                'normalized_matrix' => $normalizedMatrix,
                'priority_vector' => $weights,
                'consistency_check' => $consistencyInfo
            ]
        ];
    }

    public function createPairwiseMatrixFromPriorities(array $priorities): array
    {
        $criteria = array_keys($priorities);
        $n = count($criteria);
        $matrix = [];
        
        for ($i = 0; $i < $n; $i++) {
            $matrix[$i] = [];
            for ($j = 0; $j < $n; $j++) {
                if ($i == $j) {
                    $matrix[$i][$j] = 1;
                } else {
                    $priority_i = $priorities[$criteria[$i]];
                    $priority_j = $priorities[$criteria[$j]];
                    
                    if ($priority_i >= $priority_j) {
                        $matrix[$i][$j] = $priority_i / max(1, $priority_j);
                    } else {
                        $matrix[$i][$j] = 1 / ($priority_j / max(1, $priority_i));
                    }
                    
                    $matrix[$i][$j] = $this->roundToSaatyScale($matrix[$i][$j]);
                }
            }
        }
        
        return $matrix;
    }

    private function roundToSaatyScale(float $value): float
    {
        $saatyScale = [1, 2, 3, 4, 5, 6, 7, 8, 9];
        
        if ($value >= 1) {
            $closest = 1;
            $minDiff = abs($value - 1);
            
            foreach ($saatyScale as $scale) {
                $diff = abs($value - $scale);
                if ($diff < $minDiff) {
                    $minDiff = $diff;
                    $closest = $scale;
                }
            }
            
            return $closest;
        } else {
            $reciprocal = 1 / $value;
            $closest = 1;
            $minDiff = abs($reciprocal - 1);
            
            foreach ($saatyScale as $scale) {
                $diff = abs($reciprocal - $scale);
                if ($diff < $minDiff) {
                    $minDiff = $diff;
                    $closest = $scale;
                }
            }
            
            return 1 / $closest;
        }
    }

    private function normalizeMatrix(array $matrix): array
    {
        $n = count($matrix);
        $normalized = [];
        
        $colSums = array_fill(0, $n, 0);
        for ($i = 0; $i < $n; $i++) {
            for ($j = 0; $j < $n; $j++) {
                $colSums[$j] += $matrix[$i][$j];
            }
        }
        
        for ($i = 0; $i < $n; $i++) {
            $normalized[$i] = [];
            for ($j = 0; $j < $n; $j++) {
                $normalized[$i][$j] = $matrix[$i][$j] / $colSums[$j];
            }
        }
        
        return $normalized;
    }

    private function calculatePriorityVector(array $normalizedMatrix): array
    {
        $n = count($normalizedMatrix);
        $weights = [];
        
        for ($i = 0; $i < $n; $i++) {
            $rowSum = array_sum($normalizedMatrix[$i]);
            $weights[$i] = $rowSum / $n;
        }
        
        return $weights;
    }

    private function checkConsistency(array $matrix, array $weights): array
    {
        $n = count($matrix);
        
        $weightedSumVector = $this->calculateWeightedSumVector($matrix, $weights);
        
        $consistencyVector = [];
        for ($i = 0; $i < $n; $i++) {
            $consistencyVector[$i] = $weightedSumVector[$i] / $weights[$i];
        }
        
        $lambdaMax = array_sum($consistencyVector) / $n;
        
        $ci = ($lambdaMax - $n) / ($n - 1);
        
        $ri = $this->randomConsistencyIndex[$n] ?? 1.49;
        
        $cr = ($ri != 0) ? $ci / $ri : 0;
        
        return [
            'weighted_sum_vector' => $weightedSumVector,
            'consistency_vector' => $consistencyVector,
            'lambda_max' => $lambdaMax,
            'consistency_index' => $ci,
            'random_index' => $ri,
            'consistency_ratio' => $cr
        ];
    }

    private function calculateWeightedSumVector(array $matrix, array $weights): array
    {
        $n = count($matrix);
        $weightedSumVector = array_fill(0, $n, 0);
        
        for ($i = 0; $i < $n; $i++) {
            for ($j = 0; $j < $n; $j++) {
                $weightedSumVector[$i] += $matrix[$i][$j] * $weights[$j];
            }
        }
        
        return $weightedSumVector;
    }
    
    public function calculateFinalScore(array $factors, array $weights): float
    {
        $score = 0;
        $factorKeys = array_keys($factors);
        
        foreach ($factorKeys as $index => $factor) {
            $score += ($factors[$factor] / 100) * $weights[$index];
        }
        
        return $score * 100;
    }
} 