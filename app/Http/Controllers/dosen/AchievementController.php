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

class AchievementController extends Controller
{
    /**
     * Display a listing of the achievements.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\View\View
     */
    public function index(Request $request)
    {
        // Handle search query
        $search = $request->input('search');
        
        // Filter by type if provided
        $type = $request->input('type');
        
        // Query to get students with their achievement counts
        $query = AchievementModel::select('user_id')
                    ->selectRaw('COUNT(*) as total_achievements')
                    ->with(['user']) // Eager load user data
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
        
        // Pagination of students with achievement counts
        $Achievements = $query->orderByRaw('COUNT(*) DESC')->paginate(10);
        
        // Calculate stats
        $totalAchievements = AchievementModel::count();
        $totalStudentsWithAchievements = AchievementModel::distinct('user_id')->count('user_id');
        $verifiedAchievements = AchievementModel::verified()->count();
        
        return view('Dosen.achievements.index', [
            'achievements' => $Achievements,
            'totalAchievements' => $totalAchievements,
            'totalStudentsWithAchievements' => $totalStudentsWithAchievements,
            'verifiedAchievements' => $verifiedAchievements,
        ]);
    }

    /**
     * Export achievements data as CSV or Excel.
     *
     * @return \Symfony\Component\HttpFoundation\BinaryFileResponse
     */
    public function export()
    {
        // This is a placeholder for export functionality
        // You would implement the actual export logic here
        
        return redirect()->back()->with('status', 'Data prestasi berhasil diunduh.');
    }

    /**
     * Show the form for creating a new achievement.
     *
     * @return \Illuminate\View\View
     */
    public function create()
    {
        // Return view for creating a new achievement
        return view('Dosen.achievements.create');
    }

    /**
     * Store a newly created achievement in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function store(Request $request)
    {
        // Validate the request data
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
        
        // Create the achievement
        $achievement = AchievementModel::create($validated);
        
        // Handle attachments if present
        if ($request->hasFile('attachments')) {
            // Process attachments
        }
        
        return redirect()->route('dosen.achievements.index')
                        ->with('status', 'Prestasi berhasil ditambahkan!');
    }

    /**
     * Display the specified achievement.
     *
     * @param  int  $id
     * @return \Illuminate\View\View
     */

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
    
    public function show($id)
    {
        $user = UserModel::findOrFail($id);

        $subAchievements = AchievementModel::with(['user', 'verifier', 'attachments'])->where('user_id', $user->id)
                            ->orderBy('date', 'desc')
                            ->get();
        return view('Dosen.achievements.sub-achievements.index', [
            'user' => $user,
            'subAchievements' => $subAchievements,
        ]);
    }

    /**
     * Verify the achievement.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\RedirectResponse
     */
    public function verify(Request $request, $id)
    {
        $achievement = AchievementModel::findOrFail($id);
        $achievement->status = 'verified';
        $achievement->verified_by = auth()->id();
        $achievement->verified_at = now();
        $achievement->save();
        
        return redirect()->back()->with('status', 'Prestasi berhasil diverifikasi!');
    }

    /**
     * Reject the achievement.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\RedirectResponse
     */
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

    /**
     * Display achievements for a specific student.
     *
     * @param  int  $userId
     * @return \Illuminate\View\View
     */
    public function studentAchievements($userId)
    {
        $student = UserModel::findOrFail($userId);
        $achievements = AchievementModel::with(['attachments'])
                        ->where('user_id', $userId)
                        ->orderBy('date', 'desc')
                        ->get();
        
        // Get achievement counts by type
        $achievementsByType = AchievementModel::where('user_id', $userId)
                        ->selectRaw('type, COUNT(*) as count')
                        ->groupBy('type')
                        ->get()
                        ->pluck('count', 'type')
                        ->toArray();
        
        // Get achievement counts by level
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
