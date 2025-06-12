<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\CompetitionModel;
use App\Models\SubCompetitionModel;
use App\Models\CategoryModel;
use Carbon\Carbon;
use Illuminate\Support\Str;

class SubCompetitionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $competitions = CompetitionModel::all();
        
        $programmingCategory = CategoryModel::where('name', 'like', '%program%')->first();
        $designCategory = CategoryModel::where('name', 'like', '%design%')->orWhere('name', 'like', '%desain%')->first();
        $businessCategory = CategoryModel::where('name', 'like', '%business%')->orWhere('name', 'like', '%bisnis%')->first();
        $researchCategory = CategoryModel::where('name', 'like', '%research%')->orWhere('name', 'like', '%penelitian%')->orWhere('name', 'like', '%riset%')->first();
        $cybersecurityCategory = CategoryModel::where('name', 'like', '%security%')->orWhere('name', 'like', '%keamanan%')->first();

        if (!$programmingCategory) {
            $programmingCategory = CategoryModel::create([
                'name' => 'Programming',
                'slug' => Str::slug('Programming'),
            ]);
        }
        if (!$designCategory) {
            $designCategory = CategoryModel::create([
                'name' => 'Design',
                'slug' => Str::slug('Design'),
            ]);
        }
        if (!$businessCategory) {
            $businessCategory = CategoryModel::create([
                'name' => 'Business',
                'slug' => Str::slug('Business'),
            ]);
        }
        if (!$researchCategory) {
            $researchCategory = CategoryModel::create([
                'name' => 'Research',
                'slug' => Str::slug('Research'),
            ]);
        }
        if (!$cybersecurityCategory) {
            $cybersecurityCategory = CategoryModel::create([
                'name' => 'Cybersecurity',
                'slug' => Str::slug('Cybersecurity'),
            ]);
        }

        $subCompetitionData = [
            [
                'name' => 'Web Development',
                'description' => 'Kompetisi pengembangan website dengan fokus pada user experience dan modern design.',
                'category_id' => $programmingCategory->id,
                'start_date' => Carbon::now()->addDays(30),
                'end_date' => Carbon::now()->addDays(60),
                'registration_start' => Carbon::now(),
                'registration_end' => Carbon::now()->addDays(20),
                'competition_date' => Carbon::now()->addDays(45),
                'registration_link' => 'https://example.com/register/web-dev',
                'requirements' => "1. Mahasiswa aktif\n2. Minimal 2 orang dalam tim\n3. Menguasai HTML, CSS, dan JavaScript\n4. Memiliki portfolio proyek web",
                'status' => 'upcoming'
            ],
            [
                'name' => 'Mobile App Development',
                'description' => 'Kompetisi pengembangan aplikasi mobile dengan fokus pada inovasi dan user interface.',
                'category_id' => $programmingCategory->id,
                'start_date' => Carbon::now()->addDays(45),
                'end_date' => Carbon::now()->addDays(75),
                'registration_start' => Carbon::now()->addDays(15),
                'registration_end' => Carbon::now()->addDays(35),
                'competition_date' => Carbon::now()->addDays(60),
                'registration_link' => 'https://example.com/register/mobile-dev',
                'requirements' => "1. Mahasiswa aktif\n2. Minimal 2 orang dalam tim\n3. Menguasai Flutter atau React Native\n4. Memiliki portfolio aplikasi mobile",
                'status' => 'upcoming'
            ],
            [
                'name' => 'UI/UX Design',
                'description' => 'Kompetisi desain antarmuka pengguna dengan fokus pada user experience dan visual design.',
                'category_id' => $designCategory->id,
                'start_date' => Carbon::now()->addDays(20),
                'end_date' => Carbon::now()->addDays(50),
                'registration_start' => Carbon::now()->addDays(5),
                'registration_end' => Carbon::now()->addDays(15),
                'competition_date' => Carbon::now()->addDays(35),
                'registration_link' => 'https://example.com/register/uiux',
                'requirements' => "1. Mahasiswa aktif\n2. Individu atau tim maksimal 3 orang\n3. Menguasai Figma atau Adobe XD\n4. Memiliki portfolio desain UI/UX",
                'status' => 'upcoming'
            ],
            [
                'name' => 'Data Science',
                'description' => 'Kompetisi analisis data dan machine learning dengan fokus pada solusi bisnis.',
                'category_id' => $researchCategory->id,
                'start_date' => Carbon::now()->addDays(40),
                'end_date' => Carbon::now()->addDays(70),
                'registration_start' => Carbon::now()->addDays(10),
                'registration_end' => Carbon::now()->addDays(30),
                'competition_date' => Carbon::now()->addDays(55),
                'registration_link' => 'https://example.com/register/data-science',
                'requirements' => "1. Mahasiswa aktif\n2. Minimal 2 orang dalam tim\n3. Menguasai Python dan library data science\n4. Memiliki pengalaman dalam analisis data",
                'status' => 'upcoming'
            ],
            [
                'name' => 'Cybersecurity',
                'description' => 'Kompetisi keamanan siber dengan fokus pada penetration testing dan security analysis.',
                'category_id' => $cybersecurityCategory->id,
                'start_date' => Carbon::now()->addDays(25),
                'end_date' => Carbon::now()->addDays(55),
                'registration_start' => Carbon::now()->addDays(5),
                'registration_end' => Carbon::now()->addDays(20),
                'competition_date' => Carbon::now()->addDays(40),
                'registration_link' => 'https://example.com/register/cybersecurity',
                'requirements' => "1. Mahasiswa aktif\n2. Individu atau tim maksimal 3 orang\n3. Memahami konsep keamanan jaringan\n4. Memiliki pengalaman dalam CTF atau security testing",
                'status' => 'upcoming'
            ]
        ];

        foreach ($competitions as $competition) {
            $numberOfSubCompetitions = rand(1, 3);
            $selectedSubCompetitions = array_rand($subCompetitionData, $numberOfSubCompetitions);
            
            if (!is_array($selectedSubCompetitions)) {
                $selectedSubCompetitions = [$selectedSubCompetitions];
            }

            foreach ($selectedSubCompetitions as $index) {
                $data = $subCompetitionData[$index];
                $data['competition_id'] = $competition->id;
                
                SubCompetitionModel::create($data);
            }
        }
    }
} 