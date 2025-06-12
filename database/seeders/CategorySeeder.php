<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\CategoryModel;
use Illuminate\Support\Str;

class CategorySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $categories = [
            [
                'name' => 'Pemrograman',
                'description' => 'Kompetisi yang berfokus pada kemampuan pemrograman dan algoritma',
                'slug' => 'pemrograman',
            ],
            [
                'name' => 'Desain UI/UX',
                'description' => 'Kompetisi desain antarmuka dan pengalaman pengguna',
                'slug' => 'desain-ui-ux',
            ],
            [
                'name' => 'Pengembangan Aplikasi',
                'description' => 'Kompetisi pengembangan aplikasi berbasis web, mobile, atau desktop',
                'slug' => 'pengembangan-aplikasi',
            ],
            [
                'name' => 'Kecerdasan Buatan',
                'description' => 'Kompetisi yang berfokus pada implementasi artificial intelligence dan machine learning',
                'slug' => 'kecerdasan-buatan',
            ],
            [
                'name' => 'IoT',
                'description' => 'Kompetisi pengembangan solusi Internet of Things',
                'slug' => 'iot',
            ],
            [
                'name' => 'Keamanan Siber',
                'description' => 'Kompetisi yang berfokus pada keamanan informasi dan cyber security',
                'slug' => 'keamanan-siber',
            ],
            [
                'name' => 'Bisnis TI',
                'description' => 'Kompetisi perencanaan bisnis berbasis teknologi informasi',
                'slug' => 'bisnis-ti',
            ],
            [
                'name' => 'Karya Tulis',
                'description' => 'Kompetisi penulisan karya ilmiah di bidang teknologi',
                'slug' => 'karya-tulis',
            ],
        ];

        foreach ($categories as $category) {
            CategoryModel::create($category);
        }
    }
} 