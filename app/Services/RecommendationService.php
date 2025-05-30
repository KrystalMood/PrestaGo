<?php

namespace App\Services;

use App\Models\CompetitionModel;
use App\Models\UserModel;
use App\Models\RecommendationModel;
use App\Models\AchievementModel;
use App\Models\CompetitionParticipantModel;
use Illuminate\Support\Collection;

class RecommendationService
{
    public function generateRecommendations(array $params): Collection
    {
        $method = $params['dss_method'] ?? 'combined';
        $threshold = $params['threshold'] ?? 60;
        $maxRecommendations = $params['max_recommendations'] ?? 3;
        
        $students = $this->getEligibleStudents($params['program_studi_id'] ?? null);
        
        $competitions = $this->getTargetCompetitions($params['competition_id'] ?? null);
        
        $recommendations = collect();
        
        foreach ($students as $student) {
            $studentRecommendations = $this->generateStudentRecommendations(
                $student,
                $competitions,
                $method,
                $threshold,
                $maxRecommendations,
                $params
            );
            
            $recommendations = $recommendations->merge($studentRecommendations);
        }
        
        return $recommendations;
    }
    
    private function generateStudentRecommendations(
        UserModel $student,
        Collection $competitions,
        string $method,
        float $threshold,
        int $maxRecommendations,
        array $params
    ): Collection {
        $scores = collect();
        
        foreach ($competitions as $competition) {
            $matchFactors = $this->calculateMatchFactors($student, $competition);
            
            $score = match($method) {
                'ahp' => $this->calculateAHPScore($matchFactors, $params),
                'wp' => $this->calculateWPScore($matchFactors, $params),
                'combined' => $this->calculateCombinedScore($matchFactors, $params),
                default => 0
            };
            
            if ($score >= $threshold) {
                $scores->push([
                    'student' => $student,
                    'competition' => $competition,
                    'score' => $score,
                    'method' => $method,
                    'factors' => $matchFactors
                ]);
            }
        }
        
        return $scores
            ->sortByDesc('score')
            ->take($maxRecommendations)
            ->map(function ($data) {
                return new RecommendationModel([
                    'user_id' => $data['student']->id,
                    'competition_id' => $data['competition']->id,
                    'match_score' => $data['score'],
                    'recommended_by' => 'system',
                    'status' => 'pending',
                    'match_factors' => json_encode($data['factors'])
                ]);
            });
    }
    
    private function calculateAHPScore(array $factors, array $params): float
    {
        $skillsPriority = $params['ahp_priority_skills'] ?? 5;
        $achievementsPriority = $params['ahp_priority_achievements'] ?? 4;
        $academicPriority = $params['ahp_priority_academic'] ?? 3;
        $experiencePriority = $params['ahp_priority_interests'] ?? 4;
        
        $total = $skillsPriority + $achievementsPriority + $academicPriority + $experiencePriority;
        $weights = [
            'skills' => $skillsPriority / $total,
            'achievements' => $achievementsPriority / $total,
            'academic' => $academicPriority / $total,
            'experience' => $experiencePriority / $total
        ];
        
        $finalScore = 0;
        foreach ($weights as $criterion => $weight) {
            $finalScore += ($factors[$criterion] ?? 0) * $weight;
        }
        
        return $finalScore;
    }
    
    private function calculateWPScore(array $factors, array $params): float
    {
        $weights = [
            'skills' => ($params['weight_skills'] ?? 30) / 100,
            'achievements' => ($params['weight_achievements'] ?? 25) / 100,
            'academic' => ($params['weight_academic'] ?? 25) / 100,
            'experience' => ($params['weight_interests'] ?? 20) / 100
        ];
        
        $vectorS = 1;
        foreach ($weights as $criterion => $weight) {
            $score = ($factors[$criterion] ?? 0) / 100; 
            $vectorS *= pow($score, $weight);
        }
        
        return $vectorS * 100; 
    }
    
    private function calculateCombinedScore(array $factors, array $params): float
    {
        $ahpScore = $this->calculateAHPScore($factors, $params);
        $wpScore = $this->calculateWPScore($factors, $params);
        
        return ($ahpScore + $wpScore) / 2;
    }
    
    private function calculateMatchFactors(UserModel $student, CompetitionModel $competition): array
    {
        $factors = [
            'skills' => 0,
            'achievements' => 0,
            'academic' => 0,
            'experience' => 0
        ];
        
        $studentSkills = $student->skills()->get();
        $competitionSkills = $competition->skills()->get();
        
        if ($competitionSkills->count() > 0) {
            $skillMatches = 0;
            $totalSkillImportance = 0;
            
            foreach ($competitionSkills as $reqSkill) {
                $totalSkillImportance += $reqSkill->pivot->importance_level;
                
                foreach ($studentSkills as $studentSkill) {
                    if ($reqSkill->id === $studentSkill->id) {
                        $proficiencyPercentage = ($studentSkill->pivot->proficiency_level / 5) * 100;
                        $skillMatches += ($reqSkill->pivot->importance_level * $proficiencyPercentage) / 5;
                        break;
                    }
                }
            }
            
            if ($totalSkillImportance > 0) {
                $factors['skills'] = round(($skillMatches / $totalSkillImportance) * 100);
            }
        }
        
        $achievements = AchievementModel::where('user_id', $student->id)
            ->where('status', 'verified')
            ->get();
            
        if ($achievements->count() > 0) {
            $achievementScore = 0;
            
            foreach ($achievements as $achievement) {
                $relevance = 0.5;
                
                if (isset($achievement->type) && isset($competition->type) && $achievement->type === $competition->type) {
                    $relevance = 1.0;
                }
                
                $levelMultiplier = match($achievement->level) {
                    'international' => 3.0,
                    'national' => 2.0,
                    'provincial' => 1.5,
                    'regional' => 1.0,
                    default => 0.5
                };
                
                $achievementScore += $relevance * $levelMultiplier;
            }
            
            $maxScore = 10;
            $factors['achievements'] = min(round(($achievementScore / $maxScore) * 100), 100);
        }
        
        $factors['academic'] = rand(50, 90); 
        
        $participations = CompetitionParticipantModel::where('user_id', $student->id)->get();
        
        if ($participations->count() > 0) {
            $experienceScore = min($participations->count() * 20, 100);
            $factors['experience'] = $experienceScore;
        }
        
        return $factors;
    }
    
    private function getEligibleStudents(?int $programStudiId): Collection
    {
        $query = UserModel::whereHas('level', function($query) {
            $query->where('level_kode', 'MHS');
        })->with(['skills', 'program_studi']);
            
        if ($programStudiId) {
            $query->where('program_studi_id', $programStudiId);
        }
        
        return $query->get();
    }
    
    private function getTargetCompetitions(?int $competitionId): Collection
    {
        $query = CompetitionModel::with('skills');
        
        if ($competitionId) {
            return $query->where('id', $competitionId)->get();
        }
        
        return $query->where(function($q) {
            $q->where('status', 'upcoming')
              ->orWhere('status', 'active');
        })->get();
    }
} 