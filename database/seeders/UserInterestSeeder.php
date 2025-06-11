<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

class UserInterestSeeder extends Seeder
{

    public function run(): void
    {
        Schema::disableForeignKeyConstraints();
        DB::table('user_interests')->truncate();
        Schema::enableForeignKeyConstraints();

        $users = DB::table('users')
            ->whereIn('role', ['dosen', 'mahasiswa'])
            ->get();

        $interestIds = DB::table('interest_areas')->pluck('id')->toArray();

        if (empty($interestIds) || $users->isEmpty()) {
            return;
        }

        foreach ($users as $user) {
            $selectedInterestIds = collect($interestIds)->random(rand(6, 8));

            foreach ($selectedInterestIds as $interestId) {
                $level = rand(1, 5);
                DB::table('user_interests')->insert([
                    'user_id'          => $user->id,
                    'interest_area_id' => $interestId,
                    'interest_level'   => $level,
                    'normalized_score' => $level / 5,
                    'self_assessment'  => 'Seeder-generated',
                    'created_at'       => now(),
                    'updated_at'       => now(),
                ]);
            }
        }
    }
} 