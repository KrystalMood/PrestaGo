<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\AchievementModel;
use App\Models\AttachmentModel;
use App\Models\UserModel;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Carbon\Carbon;

class AchievementVerificationController extends Controller
{
    public function index(Request $request)
    {
        $query = AchievementModel::with(['user', 'verifier', 'attachments']);
        
        if ($request->has('status')) {
            $query->where('status', $request->status);
        }
        
        if ($request->has('type')) {
            $query->where('type', $request->type);
        }
        
        if ($request->has('level')) {
            $query->where('level', $request->level);
        }
        
        if ($request->has('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('title', 'like', "%{$search}%")
                  ->orWhere('competition_name', 'like', "%{$search}%")
                  ->orWhereHas('user', function($user) use ($search) {
                      $user->where('name', 'like', "%{$search}%");
                  });
            });
        }
        
        $total = AchievementModel::count();
        $pending = AchievementModel::where('status', 'pending')->count();
        $verified = AchievementModel::where('status', 'verified')->count();
        $rejected = AchievementModel::where('status', 'rejected')->count();
        
        $stats = [
            [
                'title' => 'Total Prestasi',
                'value' => $total,
                'icon' => 'award'
            ],
            [
                'title' => 'Menunggu Verifikasi',
                'value' => $pending,
                'icon' => 'clock'
            ],
            [
                'title' => 'Disetujui',
                'value' => $verified,
                'icon' => 'check-circle'
            ],
            [
                'title' => 'Ditolak',
                'value' => $rejected,
                'icon' => 'x-circle'
            ],
        ];
        
        $categories = AchievementModel::select('type')->distinct()->pluck('type');
        $levels = AchievementModel::select('level')->distinct()->pluck('level');
        
        // Get achievements with pagination
        $achievements = $query->orderBy('created_at', 'desc')->paginate(10);
        
        return view('admin.verification.index', compact('achievements', 'stats', 'categories', 'levels'));
    }

    public function show($id)
    {
        $achievement = AchievementModel::with(['user', 'verifier', 'attachments'])->findOrFail($id);
        return view('admin.verification.show', compact('achievement'));
    }

    public function approve(Request $request, $id)
    {
        $achievement = AchievementModel::findOrFail($id);
        
        $achievement->status = 'verified';
        $achievement->verified_by = Auth::id();
        $achievement->verified_at = Carbon::now();
        $achievement->save();
        
        return redirect()->route('admin.verification.index')
            ->with('success', 'Prestasi berhasil diverifikasi.');
    }

    public function reject(Request $request, $id)
    {
        $request->validate([
            'rejected_reason' => 'required|string|max:255',
        ]);
        
        $achievement = AchievementModel::findOrFail($id);
        
        $achievement->status = 'rejected';
        $achievement->verified_by = Auth::id();
        $achievement->verified_at = Carbon::now();
        $achievement->rejected_reason = $request->rejected_reason;
        $achievement->save();
        
        return redirect()->route('admin.verification.index')
            ->with('success', 'Prestasi ditolak dengan alasan yang diberikan.');
    }

    public function downloadAttachment($id)
    {
        $attachment = AttachmentModel::findOrFail($id);
        
        return Storage::download($attachment->file_path, $attachment->file_name);
    }
} 