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
    protected $wpService;
    
    public function __construct(AHPService $ahpService, WPService $wpService)
    {
        $this->ahpService = $ahpService;
        $this->wpService = $wpService;
    }
    
    public function generateRecommendations(array $params): Collection
    {
        $method = $params['dss_method'] ?? 'ahp';
        $threshold = $params['threshold'] ?? 60;
        $maxRecommendations = $params['max_recommendations'] ?? 3;
        
        $recommendations = collect();
        
        Log::info("Recommendation generation started", [
            'params' => $params,
            'user_id' => auth()->id(),
            'method' => $method,
            'ajax' => request()->ajax()
        ]);
        
        if ($method === 'hybrid') {
            $studentParams = $params;
            $lecturerParams = $params;
            
            $students = $this->getEligibleStudents($params['program_studi_id'] ?? null);
            $lecturers = $this->getEligibleLecturers($params['program_studi_id'] ?? null);
            $competitions = $this->getTargetCompetitions($params['competition_id'] ?? null);
            
            Log::info("Found {$students->count()} eligible students for Hybrid method");
            Log::info("Found {$lecturers->count()} eligible lecturers for Hybrid method");
            Log::info("Found {$competitions->count()} eligible competitions");
            
            if ($students->isEmpty() && $lecturers->isEmpty()) {
                Log::warning("No eligible users found for recommendation generation");
                return $recommendations;
            }
            
            if ($competitions->isEmpty()) {
                Log::warning("No eligible competitions found for recommendation generation");
                return $recommendations;
            }
            
            foreach ($students as $student) {
                $studentRecommendations = $this->generateStudentRecommendations(
                    $student,
                    $competitions,
                    'ahp',
                    $threshold,
                    $maxRecommendations,
                    $studentParams
                );
                
                $recommendations = $recommendations->merge($studentRecommendations);
            }
            
            foreach ($lecturers as $lecturer) {
                $lecturerRecommendations = $this->generateLecturerCompetitionRecommendations(
                    $lecturer,
                    $competitions,
                    $threshold,
                    $maxRecommendations,
                    $lecturerParams
                );
                
                $recommendations = $recommendations->merge($lecturerRecommendations);
            }
        } elseif ($method === 'wp') {
            $lecturers = $this->getEligibleLecturers($params['program_studi_id'] ?? null);
            $competitions = $this->getTargetCompetitions($params['competition_id'] ?? null);
            
            Log::info("Found {$lecturers->count()} eligible lecturers for WP method");
            Log::info("Found {$competitions->count()} eligible competitions");
            
            if ($lecturers->isEmpty()) {
                Log::warning("No eligible lecturers found for recommendation generation");
                return $recommendations;
            }
            
            if ($competitions->isEmpty()) {
                Log::warning("No eligible competitions found for recommendation generation");
                return $recommendations;
            }
            
            foreach ($lecturers as $lecturer) {
                Log::info("Processing lecturer: ID {$lecturer->id}, Name: {$lecturer->name}");
                $hasSkills = $lecturer->skills()->count() > 0;
                $hasInterests = $lecturer->interests()->count() > 0;
                Log::info("Lecturer has skills: " . ($hasSkills ? 'Yes' : 'No') . ", has interests: " . ($hasInterests ? 'Yes' : 'No'));

                foreach ($competitions as $competition) {
                    try {
                        Log::info("Processing competition for lecturer: Competition ID {$competition->id}, Name: {$competition->name}");
                        
                        $hasCompSkills = $competition->skills()->count() > 0;
                        $hasCompInterests = $competition->interests()->count() > 0;
                        Log::info("Competition has skills: " . ($hasCompSkills ? 'Yes' : 'No') . ", has interests: " . ($hasCompInterests ? 'Yes' : 'No'));
                        
                        $matchFactors = $this->calculateLecturerMatchFactors($lecturer, $competition);
                        
                        Log::info("Match factors for lecturer ID {$lecturer->id} and competition ID {$competition->id}:", $matchFactors);
                        
                        $hasNonZeroFactors = false;
                        foreach ($matchFactors as $factor => $value) {
                            if ($value > 0) {
                                $hasNonZeroFactors = true;
                                break;
                            }
                        }
                        
                        if (!$hasNonZeroFactors) {
                            Log::warning("All match factors are zero for lecturer ID {$lecturer->id} and competition ID {$competition->id}");
                            continue;
                        }
                        
                        $lecturerRecommendation = new RecommendationModel();
                        $lecturerRecommendation->user_id = $lecturer->id;
                        $lecturerRecommendation->competition_id = $competition->id;
                        $lecturerRecommendation->recommended_by = 'system';
                        $lecturerRecommendation->for_lecturer = true;
                        $lecturerRecommendation->match_factors = json_encode($matchFactors);
                        $lecturerRecommendation->calculation_method = 'wp';
                        
                        try {
                            $weights = $this->getLecturerCriteriaWeights();
                            $score = $this->calculateWeightedAverage($matchFactors, $weights);
                            
                            $lecturerRecommendation->match_score = $score;
                            
                            Log::info("Weighted Average Score for lecturer ID {$lecturer->id} and competition ID {$competition->id}: {$score}");
                            
                            if ($score >= $threshold) {
                                $recommendations->push($lecturerRecommendation);
                                Log::info("Added recommendation for lecturer ID {$lecturer->id} and competition ID {$competition->id} with score {$score}");
                            }
                        } catch (\Exception $e) {
                            Log::error("Error in weighted average calculation for lecturer ID {$lecturer->id} and competition ID {$competition->id}: " . $e->getMessage(), [
                                'exception' => $e,
                                'lecturer_id' => $lecturer->id,
                                'competition_id' => $competition->id
                            ]);
                        }
                    } catch (\Exception $e) {
                        Log::error("Error processing competition for lecturer: " . $e->getMessage(), [
                            'exception' => $e,
                            'lecturer_id' => $lecturer->id,
                            'competition_id' => $competition->id
                        ]);
                    }
                }
            }
            
            // Sort recommendations by score and limit to max_recommendations
            $recommendations = $recommendations->sortByDesc('match_score')->take($maxRecommendations);
            
            Log::info("Generated {$recommendations->count()} total WP recommendations after filtering by threshold and sorting");
        } else {
            // Default AHP method is for students
            $students = $this->getEligibleStudents($params['program_studi_id'] ?? null);
            $competitions = $this->getTargetCompetitions($params['competition_id'] ?? null);
            
            Log::info("Found {$students->count()} eligible students for AHP method");
            Log::info("Found {$competitions->count()} eligible competitions");
            
            if ($students->isEmpty()) {
                Log::warning("No eligible students found for recommendation generation");
                return $recommendations;
            }
            
            if ($competitions->isEmpty()) {
                Log::warning("No eligible competitions found for recommendation generation");
                return $recommendations;
            }
            
            foreach ($students as $student) {
                $studentRecommendations = $this->generateStudentRecommendations(
                    $student,
                    $competitions,
                    'ahp',
                    $threshold,
                    $maxRecommendations,
                    $params
                );
                
                $recommendations = $recommendations->merge($studentRecommendations);
            }
        }
        
        Log::info("Generated {$recommendations->count()} total recommendations");
        
        return $recommendations;
    }
    
    public function generateLecturerRecommendations(array $params): Collection
    {
        $threshold = $params['threshold'] ?? 60;
        $maxRecommendations = $params['max_recommendations'] ?? 3;
        
        $lecturers = $this->getEligibleLecturers($params['program_studi_id'] ?? null);
        $competitions = $this->getTargetCompetitions($params['competition_id'] ?? null);
        
        $recommendations = collect();
        
        foreach ($lecturers as $lecturer) {
            $lecturerRecommendations = $this->generateLecturerCompetitionRecommendations(
                $lecturer,
                $competitions,
                $threshold,
                $maxRecommendations,
                $params
            );
            
            $recommendations = $recommendations->merge($lecturerRecommendations);
        }
        
        return $recommendations;
    }
    
    private function generateLecturerCompetitionRecommendations(
        UserModel $lecturer,
        Collection $competitions,
        float $threshold,
        int $maxRecommendations,
        array $params
    ): Collection {
        $allScores = [];
        
        foreach ($competitions as $competition) {
            $matchFactors = $this->calculateLecturerMatchFactors($lecturer, $competition);
            
            $weights = $this->getLecturerCriteriaWeights();
            $score = $this->calculateWeightedAverage($matchFactors, $weights);
            
            if ($score >= $threshold) {
                $recommendation = new RecommendationModel();
                $recommendation->user_id = $lecturer->id;
                $recommendation->competition_id = $competition->id;
                $recommendation->recommended_by = 'system';
                $recommendation->for_lecturer = true;
                $recommendation->match_factors = json_encode($matchFactors);
                $recommendation->match_score = $score;
                $recommendation->calculation_method = 'wp';
                $allScores[] = $recommendation;
            }
        }
        
        return collect($allScores)
            ->sortByDesc('match_score')
            ->take($maxRecommendations);
    }
    
    private function getEligibleLecturers($programStudiId = null): Collection
    {
        $query = UserModel::whereHas('level', function($query) {
            $query->where('level_kode', 'DSN');
        });
        
        if ($programStudiId) {
            $query->where('program_studi_id', $programStudiId);
        }
        
        return $query->with(['skills', 'interests'])->get();
    }
    
    private function calculateLecturerMatchFactors(UserModel $lecturer, CompetitionModel $competition): array
    {
        $factors = [
            'skills' => 0,
            'interests' => 0,
            'competition_level' => 0,
            'activity_rating' => 0,
            'deadline' => 0
        ];
        
        $lecturerSkills = $lecturer->skills()->get();
        $competitionSkills = $competition->skills()->get();
        
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

            foreach ($competitionSkills as $reqSkill) {
                $importanceLevel = $reqSkill->pivot->importance_level ?? 3;
                $totalSkillImportance += $importanceLevel;

                $exactMatch = false;
                $categoryMatch = false;
                $relatedCategoryMatch = false;
                $matchValue = 0;

                foreach ($lecturerSkills as $lecturerSkill) {
                    if ($reqSkill->id === $lecturerSkill->id) {
                        $proficiencyPercentage = ($lecturerSkill->pivot->proficiency_level / 5) * 100;
                        $matchValue = ($importanceLevel * $proficiencyPercentage) / 5;
                        $exactMatch = true;
                        break;
                    }

                    if (!$exactMatch && $reqSkill->category === $lecturerSkill->category) {
                        $proficiencyPercentage = ($lecturerSkill->pivot->proficiency_level / 5) * 100 * 0.7; // lower weight
                        $matchValue = max($matchValue, ($importanceLevel * $proficiencyPercentage) / 5);
                        $categoryMatch = true;
                    }

                    if (!$exactMatch && !$categoryMatch) {
                        $relatedCategories = $relatedSkillCategories[$reqSkill->category] ?? [];
                        if (in_array($lecturerSkill->category, $relatedCategories)) {
                            $proficiencyPercentage = ($lecturerSkill->pivot->proficiency_level / 5) * 100 * 0.4; // lowest weight
                            $matchValue = max($matchValue, ($importanceLevel * $proficiencyPercentage) / 5);
                            $relatedCategoryMatch = true;
                        }
                    }
                }

                if ($exactMatch || $categoryMatch || $relatedCategoryMatch) {
                    $skillMatches += $matchValue;
                }
            }
            
            if ($totalSkillImportance > 0) {
                $maxPossibleSkillScore = $totalSkillImportance * 20;
                $normalizedSkillScore = ($skillMatches / $maxPossibleSkillScore) * 100;
                $boostedSkillScore = min(round($normalizedSkillScore * 2.0), 100);
                $factors['skills'] = $boostedSkillScore;
            }
        }
        
        $lecturerInterests = $lecturer->interests()->get();
        $competitionInterests = $competition->interests()->get();
        
        $interestMatches = 0;
        $totalInterestImportance = 0;

        if ($competitionInterests->count() > 0) {
            foreach ($competitionInterests as $reqInterest) {
                $totalInterestImportance += $reqInterest->pivot->importance_factor ?? 3;
                
                foreach ($lecturerInterests as $lecturerInterest) {
                    if ($reqInterest->id === $lecturerInterest->id) {
                        $interestPercentage = ($lecturerInterest->pivot->interest_level / 5) * 100;
                        $importanceFactor = $reqInterest->pivot->importance_factor ?? 3;
                        $interestMatches += ($importanceFactor * $interestPercentage) / 5;
                        break;
                    }
                }
            }
        }
        
        $subCompetitions = \App\Models\SubCompetitionModel::where('competition_id', $competition->id)
            ->with('category')
            ->get();

        $categoryNames = [];
        foreach ($subCompetitions as $subComp) {
            if ($subComp->category && $subComp->category->name) {
                $categoryNames[] = $subComp->category->name;
            }
        }
        $categoryNames = array_unique($categoryNames);

        if (!empty($categoryNames)) {
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

            $mappedInterests = [];
            foreach ($categoryNames as $catName) {
                if (isset($categoryToInterestMap[$catName])) {
                    $mappedInterests = array_merge($mappedInterests, $categoryToInterestMap[$catName]);
                }
            }
            $mappedInterests = array_unique($mappedInterests);

            foreach ($lecturerInterests as $lecInterest) {
                if (in_array($lecInterest->name, $mappedInterests)) {
                    $alreadyMatched = false;
                    foreach ($competitionInterests as $reqInterest) {
                        if ($reqInterest->id === $lecInterest->id) {
                            $alreadyMatched = true; break;
                        }
                    }
                    if (!$alreadyMatched) {
                        $interestLevel = $lecInterest->pivot->interest_level ?? 1;
                        $defaultImportance = 1;
                        $defaultRelevance = 1;
                        $interestMatches += ($interestLevel * $defaultImportance * $defaultRelevance);
                        $totalInterestImportance += (5 * $defaultImportance * $defaultRelevance);
                    }
                }
            }
        }
        
        if ($totalInterestImportance > 0) {
            $normalizedInterestScore = ($interestMatches / $totalInterestImportance) * 100;
            $boostedInterestScore = min(round($normalizedInterestScore * 2.0), 100);
            $factors['interests'] = $boostedInterestScore;
        }
        
        $levelMatchPercentages = [
            'international' => 100,
            'national' => 90,
            'regional' => 80,
            'provincial' => 70,
            'university' => 60,
            'internal' => 50,
        ];
        
        $factors['competition_level'] = $levelMatchPercentages[$competition->level] ?? 70;
        
        if ($competition->registration_end) {
            $now = Carbon::now();
            $registrationEnd = Carbon::parse($competition->registration_end);
            $daysUntilDeadline = $now->diffInDays($registrationEnd, false);
            
            if ($daysUntilDeadline < 0) {
                $factors['deadline'] = 0;
            } else if ($daysUntilDeadline <= 7) {
                $factors['deadline'] = 100;
            } else if ($daysUntilDeadline <= 14) {
                $factors['deadline'] = 85;
            } else if ($daysUntilDeadline <= 30) {
                $factors['deadline'] = 70;
            } else if ($daysUntilDeadline <= 60) {
                $factors['deadline'] = 50;
            } else {
                $factors['deadline'] = 30;
            }
        }
        
        $averageRating = $lecturer->getAverageActivityRating();
        $ratingCount = $lecturer->getTotalRatingCount();
        
        $factors['activity_rating'] = ($averageRating / 5) * 100;
        
        if ($ratingCount > 0) {
            $reliabilityFactor = min(1, $ratingCount / 10);
            $baseActivityRating = $factors['activity_rating'];
            $defaultActivityRating = 50;
            $factors['activity_rating'] = ($baseActivityRating * $reliabilityFactor) + 
                                         ($defaultActivityRating * (1 - $reliabilityFactor));
        } else {
            $factors['activity_rating'] = 50;
        }
        
        return $factors;
    }
    
    private function performWPCalculation(
        UserModel $lecturer, 
        CompetitionModel $competition, 
        array $matchFactors, 
        array $params
    ): WPResultModel {
        $requiredFactors = ['skills', 'interests', 'competition_level', 'deadline', 'activity_rating'];
        foreach ($requiredFactors as $factor) {
            if (!isset($matchFactors[$factor])) {
                Log::warning("Missing required factor '{$factor}' for WP calculation", [
                    'lecturer_id' => $lecturer->id,
                    'competition_id' => $competition->id,
                    'available_factors' => array_keys($matchFactors)
                ]);
                $matchFactors[$factor] = 50;
            }
        }
        
        $criteria = [
            'skills' => isset($params['wp_weight_skills']) ? (float)($params['wp_weight_skills'] * 100) : 30,
            'interests' => isset($params['wp_weight_interests']) ? (float)($params['wp_weight_interests'] * 100) : 20,
            'competition_level' => isset($params['wp_weight_competition_level']) ? (float)($params['wp_weight_competition_level'] * 100) : 20,
            'deadline' => isset($params['wp_weight_deadline']) ? (float)($params['wp_weight_deadline'] * 100) : 20,
            'activity_rating' => isset($params['wp_weight_activity_rating']) ? (float)($params['wp_weight_activity_rating'] * 100) : 10
        ];
        
        foreach ($criteria as $key => $value) {
            if ($value <= 0) {
                Log::warning("Criteria '{$key}' has zero or negative weight, setting to default", [
                    'original_value' => $value
                ]);
                $criteria[$key] = 10;
            }
        }
        
        Log::info("WP criteria weights for lecturer ID {$lecturer->id} and competition ID {$competition->id}:", $criteria);
        
        $weights = $this->wpService->calculateWeights($criteria);
        
        $alternative = [];
        foreach ($requiredFactors as $factor) {
            $normalizedValue = max(0.01, $matchFactors[$factor] / 100);
            $alternative[$factor] = $normalizedValue;
        }
        
        Log::info("Normalized alternative values:", $alternative);
        
        try {
            $calculationResult = $this->wpService->calculateWPScore(['lecturer' => $alternative], $weights);
            
            $score = 0;
            if (!empty($calculationResult) && isset($calculationResult[0])) {
                $score = $calculationResult[0];
                if ($score > 1) {
                    Log::warning("WP calculation returned score > 1, normalizing", [
                        'original_score' => $score
                    ]);
                    $score = min(1, $score);
                } else if ($score <= 0) {
                    Log::warning("WP calculation returned zero or negative score, setting minimum", [
                        'original_score' => $score
                    ]);
                    $score = 0.01;
                }
            } else {
                Log::error('WP calculation returned empty result', [
                    'lecturer_id' => $lecturer->id,
                    'competition_id' => $competition->id,
                    'matchFactors' => $matchFactors,
                    'weights' => $weights,
                    'alternative' => $alternative
                ]);
                $score = 0.01;
            }
        } catch (\Exception $e) {
            Log::error('Exception in WP calculation: ' . $e->getMessage(), [
                'lecturer_id' => $lecturer->id,
                'competition_id' => $competition->id,
                'exception' => $e,
                'matchFactors' => $matchFactors,
                'weights' => $weights,
                'alternative' => $alternative
            ]);
            $score = 0.01; 
        }
        
        $wpResult = WPResultModel::updateOrCreate(
            [
                'user_id' => $lecturer->id,
                'competition_id' => $competition->id,
                'calculation_type' => 'lecturer_recommendation'
            ],
            [
                'final_score' => $score,
                'calculation_details' => json_encode([
                    'criteria' => $criteria,
                    'weights' => $weights,
                    'factors' => $matchFactors,
                    'normalized_factors' => $alternative,
                ]),
                'calculated_at' => now(),
            ]
        );
        
        return $wpResult;
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
        
        if ($competitions->isEmpty()) {
            Log::warning("No eligible competitions found for student ID {$student->id}");
            return $scores;
        }
        
        Log::info("Processing {$competitions->count()} competitions for student ID {$student->id}");
        
        foreach ($competitions as $competition) {
            $matchFactors = $this->calculateMatchFactors($student, $competition);
            
            Log::info("Match factors for student ID {$student->id} and competition ID {$competition->id}:", [
                'skills' => $matchFactors['skills'] ?? 0,
                'achievements' => $matchFactors['achievements'] ?? 0,
                'interests' => $matchFactors['interests'] ?? 0,
                'deadline' => $matchFactors['deadline'] ?? 0,
                'competition_level' => $matchFactors['competition_level'] ?? 0
            ]);
            
            $score = 0;
            $ahpResult = null;
            
            try {
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
            } catch (\Exception $e) {
                Log::error("Error calculating AHP score for student ID {$student->id} and competition ID {$competition->id}: " . $e->getMessage(), [
                    'exception' => $e,
                    'student_id' => $student->id,
                    'competition_id' => $competition->id,
                    'match_factors' => $matchFactors
                ]);
            }
        }
        
        Log::info("Found {$scores->count()} competitions matching threshold {$threshold} for student ID {$student->id}");
        
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
                    'match_factors' => json_encode($data['factors']),
                    'for_lecturer' => false,
                    'calculation_method' => $data['method']
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
                $importanceLevel = $reqSkill->pivot->importance_level ?? 3;
                $totalSkillImportance += $importanceLevel;

                $exactMatch = false;
                $categoryMatch = false;
                $relatedCategoryMatch = false;
                $matchValue = 0;

                foreach ($studentSkills as $studentSkill) {
                    // Exact skill ID match
                    if ($reqSkill->id === $studentSkill->id) {
                        $proficiencyPercentage = ($studentSkill->pivot->proficiency_level / 5) * 100;
                        $matchValue = ($importanceLevel * $proficiencyPercentage) / 5;
                        $exactMatch = true;
                        break;
                    }

                    // Same category match
                    if (!$exactMatch && $reqSkill->category === $studentSkill->category) {
                        $proficiencyPercentage = ($studentSkill->pivot->proficiency_level / 5) * 100 * 0.7; // lower weight
                        $matchValue = max($matchValue, ($importanceLevel * $proficiencyPercentage) / 5);
                        $categoryMatch = true;
                    }

                    // Related category match
                    if (!$exactMatch && !$categoryMatch) {
                        $relatedCategories = $relatedSkillCategories[$reqSkill->category] ?? [];
                        if (in_array($studentSkill->category, $relatedCategories)) {
                            $proficiencyPercentage = ($studentSkill->pivot->proficiency_level / 5) * 100 * 0.4; // lowest weight
                            $matchValue = max($matchValue, ($importanceLevel * $proficiencyPercentage) / 5);
                            $relatedCategoryMatch = true;
                        }
                    }
                }

                if ($exactMatch || $categoryMatch || $relatedCategoryMatch) {
                    $skillMatches += $matchValue;
                }
            }
            
            $maxPossibleSkillScore = $competitionSkills->sum(function($skill) {
                return $skill->pivot->importance_level ?? 3;
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
            $normalizedInterestScore = ($interestMatchScore / $maxPossibleInterestScore) * 100;
            $boostedInterestScore = min(round($normalizedInterestScore * 2.0), 100);
            $factors['interests'] = $boostedInterestScore;
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
    
    private function getEligibleStudents($programStudiId = null): Collection
    {
        $query = UserModel::whereHas('level', function($query) {
            $query->where('level_kode', 'MHS');
        });
        
        if ($programStudiId) {
            $query->where('program_studi_id', $programStudiId);
        }
        
        return $query->with(['skills', 'interests'])->get();
    }
    
    private function getTargetCompetitions($competitionId = null): Collection
    {
        // Debug to see what statuses are actually in the database
        $allStatuses = CompetitionModel::distinct()->pluck('status')->toArray();
        Log::info("Available competition statuses in database: " . implode(', ', $allStatuses));
        
        // Count competitions before filtering
        $totalCount = CompetitionModel::count();
        Log::info("Total competitions in database: {$totalCount}");
        
        // Modified query to be more inclusive with status
        $query = CompetitionModel::where(function($q) {
            $q->where('status', 'active')
              ->orWhere('status', 'open')
              ->orWhere('status', 'upcoming')
              ->orWhere('status', 'ongoing');
        })->where('verified', true);
        
        if ($competitionId) {
            $query->where('id', $competitionId);
        }
        
        $competitions = $query->with(['interests'])->get();
        
        Log::info("Found {$competitions->count()} eligible competitions after filtering");
        
        // If still no competitions, fall back to a more lenient query
        if ($competitions->count() == 0) {
            Log::info("No competitions found with standard filters, using more lenient filtering");
            
            $query = CompetitionModel::where('verified', true);
            
            if ($competitionId) {
                $query->where('id', $competitionId);
            }
            
            $competitions = $query->with(['interests'])->get();
            Log::info("Found {$competitions->count()} competitions with lenient filtering");
        }
        
        return $competitions;
    }
    
    /**
     * Calculate a simple weighted average score.
     *
     * @param array $factors Key-value array of factor scores (0-100).
     * @param array $weights Key-value array of weights (summing to 1).
     * @return float The final score (0-100).
     */
    private function calculateWeightedAverage(array $factors, array $weights): float
    {
        $totalScore = 0.0;
        
        foreach ($weights as $criterion => $weight) {
            $score = $factors[$criterion] ?? 0;
            // Ensure score is within 0-100
            $score = max(0, min(100, $score));
            $totalScore += ($score / 100) * $weight;
        }
        
        // Return score as a percentage
        return round($totalScore * 100, 2);
    }
    
    /**
     * Get the criteria weights for lecturer recommendations.
     *
     * @return array
     */
    private function getLecturerCriteriaWeights(): array
    {
        return [
            'skills' => 0.3,
            'interests' => 0.2,
            'competition_level' => 0.2,
            'deadline' => 0.2,
            'activity_rating' => 0.1
        ];
    }
} 