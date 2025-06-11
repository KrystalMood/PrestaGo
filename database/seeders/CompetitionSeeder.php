<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\CompetitionModel;
use App\Models\PeriodModel;
use App\Models\CategoryModel;
use Carbon\Carbon;

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
        $aiId = CategoryModel::where('slug', 'kecerdasan-buatan')->first()->id ?? null;
        $dataScienceId = CategoryModel::where('slug', 'data-science')->first()->id ?? null;
        $cyberSecurityId = CategoryModel::where('slug', 'keamanan-siber')->first()->id ?? null;

        // Current date for reference
        $now = Carbon::now();
        
        $competitions = [
            // COMPLETED COMPETITIONS (PAST)
            [
                'name' => 'Hackathon IoT Polinema',
                'description' => 'Kompetisi pengembangan solusi IoT untuk Smart Campus dengan fokus pada efisiensi energi dan manajemen sumber daya.',
                'organizer' => 'Jurusan Teknik Elektro Polinema',
                'level' => 'Internal',
                'start_date' => $now->copy()->subMonths(3)->format('Y-m-d'),
                'end_date' => $now->copy()->subMonths(3)->addDays(2)->format('Y-m-d'),
                'registration_start' => $now->copy()->subMonths(4)->format('Y-m-d'),
                'registration_end' => $now->copy()->subMonths(3)->subDays(15)->format('Y-m-d'),
                'competition_date' => $now->copy()->subMonths(3)->format('Y-m-d'),
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
                'start_date' => $now->copy()->subMonths(2)->format('Y-m-d'),
                'end_date' => $now->copy()->subMonths(2)->addDays(2)->format('Y-m-d'),
                'registration_start' => $now->copy()->subMonths(3)->format('Y-m-d'),
                'registration_end' => $now->copy()->subMonths(2)->subDays(15)->format('Y-m-d'),
                'competition_date' => $now->copy()->subMonths(2)->format('Y-m-d'),
                'registration_link' => 'https://administrasi-niaga.polinema.ac.id/bisnis-plan',
                'status' => 'completed',
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
                'start_date' => $now->copy()->subMonths(1)->format('Y-m-d'),
                'end_date' => $now->copy()->subMonths(1)->addDays(2)->format('Y-m-d'),
                'registration_start' => $now->copy()->subMonths(2)->format('Y-m-d'),
                'registration_end' => $now->copy()->subMonths(1)->subDays(15)->format('Y-m-d'),
                'competition_date' => $now->copy()->subMonths(1)->format('Y-m-d'),
                'registration_link' => 'https://lktin.kemdikbud.go.id/',
                'status' => 'completed',
                'verified' => true,
                'added_by' => 1,
                'period_id' => $periodId,
                'category_id' => $writingId,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            
            // ACTIVE COMPETITIONS (HAPPENING NOW)
            [
                'name' => 'Competitive Programming Challenge',
                'description' => 'Kompetisi pemrograman tingkat nasional untuk menguji kemampuan algoritma dan pemecahan masalah.',
                'organizer' => 'Asosiasi Perguruan Tinggi Informatika dan Komputer (APTIKOM)',
                'level' => 'National',
                'start_date' => $now->copy()->subDays(2)->format('Y-m-d'),
                'end_date' => $now->copy()->addDays(5)->format('Y-m-d'),
                'registration_start' => $now->copy()->subMonths(1)->format('Y-m-d'),
                'registration_end' => $now->copy()->subDays(10)->format('Y-m-d'),
                'competition_date' => $now->copy()->subDays(2)->format('Y-m-d'),
                'registration_link' => 'https://aptikom.or.id/cpc2024',
                'status' => 'active', // Will be auto-updated based on dates
                'verified' => true,
                'added_by' => 1,
                'period_id' => $periodId,
                'category_id' => $programmingId,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'Hackathon Inovasi Digital',
                'description' => 'Hackathon 48 jam untuk mengembangkan solusi digital inovatif untuk masalah sosial.',
                'organizer' => 'Kementerian Komunikasi dan Informatika',
                'level' => 'National',
                'start_date' => $now->format('Y-m-d'),
                'end_date' => $now->copy()->addDays(2)->format('Y-m-d'),
                'registration_start' => $now->copy()->subDays(30)->format('Y-m-d'),
                'registration_end' => $now->copy()->subDays(5)->format('Y-m-d'),
                'competition_date' => $now->format('Y-m-d'),
                'registration_link' => 'https://kominfo.go.id/hackathon2024',
                'status' => 'active', // Will be auto-updated based on dates
                'verified' => true,
                'added_by' => 1,
                'period_id' => $periodId,
                'category_id' => $appDevId,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            
            // REGISTRATION OPEN (COMING SOON)
            [
                'name' => 'Web Design Competition 2024',
                'description' => 'Kompetisi desain dan pengembangan website dengan tema "Digital Transformation in Education"',
                'organizer' => 'Jurusan Teknologi Informasi Polinema',
                'level' => 'Regional',
                'start_date' => $now->copy()->addDays(20)->format('Y-m-d'),
                'end_date' => $now->copy()->addDays(22)->format('Y-m-d'),
                'registration_start' => $now->copy()->subDays(10)->format('Y-m-d'),
                'registration_end' => $now->copy()->addDays(10)->format('Y-m-d'),
                'competition_date' => $now->copy()->addDays(20)->format('Y-m-d'),
                'registration_link' => 'https://jti.polinema.ac.id/wdc2024',
                'status' => 'active', // Registration is open
                'verified' => true,
                'added_by' => 1,
                'period_id' => $periodId,
                'category_id' => $uiuxId,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'Data Science Competition',
                'description' => 'Kompetisi analisis data dan machine learning untuk memecahkan masalah bisnis nyata.',
                'organizer' => 'Telkom Indonesia',
                'level' => 'National',
                'start_date' => $now->copy()->addDays(25)->format('Y-m-d'),
                'end_date' => $now->copy()->addDays(27)->format('Y-m-d'),
                'registration_start' => $now->copy()->subDays(5)->format('Y-m-d'),
                'registration_end' => $now->copy()->addDays(15)->format('Y-m-d'),
                'competition_date' => $now->copy()->addDays(25)->format('Y-m-d'),
                'registration_link' => 'https://telkom.id/datascience2024',
                'status' => 'active', // Registration is open
                'verified' => true,
                'added_by' => 1,
                'period_id' => $periodId,
                'category_id' => $dataScienceId,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            
            // UPCOMING (REGISTRATION NOT YET OPEN)
            [
                'name' => 'Gemastik XVI',
                'description' => 'Pagelaran Mahasiswa Nasional Bidang Teknologi Informasi dan Komunikasi XVI. Gemastik merupakan ajang kompetisi IT nasional tingkat universitas yang diselenggarakan oleh Kementerian Pendidikan dan Kebudayaan.',
                'organizer' => 'Kemendikbud',
                'level' => 'National',
                'start_date' => $now->copy()->addMonths(3)->format('Y-m-d'),
                'end_date' => $now->copy()->addMonths(3)->addDays(5)->format('Y-m-d'),
                'registration_start' => $now->copy()->addMonths(1)->format('Y-m-d'),
                'registration_end' => $now->copy()->addMonths(2)->format('Y-m-d'),
                'competition_date' => $now->copy()->addMonths(3)->format('Y-m-d'),
                'registration_link' => 'https://gemastik.kemdikbud.go.id/',
                'status' => 'upcoming', // Registration not yet open
                'verified' => true,
                'added_by' => 1,
                'period_id' => $periodId,
                'category_id' => $appDevId,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'UI/UX Design Challenge',
                'description' => 'Kompetisi desain antarmuka dan pengalaman pengguna untuk aplikasi mobile dengan tema "Inclusive Design"',
                'organizer' => 'Microsoft Indonesia',
                'level' => 'National',
                'start_date' => $now->copy()->addMonths(2)->addDays(15)->format('Y-m-d'),
                'end_date' => $now->copy()->addMonths(2)->addDays(17)->format('Y-m-d'),
                'registration_start' => $now->copy()->addMonths(1)->format('Y-m-d'),
                'registration_end' => $now->copy()->addMonths(1)->addDays(30)->format('Y-m-d'),
                'competition_date' => $now->copy()->addMonths(2)->addDays(15)->format('Y-m-d'),
                'registration_link' => 'https://microsoft.com/id-id/uiux-challenge',
                'status' => 'upcoming', // Registration not yet open
                'verified' => true,
                'added_by' => 1,
                'period_id' => $periodId,
                'category_id' => $uiuxId,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'Cyber Security Challenge',
                'description' => 'Kompetisi keamanan siber dengan fokus pada penetration testing, forensik digital, dan keamanan jaringan.',
                'organizer' => 'Badan Siber dan Sandi Negara (BSSN)',
                'level' => 'National',
                'start_date' => $now->copy()->addMonths(4)->format('Y-m-d'),
                'end_date' => $now->copy()->addMonths(4)->addDays(3)->format('Y-m-d'),
                'registration_start' => $now->copy()->addMonths(2)->format('Y-m-d'),
                'registration_end' => $now->copy()->addMonths(3)->format('Y-m-d'),
                'competition_date' => $now->copy()->addMonths(4)->format('Y-m-d'),
                'registration_link' => 'https://bssn.go.id/csc2024',
                'status' => 'upcoming', // Registration not yet open
                'verified' => true,
                'added_by' => 1,
                'period_id' => $periodId,
                'category_id' => $cyberSecurityId,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'AI Innovation Challenge',
                'description' => 'Kompetisi pengembangan solusi berbasis kecerdasan buatan untuk mengatasi tantangan di berbagai sektor industri.',
                'organizer' => 'Google Indonesia & Kementerian Riset dan Teknologi',
                'level' => 'National',
                'start_date' => $now->copy()->addMonths(5)->format('Y-m-d'),
                'end_date' => $now->copy()->addMonths(5)->addDays(2)->format('Y-m-d'),
                'registration_start' => $now->copy()->addMonths(3)->format('Y-m-d'),
                'registration_end' => $now->copy()->addMonths(4)->format('Y-m-d'),
                'competition_date' => $now->copy()->addMonths(5)->format('Y-m-d'),
                'registration_link' => 'https://ai-challenge.id/2024',
                'status' => 'upcoming', // Registration not yet open
                'verified' => true,
                'added_by' => 1,
                'period_id' => $periodId,
                'category_id' => $aiId,
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
        
        // Log the seeding completion
        $this->command->info('Created ' . count($competitions) . ' competitions with various statuses:');
        $this->command->info('- Completed: ' . count(array_filter($competitions, fn($c) => $c['status'] === 'completed')) . ' competitions');
        $this->command->info('- Active: ' . count(array_filter($competitions, fn($c) => $c['status'] === 'active')) . ' competitions');
        $this->command->info('- Upcoming: ' . count(array_filter($competitions, fn($c) => $c['status'] === 'upcoming')) . ' competitions');
    }
} 