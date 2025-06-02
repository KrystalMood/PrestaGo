<?php

namespace App\Http\Controllers\Dosen;

use App\Http\Controllers\Controller;
use App\Models\AchievementModel;
use App\Models\UserModel;
use Illuminate\Http\Request;
use Carbon\Carbon;
use Illuminate\Foundation\Auth\User;

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
