<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\RecommendationModel;
use App\Models\CompetitionModel;
use App\Models\UserModel;
use App\Models\StudyProgramModel;
use App\Models\AchievementModel;
use App\Models\CompetitionParticipantModel;
use App\Models\SkillModel;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;
use App\Services\RecommendationService;
use App\Models\Competition;
use App\Models\ProgramStudi;
use App\Models\Recommendation;
use App\Models\User;
use App\Models\Participation;
use App\Services\AHPService;
use App\Models\AHPResultModel;
use App\Services\WPService;
use App\Models\WPResultModel;

class RecommendationController extends Controller
{
    protected $recommendationService;
    
    public function __construct(RecommendationService $recommendationService)
    {
        $this->recommendationService = $recommendationService;
    }

    public function index(Request $request)
    {
        $query = RecommendationModel::with(['user', 'user.programStudi', 'competition'])
            ->orderBy('created_at', 'desc');
            
        if ($request->has('search') && $request->search) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->whereHas('user', function($user) use ($search) {
                    $user->where('name', 'like', "%{$search}%")
                         ->orWhere('nim', 'like', "%{$search}%");
                })
                ->orWhereHas('competition', function($comp) use ($search) {
                    $comp->where('name', 'like', "%{$search}%")
                         ->orWhere('organizer', 'like', "%{$search}%");
                });
            });
        }
        
        if ($request->has('filter')) {
            $filter = $request->filter;
            
            if (in_array($filter, ['pending', 'accepted', 'rejected'])) {
                $query->where('status', $filter);
            }
            
            if (in_array($filter, ['system', 'lecturer', 'admin'])) {
                $query->where('recommended_by', $filter);
            }
            
            if ($filter === 'high_match') {
                $query->where('match_score', '>=', 80);
            } elseif ($filter === 'medium_match') {
                $query->where('match_score', '>=', 50)
                      ->where('match_score', '<', 80);
            } elseif ($filter === 'low_match') {
                $query->where('match_score', '<', 50);
            }
        }
        
        $recommendations = $query->paginate(10)->withQueryString();
        
        return view('admin.recommendations.index', compact('recommendations'));
    }

    public function create()
    {
        $students = UserModel::whereHas('level', function($query) {
            $query->where('level_kode', 'MHS');
        })->orderBy('name')->get();
        
        $competitions = CompetitionModel::where(function($query) {
            $query->where('status', 'upcoming')
                  ->orWhere('status', 'active');
        })->orderBy('name')->get();
        
        return view('admin.recommendations.create', compact('students', 'competitions'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'user_id' => 'required|exists:users,id',
            'competition_id' => 'required|exists:competitions,id',
            'match_score' => 'required|numeric|min:0|max:100',
            'recommended_by' => 'required|in:system,lecturer,admin',
            'notes' => 'nullable|string',
            'factor_skills' => 'nullable|numeric|min:0|max:100',
            'factor_achievements' => 'nullable|numeric|min:0|max:100',
            'factor_academic' => 'nullable|numeric|min:0|max:100',
            'factor_experience' => 'nullable|numeric|min:0|max:100',
        ]);
        
        $recommendation = new RecommendationModel();
        $recommendation->user_id = $validated['user_id'];
        $recommendation->competition_id = $validated['competition_id'];
        $recommendation->match_score = $validated['match_score'];
        $recommendation->recommended_by = $validated['recommended_by'];
        $recommendation->status = 'pending';
        $recommendation->notes = $validated['notes'] ?? null;
        
        if (isset($validated['factor_skills'])) {
            $factors = [
                'skills' => $validated['factor_skills'],
                'achievements' => $validated['factor_achievements'] ?? 0,
                'academic' => $validated['factor_academic'] ?? 0,
                'experience' => $validated['factor_experience'] ?? 0
            ];
            $recommendation->match_factors = json_encode($factors);
        }
        
        $recommendation->save();
        
        return redirect()->route('admin.recommendations.index')
            ->with('success', 'Rekomendasi berhasil dibuat!');
    }

    public function show($id)
    {
        try {
            $recommendation = RecommendationModel::findOrFail($id);
            
            $user = UserModel::findOrFail($recommendation->user_id);
            $competition = CompetitionModel::findOrFail($recommendation->competition_id);
            
            $userSkills = $user->skills()->get();
            $userInterests = $user->interests()->get();
            $userAchievements = $user->achievements()->get();
            
            $competitionSkills = $competition->skills()->get();
            $competitionInterests = $competition->interests()->get();
            
            $recommendation->user = $user;
            $recommendation->competition = $competition;
            $recommendation->user->skills = $userSkills;
            $recommendation->user->interests = $userInterests;
            $recommendation->user->achievements = $userAchievements;
            $recommendation->competition->skills = $competitionSkills;
            $recommendation->competition->interests = $competitionInterests;
            $recommendation->competition->program_study = $competition->programStudy;
            
            $participations = CompetitionParticipantModel::where('user_id', $user->id)->get();
            foreach ($participations as $participation) {
                $participationCompetition = CompetitionModel::find($participation->competition_id);
                $participation->competition = $participationCompetition;
            }
            $recommendation->user->participations = $participations;
            
            $match_factors = [
                'skill_match' => 0,
                'interest_match' => 0,
                'achievement_match' => 0,
                'experience_match' => 0
            ];
            
            if ($recommendation->for_lecturer && !$recommendation->wp_result_id) {
                $this->generateWPCalculationForRecommendation($recommendation);
            } elseif (!$recommendation->for_lecturer && !$recommendation->ahp_result_id) {
                $this->generateAHPCalculationForRecommendation($recommendation);
            }
            
            if ($recommendation->for_lecturer && $recommendation->wp_result_id) {
                $wpDetails = json_decode($recommendation->wpResult->calculation_details, true);
                if (isset($wpDetails['factor_scores'])) {
                    foreach ($wpDetails['factor_scores'] as $factor => $score) {
                        if (array_key_exists($factor, $match_factors)) {
                            $match_factors[$factor] = $score;
                        }
                    }
                }
            } elseif (!$recommendation->for_lecturer && $recommendation->ahp_result_id) {
                $ahpDetails = json_decode($recommendation->ahpResult->calculation_details, true);
                if (isset($ahpDetails['factor_scores'])) {
                    foreach ($ahpDetails['factor_scores'] as $factor => $score) {
                        if (array_key_exists($factor, $match_factors)) {
                            $match_factors[$factor] = $score;
                        }
                    }
                }
            }
            
            return view('admin.recommendations.show', compact('recommendation', 'match_factors'));
        } catch (\Exception $e) {
            \Log::error('Error showing recommendation: ' . $e->getMessage(), [
                'exception' => $e,
                'id' => $id
            ]);
            
            return redirect()->route('admin.recommendations.index')
                ->with('error', 'Rekomendasi tidak ditemukan atau terjadi kesalahan.');
        }
    }

    public function updateStatus(Request $request, $id)
    {
        $request->validate([
            'status' => 'required|in:accepted,rejected'
        ]);
        
        \Log::info('Updating recommendation status: ' . $request->status . ' for ID: ' . $id);
        
        $recommendation = RecommendationModel::findOrFail($id);
        $recommendation->status = $request->status;
        $recommendation->save();
        
        \Log::info('Recommendation after save: ', $recommendation->toArray());
        
        $statusText = $request->status === 'accepted' ? 'diterima' : 'ditolak';
        
        return redirect()->back()
            ->with('success', "Rekomendasi telah {$statusText}!");
    }

    public function automatic()
    {
        $competitions = CompetitionModel::all();
            
        $programs = StudyProgramModel::all();
        
        $latest_recommendations = RecommendationModel::with(['user', 'competition'])
            ->whereNotNull('id')
            ->orderBy('created_at', 'desc')
            ->limit(5)
            ->get();
        
        $lecturers = UserModel::whereHas('level', function($query) {
            $query->where('level_kode', 'DSN');
        })->orderBy('name')->get();
        
        return view('admin.recommendations.automatic', compact('competitions', 'programs', 'latest_recommendations', 'lecturers'));
    }

    public function generate(Request $request)
    {
        $validated = $request->validate([
            'competition_id' => 'nullable|exists:competitions,id',
            'program_studi_id' => 'nullable|exists:program_studi,id',
            'dss_method' => 'required|in:ahp,wp,hybrid',
            'threshold' => 'required|numeric|min:0|max:100',
            'max_recommendations' => 'required|integer|min:1|max:10',
            
            'ahp_priority_skills' => 'nullable|numeric|min:1|max:9',
            'ahp_priority_achievements' => 'nullable|numeric|min:1|max:9',
            'ahp_priority_interests' => 'nullable|numeric|min:1|max:9',
            'ahp_priority_deadline' => 'nullable|numeric|min:1|max:9',
            'ahp_priority_competition_level' => 'nullable|numeric|min:1|max:9',
            'ahp_consistency_threshold' => 'nullable|numeric|min:0.01|max:0.5',
            
            'wp_weight_skills' => 'nullable|numeric|min:0.1|max:1.0',
            'wp_weight_interests' => 'nullable|numeric|min:0.1|max:1.0',
            'wp_weight_competition_level' => 'nullable|numeric|min:0.1|max:1.0',
            'wp_weight_deadline' => 'nullable|numeric|min:0.1|max:1.0',
            'wp_weight_activity_rating' => 'nullable|numeric|min:0.1|max:1.0',
        ]);
        
        try {
            $recommendations = $this->recommendationService->generateRecommendations($validated);
            
            if ($recommendations->isEmpty()) {
                if ($request->ajax()) {
                    return response()->json([
                        'success' => false,
                        'message' => 'Tidak ada rekomendasi yang dapat dihasilkan. Pastikan terdapat kompetisi aktif yang terverifikasi dalam sistem atau coba kurangi nilai ambang batas (threshold).'
                    ], 200);
                }
                
                return back()->with('error', 'Tidak ada rekomendasi yang dapat dihasilkan. Pastikan terdapat kompetisi aktif yang terverifikasi dalam sistem atau coba kurangi nilai ambang batas (threshold).');
            }
            
            $formattedRecommendations = [];
            
            foreach ($recommendations as $recommendation) {
                $user = $recommendation->user_id ? UserModel::find($recommendation->user_id) : null;
                
                if (!$user) continue;
                
                $competition = $recommendation->competition_id ? CompetitionModel::find($recommendation->competition_id) : null;
                
                if (!$competition) continue;
                
                $formattedRecommendations[] = [
                    'user_id' => $user->id,
                    'user_name' => $user->name,
                    'user_identifier' => $recommendation->for_lecturer ? ($user->nip ?? 'N/A') : ($user->nim ?? 'N/A'),
                    'user_type' => $recommendation->for_lecturer ? 'Dosen' : 'Mahasiswa',
                    'program_studi' => $user->programStudi->name ?? 'N/A',
                    'competition_id' => $competition->id,
                    'competition_name' => $competition->name,
                    'competition_level' => ucfirst($competition->level ?? 'N/A'),
                    'match_score' => $recommendation->match_score,
                    'factors' => json_decode($recommendation->match_factors, true) ?? [],
                    'calculation_method' => $recommendation->calculation_method ?? $validated['dss_method']
                ];
            }
            
            usort($formattedRecommendations, function ($a, $b) {
                $groupA = $a['calculation_method'] === 'ahp' ? 0 : ($a['calculation_method'] === 'wp' ? 1 : 2);
                $groupB = $b['calculation_method'] === 'ahp' ? 0 : ($b['calculation_method'] === 'wp' ? 1 : 2);

                if ($groupA === $groupB) {
                    return $b['match_score'] <=> $a['match_score'];
                }

                return $groupA <=> $groupB;
            });
            
            $request->session()->put('generated_recommendations', $formattedRecommendations);
            
            if ($request->ajax()) {
                return response()->json([
                    'success' => true,
                    'message' => 'Berhasil menghasilkan ' . count($formattedRecommendations) . ' rekomendasi',
                    'count' => count($formattedRecommendations)
                ]);
            }
            
            $latest_recommendations = RecommendationModel::with(['user', 'competition'])
                ->whereNotNull('id')
                ->orderBy('created_at', 'desc')
                ->limit(5)
                ->get();
                
            return back()->with('success', 'Berhasil menghasilkan ' . count($formattedRecommendations) . ' rekomendasi!')
                        ->with('latest_recommendations', $latest_recommendations);
        } catch (\Exception $e) {
            \Log::error('Error generating recommendations: ' . $e->getMessage(), [
                'exception' => $e,
                'params' => $validated
            ]);
            
            if ($request->ajax()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Terjadi kesalahan saat menghasilkan rekomendasi: ' . $e->getMessage()
                ], 500);
            }
            
            return back()->with('error', 'Terjadi kesalahan saat menghasilkan rekomendasi: ' . $e->getMessage());
        }
    }

    public function saveGenerated(Request $request)
    {
        $validated = $request->validate([
            'user_id' => 'required|exists:users,id',
            'competition_id' => 'required|exists:competitions,id',
            'match_score' => 'required|numeric|min:0|max:100',
        ]);
        
        $existingRec = RecommendationModel::where('user_id', $validated['user_id'])
            ->where('competition_id', $validated['competition_id'])
            ->first();
            
        if ($existingRec) {
            return back()->with('info', 'Rekomendasi untuk pengguna dan kompetisi ini sudah ada!');
        }
        
        $generatedRecs = $request->session()->get('generated_recommendations', []);
        $factors = null;
        $userType = null;
        
        foreach ($generatedRecs as $rec) {
            if ($rec['user_id'] == $validated['user_id'] && 
                $rec['competition_id'] == $validated['competition_id']) {
                $factors = $rec['factors'];
                $userType = $rec['user_type'];
                break;
            }
        }
        
        $recommendation = new RecommendationModel();
        $recommendation->user_id = $validated['user_id'];
        $recommendation->competition_id = $validated['competition_id'];
        $recommendation->match_score = $validated['match_score'];
        $recommendation->recommended_by = 'system';
        $recommendation->status = 'pending';
        $recommendation->for_lecturer = $userType === 'Dosen';
        
        if ($factors) {
            $recommendation->match_factors = json_encode($factors);
        }
        
        $recommendation->save();
        
        if ($recommendation->for_lecturer) {
            $this->generateWPCalculationForRecommendation($recommendation);
        } else {
            $this->generateAHPCalculationForRecommendation($recommendation);
        }
        
        $latest_recommendations = RecommendationModel::with(['user', 'competition'])
            ->whereNotNull('id')
            ->orderBy('created_at', 'desc')
            ->limit(5)
            ->get();
        
        return back()->with('success', 'Rekomendasi berhasil disimpan!')
                    ->with('latest_recommendations', $latest_recommendations);
    }

    public function saveAllGenerated(Request $request)
    {
        $generatedRecs = $request->session()->get('generated_recommendations', []);
        
        if (empty($generatedRecs)) {
            return back()->with('error', 'Tidak ada rekomendasi yang dihasilkan!');
        }
        
        $savedCount = 0;
        
        foreach ($generatedRecs as $rec) {
            $exists = RecommendationModel::where('user_id', $rec['user_id'])
                ->where('competition_id', $rec['competition_id'])
                ->exists();
                
            if (!$exists) {
                $recommendation = new RecommendationModel();
                $recommendation->user_id = $rec['user_id'];
                $recommendation->competition_id = $rec['competition_id'];
                $recommendation->match_score = $rec['match_score'];
                $recommendation->recommended_by = 'system';
                $recommendation->status = 'pending';
                $recommendation->match_factors = json_encode($rec['factors']);
                $recommendation->for_lecturer = $rec['user_type'] === 'Dosen';
                $recommendation->save();
                
                if ($recommendation->for_lecturer) {
                    $this->generateWPCalculationForRecommendation($recommendation);
                } else {
                    $this->generateAHPCalculationForRecommendation($recommendation);
                }
                
                $savedCount++;
            }
        }
        
        return redirect()->route('admin.recommendations.index')
            ->with('success', $savedCount . ' rekomendasi baru telah disimpan!');
    }

    public function removeGenerated(Request $request)
    {
        $validated = $request->validate([
            'user_id' => 'required',
            'competition_id' => 'required',
        ]);
        
        $generatedRecs = $request->session()->get('generated_recommendations', []);
        $filteredRecs = [];
        
        foreach ($generatedRecs as $rec) {
            if ($rec['user_id'] != $validated['user_id'] || 
                $rec['competition_id'] != $validated['competition_id']) {
                $filteredRecs[] = $rec;
            }
        }
        
        $request->session()->put('generated_recommendations', $filteredRecs);
        
        if ($request->ajax()) {
            return response()->json([
                'success' => true,
                'count' => count($filteredRecs)
            ]);
        }
        
        return back();
    }
    
    public function clearGenerated(Request $request)
    {
        $request->session()->forget('generated_recommendations');
        
        if ($request->ajax()) {
            return response()->json([
                'success' => true
            ]);
        }
        
        return back();
    }

    public function export()
    {
        $recommendations = RecommendationModel::with(['user', 'competition'])
            ->orderBy('created_at', 'desc')
            ->get();
            
        $filename = 'recommendations_' . Carbon::now()->format('Y-m-d') . '.csv';
        
        $headers = [
            'Content-Type' => 'text/csv',
            'Content-Disposition' => "attachment; filename=\"$filename\"",
        ];
        
        $callback = function() use ($recommendations) {
            $file = fopen('php://output', 'w');
            
            fputcsv($file, [
                'ID', 
                'Nama Pengguna', 
                'NIM/NIP', 
                'Tipe Pengguna',
                'Program Studi',
                'Kompetisi',
                'Level',
                'Skor Kecocokan',
                'Status',
                'Direkomendasi Oleh',
                'Tanggal'
            ]);
            
            foreach ($recommendations as $recommendation) {
                $status = [
                    'pending' => 'Menunggu',
                    'accepted' => 'Diterima',
                    'rejected' => 'Ditolak'
                ][$recommendation->status] ?? $recommendation->status;
                
                $source = [
                    'system' => 'Sistem',
                    'lecturer' => 'Dosen',
                    'admin' => 'Admin'
                ][$recommendation->recommended_by] ?? $recommendation->recommended_by;
                
                $userType = $recommendation->for_lecturer ? 'Dosen' : 'Mahasiswa';
                $userIdentifier = $recommendation->for_lecturer ? 
                    ($recommendation->user->nip ?? 'N/A') : 
                    ($recommendation->user->nim ?? 'N/A');
                
                fputcsv($file, [
                    $recommendation->id,
                    $recommendation->user->name ?? 'N/A',
                    $userIdentifier,
                    $userType,
                    $recommendation->user->programStudi->name ?? 'N/A',
                    $recommendation->competition->name ?? 'N/A',
                    ucfirst($recommendation->competition->level ?? 'N/A'),
                    $recommendation->match_score . '%',
                    $status,
                    $source,
                    Carbon::parse($recommendation->created_at)->format('d/m/Y')
                ]);
            }
            
            fclose($file);
        };
        
        return response()->stream($callback, 200, $headers);
    }

    private function calculateMatchFactors($student, $competition)
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
                
                $levelMultiplier = 1.0;
                
                if (isset($achievement->level)) {
                    if ($achievement->level === 'international') {
                        $levelMultiplier = 3.0;
                    } elseif ($achievement->level === 'national') {
                        $levelMultiplier = 2.0;
                    }
                }
                
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

    public function destroy($id)
    {
        try {
            $recommendation = RecommendationModel::findOrFail($id);
            $recommendation->delete();
            
            if (request()->ajax()) {
                return response()->json([
                    'success' => true,
                    'message' => 'Rekomendasi berhasil dihapus!'
                ]);
            }
            
            return redirect()->route('admin.recommendations.index')
                ->with('success', 'Rekomendasi berhasil dihapus!');
        } catch (\Exception $e) {
            if (request()->ajax()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Gagal menghapus rekomendasi: ' . $e->getMessage()
                ], 500);
            }
            
            return redirect()->route('admin.recommendations.index')
                ->with('error', 'Gagal menghapus rekomendasi: ' . $e->getMessage());
        }
    }
    
    public function deleteAll(Request $request)
    {
        try {
            $deletedCount = RecommendationModel::count();
            RecommendationModel::truncate();
            
            if ($request->ajax()) {
                return response()->json([
                    'success' => true,
                    'message' => $deletedCount . ' rekomendasi berhasil dihapus!'
                ]);
            }
            
            return redirect()->route('admin.recommendations.index')
                ->with('success', $deletedCount . ' rekomendasi berhasil dihapus!');
        } catch (\Exception $e) {
            if ($request->ajax()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Gagal menghapus rekomendasi: ' . $e->getMessage()
                ], 500);
            }
            
            return redirect()->route('admin.recommendations.index')
                ->with('error', 'Gagal menghapus rekomendasi: ' . $e->getMessage());
        }
    }

    private function generateAHPCalculationForRecommendation(RecommendationModel $recommendation)
    {
        try {
            $matchFactors = json_decode($recommendation->match_factors, true);
            
            if (empty($matchFactors)) {
                \Log::warning('Match factors were empty for recommendation ID: ' . $recommendation->id . '. AHP calculation might be incorrect.');
                $matchFactors = [];
            }
            
            $criteriaPriorities = [
                'skills' => 5,
                'achievements' => 4,
                'interests' => 4,
                'deadline' => 3,
                'competition_level' => 6
            ];
            
            $sanitizedMatchFactors = [];
            foreach (array_keys($criteriaPriorities) as $criterion) {
                $sanitizedMatchFactors[$criterion] = $matchFactors[$criterion] ?? 0;
            }

            $ahpService = app(AHPService::class);
            
            $pairwiseMatrix = $ahpService->createPairwiseMatrixFromPriorities($criteriaPriorities);
            
            $weightsResult = $ahpService->calculateWeights($pairwiseMatrix);
            
            $finalScore = 0;
            $criteriaWeights = array_combine(array_keys($criteriaPriorities), $weightsResult['weights']);
            foreach($sanitizedMatchFactors as $factor => $score) {
                if(isset($criteriaWeights[$factor])) {
                    $finalScore += ($score / 100) * $criteriaWeights[$factor];
                }
            }
            $finalScore = $finalScore * 100;

            $weightedScores = [];
            foreach ($sanitizedMatchFactors as $factor => $score) {
                if(isset($criteriaWeights[$factor])) {
                    $weightedScores[$factor] = ($score / 100) * $criteriaWeights[$factor];
                }
            }
            
            $calculationDetails = [
                'criteria_weights' => $criteriaWeights,
                'factor_scores' => $sanitizedMatchFactors,
                'weighted_scores' => $weightedScores,
                'pairwise_matrix' => $pairwiseMatrix,
                'consistency_info' => $weightsResult['calculation_details']['consistency_check']
            ];
            
            $ahpResult = AHPResultModel::updateOrCreate(
                [
                    'user_id' => $recommendation->user_id,
                    'competition_id' => $recommendation->competition_id,
                    'calculation_type' => 'recommendation'
                ],
                [
                    'final_score' => $finalScore / 100,
                    'consistency_ratio' => $weightsResult['consistency_ratio'],
                    'is_consistent' => $weightsResult['is_consistent'],
                    'calculation_details' => json_encode($calculationDetails),
                    'calculated_at' => now()
                ]
            );
            
            $recommendation->ahp_result_id = $ahpResult->id;
            $recommendation->save();
            
            return $ahpResult;
        } catch (\Exception $e) {
            \Log::error('Error generating AHP calculation: ' . $e->getMessage(), [
                'exception' => $e,
                'recommendation_id' => $recommendation->id
            ]);
            
            return null;
        }
    }

    /**
     * Generate WP calculation details for a recommendation
     */
    private function generateWPCalculationForRecommendation(RecommendationModel $recommendation)
    {
        try {
            $wpService = app(WPService::class);

            $lecturer = $recommendation->user;
            $competition = $recommendation->competition;

        
            $competingLecturers = UserModel::where('id', '!=', $lecturer->id)
                ->whereHas('level', fn($q) => $q->where('level_kode', 'DSN'))
                ->where('program_studi_id', $lecturer->program_studi_id)
                ->limit(10)
                ->get()
                ->prepend($lecturer);

            $criteriaWeights = [
                'skills' => 0.3,
                'interests' => 0.2,
                'competition_level' => 0.2,
                'deadline' => 0.2,
                'activity_rating' => 0.1
            ];

            if ($competingLecturers->count() <= 1) {
                $factorScores = $this->calculateLecturerMatchFactors($lecturer, $competition);
                $sanitizedFactors = [];
                foreach (array_keys($criteriaWeights) as $criterion) {
                    $sanitizedFactors[$criterion] = min($factorScores[$criterion] ?? 0, 100);
                }

                try {
                    $normalizedWeightsCalc = app(\App\Services\WPService::class)->calculateWeights($criteriaWeights);
                    $vectorS = 1.0;
                    foreach ($sanitizedFactors as $criterion => $rawVal) {
                        if ($rawVal <= 0) {
                            continue;
                        }
                        $normalizedVal = $rawVal / 100;
                        $vectorS *= pow($normalizedVal, $normalizedWeightsCalc[$criterion]);
                    }
                } catch (\Exception $e) {
                    $vectorS = null;
                }
                $vectorV = $vectorS !== null ? 1.0 : null;

                $finalScore = 0;
                foreach ($criteriaWeights as $criterion => $weight) {
                    $finalScore += ($sanitizedFactors[$criterion] / 100) * $weight;
                }

                $calculationDetails = [
                    'message' => 'Calculated using simple weighted average as only one candidate was available for comparison.',
                    'criteria_weights' => $criteriaWeights,
                    'factor_scores' => $sanitizedFactors,
                    'raw_values' => $sanitizedFactors,
                    'normalized_values' => array_map(fn($v) => round($v / 100, 4), $sanitizedFactors),
                    's_vector_product' => $vectorS,
                    'vector_s' => null,
                    'vector_v' => null,
                    'rank' => 1,
                    'full_wp_results' => []
                ];

                $wpResultModel = WPResultModel::updateOrCreate(
                    [
                        'user_id' => $recommendation->user_id,
                        'competition_id' => $recommendation->competition_id,
                        'calculation_type' => 'recommendation'
                    ],
                    [
                        'final_score' => $finalScore,
                        'vector_s' => $vectorS,
                        'vector_v' => $vectorV,
                        'relative_preference' => null,
                        'rank' => 1,
                        'calculation_details' => json_encode($calculationDetails),
                        'calculated_at' => now()
                    ]
                );

                $recommendation->wp_result_id = $wpResultModel->id;
                $recommendation->match_score = $finalScore * 100;
                $recommendation->save();
                
                return $wpResultModel;
            }

            $normalizedWeights = $wpService->calculateWeights($criteriaWeights);

            $allFactorScores = [];
            foreach ($competingLecturers as $compLec) {
                $factors = $this->calculateLecturerMatchFactors($compLec, $competition);
                $sanitizedFactors = [];
                foreach (array_keys($criteriaWeights) as $criterion) {
                    $sanitizedFactors[$criterion] = min($factors[$criterion] ?? 0, 100);
                }
                $allFactorScores[$compLec->id] = $sanitizedFactors;
            }

            $alternativesForS = array_map(function ($scores) {
                return array_map(fn($v) => $v / 100, $scores);
            }, $allFactorScores);

            $vectorS_scores = $wpService->calculateVectorS($alternativesForS, $normalizedWeights);
            $vectorV_scores = $wpService->calculateVectorV($vectorS_scores);

            arsort($vectorV_scores);
            $rankedResults = [];
            $rank = 1;
            foreach ($vectorV_scores as $lecturerId => $vScore) {
                $rankedResults[] = [
                    'alternative_id' => $lecturerId,
                    'vector_s' => $vectorS_scores[$lecturerId],
                    'vector_v' => $vScore,
                    'rank' => $rank++
                ];
            }

            $targetResult = collect($rankedResults)->firstWhere('alternative_id', $lecturer->id);

            if (!$targetResult) {
                \Log::error("Could not find WP result for target lecturer ID: {$lecturer->id}");
                return null;
            }

            $finalScore = $targetResult['vector_v'];

            $calculationDetails = [
                'criteria_weights' => $normalizedWeights,
                'factor_scores' => $allFactorScores[$lecturer->id],
                'raw_values' => $allFactorScores[$lecturer->id],
                'normalized_values' => array_map(fn($v) => round($v / 100, 4), $allFactorScores[$lecturer->id]),
                'vector_s' => $targetResult['vector_s'],
                'vector_v' => $targetResult['vector_v'],
                'rank' => $targetResult['rank'],
                'full_wp_results' => $rankedResults // Store full context
            ];
            
            $wpResultModel = WPResultModel::updateOrCreate(
                [
                    'user_id' => $recommendation->user_id,
                    'competition_id' => $recommendation->competition_id,
                    'calculation_type' => 'recommendation'
                ],
                [
                    'final_score' => $finalScore, 
                    'vector_s' => $targetResult['vector_s'],
                    'vector_v' => $targetResult['vector_v'],
                    'relative_preference' => $targetResult['vector_v'], 
                    'rank' => $targetResult['rank'],
                    'calculation_details' => json_encode($calculationDetails),
                    'calculated_at' => now()
                ]
            );
            
            $recommendation->wp_result_id = $wpResultModel->id;
            $recommendation->match_score = $finalScore * 100;
            $recommendation->save();
            
            return $wpResultModel;
        } catch (\Exception $e) {
            \Log::error('Error generating WP calculation for recommendation: ' . $e->getMessage(), [
                'exception' => $e,
                'recommendation_id' => $recommendation->id
            ]);
            
            return null;
        }
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
            
            foreach ($competitionSkills as $reqSkill) {
                $totalSkillImportance += $reqSkill->pivot->importance_level ?? 3;
                
                foreach ($lecturerSkills as $lecturerSkill) {
                    if ($reqSkill->id === $lecturerSkill->id) {
                        $proficiencyPercentage = ($lecturerSkill->pivot->proficiency_level / 5) * 100;
                        $importanceLevel = $reqSkill->pivot->importance_level ?? 3;
                        $skillMatches += ($importanceLevel * $proficiencyPercentage) / 5;
                        break;
                    }
                }
            }
            
            if ($totalSkillImportance > 0) {
                $factors['skills'] = round(($skillMatches / $totalSkillImportance) * 100);
            }
        }
        
        $lecturerInterests = $lecturer->interests()->get();
        $competitionInterests = $competition->interests()->get();
        
        if ($competitionInterests->count() > 0) {
            $interestMatches = 0;
            $totalInterestImportance = 0;
            
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
            
            if ($totalInterestImportance > 0) {
                $factors['interests'] = round(($interestMatches / $totalInterestImportance) * 100);
            }
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
            $factors['activity_rating'] = 50; // Default to neutral if no ratings
        }
        
        return $factors;
    }
} 