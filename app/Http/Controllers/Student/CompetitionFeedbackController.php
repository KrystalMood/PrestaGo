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
use App\Models\LecturerMentorshipModel;
use App\Models\LecturerRating;

class CompetitionFeedbackController extends Controller
{
    public function index()
    {
        $userId = Auth::id();
        $today = Carbon::now();
        
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
        
        $feedbackSubmitted = CompetitionFeedback::where('user_id', $userId)
            ->pluck('competition_id')
            ->toArray();
        
        $previousFeedback = CompetitionFeedback::where('user_id', $userId)
            ->with('competition')
            ->orderBy('created_at', 'desc')
            ->get();
        
        $competitionIds = $previousFeedback->pluck('competition_id')->toArray();
        $lecturerRatings = LecturerRating::whereIn('competition_id', $competitionIds)
            ->where('rated_by_user_id', $userId)
            ->with('lecturer')
            ->get()
            ->groupBy('competition_id');
        
        return view('student.feedback.index', compact('participatedCompetitions', 'feedbackSubmitted', 'previousFeedback', 'lecturerRatings'));
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
            'lecturer_ratings' => 'nullable|array',
            'lecturer_ratings.*.dosen_id' => 'required|exists:users,id',
            'lecturer_ratings.*.activity_rating' => 'required|integer|min:1|max:5',
            'lecturer_ratings.*.comments' => 'nullable|string|max:500',
        ]);

        $userId = Auth::id();
        $validated['user_id'] = $userId;
        
        $existingFeedback = CompetitionFeedback::where('user_id', $userId)
            ->where('competition_id', $validated['competition_id'])
            ->first();
            
        if ($existingFeedback) {
            return redirect()->route('student.feedback.index')
                ->with('error', 'Anda sudah memberikan feedback untuk lomba ini sebelumnya.');
        }
        
        $hasParticipated = CompetitionParticipantModel::where('user_id', $userId)
            ->where('competition_id', $validated['competition_id'])
            ->exists();
            
        if (!$hasParticipated) {
            return redirect()->route('student.feedback.index')
                ->with('error', 'Anda hanya dapat memberikan feedback untuk lomba yang telah Anda ikuti.');
        }
        
        $competition = CompetitionModel::find($validated['competition_id']);
        $today = Carbon::now();
        
        if (!$competition || $today->lessThanOrEqualTo($competition->end_date)) {
            return redirect()->route('student.feedback.index')
                ->with('error', 'Anda hanya dapat memberikan feedback setelah lomba selesai.');
        }
        
        $feedback = CompetitionFeedback::create([
            'user_id' => $userId,
            'competition_id' => $validated['competition_id'],
            'overall_rating' => $validated['overall_rating'],
            'organization_rating' => $validated['organization_rating'],
            'judging_rating' => $validated['judging_rating'],
            'learning_rating' => $validated['learning_rating'],
            'materials_rating' => $validated['materials_rating'],
            'strengths' => $validated['strengths'],
            'improvements' => $validated['improvements'],
            'skills_gained' => $validated['skills_gained'],
            'recommendation' => $validated['recommendation'],
            'additional_comments' => $validated['additional_comments'] ?? null,
        ]);
        
        if (isset($validated['lecturer_ratings']) && !empty($validated['lecturer_ratings'])) {
            foreach ($validated['lecturer_ratings'] as $lecturerRating) {
                $mentorship = LecturerMentorshipModel::where('dosen_id', $lecturerRating['dosen_id'])
                    ->where('competition_id', $validated['competition_id'])
                    ->first();
                    
                if ($mentorship) {
                    $rating = LecturerRating::updateOrCreate(
                        [
                            'dosen_id' => $lecturerRating['dosen_id'],
                            'competition_id' => $validated['competition_id'],
                            'rated_by_user_id' => $userId
                        ],
                        [
                            'activity_rating' => $lecturerRating['activity_rating'],
                            'comments' => $lecturerRating['comments'] ?? null
                        ]
                    );
                    
                    $mentorship->updateAverageRating();
                }
            }
        }
        
        return redirect()->route('student.feedback.index')
            ->with('success', 'Feedback berhasil disimpan. Terima kasih atas masukan Anda!');
    }

    public function show($id)
    {
        $feedback = CompetitionFeedback::with('competition')
            ->findOrFail($id);
        
        if ($feedback->user_id !== Auth::id() && !Auth::user()->isAdmin()) {
            abort(403, 'Unauthorized action.');
        }
        
        $lecturerRatings = LecturerRating::where([
            'competition_id' => $feedback->competition_id,
            'rated_by_user_id' => $feedback->user_id
        ])->with('lecturer')->get();
        
        return view('student.feedback.show', compact('feedback', 'lecturerRatings'));
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

    public function checkFeedbackEligibility(Request $request)
    {
        $request->validate([
            'competition_id' => 'required|exists:competitions,id',
        ]);
        
        $userId = Auth::id();
        $competitionId = $request->competition_id;
        $today = Carbon::now();
        
        $existingFeedback = CompetitionFeedback::where('user_id', $userId)
            ->where('competition_id', $competitionId)
            ->exists();
            
        if ($existingFeedback) {
            return response()->json([
                'eligible' => false,
                'message' => 'Anda sudah memberikan feedback untuk lomba ini sebelumnya.'
            ]);
        }
        
        $hasParticipated = CompetitionParticipantModel::where('user_id', $userId)
            ->where('competition_id', $competitionId)
            ->exists();
            
        if (!$hasParticipated) {
            return response()->json([
                'eligible' => false,
                'message' => 'Anda hanya dapat memberikan feedback untuk lomba yang telah Anda ikuti.'
            ]);
        }
        
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
        
        return response()->json([
            'eligible' => true,
            'message' => 'Anda dapat memberikan feedback untuk lomba ini.'
        ]);
    }

    public function getCompetitionDetails(Request $request)
    {
        $request->validate([
            'competition_id' => 'required|exists:competitions,id',
        ]);
        
        $competitionId = $request->competition_id;
        $competition = CompetitionModel::with('mentorships.lecturer')->findOrFail($competitionId);
        
        $lecturers = $competition->mentorships->map(function ($mentorship) {
            $lecturer = $mentorship->lecturer;
            
            return [
                'id' => $lecturer->id,
                'name' => $lecturer->name,
                'nip' => $lecturer->nip,
                'average_rating' => $mentorship->average_rating,
                'rating_count' => $mentorship->rating_count,
                'has_rated' => LecturerRating::where('dosen_id', $lecturer->id)
                    ->where('competition_id', $mentorship->competition_id)
                    ->where('rated_by_user_id', Auth::id())
                    ->exists()
            ];
        });
        
        return response()->json([
            'success' => true,
            'competition' => $competition,
            'lecturers' => $lecturers
        ]);
    }
}