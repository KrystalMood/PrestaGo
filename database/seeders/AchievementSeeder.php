<?php

namespace Database\Seeders;

use App\Models\AchievementModel;
use App\Models\UserModel;
use Carbon\Carbon;
use Illuminate\Database\Seeder;

class AchievementSeeder extends Seeder
{
    public function run(): void
    {
        $userIds = UserModel::where('level_id', 3)->pluck('id')->toArray();
        
        if (empty($userIds)) {
            $userIds = [1];
        }
        
        $adminId = UserModel::where('level_id', 1)->first()?->id ?? 1;
        
        $types = ['Juara', 'Penghargaan', 'Sertifikasi', 'Publikasi', 'Magang', 'Kompetisi', 'Lainnya'];
        
        $levels = ['Internasional', 'Nasional', 'Regional', 'Provinsi', 'Kota/Kabupaten', 'Kampus'];
        
        $achievements = [
            [
                'title' => 'Juara 1 Hackathon Nasional',
                'description' => 'Kompetisi pengembangan aplikasi inovatif tingkat nasional',
                'competition_name' => 'Indonesia Hackathon 2025',
                'type' => 'Juara',
                'level' => 'Nasional',
                'status' => 'verified',
                'created_at' => Carbon::now()->subMonths(1),
                'date' => Carbon::now()->subMonths(1)->format('Y-m-d'),
            ],
            [
                'title' => 'Best Paper Award',
                'description' => 'Penghargaan untuk paper terbaik di konferensi internasional',
                'competition_name' => 'International Conference on Computer Science',
                'type' => 'Penghargaan',
                'level' => 'Internasional',
                'status' => 'verified',
                'created_at' => Carbon::now()->subMonths(2),
                'date' => Carbon::now()->subMonths(2)->format('Y-m-d'),
            ],
            [
                'title' => 'Sertifikasi AWS Cloud Practitioner',
                'description' => 'Sertifikasi keahlian cloud computing dari Amazon Web Services',
                'competition_name' => 'AWS Certification Program',
                'type' => 'Sertifikasi',
                'level' => 'Internasional',
                'status' => 'verified',
                'created_at' => Carbon::now()->subMonths(3),
                'date' => Carbon::now()->subMonths(3)->format('Y-m-d'),
            ],
            [
                'title' => 'Publikasi Jurnal Nasional',
                'description' => 'Publikasi paper penelitian di jurnal nasional terakreditasi',
                'competition_name' => 'Jurnal Ilmu Komputer Indonesia',
                'type' => 'Publikasi',
                'level' => 'Nasional',
                'status' => 'verified',
                'created_at' => Carbon::now()->subMonths(4),
                'date' => Carbon::now()->subMonths(4)->format('Y-m-d'),
            ],
            [
                'title' => 'Magang di Google Indonesia',
                'description' => 'Program magang pengembangan software di Google Indonesia',
                'competition_name' => 'Google Internship Program',
                'type' => 'Magang',
                'level' => 'Nasional',
                'status' => 'verified',
                'created_at' => Carbon::now()->subMonths(5),
                'date' => Carbon::now()->subMonths(5)->format('Y-m-d'),
            ],
            [
                'title' => 'Finalis Kompetisi UI/UX Design',
                'description' => 'Finalis dalam kompetisi desain antarmuka pengguna',
                'competition_name' => 'Indonesia UI/UX Challenge',
                'type' => 'Kompetisi',
                'level' => 'Nasional',
                'status' => 'verified',
                'created_at' => Carbon::now()->subMonths(1)->subDays(15),
                'date' => Carbon::now()->subMonths(1)->subDays(15)->format('Y-m-d'),
            ],
            [
                'title' => 'Juara 2 Competitive Programming',
                'description' => 'Kompetisi pemrograman algoritma tingkat regional',
                'competition_name' => 'Regional Programming Contest',
                'type' => 'Juara',
                'level' => 'Regional',
                'status' => 'verified',
                'created_at' => Carbon::now()->subMonths(2)->subDays(10),
                'date' => Carbon::now()->subMonths(2)->subDays(10)->format('Y-m-d'),
            ],
            [
                'title' => 'Penghargaan Inovasi Teknologi',
                'description' => 'Penghargaan untuk inovasi teknologi terbaik tingkat provinsi',
                'competition_name' => 'Provincial Tech Innovation Awards',
                'type' => 'Penghargaan',
                'level' => 'Provinsi',
                'status' => 'verified',
                'created_at' => Carbon::now()->subMonths(3)->subDays(5),
                'date' => Carbon::now()->subMonths(3)->subDays(5)->format('Y-m-d'),
            ],
            [
                'title' => 'Sertifikasi Microsoft Azure',
                'description' => 'Sertifikasi keahlian cloud computing dari Microsoft',
                'competition_name' => 'Microsoft Certification Program',
                'type' => 'Sertifikasi',
                'level' => 'Internasional',
                'status' => 'verified',
                'created_at' => Carbon::now()->subMonths(4)->subDays(20),
                'date' => Carbon::now()->subMonths(4)->subDays(20)->format('Y-m-d'),
            ],
            [
                'title' => 'Juara 3 Lomba Karya Tulis Ilmiah',
                'description' => 'Kompetisi penulisan karya ilmiah tingkat kampus',
                'competition_name' => 'Campus Scientific Writing Competition',
                'type' => 'Juara',
                'level' => 'Kampus',
                'status' => 'verified',
                'created_at' => Carbon::now()->subMonths(5)->subDays(25),
                'date' => Carbon::now()->subMonths(5)->subDays(25)->format('Y-m-d'),
            ],
            [
                'title' => 'Hackathon Kota Cerdas',
                'description' => 'Kompetisi pengembangan solusi kota cerdas',
                'competition_name' => 'Smart City Hackathon',
                'type' => 'Kompetisi',
                'level' => 'Kota/Kabupaten',
                'status' => 'pending',
                'created_at' => Carbon::now()->subDays(5),
                'date' => Carbon::now()->subDays(5)->format('Y-m-d'),
            ],
            [
                'title' => 'Lomba Robot Pintar',
                'description' => 'Kompetisi pembuatan robot dengan AI',
                'competition_name' => 'Smart Robot Competition',
                'type' => 'Kompetisi',
                'level' => 'Nasional',
                'status' => 'pending',
                'created_at' => Carbon::now()->subDays(10),
                'date' => Carbon::now()->subDays(10)->format('Y-m-d'),
            ],
        ];
        
        foreach ($achievements as $index => $data) {
            $userId = $userIds[$index % count($userIds)];
            
            AchievementModel::create([
                'user_id' => $userId,
                'title' => $data['title'],
                'description' => $data['description'],
                'competition_name' => $data['competition_name'],
                'type' => $data['type'],
                'level' => $data['level'],
                'date' => $data['date'],
                'status' => $data['status'],
                'verified_by' => $data['status'] === 'verified' ? $adminId : null,
                'verified_at' => $data['status'] === 'verified' ? $data['created_at'] : null,
                'created_at' => $data['created_at'],
                'updated_at' => $data['created_at'],
            ]);
        }
    }
}
