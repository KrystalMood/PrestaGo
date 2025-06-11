<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class LevelSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('level')->insert([
            [
                'level_kode' => 'ADM',
                'level_nama' => 'Admin',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'level_kode' => 'DSN',
                'level_nama' => 'Dosen',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'level_kode' => 'MHS',
                'level_nama' => 'Mahasiswa',
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ]);
    }
}
