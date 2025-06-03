<?php

namespace App\Services;

use App\Models\AchievementModel;
use App\Models\CompetitionModel;
use App\Models\SubCompetitionModel;
use App\Models\UserModel;
use App\Models\RecommendationModel;
use App\Models\CompetitionParticipantModel;
use App\Models\AHPResultModel;
use App\Models\WPResultModel;
use Illuminate\Support\Collection;
use Carbon\Carbon;
use Illuminate\Support\Facades\Log;

class RecommendationService
{
    protected $ahpService;
    
    public function __construct(AHPService $ahpService)
    {
        $this->ahpService = $ahpService;
    }
    
    public function generateRecommendations(array $params): Collection
    {
        $method = $params['dss_method'] ?? 'ahp';
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
            
            $score = 0;
            $ahpResult = null;
            
            $ahpResult = $this->performAHPCalculation($student, $competition, $matchFactors, $params);
            $score = $ahpResult->final_score * 100;
            
            Log::info("RecommendationService: Student ID {$student->id} - Competition ID {$competition->id} - Calculated Score: {$score} - Threshold: {$threshold}", [
                'student_id' => $student->id,
                'student_name' => $student->name,
                'competition_id' => $competition->id,
                'competition_name' => $competition->name,
                'factors' => $matchFactors,
                'score' => $score,
                'threshold' => $threshold,
                'method' => 'ahp'
            ]);
            
            if ($score >= $threshold) {
                $scores->push([
                    'student' => $student,
                    'competition' => $competition,
                    'score' => $score,
                    'method' => 'ahp',
                    'factors' => $matchFactors,
                    'ahp_result' => $ahpResult
                ]);
            }
        }
        
        return $scores
            ->sortByDesc('score')
            ->take($maxRecommendations)
            ->map(function ($data) {
                $recommendation = new RecommendationModel([
                    'user_id' => $data['student']->id,
                    'competition_id' => $data['competition']->id,
                    'match_score' => $data['score'],
                    'recommended_by' => 'system',
                    'status' => 'pending',
                    'match_factors' => json_encode($data['factors'])
                ]);
                
                if ($data['ahp_result']) {
                    $recommendation->ahp_result_id = $data['ahp_result']->id;
                }
                
                return $recommendation;
            });
    }
    
    private function performAHPCalculation(
        UserModel $student, 
        CompetitionModel $competition, 
        array $matchFactors, 
        array $params
    ): AHPResultModel {
        $priorities = [
            'skills' => $params['ahp_priority_skills'] ?? 6,
            'achievements' => $params['ahp_priority_achievements'] ?? 5,
            'interests' => $params['ahp_priority_interests'] ?? 6,
            'deadline' => $params['ahp_priority_deadline'] ?? 4,
            'competition_level' => $params['ahp_priority_competition_level'] ?? 7,
        ];
        
        $pairwiseMatrix = $this->ahpService->createPairwiseMatrixFromPriorities($priorities);
        
        $consistencyThreshold = $params['ahp_consistency_threshold'] ?? 0.1;
        $ahpResult = $this->ahpService->calculateWeights($pairwiseMatrix, $consistencyThreshold);
        
        $finalScore = $this->ahpService->calculateFinalScore($matchFactors, $ahpResult['weights']) / 100;
        
        $boostedScore = min($finalScore * 1.15, 1.0);
        
        Log::info("[ScoreDebug] Original score: {$finalScore}, Boosted score: {$boostedScore}");
        
        $ahpResultModel = AHPResultModel::updateOrCreate(
            [
                'user_id' => $student->id,
                'competition_id' => $competition->id,
                'calculation_type' => 'recommendation'
            ],
            [
                'final_score' => $boostedScore,
                'consistency_ratio' => $ahpResult['consistency_ratio'],
                'is_consistent' => $ahpResult['is_consistent'],
                'calculation_details' => json_encode($ahpResult['calculation_details']),
                'calculated_at' => Carbon::now()
            ]
        );
        
        return $ahpResultModel;
    }
    
    private function calculateMatchFactors(UserModel $student, CompetitionModel $competition): array
    {
        $factors = [
            'skills' => 0,
            'achievements' => 0,
            'interests' => 0,
            'deadline' => 0,
            'competition_level' => 0
        ];
        
        $studentSkills = $student->skills()->get();
        $competitionSkills = $competition->skills()->get();
        
        Log::info("[FactorDebug] Student {$student->id} Skills Count: " . $studentSkills->count());
        Log::info("[FactorDebug] Competition {$competition->id} Skills Count: " . $competitionSkills->count());

        if ($competitionSkills->count() > 0) {
            $skillMatches = 0;
            $totalSkillImportance = 0;
            
            $relatedSkillCategories = [
                'Programming Language' => ['Web Technology', 'Backend', 'Frontend', 'Mobile Development'],
                'Web Framework' => ['Web Technology', 'Frontend', 'Backend'],
                'Web Technology' => ['Programming Language', 'Web Framework', 'Frontend', 'Backend'],
                'Frontend' => ['Web Technology', 'Web Framework', 'Design'],
                'Backend' => ['Web Technology', 'Web Framework', 'Programming Language', 'Database'],
                'Mobile Development' => ['Programming Language', 'Frontend'],
                'Design' => ['Frontend', 'UI/UX Design'],
                'UI/UX Design' => ['Design', 'Frontend'],
                'Database' => ['Backend', 'Data Science'],
                'Machine Learning' => ['Data Science', 'AI', 'Programming Language'],
                'Data Science' => ['Machine Learning', 'AI', 'Database', 'Programming Language'],
                'DevOps' => ['Cloud Computing', 'Infrastructure as Code', 'CI/CD'],
                'Security' => ['Network Security', 'Cybersecurity'],
                'Testing' => ['QA', 'Development'],
                'Cloud Computing' => ['DevOps', 'Infrastructure as Code'],
                'Game Development' => ['Programming Language', '3D Modeling'],
                'IoT' => ['Embedded Systems', 'Programming Language', 'Hardware'],
            ];
            
            $studentSkillCategories = [];
            foreach ($studentSkills as $skill) {
                $studentSkillCategories[$skill->id] = $skill->category;
                Log::info("[FactorDebug] StudentSkill ID {$skill->id}, Name: {$skill->name}, Category: {$skill->category}");
            }
            
            foreach ($competitionSkills as $reqSkill) {
                Log::info("[FactorDebug] CompSkill ID {$reqSkill->id}, Name: {$reqSkill->name}, Category: {$reqSkill->category}, Importance: {$reqSkill->importance_level}");
                $totalSkillImportance += $reqSkill->importance_level;
                
                $exactMatch = false;
                $categoryMatch = false;
                $relatedCategoryMatch = false;
                $matchValue = 0;
                
                foreach ($studentSkills as $studentSkill) {
                    if ($reqSkill->id === $studentSkill->id) {
                        Log::info("[FactorDebug] EXACT MATCH: CompSkill {$reqSkill->id} & StudentSkill {$studentSkill->id}");
                        $proficiencyPercentage = ($studentSkill->pivot->proficiency_level / 5);
                        $matchValue = ($reqSkill->importance_level * $proficiencyPercentage);
                        $exactMatch = true;
                        break;
                    }
                    
                    if (!$exactMatch && $reqSkill->category === $studentSkill->category) {
                        Log::info("[FactorDebug] CATEGORY MATCH: CompSkill {$reqSkill->name} category ({$reqSkill->category}) matches StudentSkill {$studentSkill->name} category");
                        $proficiencyPercentage = ($studentSkill->pivot->proficiency_level / 5) * 0.7;
                        $matchValueForCategory = ($reqSkill->importance_level * $proficiencyPercentage);
                        
                        if ($matchValueForCategory > $matchValue) {
                            $matchValue = $matchValueForCategory;
                            $categoryMatch = true;
                        }
                    }
                    
                    if (!$exactMatch && !$categoryMatch) {
                        $relatedCategories = $relatedSkillCategories[$reqSkill->category] ?? [];
                        
                        if (in_array($studentSkill->category, $relatedCategories)) {
                            Log::info("[FactorDebug] RELATED CATEGORY MATCH: CompSkill {$reqSkill->name} category ({$reqSkill->category}) is related to StudentSkill {$studentSkill->name} category ({$studentSkill->category})");
                            $proficiencyPercentage = ($studentSkill->pivot->proficiency_level / 5) * 0.4;
                            $matchValueForRelatedCategory = ($reqSkill->importance_level * $proficiencyPercentage);
                            
                            if ($matchValueForRelatedCategory > $matchValue) {
                                $matchValue = $matchValueForRelatedCategory;
                                $relatedCategoryMatch = true;
                            }
                        }
                    }
                }
                
                if ($exactMatch || $categoryMatch || $relatedCategoryMatch) {
                    $skillMatches += $matchValue;
                    Log::info("[FactorDebug] Added match value: {$matchValue}, Total now: {$skillMatches}");
                }
            }
            
            $maxPossibleSkillScore = $competitionSkills->sum(function($skill) {
                return $skill->importance_level;
            });

            if ($maxPossibleSkillScore > 0) {
                $rawSkillFactor = ($skillMatches / $maxPossibleSkillScore) * 100;
                $boostedSkillFactor = min(round($rawSkillFactor * 3.0), 100);
                $factors['skills'] = $boostedSkillFactor;
                
                Log::info("[FactorDebug] Final skill factor: {$factors['skills']}, Raw factor: {$rawSkillFactor}, MaxPossible: {$maxPossibleSkillScore}, Matches: {$skillMatches}");
            }
        }
        
        $achievements = AchievementModel::where('user_id', $student->id)
            ->where('status', 'verified')
            ->get();
            
        if ($achievements->count() > 0) {
            $achievementScore = 0;
            $achievementCounts = [];
            
            foreach ($achievements as $achievement) {
                $levelRaw = strtolower($achievement->level ?? 'other');
                
                $level = match(true) {
                    str_contains($levelRaw, 'internation') || str_contains($levelRaw, 'global') => 'international',
                    str_contains($levelRaw, 'nation') || str_contains($levelRaw, 'nasional') => 'national',
                    str_contains($levelRaw, 'provin') || str_contains($levelRaw, 'state') => 'provincial',
                    str_contains($levelRaw, 'region') || str_contains($levelRaw, 'daerah') || str_contains($levelRaw, 'city') => 'regional',
                    str_contains($levelRaw, 'univ') || str_contains($levelRaw, 'campus') => 'university',
                    default => 'other'
                };
                
                $levelMultiplier = match($level) {
                    'international' => 9.0,
                    'national' => 6.0,
                    'provincial' => 4.0,
                    'regional' => 3.0,
                    'university' => 2.0,
                    default => 1.0
                };
                
                $achievementScore += $levelMultiplier;
                
                if (!isset($achievementCounts[$level])) {
                    $achievementCounts[$level] = 0;
                }
                $achievementCounts[$level]++;
                
                Log::info("[FactorDebug] Achievement: {$achievement->name}, Raw Level: {$levelRaw}, Normalized Level: {$level}, Multiplier: {$levelMultiplier}");
            }
            
            $maxRawAchievementScore = 16;
            $factors['achievements'] = min(round(($achievementScore / $maxRawAchievementScore) * 100), 100);
            
            Log::info("[FactorDebug] Achievement counts by level: " . json_encode($achievementCounts));
            Log::info("[FactorDebug] Raw achievement score: {$achievementScore}, Max score: {$maxRawAchievementScore}, Final factor: {$factors['achievements']}");
        }
        
        $categoryToInterestMap = [
            'Programming' => ['Pengembangan Web', 'Pengembangan Mobile', 'Programming', 'DevOps', 'Pengembangan Aplikasi'],
            'Pemrograman' => ['Pengembangan Web', 'Pengembangan Mobile', 'Programming', 'DevOps'],
            'Desain UI/UX' => ['Desain UI/UX', 'Augmented/Virtual Reality'],
            'Pengembangan Aplikasi' => ['Pengembangan Web', 'Pengembangan Mobile', 'Pengembangan Aplikasi'],
            'Kecerdasan Buatan' => ['Kecerdasan Buatan', 'Data Science', 'Machine Learning'],
            'IoT' => ['Pengembangan IoT', 'Jaringan Komputer'],
            'Keamanan Siber' => ['Keamanan Informasi', 'Keamanan Siber'],
            'Bisnis TI' => ['Entrepreneurship', 'Project Management', 'Bisnis TI'],
            'Karya Tulis' => ['Research', 'Karya Tulis'],
            'Research' => ['Research', 'Data Science', 'Kecerdasan Buatan'],
        ];
        
        $studentInterests = $student->interests()->get();
        $competitionInterests = $competition->interests()->get();
        
        $subCompetitions = SubCompetitionModel::where('competition_id', $competition->id)->with('category')->get();
        $categoryNames = [];
        foreach ($subCompetitions as $subComp) {
            if ($subComp->category && $subComp->category->name) {
                $categoryNames[] = $subComp->category->name;
            }
        }
        $categoryNames = array_unique($categoryNames);
        
        Log::info("[FactorDebug] Student {$student->id} Interests Count: " . $studentInterests->count());
        Log::info("[FactorDebug] Competition {$competition->id} Interests Count: " . $competitionInterests->count());
        Log::info("[FactorDebug] Competition {$competition->id} Categories: " . implode(', ', $categoryNames));

        $interestMatchScore = 0;
        $maxPossibleInterestScore = 0;
        $interestMatches = [];

        if ($competitionInterests->count() > 0) {
            foreach ($competitionInterests as $compInterest) {
                Log::info("[FactorDebug] CompInterest ID {$compInterest->id}, Name: {$compInterest->name}, Relevance: {$compInterest->pivot->relevance_score}, Importance: {$compInterest->pivot->importance_factor}");
                $maxPossibleInterestScore += (5 * ($compInterest->pivot->relevance_score ?? 1) * ($compInterest->pivot->importance_factor ?? 1));

                foreach ($studentInterests as $studInterest) {
                    Log::info("[FactorDebug] StudentInterest ID {$studInterest->id}, Name: {$studInterest->name}, Level: {$studInterest->pivot->interest_level}");
                    if ($compInterest->id === $studInterest->id) {
                        Log::info("[FactorDebug] MATCH: CompInterest {$compInterest->id} & StudentInterest {$studInterest->id}");
                        $studentLevel = $studInterest->pivot->interest_level ?? 1;
                        $relevance = $compInterest->pivot->relevance_score ?? 1;
                        $importance = $compInterest->pivot->importance_factor ?? 1;
                        $interestMatchScore += ($studentLevel * $relevance * $importance);
                        $interestMatches[] = $studInterest->name;
                        break; 
                    }
                }
            }
        }
        
        if (!empty($categoryNames)) {
            $mappedInterests = [];
            
            foreach ($categoryNames as $categoryName) {
                if (isset($categoryToInterestMap[$categoryName])) {
                    $mappedInterests = array_merge($mappedInterests, $categoryToInterestMap[$categoryName]);
                }
            }
            
            $mappedInterests = array_unique($mappedInterests);
            Log::info("[FactorDebug] Mapped interests from categories: " . implode(', ', $mappedInterests));
            
            foreach ($studentInterests as $studInterest) {
                Log::info("[FactorDebug] Checking StudentInterest: {$studInterest->name} against mapped interests");
                if (in_array($studInterest->name, $mappedInterests) && !in_array($studInterest->name, $interestMatches)) {
                    $studentLevel = $studInterest->pivot->interest_level ?? 1;
                    $defaultImportance = 1;
                    $defaultRelevance = 1;
                    
                    Log::info("[FactorDebug] CATEGORY MATCH: StudentInterest {$studInterest->name} matches category mapping");
                    $interestMatchScore += ($studentLevel * $defaultImportance * $defaultRelevance);
                    $maxPossibleInterestScore += (5 * $defaultImportance * $defaultRelevance);
                    $interestMatches[] = $studInterest->name;
                } else {
                    Log::info("[FactorDebug] NO MATCH: StudentInterest {$studInterest->name} does not match any mapped interest");
                }
            }
        }

        if ($maxPossibleInterestScore > 0) {
            $factors['interests'] = round(($interestMatchScore / $maxPossibleInterestScore) * 100);
        }
        
        // Calculate deadline factor
        if ($competition->registration_end) {
            $now = Carbon::now();
            $registrationEnd = Carbon::parse($competition->registration_end);
            $daysUntilDeadline = $now->diffInDays($registrationEnd, false);
            
            Log::info("[FactorDebug] Competition {$competition->id} Days until deadline: {$daysUntilDeadline}");
            
            if ($daysUntilDeadline < 0) {
                // Registration already closed
                $factors['deadline'] = 0;
            } else if ($daysUntilDeadline <= 7) {
                // Urgent (less than a week)
                $factors['deadline'] = 100;
            } else if ($daysUntilDeadline <= 14) {
                // Soon (1-2 weeks)
                $factors['deadline'] = 85;
            } else if ($daysUntilDeadline <= 30) {
                // Medium (2-4 weeks)
                $factors['deadline'] = 70;
            } else if ($daysUntilDeadline <= 60) {
                // Comfortable (1-2 months)
                $factors['deadline'] = 50;
            } else {
                // Far away (more than 2 months)
                $factors['deadline'] = 30;
            }
            
            Log::info("[FactorDebug] Deadline factor: {$factors['deadline']}");
        }
        
        // Calculate competition level factor
        $levelScores = [
            'international' => 100,
            'national' => 85,
            'regional' => 70,
            'provincial' => 60,
            'university' => 40,
            'internal' => 30
        ];
        
        $level = strtolower($competition->level ?? 'internal');
        $factors['competition_level'] = $levelScores[$level] ?? 30;
        
        Log::info("[FactorDebug] Competition {$competition->id} Level: {$level}, Factor: {$factors['competition_level']}");
        
        Log::info("RecommendationService: Calculated factors for Student ID {$student->id} & Competition ID {$competition->id}", [
            'student_id' => $student->id,
            'competition_id' => $competition->id,
            'factors' => $factors,
            'interest_matches' => $interestMatches ?? []
        ]);

        return $factors;
    }
    
    private function getEligibleStudents(?int $programStudiId): Collection
    {
        $query = UserModel::whereHas('level', function($query) {
            $query->where('level_kode', 'MHS');
        })->with(['skills', 'programStudi', 'interests']);
            
        if ($programStudiId) {
            $query->where('program_studi_id', $programStudiId);
        }
        
        return $query->get();
    }
    
    private function getTargetCompetitions(?int $competitionId): Collection
    {
        if ($competitionId) {
            $competitions = CompetitionModel::where('id', $competitionId)->get();
        } else {
            $competitions = CompetitionModel::where(function($q) {
                $q->where('status', 'upcoming')
                  ->orWhere('status', 'active');
            })->get();
        }
        
        foreach ($competitions as $competition) {
            $competition->setRelation('skills', $competition->skills()->get());
            $competition->setRelation('interests', $competition->interests()->get());
        }
        
        return $competitions;
    }
} 