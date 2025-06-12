<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\CompetitionModel;
use App\Models\UserModel;
use App\Models\LecturerMentorshipModel;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

class FixMentorshipSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Cek dan tambahkan kolom average_rating dan rating_count jika belum ada
        if (!Schema::hasColumn('lecturer_mentorships', 'average_rating')) {
            Schema::table('lecturer_mentorships', function ($table) {
                $table->float('average_rating')->nullable();
                $table->integer('rating_count')->default(0);
            });
        }

        // Get all competitions
        $competitions = CompetitionModel::all();
        
        if ($competitions->isEmpty()) {
            $this->command->info('Tidak ada kompetisi yang ditemukan. Seeder tidak dijalankan.');
            return;
        }
        
        // Get all lecturers (dosen)
        $lecturers = UserModel::whereHas('level', function($query) {
            $query->where('level_kode', 'DSN');
        })->get();
        
        if ($lecturers->isEmpty()) {
            // Fallback to use any users with ID 2 and above as lecturers if no lecturers found
            $lecturers = UserModel::where('id', '>=', 2)->take(5)->get();
            $this->command->info('Tidak ada dosen dengan level_kode DSN yang ditemukan. Menggunakan ' . $lecturers->count() . ' pengguna sebagai dosen.');
        }
        
        if ($lecturers->isEmpty()) {
            $this->command->info('Tidak ada dosen atau user yang ditemukan. Seeder tidak dijalankan.');
            return;
        }
        
        // Clean existing mentorships that might be incomplete
        DB::table('lecturer_mentorships')->delete();
        
        $mentorshipCount = 0;
        
        // Create mentorships for all competitions
        foreach ($competitions as $competition) {
            // Determine number of lecturers to assign (1-3)
            $numLecturers = rand(1, min(3, $lecturers->count()));
            
            // Randomly select lecturers
            $selectedLecturers = $lecturers->random($numLecturers);
            
            foreach ($selectedLecturers as $lecturer) {
                try {
                    // Create mentorship record
                    LecturerMentorshipModel::create([
                        'competition_id' => $competition->id,
                        'dosen_id' => $lecturer->id,
                        'average_rating' => null,
                        'rating_count' => 0,
                    ]);
                    
                    $this->command->info("Dosen {$lecturer->name} (ID: {$lecturer->id}) ditambahkan sebagai mentor untuk kompetisi {$competition->name} (ID: {$competition->id})");
                    $mentorshipCount++;
                } catch (\Exception $e) {
                    $this->command->error("Gagal menambahkan dosen {$lecturer->name} (ID: {$lecturer->id}) ke kompetisi {$competition->name} (ID: {$competition->id}): " . $e->getMessage());
                }
            }
        }
        
        $this->command->info("Total {$mentorshipCount} mentorship berhasil ditambahkan.");
    }
} 