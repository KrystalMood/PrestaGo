<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\LecturerMentorshipModel;
use App\Models\CompetitionModel;
use App\Models\UserModel;
use App\Models\LevelModel;

class LecturerMentorshipSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Ambil semua kompetisi
        $competitions = CompetitionModel::all();
        
        // Ambil semua dosen
        $lecturers = UserModel::whereHas('level', function($query) {
            $query->where('level_kode', 'DSN');
        })->get();
        
        if ($competitions->isEmpty()) {
            $this->command->info('Tidak ada kompetisi yang ditemukan. Seeder tidak dijalankan.');
            return;
        }
        
        if ($lecturers->isEmpty()) {
            $this->command->info('Tidak ada dosen yang ditemukan. Seeder tidak dijalankan.');
            return;
        }
        
        // Untuk setiap kompetisi, assign 1-3 dosen sebagai mentor
        foreach ($competitions as $competition) {
            // Pilih jumlah dosen secara acak (1-3)
            $numLecturers = rand(1, min(3, $lecturers->count()));
            
            // Pilih dosen secara acak
            $selectedLecturers = $lecturers->random($numLecturers);
            
            foreach ($selectedLecturers as $lecturer) {
                // Buat mentorship
                LecturerMentorshipModel::create([
                    'competition_id' => $competition->id,
                    'dosen_id' => $lecturer->id,
                    'average_rating' => 0,
                    'rating_count' => 0,
                ]);
                
                $this->command->info("Dosen {$lecturer->name} ditambahkan sebagai mentor untuk kompetisi {$competition->name}");
            }
        }
    }
} 