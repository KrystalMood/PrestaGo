<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\CompetitionModel;
use App\Models\PeriodModel;
use App\Models\CategoryModel;

class CompetitionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $activePeriod = PeriodModel::where('is_active', true)->first();
        $periodId = $activePeriod ? $activePeriod->id : 1;

        $programmingId = CategoryModel::where('slug', 'pemrograman')->first()->id ?? null;
        $uiuxId = CategoryModel::where('slug', 'desain-ui-ux')->first()->id ?? null;
        $appDevId = CategoryModel::where('slug', 'pengembangan-aplikasi')->first()->id ?? null;
        $iotId = CategoryModel::where('slug', 'iot')->first()->id ?? null;
        $businessId = CategoryModel::where('slug', 'bisnis-ti')->first()->id ?? null;
        $writingId = CategoryModel::where('slug', 'karya-tulis')->first()->id ?? null;

        $competitions = [
            [
                'name' => 'Gemastik XVI',
                'description' => 'Pagelaran Mahasiswa Nasional Bidang Teknologi Informasi dan Komunikasi XVI. Gemastik merupakan ajang kompetisi IT nasional tingkat universitas yang diselenggarakan oleh Kementerian Pendidikan dan Kebudayaan.',
                'organizer' => 'Kemendikbud',
                'level' => 'Nasional',
                'type' => 'Development',
                'start_date' => '2024-10-20',
                'end_date' => '2024-10-25',
                'registration_start' => '2024-07-15',
                'registration_end' => '2024-08-15',
                'competition_date' => '2024-10-20',
                'registration_link' => 'https://gemastik.kemdikbud.go.id/',
                'requirements' => 'Mahasiswa aktif D1 sampai S1 perguruan tinggi negeri atau swasta di Indonesia.',
                'status' => 'upcoming',
                'verified' => true,
                'added_by' => 1,
                'period_id' => $periodId,
                'category_id' => $appDevId,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'Competitive Programming Polinema',
                'description' => 'Kompetisi pemrograman tingkat Politeknik Negeri Malang untuk melatih logika algoritma dan pemecahan masalah.',
                'organizer' => 'HMTI Polinema',
                'level' => 'Internal',
                'type' => 'Coding',
                'start_date' => '2024-10-15',
                'end_date' => '2024-10-16',
                'registration_start' => '2024-09-10',
                'registration_end' => '2024-10-01',
                'competition_date' => '2024-10-15',
                'registration_link' => 'https://hmti.polinema.ac.id/cp-polinema',
                'requirements' => 'Mahasiswa aktif Polinema, maksimal semester 6',
                'status' => 'upcoming',
                'verified' => true,
                'added_by' => 1,
                'period_id' => $periodId,
                'category_id' => $programmingId,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'Web Design Competition 2024',
                'description' => 'Kompetisi desain dan pengembangan website dengan tema "Digital Transformation in Education"',
                'organizer' => 'Jurusan Teknologi Informasi Polinema',
                'level' => 'Regional',
                'type' => 'Design',
                'start_date' => '2024-09-15',
                'end_date' => '2024-09-17',
                'registration_start' => '2024-08-01',
                'registration_end' => '2024-08-30',
                'competition_date' => '2024-09-15',
                'registration_link' => 'https://jti.polinema.ac.id/wdc2024',
                'requirements' => 'Mahasiswa aktif dari perguruan tinggi di Jawa Timur, tim terdiri dari 2-3 orang',
                'status' => 'upcoming',
                'verified' => true,
                'added_by' => 1,
                'period_id' => $periodId,
                'category_id' => $uiuxId,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'Hackathon IoT Polinema',
                'description' => 'Kompetisi pengembangan solusi IoT untuk Smart Campus dengan fokus pada efisiensi energi dan manajemen sumber daya.',
                'organizer' => 'Jurusan Teknik Elektro Polinema',
                'level' => 'Internal',
                'type' => 'Development',
                'start_date' => '2024-07-25',
                'end_date' => '2024-07-27',
                'registration_start' => '2024-06-10',
                'registration_end' => '2024-07-10',
                'competition_date' => '2024-07-25',
                'registration_link' => 'https://elektro.polinema.ac.id/hackathon-iot',
                'requirements' => 'Mahasiswa aktif Polinema dari semua jurusan, tim terdiri dari 3-5 orang',
                'status' => 'completed',
                'verified' => true,
                'added_by' => 1,
                'period_id' => $periodId,
                'category_id' => $iotId,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'Kompetisi Bisnis Plan',
                'description' => 'Kompetisi perencanaan bisnis untuk mahasiswa dengan fokus pada inovasi dan sustainability.',
                'organizer' => 'Jurusan Administrasi Niaga Polinema',
                'level' => 'Internal',
                'type' => 'Business',
                'start_date' => '2024-10-05',
                'end_date' => '2024-10-07',
                'registration_start' => '2024-08-15',
                'registration_end' => '2024-09-15',
                'competition_date' => '2024-10-05',
                'registration_link' => 'https://administrasi-niaga.polinema.ac.id/bisnis-plan',
                'requirements' => 'Mahasiswa aktif Polinema, tim terdiri dari 2-4 orang',
                'status' => 'upcoming',
                'verified' => true,
                'added_by' => 1,
                'period_id' => $periodId,
                'category_id' => $businessId,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'Lomba Karya Tulis Ilmiah Nasional',
                'description' => 'Kompetisi karya tulis ilmiah dengan tema "Inovasi Teknologi untuk Pembangunan Berkelanjutan"',
                'organizer' => 'Kemendikbud',
                'level' => 'Nasional',
                'type' => 'Scientific',
                'start_date' => '2024-09-10',
                'end_date' => '2024-09-12',
                'registration_start' => '2024-07-01',
                'registration_end' => '2024-08-01',
                'competition_date' => '2024-09-10',
                'registration_link' => 'https://lktin.kemdikbud.go.id/',
                'requirements' => 'Mahasiswa aktif di perguruan tinggi Indonesia, tim terdiri dari 1-3 orang',
                'status' => 'upcoming',
                'verified' => true,
                'added_by' => 1,
                'period_id' => $periodId,
                'category_id' => $writingId,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'UI/UX Design Challenge',
                'description' => 'Kompetisi desain antarmuka dan pengalaman pengguna untuk aplikasi mobile dengan tema "Inclusive Design"',
                'organizer' => 'Microsoft Indonesia',
                'level' => 'Nasional',
                'type' => 'Design',
                'start_date' => '2024-11-15',
                'end_date' => '2024-11-17',
                'registration_start' => '2024-09-01',
                'registration_end' => '2024-09-30',
                'competition_date' => '2024-11-15',
                'registration_link' => 'https://microsoft.com/id-id/uiux-challenge',
                'requirements' => 'Mahasiswa aktif perguruan tinggi di Indonesia, individual atau tim (maks 2 orang)',
                'status' => 'upcoming',
                'verified' => true,
                'added_by' => 1,
                'period_id' => $periodId,
                'category_id' => $uiuxId,
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ];

        foreach ($competitions as $competition) {
            CompetitionModel::create($competition);
        }
    }
} 