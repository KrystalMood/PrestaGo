<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

class InterestAreaSeeder extends Seeder
{
    public function run(): void
    {
        Schema::disableForeignKeyConstraints();
        DB::table('interest_areas')->truncate();
        Schema::enableForeignKeyConstraints();

        $interestAreas = [
            [
                'name' => 'Pengembangan Web',
                'description' => 'Pengembangan aplikasi berbasis web dengan teknologi front-end dan back-end',
                'weight_value' => 1.0,
                'criterion_type' => 'benefit',
                'ahp_priority' => 0.15,
                'display_order' => 1,
                'is_active' => true,
            ],
            [
                'name' => 'Pengembangan Mobile',
                'description' => 'Pengembangan aplikasi untuk perangkat mobile seperti Android dan iOS',
                'weight_value' => 1.0,
                'criterion_type' => 'benefit',
                'ahp_priority' => 0.14,
                'display_order' => 2,
                'is_active' => true,
            ],
            [
                'name' => 'Kecerdasan Buatan',
                'description' => 'Pengembangan sistem berbasis AI, machine learning, dan deep learning',
                'weight_value' => 1.2,
                'criterion_type' => 'benefit',
                'ahp_priority' => 0.13,
                'display_order' => 3,
                'is_active' => true,
            ],
            [
                'name' => 'Data Science',
                'description' => 'Analisis data, big data, dan visualisasi data',
                'weight_value' => 1.1,
                'criterion_type' => 'benefit',
                'ahp_priority' => 0.12,
                'display_order' => 4,
                'is_active' => true,
            ],
            [
                'name' => 'Keamanan Informasi',
                'description' => 'Keamanan sistem, pengujian penetrasi, dan forensik digital',
                'weight_value' => 1.2,
                'criterion_type' => 'benefit',
                'ahp_priority' => 0.11,
                'display_order' => 5,
                'is_active' => true,
            ],
            [
                'name' => 'Jaringan Komputer',
                'description' => 'Perancangan jaringan, administrasi server, dan cloud computing',
                'weight_value' => 0.9,
                'criterion_type' => 'benefit',
                'ahp_priority' => 0.10,
                'display_order' => 6,
                'is_active' => true,
            ],
            [
                'name' => 'Game Development',
                'description' => 'Pengembangan game untuk berbagai platform',
                'weight_value' => 0.9,
                'criterion_type' => 'benefit',
                'ahp_priority' => 0.09,
                'display_order' => 7,
                'is_active' => true,
            ],
            [
                'name' => 'Desain UI/UX',
                'description' => 'Perancangan antarmuka dan pengalaman pengguna',
                'weight_value' => 0.9,
                'criterion_type' => 'benefit',
                'ahp_priority' => 0.08,
                'display_order' => 8,
                'is_active' => true,
            ],
            [
                'name' => 'Pengembangan IoT',
                'description' => 'Internet of Things dan embedded systems',
                'weight_value' => 0.9,
                'criterion_type' => 'benefit',
                'ahp_priority' => 0.07,
                'display_order' => 9,
                'is_active' => true,
            ],
            [
                'name' => 'DevOps',
                'description' => 'Integrasi pengembangan dan operasi, CI/CD, dan otomatisasi',
                'weight_value' => 1.0,
                'criterion_type' => 'benefit',
                'ahp_priority' => 0.06,
                'display_order' => 10,
                'is_active' => true,
            ],
            [
                'name' => 'Blockchain',
                'description' => 'Teknologi blockchain, cryptocurrency, dan smart contract',
                'weight_value' => 0.8,
                'criterion_type' => 'benefit',
                'ahp_priority' => 0.05,
                'display_order' => 11,
                'is_active' => true,
            ],
            [
                'name' => 'Augmented/Virtual Reality',
                'description' => 'Pengembangan aplikasi AR/VR',
                'weight_value' => 0.8,
                'criterion_type' => 'benefit',
                'ahp_priority' => 0.04,
                'display_order' => 12,
                'is_active' => true,
            ],
            [
                'name' => 'Project Management',
                'description' => 'Manajemen proyek IT dan metodologi agile',
                'weight_value' => 0.8,
                'criterion_type' => 'benefit',
                'ahp_priority' => 0.03,
                'display_order' => 13,
                'is_active' => true,
            ],
            [
                'name' => 'Database Administration',
                'description' => 'Administrasi dan optimasi database',
                'weight_value' => 0.8,
                'criterion_type' => 'benefit',
                'ahp_priority' => 0.02,
                'display_order' => 14,
                'is_active' => true,
            ],
            [
                'name' => 'Entrepreneurship',
                'description' => 'Kewirausahaan teknologi dan startup',
                'weight_value' => 0.7,
                'criterion_type' => 'benefit',
                'ahp_priority' => 0.01,
                'display_order' => 15,
                'is_active' => true,
            ],
        ];

        DB::table('interest_areas')->insert($interestAreas);
    }
}