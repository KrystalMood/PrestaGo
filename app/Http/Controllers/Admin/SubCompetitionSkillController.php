<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\CompetitionModel;
use App\Models\SubCompetitionModel;
use App\Models\SkillModel;
use App\Models\SubCompetitionSkillModel;

class SubCompetitionSkillController extends Controller
{
    public function index($competitionId, $subCompetitionId)
    {
        try {
            $competition = CompetitionModel::findOrFail($competitionId);
            $subCompetition = SubCompetitionModel::with('skills')->findOrFail($subCompetitionId);
            
            if ($subCompetition->competition_id != $competition->id) {
                $correctCompetitionId = $subCompetition->competition_id;
                return redirect()
                    ->route('admin.competitions.sub-competitions.skills', [
                        'competition' => $correctCompetitionId,
                        'sub_competition' => $subCompetitionId
                    ])
                    ->with('info', 'Anda telah dialihkan ke kompetisi yang benar.');
            }
            
            $allSkills = SkillModel::orderBy('category', 'asc')->orderBy('name', 'asc')->get();
            
            return view('admin.competitions.sub-competitions.skills.index', compact('competition', 'subCompetition', 'allSkills'));
        } catch (\Exception $e) {
            return redirect()
                ->route('admin.competitions.index')
                ->with('error', 'Terjadi kesalahan: ' . $e->getMessage());
        }
    }

    public function store(Request $request, $competitionId, $subCompetitionId)
    {
        try {
            $validated = $request->validate([
                'skill_id' => 'required|exists:skills,id',
                'importance_level' => 'required|integer|min:1|max:10',
                'weight_value' => 'nullable|numeric|min:0',
                'criterion_type' => 'nullable|string|in:benefit,cost',
            ]);
            
            $competition = CompetitionModel::findOrFail($competitionId);
            $subCompetition = SubCompetitionModel::findOrFail($subCompetitionId);
            
            if ($subCompetition->competition_id != $competition->id) {
                return response()->json([
                    'success' => false,
                    'message' => 'Sub-competition does not belong to this competition'
                ], 422);
            }
            
            $exists = $subCompetition->skills()->where('skill_id', $request->skill_id)->exists();
            
            if ($exists) {
                return response()->json([
                    'success' => false,
                    'message' => 'Skill already added to this sub-competition'
                ], 422);
            }
            
            $skill = SkillModel::findOrFail($request->skill_id);
            
            $subCompetition->skills()->attach($request->skill_id, [
                'importance_level' => $request->importance_level,
                'weight_value' => $request->weight_value ?? 1.0,
                'criterion_type' => $request->criterion_type ?? 'benefit',
                'ahp_priority' => 0.0
            ]);
            
            return response()->json([
                'success' => true,
                'message' => 'Skill berhasil ditambahkan',
                'data' => [
                    'skill' => $skill,
                    'importance_level' => $request->importance_level,
                    'weight_value' => $request->weight_value ?? 1.0,
                    'criterion_type' => $request->criterion_type ?? 'benefit',
                ]
            ]);
        } catch (\Illuminate\Validation\ValidationException $e) {
            return response()->json([
                'success' => false,
                'message' => 'Validasi gagal',
                'errors' => $e->errors()
            ], 422);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Terjadi kesalahan saat menambahkan skill: ' . $e->getMessage()
            ], 500);
        }
    }

    public function update(Request $request, $competitionId, $subCompetitionId, $skillId)
    {
        try {
            $request->validate([
                'importance_level' => 'required|integer|min:1|max:10',
                'weight_value' => 'nullable|numeric|min:0',
                'criterion_type' => 'nullable|string|in:benefit,cost',
            ]);
            
            $competition = CompetitionModel::findOrFail($competitionId);
            $subCompetition = SubCompetitionModel::findOrFail($subCompetitionId);
            
            if ($subCompetition->competition_id != $competition->id) {
                return response()->json([
                    'success' => false,
                    'message' => 'Sub-competition does not belong to this competition'
                ], 422);
            }
            
            $skill = SkillModel::findOrFail($skillId);
            
            $subCompetition->skills()->updateExistingPivot($skillId, [
                'importance_level' => $request->importance_level,
                'weight_value' => $request->weight_value ?? 1.0,
                'criterion_type' => $request->criterion_type ?? 'benefit',
            ]);
            
            return response()->json([
                'success' => true,
                'message' => 'Skill berhasil diperbarui',
                'data' => [
                    'skill' => $skill,
                    'importance_level' => $request->importance_level,
                    'weight_value' => $request->weight_value ?? 1.0,
                    'criterion_type' => $request->criterion_type ?? 'benefit',
                ]
            ]);
        } catch (\Illuminate\Validation\ValidationException $e) {
            return response()->json([
                'success' => false,
                'message' => 'Validasi gagal',
                'errors' => $e->errors()
            ], 422);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Terjadi kesalahan saat memperbarui skill: ' . $e->getMessage()
            ], 500);
        }
    }

    public function destroy($competitionId, $subCompetitionId, $skillId)
    {
        try {
            $competition = CompetitionModel::findOrFail($competitionId);
            $subCompetition = SubCompetitionModel::findOrFail($subCompetitionId);
            
            if ($subCompetition->competition_id != $competition->id) {
                return response()->json([
                    'success' => false,
                    'message' => 'Sub-competition does not belong to this competition'
                ], 422);
            }
            
            $exists = $subCompetition->skills()->where('skill_id', $skillId)->exists();
            
            if (!$exists) {
                return response()->json([
                    'success' => false,
                    'message' => 'Skill tidak ditemukan pada sub-kompetisi ini'
                ], 404);
            }
            
            $subCompetition->skills()->detach($skillId);
            
            return response()->json([
                'success' => true,
                'message' => 'Skill berhasil dihapus dari sub-kompetisi'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Terjadi kesalahan saat menghapus skill: ' . $e->getMessage()
            ], 500);
        }
    }
    
    public function getAvailableSkills($competitionId, $subCompetitionId)
    {
        try {
            $competition = CompetitionModel::findOrFail($competitionId);
            $subCompetition = SubCompetitionModel::findOrFail($subCompetitionId);
            
            if ($subCompetition->competition_id != $competition->id) {
                return response()->json([
                    'success' => false,
                    'message' => 'Sub-competition does not belong to this competition'
                ], 422);
            }
            
            $existingSkillIds = $subCompetition->skills()->pluck('skill_id')->toArray();
            
            $availableSkills = SkillModel::whereNotIn('id', $existingSkillIds)
                ->orderBy('category', 'asc')
                ->orderBy('name', 'asc')
                ->get();
            
            return response()->json([
                'success' => true,
                'message' => 'Available skills retrieved successfully',
                'data' => $availableSkills
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Terjadi kesalahan saat mengambil data skill: ' . $e->getMessage()
            ], 500);
        }
    }
}
