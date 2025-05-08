<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\PeriodModel;

class PeriodSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $periods = [
            [
                'name' => 'Tahun Akademik 2023/2024 - Ganjil',
                'start_date' => '2023-09-01',
                'end_date' => '2024-01-31',
                'is_active' => false,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'Tahun Akademik 2023/2024 - Genap',
                'start_date' => '2024-02-01',
                'end_date' => '2024-06-30',
                'is_active' => false,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'Tahun Akademik 2024/2025 - Ganjil',
                'start_date' => '2024-09-01',
                'end_date' => '2025-01-31',
                'is_active' => true,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'Tahun Akademik 2024/2025 - Genap',
                'start_date' => '2025-02-01',
                'end_date' => '2025-06-30',
                'is_active' => false,
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ];

        foreach ($periods as $period) {
            PeriodModel::create($period);
        }
    }
} 