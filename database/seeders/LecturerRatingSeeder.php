<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\LecturerRating;
use App\Models\LecturerMentorshipModel;
use App\Models\UserModel;
use App\Models\CompetitionModel;
use Faker\Factory as Faker;

class LecturerRatingSeeder extends Seeder
{
    public function run(): void
    {
        $faker = Faker::create('id_ID');
        
        // Get all lecturer mentorships
        $mentorships = LecturerMentorshipModel::with(['competition', 'lecturer'])->get();
        
        if ($mentorships->isEmpty()) {
            $this->command->info('No lecturer mentorships found. Skipping lecturer rating seeding.');
            return;
        }
        
        // Get student users for rating
        $studentUsers = UserModel::whereHas('level', function($query) {
            $query->where('level_kode', 'MHS');
        })->pluck('id')->toArray();
        
        if (empty($studentUsers)) {
            $this->command->info('No student users found. Skipping lecturer rating seeding.');
            return;
        }
        
        $ratingsCount = 0;
        $processedMentorships = 0;
        
        foreach ($mentorships as $mentorship) {
            $competitionId = $mentorship->competition_id;
            $dosenId = $mentorship->dosen_id;
            
            // Get students who participated in this competition
            $participatingStudents = UserModel::whereHas('competitionParticipations', function($query) use ($competitionId) {
                $query->where('competition_id', $competitionId);
            })->pluck('id')->toArray();
            
            // If no participants, use random students
            $raters = !empty($participatingStudents) ? $participatingStudents : $studentUsers;
            
            // Number of ratings for this mentorship (1-5 ratings)
            $numRatings = $faker->numberBetween(1, min(5, count($raters)));
            
            // Select random students to provide ratings
            $selectedRaters = $faker->randomElements($raters, $numRatings);
            
            foreach ($selectedRaters as $raterId) {
                $rating = LecturerRating::create([
                    'dosen_id' => $dosenId,
                    'competition_id' => $competitionId,
                    'rated_by_user_id' => $raterId,
                    'activity_rating' => $faker->numberBetween(3, 5), // Slightly biased toward positive ratings
                    'comments' => $faker->boolean(70) ? $faker->sentences(2, true) : null,
                    'created_at' => $faker->dateTimeBetween('-6 months', 'now'),
                    'updated_at' => now(),
                ]);
                
                $ratingsCount++;
            }
            
            // Update the mentorship average rating
            $mentorship->updateAverageRating();
            $processedMentorships++;
        }
        
        $this->command->info("Created {$ratingsCount} lecturer ratings for {$processedMentorships} mentorships.");
    }
} 