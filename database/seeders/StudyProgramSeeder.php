<?php

namespace Database\Seeders;

use App\Models\StudyProgramModel;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class StudyProgramSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $studyPrograms = [
            [
                'name' => 'D-III Teknik Mesin',
                'code' => 'TMES_D3',
                'faculty' => 'Jurusan Teknik Mesin',
                'degree_level' => 'D-III',
                'accreditation' => 'A',
                'year_established' => 1982,
                'description' => 'Program studi D-III Teknik Mesin mempersiapkan mahasiswa untuk menjadi ahli dalam bidang perancangan, pemeliharaan, dan perbaikan mesin-mesin industri.',
            ],
            [
                'name' => 'D-III Teknologi Pemeliharaan Pesawat Udara',
                'code' => 'TPPU_D3',
                'faculty' => 'Jurusan Teknik Mesin',
                'degree_level' => 'D-III',
                'accreditation' => 'A',
                'year_established' => 1990,
                'description' => 'Program studi D-III Teknologi Pemeliharaan Pesawat Udara fokus pada perawatan dan pemeliharaan pesawat udara sesuai dengan standar internasional.',
            ],
            [
                'name' => 'D-IV Teknik Mesin Produksi dan Perawatan',
                'code' => 'TMPP_D4',
                'faculty' => 'Jurusan Teknik Mesin',
                'degree_level' => 'D-IV',
                'accreditation' => 'A',
                'year_established' => 1995,
                'description' => 'Program studi D-IV Teknik Mesin Produksi dan Perawatan menyiapkan mahasiswa untuk menguasai teknologi produksi dan perawatan mesin modern.',
            ],
            [
                'name' => 'D-IV Teknik Otomotif Elektronik',
                'code' => 'TOE_D4',
                'faculty' => 'Jurusan Teknik Mesin',
                'degree_level' => 'D-IV',
                'accreditation' => 'B',
                'year_established' => 2005,
                'description' => 'Program studi D-IV Teknik Otomotif Elektronik menyiapkan mahasiswa untuk menjadi ahli dalam bidang sistem elektronik pada kendaraan modern.',
            ],
            [
                'name' => 'S2 Terapan Rekayasa Teknologi Manufaktur',
                'code' => 'RTM_S2',
                'faculty' => 'Pascasarjana (Jurusan Teknik Mesin)',
                'degree_level' => 'S2 Terapan',
                'accreditation' => 'B',
                'year_established' => 2010,
                'description' => 'Program studi S2 Terapan Rekayasa Teknologi Manufaktur mempersiapkan mahasiswa untuk menjadi ahli dalam teknologi manufaktur tingkat lanjut.',
            ],
        
            [
                'name' => 'D-III Teknik Elektronika',
                'code' => 'TELKOM_D3',
                'faculty' => 'Jurusan Teknik Elektro',
                'degree_level' => 'D-III',
                'accreditation' => 'A',
                'year_established' => 1983,
                'description' => 'Program studi D-III Teknik Elektronika mempersiapkan mahasiswa untuk menjadi ahli dalam bidang sistem elektronika dan rangkaian elektronik.',
            ],
            [
                'name' => 'D-III Teknik Telekomunikasi',
                'code' => 'TTEL_D3',
                'faculty' => 'Jurusan Teknik Elektro',
                'degree_level' => 'D-III',
                'accreditation' => 'A',
                'year_established' => 1985,
                'description' => 'Program studi D-III Teknik Telekomunikasi fokus pada pengembangan keterampilan dalam bidang sistem komunikasi dan jaringan telekomunikasi.',
            ],
            [
                'name' => 'D-III Teknik Listrik',
                'code' => 'TLIS_D3',
                'faculty' => 'Jurusan Teknik Elektro',
                'degree_level' => 'D-III',
                'accreditation' => 'A',
                'year_established' => 1980,
                'description' => 'Program studi D-III Teknik Listrik mempersiapkan mahasiswa untuk menjadi ahli dalam bidang instalasi dan sistem kelistrikan.',
            ],
            [
                'name' => 'D-IV Teknik Elektronika',
                'code' => 'TELKOM_D4',
                'faculty' => 'Jurusan Teknik Elektro',
                'degree_level' => 'D-IV',
                'accreditation' => 'A',
                'year_established' => 1997,
                'description' => 'Program studi D-IV Teknik Elektronika menyiapkan mahasiswa untuk menjadi ahli dalam perancangan dan pengembangan sistem elektronika canggih.',
            ],
            [
                'name' => 'D-IV Sistem Kelistrikan',
                'code' => 'SKL_D4',
                'faculty' => 'Jurusan Teknik Elektro',
                'degree_level' => 'D-IV',
                'accreditation' => 'B',
                'year_established' => 2000,
                'description' => 'Program studi D-IV Sistem Kelistrikan mempersiapkan mahasiswa untuk menjadi ahli dalam bidang sistem tenaga listrik dan energi.',
            ],
            [
                'name' => 'D-IV Jaringan Telekomunikasi Digital',
                'code' => 'JTD_D4',
                'faculty' => 'Jurusan Teknik Elektro',
                'degree_level' => 'D-IV',
                'accreditation' => 'B',
                'year_established' => 2008,
                'description' => 'Program studi D-IV Jaringan Telekomunikasi Digital fokus pada teknologi jaringan digital dan infrastruktur telekomunikasi modern.',
            ],
            [
                'name' => 'S2 Terapan Teknik Elektro',
                'code' => 'TE_S2',
                'faculty' => 'Pascasarjana (Jurusan Teknik Elektro)',
                'degree_level' => 'S2 Terapan',
                'accreditation' => 'B',
                'year_established' => 2012,
                'description' => 'Program studi S2 Terapan Teknik Elektro mempersiapkan mahasiswa untuk mendalami teknologi elektro tingkat lanjut dan aplikasinya dalam industri.',
            ],
        
            [
                'name' => 'D-III Manajemen Informatika',
                'code' => 'MI_D3',
                'faculty' => 'Jurusan Teknologi Informasi',
                'degree_level' => 'D-III',
                'accreditation' => 'A',
                'year_established' => 1992,
                'description' => 'Program studi D-III Manajemen Informatika mempersiapkan mahasiswa untuk menjadi ahli dalam pengelolaan sistem informasi dan basis data.',
            ],
            [
                'name' => 'D-IV Teknik Informatika',
                'code' => 'TI_D4',
                'faculty' => 'Jurusan Teknologi Informasi',
                'degree_level' => 'D-IV',
                'accreditation' => 'A',
                'year_established' => 1998,
                'description' => 'Program studi D-IV Teknik Informatika fokus pada pengembangan perangkat lunak, analisis sistem, dan teknologi komputasi.',
            ],
            [
                'name' => 'D-IV Sistem Informasi Bisnis',
                'code' => 'SIB_D4',
                'faculty' => 'Jurusan Teknologi Informasi',
                'degree_level' => 'D-IV',
                'accreditation' => 'A',
                'year_established' => 2003,
                'description' => 'Program studi D-IV Sistem Informasi Bisnis mempersiapkan mahasiswa untuk mengembangkan dan mengelola sistem informasi dalam konteks bisnis dan organisasi.',
            ],
            [
                'name' => 'S2 Terapan Rekayasa Teknologi Informasi',
                'code' => 'RTI_S2',
                'faculty' => 'Pascasarjana (Jurusan Teknologi Informasi)',
                'degree_level' => 'S2 Terapan',
                'accreditation' => 'B',
                'year_established' => 2014,
                'description' => 'Program studi S2 Terapan Rekayasa Teknologi Informasi fokus pada penelitian dan pengembangan teknologi informasi tingkat lanjut.',
            ],
        
            [
                'name' => 'D-III Akuntansi',
                'code' => 'AKT_D3',
                'faculty' => 'Jurusan Akuntansi',
                'degree_level' => 'D-III',
                'accreditation' => 'A',
                'year_established' => 1984,
                'description' => 'Program studi D-III Akuntansi mempersiapkan mahasiswa untuk menjadi ahli dalam bidang akuntansi dan pelaporan keuangan.',
            ],
            [
                'name' => 'D-IV Akuntansi Manajemen',
                'code' => 'AKM_D4',
                'faculty' => 'Jurusan Akuntansi',
                'degree_level' => 'D-IV',
                'accreditation' => 'A',
                'year_established' => 1996,
                'description' => 'Program studi D-IV Akuntansi Manajemen fokus pada akuntansi untuk pengambilan keputusan manajemen dan pengendalian organisasi.',
            ],
            [
                'name' => 'D-IV Keuangan',
                'code' => 'KEU_D4',
                'faculty' => 'Jurusan Akuntansi',
                'degree_level' => 'D-IV',
                'accreditation' => 'B',
                'year_established' => 2005,
                'description' => 'Program studi D-IV Keuangan mempersiapkan mahasiswa untuk menjadi ahli dalam manajemen keuangan dan investasi.',
            ],
        
            [
                'name' => 'D-III Administrasi Bisnis',
                'code' => 'ADBIS_D3',
                'faculty' => 'Jurusan Administrasi Niaga',
                'degree_level' => 'D-III',
                'accreditation' => 'A',
                'year_established' => 1982,
                'description' => 'Program studi D-III Administrasi Bisnis mempersiapkan mahasiswa untuk memahami dan menerapkan prinsip-prinsip administrasi dalam konteks bisnis.',
            ],
            [
                'name' => 'D-IV Manajemen Pemasaran',
                'code' => 'MP_D4',
                'faculty' => 'Jurusan Administrasi Niaga',
                'degree_level' => 'D-IV',
                'accreditation' => 'A',
                'year_established' => 1998,
                'description' => 'Program studi D-IV Manajemen Pemasaran fokus pada strategi pemasaran, analisis pasar, dan komunikasi pemasaran terintegrasi.',
            ],
            [
                'name' => 'D-IV Bahasa Inggris untuk Komunikasi Bisnis dan Profesional',
                'code' => 'BIKBP_D4',
                'faculty' => 'Jurusan Administrasi Niaga',
                'degree_level' => 'D-IV',
                'accreditation' => 'B',
                'year_established' => 2007,
                'description' => 'Program studi D-IV Bahasa Inggris untuk Komunikasi Bisnis dan Profesional mempersiapkan mahasiswa untuk berkomunikasi secara efektif dalam konteks bisnis internasional.',
            ],
            [
                'name' => 'D-IV Usaha Perjalanan Wisata',
                'code' => 'UPW_D4',
                'faculty' => 'Jurusan Administrasi Niaga',
                'degree_level' => 'D-IV',
                'accreditation' => 'B',
                'year_established' => 2010,
                'description' => 'Program studi D-IV Usaha Perjalanan Wisata fokus pada manajemen pariwisata, hospitalitas, dan pengembangan destinasi wisata.',
            ],
        
            [
                'name' => 'D-III Teknik Kimia',
                'code' => 'TKIM_D3',
                'faculty' => 'Jurusan Teknik Kimia',
                'degree_level' => 'D-III',
                'accreditation' => 'A',
                'year_established' => 1983,
                'description' => 'Program studi D-III Teknik Kimia mempersiapkan mahasiswa untuk memahami proses-proses kimia dalam industri dan aplikasinya.',
            ],
            [
                'name' => 'D-IV Teknologi Kimia Industri',
                'code' => 'TKI_D4',
                'faculty' => 'Jurusan Teknik Kimia',
                'degree_level' => 'D-IV',
                'accreditation' => 'A',
                'year_established' => 1999,
                'description' => 'Program studi D-IV Teknologi Kimia Industri fokus pada pengembangan proses kimia dan teknologi untuk aplikasi industri.',
            ],
        
            [
                'name' => 'D-III Teknik Sipil',
                'code' => 'TSIP_D3',
                'faculty' => 'Jurusan Teknik Sipil',
                'degree_level' => 'D-III',
                'accreditation' => 'A',
                'year_established' => 1980,
                'description' => 'Program studi D-III Teknik Sipil mempersiapkan mahasiswa untuk menjadi ahli dalam bidang konstruksi bangunan dan infrastruktur.',
            ],
            [
                'name' => 'D-III Teknologi Pertambangan',
                'code' => 'TPER_D3',
                'faculty' => 'Jurusan Teknik Sipil',
                'degree_level' => 'D-III',
                'accreditation' => 'B',
                'year_established' => 1994,
                'description' => 'Program studi D-III Teknologi Pertambangan fokus pada teknik eksplorasi dan ekstraksi sumber daya mineral.',
            ],
            [
                'name' => 'D-III Teknologi Konstruksi Jalan, Jembatan, dan Bangunan Air',
                'code' => 'TKJJBA_D3',
                'faculty' => 'Jurusan Teknik Sipil',
                'degree_level' => 'D-III',
                'accreditation' => 'B',
                'year_established' => 2003,
                'description' => 'Program studi D-III Teknologi Konstruksi Jalan, Jembatan, dan Bangunan Air mempersiapkan mahasiswa untuk menguasai teknologi konstruksi infrastruktur transportasi dan bangunan air.',
            ],
            [
                'name' => 'D-IV Manajemen Rekayasa Konstruksi',
                'code' => 'MRK_D4',
                'faculty' => 'Jurusan Teknik Sipil',
                'degree_level' => 'D-IV',
                'accreditation' => 'A',
                'year_established' => 2001,
                'description' => 'Program studi D-IV Manajemen Rekayasa Konstruksi fokus pada manajemen proyek konstruksi dan teknik rekayasa bangunan.',
            ],
        ];

        foreach ($studyPrograms as $program) {
            StudyProgramModel::create($program);
        }
    }
}
