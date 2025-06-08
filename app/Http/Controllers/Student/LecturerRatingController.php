<?php

namespace App\Http\Controllers\Student;

use App\Http\Controllers\Controller;
use App\Models\LecturerRating;
use App\Models\LecturerMentorshipModel;
use App\Models\UserModel;
use App\Models\CompetitionModel;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class LecturerRatingController extends Controller
{
    public function store(Request $request)
    {
        $request->validate([
            'dosen_id' => 'required|exists:users,id',
            'competition_id' => 'required|exists:competitions,id',
            'activity_rating' => 'required|integer|min:1|max:5',
            'comments' => 'nullable|string|max:500'
        ]);
        
        $mentorship = LecturerMentorshipModel::where('dosen_id', $request->dosen_id)
            ->where('competition_id', $request->competition_id)
            ->first();
            
        if (!$mentorship) {
            return response()->json([
                'success' => false,
                'message' => 'Dosen ini tidak terdaftar sebagai pembimbing untuk kompetisi ini'
            ], 404);
        }
        
        $rating = LecturerRating::updateOrCreate(
            [
                'dosen_id' => $request->dosen_id,
                'competition_id' => $request->competition_id,
                'rated_by_user_id' => Auth::id()
            ],
            [
                'activity_rating' => $request->activity_rating,
                'comments' => $request->comments
            ]
        );
        
        $mentorship->updateAverageRating();
        
        return response()->json([
            'success' => true,
            'message' => 'Rating berhasil disimpan',
            'rating' => $rating
        ]);
    }
    
    public function getLecturersForCompetition($competitionId)
    {
        $mentorships = LecturerMentorshipModel::where('competition_id', $competitionId)
            ->with(['lecturer'])
            ->get()
            ->map(function ($mentorship) {
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
            'lecturers' => $mentorships
        ]);
    }
    
    public function getUserRating($competitionId, $dosenId)
    {
        $rating = LecturerRating::where('competition_id', $competitionId)
            ->where('dosen_id', $dosenId)
            ->where('rated_by_user_id', Auth::id())
            ->first();
            
        return response()->json([
            'success' => true,
            'has_rated' => $rating ? true : false,
            'rating' => $rating
        ]);
    }
} 