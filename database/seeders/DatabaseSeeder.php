<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Schema;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        $this->call([
            LevelSeeder::class,
            UserSeeder::class,
            StudyProgramSeeder::class,
            PeriodSeeder::class,
            SkillSeeder::class,
            CategorySeeder::class,
            InterestAreaSeeder::class,
            CompetitionSeeder::class,
            MahasiswaSeeder::class,
            DosenSeeder::class,
            SubCompetitionSeeder::class,
            ActivitySeeder::class,
            AchievementSeeder::class,
            CompetitionParticipantSeeder::class,
            CompetitionFeedbackSeeder::class,
        ]);
        
        if (Schema::hasTable('sub_competition_skills')) {
            $this->call(SubCompetitionSkillSeeder::class);
        }
    }
}
