<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\CompetitionModel;
use App\Models\SkillModel;
use App\Models\CompetitionSkillModel;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

class CompetitionSkillSeeder extends Seeder
{
    public function run(): void
    {
        if (!Schema::hasTable('competition_skills')) {
            return;
        }
        
        DB::table('competition_skills')->truncate();

        $competitions = CompetitionModel::all();
        
        $importanceLevels = [
            'high' => 3,
            'medium' => 2,
            'low' => 1
        ];
        
        $competitionSkills = [
            'Gemastik XVI' => [
                ['name' => 'Java', 'importance_level' => 'high'],
                ['name' => 'Python', 'importance_level' => 'high'],
                ['name' => 'Problem Solving', 'importance_level' => 'high'],
                ['name' => 'Critical Thinking', 'importance_level' => 'high'],
                ['name' => 'Team Leadership', 'importance_level' => 'medium'],
            ],
            'Competitive Programming Polinema' => [
                ['name' => 'C++', 'importance_level' => 'high'],
                ['name' => 'Java', 'importance_level' => 'high'],
                ['name' => 'Python', 'importance_level' => 'medium'],
                ['name' => 'Problem Solving', 'importance_level' => 'high'],
                ['name' => 'Critical Thinking', 'importance_level' => 'high'],
            ],
            'Web Design Competition 2024' => [
                ['name' => 'HTML/CSS', 'importance_level' => 'high'],
                ['name' => 'JavaScript', 'importance_level' => 'high'],
                ['name' => 'UI/UX Design', 'importance_level' => 'high'],
                ['name' => 'React', 'importance_level' => 'medium'],
                ['name' => 'Laravel', 'importance_level' => 'medium'],
            ],
            'Hackathon IoT Polinema' => [
                ['name' => 'IoT Development', 'importance_level' => 'high'],
                ['name' => 'Python', 'importance_level' => 'high'],
                ['name' => 'C++', 'importance_level' => 'medium'],
                ['name' => 'Problem Solving', 'importance_level' => 'high'],
                ['name' => 'Team Leadership', 'importance_level' => 'high'],
            ],
            'Kompetisi Bisnis Plan' => [
                ['name' => 'Project Management', 'importance_level' => 'high'],
                ['name' => 'Public Speaking', 'importance_level' => 'high'],
                ['name' => 'Communication', 'importance_level' => 'high'],
            ],
            'Lomba Karya Tulis Ilmiah Nasional' => [
                ['name' => 'Communication', 'importance_level' => 'high'],
                ['name' => 'Critical Thinking', 'importance_level' => 'high'],
                ['name' => 'Public Speaking', 'importance_level' => 'medium'],
            ],
            'UI/UX Design Challenge' => [
                ['name' => 'UI/UX Design', 'importance_level' => 'high'],
                ['name' => 'Figma', 'importance_level' => 'high'],
                ['name' => 'Communication', 'importance_level' => 'medium'],
                ['name' => 'Problem Solving', 'importance_level' => 'high'],
            ],
        ];

        foreach ($competitions as $competition) {
            if (isset($competitionSkills[$competition->name])) {
                $skills = $competitionSkills[$competition->name];
                
                foreach ($skills as $skillData) {
                    $skill = SkillModel::where('name', $skillData['name'])->first();
                    
                    if ($skill) {
                        $importanceLevel = $importanceLevels[$skillData['importance_level']];
                        
                        CompetitionSkillModel::create([
                            'competition_id' => $competition->id,
                            'skill_id' => $skill->id,
                            'importance_level' => $importanceLevel,
                            'created_at' => now(),
                            'updated_at' => now(),
                        ]);
                    }
                }
            }
        }
        
        if (!SkillModel::where('name', 'Figma')->exists()) {
            $figma = SkillModel::create([
                'name' => 'Figma',
                'category' => 'Design',
                'created_at' => now(),
                'updated_at' => now(),
            ]);
            
            $uiuxCompetition = CompetitionModel::where('name', 'UI/UX Design Challenge')->first();
            
            if ($uiuxCompetition) {
                CompetitionSkillModel::create([
                    'competition_id' => $uiuxCompetition->id,
                    'skill_id' => $figma->id,
                    'importance_level' => $importanceLevels['high'],
                    'created_at' => now(),
                    'updated_at' => now(),
                ]);
            }
        }
        
        if (!SkillModel::where('name', 'HTML/CSS')->exists()) {
            $htmlCss = SkillModel::create([
                'name' => 'HTML/CSS',
                'category' => 'Web Technology',
                'created_at' => now(),
                'updated_at' => now(),
            ]);
            
            $webDesignCompetition = CompetitionModel::where('name', 'Web Design Competition 2024')->first();
            
            if ($webDesignCompetition) {
                CompetitionSkillModel::create([
                    'competition_id' => $webDesignCompetition->id,
                    'skill_id' => $htmlCss->id,
                    'importance_level' => $importanceLevels['high'],
                    'created_at' => now(),
                    'updated_at' => now(),
                ]);
            }
        }
    }
} 