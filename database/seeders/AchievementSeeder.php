<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\AchievementModel;

class AchievementSeeder extends Seeder
{
    public function run(): void
    {
        $achievements = [
            [
                'user_id' => 4,
                'title' => 'Achievement 1',
                'description' => 'This is achievement 1',
                'competition_name' => 'Competition 1',
                'competition_id' => 1,
                'type' => 'type1',
                'level' => 'internal',
                'date' => now(),
                'status' => 'pending',
                'verified_by' => null,
                'verified_at' => null,
                'rejected_reason' => null,
                'period_id' => null,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'user_id' => 4,
                'title' => 'Achievement 2',
                'description' => 'This is achievement 2',
                'competition_name' => 'Competition 2',
                'competition_id' => 3,
                'type' => 'type2',
                'level' => 'nasional',
                'date' => now(),
                'status' => 'completed',
                'verified_by' => 2,
                'verified_at' => now(),
                'rejected_reason' => null,
                'period_id' => null,
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ];

        foreach ($achievements as $achievement) {
            AchievementModel::create($achievement);
        }
    }
}