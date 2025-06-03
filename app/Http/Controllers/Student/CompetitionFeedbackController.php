<?php

namespace App\Http\Controllers\Student;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use App\Models\CompetitionFeedback;
use App\Models\CompetitionModel;
use App\Models\CompetitionParticipantModel;
use Carbon\Carbon;

class CompetitionFeedbackController extends Controller
{
    public function index()
    {
        $userId = Auth::id();
        $today = Carbon::now();
        
        // Get competitions where the user has participated AND the competition has ended
        $participatedCompetitions = CompetitionParticipantModel::where('user_id', $userId)
            ->with(['competition' => function($query) use ($today) {
                $query->whereDate('end_date', '<', $today);
            }])
            ->get()
            ->pluck('competition')
            ->filter()
            ->mapWithKeys(function ($competition) {
                return [$competition->id => $competition->name];
            });
        
        // If no competitions found, don't fall back to all completed competitions
        // This ensures students can only rate competitions they've participated in
        
        $feedbackSubmitted = CompetitionFeedback::where('user_id', $userId)
            ->pluck('competition_id')
            ->toArray();
        
        $previousFeedback = CompetitionFeedback::where('user_id', $userId)
            ->with('competition')
            ->orderBy('created_at', 'desc')
            ->get();
        
        return view('student.feedback.index', compact('participatedCompetitions', 'feedbackSubmitted', 'previousFeedback'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'competition_id' => 'required|exists:competitions,id',
            'overall_rating' => 'required|integer|min:1|max:5',
            'organization_rating' => 'required|integer|min:1|max:5',
            'judging_rating' => 'required|integer|min:1|max:5',
            'learning_rating' => 'required|integer|min:1|max:5',
            'materials_rating' => 'required|integer|min:1|max:5',
            'strengths' => 'required|string',
            'improvements' => 'required|string',
            'skills_gained' => 'required|string',
            'recommendation' => 'required|in:yes,maybe,no',
            'additional_comments' => 'nullable|string',
        ]);

        $userId = Auth::id();
        $validated['user_id'] = $userId;
        
        // Check if the user has already submitted feedback for this competition
        $existingFeedback = CompetitionFeedback::where('user_id', $userId)
            ->where('competition_id', $validated['competition_id'])
            ->first();
            
        if ($existingFeedback) {
            return redirect()->route('student.feedback.index')
                ->with('error', 'Anda sudah memberikan feedback untuk lomba ini sebelumnya.');
        }
        
        // Check if the user has participated in this competition
        $hasParticipated = CompetitionParticipantModel::where('user_id', $userId)
            ->where('competition_id', $validated['competition_id'])
            ->exists();
            
        if (!$hasParticipated) {
            return redirect()->route('student.feedback.index')
                ->with('error', 'Anda hanya dapat memberikan feedback untuk lomba yang telah Anda ikuti.');
        }
        
        // Check if the competition has ended
        $competition = CompetitionModel::find($validated['competition_id']);
        $today = Carbon::now();
        
        if (!$competition || $today->lessThanOrEqualTo($competition->end_date)) {
            return redirect()->route('student.feedback.index')
                ->with('error', 'Anda hanya dapat memberikan feedback setelah lomba selesai.');
        }
        
        CompetitionFeedback::create($validated);
        
        return redirect()->route('student.feedback.index')
            ->with('success', 'Feedback berhasil disimpan. Terima kasih atas masukan Anda!');
    }

    public function show($id)
    {
        $feedback = CompetitionFeedback::findOrFail($id);
        
        if ($feedback->user_id !== Auth::id() && !Auth::user()->isAdmin()) {
            abort(403, 'Unauthorized action.');
        }
        
        return view('student.feedback.show', compact('feedback'));
    }

    public function destroy($id)
    {
        $feedback = CompetitionFeedback::findOrFail($id);
        
        if ($feedback->user_id !== Auth::id() && !Auth::user()->isAdmin()) {
            abort(403, 'Unauthorized action.');
        }
        
        $feedback->delete();
        
        return redirect()->route('student.feedback.index')
            ->with('success', 'Feedback berhasil dihapus.');
    }
    
    public function list()
    {
        if (Auth::user()->role === 'mahasiswa') {
            $feedbackData = CompetitionFeedback::where('user_id', Auth::id())
                ->with('competition')
                ->orderBy('created_at', 'desc')
                ->get()
                ->map(function ($feedback) {
                    return [
                        'id' => $feedback->id,
                        'competition_name' => $feedback->competition->name ?? 'Unknown Competition',
                        'overall_rating' => $feedback->overall_rating,
                        'strengths' => \Str::limit($feedback->strengths, 100),
                        'improvements' => \Str::limit($feedback->improvements, 100),
                        'created_at' => $feedback->created_at->toDateTimeString()
                    ];
                });
        } 
        else {
            $feedbackData = CompetitionFeedback::with(['competition', 'user'])
                ->orderBy('created_at', 'desc')
                ->get()
                ->map(function ($feedback) {
                    return [
                        'id' => $feedback->id,
                        'competition_name' => $feedback->competition->name ?? 'Unknown Competition',
                        'user_name' => $feedback->user->name ?? 'Unknown User',
                        'overall_rating' => $feedback->overall_rating,
                        'strengths' => \Str::limit($feedback->strengths, 100),
                        'improvements' => \Str::limit($feedback->improvements, 100),
                        'created_at' => $feedback->created_at->toDateTimeString()
                    ];
                });
        }
        
        return response()->json([
            'success' => true,
            'feedback' => $feedbackData
        ]);
    }

    /**
     * Check if the student can provide feedback for a competition
     * 
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function checkFeedbackEligibility(Request $request)
    {
        $request->validate([
            'competition_id' => 'required|exists:competitions,id',
        ]);
        
        $userId = Auth::id();
        $competitionId = $request->competition_id;
        $today = Carbon::now();
        
        // Check if the user has already submitted feedback
        $existingFeedback = CompetitionFeedback::where('user_id', $userId)
            ->where('competition_id', $competitionId)
            ->exists();
            
        if ($existingFeedback) {
            return response()->json([
                'eligible' => false,
                'message' => 'Anda sudah memberikan feedback untuk lomba ini sebelumnya.'
            ]);
        }
        
        // Check if the user has participated in this competition
        $hasParticipated = CompetitionParticipantModel::where('user_id', $userId)
            ->where('competition_id', $competitionId)
            ->exists();
            
        if (!$hasParticipated) {
            return response()->json([
                'eligible' => false,
                'message' => 'Anda hanya dapat memberikan feedback untuk lomba yang telah Anda ikuti.'
            ]);
        }
        
        // Check if the competition has ended
        $competition = CompetitionModel::find($competitionId);
        
        if (!$competition) {
            return response()->json([
                'eligible' => false,
                'message' => 'Lomba tidak ditemukan.'
            ]);
        }
        
        if ($today->lessThanOrEqualTo($competition->end_date)) {
            return response()->json([
                'eligible' => false,
                'message' => 'Anda hanya dapat memberikan feedback setelah lomba selesai.'
            ]);
        }
        
        // If all checks pass, the user is eligible to provide feedback
        return response()->json([
            'eligible' => true,
            'message' => 'Anda dapat memberikan feedback untuk lomba ini.'
        ]);
    }
}