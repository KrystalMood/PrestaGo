<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

class UserSkillSeeder extends Seeder
{

    public function run(): void
    {
        Schema::disableForeignKeyConstraints();
        DB::table('user_skills')->truncate();
        Schema::enableForeignKeyConstraints();

        $users = DB::table('users')
            ->whereIn('role', ['dosen', 'mahasiswa'])
            ->get();

        $skillIds = DB::table('skills')->pluck('id')->toArray();

        if (empty($skillIds) || $users->isEmpty()) {
            return;
        }

        foreach ($users as $user) {
            $selectedSkillIds = collect($skillIds)->random(rand(10, 15));

            foreach ($selectedSkillIds as $skillId) {
                DB::table('user_skills')->insert([
                    'user_id'          => $user->id,
                    'skill_id'         => $skillId,
                    'proficiency_level'=> rand(1, 5),
                    'created_at'       => now(),
                    'updated_at'       => now(),
                ]);
            }
        }
    }
} 