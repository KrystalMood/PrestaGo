<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\CompetitionParticipantModel;
use App\Models\CompetitionModel;
use App\Models\UserModel;
use Faker\Factory as Faker;

class CompetitionParticipantSeeder extends Seeder
{
    public function run(): void
    {
        $faker = Faker::create('id_ID');
        
        $competitions = CompetitionModel::all();
        
        $studentUsers = UserModel::where('role', 'mahasiswa')->pluck('id')->toArray();
        
        if (empty($studentUsers)) {
            $studentUsers = UserModel::take(5)->pluck('id')->toArray();
        }
        
        $participants = [];
        
        foreach ($studentUsers as $userId) {
            $userCompetitions = $competitions->random(rand(2, 4));
            
            foreach ($userCompetitions as $competition) {
                $participants[] = [
                    'competition_id' => $competition->id,
                    'user_id' => $userId,
                    'team_name' => $faker->optional(0.7)->company,
                    'status' => $faker->randomElement(['registered', 'confirmed', 'completed']),
                    'notes' => $faker->optional(0.3)->sentence,
                    'created_at' => $faker->dateTimeBetween('-1 year', 'now'),
                    'updated_at' => now(),
                ];
            }
        }
        
        foreach ($participants as $participant) {
            CompetitionParticipantModel::create($participant);
        }
    }
} 