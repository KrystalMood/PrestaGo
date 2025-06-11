<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\CompetitionFeedback;
use App\Models\CompetitionModel;
use App\Models\UserModel;
use Faker\Factory as Faker;

class CompetitionFeedbackSeeder extends Seeder
{
    public function run(): void
    {
        $faker = Faker::create('id_ID');
        
        $completedCompetitions = CompetitionModel::where('status', 'completed')->pluck('id')->toArray();
        
        if (empty($completedCompetitions)) {
            $completedCompetitions = CompetitionModel::take(3)->pluck('id')->toArray();
        }
        
        $studentUsers = UserModel::where('role', 'mahasiswa')->pluck('id')->toArray();
        
        if (empty($studentUsers)) {
            $studentUsers = UserModel::take(5)->pluck('id')->toArray();
        }
        
        $feedbackCount = count($studentUsers) * 2;
        
        $feedbacks = [];
        
        for ($i = 0; $i < $feedbackCount; $i++) {
            $userId = $faker->randomElement($studentUsers);
            $competitionId = $faker->randomElement($completedCompetitions);
            
            $exists = false;
            foreach ($feedbacks as $feedback) {
                if ($feedback['user_id'] == $userId && $feedback['competition_id'] == $competitionId) {
                    $exists = true;
                    break;
                }
            }
            
            if ($exists) {
                continue;
            }
            
            $feedbacks[] = [
                'user_id' => $userId,
                'competition_id' => $competitionId,
                'overall_rating' => $faker->numberBetween(3, 5),
                'organization_rating' => $faker->numberBetween(2, 5),
                'judging_rating' => $faker->numberBetween(2, 5),
                'learning_rating' => $faker->numberBetween(3, 5),
                'materials_rating' => $faker->numberBetween(3, 5),
                'strengths' => $faker->paragraphs(2, true),
                'improvements' => $faker->paragraphs(2, true),
                'skills_gained' => $faker->words(rand(3, 8), true),
                'recommendation' => $faker->randomElement(['yes', 'maybe', 'no']),
                'additional_comments' => $faker->optional(0.7)->paragraphs(1, true),
                'created_at' => $faker->dateTimeBetween('-6 months', 'now'),
                'updated_at' => now(),
            ];
        }
        
        foreach ($feedbacks as $feedback) {
            CompetitionFeedback::create($feedback);
        }
    }
}