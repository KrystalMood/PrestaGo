<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Faker\Factory as Faker;

class MahasiswaSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $faker = Faker::create('id_ID');
        
        $studyProgramIds = DB::table('study_programs')->pluck('id')->toArray();
        
        $studentLevelId = DB::table('level')->where('level_kode', 'MHS')->value('id');
        
        for ($i = 1; $i <= 10; $i++) {
            $nim = '1' . $faker->numerify('#######');
            
            DB::table('users')->insert([
                'name' => $faker->name,
                'email' => $faker->unique()->safeEmail,
                'password' => bcrypt('password123'),
                'role' => 'mahasiswa',
                'level_id' => $studentLevelId,
                'nim' => $nim,
                'program_studi_id' => $faker->randomElement($studyProgramIds),
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }
    }
} 