<?php

namespace App\Http\Controllers\Dosen;

use App\Http\Controllers\Controller;
use App\Models\AchievementModel;
use App\Models\AttachmentModel;
use App\Models\UserModel;
use Illuminate\Http\Request;
use Carbon\Carbon;
use Illuminate\Foundation\Auth\User;
use Illuminate\Support\Facades\Auth;
use App\Models\SubCompetitionParticipantModel;
use App\Models\CompetitionParticipantModel;
use Illuminate\Support\Facades\Schema;

class AchievementController extends Controller
{
    public function index(Request $request)
    {
        $lecturer = Auth::user();

        $subParticipants = SubCompetitionParticipantModel::where('mentor_id', $lecturer->id)
                                    ->where('status_mentor', 'accept')
                                    ->get(['user_id', 'team_members']);

        $subCompetitionStudentIds = collect();
        foreach ($subParticipants as $participant) {
            $subCompetitionStudentIds->push($participant->user_id);

            $members = $participant->team_members;
            if (is_string($members)) {
                $members = json_decode($members, true);
            }
            if (is_array($members)) {
                foreach ($members as $member) {
                    if (is_array($member) && isset($member['id'])) {
                        $subCompetitionStudentIds->push($member['id']);
                    } elseif (is_scalar($member)) {
                        $subCompetitionStudentIds->push($member);
                    }
                }
            }
        }
        $subCompetitionStudentIds = $subCompetitionStudentIds->unique();

        $competitionParticipantsQuery = CompetitionParticipantModel::where('mentor_id', $lecturer->id);
        if (Schema::hasColumn('competition_participants', 'status_mentor')) {
            $competitionParticipantsQuery->where('status_mentor', 'accept');
        }
        $competitionStudentIds = $competitionParticipantsQuery->pluck('user_id');

        $allowedStudentIds = $subCompetitionStudentIds->merge($competitionStudentIds)->unique()->toArray();

        if (empty($allowedStudentIds)) {
            return view('Dosen.achievements.index', [
                'achievements' => collect(),
                'totalAchievements' => 0,
                'totalStudentsWithAchievements' => 0,
                'verifiedAchievements' => 0,
                'currentSort' => $request->input('sort', 'achievements_desc')
            ]);
        }

        $search = $request->input('search');
        
        $type = $request->input('type');
        
        $sort = $request->input('sort', 'achievements_desc');
        
        $query = AchievementModel::select('user_id')
                    ->selectRaw('COUNT(*) as total_achievements')
                    ->with(['user'])
                    ->whereIn('user_id', $allowedStudentIds)
                    ->when($search, function ($query, $search) {
                        return $query->whereHas('user', function ($q) use ($search) {
                            $q->where('name', 'LIKE', '%' . $search . '%')
                              ->orWhere('nim', 'LIKE', '%' . $search . '%');
                        });
                    })
                    ->when($type, function ($query, $type) {
                        return $query->where('type', $type);
                    })
                    ->groupBy('user_id');
        
        switch ($sort) {
            case 'name_asc':
                $query->join('users', 'achievements.user_id', '=', 'users.id')
                      ->orderBy('users.name', 'asc');
                break;
            case 'name_desc':
                $query->join('users', 'achievements.user_id', '=', 'users.id')
                      ->orderBy('users.name', 'desc');
                break;
            case 'achievements_asc':
                $query->orderByRaw('COUNT(*) ASC');
                break;
            case 'achievements_desc':
            default:
                $query->orderByRaw('COUNT(*) DESC');
                break;
        }
        
        $Achievements = $query->paginate(10);
        
        $totalAchievements = AchievementModel::whereIn('user_id', $allowedStudentIds)->count();
        $totalStudentsWithAchievements = AchievementModel::whereIn('user_id', $allowedStudentIds)
                                            ->distinct('user_id')
                                            ->count('user_id');
        $verifiedAchievements = AchievementModel::whereIn('user_id', $allowedStudentIds)->verified()->count();
        
        return view('Dosen.achievements.index', [
            'achievements' => $Achievements,
            'totalAchievements' => $totalAchievements,
            'totalStudentsWithAchievements' => $totalStudentsWithAchievements,
            'verifiedAchievements' => $verifiedAchievements,
            'currentSort' => $sort
        ]);
    }

    public function export()
    {
        return redirect()->back()->with('status', 'Data prestasi berhasil diunduh.');
    }

    public function create()
    {
        return view('Dosen.achievements.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'user_id' => 'required|exists:users,id',
            'title' => 'required|string|max:255',
            'description' => 'required|string',
            'competition_name' => 'required|string|max:255',
            'type' => 'required|in:academic,technology,arts,sports,entrepreneurship',
            'level' => 'required|in:international,national,regional',
            'date' => 'required|date',
            'status' => 'required|in:pending,verified,rejected',
        ]);
        
        $achievement = AchievementModel::create($validated);
        
        if ($request->hasFile('attachments')) {
        }
        
        return redirect()->route('dosen.achievements.index')
                        ->with('status', 'Prestasi berhasil ditambahkan!');
    }

    public function getDetails($id,$userId)
    {
        try {

            \Log::info('Achievement details request', [
                'user_id' => $userId,
                'achievement_id' => $id
            ]);
            
            $achievement = AchievementModel::where('id', $id)
                ->where('user_id', $userId)
                ->first();
                
            if (!$achievement) {
                \Log::warning('Achievement not found', [
                    'achievement_id' => $id,
                    'user_id' => $userId
                ]);
                
                return response()->json([
                    'success' => false,
                    'message' => 'Achievement not found'
                ], 404);
            }
            
            $attachments = [];
            try {
                $attachmentRecords = AttachmentModel    ::where('achievement_id', $achievement->id)->get();
                
                foreach ($attachmentRecords as $attachment) {
                    $attachments[] = [
                        'file_name' => $attachment->file_name,
                        'file_path' => $attachment->file_path,
                        'file_size' => $attachment->file_size,
                        'file_type' => $attachment->file_type
                    ];
                }
            } catch (\Exception $e) {
                \Log::warning('Error loading attachments', [
                    'achievement_id' => $id,
                    'error' => $e->getMessage()
                ]);
            }
            
            return response()->json([
                'success' => true,
                'achievement' => [
                    'achievement_id' => $achievement->id,
                    'title' => $achievement->title,
                    'competition_name' => $achievement->competition_name,
                    'description' => $achievement->description,
                    'type' => $achievement->type,
                    'level' => $achievement->level,
                    'date' => $achievement->date ? $achievement->date->format('Y-m-d') : null,
                    'status' => $achievement->status,
                    'competition_id' => $achievement->competition_id,
                    'rejected_reason' => $achievement->rejected_reason
                ],
                'attachments' => $attachments
            ]);
            
        } catch (\Exception $e) {
            \Log::error('Error fetching achievement details', [
                'achievement_id' => $id,
                'error' => $e->getMessage(),
                'file' => $e->getFile(),
                'line' => $e->getLine(),
                'trace' => $e->getTraceAsString()
            ]);
            
            return response()->json([
                'success' => false,
                'message' => 'An error occurred while fetching achievement details: ' . $e->getMessage()
            ], 500);
        }
    }
    
    public function show($id, Request $request)
    {
        $lecturer = Auth::user();

        // Recompute with team members for show guard
        $subParticipants = SubCompetitionParticipantModel::where('mentor_id', $lecturer->id)
                                    ->where('status_mentor', 'accept')
                                    ->get(['user_id', 'team_members']);
        $subCompetitionStudentIds = collect();
        foreach ($subParticipants as $participant) {
            $subCompetitionStudentIds->push($participant->user_id);
            $members = $participant->team_members;
            if (is_string($members)) {
                $members = json_decode($members, true);
            }
            if (is_array($members)) {
                foreach ($members as $member) {
                    if (is_array($member) && isset($member['id'])) {
                        $subCompetitionStudentIds->push($member['id']);
                    } elseif (is_scalar($member)) {
                        $subCompetitionStudentIds->push($member);
                    }
                }
            }
        }
        $subCompetitionStudentIds = $subCompetitionStudentIds->unique();

        $competitionParticipantsQuery = CompetitionParticipantModel::where('mentor_id', $lecturer->id);
        if (Schema::hasColumn('competition_participants', 'status_mentor')) {
            $competitionParticipantsQuery->where('status_mentor', 'accept');
        }
        $competitionStudentIds = $competitionParticipantsQuery->pluck('user_id');

        $allowedStudentIds = $subCompetitionStudentIds->merge($competitionStudentIds)->unique();

        if (!$allowedStudentIds->contains($id)) {
            abort(403, 'Anda tidak memiliki izin untuk melihat prestasi mahasiswa ini.');
        }

        $user = UserModel::findOrFail($id);

        $query = AchievementModel::with(['user', 'verifier', 'attachments'])
                    ->where('user_id', $user->id);

        $sort = $request->input('sort', 'date_desc');
        
        switch ($sort) {
            case 'title_asc':
                $query->orderBy('title', 'asc');
                break;
            case 'title_desc':
                $query->orderBy('title', 'desc');
                break;
            case 'date_asc':
                $query->orderBy('date', 'asc');
                break;
            case 'date_desc':
            default:
                $query->orderBy('date', 'desc');
                break;
        }
        
        $subAchievements = $query->get();
        
        return view('Dosen.achievements.sub-achievements.index', [
            'user' => $user,
            'subAchievements' => $subAchievements,
            'currentSort' => $sort
        ]);
    }

    public function verify(Request $request, $id)
    {
        $achievement = AchievementModel::findOrFail($id);
        $achievement->status = 'verified';
        $achievement->verified_by = auth()->id();
        $achievement->verified_at = now();
        $achievement->save();
        
        return redirect()->back()->with('status', 'Prestasi berhasil diverifikasi!');
    }

    public function reject(Request $request, $id)
    {
        $validated = $request->validate([
            'rejected_reason' => 'required|string'
        ]);
        
        $achievement = AchievementModel::findOrFail($id);
        $achievement->status = 'rejected';
        $achievement->rejected_reason = $validated['rejected_reason'];
        $achievement->save();
        
        return redirect()->back()->with('status', 'Prestasi ditolak!');
    }

    public function studentAchievements($userId)
    {
        $student = UserModel::findOrFail($userId);
        $achievements = AchievementModel::with(['attachments'])
                        ->where('user_id', $userId)
                        ->orderBy('date', 'desc')
                        ->get();
        
        $achievementsByType = AchievementModel::where('user_id', $userId)
                        ->selectRaw('type, COUNT(*) as count')
                        ->groupBy('type')
                        ->get()
                        ->pluck('count', 'type')
                        ->toArray();
        
        $achievementsByLevel = AchievementModel::where('user_id', $userId)
                        ->selectRaw('level, COUNT(*) as count')
                        ->groupBy('level')
                        ->get()
                        ->pluck('count', 'level')
                        ->toArray();
        
        return view('Dosen.achievements.student', [
            'student' => $student,
            'achievements' => $achievements,
            'achievementsByType' => $achievementsByType,
            'achievementsByLevel' => $achievementsByLevel,
            'totalAchievements' => $achievements->count(),
            'verifiedAchievements' => $achievements->where('status', 'verified')->count()
        ]);
    }
}
