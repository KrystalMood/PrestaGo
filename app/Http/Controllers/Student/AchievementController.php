<?php

namespace App\Http\Controllers\Student;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\AchievementModel;
use App\Models\AttachmentModel;
use App\Services\ActivityService;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;

class AchievementController extends Controller
{
    protected $activityService;

    public function __construct(ActivityService $activityService)
    {
        $this->activityService = $activityService;
    }

    public function index(Request $request)
    {
        $user = Auth::user();
        
        $totalAchievements = AchievementModel::where('user_id', $user->id)->count();
        $verifiedAchievements = AchievementModel::where('user_id', $user->id)
            ->where('status', 'verified')
            ->count();
        $pendingAchievements = AchievementModel::where('user_id', $user->id)
            ->where('status', 'pending')
            ->count();
        
        $achievements = AchievementModel::where('user_id', $user->id)
            ->orderBy('created_at', 'desc')
            ->paginate(10);
        
        $achievementStats = $this->getAchievementStatistics($user->id);
        
        if ($request->ajax() || $request->query('ajax') == 1) {
            $table = view('student.achievements.components.tables', compact('achievements'))->render();
            $pagination = view('admin.components.tables.pagination', compact('achievements'))->render();
            
            return response()->json([
                'success' => true,
                'table' => $table,
                'pagination' => $pagination,
                'stats' => [
                    'total' => $totalAchievements,
                    'verified' => $verifiedAchievements,
                    'pending' => $pendingAchievements
                ]
            ]);
        }
        
        return view('student.achievements.index', compact(
            'achievements', 
            'totalAchievements', 
            'verifiedAchievements', 
            'pendingAchievements',
            'achievementStats'
        ));
    }

    public function create()
    {
        return view('student.achievements.create');
    }

    public function store(Request $request)
    {
        $validator = \Validator::make($request->all(), [
            'title' => 'required|string|max:255',
            'competition_name' => 'required|string|max:255',
            'type' => 'required|string|in:academic,technology,arts,sports,entrepreneurship',
            'level' => 'required|string|in:international,national,regional',
            'date' => 'required|date',
            'description' => 'required|string',
            'competition_id' => 'nullable|exists:competitions,id',
            'attachments' => 'required|array',
            'attachments.*' => 'file|mimes:pdf,jpg,jpeg,png|max:2048',
        ]);

        if ($validator->fails()) {
            if ($request->ajax() || $request->wantsJson()) {
                return response()->json([
                    'success' => false,
                    'errors' => $validator->errors()
                ], 422);
            }
            
            return back()->withErrors($validator)->withInput();
        }

        try {
            DB::beginTransaction();
            
            $user = Auth::user();
            
            $achievement = new AchievementModel();
            $achievement->title = $request->title;
            $achievement->competition_name = $request->competition_name;
            $achievement->type = $request->type;
            $achievement->level = $request->level;
            $achievement->date = $request->date;
            $achievement->description = $request->description;
            $achievement->competition_id = $request->competition_id;
            $achievement->user_id = $user->id;
            $achievement->status = 'pending';
            $achievement->save();
            
            if ($request->hasFile('attachments')) {
                foreach ($request->file('attachments') as $file) {
                    $path = $file->store('achievements/' . $achievement->id, 'public');
                    
                    $attachment = new AttachmentModel();
                    $attachment->achievement_id = $achievement->id;
                    $attachment->file_path = $path;
                    $attachment->file_name = $file->getClientOriginalName();
                    $attachment->file_type = $file->getClientMimeType();
                    $attachment->file_size = $file->getSize();
                    $attachment->save();
                }
            }
            
            $this->activityService->log(
                'blue',
                'Mahasiswa ' . $user->name . ' mengajukan prestasi baru: ' . $achievement->title,
                'Create',
                'Achievement',
                'User',
                $user
            );
            
            DB::commit();
            
            if ($request->ajax() || $request->wantsJson()) {
                return response()->json([
                    'success' => true,
                    'message' => 'Prestasi berhasil ditambahkan dan sedang menunggu verifikasi.',
                    'achievement' => $achievement
                ]);
            }
            
            return redirect()->route('student.achievements.index')
                ->with('status', 'Prestasi berhasil ditambahkan dan sedang menunggu verifikasi.');
                
        } catch (\Exception $e) {
            DB::rollBack();
            
            if ($request->ajax() || $request->wantsJson()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Terjadi kesalahan: ' . $e->getMessage()
                ], 500);
            }
            
            return back()->withErrors(['message' => 'Terjadi kesalahan: ' . $e->getMessage()]);
        }
    }

    public function show($id)
    {
        $user = Auth::user();
        $achievement = AchievementModel::with('attachments')
            ->where('user_id', $user->id)
            ->where('id', $id)
            ->firstOrFail();
            
        return view('student.achievements.show', compact('achievement'));
    }

    public function getDetails($id)
    {
        try {
            $user = Auth::user();
            
            \Log::info('Achievement details request', [
                'user_id' => $user->id,
                'achievement_id' => $id
            ]);
            
            $achievement = AchievementModel::where('id', $id)
                ->where('user_id', $user->id)
                ->first();
                
            if (!$achievement) {
                \Log::warning('Achievement not found', [
                    'achievement_id' => $id,
                    'user_id' => $user->id
                ]);
                
                return response()->json([
                    'success' => false,
                    'message' => 'Achievement not found'
                ], 404);
            }
            
            $attachments = [];
            try {
                $attachmentRecords = AttachmentModel::where('achievement_id', $achievement->id)->get();
                
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

    public function edit($id)
    {
        $user = Auth::user();
        $achievement = AchievementModel::with('attachments')
            ->where('user_id', $user->id)
            ->where('id', $id)
            ->firstOrFail();
            
        if ($achievement->status !== 'pending') {
            return redirect()->route('student.achievements.index')
                ->with('error', 'Hanya prestasi dengan status menunggu yang dapat diubah.');
        }
            
        return view('student.achievements.edit', compact('achievement'));
    }

    public function update(Request $request, $id)
    {
        $validator = \Validator::make($request->all(), [
            'title' => 'required|string|max:255',
            'competition_name' => 'required|string|max:255',
            'type' => 'required|string|in:academic,technology,arts,sports,entrepreneurship',
            'level' => 'required|string|in:international,national,regional',
            'date' => 'required|date',
            'description' => 'required|string',
            'competition_id' => 'nullable|exists:competitions,id',
            'attachments' => 'nullable|array',
            'attachments.*' => 'file|mimes:pdf,jpg,jpeg,png|max:2048',
        ]);

        if ($validator->fails()) {
            if ($request->ajax() || $request->wantsJson()) {
                return response()->json([
                    'success' => false,
                    'errors' => $validator->errors()
                ], 422);
            }
            
            return back()->withErrors($validator)->withInput();
        }

        try {
            DB::beginTransaction();
            
            $user = Auth::user();
            $achievement = AchievementModel::where('user_id', $user->id)
                ->where('id', $id)
                ->firstOrFail();
            
            if ($achievement->status !== 'pending') {
                if ($request->ajax() || $request->wantsJson()) {
                    return response()->json([
                        'success' => false,
                        'message' => 'Hanya prestasi dengan status menunggu yang dapat diubah.'
                    ], 403);
                }
                
                return redirect()->route('student.achievements.index')
                    ->with('error', 'Hanya prestasi dengan status menunggu yang dapat diubah.');
            }
            
            $achievement->title = $request->title;
            $achievement->competition_name = $request->competition_name;
            $achievement->type = $request->type;
            $achievement->level = $request->level;
            $achievement->date = $request->date;
            $achievement->description = $request->description;
            $achievement->competition_id = $request->competition_id;
            $achievement->save();
            
            if ($request->hasFile('attachments')) {
                foreach ($request->file('attachments') as $file) {
                    $path = $file->store('achievements/' . $achievement->id, 'public');
                    
                    $attachment = new AttachmentModel();
                    $attachment->achievement_id = $achievement->id;
                    $attachment->file_path = $path;
                    $attachment->file_name = $file->getClientOriginalName();
                    $attachment->file_type = $file->getClientMimeType();
                    $attachment->file_size = $file->getSize();
                    $attachment->save();
                }
            }
            
            $this->activityService->log(
                'blue',
                'Mahasiswa ' . $user->name . ' mengubah prestasi: ' . $achievement->title,
                'Update',
                'Achievement',
                'User',
                $user
            );
            
            DB::commit();
            
            if ($request->ajax() || $request->wantsJson()) {
                return response()->json([
                    'success' => true,
                    'message' => 'Prestasi berhasil diperbarui.',
                    'achievement' => $achievement
                ]);
            }
            
            return redirect()->route('student.achievements.index')
                ->with('status', 'Prestasi berhasil diperbarui.');
                
        } catch (\Exception $e) {
            DB::rollBack();
            
            if ($request->ajax() || $request->wantsJson()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Terjadi kesalahan: ' . $e->getMessage()
                ], 500);
            }
            
            return back()->withErrors(['message' => 'Terjadi kesalahan: ' . $e->getMessage()]);
        }
    }
    
    public function destroy($id)
    {
        try {
            DB::beginTransaction();
            
            $user = Auth::user();
            
            $achievement = AchievementModel::where('user_id', $user->id)
                ->where('id', $id)
                ->first();
                
            if (!$achievement) {
                DB::rollBack();
                return response()->json([
                    'success' => false,
                    'message' => 'Prestasi tidak ditemukan atau sudah dihapus.'
                ], 404);
            }
            
            if ($achievement->status === 'verified') {
                DB::rollBack();
                return response()->json([
                    'success' => false,
                    'message' => 'Prestasi yang sudah terverifikasi tidak dapat dihapus.'
                ], 403);
            }
            
            if ($achievement->attachments) {
                foreach ($achievement->attachments as $attachment) {
                    if ($attachment && $attachment->file_path) {
                        Storage::disk('public')->delete($attachment->file_path);
                        $attachment->delete();
                    }
                }
            }
            
            $this->activityService->log(
                'red',
                'Mahasiswa ' . $user->name . ' menghapus prestasi: ' . $achievement->title,
                'Delete',
                'Achievement',
                'User',
                $user
            );
            
            $achievement->delete();
            
            DB::commit();
            
            return response()->json([
                'success' => true,
                'message' => 'Prestasi berhasil dihapus.'
            ]);
                
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json([
                'success' => false,
                'message' => 'Terjadi kesalahan: ' . $e->getMessage()
            ]);
        }
    }
    
    private function getAchievementStatistics($userId)
    {
        $achievements = AchievementModel::where('user_id', $userId)
            ->where('status', 'verified')
            ->get();
            
        if ($achievements->isEmpty()) {
            return null;
        }
        
        $byType = [
            'labels' => [],
            'data' => [],
            'colors' => [
                'backgroundColor' => [
                    'academic' => 'rgba(79, 70, 229, 0.7)',
                    'technology' => 'rgba(59, 130, 246, 0.7)',
                    'arts' => 'rgba(16, 185, 129, 0.7)',
                    'sports' => 'rgba(245, 158, 11, 0.7)',
                    'entrepreneurship' => 'rgba(239, 68, 68, 0.7)'
                ],
                'borderColor' => [
                    'academic' => 'rgba(79, 70, 229, 1)',
                    'technology' => 'rgba(59, 130, 246, 1)',
                    'arts' => 'rgba(16, 185, 129, 1)',
                    'sports' => 'rgba(245, 158, 11, 1)',
                    'entrepreneurship' => 'rgba(239, 68, 68, 1)'
                ]
            ],
            'backgroundColors' => [],
            'borderColors' => []
        ];
        
        $typeCount = $achievements->groupBy('type')->map->count();
        foreach ($typeCount as $type => $count) {
            $label = ucfirst($type);
            switch ($type) {
                case 'academic':
                    $label = 'Akademik';
                    break;
                case 'technology':
                    $label = 'Teknologi';
                    break;
                case 'arts':
                    $label = 'Seni';
                    break;
                case 'sports':
                    $label = 'Olahraga';
                    break;
                case 'entrepreneurship':
                    $label = 'Kewirausahaan';
                    break;
            }
            
            $byType['labels'][] = $label;
            $byType['data'][] = $count;
            $byType['backgroundColors'][] = $byType['colors']['backgroundColor'][$type] ?? 'rgba(107, 114, 128, 0.7)';
            $byType['borderColors'][] = $byType['colors']['borderColor'][$type] ?? 'rgba(107, 114, 128, 1)';
        }
        
        $byLevel = [
            'labels' => [],
            'data' => [],
            'colors' => [
                'backgroundColor' => [
                    'international' => 'rgba(239, 68, 68, 0.8)',
                    'national' => 'rgba(59, 130, 246, 0.8)',
                    'regional' => 'rgba(16, 185, 129, 0.8)'
                ],
                'borderColor' => [
                    'international' => 'rgba(239, 68, 68, 1)',
                    'national' => 'rgba(59, 130, 246, 1)',
                    'regional' => 'rgba(16, 185, 129, 1)'
                ]
            ],
            'backgroundColors' => [],
            'borderColors' => []
        ];
        
        $levelCount = $achievements->groupBy('level')->map->count();
        foreach ($levelCount as $level => $count) {
            $label = ucfirst($level);
            switch ($level) {
                case 'international':
                    $label = 'Internasional';
                    break;
                case 'national':
                    $label = 'Nasional';
                    break;
                case 'regional':
                    $label = 'Regional';
                    break;
            }
            
            $byLevel['labels'][] = $label;
            $byLevel['data'][] = $count;
            $byLevel['backgroundColors'][] = $byLevel['colors']['backgroundColor'][$level] ?? 'rgba(107, 114, 128, 0.8)';
            $byLevel['borderColors'][] = $byLevel['colors']['borderColor'][$level] ?? 'rgba(107, 114, 128, 1)';
        }
        
        $byRank = [
            'labels' => ['Juara 1', 'Juara 2', 'Juara 3', 'Juara Harapan', 'Finalis', 'Peserta'],
            'data' => [0, 0, 0, 0, 0, 0],
            'colors' => [
                'backgroundColor' => [
                    'rgba(249, 115, 22, 0.8)',
                    'rgba(139, 92, 246, 0.8)',
                    'rgba(14, 165, 233, 0.8)',
                    'rgba(234, 88, 12, 0.8)',
                    'rgba(56, 189, 248, 0.8)',
                    'rgba(148, 163, 184, 0.8)'
                ],
                'borderColor' => [
                    'rgba(249, 115, 22, 1)',
                    'rgba(139, 92, 246, 1)',
                    'rgba(14, 165, 233, 1)',
                    'rgba(234, 88, 12, 1)',
                    'rgba(56, 189, 248, 1)',
                    'rgba(148, 163, 184, 1)'
                ]
            ]
        ];
        
        foreach ($achievements as $achievement) {
            $title = strtolower($achievement->title);
            $description = strtolower($achievement->description);
            $content = $title . ' ' . $description;
            
            if (strpos($content, 'juara 1') !== false || 
                strpos($content, 'juara pertama') !== false || 
                strpos($content, '1st place') !== false ||
                strpos($content, 'first place') !== false) {
                $byRank['data'][0]++;
            } else if (strpos($content, 'juara 2') !== false || 
                strpos($content, 'juara kedua') !== false || 
                strpos($content, '2nd place') !== false ||
                strpos($content, 'second place') !== false) {
                $byRank['data'][1]++;
            } else if (strpos($content, 'juara 3') !== false || 
                strpos($content, 'juara ketiga') !== false || 
                strpos($content, '3rd place') !== false ||
                strpos($content, 'third place') !== false) {
                $byRank['data'][2]++;
            } else if (strpos($content, 'juara harapan') !== false || 
                strpos($content, 'honorable') !== false || 
                strpos($content, 'mention') !== false) {
                $byRank['data'][3]++;
            } else if (strpos($content, 'finalis') !== false || 
                strpos($content, 'finalist') !== false || 
                strpos($content, 'final') !== false) {
                $byRank['data'][4]++;
            } else {
                $byRank['data'][5]++;
            }
        }
        
        $filteredRank = ['labels' => [], 'data' => [], 'backgroundColors' => [], 'borderColors' => []];
        for ($i = 0; $i < count($byRank['labels']); $i++) {
            if ($byRank['data'][$i] > 0) {
                $filteredRank['labels'][] = $byRank['labels'][$i];
                $filteredRank['data'][] = $byRank['data'][$i];
                $filteredRank['backgroundColors'][] = $byRank['colors']['backgroundColor'][$i];
                $filteredRank['borderColors'][] = $byRank['colors']['borderColor'][$i];
            }
        }
        
        return [
            'byType' => $byType,
            'byLevel' => $byLevel,
            'byRank' => $filteredRank
        ];
    }
}
