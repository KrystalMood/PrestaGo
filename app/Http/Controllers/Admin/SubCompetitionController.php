<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\CompetitionModel;
use App\Models\SubCompetitionModel;
use App\Models\UserModel;
use App\Models\SubCompetitionParticipantModel;
use App\Models\CategoryModel;
use App\Models\SkillModel;
use App\Models\SubCompetitionSkillModel;

class SubCompetitionController extends Controller
{
    public function index($competitionId)
    {
        $competition = CompetitionModel::findOrFail($competitionId);
        $subCompetitions = $competition->subCompetitions()->with(['category', 'skills'])->get();
        $categories = CategoryModel::all();
        $skills = SkillModel::all();
        
        return view('admin.competitions.sub-competitions.index', compact('competition', 'subCompetitions', 'categories', 'skills'));
    }

    public function store(Request $request, $competitionId)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'category_id' => 'nullable|exists:categories,id',
            'start_date' => 'nullable|date',
            'end_date' => 'nullable|date|after_or_equal:start_date',
            'registration_start' => 'nullable|date',
            'registration_end' => 'nullable|date|after_or_equal:registration_start',
            'competition_date' => 'nullable|date',
            'registration_link' => 'nullable|url|max:255',
            'requirements' => 'nullable|string',
            'status' => 'nullable|string|max:20',
        ]);

        $competition = CompetitionModel::findOrFail($competitionId);
        
        $subCompetition = new SubCompetitionModel();
        $subCompetition->name = $request->name;
        $subCompetition->description = $request->description;
        $subCompetition->competition_id = $competition->id;
        $subCompetition->category_id = $request->category_id;
        $subCompetition->start_date = $request->start_date;
        $subCompetition->end_date = $request->end_date;
        $subCompetition->registration_start = $request->registration_start;
        $subCompetition->registration_end = $request->registration_end;
        $subCompetition->competition_date = $request->competition_date;
        $subCompetition->registration_link = $request->registration_link;
        $subCompetition->requirements = $request->requirements;
        $subCompetition->status = $request->status ?? 'upcoming';
        $subCompetition->save();

        return response()->json([
            'success' => true,
            'message' => 'Sub-competition created successfully',
            'data' => $subCompetition
        ]);
    }

    public function show($competitionId, $subCompetitionId)
    {
        $competition = CompetitionModel::findOrFail($competitionId);
        $subCompetition = SubCompetitionModel::with(['category', 'skills'])->findOrFail($subCompetitionId);
        
        return response()->json([
            'success' => true,
            'data' => $subCompetition
        ]);
    }

    public function update(Request $request, $competitionId, $subCompetitionId)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'category_id' => 'nullable|exists:categories,id',
            'start_date' => 'nullable|date',
            'end_date' => 'nullable|date|after_or_equal:start_date',
            'registration_start' => 'nullable|date',
            'registration_end' => 'nullable|date|after_or_equal:registration_start',
            'competition_date' => 'nullable|date',
            'registration_link' => 'nullable|url|max:255',
            'requirements' => 'nullable|string',
            'status' => 'nullable|string|max:20',
        ]);

        $competition = CompetitionModel::findOrFail($competitionId);
        $subCompetition = SubCompetitionModel::findOrFail($subCompetitionId);
        
        $subCompetition->name = $request->name;
        $subCompetition->description = $request->description;
        $subCompetition->category_id = $request->category_id;
        $subCompetition->start_date = $request->start_date;
        $subCompetition->end_date = $request->end_date;
        $subCompetition->registration_start = $request->registration_start;
        $subCompetition->registration_end = $request->registration_end;
        $subCompetition->competition_date = $request->competition_date;
        $subCompetition->registration_link = $request->registration_link;
        $subCompetition->requirements = $request->requirements;
        $subCompetition->status = $request->status ?? $subCompetition->status;
        $subCompetition->save();

        return response()->json([
            'success' => true,
            'message' => 'Sub-competition updated successfully',
            'data' => $subCompetition
        ]);
    }

    public function destroy($competitionId, $subCompetitionId)
    {
        $competition = CompetitionModel::findOrFail($competitionId);
        $subCompetition = SubCompetitionModel::findOrFail($subCompetitionId);
        
        $subCompetition->delete();

        return response()->json([
            'success' => true,
            'message' => 'Sub-competition deleted successfully'
        ]);
    }

    public function participants($competitionId, $subCompetitionId)
    {
        $competition = CompetitionModel::findOrFail($competitionId);
        $subCompetition = SubCompetitionModel::findOrFail($subCompetitionId);
        $participants = $subCompetition->participants;
        $students = UserModel::where('role', 'student')->get();
        
        return view('admin.competitions.sub-competitions.participants', compact('competition', 'subCompetition', 'participants', 'students'));
    }

    public function addParticipant(Request $request, $competitionId, $subCompetitionId)
    {
        $request->validate([
            'student_id' => 'required|exists:users,id',
            'team_name' => 'nullable|string|max:255',
            'advisor_name' => 'nullable|string|max:255',
            'status' => 'nullable|string|max:20',
        ]);

        $subCompetition = SubCompetitionModel::findOrFail($subCompetitionId);
        
        $participant = new SubCompetitionParticipantModel();
        $participant->sub_competition_id = $subCompetition->id;
        $participant->user_id = $request->student_id;
        $participant->team_name = $request->team_name;
        $participant->advisor_name = $request->advisor_name;
        $participant->status = $request->status ?? 'registered';
        $participant->save();

        return redirect()->back()->with('success', 'Participant added successfully');
    }

    public function removeParticipant($competitionId, $subCompetitionId, $participantId)
    {
        $participant = SubCompetitionParticipantModel::findOrFail($participantId);
        $participant->delete();

        return redirect()->back()->with('success', 'Participant removed successfully');
    }
    
    public function showParticipantAjax($competitionId, $subCompetitionId, $participantId)
    {
        $competition = CompetitionModel::findOrFail($competitionId);
        $subCompetition = SubCompetitionModel::findOrFail($subCompetitionId);
        $participant = SubCompetitionParticipantModel::with('user')->findOrFail($participantId);
        
        return response()->json([
            'success' => true,
            'html' => view('admin.competitions.sub-competitions.participant-detail-ajax', compact('competition', 'subCompetition', 'participant'))->render(),
            'participant' => $participant
        ]);
    }
    
    public function updateParticipant(Request $request, $competitionId, $subCompetitionId, $participantId)
    {
        $request->validate([
            'status' => 'required|in:registered,pending',
            'team_name' => 'nullable|string|max:255',
        ]);

        $participant = SubCompetitionParticipantModel::findOrFail($participantId);
        $participant->status = $request->status;
        if ($request->has('team_name')) {
            $participant->team_name = $request->team_name;
        }
        $participant->save();

        if ($request->ajax() || $request->wantsJson()) {
            return response()->json([
                'success' => true,
                'message' => 'Status peserta berhasil diperbarui',
                'participant' => $participant
            ]);
        }

        return redirect()->route('admin.competitions.sub-competitions.participants', [
            'competition' => $competitionId,
            'sub_competition' => $subCompetitionId
        ])->with('success', 'Status peserta berhasil diperbarui');
    }
    
    public function skills($competitionId, $subCompetitionId)
    {
        $competition = CompetitionModel::findOrFail($competitionId);
        $subCompetition = SubCompetitionModel::with('skills')->findOrFail($subCompetitionId);
        $allSkills = SkillModel::all();
        
        return view('admin.competitions.sub-competitions.skills', compact('competition', 'subCompetition', 'allSkills'));
    }
    
    public function addSkill(Request $request, $competitionId, $subCompetitionId)
    {
        $request->validate([
            'skill_id' => 'required|exists:skills,id',
            'importance_level' => 'required|integer|min:1|max:10',
            'weight_value' => 'nullable|numeric',
            'criterion_type' => 'nullable|string|in:benefit,cost',
        ]);
        
        $subCompetition = SubCompetitionModel::findOrFail($subCompetitionId);
        
        $exists = $subCompetition->skills()->where('skill_id', $request->skill_id)->exists();
        
        if ($exists) {
            return response()->json([
                'success' => false,
                'message' => 'Skill already added to this sub-competition'
            ], 422);
        }
        
        $subCompetition->skills()->attach($request->skill_id, [
            'importance_level' => $request->importance_level,
            'weight_value' => $request->weight_value ?? 1.0,
            'criterion_type' => $request->criterion_type ?? 'benefit',
            'ahp_priority' => 0.0
        ]);
        
        return response()->json([
            'success' => true,
            'message' => 'Skill added to sub-competition successfully'
        ]);
    }
    
    public function updateSkill(Request $request, $competitionId, $subCompetitionId, $skillId)
    {
        $request->validate([
            'importance_level' => 'required|integer|min:1|max:10',
            'weight_value' => 'nullable|numeric',
            'criterion_type' => 'nullable|string|in:benefit,cost',
        ]);
        
        $subCompetition = SubCompetitionModel::findOrFail($subCompetitionId);
        
        $subCompetition->skills()->updateExistingPivot($skillId, [
            'importance_level' => $request->importance_level,
            'weight_value' => $request->weight_value ?? 1.0,
            'criterion_type' => $request->criterion_type ?? 'benefit',
        ]);
        
        return response()->json([
            'success' => true,
            'message' => 'Skill updated successfully'
        ]);
    }
    
    public function removeSkill($competitionId, $subCompetitionId, $skillId)
    {
        $subCompetition = SubCompetitionModel::findOrFail($subCompetitionId);
        $subCompetition->skills()->detach($skillId);
        
        return response()->json([
            'success' => true,
            'message' => 'Skill removed from sub-competition successfully'
        ]);
    }
} 