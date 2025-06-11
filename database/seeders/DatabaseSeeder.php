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
            UserSkillSeeder::class,
            UserInterestSeeder::class,
            SubCompetitionSeeder::class,
            ActivitySeeder::class,
            AchievementSeeder::class,
            PeriodAchievementSeeder::class,
            CompetitionParticipantSeeder::class,
            CompetitionFeedbackSeeder::class,
            FixMentorshipSeeder::class,
            RecommendationSeeder::class,
            ActivityLogSeeder::class,
        ]);
        
        if (Schema::hasTable('lecturer_ratings')) {
            $this->call(LecturerRatingSeeder::class);
        } else {
            $this->command->warn('lecturer_ratings table does not exist. Skipping LecturerRatingSeeder.');
        }
        
        if (Schema::hasTable('sub_competition_skills')) {
            $this->call(SubCompetitionSkillSeeder::class);
        }
    }
}
