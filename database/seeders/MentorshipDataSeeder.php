<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\LecturerMentorshipModel;
use App\Models\CompetitionModel;
use App\Models\UserModel;
use App\Models\LevelModel;
use Illuminate\Support\Facades\DB;

class MentorshipDataSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Mendapatkan ID dari kompetisi
        $competitionIds = DB::table('competitions')->pluck('id')->toArray();
        
        if (empty($competitionIds)) {
            $this->command->info('Tidak ada kompetisi ditemukan.');
            return;
        }
        
        // Mendapatkan ID dari dosen (level_id = 2 untuk DSN)
        $dosenIds = DB::table('users')
            ->join('level', 'users.level_id', '=', 'level.id')
            ->where('level.level_kode', 'DSN')
            ->pluck('users.id')
            ->toArray();
        
        if (empty($dosenIds)) {
            // Jika tidak ada dosen, gunakan ID user yang ada
            $dosenIds = DB::table('users')->pluck('id')->take(3)->toArray();
        }
        
        if (empty($dosenIds)) {
            $this->command->info('Tidak ada dosen atau user ditemukan.');
            return;
        }
        
        // Hapus data mentorship yang sudah ada
        DB::table('lecturer_mentorships')->delete();
        
        // Insert data mentorship dengan query builder
        foreach ($competitionIds as $competitionId) {
            // Pilih 1-3 dosen secara acak
            $numDosen = mt_rand(1, min(3, count($dosenIds)));
            $selectedDosenIds = collect($dosenIds)->random($numDosen)->toArray();
            
            foreach ($selectedDosenIds as $dosenId) {
                DB::table('lecturer_mentorships')->insert([
                    'competition_id' => $competitionId,
                    'dosen_id' => $dosenId,
                    'average_rating' => 0,
                    'rating_count' => 0,
                    'created_at' => now(),
                    'updated_at' => now()
                ]);
                
                $this->command->info("Dosen ID {$dosenId} ditambahkan sebagai mentor untuk kompetisi ID {$competitionId}");
            }
        }
        
        $this->command->info('Data mentorship berhasil ditambahkan.');
    }
} 