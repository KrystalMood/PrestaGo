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
        $recommendation = RecommendationModel::with([
            'user', 
            'user.skills', 
            'user.programStudi', 
            'competition',
            'competition.skills'
        ])->findOrFail($id);
        
        $achievements = AchievementModel::where('user_id', $recommendation->user_id)
            ->where('status', 'verified')
            ->orderBy('date', 'desc')
            ->limit(5)
            ->get();
            
        $participations = CompetitionParticipantModel::with('competition')
            ->where('user_id', $recommendation->user_id)
            ->orderBy('created_at', 'desc')
            ->limit(5)
            ->get();
        
        $match_factors = [];
        if (!empty($recommendation->match_factors)) {
            $match_factors = json_decode($recommendation->match_factors, true);
        } else {
            $match_factors = [
                'skills' => 0,
                'achievements' => 0,
                'academic' => 0,
                'experience' => 0
            ];
        }
        
        return view('admin.recommendations.show', compact(
            'recommendation', 
            'achievements', 
            'participations',
            'match_factors'
        ));
    }

    public function updateStatus(Request $request, $id)
    {
        $request->validate([
            'status' => 'required|in:accepted,rejected'
        ]);
        
        $recommendation = RecommendationModel::findOrFail($id);
        $recommendation->status = $request->status;
        $recommendation->save();
        
        $statusText = $request->status === 'accepted' ? 'diterima' : 'ditolak';
        
        return redirect()->back()
            ->with('success', "Rekomendasi telah {$statusText}!");
    }

    public function automatic()
    {
        $competitions = CompetitionModel::all();
            
        $programs = StudyProgramModel::all();
        
        $latest_recommendations = RecommendationModel::with(['user', 'competition'])
            ->orderBy('created_at', 'desc')
            ->limit(5)
            ->get();
        
        return view('admin.recommendations.automatic', compact('competitions', 'programs', 'latest_recommendations'));
    }

    public function generate(Request $request)
    {
        $validated = $request->validate([
            'competition_id' => 'nullable|exists:competitions,id',
            'program_studi_id' => 'nullable|exists:study_programs,id',
            'threshold' => 'required|numeric|min:0|max:100',
            'max_recommendations' => 'required|integer|min:1|max:10',
            'dss_method' => 'required|in:ahp',
            'ahp_consistency_threshold' => 'required|numeric|min:0|max:0.2',
            'ahp_priority_skills' => 'required|integer|min:1|max:9',
            'ahp_priority_achievements' => 'required|integer|min:1|max:9',
            'ahp_priority_interests' => 'required|integer|min:1|max:9',
        ]);
        
        try {
            DB::beginTransaction();
            
            // Generate recommendations using the service
            $recommendations = $this->recommendationService->generateRecommendations($validated);
            
            // Save all recommendations
            foreach ($recommendations as $recommendation) {
                $recommendation->save();
            }
            
            DB::commit();
            
            return redirect()
                ->route('admin.recommendations.index')
                ->with('success', 'Berhasil menghasilkan ' . $recommendations->count() . ' rekomendasi baru.');
                
        } catch (\Exception $e) {
            DB::rollBack();
            
            return back()
                ->withInput()
                ->with('error', 'Terjadi kesalahan saat menghasilkan rekomendasi: ' . $e->getMessage());
        }
    }

    public function saveGenerated(Request $request)
    {
        $validated = $request->validate([
            'student_id' => 'required|exists:users,id',
            'competition_id' => 'required|exists:competitions,id',
            'match_score' => 'required|numeric|min:0|max:100',
        ]);
        
        $existingRec = RecommendationModel::where('user_id', $validated['student_id'])
            ->where('competition_id', $validated['competition_id'])
            ->first();
            
        if ($existingRec) {
            return back()->with('info', 'Rekomendasi untuk mahasiswa dan kompetisi ini sudah ada!');
        }
        
        $generatedRecs = $request->session()->get('generated_recommendations', []);
        $factors = null;
        
        foreach ($generatedRecs as $rec) {
            if ($rec['student_id'] == $validated['student_id'] && 
                $rec['competition_id'] == $validated['competition_id']) {
                $factors = $rec['factors'];
                break;
            }
        }
        
        $recommendation = new RecommendationModel();
        $recommendation->user_id = $validated['student_id'];
        $recommendation->competition_id = $validated['competition_id'];
        $recommendation->match_score = $validated['match_score'];
        $recommendation->recommended_by = 'system';
        $recommendation->status = 'pending';
        
        if ($factors) {
            $recommendation->match_factors = json_encode($factors);
        }
        
        $recommendation->save();
        
        return back()->with('success', 'Rekomendasi berhasil disimpan!');
    }

    public function saveAllGenerated(Request $request)
    {
        $generatedRecs = $request->session()->get('generated_recommendations', []);
        
        if (empty($generatedRecs)) {
            return back()->with('error', 'Tidak ada rekomendasi yang dihasilkan!');
        }
        
        $savedCount = 0;
        
        foreach ($generatedRecs as $rec) {
            $exists = RecommendationModel::where('user_id', $rec['student_id'])
                ->where('competition_id', $rec['competition_id'])
                ->exists();
                
            if (!$exists) {
                $recommendation = new RecommendationModel();
                $recommendation->user_id = $rec['student_id'];
                $recommendation->competition_id = $rec['competition_id'];
                $recommendation->match_score = $rec['match_score'];
                $recommendation->recommended_by = 'system';
                $recommendation->status = 'pending';
                $recommendation->match_factors = json_encode($rec['factors']);
                $recommendation->save();
                
                $savedCount++;
            }
        }
        
        return redirect()->route('admin.recommendations.index')
            ->with('success', $savedCount . ' rekomendasi baru telah disimpan!');
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
                'Mahasiswa', 
                'NIM', 
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
                
                fputcsv($file, [
                    $recommendation->id,
                    $recommendation->user->name ?? 'N/A',
                    $recommendation->user->nim ?? 'N/A',
                    $recommendation->user->program_studi->name ?? 'N/A',
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
} 