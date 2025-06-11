<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\CompetitionModel;
use App\Models\SubCompetitionModel;
use App\Models\SkillModel;
use App\Models\CompetitionSkillModel;
use App\Models\SubCompetitionSkillModel;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

class SubCompetitionSkillSeeder extends Seeder
{
    public function run(): void
    {
        if (Schema::hasTable('sub_competition_skills')) {
            DB::table('sub_competition_skills')->truncate();
        } else {
            return;
        }

        $subCompetitions = SubCompetitionModel::with('competition')->get();
        
        $subCompetitionSkills = [
            'Programming' => [
                ['name' => 'C++', 'importance_level' => 3],
                ['name' => 'Problem Solving', 'importance_level' => 3],
                ['name' => 'Algorithm', 'importance_level' => 3],
            ],
            'Competitive Programming' => [
                ['name' => 'C++', 'importance_level' => 3],
                ['name' => 'Algorithm', 'importance_level' => 3],
                ['name' => 'Data Structures', 'importance_level' => 3],
                ['name' => 'Problem Solving', 'importance_level' => 3],
            ],
            'Coding' => [
                ['name' => 'Java', 'importance_level' => 3],
                ['name' => 'Python', 'importance_level' => 2],
                ['name' => 'Problem Solving', 'importance_level' => 3],
            ],
            
            'UI/UX' => [
                ['name' => 'Figma', 'importance_level' => 3],
                ['name' => 'UI/UX Design', 'importance_level' => 3],
                ['name' => 'Adobe XD', 'importance_level' => 2],
                ['name' => 'User Research', 'importance_level' => 2],
            ],
            'Design' => [
                ['name' => 'UI/UX Design', 'importance_level' => 3],
                ['name' => 'Figma', 'importance_level' => 3],
                ['name' => 'Design Thinking', 'importance_level' => 2],
            ],
            'UX Research' => [
                ['name' => 'User Research', 'importance_level' => 3],
                ['name' => 'UI/UX Design', 'importance_level' => 2],
                ['name' => 'Data Analysis', 'importance_level' => 2],
            ],
            
            'Web' => [
                ['name' => 'HTML/CSS', 'importance_level' => 3],
                ['name' => 'JavaScript', 'importance_level' => 3],
                ['name' => 'React', 'importance_level' => 2],
            ],
            'Web Development' => [
                ['name' => 'HTML/CSS', 'importance_level' => 3],
                ['name' => 'JavaScript', 'importance_level' => 3],
                ['name' => 'React', 'importance_level' => 2],
                ['name' => 'Laravel', 'importance_level' => 2],
            ],
            'Frontend' => [
                ['name' => 'HTML/CSS', 'importance_level' => 3],
                ['name' => 'JavaScript', 'importance_level' => 3],
                ['name' => 'React', 'importance_level' => 3],
                ['name' => 'Vue.js', 'importance_level' => 2],
            ],
            'Backend' => [
                ['name' => 'PHP', 'importance_level' => 3],
                ['name' => 'Laravel', 'importance_level' => 3],
                ['name' => 'MySQL', 'importance_level' => 3],
                ['name' => 'Node.js', 'importance_level' => 2],
            ],
            
            'Mobile' => [
                ['name' => 'Flutter', 'importance_level' => 3],
                ['name' => 'Java', 'importance_level' => 2],
                ['name' => 'Swift', 'importance_level' => 2],
            ],
            'Android' => [
                ['name' => 'Java', 'importance_level' => 3],
                ['name' => 'Kotlin', 'importance_level' => 3],
                ['name' => 'Android SDK', 'importance_level' => 3],
            ],
            'iOS' => [
                ['name' => 'Swift', 'importance_level' => 3],
                ['name' => 'iOS Development', 'importance_level' => 3],
                ['name' => 'XCode', 'importance_level' => 2],
            ],
            
            'IoT' => [
                ['name' => 'IoT Development', 'importance_level' => 3],
                ['name' => 'Python', 'importance_level' => 2],
                ['name' => 'C++', 'importance_level' => 2],
                ['name' => 'Arduino', 'importance_level' => 3],
            ],
            'Hardware' => [
                ['name' => 'Arduino', 'importance_level' => 3],
                ['name' => 'Raspberry Pi', 'importance_level' => 3],
                ['name' => 'C++', 'importance_level' => 2],
            ],
            
            'Data' => [
                ['name' => 'Python', 'importance_level' => 3],
                ['name' => 'Data Analysis', 'importance_level' => 3],
                ['name' => 'SQL', 'importance_level' => 2],
            ],
            'Data Science' => [
                ['name' => 'Python', 'importance_level' => 3],
                ['name' => 'Machine Learning', 'importance_level' => 3],
                ['name' => 'Data Analysis', 'importance_level' => 3],
                ['name' => 'Statistics', 'importance_level' => 2],
            ],
            'Machine Learning' => [
                ['name' => 'Python', 'importance_level' => 3],
                ['name' => 'Machine Learning', 'importance_level' => 3],
                ['name' => 'TensorFlow', 'importance_level' => 3],
                ['name' => 'Deep Learning', 'importance_level' => 2],
            ],
            
            'Business' => [
                ['name' => 'Project Management', 'importance_level' => 3],
                ['name' => 'Public Speaking', 'importance_level' => 3],
                ['name' => 'Business Analysis', 'importance_level' => 3],
            ],
            'Presentation' => [
                ['name' => 'Public Speaking', 'importance_level' => 3],
                ['name' => 'Communication', 'importance_level' => 3],
                ['name' => 'Presentation Skills', 'importance_level' => 3],
            ],
        ];
        
        $processedSubCompetitions = [];
        
        foreach ($subCompetitions as $subCompetition) {
            $matched = false;
            
            foreach ($subCompetitionSkills as $category => $skills) {
                if (stripos($subCompetition->name, $category) !== false) {
                    $this->assignSkillsToSubCompetition($subCompetition, $skills);
                    $processedSubCompetitions[] = $subCompetition->id;
                    $matched = true;
                    break; 
                }
            }
            
            if (!$matched && $subCompetition->competition && $subCompetition->competition->name) {
                foreach ($subCompetitionSkills as $category => $skills) {
                    if (stripos($subCompetition->competition->name, $category) !== false) {
                        $this->assignSkillsToSubCompetition($subCompetition, $skills);
                        $processedSubCompetitions[] = $subCompetition->id;
                        $matched = true;
                        break;
                    }
                }
            }
        }
        
        foreach ($subCompetitions as $subCompetition) {
            if (!in_array($subCompetition->id, $processedSubCompetitions)) {
                $categories = array_keys($subCompetitionSkills);
                $categoryIndex = $subCompetition->id % count($categories);
                $category = $categories[$categoryIndex];
                
                $this->assignSkillsToSubCompetition($subCompetition, $subCompetitionSkills[$category]);
            }
        }
    }
    
    private function assignSkillsToSubCompetition($subCompetition, $skills)
    {
        foreach ($skills as $skillData) {
            $skill = SkillModel::where('name', $skillData['name'])->first();
            
            if (!$skill) {
                $skill = SkillModel::create([
                    'name' => $skillData['name'],
                    'category' => $this->getCategoryForSkill($skillData['name']),
                    'created_at' => now(),
                    'updated_at' => now(),
                ]);
            }
            
            $exists = SubCompetitionSkillModel::where('sub_competition_id', $subCompetition->id)
                ->where('skill_id', $skill->id)
                ->exists();
                
            if (!$exists) {
                SubCompetitionSkillModel::create([
                    'sub_competition_id' => $subCompetition->id,
                    'skill_id' => $skill->id,
                    'importance_level' => $skillData['importance_level'],
                    'weight_value' => 1.0,
                    'criterion_type' => 'benefit',
                    'ahp_priority' => 0.0,
                    'created_at' => now(),
                    'updated_at' => now(),
                ]);
            }
        }
    }
    
    private function getCategoryForSkill($skillName)
    {
        $categories = [
            'Programming Language' => ['Java', 'Python', 'C++', 'Swift', 'Kotlin'],
            'Framework' => ['React', 'Laravel', 'Flutter', 'Angular', 'Vue.js', 'Node.js', 'TensorFlow'],
            'Web Technology' => ['HTML/CSS', 'JavaScript', 'PHP', 'MySQL'],
            'Design' => ['UI/UX Design', 'Figma', 'Adobe XD', 'Design Thinking'],
            'AI' => ['Machine Learning', 'Data Analysis', 'Deep Learning', 'Statistics'],
            'Hardware' => ['IoT Development', 'Arduino', 'Raspberry Pi'],
            'Soft Skill' => ['Problem Solving', 'Algorithm', 'Critical Thinking', 'Communication', 'Presentation Skills', 'Public Speaking'],
            'Mobile' => ['Android SDK', 'iOS Development', 'XCode'],
            'Business' => ['Project Management', 'Business Analysis'],
            'Research' => ['User Research'],
        ];
        
        foreach ($categories as $category => $skills) {
            if (in_array($skillName, $skills)) {
                return $category;
            }
        }
        
        return 'Other';
    }
}
