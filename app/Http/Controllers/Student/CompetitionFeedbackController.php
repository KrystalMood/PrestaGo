<?php

namespace App\Http\Controllers\Student;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

class CompetitionFeedbackController extends Controller
{
    public function index()
    {
        return view('student.feedback.index');
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

        $validated['user_id'] = Auth::id();
        
        return redirect()->route('student.feedback.index')
            ->with('success', 'Feedback berhasil disimpan. Terima kasih atas masukan Anda!');
    }

    public function show($id)
    {
        return view('student.feedback.show', compact('feedback'));
    }

    public function destroy($id)
    {
        return redirect()->route('student.feedback.index')
            ->with('success', 'Feedback berhasil dihapus.');
    }
    
    public function list()
    {
        $feedback = [
            [
                'id' => 1,
                'competition_name' => 'Gemastik 2023 - Web Development',
                'overall_rating' => 4,
                'strengths' => 'Soal yang menarik dan menantang, panitia yang ramah dan membantu.',
                'improvements' => 'Waktu untuk pengerjaan kurang, mungkin bisa ditambah.',
                'created_at' => now()->subDays(5)->toDateTimeString()
            ],
            [
                'id' => 2,
                'competition_name' => 'Startup Weekend 2023',
                'overall_rating' => 5,
                'strengths' => 'Networking yang sangat baik, mentor yang berpengalaman.',
                'improvements' => 'Durasi pitching bisa diperpanjang untuk penjelasan lebih detail.',
                'created_at' => now()->subDays(15)->toDateTimeString()
            ]
        ];
        
        return response()->json([
            'success' => true,
            'feedback' => $feedback
        ]);
    }
}