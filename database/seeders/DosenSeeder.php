<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Faker\Factory as Faker;

class DosenSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $faker = Faker::create('id_ID');
        
        $studyProgramIds = DB::table('study_programs')->pluck('id')->toArray();
        
        $lecturerLevelId = DB::table('level')->where('level_kode', 'DSN')->value('id');
        
        for ($i = 1; $i <= 5; $i++) {
            $nip = '2' . $faker->numerify('#########');
            
            DB::table('users')->insert([
                'name' => $faker->name,
                'email' => $faker->unique()->safeEmail,
                'password' => bcrypt('password123'),
                'role' => 'dosen',
                'level_id' => $lecturerLevelId,
                'nip' => $nip,
                'program_studi_id' => $faker->randomElement($studyProgramIds),
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }
    }
} 