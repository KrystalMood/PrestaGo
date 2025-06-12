<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;
use App\Models\UserModel;

class ActivityLogSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $admin = UserModel::where('level_id', 1)->first();
        
        if (!$admin) {
            $this->command->warn('No admin user found. Using null for user_id.');
            $adminId = null;
        } else {
            $adminId = $admin->id;
        }
        
        $formats = ['pdf', 'excel'];
        $reportTypes = ['achievements', 'programs', 'periods', 'trends'];
        
        for ($i = 0; $i < 15; $i++) {
            $format = $formats[array_rand($formats)];
            $reportType = $reportTypes[array_rand($reportTypes)];
            $date = Carbon::now()->subDays(rand(1, 28));
            
            DB::table('activity_logs')->insert([
                'activity_type' => 'report_export',
                'user_id' => $adminId,
                'details' => json_encode([
                    'format' => $format,
                    'report_type' => $reportType,
                ]),
                'description' => "Exported $reportType report in $format format",
                'created_at' => $date,
                'updated_at' => $date,
            ]);
        }
        
        for ($i = 0; $i < 10; $i++) {
            $format = $formats[array_rand($formats)];
            $reportType = $reportTypes[array_rand($reportTypes)];
            $date = Carbon::now()->subMonth()->subDays(rand(1, 28));
            
            DB::table('activity_logs')->insert([
                'activity_type' => 'report_export',
                'user_id' => $adminId,
                'details' => json_encode([
                    'format' => $format,
                    'report_type' => $reportType,
                ]),
                'description' => "Exported $reportType report in $format format",
                'created_at' => $date,
                'updated_at' => $date,
            ]);
        }
        
        $this->command->info('Activity logs seeded successfully.');
    }
}
