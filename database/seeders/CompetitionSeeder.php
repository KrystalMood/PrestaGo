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
        $periodId = 1;

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
                'level' => 'National',
                'start_date' => '2024-10-20',
                'end_date' => '2024-10-25',
                'registration_start' => '2024-07-15',
                'registration_end' => '2024-08-15',
                'competition_date' => '2024-10-20',
                'registration_link' => 'https://gemastik.kemdikbud.go.id/',
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
                'start_date' => '2024-10-15',
                'end_date' => '2024-10-16',
                'registration_start' => '2024-09-10',
                'registration_end' => '2024-10-01',
                'competition_date' => '2024-10-15',
                'registration_link' => 'https://hmti.polinema.ac.id/cp-polinema',
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
                'start_date' => '2024-09-15',
                'end_date' => '2024-09-17',
                'registration_start' => '2024-08-01',
                'registration_end' => '2024-08-30',
                'competition_date' => '2024-09-15',
                'registration_link' => 'https://jti.polinema.ac.id/wdc2024',
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
                'start_date' => '2024-07-25',
                'end_date' => '2024-07-27',
                'registration_start' => '2024-06-10',
                'registration_end' => '2024-07-10',
                'competition_date' => '2024-07-25',
                'registration_link' => 'https://elektro.polinema.ac.id/hackathon-iot',
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
                'start_date' => '2024-10-05',
                'end_date' => '2024-10-07',
                'registration_start' => '2024-08-15',
                'registration_end' => '2024-09-15',
                'competition_date' => '2024-10-05',
                'registration_link' => 'https://administrasi-niaga.polinema.ac.id/bisnis-plan',
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
                'level' => 'National',
                'start_date' => '2024-09-10',
                'end_date' => '2024-09-12',
                'registration_start' => '2024-07-01',
                'registration_end' => '2024-08-01',
                'competition_date' => '2024-09-10',
                'registration_link' => 'https://lktin.kemdikbud.go.id/',
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
                'level' => 'National',
                'start_date' => '2024-11-15',
                'end_date' => '2024-11-17',
                'registration_start' => '2024-09-01',
                'registration_end' => '2024-09-30',
                'competition_date' => '2024-11-15',
                'registration_link' => 'https://microsoft.com/id-id/uiux-challenge',
                'status' => 'upcoming',
                'verified' => true,
                'added_by' => 1,
                'period_id' => $periodId,
                'category_id' => $uiuxId,
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ];

        $levelMap = [
            'International' => 'international',
            'National'      => 'national',
            'Regional'      => 'regional',
            'Provincial'    => 'provincial',
            'University'    => 'university',
            'Internal'      => 'internal',
        ];

        foreach ($competitions as &$competition) {
            $competition['level'] = $levelMap[$competition['level']] ?? $competition['level'];
        }
        unset($competition);

        foreach ($competitions as $competition) {
            CompetitionModel::create($competition);
        }
    }
} 