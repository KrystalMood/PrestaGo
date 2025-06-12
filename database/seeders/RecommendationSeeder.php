<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\RecommendationModel;
use App\Models\UserModel;
use App\Models\CompetitionModel;
use App\Http\Controllers\Admin\RecommendationController;

class RecommendationSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $users = UserModel::all();
        $competitions = CompetitionModel::all();
        
        if ($users->isEmpty() || $competitions->isEmpty()) {
            $this->command->error('No users or competitions found. Please seed them first.');
            return;
        }
        
        $studentUsers = $users->filter(function($user) {
            return $user->level && $user->level->level_kode === 'MHS';
        })->take(3);
        
        foreach ($studentUsers as $student) {
            foreach ($competitions->take(2) as $competition) {
                $recommendation = RecommendationModel::create([
                    'user_id' => $student->id,
                    'competition_id' => $competition->id,
                    'match_score' => rand(65, 95),
                    'status' => 'pending',
                    'recommended_by' => 'system',
                    'for_lecturer' => false,
                    'match_factors' => json_encode([
                        'skills' => rand(60, 90),
                        'achievements' => rand(50, 80),
                        'academic' => rand(70, 95),
                        'experience' => rand(40, 75)
                    ])
                ]);
                
                $this->command->info("Created student recommendation ID: {$recommendation->id}");
            }
        }
        
        $lecturerUsers = $users->filter(function($user) {
            return $user->level && $user->level->level_kode === 'DSN';
        })->take(2);
        
        foreach ($lecturerUsers as $lecturer) {
            foreach ($competitions->take(2) as $competition) {
                $recommendation = RecommendationModel::create([
                    'user_id' => $lecturer->id,
                    'competition_id' => $competition->id,
                    'match_score' => rand(65, 95),
                    'status' => 'pending',
                    'recommended_by' => 'system',
                    'for_lecturer' => true,
                    'match_factors' => json_encode([
                        'skills' => rand(60, 90),
                        'interests' => rand(50, 80),
                        'competition_level' => rand(70, 95),
                        'deadline' => rand(40, 75),
                        'activity_rating' => rand(50, 85)
                    ])
                ]);
                
                $this->command->info("Created lecturer recommendation ID: {$recommendation->id}");
            }
        }
        
        $this->command->info('Generating DSS calculation details...');
        
        $controller = app(RecommendationController::class);
        
        $studentRecommendations = RecommendationModel::where('for_lecturer', false)->get();
        foreach ($studentRecommendations as $recommendation) {
            try {
                $method = new \ReflectionMethod($controller, 'generateAHPCalculationForRecommendation');
                $method->setAccessible(true);
                $result = $method->invoke($controller, $recommendation);
                
                if ($result) {
                    $this->command->info("Generated AHP calculation for student recommendation ID: {$recommendation->id}");
                }
            } catch (\Exception $e) {
                $this->command->error("Error generating AHP calculation for recommendation ID {$recommendation->id}: {$e->getMessage()}");
            }
        }
        
        $lecturerRecommendations = RecommendationModel::where('for_lecturer', true)->get();
        foreach ($lecturerRecommendations as $recommendation) {
            try {
                $method = new \ReflectionMethod($controller, 'generateWPCalculationForRecommendation');
                $method->setAccessible(true);
                $result = $method->invoke($controller, $recommendation);
                
                if ($result) {
                    $this->command->info("Generated WP calculation for lecturer recommendation ID: {$recommendation->id}");
                }
            } catch (\Exception $e) {
                $this->command->error("Error generating WP calculation for recommendation ID {$recommendation->id}: {$e->getMessage()}");
            }
        }
    }
} 