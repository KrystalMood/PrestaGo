<?php

namespace Database\Seeders;

use App\Models\AchievementModel;
use App\Models\UserModel;
use App\Models\PeriodModel;
use App\Models\CompetitionModel;
use Carbon\Carbon;
use Illuminate\Database\Seeder;

class PeriodAchievementSeeder extends Seeder
{
    public function run(): void
    {
        $periods = PeriodModel::orderBy('start_date', 'desc')->take(2)->get();
        
        if ($periods->count() < 2) {
            $this->command->error('Need at least 2 periods in the database. Run PeriodSeeder first.');
            return;
        }
        
        $currentPeriod = $periods[0];
        $previousPeriod = $periods[1];
        
        $students = UserModel::where('level_id', 3)->take(10)->get();
        if ($students->isEmpty()) {
            $this->command->error('No students found in the database.');
            return;
        }
        
        $admin = UserModel::where('level_id', 1)->first();
        if (!$admin) {
            $this->command->error('No admin found for verification.');
            return;
        }
        
        $competitions = CompetitionModel::take(5)->get();
        if ($competitions->isEmpty()) {
            $this->command->warn('No competitions found. Using null for competition_id.');
        }
        
        $types = ['Juara', 'Penghargaan', 'Sertifikasi', 'Publikasi', 'Kompetisi'];
        $levels = ['Internasional', 'Nasional', 'Regional', 'Provinsi'];
        
        $this->createAchievementsForPeriod(
            $currentPeriod,
            $students,
            $admin,
            $competitions,
            $types,
            $levels,
            15, 
            Carbon::parse($currentPeriod->start_date),
            Carbon::parse($currentPeriod->end_date)
        );
        
        $this->createAchievementsForPeriod(
            $previousPeriod,
            $students,
            $admin,
            $competitions,
            $types,
            $levels,
            10, 
            Carbon::parse($previousPeriod->start_date),
            Carbon::parse($previousPeriod->end_date)
        );
        
        $this->command->info('Period-based achievements seeded successfully.');
    }
    
    private function createAchievementsForPeriod($period, $students, $admin, $competitions, $types, $levels, $count, $startDate, $endDate)
    {
        for ($i = 0; $i < $count; $i++) {
            $student = $students->random();
            $competition = $competitions->isNotEmpty() ? $competitions->random() : null;
            $type = $types[array_rand($types)];
            $level = $levels[array_rand($levels)];
            
            $achievementDate = Carbon::createFromTimestamp(
                rand($startDate->timestamp, $endDate->timestamp)
            );
            
            $titles = [
                'Juara' => ['Juara 1', 'Juara 2', 'Juara 3', 'Finalis', 'Juara Favorit'],
                'Penghargaan' => ['Best Innovation', 'Best Paper', 'Best Presentation', 'Outstanding Achievement'],
                'Sertifikasi' => ['AWS Certification', 'Microsoft Certification', 'Google Certification', 'Oracle Certification'],
                'Publikasi' => ['Journal Publication', 'Conference Paper', 'Research Article', 'Book Chapter'],
                'Kompetisi' => ['Hackathon', 'Programming Contest', 'Design Competition', 'Business Case Competition']
            ];
            
            $titlePrefix = $titles[$type][array_rand($titles[$type])];
            $competitionName = $competition ? $competition->name : "Competition " . ($i + 1);
            
            AchievementModel::create([
                'user_id' => $student->id,
                'period_id' => $period->id,
                'competition_id' => $competition ? $competition->id : null,
                'title' => "$titlePrefix - $competitionName",
                'description' => "Achievement in $competitionName at $level level",
                'competition_name' => $competitionName,
                'type' => $type,
                'level' => $level,
                'date' => $achievementDate->format('Y-m-d'),
                'status' => 'verified',
                'verified_by' => $admin->id,
                'verified_at' => $achievementDate->addDays(rand(1, 10)),
                'created_at' => $achievementDate,
                'updated_at' => $achievementDate,
            ]);
        }
    }
} 