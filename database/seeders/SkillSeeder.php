<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\SkillModel;

class SkillSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $skills = [
            // Programming Languages
            [
                'name' => 'Java',
                'category' => 'Programming Language',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'Python',
                'category' => 'Programming Language',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'JavaScript',
                'category' => 'Programming Language',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'PHP',
                'category' => 'Programming Language',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'C++',
                'category' => 'Programming Language',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'C#',
                'category' => 'Programming Language',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            
            // Frameworks
            [
                'name' => 'Laravel',
                'category' => 'Framework',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'React',
                'category' => 'Framework',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'Angular',
                'category' => 'Framework',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'Vue.js',
                'category' => 'Framework',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'Spring Boot',
                'category' => 'Framework',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'Django',
                'category' => 'Framework',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'Flutter',
                'category' => 'Framework',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'Node.js',
                'category' => 'Framework',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            
            // Database
            [
                'name' => 'MySQL',
                'category' => 'Database',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'PostgreSQL',
                'category' => 'Database',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'MongoDB',
                'category' => 'Database',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'Oracle',
                'category' => 'Database',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            
            // Soft Skills
            [
                'name' => 'Public Speaking',
                'category' => 'Soft Skill',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'Team Leadership',
                'category' => 'Soft Skill',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'Project Management',
                'category' => 'Soft Skill',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'Problem Solving',
                'category' => 'Soft Skill',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'Critical Thinking',
                'category' => 'Soft Skill',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'Communication',
                'category' => 'Soft Skill',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            
            // Other Technical Skills
            [
                'name' => 'UI/UX Design',
                'category' => 'Design',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'Machine Learning',
                'category' => 'AI',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'DevOps',
                'category' => 'Operations',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'Cloud Computing',
                'category' => 'Infrastructure',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'IoT Development',
                'category' => 'Hardware',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'Cybersecurity',
                'category' => 'Security',
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ];

        foreach ($skills as $skill) {
            SkillModel::create($skill);
        }
    }
} 