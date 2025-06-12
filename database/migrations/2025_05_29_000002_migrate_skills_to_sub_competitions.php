<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;
use App\Models\CompetitionModel;
use App\Models\SubCompetitionModel;
use App\Models\CompetitionSkillModel;
use App\Models\SubCompetitionSkillModel;
use App\Models\SkillModel;

return new class extends Migration
{
    public function up(): void
    {
        if (!Schema::hasTable('sub_competition_skills')) {
            return;
        }
        
        $competitionsWithSkills = DB::table('competitions')
            ->join('competition_skills', 'competitions.id', '=', 'competition_skills.competition_id')
            ->select('competitions.id')
            ->distinct()
            ->get();
        
        foreach ($competitionsWithSkills as $competition) {
            $subCompetitions = SubCompetitionModel::where('competition_id', $competition->id)->get();
            
            if ($subCompetitions->count() > 0) {
                $competitionSkills = CompetitionSkillModel::where('competition_id', $competition->id)->get();
                
                foreach ($subCompetitions as $subCompetition) {
                    foreach ($competitionSkills as $competitionSkill) {
                        $exists = SubCompetitionSkillModel::where('sub_competition_id', $subCompetition->id)
                            ->where('skill_id', $competitionSkill->skill_id)
                            ->exists();
                            
                        if (!$exists) {
                            SubCompetitionSkillModel::create([
                                'sub_competition_id' => $subCompetition->id,
                                'skill_id' => $competitionSkill->skill_id,
                                'importance_level' => $competitionSkill->importance_level,
                                'weight_value' => $competitionSkill->weight_value ?? 1.0,
                                'criterion_type' => $competitionSkill->criterion_type ?? 'benefit',
                                'ahp_priority' => $competitionSkill->ahp_priority ?? 0.0,
                                'created_at' => now(),
                                'updated_at' => now(),
                            ]);
                        }
                    }
                }
            }
        }
        
        if (Schema::hasTable('competition_skills')) {
            Schema::dropIfExists('competition_skills');
        }
    }

    public function down(): void
    {
        if (Schema::hasTable('competition_skills') && Schema::hasTable('sub_competition_skills')) {
            $subCompetitionSkills = DB::table('sub_competition_skills')
                ->join('sub_competitions', 'sub_competitions.id', '=', 'sub_competition_skills.sub_competition_id')
                ->select(
                    'sub_competitions.competition_id',
                    'sub_competition_skills.skill_id',
                    'sub_competition_skills.importance_level',
                    'sub_competition_skills.weight_value',
                    'sub_competition_skills.criterion_type',
                    'sub_competition_skills.ahp_priority'
                )
                ->get();
            
            $competitionSkillsMap = [];
            foreach ($subCompetitionSkills as $skill) {
                $key = $skill->competition_id . '-' . $skill->skill_id;
                if (!isset($competitionSkillsMap[$key])) {
                    $competitionSkillsMap[$key] = [
                        'competition_id' => $skill->competition_id,
                        'skill_id' => $skill->skill_id,
                        'importance_level' => $skill->importance_level,
                        'weight_value' => $skill->weight_value,
                        'criterion_type' => $skill->criterion_type,
                        'ahp_priority' => $skill->ahp_priority,
                        'created_at' => now(),
                        'updated_at' => now()
                    ];
                }
            }
            
            if (!empty($competitionSkillsMap)) {
                DB::table('competition_skills')->insert(array_values($competitionSkillsMap));
            }
        }
    }
};
