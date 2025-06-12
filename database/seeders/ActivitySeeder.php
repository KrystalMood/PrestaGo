<?php

namespace Database\Seeders;

use App\Models\ActivityModel;
use Carbon\Carbon;
use Illuminate\Database\Seeder;

class ActivitySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $activities = [
            [
                'icon_type' => 'green',
                'message' => 'Prestasi Ahmad <span class="font-medium">"UI/UX Design Competition"</span> telah diverifikasi',
                'action' => 'verify',
                'subject' => 'achievement',
                'causer' => 'admin',
                'created_at' => Carbon::now()->subMinutes(5)
            ],
            [
                'icon_type' => 'blue',
                'message' => 'Kompetisi baru <span class="font-medium">"National Robotics Championship"</span> ditambahkan',
                'action' => 'create',
                'subject' => 'competition',
                'causer' => 'admin',
                'created_at' => Carbon::now()->subHours(1)
            ],
            [
                'icon_type' => 'indigo',
                'message' => 'Pengguna baru <span class="font-medium">Siska Meliana</span> terdaftar',
                'action' => 'register',
                'subject' => 'user',
                'causer' => 'system',
                'created_at' => Carbon::now()->subHours(3)
            ],
            [
                'icon_type' => 'amber',
                'message' => 'Batas waktu kompetisi <span class="font-medium">"AI Challenge 2025"</span> dalam 2 hari',
                'action' => 'notify',
                'subject' => 'deadline',
                'causer' => 'system',
                'created_at' => Carbon::now()->subHours(6)
            ],
            [
                'icon_type' => 'red',
                'message' => 'Pengajuan prestasi <span class="font-medium">"Hackathon Nasional"</span> ditolak',
                'action' => 'reject',
                'subject' => 'achievement',
                'causer' => 'admin',
                'created_at' => Carbon::now()->subHours(12)
            ],
        ];

        foreach ($activities as $activity) {
            ActivityModel::create($activity);
        }
    }
}
