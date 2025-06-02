<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;
use App\Models\CompetitionModel;
use App\Models\SubCompetitionModel;
use App\Models\CompetitionSkillModel;
use App\Models\SubCompetitionSkillModel;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // Check if the sub_competition_skills table exists
        if (!Schema::hasTable('sub_competition_skills')) {
            // Table doesn't exist yet, so we can't migrate data
            return;
        }
        
        // Get all competitions with skills
        $competitionsWithSkills = CompetitionModel::whereHas('skills')->with('skills')->get();
        
        foreach ($competitionsWithSkills as $competition) {
            // Get all sub-competitions for this competition
            $subCompetitions = SubCompetitionModel::where('competition_id', $competition->id)->get();
            
            if ($subCompetitions->count() > 0) {
                // Get all skills for this competition
                $competitionSkills = CompetitionSkillModel::where('competition_id', $competition->id)->get();
                
                // For each sub-competition, add the skills from the parent competition
                foreach ($subCompetitions as $subCompetition) {
                    foreach ($competitionSkills as $competitionSkill) {
                        // Check if this skill is already assigned to this sub-competition
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
        
        // After migrating all skills to sub-competitions, drop the competition_skills table
        // This ensures we don't keep redundant data in the database
        if (Schema::hasTable('competition_skills')) {
            Schema::dropIfExists('competition_skills');
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // The original migration (2024_01_01_000006_create_competition_skills_table.php) will handle
        // recreating the competition_skills table structure when rolling back migrations.
        // Here, we just need to ensure the data flow is properly handled.
        
        // If we have sub_competition_skills data and the competition_skills table exists,
        // we could migrate data back to competition_skills if needed
        if (Schema::hasTable('competition_skills') && Schema::hasTable('sub_competition_skills')) {
            // Get all sub-competitions with skills
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
            
            // Group by competition_id and skill_id to avoid duplicates
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
            
            // Insert the data back into competition_skills
            if (!empty($competitionSkillsMap)) {
                DB::table('competition_skills')->insert(array_values($competitionSkillsMap));
            }
        }
    }
};
